<?php

namespace App\Publishers;

use Rest\Shared\DomainEvent;
use Rest\Shared\DomainEventPublisher;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQEventPublisher implements DomainEventPublisher
{

    /**
     * @var AMQPStreamConnection
     */
    private $connection;

    /**
     * @var string
     */
    private $exchangeName = '';


    /**
     * RabbitMQEventPublisher constructor.
     * @param AMQPStreamConnection $connection
     * @param string $exchangeName
     */
    public function __construct(AMQPStreamConnection $connection, string $exchangeName)
    {
        $this->connection = $connection;
        $this->exchangeName = $exchangeName;
    }

    /**
     * @param DomainEvent $domainEvent
     */
    public function publish(DomainEvent $domainEvent): void
    {
        $channel = $this->connection->channel();
        $data = array_merge(['context' => $domainEvent->entityContext()], $domainEvent->toArray());

        $message = new AMQPMessage(json_encode($data));
        $channel->exchange_declare($this->exchangeName, 'fanout', false, false, false);
        $channel->basic_publish($message, $this->exchangeName);
        $channel->close();
    }
}
