<?php

namespace App\Repositories\Command;

use Rest\Sales\Domain\Model\Lead\Lead;
use Rest\Sales\Domain\Model\Lead\Repository\LeadRepository;
use Rest\Shared\DomainEventRepository;

class EventSourcedLeadRepository implements LeadRepository
{

    private const CONTEXT_NAME = 'Sales';

    private const ENTITY_TYPE = 'Lead';

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
     * @param string $leadId
     * @return Lead|null
     */
    public function findById(string $leadId): ?Lead
    {
        $recordedEvents = $this->repository->getEventsFor(self::CONTEXT_NAME, self::ENTITY_TYPE, $leadId);

        if (count($recordedEvents) === 0) {
            return null;
        }

        $lead = new Lead($leadId);
        foreach ($recordedEvents as $recordedEvent) {
            $lead->replayThis($recordedEvent);
        }
        return $lead;
    }

    /**
     * @param Lead $lead
     */
    public function store(Lead $lead): void
    {
        foreach ($lead->storedEvents() as $storedEvent) {
            $this->repository->store($storedEvent);
        }
    }
}
