<?php

namespace Monii\AggregateEventStorage\EventStore\Persistence\Adapter\InMemory;

use Monii\AggregateEventStorage\Contract\SimplePhpFqcnContractResolver;
use Monii\AggregateEventStorage\EventStore\Persistence\PersistenceTest;
use Monii\Serialization\ReflectionPropertiesSerializer\ReflectionPropertiesSerializer;

class InMemoryPersistenceTest extends PersistenceTest
{
    protected $inMemoryPersistence;

    protected function createPersistence()
    {
        $serializer = new ReflectionPropertiesSerializer(
        );

        return $this->inMemoryPersistence = new InMemoryPersistence(
            $serializer,
            $serializer
        );
    }

    protected function getPersistence()
    {
        return $this->inMemoryPersistence;
    }
}
