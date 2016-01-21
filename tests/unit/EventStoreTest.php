<?php

use Depot\EventStore\EventStore;
use Depot\EventStore\EventEnvelope;
use Depot\EventStore\Persistence\Persistence;
use Depot\Testing\Fixtures\Banking\Account\AccountWasOpened;
use Depot\Testing\Fixtures\Banking\Account\AccountBalanceIncreased;
use Depot\Contract\SimplePhpFqcnContractResolver;
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

    private function createEventEnvelope($eventId, $event, $version)
    {
        return new EventEnvelope(
            $this->contractResolver->resolveFromObject($event),
            $eventId,
            $event,
            $version
        );
    }

    public function testAppendingEventEnvelopeToCreatedEventStore()
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

        $eventStore = new EventStore($persistence);

        $eventStream = $eventStore->create($aggregateRootType, $aggregateRootId);

        $appendedEventEnvelope = $this->createEventEnvelope(
            $eventId,
            new AccountWasOpened('fixture-account-000', 25),
            0
        );

        $eventStream->append($appendedEventEnvelope);

        $this->assertEquals($eventStream->all(), [
            $appendedEventEnvelope
        ]);
    }

    public function testAppendingEventEnvelopeToOpenedEventStore()
    {
        $this->setUpContractResolver();

        $aggregateRootType = $this->contractResolver->resolveFromClassName(Account::class);
        $aggregateRootId = 123;
        $eventId = 456;
        $secondEventId = 789;

        $existingEventEnvelope = $this->createEventEnvelope(
            $eventId,
            new AccountWasOpened('fixture-account-000', 25),
            0
        );

        $persistence = $this->getMockBuilder(Persistence::class)
            ->getMock();

        $persistence
            ->expects($this->once())
            ->method('fetch')
            ->with($this->equalTo($aggregateRootType), $this->equalTo($aggregateRootId))
            ->will($this->returnValue([$existingEventEnvelope]));

        $eventStore = new EventStore($persistence);

        $eventStream = $eventStore->open($aggregateRootType, $aggregateRootId);

        $appendedEventEnvelope = $this->createEventEnvelope(
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
}
