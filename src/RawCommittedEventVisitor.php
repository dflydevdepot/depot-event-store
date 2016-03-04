<?php

namespace Depot\EventStore;

interface RawCommittedEventVisitor
{
    public function doWithRawCommittedEvent(RawCommittedEvent $rawCommittedEvent);
}
