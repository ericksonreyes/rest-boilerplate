<?php

namespace Company\HumanResources\Domain\Model\Employee\Repository;


use Company\HumanResources\Domain\Model\Employee\Employee;

interface EmployeeRepository
{

    /**
     * @param string $userId
     * @return Employee|null
     */
    public function findById(string $userId): ?Employee;

    /**
     * @param Employee $user
     */
    public function store(Employee $user): void;
}
