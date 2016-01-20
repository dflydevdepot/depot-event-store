<?php

namespace Depot\EventStore\Management;

use Depot\EventStore\EventVisitor;

interface EventStoreManagement
{
    public function visitEvents(Criteria $criteria, EventVisitor $eventVisitor);
}
