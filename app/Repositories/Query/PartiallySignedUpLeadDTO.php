<?php
/**
 * Created by PhpStorm.
 * User: Erickson Reyes
 * Date: 11/12/2018
 * Time: 6:00 PM
 */

namespace App\Repositories\Query;


class PartiallySignedUpLeadDTO implements PartiallySignedUpLead
{
    /**
     * @return string
     */
    private $leadId;

    /**
     * @return string
     */
    private $email;

    /**
     * @return string
     */
    private $token;

    /**
     * @return string
     */
    private $code;

    /**
     * @return string
     */
    private $signedUpOn;

    /**
     * PartiallySignedUpLeadDTO constructor.
     * @param $leadId
     * @param $email
     * @param $token
     * @param $code
     * @param $signedUpOn
     */
    public function __construct($leadId, $email, $token, $code, $signedUpOn)
    {
        $this->leadId = $leadId;
        $this->email = $email;
        $this->token = $token;
        $this->code = $code;
        $this->signedUpOn = $signedUpOn;
    }


    /**
     * @return string
     */
    public function leadId(): string
    {
        return $this->leadId;
    }

    /**
     * @return string
     */
    public function email(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function token(): string
    {
        return $this->token;
    }

    /**
     * @return string
     */
    public function code(): string
    {
        return $this->code;
    }

    /**
     * @return int
     */
    public function signedUpOn(): int
    {
        return $this->signedUpOn;
    }

}