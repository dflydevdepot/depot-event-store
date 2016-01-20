<?php

namespace Depot\EventStore\Management;

use Depot\EventStore\CommittedEventVisitor;

interface EventStoreManagement
{
    public function visitCommittedEvents(Criteria $criteria, CommittedEventVisitor $committedEventVisitor);
}
