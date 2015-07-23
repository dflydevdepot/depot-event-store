<?php

namespace Monii\AggregateEventStorage\EventStore\Persistence\Adapter\InMemory;

use Monii\AggregateEventStorage\Contract\Contract;
use Monii\AggregateEventStorage\EventStore\EventEnvelope;
use Monii\AggregateEventStorage\EventStore\Persistence\Persistence;
use Monii\AggregateEventStorage\EventStore\Serialization\Serializer;
use Monii\AggregateEventStorage\EventStore\StreamIdentity\StreamId;
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

    public function fetch(StreamId $streamId, Contract $aggregateType = null, $aggregateId = null)
    {
        $eventEnvelopes = [];

        foreach ($this->records as $record) {
            if ($streamId !== $record->streamId) {
                continue;
            }

            if (!is_null($aggregateType) && $aggregateType != $record->aggregateType) {
                continue;
            }

            if (!is_null($aggregateId) && $aggregateId != $record->aggregateId) {
                continue;
            }

            $eventEnvelopes[] = new EventEnvelope(
                $record->eventType,
                $record->eventId,
                $record->event,
                $record->metadataType,
                $record->metadata
            );
        }

        return $eventEnvelopes;
    }

    /**
     * @param CommitId $commitId
     * @param StreamId $streamId
     * @param Contract $aggregateType
     * @param string $aggregateId
     * @param int $expectedAggregateVersion
     * @param EventEnvelope[] $eventEnvelopes
     */
    public function commit(
        CommitId $commitId,
        StreamId $streamId,
        Contract $aggregateType,
        $aggregateId,
        $expectedAggregateVersion,
        array $eventEnvelopes
    ) {
        $aggregateVersion = $expectedAggregateVersion;

        foreach ($eventEnvelopes as $eventEnvelope) {
            $record = new InMemoryPersistenceRecord();

            $record->commitId = $commitId;
            $record->utcComittedTime = new \DateTimeImmutable('now');
            $record->streamId = $streamId;
            $record->aggregateType = $aggregateType;
            $record->aggregateId = $aggregateId;
            $record->aggregateVersion = ++$aggregateVersion;
            $record->eventType = $eventEnvelope->getEventType();
            $record->eventId = $eventEnvelope->getEventId();
            $record->event = $this->eventSerializer->serialize($eventEnvelope->getEvent());
            $record->metadata = $this->metadataSerializer->serialize($eventEnvelope->getMetadata());
        }
    }
}
