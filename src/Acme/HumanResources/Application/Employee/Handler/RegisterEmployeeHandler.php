<?php

namespace Acme\HumanResources\Application\Employee\Handler;

use Acme\HumanResources\Application\Employee\Handler\Exception\EmailSendingFailureException;
use Acme\HumanResources\Application\Employee\RegisterEmployee;
use Acme\HumanResources\Application\Employee\Service\MailerService;
use Acme\HumanResources\Domain\Model\Employee\Employee;
use Acme\HumanResources\Domain\Model\Employee\Repository\EmployeeRepository;

/**
 * Class UseCaseHandler
 * @package Acme\HumanResources\Application\Employee\Handler
 */
class RegisterEmployeeHandler
{

    /**
     * @var EmployeeRepository
     */
    private $employeeRepository;

    /**
     * @var MailerService
     */
    private $mailerService;

    /**
     * RegisterEmployeeHandler constructor.
     * @param EmployeeRepository $employeeRepository
     * @param MailerService $mailerService
     */
    public function __construct(EmployeeRepository $employeeRepository, MailerService $mailerService)
    {
        $this->employeeRepository = $employeeRepository;
        $this->mailerService = $mailerService;
    }


    /**
     * @param RegisterEmployee $useCase
     */
    public function handleThis(RegisterEmployee $useCase): void
    {
        $emailWasNotSent = !$this->mailerService->sendWelcomeEmailTo($useCase->email());

        if ($emailWasNotSent) {
            throw new EmailSendingFailureException();
        }

        $employeeRecord = new Employee($useCase->id());
        $this->employeeRepository->store($employeeRecord);
    }
}