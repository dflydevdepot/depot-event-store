<?php

namespace Monii\AggregateEventStorage\Aggregate\ChangeReading;

interface ChangeReader
{
    /**
     * @param object $change
     *
     * @return object
     */
    public function readEvent($change);

    /**
     * @param object $change
     *
     * @return object|null
     */
    public function readMetadata($change);
}
