<?php

namespace App\Http\Controllers\Exception;

use Rest\Shared\Exception\ConflictException;

final class DuplicateLeadIdException extends ConflictException
{
    public function __construct()
    {
        parent::__construct('Lead Id is already in use.');
    }
}
