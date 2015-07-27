<?php

namespace Monii\AggregateEventStorage\EventStore\Serialization\Adapter\PropertiesReflection;

class ReflectionPropertyHelper
{
    /**
     * @var \ReflectionClass
     */
    private $reflectionClass;

    /**
     * @var \ReflectionProperty
     */
    private $reflectionProperty;

    /**
     * @var string|null
     */
    private $type;

    public function __construct(\ReflectionClass $reflectionClass, \ReflectionProperty $reflectionProperty)
    {
        $reflectionProperty->setAccessible(true);

        if (preg_match('/@var\s+([^\s]+)/', $reflectionProperty->getDocComment(), $matches)) {
            list(, $type) = $matches;
            if (class_exists($type)) {
                $this->type = $type;
            } else {
                $type = $reflectionClass->getNamespaceName().'\\'.$type;
                if (class_exists($type)) {
                    $this->type = $type;
                } else {
                    $this->type = $type = null;
                }
            }
        }

        $this->reflectionClass = $reflectionClass;
        $this->reflectionProperty = $reflectionProperty;
    }

    public function isObject()
    {
        return ! is_null($this->type);
    }

    public function getType()
    {
        return $this->type;
    }

    public function getValue($object)
    {
        return $this->reflectionProperty->getValue($object);
    }

    public function setValue($object, $value = null)
    {
        $this->reflectionProperty->setValue($object, $value);
    }
}
