<?php

namespace Monii\AggregateEventStorage\EventStore;

use Monii\AggregateEventStorage\Contract\Contract;
use Monii\AggregateEventStorage\EventStore\Persistence\Persistence;
use Monii\AggregateEventStorage\EventStore\StreamIdentity\StreamId;

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

    public function createAggregateStream(StreamId $streamId, Contract $aggregateType, $aggregateId)
    {
        return EventStream::create($this->persistence, $streamId, $aggregateType, $aggregateId);
    }

    public function openStream(StreamId $streamId)
    {
        return EventStream::open($this->persistence, $streamId);
    }

    public function openAggregateStream(StreamId $streamId, Contract $aggregateType)
    {
        return EventStream::openForAggregateType($this->persistence, $streamId, $aggregateType);
    }

    public function openAggregateInstanceStream(StreamId $streamId, Contract $aggregateType, $aggregateId)
    {
        return EventStream::openForAggregateId($this->persistence, $streamId, $aggregateType, $aggregateId);
    }
}
