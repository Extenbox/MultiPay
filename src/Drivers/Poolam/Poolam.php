<?php

namespace Extenbox\Multipay\Drivers\Poolam;

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

class Poolam extends Driver
{
    use HasIranCurrency;

    /**
     * Poolam Client.
     */
    protected Client $client;

    /**
     * Poolam constructor.
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
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function purchase(): string|int|null
    {
        $amount = $this->convertAmountToRial($this->invoice->getAmount());

        $data = [
            'api_key' => $this->settings->merchantId,
            'amount' => $amount,
            'return_url' => $this->settings->callbackUrl,
        ];

        $response = $this->client->request(
            'POST',
            $this->settings->apiPurchaseUrl,
            [
                'form_params' => $data,
                'http_errors' => false,
            ]
        );
        $body = json_decode($response->getBody()->getContents(), true);

        if (empty($body['status']) || $body['status'] != 1) {
            // some error has happened
            throw new PurchaseFailedException($body['status']);
        }

        $this->invoice->transactionId($body['invoice_key']);

        // return the transaction's id
        return $this->invoice->getTransactionId();
    }

    /**
     * Pay the Invoice
     */
    public function pay() : RedirectionForm
    {
        $payUrl = $this->settings->apiPaymentUrl.$this->invoice->getTransactionId();

        return $this->redirectWithForm($payUrl, [], 'GET');
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
        $data = [
            'api_key' => $this->settings->merchantId,
        ];

        $transactionId = $this->invoice->getTransactionId() ?? Request::input('invoice_key');
        $url = $this->settings->apiVerificationUrl.$transactionId;

        $response = $this->client->request(
            'POST',
            $url,
            ['form_params' => $data, 'http_errors' => false]
        );
        $body = json_decode($response->getBody()->getContents(), true);

        if (empty($body['status']) || $body['status'] != 1) {
            $message = $body['errorDescription'] ?? null;

            $this->notVerified($message, $body['status']);
        }

        return $this->createReceipt($body['bank_code']);
    }

    /**
     * Generate the payment's receipt
     *
     * @param $referenceId
     */
    protected function createReceipt(string|int $referenceId): Receipt
    {
        return new Receipt('poolam', $referenceId);
    }

    /**
     * Trigger an exception
     *
     * @param $message
     * @throws InvalidPaymentException
     */
    private function notVerified(string|null $message, int|string|null $status): void
    {
        if (empty($message)) {
            throw new InvalidPaymentException('خطای ناشناخته ای رخ داده است.', (int)$status);
        }
        throw new InvalidPaymentException($message, (int)$status);
    }
}
