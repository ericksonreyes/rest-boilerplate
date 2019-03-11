<?php
/**
 * Created by PhpStorm.
 * User: Erickson Reyes
 * Date: 11/12/2018
 * Time: 6:15 PM
 */

namespace App\Http\Controllers\Exception;

use Rest\Shared\Exception\MissingEntityException;


final class ExpiredTokenException extends MissingEntityException
{
    public function __construct()
    {
        parent::__construct('Token is already expired.');
    }
}