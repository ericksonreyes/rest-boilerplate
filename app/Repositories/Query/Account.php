<?php
/**
 * Created by PhpStorm.
 * User: Erickson Reyes
 * Date: 13/12/2018
 * Time: 12:55 PM
 */

namespace App\Repositories\Query;

interface Account
{
    /**
     * @return string
     */
    public function accountId(): string;

    /**
     * @return string
     */
    public function email(): string;

    /**
     * @return int|null
     */
    public function closedOn(): ?int;

    /**
     * @return null|string
     */
    public function closedBy(): ?string;

    /**
     * @return int
     */
    public function createdOn(): int;
}
