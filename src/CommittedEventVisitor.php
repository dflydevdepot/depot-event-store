<?php

namespace Depot\EventStore;

use Depot\EventStore\Persistence\CommittedEvent;

interface CommittedEventVisitor
{
    public function doWithCommittedEvent(CommittedEvent $committedEvent);
}
