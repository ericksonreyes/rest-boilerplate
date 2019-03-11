<?php
/**
 * Created by PhpStorm.
 * User: ericksonreyes
 * Date: 2019-01-07
 * Time: 18:47
 */

namespace App\Console\Commands;

class SalesApiEmailNotificationSender extends RabbitMQDomainEventListener
{
    protected $signature = 'knit:sales:listen:email_notification_sender';

    protected $description = 'Sends email notification from domain events.';

    /**
     * RabbitMQDomainEventListenerCommand constructor.
     */
    public function __construct()
    {
        parent::__construct();
        parent::setEventProjectors(parent::container()->get('email_notification_senders'));
    }
}
