<?php

namespace Depot\EventStore;

class CallableRawCommittedEventVisitor implements RawCommittedEventVisitor
{
    /**
     * @var callable
     */
    private $callable;

    public function __construct(callable $callable)
    {
        $this->callable = $callable;
    }

    public function doWithRawCommittedEvent(RawCommittedEvent $rawCommittedEvent)
    {
        call_user_func($this->callable, $rawCommittedEvent);
    }
}
