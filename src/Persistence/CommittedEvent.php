<?php

namespace Depot\EventStore\Persistence;

use DateTimeImmutable;
use Depot\Contract\Contract;
use Depot\EventStore\EventEnvelope;
use Depot\EventStore\Transaction\CommitId;

class CommittedEvent
{
    /**
     * @var int
     */
    private $committedEventId;

    /**
     * @var CommitId
     */
    private $commitId;

    /**
     * @var \DateTimeImmutable
     */
    private $utcCommittedTime;

    /**
     * @var Contract
     */
    private $aggregateRootType;

    /**
     * @var string
     */
    private $aggregateRootId;

    /**
     * @var int
     */
    private $aggregateRootVersion;

    /**
     * @var EventEnvelope
     */
    private $eventEnvelope;

    /**
     * CommittedEvent constructor.
     * @param int $committedEventId
     * @param CommitId $commitId
     * @param DateTimeImmutable $utcCommittedTime
     * @param Contract $aggregateRootType
     * @param string $aggregateRootId
     * @param int $aggregateRootVersion
     * @param EventEnvelope $eventEnvelope
     */
    public function __construct(
        $committedEventId,
        CommitId $commitId,
        DateTimeImmutable $utcCommittedTime,
        Contract $aggregateRootType,
        $aggregateRootId,
        $aggregateRootVersion,
        EventEnvelope $eventEnvelope
    ) {
        $this->committedEventId = $committedEventId;
        $this->commitId = $commitId;
        $this->utcCommittedTime = $utcCommittedTime;
        $this->aggregateRootType = $aggregateRootType;
        $this->aggregateRootId = $aggregateRootId;
        $this->aggregateRootVersion = $aggregateRootVersion;
        $this->eventEnvelope = $eventEnvelope;
    }

    /**
     * @return int
     */
    public function getCommittedEventId()
    {
        return $this->committedEventId;
    }

    /**
     * @return CommitId
     */
    public function getCommitId()
    {
        return $this->commitId;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getUtcCommittedTime()
    {
        return $this->utcCommittedTime;
    }

    /**
     * @return Contract
     */
    public function getAggregateRootType()
    {
        return $this->aggregateRootType;
    }

    /**
     * @return string
     */
    public function getAggregateRootId()
    {
        return $this->aggregateRootId;
    }

    /**
     * @return int
     */
    public function getAggregateRootVersion()
    {
        return $this->aggregateRootVersion;
    }

    /**
     * @return EventEnvelope
     */
    public function getEventEnvelope()
    {
        return $this->eventEnvelope;
    }
}
