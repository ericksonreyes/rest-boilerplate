<?php
/**
 * Created by PhpStorm.
 * User: ericksonreyes
 * Date: 2019-01-03
 * Time: 16:06
 */

namespace App\Repositories\Command;

use Rest\Sales\Domain\Model\User\Repository\EmployeeRepository;
use Rest\Sales\Domain\Model\User\Employee;
use Rest\Shared\DomainEventRepository;

class EventSourcedEmployeeRepository implements EmployeeRepository
{
    private const CONTEXT_NAME = 'Sales';

    private const ENTITY_TYPE = 'User';

    /**
     * @var DomainEventRepository
     */
    private $repository;

    /**
     * EventSourcedWeaveRepository constructor.
     * @param DomainEventRepository $eventRepository
     */
    public function __construct(DomainEventRepository $eventRepository)
    {
        $this->repository = $eventRepository;
    }


    /**
     * @param string $userId
     * @return Employee|null
     */
    public function findById(string $userId): ?Employee
    {
        $recordedEvents = $this->repository()->getEventsFor(self::CONTEXT_NAME, self::ENTITY_TYPE, $userId);

        if (count($recordedEvents) === 0) {
            return null;
        }

        $lead = new Employee($userId);
        foreach ($recordedEvents as $recordedEvent) {
            $lead->replayThis($recordedEvent);
        }
        return $lead;
    }

    /**
     * @param Employee $user
     */
    public function store(Employee $user): void
    {
        foreach ($user->storedEvents() as $storedEvent) {
            $this->repository()->store($storedEvent);
        }
    }

    /**
     * @return DomainEventRepository
     */
    private function repository(): DomainEventRepository
    {
        return $this->repository;
    }
}
