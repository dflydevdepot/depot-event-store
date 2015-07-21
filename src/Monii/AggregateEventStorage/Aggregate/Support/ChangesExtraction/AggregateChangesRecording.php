<?php

namespace Monii\AggregateEventStorage\Aggregate\Support\ChangesExtraction;

interface AggregateChangesRecording
{
    /**
     * @return array
     */
    public function getAggregateChanges();
}
