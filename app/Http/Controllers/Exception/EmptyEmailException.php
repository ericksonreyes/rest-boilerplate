<?php
/**
 * Created by PhpStorm.
 * User: erickson
 * Date: 07/12/2018
 * Time: 1:56 PM
 */

namespace App\Http\Controllers\Exception;

use InvalidArgumentException;

final class EmptyEmailException extends InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct('E-mail is required.');
    }
}
