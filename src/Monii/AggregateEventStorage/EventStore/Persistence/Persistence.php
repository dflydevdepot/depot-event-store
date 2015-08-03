<?php

namespace Monii\AggregateEventStorage\EventStore\Persistence;

use Monii\AggregateEventStorage\Contract\Contract;
use Monii\AggregateEventStorage\EventStore\Transaction\CommitId;

interface Persistence
{
    public function fetch(Contract $aggregateType, $aggregateId);

    public function commit(
        CommitId $commitId,
        Contract $aggregateType,
        $aggregateId,
        $expectedAggregateVersion,
        array $eventEnvelopes
    );
}
