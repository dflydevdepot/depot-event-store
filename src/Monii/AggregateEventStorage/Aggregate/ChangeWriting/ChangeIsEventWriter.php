<?php

namespace Monii\AggregateEventStorage\Aggregate\ChangeWriting;

class ChangeIsEventWriter implements ChangeWriter
{
    public function writeChange($eventId, $event, $metadata = null)
    {
        return $event;
    }
}
