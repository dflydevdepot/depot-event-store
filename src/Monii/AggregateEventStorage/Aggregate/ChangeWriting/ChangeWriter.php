<?php

namespace Monii\AggregateEventStorage\Aggregate\ChangeWriting;

use DateTimeImmutable;

interface ChangeWriter
{
    /**
     * @param string $eventId
     * @param object $event
     * @param DateTimeImmutable $when
     * @param object|null $metadata
     *
     * @return object
     */
    public function writeChange($eventId, $event, $when, $metadata = null);
}
