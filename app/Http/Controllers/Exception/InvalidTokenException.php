<?php
/**
 * Created by PhpStorm.
 * User: Erickson Reyes
 * Date: 11/12/2018
 * Time: 6:27 PM
 */

namespace App\Http\Controllers\Exception;

use Rest\Shared\Exception\AuthenticationFailureException;


final class InvalidTokenException extends AuthenticationFailureException
{
    public function __construct()
    {
        parent::__construct("This token doesn't belong to you.");
    }
}