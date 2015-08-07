<?php

namespace Monii\AggregateEventStorage\Aggregate\VersionReading;

interface VersionReader
{
    /**
     * @param $object
     *
     * @return int
     */
    public function readVersion($object);
}
