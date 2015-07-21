<?php

namespace Monii\AggregateEventStorage\Aggregate\Support\Reconstitution;

interface AggregateReconstitution
{
    /**
     * @param array $events
     *
     * @return void
     */
    public function reconstituteAggregateFrom(array $events);
}
