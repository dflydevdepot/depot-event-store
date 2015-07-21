<?php

namespace Monii\AggregateEventStorage\Aggregate\Support\Identification;

interface AggregateIdentity
{
    /**
     * @return string
     */
    public function getAggregateIdentity();
}
