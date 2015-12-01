<?php

namespace Depot\EventStore\Persistence\Adapter\InMemory;

use DateTimeImmutable;
use Depot\Contract\Contract;
use Depot\EventStore\Transaction\CommitId;

class InMemoryPersistenceRecord
{
    /**
     * @var CommitId
     */
    public $commitId;

    /**
     * @var \DateTimeImmutable
     */
    public $utcCommittedTime;

    /**
     * @var Contract
     */
    public $aggregateType;

    /**
     * @var string
     */
    public $aggregateId;

    /**
     * @var int
     */
    public $aggregateVersion;

    /**
     * @var Contract
     */
    public $eventType;

    /**
     * @var string
     */
    public $eventId;

    /**
     * @var array
     */
    public $event;

    /**
     * @var int
     */
    public $version;

    /**
     * @var DateTimeImmutable
     */
    public $when;

    /**
     * @var Contract|null
     */
    public $metadataType;

    /**
     * @var array
     */
    public $metadata;
}
