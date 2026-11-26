<?php

namespace Extenbox\Multipay\Traits;

use Extenbox\Multipay\Constants\IranCurrency;
use InvalidArgumentException;

trait HasIranCurrency
{
    /**
     * Convert the given amount to rial.
     */
    protected function convertAmountToRial(int|float $price): int|float
    {
        $currency = $this->settings->currency ?? null;

        if (is_string($currency)) {
            $currency = match (strtoupper(trim($currency))) {
                'TOMAN' => IranCurrency::TOMAN,
                'RIAL' => IranCurrency::RIAL,
                default => null,
            };
        }

        if (! $currency instanceof IranCurrency) {
            throw new InvalidArgumentException(
                'Currency must be IranCurrency::TOMAN, IranCurrency::RIAL, "TOMAN", or "RIAL".'
            );
        }

        return $price * $currency->ratio();
    }

    /**
     * Convert the given amount to toman.
     */
    protected function convertAmountToToman(int|float $price): int|float
    {
        return $this->convertAmountToRial($price) / 10;
    }
}
