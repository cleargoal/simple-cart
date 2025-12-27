<?php

namespace App\Exceptions\Cart;

use Exception;

class InvalidQuantityException extends Exception
{
    public function __construct(string $message = 'Quantity must be greater than 0')
    {
        parent::__construct($message);
    }
}
