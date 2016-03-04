<?php

namespace Depot\EventStore;

use DateTimeImmutable;
use Depot\Contract\Contract;
use Depot\EventStore\Transaction\CommitId;

class RawCommittedEvent
{
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
     * @var RawEventEnvelope
     */
    private $rawEventEnvelope;

    /**
     * CommittedEvent constructor.
     * @param CommitId $commitId
     * @param DateTimeImmutable $utcCommittedTime
     * @param Contract $aggregateRootType
     * @param string $aggregateRootId
     * @param int $aggregateRootVersion
     * @param RawEventEnvelope $rawEventEnvelope
     */
    public function __construct(
        CommitId $commitId,
        DateTimeImmutable $utcCommittedTime,
        Contract $aggregateRootType,
        $aggregateRootId,
        $aggregateRootVersion,
        RawEventEnvelope $rawEventEnvelope
    ) {
        $this->commitId = $commitId;
        $this->utcCommittedTime = $utcCommittedTime;
        $this->aggregateRootType = $aggregateRootType;
        $this->aggregateRootId = $aggregateRootId;
        $this->aggregateRootVersion = $aggregateRootVersion;
        $this->rawEventEnvelope = $rawEventEnvelope;
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
     * @return RawEventEnvelope
     */
    public function getRawEventEnvelope()
    {
        return $this->rawEventEnvelope;
    }
}
