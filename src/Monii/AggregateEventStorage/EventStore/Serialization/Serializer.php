<?php

namespace Monii\AggregateEventStorage\EventStore\Serialization;

use Monii\AggregateEventStorage\Contract\Contract;

interface Serializer
{
    /**
     * @param Contract $type
     * @param object $object
     *
     * @return bool
     */
    public function canSerialize($type, $object);

    /**
     * @param Contract $type
     * @param object $object
     *
     * @return array
     */
    public function serialize($type, $object);

    /**
     * @param Contract $type
     * @param array $data
     *
     * @return bool
     */
    public function canDeserialize($type, array $data);

    /**
     * @param Contract $type
     * @param array $data
     *
     * @return object
     */
    public function deserialize($type, array $data);
}
