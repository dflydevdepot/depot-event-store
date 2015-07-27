<?php

namespace Monii\AggregateEventStorage\EventStore\Serialization;

use Monii\AggregateEventStorage\Contract\Contract;

class PropertiesReflectionSerializer implements Serializer
{
   /**
     * (@inheritdoc)
     */
    public function canSerialize(Contract $type, $object)
    {
        return true;
    }

    /**
     * (@inheritdoc)
     */
    public function serialize(Contract $type, $object)
    {
        $data = [];
        $reflectionClass = new \ReflectionClass($object);

        foreach ($reflectionClass->getProperties() as $reflectionProperty) {
            $reflectionProperty->setAccessible(true);
            $data[$reflectionProperty->getName()] = $reflectionProperty->getValue($object);
        }

        return $data;
    }
    /**
     * (@inheritdoc)
     */
    public function canDeserialize(Contract $type, array $data)
    {
        return true;
    }

    /**
     * (@inheritdoc)
     */
    public function deserialize(Contract $type, array $data)
    {
        $reflectionClass = new \ReflectionClass($type->getClassName());

        $object = $reflectionClass->newInstanceWithoutConstructor();

        foreach ($reflectionClass->getProperties() as $reflectionProperty) {
            $reflectionProperty->setAccessible(true);
            $reflectionProperty->setValue($object, $data[$reflectionProperty->getName()]);
        }

        return $object;
    }
}
