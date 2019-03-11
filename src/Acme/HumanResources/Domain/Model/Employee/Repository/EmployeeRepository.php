<?php

namespace Acme\HumanResources\Domain\Model\Employee\Repository;


use Acme\HumanResources\Domain\Model\Employee\Employee;

interface EmployeeRepository
{

    /**
     * @param string $userId
     * @return Employee|null
     */
    public function findById(string $userId): ?Employee;

    /**
     * @param Employee $employee
     */
    public function store(Employee $employee): void;
}
