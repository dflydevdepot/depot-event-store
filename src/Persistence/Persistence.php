<?php

namespace Depot\EventStore\Persistence;

use Depot\Contract\Contract;
use Depot\EventStore\EventEnvelope;
use Depot\EventStore\Transaction\CommitId;

interface Persistence
{
    /**
     * @param Contract $aggregateType
     * @param string $aggregateId
     * @return EventEnvelope[]
     */
    public function fetch(Contract $aggregateType, $aggregateId);

    public function commit(
        CommitId $commitId,
        Contract $aggregateType,
        $aggregateId,
        $expectedAggregateVersion,
        array $eventEnvelopes
    );
}
