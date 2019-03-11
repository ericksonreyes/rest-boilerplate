<?php

namespace App\Http\Controllers\Exception;

use InvalidArgumentException;

final class InvalidEmailException extends InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct('Your email address is not in the right format.');
    }
}
