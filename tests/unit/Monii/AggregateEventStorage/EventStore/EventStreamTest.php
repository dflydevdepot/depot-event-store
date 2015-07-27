<?php

use Monii\AggregateEventStorage\EventStore\EventStream;
use Monii\AggregateEventStorage\EventStore\EventEnvelope;
use Monii\AggregateEventStorage\Fixtures\Banking\Account\AccountWasOpened;
use Monii\AggregateEventStorage\Contract\SimplePhpFqcnContractResolver;
use PHPUnit_Framework_TestCase as TestCase;

class EventStreamTest extends TestCase
{
    public function testCreatingEventStream()
    {
        /*$persistence = ; // where to get $persistence from / How to create persistence?
        $aggregateType = ;
        $aggregateId = ;

        $eventStream = EventStream::create($persistence, $aggregateType, $aggregateId);

        // Check values in instance of EventStream match what they were passed in as
        $this->assertEquals($persistence, $eventStream->persistence);
        $this->assertEquals($aggregateType, $eventStream->aggregateType);
        $this->assertEquals($aggregateId, $eventStream->aggregateId);*/
    }

    public function testOpeningEventStream()
    {
        /*$persistence = ; // where to get $persistence from / How to create persistence?
        $aggregateType = ;
        $aggregateId = ;

        $eventStream = EventStream::open($persistence, $aggregateType, $aggregateId);

        // Check values in instance of EventStream match what they were passed in as
        $this->assertEquals($persistence, $eventStream->persistence);
        $this->assertEquals($aggregateType, $eventStream->aggregateType);
        $this->assertEquals($aggregateId, $eventStream->aggregateId);*/
    }

    public function testAppendingEventEnvelopeEventStream()
    {
        $contractResolver = new SimplePhpFqcnContractResolver();
        $contract = $contractResolver->resolveFromClassName(AccountWasOpened::class);

        $eventType = $contract;
        $eventId = 123;
        $event = new AccountWasOpened('fixture-account-000', 25);

        $eventEnvelope = new EventEnvelope($eventType, $eventId, $event);

        /*$persistence = ; // where to get $persistence from / How to create persistence?
        $aggregateType = ;
        $aggregateId = ;

        $eventStream = EventStream::open($persistence, $aggregateType, $aggregateId);

        $eventStream->append($eventEnvelope);

        $this->assertEquals($eventStream->pendingEventEnvelopes, array($eventEnvelope));*/
    }

    public function testAppendingAllEventEnvelopesEventStream()
    {
        $contractResolver = new SimplePhpFqcnContractResolver();
        $contract = $contractResolver->resolveFromClassName(AccountWasOpened::class);

        $eventType = $contract;
        $eventId = 123;
        $event = new AccountWasOpened('fixture-account-000', 25);

        $eventEnvelope = new EventEnvelope($eventType, $eventId, $event);

        /*$persistence = ; // where to get $persistence from / How to create persistence?
        $aggregateType = ;
        $aggregateId = ;

        $eventStream = EventStream::open($persistence, $aggregateType, $aggregateId);

        $eventStream->appendAll(array($eventEnvelope, $eventEnvelope));

        $this->assertEquals($eventStream->pendingEventEnvelopes, array($eventEnvelope, $eventEnvelope));*/
    }

    public function testCommittingEventStream()
    {
        $contractResolver = new SimplePhpFqcnContractResolver();
        $contract = $contractResolver->resolveFromClassName(AccountWasOpened::class);

        $eventType = $contract;
        $eventId = 123;
        $event = new AccountWasOpened('fixture-account-000', 25);

        $eventEnvelope = new EventEnvelope($eventType, $eventId, $event);

        /*$persistence = ; // where to get $persistence from / How to create persistence?
        $aggregateType = ;
        $aggregateId = ;

        $eventStream = EventStream::open($persistence, $aggregateType, $aggregateId);

        $eventStream->append($eventEnvelope);

        $eventStream->commit($commitId); // Where do I get a commitId from?

        $this->assertEquals($eventStream->committedEventEnvelopes, array($eventEnvelope));
        $this->assertEquals($eventStream->pendingEventEnvelopes, array());*/
    }

    public function testCommittingTwiceEventStream()
    {
        $contractResolver = new SimplePhpFqcnContractResolver();
        $contract = $contractResolver->resolveFromClassName(AccountWasOpened::class);

        $eventType = $contract;
        $eventId = 123;
        $event = new AccountWasOpened('fixture-account-000', 25);

        $eventEnvelope = new EventEnvelope($eventType, $eventId, $event);

        /*$persistence = ; // where to get $persistence from / How to create persistence?
        $aggregateType = ;
        $aggregateId = ;

        $eventStream = EventStream::open($persistence, $aggregateType, $aggregateId);

        $eventStream->append($eventEnvelope);

        $eventStream->commit($commitId); // Where do I get a commitId from?

        $eventStream->commit($commitId); // Where do I get a commitId from?

        $this->assertEquals($eventStream->committedEventEnvelopes, array($eventEnvelope, $eventEnvelope));
        $this->assertEquals($eventStream->pendingEventEnvelopes, array());*/
    }

    public function testAppendCommitAppendCommitEventStream()
    {
        $contractResolver = new SimplePhpFqcnContractResolver();
        $contract = $contractResolver->resolveFromClassName(AccountWasOpened::class);

        $eventType = $contract;
        $eventId = 123;
        $event = new AccountWasOpened('fixture-account-000', 25);

        $eventEnvelope = new EventEnvelope($eventType, $eventId, $event);

        /*$persistence = ; // where to get $persistence from / How to create persistence?
        $aggregateType = ;
        $aggregateId = ;

        $eventStream = EventStream::open($persistence, $aggregateType, $aggregateId);

        $eventStream->append($eventEnvelope);

        $eventStream->commit($commitId); // Where do I get a commitId from?

        $this->assertEquals($eventStream->committedEventEnvelopes, array($eventEnvelope));
        $this->assertEquals($eventStream->pendingEventEnvelopes, array());

        $eventStream->append($eventEnvelope);
        
        $eventStream->commit($commitId); // Where do I get a commitId from?

        $this->assertEquals($eventStream->committedEventEnvelopes, array($eventEnvelope, $eventEnvelope));
        $this->assertEquals($eventStream->pendingEventEnvelopes, array());*/
    }
}