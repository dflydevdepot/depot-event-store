<?php

namespace Monii\AggregateEventStorage\EventStore;

use Monii\AggregateEventStorage\Contract\Contract;
use Monii\AggregateEventStorage\EventStore\Persistence\Persistence;
use Monii\AggregateEventStorage\EventStore\StreamIdentity\StreamId;
use Monii\AggregateEventStorage\EventStore\Transaction\CommitId;

class EventStream
{
    /**
     * @var Persistence
     */
    private $persistence;

    /**
     * @var StreamId
     */
    private $streamId;

    /**
     * @var Contract
     */
    private $aggregateType;

    /**
     * @var string
     */
    private $aggregateId;

    /**
     * @var EventEnvelope[]
     */
    private $committedEventEnvelopes = [];

    /**
     * @var EventEnvelope[]
     */
    private $pendingEventEnvelopes = [];

    /**
     * @param Persistence $persistence
     * @param StreamId $streamId
     * @param Contract $aggregateType
     * @param string $aggregateId
     */
    private function __construct(
        Persistence $persistence,
        StreamId $streamId,
        Contract $aggregateType = null,
        $aggregateId = null
    ) {
        $this->persistence = $persistence;
        $this->streamId = $streamId;
        $this->aggregateType = $aggregateType;
        $this->aggregateId = $aggregateId;
    }

    public static function create(Persistence $persistence, StreamId $streamId)
    {
        $instance = new self($persistence, $streamId);

        return $instance;
    }

    /**
     * @param Persistence $persistence
     * @param StreamId $streamId
     *
     * @return EventStream
     */
    public static function open(Persistence $persistence, StreamId $streamId)
    {
        $instance = new self($persistence, $streamId);
        $instance->committedEventEnvelopes = $persistence->fetch($streamId);

        return $instance;
    }

    /**
     * @param Persistence $persistence
     * @param StreamId $streamId
     * @param Contract $aggregateType
     *
     * @return EventStream
     */
    public static function openForAggregateType(Persistence $persistence, StreamId $streamId, Contract $aggregateType)
    {
        $instance = new self($persistence, $streamId);
        $instance->committedEventEnvelopes = $persistence->fetch($streamId, $aggregateType);

        return $instance;
    }

    /**
     * @param Persistence $persistence
     * @param StreamId $streamId
     * @param Contract $aggregateType
     * @param string $aggregateId
     *
     * @return EventStream
     */
    public static function openForAggregateId(
        Persistence $persistence,
        StreamId $streamId,
        Contract $aggregateType,
        $aggregateId
    ) {
        $instance = new self($persistence, $streamId);
        $instance->committedEventEnvelopes = $persistence->fetch($streamId, $aggregateType, $aggregateId);

        return $instance;
    }

    /**
     * @return EventEnvelope[]
     */
    public function all()
    {
        return array_merge($this->committedEventEnvelopes, $this->pendingEventEnvelopes);
    }

    /**
     * @param EventEnvelope $eventEnvelope
     *
     * @return void
     */
    public function append(EventEnvelope $eventEnvelope)
    {
        $this->pendingEventEnvelopes[] = $eventEnvelope;
    }

    /**
     * @param EventEnvelope[] $eventEnvelopes
     *
     * @return void
     */
    public function appendAll(array $eventEnvelopes)
    {
        $this->pendingEventEnvelopes = array_merge($this->pendingEventEnvelopes, $eventEnvelopes);
    }

    /**
     * @param CommitId $commitId
     *
     * @return void
     */
    public function commit(CommitId $commitId)
    {
        $this->persistence->commit(
            $commitId,
            $this->streamId,
            $this->aggregateType,
            $this->aggregateId,
            count($this->committedEventEnvelopes),
            $this->pendingEventEnvelopes
        );
        $this->committedEventEnvelopes = array_merge($this->committedEventEnvelopes, $this->pendingEventEnvelopes);
        $this->pendingEventEnvelopes = [];
    }
}
