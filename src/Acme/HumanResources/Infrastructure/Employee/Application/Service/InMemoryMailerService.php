<?php

namespace Acme\HumanResources\Infrastructure\Application\Service;

/**
 * Class InMemoryMailerService
 * @package Acme\HumanResources\Infrastructure\Application\Service
 */
class InMemoryMailerService
{
    /**
     * @param string $emailAddress
     * @return bool
     */
    public function sendWelcomeEmailTo(string $emailAddress): bool
    {
        return (bool) mt_rand(0, 1);
    }

}