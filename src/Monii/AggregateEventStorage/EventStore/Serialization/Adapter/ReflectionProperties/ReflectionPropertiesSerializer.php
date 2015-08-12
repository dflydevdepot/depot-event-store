<?php

namespace Monii\AggregateEventStorage\EventStore\Serialization\Adapter\ReflectionProperties;

use Monii\Serialization\ReflectionPropertiesSerializer\ReflectionPropertiesSerializer as MoniiReflectionPropertiesSerializer;
use Monii\AggregateEventStorage\EventStore\Serialization\Serializer;
use Monii\AggregateEventStorage\Contract\ContractResolver;
use Monii\AggregateEventStorage\Contract\Contract;


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
        Contract $type,
        $data
    ) {
        return true;
    }

    public function canDeserialize(
        Contract $type,
        array $data
    ) {
        return true;
    }

    public function serialize(
        Contract $type,
        $object
    ) {
        return $this->serializer->serialize($object);
    }

    public function deserialize(
        Contract $type,
        array $data
    ) {
        return $this->serializer->deserialize($type->getClassName(), $data);
    }
}
