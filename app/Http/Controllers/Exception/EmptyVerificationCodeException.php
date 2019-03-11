<?php

namespace App\Http\Controllers\Exception;

use InvalidArgumentException;

final class EmptyVerificationCodeException extends InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct('Verification code is required. ');
    }
}
