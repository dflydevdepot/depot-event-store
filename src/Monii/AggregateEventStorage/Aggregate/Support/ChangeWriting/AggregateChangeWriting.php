<?php

namespace Monii\AggregateEventStorage\Aggregate\Support\ChangeWriting;

interface AggregateChangeWriting
{
    /**
     * @return static
     */
    public static function instantiateAggregateChangeFromEventAndMetadata();
}
