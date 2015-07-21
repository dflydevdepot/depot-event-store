<?php

namespace Monii\AggregateEventStorage\EventStore\Serialization;

interface Serializer
{
    /**
     * @param object $object
     *
     * @return array
     */
    public function serialize($object);

    /**
     * @param array $data
     *
     * @return object
     */
    public function deserialize(array $data);
}
