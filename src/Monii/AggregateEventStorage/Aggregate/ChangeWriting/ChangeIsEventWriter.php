<?php

namespace Monii\AggregateEventStorage\Aggregate\ChangeWriting;

class ChangeIsEventWriter
{
    public function writeChange($event, $metadata = null)
    {
        return $event;
    }

}