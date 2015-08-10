<?php

namespace Monii\AggregateEventStorage\EventStore\Serialization\Adapter\PropertiesReflection;

use Monii\AggregateEventStorage\Contract\Contract;
use Monii\AggregateEventStorage\Contract\ContractResolver;
use Monii\AggregateEventStorage\EventStore\Serialization\Serializer;

class PropertiesReflectionSerializer implements Serializer
{
    /**
     * @var ContractResolver
     */
    private $contractResolver;

    /**
     * @var Serializer
     */
    private $subSerializer;

    public function __construct(ContractResolver $contractResolver, Serializer $subSerializer = null)
    {
        $this->contractResolver = $contractResolver;
        $this->subSerializer = $subSerializer;
    }

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

        $data = $this->addDataFromReflectionClass($data, $reflectionClass, $object);

        return $data;
    }

    public function addDataFromReflectionClass(
        array $data,
        \ReflectionClass $reflectionClass,
        $object
    ) {
        foreach ($reflectionClass->getProperties() as $reflectionProperty) {
            if (array_key_exists($reflectionProperty->getName(), $data)) {
                continue;
            }

            $property = new ReflectionPropertyHelper($reflectionClass, $reflectionProperty);

            if ($property->isObject()) {
                $data[$reflectionProperty->getName()] = $this->subSerialize($property->getValue($object));
            } else {
                $data[$reflectionProperty->getName()] = $property->getValue($object);
            }
        }

        if (false !== $parentClass = $reflectionClass->getParentClass()) {
            $data = $this->addDataFromReflectionClass($data, $parentClass, $object);
        }

        foreach ($reflectionClass->getTraits() as $traitReflectionClass) {
            $data = $this->addDataFromReflectionClass($data, $traitReflectionClass, $object);
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
            $property = new ReflectionPropertyHelper($reflectionClass, $reflectionProperty);
            if ($property->isObject()) {
                $property->setValue($object, $this->subDeserialize(
                    $property->getType(),
                    $data[$reflectionProperty->getName()]
                ));
            } else {
                $property->setValue($object, $data[$reflectionProperty->getName()]);
            }
        }

        return $object;
    }

    private function subSerialize($object)
    {
        $this->ensureSubSerializerExists();

        return $this->subSerializer->serialize(
            $this->contractResolver->resolveFromObject($object),
            $object
        );
    }

    private function subDeserialize($className, $data)
    {
        $this->ensureSubSerializerExists();

        return $this->subSerializer->deserialize(
            $this->contractResolver->resolveFromClassName($className),
            $data
        );
    }

    private function ensureSubSerializerExists()
    {
        if (! is_null($this->subSerializer)) {
            return;
        }

        $this->subSerializer = new self($this->contractResolver);
    }
}
