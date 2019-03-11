<?php
/**
 * Created by PhpStorm.
 * User: Erickson Reyes
 * Date: 12/12/2018
 * Time: 3:14 PM
 */

namespace App\Http\Controllers\Exception;

use Rest\Shared\Exception\EntityNotFoundException;

final class LeadNotFoundException extends EntityNotFoundException
{

    public function __construct()
    {
        parent::__construct('Lead not found.');
    }
}
