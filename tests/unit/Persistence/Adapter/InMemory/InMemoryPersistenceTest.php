<?php

namespace Depot\EventStore\Persistence\Adapter\InMemory;

use Depot\Contract\SimplePhpFqcnContractResolver;
use Depot\Testing\EventStore\Persistence\PersistenceTest;
use Depot\EventStore\Serialization\Adapter\MoniiReflectionPropertiesSerializer\MoniiReflectionPropertiesSerializer;

class InMemoryPersistenceTest extends PersistenceTest
{
    protected $inMemoryPersistence;

    protected function createPersistence()
    {
        $serializer = new MoniiReflectionPropertiesSerializer(
            new SimplePhpFqcnContractResolver()
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
