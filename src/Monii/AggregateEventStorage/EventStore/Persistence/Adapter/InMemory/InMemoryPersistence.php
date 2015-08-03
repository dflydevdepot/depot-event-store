<?php

namespace Monii\AggregateEventStorage\EventStore\Persistence\Adapter\InMemory;

use Monii\AggregateEventStorage\Contract\Contract;
use Monii\AggregateEventStorage\EventStore\EventEnvelope;
use Monii\AggregateEventStorage\EventStore\Persistence\Persistence;
use Monii\AggregateEventStorage\EventStore\Serialization\Serializer;
use Monii\AggregateEventStorage\EventStore\Transaction\CommitId;

class InMemoryPersistence implements Persistence
{
    /**
     * @var Serializer
     */
    private $eventSerializer;

    /**
     * @var Serializer
     */
    private $metadataSerializer;

    /**
     * @var InMemoryPersistenceRecord[]
     */
    private $records = [];

    public function __construct(
        Serializer $eventSerializer,
        Serializer $metadataSerializer
    ) {
        $this->eventSerializer = $eventSerializer;
        $this->metadataSerializer = $metadataSerializer;
    }

    public function fetch(Contract $aggregateType = null, $aggregateId = null)
    {
        $eventEnvelopes = [];

        foreach ($this->records as $record) {
            if (!is_null($aggregateType) && $aggregateType != $record->aggregateType) {
                continue;
            }

            if (!is_null($aggregateId) && $aggregateId != $record->aggregateId) {
                continue;
            }

            $eventEnvelopes[] = new EventEnvelope(
                $record->eventType,
                $record->eventId,
                $this->eventSerializer->deserialize($record->eventType, $record->event),
                $record->metadataType,
                $record->metadataType
                    ? $this->metadataSerializer->deserialize($record->metadataType, $record->metadata)
                    : null
            );
        }

        return $eventEnvelopes;
    }

    /**
     * @param CommitId $commitId
     * @param Contract $aggregateType
     * @param string $aggregateId
     * @param int $expectedAggregateVersion
     * @param EventEnvelope[] $eventEnvelopes
     */
    public function commit(
        CommitId $commitId,
        Contract $aggregateType,
        $aggregateId,
        $expectedAggregateVersion,
        array $eventEnvelopes
    ) {
        $aggregateVersion = $expectedAggregateVersion;

        foreach ($eventEnvelopes as $eventEnvelope) {
            $record = new InMemoryPersistenceRecord();

            $record->commitId = $commitId;
            $record->utcCommittedTime = new \DateTimeImmutable('now');
            $record->aggregateType = $aggregateType;
            $record->aggregateId = $aggregateId;
            $record->aggregateVersion = ++$aggregateVersion;
            $record->eventType = $eventEnvelope->getEventType();
            $record->eventId = $eventEnvelope->getEventId();
            $record->event = $this->eventSerializer->serialize($eventEnvelope->getEventType(), $eventEnvelope->getEvent());
            $record->metadataType = $eventEnvelope->getMetadataType();
            $record->metadata = $eventEnvelope->getMetadataType()
                ? $this->metadataSerializer->serialize( $eventEnvelope->getMetadataType(), $eventEnvelope->getMetadata())
                : null
            ;

            $this->records[] = $record;
        }
    }
}
