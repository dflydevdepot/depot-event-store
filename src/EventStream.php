<?php

namespace Depot\EventStore;

use Depot\Contract\Contract;
use Depot\EventStore\EventEnvelopeDecoration\EventEnvelopeDecorator;
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
     * @var EventEnvelopeDecorator
     */
    private $eventEnvelopeDecorator;

    /**
     * @var EventEnvelope[]
     */
    private $committedEventEnvelopes = [];

    /**
     * @var EventEnvelope[]
     */
    private $pendingEventEnvelopes = [];

    private function __construct(
        Persistence $persistence,
        Contract $aggregateType = null,
        $aggregateId = null,
        EventEnvelopeDecorator $eventEnvelopeDecorator = null
    ) {
        $this->persistence = $persistence;
        $this->aggregateType = $aggregateType;
        $this->aggregateId = $aggregateId;
        $this->eventEnvelopeDecorator = $eventEnvelopeDecorator;
    }

    public static function create(
        Persistence $persistence,
        Contract $aggregateType,
        $aggregateId,
        EventEnvelopeDecorator $eventEnvelopeDecorator = null
    ) {
        $instance = new self($persistence, $aggregateType, $aggregateId, $eventEnvelopeDecorator);

        return $instance;
    }

    /**
     * @param Persistence $persistence
     * @param Contract $aggregateType
     * @param $aggregateId
     *
     * @return EventStream
     */
    public static function open(
        Persistence $persistence,
        Contract $aggregateType,
        $aggregateId,
        EventEnvelopeDecorator $eventEnvelopeDecorator = null
    ) {
        $instance = new self($persistence, $aggregateType, $aggregateId, $eventEnvelopeDecorator);
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
        $pendingEventEnvelopes = $this->pendingEventEnvelopes;

        if ($this->eventEnvelopeDecorator) {
            $pendingEventEnvelopes = array_map(function ($eventEnvelope) {
                return $this->eventEnvelopeDecorator->decorateEventEnvelope($eventEnvelope);
            }, $pendingEventEnvelopes);
        }

        $this->persistence->commit(
            $commitId,
            $this->aggregateType,
            $this->aggregateId,
            count($this->committedEventEnvelopes) - 1,
            $pendingEventEnvelopes
        );

        $this->committedEventEnvelopes = array_merge($this->committedEventEnvelopes, $pendingEventEnvelopes);
        $this->pendingEventEnvelopes = [];
    }
}
