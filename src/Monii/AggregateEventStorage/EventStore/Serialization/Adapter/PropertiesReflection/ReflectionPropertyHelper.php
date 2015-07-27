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
     * @var array|null
     */
    private $types;

    /**
     * @var boolean|null
     */
    private $isObject = false;

    /**
     * @var boolean
     */
    private $isArray = false;

    public function __construct(\ReflectionClass $reflectionClass, \ReflectionProperty $reflectionProperty)
    {
        $reflectionProperty->setAccessible(true);

        if (preg_match('/@var\s+([^\s]+)/', $reflectionProperty->getDocComment(), $matches)) {
            list(, $type) = $matches;
            $types = explode("|", $type);
            if (!empty($types)) {
                foreach ($types as $type) {
                    if (class_exists($type)) {
                        // Object
                        $this->types[] = $type;
                        $this->isObject = $type;
                    } else {
                        $type = $reflectionClass->getNamespaceName() . '\\' . $type;
                        if (class_exists($type)) {
                            // Object
                            $this->types[] = $type;
                            $this->isObject = $type;
                        } else {
                            $this->types[] = $type = null;
                        }
                    }
                }
                array_unique($this->types);
            }
        }

        $this->reflectionClass = $reflectionClass;
        $this->reflectionProperty = $reflectionProperty;
    }

    public function isObject()
    {
        return ($this->isObject !== false);
    }

    public function isArray()
    {
        return in_array('array', $this->types);
    }

    public function getType()
    {
        return $this->isObject;
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
