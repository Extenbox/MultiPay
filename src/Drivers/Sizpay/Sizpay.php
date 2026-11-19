<?php

namespace Extenbox\Multipay\Drivers\Sizpay;

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
use SoapClient;

class Sizpay extends Driver
{
    use HasIranCurrency;

    /**
     * Nextpay Client.
     */
    protected Client $client;

    /**
     * Nextpay constructor.
     * Construct the class with the relevant settings.
     *
     * @param $settings
     */
    public function __construct(Invoice $invoice, array|object $settings)
    {
        $this->invoice($invoice);
        $this->settings = (object) $settings;
        $this->client = new Client();
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
        $client = new SoapClient($this->settings->apiPurchaseUrl);
        $response = $client->GetToken2([
            'MerchantID' => $this->settings->merchantId,
            'TerminalID' => $this->settings->terminal,
            'UserName' => $this->settings->username,
            'Password' => $this->settings->password,
            'Amount' => $this->convertAmountToRial($this->invoice->getAmount()),
            'OrderID' => time(),
            'ReturnURL' => $this->settings->callbackUrl,
            'InvoiceNo' => time(),
            'DocDate' => '',
            'ExtraInf' => time(),
            'AppExtraInf' => '',
            'SignData' => $this->settings->SignData
        ])->GetToken2Result;
        $result = json_decode($response);

        if (! isset($result->ResCod) || ! in_array($result->ResCod, ['0', '00'])) {
            // error has happened
            $message = $result->Message ?? 'خطای ناشناخته رخ داده';
            throw new PurchaseFailedException($message);
        }

        $this->invoice->transactionId($result->Token);

        // return the transaction's id
        return $this->invoice->getTransactionId();
    }

    /**
     * Pay the Invoice
     */
    public function pay() : RedirectionForm
    {
        $payUrl = $this->settings->apiPaymentUrl;

        return $this->redirectWithForm(
            $payUrl,
            [
                'MerchantID' => $this->settings->merchantId,
                'TerminalID' => $this->settings->terminal,
                'Token' => $this->invoice->getTransactionId(),
                'SignData' => $this->settings->SignData
            ],
            'POST'
        );
    }

    /**
     * Verify payment
     *
     *
     * @throws InvalidPaymentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function verify() : ReceiptInterface
    {
        $resCode = Request::input('ResCod');
        if (! in_array($resCode, ['0', '00'])) {
            $message = 'پرداخت توسط کاربر لغو شد';
            throw new InvalidPaymentException($message);
        }

        $data = [
            'MerchantID'  => $this->settings->merchantId,
            'TerminalID'  => $this->settings->terminal,
            'UserName'    => $this->settings->username,
            'Password'    => $this->settings->password,
            'Token'       => Request::input('Token'),
            'SignData'    => $this->settings->SignData
        ];

        $client = new SoapClient($this->settings->apiVerificationUrl);
        $response = $client->Confirm2($data)->Confirm2Result;
        $result = json_decode($response);

        if (! isset($result->ResCod) || ! in_array($result->ResCod, ['0', '00'])) {
            $message = $result->Message ?? 'خطا در انجام عملیات رخ داده است';
            throw new InvalidPaymentException($message, (int)($result->ResCod ?? 0));
        }

        return $this->createReceipt($result->RefNo);
    }

    /**
     * Generate the payment's receipt
     *
     * @param $resCode
     */
    protected function createReceipt(string|int $resCode): Receipt
    {
        return new Receipt('sizpay', $resCode);
    }
}
