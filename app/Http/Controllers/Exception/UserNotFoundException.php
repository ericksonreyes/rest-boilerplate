<?php
/**
 * Created by PhpStorm.
 * User: ericksonreyes
 * Date: 2019-01-03
 * Time: 20:15
 */

namespace App\Http\Controllers\Exception;

use Rest\Shared\Exception\EntityNotFoundException;

class UserNotFoundException extends EntityNotFoundException
{
    public function __construct()
    {
        parent::__construct('User not found.');
    }
}
