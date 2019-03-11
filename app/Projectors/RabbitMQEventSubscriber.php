<?php

namespace App\Projectors;

use App\Repositories\Command\EventFactory;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitMQEventSubscriber
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
     * @var EventFactory
     */
    private $domainEventFactory;

    /**
     * @var callable
     */
    private $callback;

    /**
     * RabbitMQEventSubscriber constructor.
     * @param AMQPStreamConnection $connection
     * @param string $exchangeName
     * @param EventFactory $domainEventFactory
     */
    public function __construct(AMQPStreamConnection $connection, string $exchangeName, EventFactory $domainEventFactory)
    {
        $this->connection = $connection;
        $this->exchangeName = $exchangeName;
        $this->domainEventFactory = $domainEventFactory;
    }

    /**
     * @param callable $callback
     */
    public function setCallback(callable $callback): void
    {
        $this->callback = $callback;
    }

    public function listen()
    {
        $channel = $this->connection->channel();
        $channel->exchange_declare($this->exchangeName, 'fanout', false, false, false);

        list($queue_name, ,) = $channel->queue_declare("", false, false, true, false);

        $channel->queue_bind($queue_name, $this->exchangeName);
        $channel->basic_consume($queue_name, '', false, true, false, false, $this->callback);
        while (count($channel->callbacks)) {
            $channel->wait();
        }

        $channel->close();
        $this->connection->close();
    }
}
