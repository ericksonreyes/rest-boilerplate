<?php

namespace Acme\HumanResources\Application\Employee;

/**
 * Class UseCase
 * @package Acme\HumanResources\Application\Employee
 */
class RegisterEmployee
{
    /**
     * @return string
     */
    public function email(): string
    {
        return 'acme@acme.com';
    }

    public function id(): string
    {
        return md5(time());
    }
}