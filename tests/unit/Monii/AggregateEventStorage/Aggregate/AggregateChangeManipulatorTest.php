<?php

namespace Monii\AggregateEventStorage\Aggregate;

use Monii\AggregateEventStorage\Aggregate\ChangeReading\PublicMethodsChangeReader;
use Monii\AggregateEventStorage\Aggregate\ChangeWriting\ChangeIsEventWriter;
use Monii\AggregateEventStorage\Aggregate\ChangeWriting\NamedConstructorChangeWriter;
use Monii\AggregateEventStorage\Fixtures\Banking\Account\Account;
use Monii\AggregateEventStorage\Fixtures\Banking\Account\AccountBalanceDecreased;
use Monii\AggregateEventStorage\Fixtures\Banking\Account\AccountBalanceIncreased;
use Monii\AggregateEventStorage\Fixtures\Banking\Account\AccountWasOpened;
use Monii\AggregateEventStorage\Fixtures\Banking\Common\BankingEventEnvelope;
use PHPUnit_Framework_TestCase as TestCase;

class AggregateChangeManipulatorTest extends TestCase
{
    protected function getAccountFixture()
    {
        $account = Account::open('fixture-account-000', 25);
        $account->increaseBalance(3);
        $account->decreaseBalance(2);
        $account->increaseBalance(5);

        return $account;
    }

    protected function getAccountFixtureBankingEventEnvelopes()
    {
        return [
            BankingEventEnvelope::create(new AccountWasOpened('fixture-account-000', 25),'metaData'),
            BankingEventEnvelope::create(new AccountBalanceIncreased('fixture-account-000', 3)),
            BankingEventEnvelope::create(new AccountBalanceDecreased('fixture-account-000', 2)),
            BankingEventEnvelope::create(new AccountBalanceIncreased('fixture-account-000', 5)),
        ];
    }

    protected function getAccountFixtureEvents()
    {
        return [
            new AccountWasOpened('fixture-account-000', 25),
            new AccountBalanceIncreased('fixture-account-000', 3),
            new AccountBalanceDecreased('fixture-account-000', 2),
            new AccountBalanceIncreased('fixture-account-000', 5),
        ];
    }

    protected function getAggregateChangeManipulator()
    {
        return new AggregateChangeManipulator(
            new PublicMethodsChangeReader(),
            new NamedConstructorChangeWriter(BankingEventEnvelope::class)
        );
    }

    public function testReadEvent()
    {
        $eventEnvelope =  $this->getAccountFixtureBankingEventEnvelopes()[0];
        $actualEvent = $this->getAccountFixtureEvents()[0];

        $event = $this->getAggregateChangeManipulator()->readEvent($eventEnvelope);

        $this->assertEquals($actualEvent, $event);
    }

    public function testReadMetadata()
    {
        $eventEnvelope =  $this->getAccountFixtureBankingEventEnvelopes()[0];
        $metaData = 'metaData';

        $eventsMetaData = $this->getAggregateChangeManipulator()->readMetadata($eventEnvelope);

        $this->assertEquals($metaData, $eventsMetaData);
    }

    public function testWriteChange()
    {
        $event = new AccountWasOpened('fixture-account-000', 50);
        $eventEnvelope = BankingEventEnvelope::create($event);
        $change = $this->getAggregateChangeManipulator()->writeChange($event);

        $this->assertEquals($eventEnvelope, $change);
    }
}