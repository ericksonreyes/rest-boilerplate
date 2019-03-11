<?php
/**
 * Created by PhpStorm.
 * User: erickson
 * Date: 07/12/2018
 * Time: 2:27 PM
 */

namespace App\Http\Controllers\Exception;

use InvalidArgumentException;

final class EmptyPasswordException extends InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct('Password is required.');
    }
}
