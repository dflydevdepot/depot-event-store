<?php

namespace Depot\EventStore;

use Depot\Contract\Contract;
use Depot\EventStore\Persistence\Persistence;
use Depot\EventStore\Transaction\CommitId;

class EventStream
{
    /**
     * @var Persistence
     */
    private $persistence;

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
     * @param Contract $aggregateType
     * @param string $aggregateId
     */
    private function __construct(
        Persistence $persistence,
        Contract $aggregateType = null,
        $aggregateId = null
    ) {
        $this->persistence = $persistence;
        $this->aggregateType = $aggregateType;
        $this->aggregateId = $aggregateId;
    }

    public static function create(Persistence $persistence, Contract $aggregateType, $aggregateId)
    {
        $instance = new self($persistence, $aggregateType, $aggregateId);

        return $instance;
    }

    /**
     * @param Persistence $persistence
     * @param Contract $aggregateType
     * @param $aggregateId
     *
     * @return EventStream
     */
    public static function open(Persistence $persistence, Contract $aggregateType, $aggregateId)
    {
        $instance = new self($persistence, $aggregateType, $aggregateId);
        $instance->committedEventEnvelopes = $persistence->fetch($aggregateType, $aggregateId);

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
            $this->aggregateType,
            $this->aggregateId,
            count($this->committedEventEnvelopes) - 1,
            $this->pendingEventEnvelopes
        );
        $this->committedEventEnvelopes = array_merge($this->committedEventEnvelopes, $this->pendingEventEnvelopes);
        $this->pendingEventEnvelopes = [];
    }
}
