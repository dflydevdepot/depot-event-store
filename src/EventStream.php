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
    private $aggregateRootType;

    /**
     * @var string
     */
    private $aggregateRootId;

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
        Contract $aggregateRootType = null,
        $aggregateRootId = null,
        EventEnvelopeDecorator $eventEnvelopeDecorator = null
    ) {
        $this->persistence = $persistence;
        $this->aggregateRootType = $aggregateRootType;
        $this->aggregateRootId = $aggregateRootId;
        $this->eventEnvelopeDecorator = $eventEnvelopeDecorator;
    }

    public static function create(
        Persistence $persistence,
        Contract $aggregateRootType,
        $aggregateRootId,
        EventEnvelopeDecorator $eventEnvelopeDecorator = null
    ) {
        $instance = new self($persistence, $aggregateRootType, $aggregateRootId, $eventEnvelopeDecorator);

        return $instance;
    }

    /**
     * @param Persistence $persistence
     * @param Contract $aggregateRootType
     * @param $aggregateRootId
     *
     * @return EventStream
     */
    public static function open(
        Persistence $persistence,
        Contract $aggregateRootType,
        $aggregateRootId,
        EventEnvelopeDecorator $eventEnvelopeDecorator = null
    ) {
        $instance = new self($persistence, $aggregateRootType, $aggregateRootId, $eventEnvelopeDecorator);
        $instance->committedEventEnvelopes = $persistence->fetch($aggregateRootType, $aggregateRootId);

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
     * @param \DateTimeImmutable|null $now
     *
     * @return void
     */
    public function commit(CommitId $commitId, $now = null)
    {
        $pendingEventEnvelopes = $this->pendingEventEnvelopes;

        if ($this->eventEnvelopeDecorator) {
            $pendingEventEnvelopes = array_map(function ($eventEnvelope) {
                return $this->eventEnvelopeDecorator->decorateEventEnvelope($eventEnvelope);
            }, $pendingEventEnvelopes);
        }

        $this->persistence->commit(
            $commitId,
            $this->aggregateRootType,
            $this->aggregateRootId,
            count($this->committedEventEnvelopes) - 1,
            $pendingEventEnvelopes,
            $now
        );

        $this->committedEventEnvelopes = array_merge($this->committedEventEnvelopes, $pendingEventEnvelopes);
        $this->pendingEventEnvelopes = [];
    }
}
