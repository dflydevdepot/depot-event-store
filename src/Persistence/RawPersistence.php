<?php

namespace Depot\EventStore\Persistence;

use Depot\Contract\Contract;
use Depot\EventStore\RawEventEnvelope;
use Depot\EventStore\Transaction\CommitId;

interface RawPersistence
{
    /**
     * @param Contract $aggregateRootType
     * @param string $aggregateRootId
     * @return RawEventEnvelope[]
     */
    public function fetchRaw(Contract $aggregateRootType, $aggregateRootId);

    /**
     * @param CommitId $commitId
     * @param Contract $aggregateRootType
     * @param $aggregateRootId
     * @param $expectedAggregateRootVersion
     * @param RawEventEnvelope[] $rawEventEnvelopes
     * @param null $now
     * @return mixed
     */
    public function commitRaw(
        CommitId $commitId,
        Contract $aggregateRootType,
        $aggregateRootId,
        $expectedAggregateRootVersion,
        array $eventEnvelopes,
        $now = null
    );
}
