<?php

namespace App\Repositories\Command;

use Elasticsearch\Client;
use Rest\Shared\DomainEvent;
use Rest\Shared\DomainEventRepository;

class ElasticSearchEventStorage implements DomainEventRepository
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var EventFactory
     */
    private $eventFactory;

    /**
     * ElasticSearchEventStorage constructor.
     * @param Client $client
     */
    public function __construct(Client $client, EventFactory $eventFactory)
    {
        $this->client = $client;
        $this->eventFactory = $eventFactory;
    }

    /**
     * @param DomainEvent $domainEvent
     * @return mixed
     */
    public function store(DomainEvent $domainEvent): void
    {
        $params = [
            'index' => $this->indexName($domainEvent->entityContext().':'.$domainEvent->entityType()),
            'type' => 'events',
            'body' => [
                'happenedOn' => $domainEvent->happenedOn()->getTimestamp(),
                'entityContext' => $domainEvent->entityContext(),
                'entityType'=>$domainEvent->entityType(),
                'entityId' => $domainEvent->entityId(),
                'eventName' => $domainEvent->eventName(),
                'eventData' => json_encode($domainEvent->toArray()),
                'eventMetaData' => json_encode($_SERVER)
            ]
        ];
        $this->client->index($params);
    }

    /**
     * @param string $contextName
     * @param string $entityType
     * @param string $entityId
     * @return DomainEvent[]
     */
    public function getEventsFor(string $contextName, string $entityType, string $entityId): array
    {
        $searchResult =  $this->client->search([
            'index' => $this->indexName($contextName.':'.$entityType),
            'type' => 'events',
            'body' => [
                'query' => [
                    'bool' => [
                        'filter' =>[
                            'term' => [
                                'entityId.keyword' => $entityId
                            ]
                        ]
                    ]
                ],
                'sort' => [
                    'happenedOn' => [
                        'order' => 'asc'
                    ]
                ]
            ]
        ]);
        if (isset($searchResult['hits']['hits'])) {
            $hits = $searchResult['hits']['hits'];
            $events = [];

            foreach ($hits as $hit) {
                $row = $hit['_source'];
                $events[] = $this->eventFactory->makeEventFromName(
                    $row['eventName'],
                    json_decode($row['eventData'], true)
                );
            }
            return $events;
        }

        return null;
    }

    /**
     * @param $entityId
     * @return DomainEvent[]|null
     */
    public function getEventsForId($entityId): ?array
    {
        $searchResult =  $this->client->search([
            'body' => [
                'query' => [
                    'bool' => [
                        'filter' =>[
                            'term' => [
                                'entityId.keyword' => $entityId
                            ]
                        ]
                    ]
                ],
                'sort' => [
                    'happenedOn' => [
                        'order' => 'asc'
                    ]
                ]
            ]
        ]);
        if (isset($searchResult['hits']['hits'])) {
            $hits = $searchResult['hits']['hits'];
            $events = [];

            foreach ($hits as $hit) {
                $row = $hit['_source'];
                $events[] = $this->eventFactory->makeEventFromName(
                    $row['eventName'],
                    json_decode($row['eventData'], true)
                );
            }
            return $events;
        }

        return null;
    }


    /**
     * @param string $indexName
     * @return null|string|string[]
     */
    private function indexName(string $indexName)
    {
        return preg_replace("/[^A-Za-z0-9-_:]/", "", strtolower($indexName));
    }
}
