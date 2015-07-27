<?php

namespace Monii\AggregateEventStorage\EventStore;

use Monii\AggregateEventStorage\Contract\Contract;
use Monii\AggregateEventStorage\EventStore\Persistence\Persistence;

class EventStore
{
    /**
     * @var Persistence
     */
    private $persistence;

    public function __construct(Persistence $persistence)
    {
        $this->persistence = $persistence;
    }

    public function createAggregateStream(Contract $aggregateType, $aggregateId)
    {
        return EventStream::create($this->persistence, $aggregateType, $aggregateId);
    }

    public function openAggregateStream(Contract $aggregateType)
    {
        return EventStream::openForAggregateType($this->persistence, $aggregateType);
    }

    public function openAggregateInstanceStream(Contract $aggregateType, $aggregateId)
    {
        return EventStream::openForAggregateId($this->persistence, $aggregateType, $aggregateId);
    }
}
