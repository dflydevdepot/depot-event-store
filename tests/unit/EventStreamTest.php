<?php

use Depot\EventStore\EventStream;
use Depot\EventStore\EventEnvelope;
use Depot\EventStore\Persistence\Persistence;
use Depot\Testing\Fixtures\Banking\Account\AccountWasOpened;
use Depot\Testing\Fixtures\Banking\Account\AccountBalanceIncreased;
use Depot\Contract\SimplePhpFqcnContractResolver;
use Depot\EventStore\Transaction\CommitId;
use PHPUnit_Framework_TestCase as TestCase;

class EventStreamTest extends TestCase
{
    /**
     * @var ContractResolver
     */
    private $contractResolver;

    private function setUpContractResolver()
    {
        $this->contractResolver = new SimplePhpFqcnContractResolver();
    }

    private function createEventEnvelope($aggregateRootType, $aggregateRootId, $eventId, $event, $version)
    {
        return new EventEnvelope(
            $aggregateRootType,
            $aggregateRootId,
            $this->contractResolver->resolveFromObject($event),
            $eventId,
            $event,
            $version
        );
    }

    public function testAppendingEventEnvelopeToCreatedEventStream()
    {
        $this->setUpContractResolver();

        $aggregateRootType = $this->contractResolver->resolveFromClassName(Account::class);
        $aggregateRootId = 123;
        $eventId = 456;

        $persistence = $this->getMockBuilder(Persistence::class)
            ->getMock();

        $persistence
            ->expects($this->never())
            ->method('fetch');

        $eventStream = EventStream::create($persistence, $aggregateRootType, $aggregateRootId);

        $appendedEventEnvelope = $this->createEventEnvelope(
            $aggregateRootType,
            $aggregateRootId,
            $eventId,
            new AccountWasOpened('fixture-account-000', 25),
            0
        );

        $eventStream->append($appendedEventEnvelope);

        $this->assertEquals($eventStream->all(), [
            $appendedEventEnvelope
        ]);
    }

    public function testAppendingEventEnvelopeToOpenedEventStream()
    {
        $this->setUpContractResolver();

        $aggregateRootType = $this->contractResolver->resolveFromClassName(Account::class);
        $aggregateRootId = 123;
        $eventId = 456;
        $secondEventId = 789;

        $existingEventEnvelope = $this->createEventEnvelope(
            $aggregateRootType,
            $aggregateRootId,
            $eventId,
            new AccountWasOpened('fixture-account-000', 25),
            0
        );

        $persistence = $this->getMockBuilder(Persistence::class)
            ->getMock();

        $persistence
            ->expects($this->once())
            ->method('fetch')
            ->with($this->equalTo($aggregateRootType), $this->equalTo(123))
            ->will($this->returnValue([$existingEventEnvelope]));

        $eventStream = EventStream::open($persistence, $aggregateRootType, $aggregateRootId);

        $appendedEventEnvelope = $this->createEventEnvelope(
            $aggregateRootType,
            $aggregateRootId,
            $secondEventId,
            new AccountBalanceIncreased('fixture-account-000', 10),
            1
        );

        $eventStream->append($appendedEventEnvelope);

        $this->assertEquals($eventStream->all(), [
            $existingEventEnvelope,
            $appendedEventEnvelope
        ]);
    }

    public function testCommittingEventStream()
    {
        $this->setUpContractResolver();

        $aggregateRootType = $this->contractResolver->resolveFromClassName(Account::class);
        $aggregateRootId = 123;
        $eventId = 456;
        $secondEventId = 789;

        $appendedEventEnvelopeOne = $this->createEventEnvelope(
            $aggregateRootType,
            $aggregateRootId,
            $eventId,
            new AccountWasOpened('fixture-account-000', 25),
            0
        );

        $appendedEventEnvelopeTwo = $this->createEventEnvelope(
            $aggregateRootType,
            $aggregateRootId,
            $secondEventId,
            new AccountWasOpened('fixture-account-001', 35),
            1
        );

        // Commit EventStream - First Time
        $commitIdOne = CommitId::fromString('first-time');

        // Commit EventStream - First Time
        $commitIdTwo = CommitId::fromString('second-time');

        $persistence = $this->getMockBuilder(Persistence::class)
            ->getMock();

        $eventStream = EventStream::create($persistence, $aggregateRootType, $aggregateRootId);

        $persistence
            ->expects($this->exactly(2))
            ->method('commit')
            ->withConsecutive(
                array($commitIdOne),
                array($commitIdTwo)
            );

        // Append to EventStream
        $eventStream->append($appendedEventEnvelopeOne);

        $this->assertEquals($eventStream->all(), [
            $appendedEventEnvelopeOne
        ]);

        $eventStream->commit($commitIdOne);

        $this->assertEquals($eventStream->all(), [
            $appendedEventEnvelopeOne
        ]);

        $eventStream->append($appendedEventEnvelopeTwo);

        $this->assertEquals($eventStream->all(), [
            $appendedEventEnvelopeOne,
            $appendedEventEnvelopeTwo
        ]);

        $eventStream->commit($commitIdTwo);

        $this->assertEquals($eventStream->all(), [
            $appendedEventEnvelopeOne,
            $appendedEventEnvelopeTwo
        ]);
    }
}
