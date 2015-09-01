<?php

namespace Monii\AggregateEventStorage\Aggregate\ChangeReading;

class ChangeIsEventReader implements ChangeReader
{
    public function readEvent($change)
    {
        return $change;
    }

    public function readMetadata($change)
    {
        return null;
    }

    public function canReadEventId($change)
    {
        return false;
    }

    public function readEventId($change)
    {
        return null;
    }

    public function canReadEventVersion($change)
    {
        return false;
    }

    public function readEventVersion($change)
    {
        return null;
    }

    public function readWhen($change)
    {
        return null;
    }
}
