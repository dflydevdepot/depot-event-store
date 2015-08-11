<?php

namespace Monii\AggregateEventStorage\EventStore\Serialization;

use Monii\AggregateEventStorage\Contract\Contract;

interface Serializer
{
    /**
     * @param object $object
     *
     * @return bool
     */
    public function canSerialize($object);

    /**
     * @param object $object
     *
     * @return array
     */
    public function serialize($object);

    /**
     * @param $type
     * @param array $data
     *
     * @return bool
     */
    public function canDeserialize($type, array $data);

    /**
     * @param $type
     * @param array $data
     *
     * @return object
     */
    public function deserialize($type, array $data);
}
