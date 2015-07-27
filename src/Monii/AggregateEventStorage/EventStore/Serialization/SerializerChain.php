<?php

namespace Monii\AggregateEventStorage\EventStore\Serialization;

use Monii\AggregateEventStorage\Aggregate\Error\SerializationNotPossible;
use Monii\AggregateEventStorage\Contract\Contract;

class SerializerChain implements Serializer
{
    /**
     * @var Serializer[]
     */
    private $serializers;

    public function __construct(
        $serializers = array()
    ){
        $this->serializers = $serializers;
    }

    /**
     * (@inheritdoc)
     */
    public function canSerialize(Contract $type, $object)
    {
        foreach ($this->serializers as $serializer) {
            if ($serializer->canSerialize($type, $object)) {
                return true;
            }
        }
        return false;
    }

    /**
     * (@inheritdoc)
     */
    public function serialize(Contract $type, $object)
    {
        foreach ($this->serializers as $serializer) {
            if ($serializer->canSerialize($type, $object)) {
                return $serializer->serialize($type, $object);
            }
        }
        throw new SerializationNotPossible();
    }
    /**
     * (@inheritdoc)
     */
    public function canDeserialize(Contract $type, array $data)
    {
        foreach ($this->serializers as $serializer) {
            if ($serializer->canDeserialize($type, $data)) {
                return true;
            }
        }
        return false;
    }

    /**
     * (@inheritdoc)
     */
    public function deserialize(Contract $type, array $data)
    {
        foreach ($this->serializers as $serializer) {
            if ($serializer->canDeserialize($type, $data)) {
                return $serializer->deserialize($type, $data);
            }
        }
        throw new SerializationNotPossible();
    }

    public function  pushSerializer($serializer)
    {
        $this->serializers[] = $serializer;
    }

    public function unshiftSerializer($serializer)
    {
        array_unshift($serializers, $serializer);
    }
}
