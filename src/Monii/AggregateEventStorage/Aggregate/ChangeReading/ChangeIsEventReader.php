<?php

namespace Monii\AggregateEventStorage\Aggregate\ChangeReading;

class ChangeIsEventReader
{
    public function getEvent($change) {

        return $change;
    }

    public function getMetadata($change) {

        return null;

    }

}