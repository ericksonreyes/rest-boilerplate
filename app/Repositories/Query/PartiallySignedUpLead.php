<?php
/**
 * Created by PhpStorm.
 * User: Erickson Reyes
 * Date: 11/12/2018
 * Time: 5:47 PM
 */

namespace App\Repositories\Query;


interface PartiallySignedUpLead
{

    /**
     * @return string
     */
    public function leadId(): string;

    /**
     * @return string
     */
    public function email(): string;

    /**
     * @return string
     */
    public function token(): string;

    /**
     * @return string
     */
    public function code(): string;

    /**
     * @return int
     */
    public function signedUpOn(): int;
}