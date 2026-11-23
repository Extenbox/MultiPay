<?php

namespace Extenbox\Multipay\Drivers\SEP;

use GuzzleHttp\Client;
use Extenbox\Multipay\Abstracts\Driver;
use Extenbox\Multipay\Exceptions\InvalidPaymentException;
use Extenbox\Multipay\Exceptions\PurchaseFailedException;
use Extenbox\Multipay\Contracts\ReceiptInterface;
use Extenbox\Multipay\Invoice;
use Extenbox\Multipay\Receipt;
use Extenbox\Multipay\RedirectionForm;
use Extenbox\Multipay\Request;
use Extenbox\Multipay\Traits\HasIranCurrency;

class SEP extends Driver
{
    use HasIranCurrency;

    /**
     * SEP HTTP Client.
     */
    protected Client $client;

    /**
     * SEP constructor.
     * Construct the class with the relevant settings.
     *
     * @param $settings
     */
    public function __construct(Invoice $invoice, array|object $settings)
    {
        $this->invoice($invoice);
        $this->settings = (object)$settings;
        $this->client = new Client([
            'curl' => [CURLOPT_SSL_CIPHER_LIST => 'DEFAULT@SECLEVEL=1'],
        ]);
    }

    /**
     * Purchase Invoice.
     *
     * @return string
     *
     * @throws PurchaseFailedException
     * @throws \SoapFault
     */
    public function purchase(): string|int|null
    {
        $data = [
            'Action' => 'Token',
            'TerminalId' => $this->settings->terminalId,
            'Amount' => $this->convertAmountToRial($this->invoice->getAmount()),
            'ResNum' => $this->invoice->getUuid(),
            'RedirectUrl' => $this->settings->callbackUrl,
            'CellNumber' => $this->invoice->getDetail('mobile') ?? $this->invoice->getDetail('phone') ?? '',
        ];

        $response = $this->client->post(
            $this->settings->apiGetToken,
            [
                'json' => $data,
                'headers' => [
                    'Accept' => 'application/json',
                ],
            ]
        );

        $responseStatus = $response->getStatusCode();

        if ($responseStatus !== 200) { // if something has done in a wrong way
            $this->purchaseFailed(0);
        }

        $jsonBody = $response->getBody()->getContents();
        $responseData = json_decode($jsonBody, true);

        if (!isset($responseData['token'])) {
            $errorCode = $responseData['errorCode'] ?? $responseData['status'] ?? 0;
            $this->purchaseFailed($errorCode);
        }

        // set transaction id
        $this->invoice->transactionId($responseData['token']);

        // return the transaction's id
        return $this->invoice->getTransactionId();
    }

    /**
     * Pay the Invoice
     */
    public function pay(): RedirectionForm
    {
        $payUrl = $this->settings->apiPaymentUrl;

        return $this->redirectWithForm(
            $payUrl,
            [
                'Token' => $this->invoice->getTransactionId(),
            ]
        );
    }

    /**
     * Verify payment
     *
     *
     * @throws InvalidPaymentException
     * @throws \SoapFault
     * @throws PurchaseFailedException
     */
    public function verify(): ReceiptInterface
    {
        $status = Request::input('Status');
        $state = Request::input('State');
        $statusValue = $status ?? $state;
        if ($statusValue !== null && $statusValue !== '' && is_numeric($statusValue) && (int) $statusValue !== 2) {
            $this->purchaseFailed((int) $statusValue);
        }

        $refNum = Request::input('RefNum');
        if ($refNum === null || $refNum === '') {
            $this->notVerified(0);
        }

        $data = [
            'TerminalNumber' => $this->settings->terminalId,
            'RefNum' => $refNum,
            'CellNumber' => $this->invoice->getDetail('mobile') ?? $this->invoice->getDetail('phone') ?? '',
            'NationalCode' => $this->invoice->getDetail('national_code') ?? '',
            'IgnoreNationalcode' => true,
        ];

        $token = Request::input('Token');
        if ($token !== null && $token !== '' && $this->invoice->getTransactionId() !== $token) {
            $this->notVerified(-108);
        }

        $response = $this->client->post(
            $this->settings->apiVerificationUrl,
            [
                'json' => $data,
                'headers' => [
                    'Accept' => 'application/json',
                ],
            ]
        );

        if ($response->getStatusCode() !== 200) {
            $this->notVerified(0);
        }

        $jsonData = $response->getBody()->getContents();
        $responseData = json_decode($jsonData, true);

        if ($responseData['ResultCode'] != 0) {
            $this->notVerified($responseData['ResultCode']);
        }

        $transactionDetail = $responseData['TransactionDetail'];

        $verifiedAmount = $this->convertAmountToRial($this->invoice->getAmount());
        if ($verifiedAmount !== $transactionDetail['AffectiveAmount']) {
            $this->notVerified(-107);
        }

        $receipt = $this->createReceipt($data['RefNum']);
        $receipt->detail([
            'traceNo' => $transactionDetail['StraceNo'],
            'referenceNo' => $transactionDetail['RRN'],
            'transactionId' => Request::input('ResNum'),
            'RefNum' => $transactionDetail['RefNum'],
            'cardNo' => $transactionDetail['MaskedPan'],
        ]);

        // Add additional data specific for SEP gateway
        $receipt->detail([
            'TerminalNumber' => $transactionDetail['TerminalNumber'],
            'OrginalAmount' => $transactionDetail['OrginalAmount'],
            'AffectiveAmount' => $transactionDetail['AffectiveAmount'],
            'StraceDate' => $transactionDetail['StraceDate'],
            // SEP documents are not up-to-date. This will fix different
            // between variable name in docs and actual returned values.
            'Amount' => $transactionDetail['OrginalAmount'],
        ]);

        return $receipt;
    }

    /**
     * Generate the payment's receipt
     *
     * @param $referenceId
     */
    protected function createReceipt(string|int $referenceId): Receipt
    {
        return new Receipt('saman', $referenceId);
    }

    /**
     * Trigger an exception
     *
     * @param $status
     *
     * @throws PurchaseFailedException
     */
    protected function purchaseFailed(int|string|null $status) : never
    {
        $translations = [
            1 => ' تراکنش توسط خریدار لغو شده است.',
            2 => 'پرداخت با موفقیت انجام شد.',
            3 => 'پرداخت انجام نشد.',
            4 => 'کاربر در بازه زمانی تعیین شده پاسخی ارسال نکرده است.',
            5 => 'پارامترهای ارسالی نامعتبر است.',
            8 => 'آدرس سرور پذیرنده نامعتبر است.',
            9 => 'رمز کارت 3 مرتبه اشتباه وارد شده است در نتیجه کارت غیر فعال خواهد شد.',
            10 => 'توکن ارسال شده یافت نشد.',
            11 => 'با این شماره ترمینال فقط تراکنش های توکنی قابل پرداخت هستند.',
            12 => 'شماره ترمینال ارسال شده یافت نشد.',
        ];

        if (array_key_exists($status, $translations)) {
            throw new PurchaseFailedException($translations[$status]);
        }
        throw new PurchaseFailedException('خطای ناشناخته ای رخ داده است.');
    }

    /**
     * Trigger an exception
     *
     * @param $status
     *
     * @throws InvalidPaymentException
     */
    private function notVerified(int|string|null $status): void
    {
        $translations = [
            -2 => ' تراکنش یافت نشد.',
            -6 => 'بیش از 30 دقیقه از زمان اجرای تراکنش گذشته است.',
            2 => 'کاربر در بازه زمانی تعیین شده پاسخی ارسال نکرده است.',
            -104 => 'پارامترهای ارسالی نامعتبر است.',
            -105 => 'آدرس سرور پذیرنده نامعتبر است.',
            -106 => 'رمز کارت 3 مرتبه اشتباه وارد شده است در نتیجه کارت غیر فعال خواهد شد.',
            -107 => 'مبلغ برگشتی با مبلغ فاکتور همخوانی ندارد.',
            -108 => 'اطلاعات پرداخت با فاکتور همخوانی ندارد.',
        ];

        if (array_key_exists($status, $translations)) {
            throw new InvalidPaymentException($translations[$status], (int)$status);
        }
        throw new InvalidPaymentException('خطای ناشناخته ای رخ داده است.', (int)$status);
    }
}
