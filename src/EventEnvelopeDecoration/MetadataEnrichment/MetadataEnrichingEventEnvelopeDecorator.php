<?php

namespace Depot\EventStore\EventEnvelopeDecoration\MetadataEnrichment;

use Depot\EventStore\EventEnvelope;
use Depot\EventStore\EventEnvelopeDecoration\EventEnvelopeDecorator;

class MetadataEnrichingEventEnvelopeDecorator implements EventEnvelopeDecorator
{
    /**
     * @var MetadataEnricher
     */
    private $metadataEnricher;

    public function __construct(MetadataEnricher $metadataEnricher)
    {
        $this->metadataEnricher = $metadataEnricher;
    }

    public function decorateEventEnvelope(EventEnvelope $eventEnvelope)
    {
        $metadata = $this->metadataEnricher->enrichMetadata($eventEnvelope->getMetadata());

        return $eventEnvelope->withMetadata($metadata);
    }
}
