<?php
/**
 * Created by PhpStorm.
 * User: erickson
 * Date: 07/12/2018
 * Time: 2:19 PM
 */

namespace App\Http\Controllers\Exception;

use InvalidArgumentException;

final class EmptyLeadIdException extends InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct('Lead Id is required.');
    }
}
