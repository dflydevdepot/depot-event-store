<?php

namespace Monii\AggregateEventStorage\Aggregate\ChangeReading;

class ChangeIsEventReader
{
    public function readEvent($change) {

        return $change;
    }

    public function readMetadata($change) {

        return null;

    }

}