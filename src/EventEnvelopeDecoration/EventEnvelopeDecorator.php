<?php

namespace Depot\EventStore\EventEnvelopeDecoration;

use Depot\EventStore\EventEnvelope;

interface EventEnvelopeDecorator
{
    /**
     * @param $eventEnvelope
     * @return mixed
     */
    public function decorateEventEnvelope(EventEnvelope $eventEnvelope);
}
