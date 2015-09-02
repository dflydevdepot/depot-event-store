<?php

namespace Monii\AggregateEventStorage\Aggregate\ChangeWriting;

class ChangeIsEventWriter implements ChangeWriter
{
    public function writeChange($eventId, $event, $when = null, $metadata = null, $version = null)
    {
        return $event;
    }
}
