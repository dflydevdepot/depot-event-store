<?php

namespace Monii\AggregateEventStorage\Aggregate\ChangeWriting;

interface ChangeWriter
{
    /**
     * @param object $event
     * @param object|null $metadata
     *
     * @return object
     */
    public function writeChange($event, $metadata = null);
}
