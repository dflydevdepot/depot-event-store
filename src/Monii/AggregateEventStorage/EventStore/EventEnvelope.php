<?php

namespace Monii\AggregateEventStorage\EventStore;

use Monii\AggregateEventStorage\Contract\Contract;

class EventEnvelope
{
    /**
     * @var Contract
     */
    private $eventType;

    /**
     * @var string
     */
    private $eventId;

    /**
     * @var object
     */
    private $event;

    /**
     * @var object
     */
    private $metadata;

    public function __construct(
        Contract $eventType,
        $eventId,
        $event,
        $metadata = null
    ) {
        $this->eventType = $eventType;
        $this->eventId = $eventId;
        $this->event = $event;
        $this->metadata = $metadata;
    }

    /**
     * @return Contract
     */
    public function getEventType()
    {
        return $this->eventType;
    }

    /**
     * @return string
     */
    public function getEventId()
    {
        return $this->eventId;
    }

    /**
     * @return object
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @return object|null
     */
    public function getMetadata()
    {
        return $this->metadata;
    }
}
