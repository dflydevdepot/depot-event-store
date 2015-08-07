<?php

namespace Monii\AggregateEventStorage\Aggregate\Support\VersionReading;

interface AggregateVersionReading
{
    /**
     * @return object
     */
    public function getAggregateVersion();
}
