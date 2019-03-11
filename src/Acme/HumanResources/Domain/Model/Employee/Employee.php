<?php

namespace Acme\HumanResources\Domain\Model\Employee;

/**
 * Class Employee
 * @package Acme\HumanResources\Domain\Model\Employee
 */
class Employee
{
    /**
     * @var string
     */
    private $id;

    /**
     * Employee constructor.
     * @param string $id
     */
    public function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function id(): string
    {
        return $this->id;
    }




}
