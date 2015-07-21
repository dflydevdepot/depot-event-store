<?php

namespace Monii\AggregateEventStorage\Aggregate\Identification;

interface Identifier
{
    /**
     * @param $object
     *
     * @return string
     */
    public function identify($object);
}
