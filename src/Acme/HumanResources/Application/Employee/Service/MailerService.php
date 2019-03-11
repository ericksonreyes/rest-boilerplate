<?php

namespace Acme\HumanResources\Application\Employee\Service;


interface MailerService
{
    /**
     * @param string $emailAddress
     * @return bool
     */
    public function sendWelcomeEmailTo(string $emailAddress): bool;
}