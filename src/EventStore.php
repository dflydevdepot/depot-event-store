<?php

namespace Depot\EventStore;

use Depot\Contract\Contract;
use Depot\EventStore\EventEnvelopeDecoration\EventEnvelopeDecorator;
use Depot\EventStore\Persistence\Persistence;

class EventStore
{
    /**
     * @var Persistence
     */
    private $persistence;

    /**
     * @var EventEnvelopeDecorator
     */
    private $eventEnvelopeDecorator;

    public function __construct(
        Persistence $persistence,
        EventEnvelopeDecorator $eventEnvelopeDecorator = null
    ) {
        $this->persistence = $persistence;
        $this->eventEnvelopeDecorator = $eventEnvelopeDecorator;
    }

    public function create(Contract $aggregateRootType, $aggregateRootId)
    {
        return EventStream::create(
            $this->persistence,
            $aggregateRootType,
            $aggregateRootId,
            $this->eventEnvelopeDecorator
        );
    }

    public function open(Contract $aggregateRootType, $aggregateRootId)
    {
        return EventStream::open(
            $this->persistence,
            $aggregateRootType,
            $aggregateRootId,
            $this->eventEnvelopeDecorator
        );
    }
}
