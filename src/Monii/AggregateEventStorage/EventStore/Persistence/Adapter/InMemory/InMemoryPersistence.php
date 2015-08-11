<?php

namespace Monii\AggregateEventStorage\EventStore\Persistence\Adapter\InMemory;

use Monii\AggregateEventStorage\Contract\Contract;
use Monii\AggregateEventStorage\EventStore\EventEnvelope;
use Monii\AggregateEventStorage\EventStore\Persistence\OptimisticConcurrencyFailed;
use Monii\AggregateEventStorage\EventStore\Persistence\Persistence;
use Monii\Serialization\ReflectionPropertiesSerializer\ReflectionPropertiesSerializer;
use Monii\AggregateEventStorage\EventStore\Transaction\CommitId;

class InMemoryPersistence implements Persistence
{
    /**
     * @var ReflectionPropertiesSerializer
     */
    private $eventSerializer;

    /**
     * @var ReflectionPropertiesSerializer
     */
    private $metadataSerializer;

    /**
     * @var InMemoryPersistenceRecord[]
     */
    private $records = [];

    public function __construct(
        ReflectionPropertiesSerializer $eventSerializer,
        ReflectionPropertiesSerializer $metadataSerializer
    ) {
        $this->eventSerializer = $eventSerializer;
        $this->metadataSerializer = $metadataSerializer;
    }

    public function fetch(Contract $aggregateType, $aggregateId)
    {
        $eventEnvelopes = [];

        foreach ($this->records as $record) {
            if ($aggregateType != $record->aggregateType) {
                continue;
            }

            if ($aggregateId != $record->aggregateId) {
                continue;
            }
            $metadata = $record->metadataType
                ? $this->metadataSerializer->deserialize($record->metadataType, $record->metadata)
                : null
            ;
            $eventEnvelopes[] = new EventEnvelope(
                $record->eventType,
                $record->eventId,
                $this->eventSerializer->deserialize($record->eventType, $record->event),
                $record->version,
                $record->metadataType,
                $metadata
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
        $aggregateVersion = $this->versionFor($aggregateType, $aggregateId);

        if ($aggregateVersion !== $expectedAggregateVersion) {
            throw new OptimisticConcurrencyFailed();
        }

        foreach ($eventEnvelopes as $eventEnvelope) {

            $metadata = $eventEnvelope->getMetadataType()
                ? $this->metadataSerializer->serialize($eventEnvelope->getMetadataType(), $eventEnvelope->getMetadata())
                : null
            ;
            $record = new InMemoryPersistenceRecord();

            $record->commitId = $commitId;
            $record->utcCommittedTime = new \DateTimeImmutable('now');
            $record->aggregateType = $aggregateType;
            $record->aggregateId = $aggregateId;
            $record->aggregateVersion = ++$aggregateVersion;
            $record->eventType = $eventEnvelope->getEventType();
            $record->eventId = $eventEnvelope->getEventId();
            $record->event = $this->eventSerializer->serialize(
                $eventEnvelope->getEventType(),
                $eventEnvelope->getEvent()
            );
            $record->version = $eventEnvelope->getVersion();
            $record->metadataType = $eventEnvelope->getMetadataType();
            $record->metadata = $metadata;

            $this->records[] = $record;
        }
    }

    private function versionFor(Contract $aggregateType, $aggregateId)
    {
        $version = -1;

        foreach ($this->fetch($aggregateType, $aggregateId) as $eventEnvelope) {
            if ($eventEnvelope->getVersion() > $version) {
                $version = $eventEnvelope->getVersion();
            }
        }

        return $version;
    }
}
