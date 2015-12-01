<?php

namespace Depot\EventStore\EventIdentity;

interface EventIdGenerator
{
    /**
     * @return EventId
     */
    public function generateEventId();
}
