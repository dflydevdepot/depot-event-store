<?php

namespace Depot\EventStore;

use Depot\Contract\Contract;
use Depot\EventStore\Persistence\Persistence;

class EventStore
{
    /**
     * @var Persistence
     */
    private $persistence;

    public function __construct(Persistence $persistence)
    {
        $this->persistence = $persistence;
    }

    public function create(Contract $aggregateType, $aggregateId)
    {
        return EventStream::create($this->persistence, $aggregateType, $aggregateId);
    }

    public function open(Contract $aggregateType, $aggregateId)
    {
        return EventStream::open($this->persistence, $aggregateType, $aggregateId);
    }
}
