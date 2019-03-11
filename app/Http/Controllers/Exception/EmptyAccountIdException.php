<?php
/**
 * Created by PhpStorm.
 * User: erickson
 * Date: 07/12/2018
 * Time: 2:14 PM
 */

namespace App\Http\Controllers\Exception;

use InvalidArgumentException;

final class EmptyAccountIdException extends InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct('Account Id is required.');
    }
}
