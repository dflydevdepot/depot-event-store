<?php

namespace Monii\AggregateEventStorage\EventStore\Persistence;

use Monii\AggregateEventStorage\Contract\Contract;
use Monii\AggregateEventStorage\EventStore\StreamIdentity\StreamId;
use Monii\AggregateEventStorage\EventStore\Transaction\CommitId;

interface Persistence
{
    public function fetch(StreamId $streamIdId, Contract $aggregateType = null, $aggregateId = null);

    public function commit(
        CommitId $commitId,
        StreamId $streamIdId,
        Contract $aggregateType,
        $aggregateId,
        $expectedAggregateVersion,
        array $eventEnvelopes
    );
}
