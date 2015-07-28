<?php

namespace Monii\AggregateEventStorage\Aggregate;

use Monii\AggregateEventStorage\Aggregate\ChangesExtraction\PublicMethodChangeExtractor;
use Monii\AggregateEventStorage\Aggregate\ChangesClearing\PublicMethodChangeClearor;
use Monii\AggregateEventStorage\Aggregate\Identification\PublicMethodIdentifier;
use Monii\AggregateEventStorage\Aggregate\Instantiation\NamedConstructorInstantiator;
use Monii\AggregateEventStorage\Aggregate\Reconstitution\PublicMethodReconstituter;
use Monii\AggregateEventStorage\Fixtures\Banking\Account\Account;
use Monii\AggregateEventStorage\Fixtures\Banking\Account\AccountBalanceDecreased;
use Monii\AggregateEventStorage\Fixtures\Banking\Account\AccountBalanceIncreased;
use Monii\AggregateEventStorage\Fixtures\Banking\Account\AccountWasOpened;
use Monii\AggregateEventStorage\Fixtures\Banking\Common\BankingEventEnvelope;
use PHPUnit_Framework_TestCase as TestCase;

class AggregateManipulatorTest extends TestCase
{
    protected function getAccountFixture()
    {
        $account = Account::open(0, 'fixture-account-000', 25);
        $account->increaseBalance(1, 3);
        $account->decreaseBalance(2, 2);
        $account->increaseBalance(3, 5);

        return $account;
    }

    protected function getAccountFixtureBankingEventEnvelopes()
    {
        return [
            BankingEventEnvelope::create(0, new AccountWasOpened('fixture-account-000', 25)),
            BankingEventEnvelope::create(1, new AccountBalanceIncreased('fixture-account-000', 3)),
            BankingEventEnvelope::create(2, new AccountBalanceDecreased('fixture-account-000', 2)),
            BankingEventEnvelope::create(3, new AccountBalanceIncreased('fixture-account-000', 5)),
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

    protected function getAggregateManipulator()
    {
        return new AggregateManipulator(
            new NamedConstructorInstantiator(),
            new PublicMethodReconstituter(),
            new PublicMethodIdentifier(),
            new PublicMethodChangeExtractor(),
            new PublicMethodChangeClearor()
        );
    }

    public function testInstantiation()
    {
        $account = $this->getAggregateManipulator()->instantiateForReconstitution(Account::class);

        $this->assertInstanceOf(Account::class, $account);
    }

    public function testReconstitution()
    {
        $account = Account::instantiateAggregateForReconstitution();

        $this->getAggregateManipulator()->reconstitute($account, $this->getAccountFixtureBankingEventEnvelopes());

        $this->assertEquals($this->getAccountFixtureEvents(), $account->getHandledEvents());
    }

    public function testChangesExtraction()
    {
        $account = $this->getAccountFixture();

        $bankingEventEnvelopes = $this->getAggregateManipulator()->extractChanges($account);

        $this->assertEquals($this->getAccountFixtureBankingEventEnvelopes(), $bankingEventEnvelopes);
        $this->assertEquals($this->getAccountFixtureBankingEventEnvelopes(), $bankingEventEnvelopes);
    }

    public function testChangesClearing()
    {
        $account = $this->getAccountFixture();

        $this->getAggregateManipulator()->clearChanges($account);

        $bankingEventEnvelopes = $this->getAggregateManipulator()->extractChanges($account);

        $this->assertEquals([], $bankingEventEnvelopes);
    }

    public function testIdentification()
    {
        $account = $this->getAccountFixture();

        $accountId = $this->getAggregateManipulator()->identify($account);

        $this->assertEquals('fixture-account-000', $accountId);
    }
}