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
    public function canSerialize(Contract $type, $object);

    /**
     * @param Contract $type
     * @param object $object
     *
     * @return array
     */
    public function serialize(Contract $type, $object);

    /**
     * @param Contract $type
     * @param array $data
     *
     * @return bool
     */
    public function canDeserialize(Contract $type, array $data);

    /**
     * @param Contract $type
     * @param array $data
     *
     * @return object
     */
    public function deserialize(Contract $type, array $data);
}
