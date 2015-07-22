<?php

namespace Monii\AggregateEventStorage\Aggregate\Support\ChangeWriting;

interface AggregateChangeWriter
{
    /**
     * @return static
     */
    public static function instantiateAggregateChangeFromEventAndMetadata();
}
