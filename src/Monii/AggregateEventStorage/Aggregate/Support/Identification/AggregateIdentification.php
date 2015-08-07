<?php

namespace Monii\AggregateEventStorage\Aggregate\Support\Identification;

interface AggregateIdentification
{
    /**
     * @return string
     */
    public function getAggregateIdentity();
}
