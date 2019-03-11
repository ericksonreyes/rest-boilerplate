<?php
/**
 * Created by PhpStorm.
 * User: erickson
 * Date: 07/12/2018
 * Time: 1:46 PM
 */

namespace Http\Controllers\Exception;

use Rest\Shared\Exception\ConflictException;

final class EmailAlreadyInUseException extends ConflictException
{
    public function __construct()
    {
        parent::__construct('This email is already in use. Do you want to sign in instead?');
    }
}
