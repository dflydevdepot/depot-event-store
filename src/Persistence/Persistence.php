<?php

namespace Depot\EventStore\Persistence;

use Depot\Contract\Contract;
use Depot\EventStore\EventEnvelope;
use Depot\EventStore\Transaction\CommitId;

interface Persistence
{
    /**
     * @param Contract $aggregateRootType
     * @param string $aggregateRootId
     * @return EventEnvelope[]
     */
    public function fetch(Contract $aggregateRootType, $aggregateRootId);

    public function commit(
        CommitId $commitId,
        Contract $aggregateRootType,
        $aggregateRootId,
        $expectedAggregateRootVersion,
        array $eventEnvelopes,
        $now = null
    );
}
