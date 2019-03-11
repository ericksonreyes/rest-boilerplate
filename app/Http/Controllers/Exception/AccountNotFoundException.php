<?php
/**
 * Created by PhpStorm.
 * User: Erickson Reyes
 * Date: 10/12/2018
 * Time: 6:53 PM
 */

namespace App\Http\Controllers\Exception;

use Rest\Shared\Exception\EntityNotFoundException;

final class AccountNotFoundException extends EntityNotFoundException
{
    public function __construct()
    {
        parent::__construct('Account not found.');
    }
}
