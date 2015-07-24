<?php

namespace Monii\AggregateEventStorage\EventStore\Serialization;

use Monii\AggregateEventStorage\Aggregate\Error\SerializationNotPossible;

class SerializerChain implements Serializer
{
    /**
     * @var array
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
    public function canSerialize($type, $object)
    {
        /** @var Serializer $serializer */
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
    public function serialize($type, $object)
    {
        /** @var Serializer $serializer */
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
    public function canDeserialize($type, array $data)
    {
        /** @var Serializer $serializer */
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
    public function deserialize($type, array $data)
    {
        /** @var Serializer $serializer */
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
