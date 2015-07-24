<?php

namespace Monii\AggregateEventStorage\EventStore\Serialization;

class SerializerChain implements Serializer
{
    /**
     * @var array
     */
    private $serializers;

    public function __construct(
        $serializers = null
    ){
        $this->serializers = $serializers;
    }

    /**
     * (@inheritdoc)
     */
    public function canSerialize($type, $object)
    {

    }

    /**
     * (@inheritdoc)
     */
    public function serialize($type, $object)
    {

    }
    /**
     * (@inheritdoc)
     */
    public function canDeserialize($type, array $data)
    {

    }

    /**
     * (@inheritdoc)
     */
    public function deserialize($type, array $data)
    {

    }

    public function  pushSerializer($serializer)
    {
        $serializers[] = $serializer;
    }

    public function unshiftSerializer($serializer)
    {
        array_unshift($serializers, $serializer);
    }
}
