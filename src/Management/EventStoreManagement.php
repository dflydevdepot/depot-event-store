<?php

namespace Depot\EventStore\Management;

use Depot\EventStore\CommittedEventVisitor;
use Depot\EventStore\Raw\RawCommittedEventVisitor;

interface EventStoreManagement
{
    public function visitCommittedEvents(
        Criteria $criteria,
        CommittedEventVisitor $committedEventVisitor,
        RawCommittedEventVisitor $fallbackRawCommittedEventVisitor = null
    );

    public function visitRawCommittedEvents(
        Criteria $criteria,
        RawCommittedEventVisitor $fallbackRawCommittedEventVisitor
    );
}
