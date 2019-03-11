<?php
/**
 * Created by PhpStorm.
 * User: erickson
 * Date: 07/12/2018
 * Time: 2:21 PM
 */

namespace App\Http\Controllers\Exception;

use InvalidArgumentException;

final class EmptyUserIdException extends InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct('User id is required.');
    }
}
