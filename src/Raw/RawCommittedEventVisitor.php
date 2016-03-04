<?php

namespace Depot\EventStore\Raw;

interface RawCommittedEventVisitor
{
    public function doWithRawCommittedEvent(RawCommittedEvent $rawCommittedEvent);
}
