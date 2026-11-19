<?php

namespace Extenbox\Multipay\Drivers\Zarinpal;

use Extenbox\Multipay\Abstracts\Driver;
use Extenbox\Multipay\Contracts\DriverInterface;
use Extenbox\Multipay\Exceptions\InvalidPaymentException;
use Extenbox\Multipay\Exceptions\PurchaseFailedException;
use Extenbox\Multipay\Contracts\ReceiptInterface;
use Extenbox\Multipay\Drivers\Zarinpal\Strategies\Normal;
use Extenbox\Multipay\Drivers\Zarinpal\Strategies\Sandbox;
use Extenbox\Multipay\Drivers\Zarinpal\Strategies\Zaringate;
use Extenbox\Multipay\Exceptions\DriverNotFoundException;
use Extenbox\Multipay\Invoice;
use Extenbox\Multipay\RedirectionForm;

class Zarinpal extends Driver
{
    /**
     * Strategies map.
     */
    public static array $strategies = [
        'normal' => Normal::class,
        'sandbox' => Sandbox::class,
        'zaringate' => Zaringate::class,
    ];

    /**
     * Current strategy instance.
     */
    protected DriverInterface $strategy;

    /**
     * Zarinpal constructor.
     * Construct the class with the relevant settings.
     *
     * @param $settings
     */
    public function __construct(Invoice $invoice, array|object $settings)
    {
        $this->invoice = $invoice;
        $this->settings = (object) $settings;
        $this->strategy = $this->getFreshStrategyInstance($this->invoice, $this->settings);
    }

    /**
     * Purchase Invoice.
     *
     * @return string
     *
     * @throws PurchaseFailedException
     * @throws \SoapFault
     */
    public function purchase() : string|int|null
    {
        return $this->strategy->purchase();
    }

    /**
     * Pay the Invoice
     */
    public function pay() : RedirectionForm
    {
        return $this->strategy->pay();
    }

    /**
     * Verify payment
     *
     *
     * @throws InvalidPaymentException
     * @throws \SoapFault
     */
    public function verify() : ReceiptInterface
    {
        return $this->strategy->verify();
    }

    /**
     * Get zarinpal payment's strategy according to config's mode.
     *
     * @param $settings
     */
    protected function getFreshStrategyInstance(Invoice $invoice, array|object $settings) : DriverInterface
    {
        $strategy = static::$strategies[$this->getMode()] ?? null;

        if (! $strategy) {
            $this->strategyNotFound();
        }

        return new $strategy($invoice, $settings);
    }

    protected function strategyNotFound(): never
    {
        $message = sprintf(
            'Zarinpal payment mode not found (check your settings), valid modes are: %s',
            implode(',', array_keys(static::$strategies))
        );

        throw new DriverNotFoundException($message);
    }

    /**
     * Retrieve payment mode.
     */
    protected function getMode() : string
    {
        return strtolower($this->settings->mode);
    }
}
