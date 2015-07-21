<?php

namespace Monii\AggregateEventStorage\EventStore\EventIdentity;

class EventId
{
    private $eventId;

    private function __construct($eventId)
    {
        $this->eventId = $eventId;
    }

    public static function fromString($string)
    {
        return new self($string);
    }

    public function __toString()
    {
        return $this->eventId;
    }
}
