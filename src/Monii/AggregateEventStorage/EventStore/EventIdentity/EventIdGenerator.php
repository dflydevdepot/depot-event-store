<?php

namespace Monii\AggregateEventStorage\EventStore\EventIdentity;

interface EventIdGenerator
{
    /**
     * @return EventId
     */
    public function generateEventId();
}
