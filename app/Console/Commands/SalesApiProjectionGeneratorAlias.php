<?php
/**
 * Created by PhpStorm.
 * User: ericksonreyes
 * Date: 2019-01-07
 * Time: 18:41
 */

namespace App\Console\Commands;

class SalesApiProjectionGeneratorAlias extends RabbitMQDomainEventListener
{
    protected $signature = 'knit:sales:listen';

    protected $description = 'Alias of knit:sales:listen:projection_generator';

    /**
     * RabbitMQDomainEventListenerCommand constructor.
     */
    public function __construct()
    {
        parent::__construct();
        parent::setEventProjectors(parent::container()->get('projection_generators'));
    }
}
