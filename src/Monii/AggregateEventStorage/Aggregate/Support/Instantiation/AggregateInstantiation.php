<?php

namespace Monii\AggregateEventStorage\Aggregate\Support\Instantiation;

interface AggregateInstantiation
{
    /**
     * @return static
     */
    public static function instantiateAggregateForReconstitution();
}
