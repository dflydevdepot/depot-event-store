<?php

use Monii\AggregateEventStorage\EventStore\EventStream;
use Monii\AggregateEventStorage\EventStore\EventEnvelope;
use Monii\AggregateEventStorage\EventStore\Persistence\Persistence;
use Monii\AggregateEventStorage\Fixtures\Banking\Account\AccountWasOpened;
use Monii\AggregateEventStorage\Fixtures\Banking\Account\AccountBalanceIncreased;
use Monii\AggregateEventStorage\Contract\SimplePhpFqcnContractResolver;
use Monii\AggregateEventStorage\EventStore\Transaction\CommitId;
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

    private function createEventEnvelope($eventId, $event)
    {
        return new EventEnvelope(
            $this->contractResolver->resolveFromObject($event),
            $eventId,
            $event
        );
    }

    public function testAppendingEventEnvelopeToCreatedEventStream()
    {
        $this->setUpContractResolver();

        $contract = $this->contractResolver->resolveFromClassName(Account::class);

        $persistence = $this->getMockBuilder(Persistence::class)
            ->getMock();

        $persistence
            ->expects($this->never())
            ->method('fetch');

        $eventStream = EventStream::create($persistence, $contract, 123);

        $appendedEventEnvelope = $this->createEventEnvelope(
            123,
            new AccountWasOpened('fixture-account-000', 25)
        );

        $eventStream->append($appendedEventEnvelope);

        $this->assertEquals($eventStream->all(), [
            $appendedEventEnvelope
        ]);
    }

    public function testAppendingEventEnvelopeToOpenedEventStream()
    {
        $this->setUpContractResolver();

        $contract = $this->contractResolver->resolveFromClassName(Account::class);

        $existingEventEnvelope = $this->createEventEnvelope(
            123,
            new AccountWasOpened('fixture-account-000', 25)
        );

        $persistence = $this->getMockBuilder(Persistence::class)
            ->getMock();

        $persistence
            ->expects($this->once())
            ->method('fetch')
            ->with($this->equalTo($contract), $this->equalTo(123))
            ->will($this->returnValue([$existingEventEnvelope]));

        $eventStream = EventStream::open($persistence, $contract, 123);

        $appendedEventEnvelope = $this->createEventEnvelope(
            124,
            new AccountBalanceIncreased('fixture-account-000', 10)
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

        $contract = $this->contractResolver->resolveFromClassName(Account::class);

        $appendedEventEnvelopeOne = $this->createEventEnvelope(
            123,
            new AccountWasOpened('fixture-account-000', 25)
        );

        $appendedEventEnvelopeTwo = $this->createEventEnvelope(
            124,
            new AccountWasOpened('fixture-account-001', 35)
        );

        // Commit EventStream - First Time
        $commitIdOne = new CommitId();

        // Commit EventStream - First Time
        $commitIdTwo = new CommitId();

        $persistence = $this->getMockBuilder(Persistence::class)
            ->getMock();

        $eventStream = EventStream::create($persistence, $contract, 123);

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