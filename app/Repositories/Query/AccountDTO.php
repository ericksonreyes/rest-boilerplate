<?php
/**
 * Created by PhpStorm.
 * User: Erickson Reyes
 * Date: 13/12/2018
 * Time: 12:56 PM
 */

namespace App\Repositories\Query;

class AccountDTO implements Account
{

    /**
     * @var string
     */
    private $accountId;

    /**
     * @var string
     */
    private $email;

    /**
     * @var int
     */
    private $closedOn;

    /**
     * @var string
     */
    private $closedBy;

    /**
     * @var int
     */
    private $createdOn;

    /**
     * AccountDTO constructor.
     * @param string $accountId
     * @param string $leadId
     * @param string $email
     * @param string $password
     * @param int $createdOn
     */
    public function __construct(string $accountId, string $email, int $createdOn)
    {
        $this->accountId = $accountId;
        $this->email = $email;
        $this->createdOn = $createdOn;
    }

    /**
     * @param int $closedOn
     */
    public function setClosedOn(int $closedOn): void
    {
        $this->closedOn = $closedOn;
    }

    /**
     * @param string $closedBy
     */
    public function setClosedBy(string $closedBy): void
    {
        $this->closedBy = $closedBy;
    }


    /**
     * @return string
     */
    public function accountId(): string
    {
        return $this->accountId;
    }

    /**
     * @return string
     */
    public function email(): string
    {
        return $this->email;
    }

    /**
     * @return int|null
     */
    public function closedOn(): ?int
    {
        return $this->closedOn;
    }

    /**
     * @return null|string
     */
    public function closedBy(): ?string
    {
        return $this->closedBy;
    }

    /**
     * @return int
     */
    public function createdOn(): int
    {
        return $this->createdOn;
    }
}
