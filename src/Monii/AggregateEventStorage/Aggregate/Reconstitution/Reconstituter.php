<?php

namespace Monii\AggregateEventStorage\Aggregate\Reconstitution;

interface Reconstituter
{
    /**
     * @param mixed $object
     * @param array $events
     *
     * @return mixed
     */
    public function reconstitute($object, array $events);
}
