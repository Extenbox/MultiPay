<?php

namespace Extenbox\Multipay\Tests\Mocks;

use Extenbox\Multipay\Payment;
use Extenbox\Multipay\Invoice;

class MockPaymentManager extends Payment
{
    public function getDriver() : string
    {
        return $this->driver;
    }

    public function getConfig() : array
    {
        return $this->config;
    }

    public function getCallbackUrl() : string
    {
        return $this->settings['callbackUrl'];
    }

    public function getInvoice(): Invoice
    {
        return $this->invoice;
    }

    public function getCurrentDriverSetting(): array
    {
        return $this->settings;
    }
}
