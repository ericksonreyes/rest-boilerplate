<?php

namespace App\Projectors;

use Rest\Shared\DomainEvent;

/**
 * Interface Projector
 * @package Projectors
 */
interface Projector
{

    /**
     * @return string
     */
    public function name(): string;

    /**
     * @param DomainEvent $domainEvent
     * @return bool
     */
    public function project(DomainEvent $domainEvent): bool;
}
