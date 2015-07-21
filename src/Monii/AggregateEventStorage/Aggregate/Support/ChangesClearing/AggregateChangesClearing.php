<?php

namespace Monii\AggregateEventStorage\Aggregate\Support\ChangesClearing;

interface AggregateChangesClearing
{
    /**
     * @return array
     */
    public function clearAggregateChanges();
}
