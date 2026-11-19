<?php

namespace Extenbox\Multipay;

use Extenbox\Multipay\Abstracts\Receipt as ReceiptAbstract;
use Extenbox\Multipay\Traits\HasDetail;

class Receipt extends ReceiptAbstract
{
    use HasDetail;

    /**
     * Add given value into details
     */
    public function __set(string $name, mixed $value) : void
    {
        $this->detail($name, $value);
    }

    /**
     * Retrieve given value from details
     */
    public function __get(string $name) : mixed
    {
        return $this->getDetail($name);
    }
}
