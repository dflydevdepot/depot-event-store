<?php

namespace Monii\AggregateEventStorage\Aggregate\ChangeWriting;

interface ChangeWriter
{
    /**
     * @param string $eventId
     * @param object $event
     * @param object|null $metadata
     *
     * @return object
     */
    public function writeChange($eventId, $event, $metadata = null);
}
