<?php


namespace Extenbox\Multipay\Tests\Drivers;

use Extenbox\Multipay\Abstracts\Driver;
use Extenbox\Multipay\Contracts\ReceiptInterface;
use Extenbox\Multipay\Invoice;
use Extenbox\Multipay\Receipt;
use Extenbox\Multipay\RedirectionForm;
use Extenbox\Multipay\Traits\HasIranCurrency;

class BarDriver extends Driver
{
    use HasIranCurrency;
    public const DRIVER_NAME = 'bar';
    public const TRANSACTION_ID = 'random_transaction_id';
    public const REFERENCE_ID = 'random_reference_id';

    public function __construct(Invoice $invoice, array|object $settings)
    {
        $this->invoice($invoice);
        $this->settings = (object) $settings;
    }

    public function purchase() : string|int|null
    {
        return static::TRANSACTION_ID;
    }

    public function pay(): RedirectionForm
    {
        return $this->redirectWithForm('/', [
            'amount' => $this->invoice->getAmount()
        ], 'GET');
    }

    public function verify(): ReceiptInterface
    {
        return new Receipt(static::DRIVER_NAME, static::REFERENCE_ID);
    }

    public function normalizePrice(int|float $price): int|float
    {
        return $this->convertAmountToRial($price);
    }
}
