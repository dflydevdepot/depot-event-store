<?php

namespace Monii\AggregateEventStorage\Aggregate;

use Monii\AggregateEventStorage\Aggregate\Error\AggregateRootIsAlreadyTracked;
use Monii\AggregateEventStorage\Contract\Contract;
use Monii\AggregateEventStorage\Contract\ContractResolver;
use Monii\AggregateEventStorage\EventStore\EventEnvelope;
use Monii\AggregateEventStorage\EventStore\EventIdentity\EventIdGenerator;
use Monii\AggregateEventStorage\EventStore\EventStore;
use Monii\AggregateEventStorage\EventStore\StreamIdentity\StreamId;
use Monii\AggregateEventStorage\EventStore\Transaction\CommitId;

class UnitOfWork
{
    /**
     * @var EventStore
     */
    private $eventStore;

    /**
     * @var StreamId
     */
    private $streamId;

    /**
     * @var AggregateManipulator
     */
    private $aggregateManipulator;

    /**
     * @var AggregateChangeManipulator
     */
    private $aggregateChangeManipulator;

    /**
     * @var ContractResolver
     */
    private $eventContractResolver;

    /**
     * @var EventIdGenerator
     */
    private $eventIdGenerator;

    /**
     * @var object[]
     */
    private $trackedAggregates = [];

    public function __construct(
        EventStore $eventStore,
        Contract $stream,
        AggregateManipulatorTest $aggregateManipulator,
        AggregateChangeManipulator $aggregateChangeManipulator,
        ContractResolver $eventContractResolver,
        ContractResolver $metadataContractResolver,
        EventIdGenerator $eventIdGenerator = null
    ) {
        $this->eventStore = $eventStore;
        $this->stream = $stream;
        $this->aggregateManipulator = $aggregateManipulator;
        $this->aggregateChangeManipulator = $aggregateChangeManipulator;
        $this->eventContractResolver = $eventContractResolver;
        $this->metadataContractResolver = $metadataContractResolver;
        $this->eventIdGenerator = $eventIdGenerator;
    }

    /**
     * @param Contract $aggregateType
     * @param string $aggregateId
     * @param object $aggregate
     */
    public function track(Contract $aggregateType, $aggregateId, $aggregate)
    {
        $trackedAggregate = $this->findTrackedAggregate($aggregateType, $aggregateId);

        if (! is_null($trackedAggregate)) {
            throw new AggregateRootIsAlreadyTracked();
        }

        $this->ensureTrackedAggregateTypeIsPrepared($aggregateType);

        $this->trackedAggregates[$aggregateType->getContractName()]['aggregates'][] = $aggregate;
    }

    private function ensureTrackedAggregateTypeIsPrepared(Contract $aggregateType)
    {
        if ($this->isTrackedAggregateTypeIsPrepared($aggregateType)) {
            return;
        }

        $this->trackedAggregates[$aggregateType->getContractName()] = [
            'contract' => $aggregateType,
            'aggregates' => [],
        ];
    }

    private function isTrackedAggregateTypeIsPrepared(Contract $aggregateType)
    {
        return array_key_exists($aggregateType->getContractName(), $this->trackedAggregates);
    }

    public function commit()
    {
        $commitId = new CommitId();

        foreach ($this->trackedAggregates as $trackedAggregateTypes) {
            $aggregateType = $trackedAggregateTypes['contract'];
            foreach ($trackedAggregateTypes['aggregates'] as $aggregate) {
                $this->persist(
                    $aggregateType,
                    $this->aggregateManipulator->identify($aggregate),
                    $aggregate,
                    $commitId
                );
            }
        }
    }

    private function persist(Contract $aggregateType, $aggregateId, $aggregate, CommitId $commitId)
    {
        $this->ensureTrackedAggregateTypeIsPrepared($aggregateType);

        $changes = $this->aggregateManipulator->extractChanges($aggregate);

        $eventEnvelopes = [];
        foreach ($changes as $change) {
            $eventId = $this->eventIdGenerator->generateEventId();
            $event = $this->aggregateChangeManipulator->readEvent($change);
            $metadata = $this->aggregateChangeManipulator->readMetadata($change);

            $eventEnvelopes[] = new EventEnvelope(
                $this->eventContractResolver->resolveFromObject($event),
                $eventId,
                $event,
                $this->metadataContractResolver->resolveFromObject($metadata),
                $metadata
            );
        }

        $eventStream = $this->eventStore->createAggregateStream($this->streamId, $aggregateType, $aggregateId);
        $eventStream->appendAll($eventEnvelopes);
        $eventStream->commit($commitId);

        $this->aggregateManipulator->clearChanges($aggregate);
    }

    /**
     * @param Contract $aggregateType
     * @param string $aggregateId
     *
     * @return null|object
     */
    public function get(Contract $aggregateType, $aggregateId)
    {
        $trackedAggregate = $this->findTrackedAggregate($aggregateType, $aggregateId);

        if (! is_null($trackedAggregate)) {
            return $trackedAggregate;
        }

        $aggregate = $this->findAndTrackPersistedAggregate($aggregateType, $aggregateId);

        return $aggregate;
    }

    /**
     * @param Contract $aggregateType
     * @param string $aggregateId
     *
     * @return mixed
     */
    private function findTrackedAggregate(Contract $aggregateType, $aggregateId)
    {
        if (! array_key_exists((string) $aggregateType, $this->trackedAggregates)) {
            $this->trackedAggregates[(string) $aggregateType] = [];

            return null;
        }

        foreach ($this->trackedAggregates[(string) $aggregateType] as $trackedAggregate) {
            $trackedAggregateId = $this->aggregateManipulator->identify($trackedAggregate);
            if ($trackedAggregateId == $aggregateId) {
                return $trackedAggregate;
            }
        }

        return null;
    }

    /**
     * @param Contract $aggregateType
     * @param string $aggregateId
     *
     * @return object
     */
    private function findAndTrackPersistedAggregate(Contract $aggregateType, $aggregateId)
    {
        $aggregate = $this->findPersistedAggregate($aggregateType, $aggregateId);

        return $aggregate;
    }

    /**
     * @param Contract $aggregateType
     * @param string $aggregateId
     *
     * @return object
     */
    private function findPersistedAggregate(Contract $aggregateType, $aggregateId)
    {
        $eventStream = $this->eventStore->openAggregateInstanceStream(
            $this->streamId,
            $aggregateType,
            $aggregateId
        );

        $events = array_map(function (EventEnvelope $eventEnvelope) {
            return $this->aggregateChangeManipulator->writeChange(
                $eventEnvelope->getEvent(),
                $eventEnvelope->getMetadata()
            );
        }, $eventStream->all());

        $aggregate = $this->instantiateAggregate($aggregateType);
        $this->reconstituteAggregate($aggregate, $events);
        $this->track($aggregateType, $aggregateId, $aggregate);

        return $aggregate;
    }

    private function instantiateAggregate(Contract $aggregateType)
    {
        return $this->aggregateManipulator
            ->instantiateForReconstitution($aggregateType->getClassName())
        ;
    }

    private function reconstituteAggregate($aggregate, array $events)
    {
        $this->aggregateManipulator
            ->reconstitute($aggregate, $events)
        ;
    }
}
