<?php

namespace App\Repositories\Command;

use App\Models\Command\EventModel;
use Rest\Shared\DomainEvent;
use Rest\Shared\DomainEventRepository;
use Rest\Shared\IdentityGenerator;

class EloquentEventStorage implements DomainEventRepository
{
    /**
     * @var EventModel
     */
    private $events;

    /**
     * @var EventFactory
     */
    private $factory;

    /**
     * @var IdentityGenerator
     */
    private $identityGenerator;

    /**
     * EloquentEventStoreRepository constructor.
     * @param EventModel $events
     * @param EventFactory $factory
     * @param IdentityGenerator $identityGenerator
     */
    public function __construct(EventModel $events, EventFactory $factory, IdentityGenerator $identityGenerator)
    {
        $this->events = $events;
        $this->factory = $factory;
        $this->identityGenerator = $identityGenerator;
    }


    /**
     * @param string $contextName
     * @param string $entityType
     * @param string $entityId
     * @return DomainEvent[]
     */
    public function getEventsFor(string $contextName, string $entityType, string $entityId): array
    {
        $models = $this->events->where('context_name', $contextName)
            ->where('entity_type', $entityType)
            ->where('entity_id', $entityId)
            ->get();

        $events = [];
        foreach ($models as $model) {
            $event = $this->factory->makeEventFromName(
                $model->event_name,
                json_decode($model->event_data, true)
            );

            if ($event instanceof DomainEvent) {
                $events[] = $event;
            }
        }

        return $events;
    }

    /**
     * @param $entityId
     * @return array|null
     */
    public function getEventsForId($entityId): ?array
    {
        return $this->events->where('entity_id', $entityId)
            ->get();
    }


    /**
     * @param DomainEvent $domainEvent
     * @throws \Exception
     */
    public function store(DomainEvent $domainEvent):void
    {
        $newEvent = new EventModel();
        $newEvent->event_id = $this->identityGenerator->nextIdentity('sales-event-');
        $newEvent->event_name = $domainEvent->eventName();
        $newEvent->happened_on = time();
        $newEvent->context_name = $domainEvent->entityContext();
        $newEvent->entity_type = $domainEvent->entityType();
        $newEvent->entity_id = $domainEvent->entityId();
        $newEvent->event_data = json_encode($domainEvent->toArray());
        $newEvent->event_meta_data = json_encode($_SERVER);
        $newEvent->save();
    }
}
