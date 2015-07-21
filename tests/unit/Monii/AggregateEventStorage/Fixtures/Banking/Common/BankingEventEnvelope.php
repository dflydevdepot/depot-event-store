<?php

namespace Monii\AggregateEventStorage\Fixtures\Banking\Common;

use Monii\AggregateEventStorage\Aggregate\Support\ChangeReading\AggregateChangeReader;

class BankingEventEnvelope implements AggregateChangeReader
{
    public $event;
    public $metadata;

    private function __construct($event, $metadata = null)
    {
        $this->event = $event;
        $this->metadata = $metadata;
    }

    public static function create($event, $metadata = null)
    {
        return new self($event, $metadata);
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


}
