<?php

namespace App\Repositories\Command;

use Rest\Shared\DomainEvent;
use Rest\Shared\DomainEventPublisher;
use Rest\Shared\DomainEventRepository;

class EventStoreRepository implements DomainEventRepository
{

    /**
     * @var DomainEventRepository[]
     */
    private $repositories = [];

    /**
     * @var DomainEventPublisher[]
     */
    private $publishers = [];

    /**
     * EventStoreRepository constructor.
     * @param DomainEventRepository $repository
     */
    public function __construct(DomainEventRepository $repository)
    {
        $this->repositories[] = $repository;
    }

    /**
     * @param DomainEventRepository $repository
     */
    public function addRepository(DomainEventRepository $repository): void
    {
        $this->repositories[] = $repository;
    }

    /**
     * @param DomainEventPublisher $publisher
     */
    public function addPublisher(DomainEventPublisher $publisher): void
    {
        $this->publishers[] = $publisher;
    }


    /**
     * @param DomainEvent $domainEvent
     * @return mixed
     */
    public function store(DomainEvent $domainEvent): void
    {
        foreach ($this->repositories as $repository) {
            $repository->store($domainEvent);
        }

        foreach ($this->publishers as $publisher) {
            $publisher->publish($domainEvent);
        }
    }

    /**
     * @param string $contextName
     * @param string $entityType
     * @param string $entityId
     * @return DomainEvent[]
     */
    public function getEventsFor(string $contextName, string $entityType, string $entityId): array
    {
        return $this->repositories[0]->getEventsFor($contextName, $entityType, $entityId);
    }

    /**
     * @param $entityId
     * @return array|null
     */
    public function getEventsForId($entityId): ?array
    {
        return $this->repositories[0]->getEventsForId($entityId);
    }
}
