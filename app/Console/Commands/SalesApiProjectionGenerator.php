<?php
/**
 * Created by PhpStorm.
 * User: ericksonreyes
 * Date: 2019-01-07
 * Time: 18:41
 */

namespace App\Console\Commands;

class SalesApiProjectionGenerator extends RabbitMQDomainEventListener
{
    protected $signature = 'knit:sales:listen:projection_generator';

    protected $description = 'Creates projections from domain events.';

    /**
     * RabbitMQDomainEventListenerCommand constructor.
     */
    public function __construct()
    {
        parent::__construct();
        parent::setEventProjectors(parent::container()->get('projection_generators'));
    }
}
