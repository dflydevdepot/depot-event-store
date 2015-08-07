<?php

namespace Monii\AggregateEventStorage\Aggregate\Support\ChangesExtraction;

interface AggregateChangesExtraction
{
    /**
     * @return array
     */
    public function getAggregateChanges();
}
