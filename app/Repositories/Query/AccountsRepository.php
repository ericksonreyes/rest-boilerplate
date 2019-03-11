<?php
/**
 * Created by PhpStorm.
 * User: Erickson Reyes
 * Date: 13/12/2018
 * Time: 12:54 PM
 */

namespace App\Repositories\Query;

interface AccountsRepository
{
    /**
     * @param string $accountId
     * @return Account
     */
    public function findById(string $accountId): ?Account;
}
