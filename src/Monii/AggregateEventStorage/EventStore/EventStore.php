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

    public function openAggregateInstanceStream(Contract $aggregateType, $aggregateId)
    {
        return EventStream::open($this->persistence, $aggregateType, $aggregateId);
    }
}
