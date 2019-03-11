<?php

namespace App\Http\Controllers\Exception;

use InvalidArgumentException;

final class InvalidVerificationCodeException extends InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct('Invalid verification code.');
    }
}
