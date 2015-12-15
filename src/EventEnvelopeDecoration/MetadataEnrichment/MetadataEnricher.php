<?php

namespace Depot\EventStore\EventEnvelopeDecoration\MetadataEnrichment;

interface MetadataEnricher
{
    /**
     * @param object|null $metadata
     * @return object|null
     */
    public function enrichMetadata($metadata = null);
}
