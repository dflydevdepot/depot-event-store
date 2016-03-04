<?php

namespace Depot\EventStore\Management;

use Depot\EventStore\RawCommittedEventVisitor;

interface RawEventStoreManagement
{
    public function visitRawCommittedEvents(Criteria $criteria, RawCommittedEventVisitor $rawCommittedEventVisitor);
}
