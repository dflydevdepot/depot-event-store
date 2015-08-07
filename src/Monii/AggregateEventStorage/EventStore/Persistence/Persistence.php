<?php

namespace Monii\AggregateEventStorage\EventStore\Persistence;

use Monii\AggregateEventStorage\Contract\Contract;
use Monii\AggregateEventStorage\EventStore\EventEnvelope;
use Monii\AggregateEventStorage\EventStore\Transaction\CommitId;

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
