<?php

namespace Extenbox\Multipay\Drivers\Aqayepardakht;

use GuzzleHttp\Client;
use Extenbox\Multipay\Abstracts\Driver;
use Extenbox\Multipay\Contracts\ReceiptInterface;
use Extenbox\Multipay\Exceptions\InvalidPaymentException;
use Extenbox\Multipay\Exceptions\PurchaseFailedException;
use Extenbox\Multipay\Invoice;
use Extenbox\Multipay\Receipt;
use Extenbox\Multipay\RedirectionForm;
use Extenbox\Multipay\Request;
use Extenbox\Multipay\Traits\HasIranCurrency;

class Aqayepardakht extends Driver
{
    use HasIranCurrency;

    /**
     * Aqayepardakht Client.
     */
    protected Client $client;

    const PAYMENT_STATUS_FAILED = 'error';
    const PAYMENT_STATUS_OK = 'success';

    public function __construct(Invoice $invoice, array|object $settings)
    {
        $this->invoice($invoice);
        $this->settings = (object) $settings;
        $this->client = new Client();
    }

    /**
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Extenbox\Multipay\Exceptions\PurchaseFailedException
     */
    public function purchase(): string|int|null
    {
        $data = [
            'pin' => $this->settings->mode === 'normal' ? $this->settings->pin : 'sandbox',
            'amount' => $this->convertAmountToToman($this->invoice->getAmount()),
            'callback' => $this->settings->callbackUrl,
            'invoice_id' => $this->invoice->getDetail('invoice_id') ?? $this->settings->invoice_id ?? null,
            'mobile' => $this->invoice->getDetail('mobile') ?? $this->invoice->getDetail('phone') ?? $this->settings->mobile ?? null,
            'email' => $this->invoice->getDetail('email') ?? $this->settings->email ?? null,
        ];

        // Aqayepardakht API v2 expects form-data (see official Postman collection).
        $data = array_filter($data, static fn ($value) => $value !== null && $value !== '');

        $response = $this->client
            ->request(
                'POST',
                $this->settings->apiPurchaseUrl,
                [
                    'form_params' => $data,
                    'headers' => [
                        'Accept' => 'application/json',
                    ],
                    'http_errors' => false,
                ]
            );

        $responseBody = json_decode($response->getBody()->getContents(), true);

        if ($responseBody['status'] !== self::PAYMENT_STATUS_OK) {
            throw new PurchaseFailedException($this->getErrorMessage($responseBody['code']));
        }

        $this->invoice->transactionId($responseBody['transid']);

        return $this->invoice->getTransactionId();
    }

    public function pay(): RedirectionForm
    {
        $url = $this->settings->mode === 'normal' ? $this->settings->apiPaymentUrl : $this->settings->apiPaymentUrlSandbox;
        $url .= $this->invoice->getTransactionId();

        return $this->redirectWithForm($url, [], 'GET');
    }

    /**
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Extenbox\Multipay\Exceptions\InvalidPaymentException
     */
    public function verify(): ReceiptInterface
    {
        $tracking_number = Request::input('tracking_number');
        $transid = Request::input('transid');
        if ($tracking_number === null || $tracking_number === '' || $transid === '' || $transid === null) {
            $this->notVerified('پرداخت ناموفق.');
        }
        $data = [
            'pin' => $this->settings->pin,
            'amount' => $this->convertAmountToToman($this->invoice->getAmount()),
            'transid' => $transid
        ];
        $response = $this->client
            ->request(
                'POST',
                $this->settings->apiVerificationUrl,
                [
                    'form_params' => $data,
                    'headers' => [
                        'Accept' => 'application/json',
                    ],
                    'http_errors' => false,
                ]
            );

        $responseBody = json_decode($response->getBody()->getContents(), true);

        if ($responseBody['status'] !== self::PAYMENT_STATUS_OK) {
            if (isset($responseBody['code'])) {
                $message = $this->getErrorMessage($responseBody['code']);
            }

            $this->notVerified($message ?? '', $responseBody['code']);
        }
        return $this->createReceipt($tracking_number);
    }

    /**
     * Generate the payment's receipt
     *
     * @param $referenceId
     */
    protected function createReceipt(string|int $referenceId): Receipt
    {
        return new Receipt('aqayepardakht', $referenceId);
    }

    /**
     * @param $message
     * @throws \Extenbox\Multipay\Exceptions\InvalidPaymentException
     */
    protected function notVerified(string|null $message, int|string $status = 0) : never
    {
        if (empty($message)) {
            throw new InvalidPaymentException('خطای ناشناخته ای رخ داده است.', (int)$status);
        }
        throw new InvalidPaymentException($message, (int)$status);
    }

    /**
     * @param $code
     */
    protected function getErrorMessage(int|string|null $code): string
    {
        $code = (int)$code;
        return match ($code) {
            -1 => 'مبلغ نباید خالی باشد.',
            -2 => 'کد پین درگاه نمیتواند خالی باشد.',
            -3 => 'آدرس بازگشت نمیتواند خالی باشد.',
            -4 => 'مبلغ وارد شده اشتباه است.',
            -5 => 'مبلع باید بین 100 تومان تا 50 میلیون تومان باشد.',
            -6 => 'کد پین وارد شده اشتباه است.',
            -7 => 'کد تراکنش نمیتواند خالی باشد.',
            -8 => 'تراکنش مورد نظر وجود ندارد.',
            -9 => 'کد پین درگاه با درگاه تراکنش مطابقت ندارد.',
            -10 => 'مبلغ با مبلغ تراکنش مطابقت ندارد.',
            -11 => 'درگاه در انتظار تایید و یا غیرفعال است.',
            -12 => 'امکان ارسال درخواست برای این پذیرنده وجود ندارد.',
            -13 => 'شماره کارت باید 16 رقم چسبیده بهم باشد.',
            -14 => 'درگاه برروی سایت دیگری درحال استفاده است.',
            -15 => 'آدرس کال بک ارسال شده با دامنه تایید شده درگاه پرداخت مغایرت دارد.',
            -16 => 'ارجاع دهنده نامعتبر است (Referrer ارسال نشده است).',
            -17 => 'مقدار ارسال شده برای پارامتر callback_method باید POST یا GET باشد.',
            0 => 'پرداخت انجام نشد.',
            1 => 'پرداخت با موفقیت انجام شد.',
            2 => 'تراکنش قبلا وریفای شده است.',
            default => 'خطای نامشخص.',
        };
    }
}
