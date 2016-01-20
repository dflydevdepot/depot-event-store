<?php

namespace Depot\EventStore;

use Depot\EventStore\Persistence\CommittedEvent;

interface EventVisitor
{
    public function doWithCommittedEvent(CommittedEvent $committedEvent);
}
