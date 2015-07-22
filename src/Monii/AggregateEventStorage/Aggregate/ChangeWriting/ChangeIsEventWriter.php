<?php

namespace Monii\AggregateEventStorage\Aggregate\ChangeWriting;

class ChangeIsEventWriter implements ChangeWriter
{
    public function writeChange($event, $metadata = null)
    {
        return $event;
    }

}