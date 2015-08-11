<?php

namespace Monii\AggregateEventStorage\EventStore\Serialization\Adapter\ReflectionProperties;

use Monii\Serialization\ReflectionPropertiesSerializer\ReflectionPropertiesSerializer as MoniiReflectionPropertiesSerializer;
use Monii\AggregateEventStorage\EventStore\Serialization\Serializer;
use Monii\AggregateEventStorage\Contract\ContractResolver;


class ReflectionPropertiesSerializer implements Serializer
{
    public function __construct(
        ContractResolver $contractResolver,
        MoniiReflectionPropertiesSerializer $serializer = null
    ) {
        $this->contractResolver = $contractResolver;
        $this->serializer = $serializer ?: new MoniiReflectionPropertiesSerializer();
    }

    public function canSerialize(
        $data
    ) {
        return true;
    }

    public function canDeserialize(
        $type,
        array $data
    ) {
        return true;
    }

    public function serialize(
        $object
    ) {
        return $this->serializer->serialize($object);
    }

    public function deserialize(
        $type,
        array $data
    ) {
        return $this->serializer->deserialize($type, $data);
    }
}
