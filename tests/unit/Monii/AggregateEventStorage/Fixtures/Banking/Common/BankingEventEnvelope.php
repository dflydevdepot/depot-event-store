<?php

namespace Monii\AggregateEventStorage\Fixtures\Banking\Common;

use Monii\AggregateEventStorage\Aggregate\Support\ChangeReading\AggregateChangeReader;

class BankingEventEnvelope implements AggregateChangeReader
{
    public $event;
    public $metadata;
    public $eventId;

    private function __construct($eventId, $event, $metadata = null)
    {
        $this->eventId;
        $this->event = $event;
        $this->metadata = $metadata;
    }

    public static function create($eventId, $event, $metadata = null)
    {
        return new self($eventId, $event, $metadata);
    }

    public static function instantiateAggregateChangeFromEventAndMetadata($event, $metadata = null)
    {
        return new self($event, $metadata);
    }

    /**
     * @return object
     */
    public function getAggregateEvent()
    {
        return $this->event;
    }

    /**
     * @return object
     */
    public function getAggregateMetadata()
    {
        return $this->metadata;
    }

    /**
     * @return object
     */
    public function getCanReadAggregateEventId()
    {
        return true;
    }

    /**
     * @return object
     */
    public function getAggregateEventId()
    {
        return $this->eventId;
    }

}
