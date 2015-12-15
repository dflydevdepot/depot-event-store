<?php

namespace Depot\EventStore\EventEnvelopeDecoration\MetadataEnrichment;

class MetadataEnricherChain implements MetadataEnricher
{
    /**
     * @var MetadataEnricher[]
     */
    private $metadataEnrichers;

    public function __construct(
        $metadataEnrichers = []
    ) {
        $this->metadataEnrichers = $metadataEnrichers;
    }

    /**
     * @param object|null $metadata
     * @return object|null
     */
    public function enrichMetadata($metadata = null)
    {
        foreach ($this->metadataEnrichers as $metadataEnricher) {
            $metadata = $metadataEnricher->enrichMetadata($metadata);
        }

        return $metadata;
    }

    public function pushMetadataEnricher($metadataEnricher)
    {
        $this->metadataEnrichers[] = $metadataEnricher;
    }

    public function unshiftMetadataEnricher($metadataEnricher)
    {
        array_unshift($metadataEnrichers, $metadataEnricher);
    }
}
