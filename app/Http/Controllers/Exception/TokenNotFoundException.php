<?php
/**
 * Created by PhpStorm.
 * User: Erickson Reyes
 * Date: 11/12/2018
 * Time: 6:31 PM
 */

namespace App\Http\Controllers\Exception;

use DomainException;
use Rest\Shared\Exception\EntityNotFoundException;


final class TokenNotFoundException extends EntityNotFoundException
{
    public function __construct()
    {
        parent::__construct('Token not found.');
    }
}