<?php
/**
 * Created by PhpStorm.
 * User: Erickson Reyes
 * Date: 12/12/2018
 * Time: 3:15 PM
 */

namespace App\Http\Controllers\Exception;

use Rest\Shared\Exception\MissingEntityException;

final class DeletedUserException extends MissingEntityException
{
    public function __construct()
    {
        parent::__construct('User was deleted.');
    }
}
