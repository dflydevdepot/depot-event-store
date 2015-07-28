<?php

use Monii\AggregateEventStorage\EventStore\EventStore;
use Monii\AggregateEventStorage\EventStore\EventEnvelope;
use Monii\AggregateEventStorage\EventStore\Persistence\Persistence;
use Monii\AggregateEventStorage\Fixtures\Banking\Account\AccountWasOpened;
use Monii\AggregateEventStorage\Fixtures\Banking\Account\AccountBalanceIncreased;
use Monii\AggregateEventStorage\Contract\SimplePhpFqcnContractResolver;
use PHPUnit_Framework_TestCase as TestCase;

class EventStoreTest extends TestCase
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

    public function testAppendingEventEnvelopeToCreatedEventStore()
    {
        $this->setUpContractResolver();

        $contract = $this->contractResolver->resolveFromClassName(Account::class);

        $persistence = $this->getMockBuilder(Persistence::class)
            ->getMock();

        $persistence
            ->expects($this->never())
            ->method('fetch');

        $eventStore = new EventStore($persistence);

        $eventStream = $eventStore->create($contract, 123);

        $appendedEventEnvelope = $this->createEventEnvelope(
            123,
            new AccountWasOpened('fixture-account-000', 25)
        );

        $eventStream->append($appendedEventEnvelope);

        $this->assertEquals($eventStream->all(), [
            $appendedEventEnvelope
        ]);
    }

    public function testAppendingEventEnvelopeToOpenedEventStore()
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

        $eventStore = new EventStore($persistence);

        $eventStream = $eventStore->open($contract, 123);

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
}