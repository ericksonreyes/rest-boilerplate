<?php

namespace Acme\HumanResources\Infrastructure\Employee\Repository;

use Acme\HumanResources\Domain\Model\Employee\Employee;
use Acme\HumanResources\Domain\Model\Employee\Repository\EmployeeRepository;

class InMemoryEmployeeRepository implements EmployeeRepository
{
    /**
     * @var Employee[]
     */
    private $employees;

    /**
     * @param string $userId
     * @return Employee|null
     */
    public function findById(string $userId): ?Employee
    {
        return new Employee($userId);
    }

    /**
     * @param Employee $employee
     */
    public function store(Employee $employee): void
    {
        $this->employees[$employee->id()] = $employee;
    }

}