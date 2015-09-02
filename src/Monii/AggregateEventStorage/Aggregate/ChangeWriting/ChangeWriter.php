<?php

namespace Monii\AggregateEventStorage\Aggregate\ChangeWriting;

use DateTimeImmutable;

interface ChangeWriter
{
    /**
     * @param string $eventId
     * @param object $event
     * @param DateTimeImmutable|null $when
     * @param object|null $metadata
     * @param string|null $version
     *
     * @return object
     */
    public function writeChange($eventId, $event, $when = null, $metadata = null, $version = null);
}
