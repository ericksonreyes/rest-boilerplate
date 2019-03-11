<?php
/**
 * Created by PhpStorm.
 * User: erickson
 * Date: 07/12/2018
 * Time: 2:13 PM
 */

namespace App\Http\Controllers\Exception;

use Rest\Shared\Exception\ConflictException;

final class DuplicateAccountIdException extends ConflictException
{
    public function __construct()
    {
        parent::__construct('Account Id already in use.');
    }
}
