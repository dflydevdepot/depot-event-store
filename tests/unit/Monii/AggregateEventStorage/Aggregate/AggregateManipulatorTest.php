<?php

namespace Monii\AggregateEventStorage\Aggregate;

//use Monii\AggregateEventStorage\Aggregate\ChangesExtraction\PublicMethodChangeExtractor;
use Monii\AggregateEventStorage\Aggregate\ChangesClearing\PublicMethodChangeClearor;
/*use Monii\AggregateEventStorage\Aggregate\Identification\PublicMethodIdentifier;
use Monii\AggregateEventStorage\Aggregate\Instantiation\NamedConstructorInstantiator;
use Monii\AggregateEventStorage\Aggregate\Reconstitution\PublicMethodReconstituter;
use Monii\AggregateEventStorage\Fixtures\Banking\Account\Account;
use Monii\AggregateEventStorage\Fixtures\Banking\Account\AccountBalanceDecreased;
use Monii\AggregateEventStorage\Fixtures\Banking\Account\AccountBalanceIncreased;
use Monii\AggregateEventStorage\Fixtures\Banking\Account\AccountWasOpened;
use Monii\AggregateEventStorage\Fixtures\Banking\Common\BankingEventEnvelope;*/
use Monii\AggregateEventStorage\Aggregate\Support\ChangesClearing\AggregateChangesClearing;
use Monii\AggregateEventStorage\Aggregate\Error\AggregateNotSupported;
use Monii\AggregateEventStorage\Aggregate\Reconstitution\PublicMethodReconstituter;
use Monii\AggregateEventStorage\Aggregate\Identification\PublicMethodIdentifier;
use PHPUnit_Framework_TestCase as TestCase;

class AggregateManipulatorTest extends TestCase
{
    /*public function testMock() {
        var_dump($this->getMock('AggregateChangesClearing') instanceOf AggregateChangesClearing);
        var_dump($this->getMock('Monii\AggregateEventStorage\Aggregate\Support\ChangesClearing\AggregateChangesClearing') instanceOf AggregateChangesClearing);
        var_dump($this->getMock('\\Monii\\AggregateEventStorage\\Aggregate\\Support\\ChangesClearing\\AggregateChangesClearing') instanceOf AggregateChangesClearing);
    }*/

    public function testHappyGetClearAggregatesCalledOnce()
    {
        // Create a mock of AggregateChangesClearing
        // only mock the clearAggregateChanges() method.
        $object = $this
            ->getMockBuilder('Monii\AggregateEventStorage\Aggregate\Support\ChangesClearing\AggregateChangesClearing')
            ->setMethods(array('clearAggregateChanges'))
            ->getMock();
        // We only expect the clearAggregateChanges method to be called once
        $object->expects($this->once())->method('clearAggregateChanges');

        $changeClearor = new PublicMethodChangeClearor();
        $changeClearor->clearChanges($object);

    }


    /** @expectedException AggregateNotSupported */
    public function testUnhappyGetClearAggregatesCalledOnce()
    {
        $this->setExpectedException('Monii\AggregateEventStorage\Aggregate\Error\AggregateNotSupported');
        $object = new \DateTimeImmutable();

        $changeClearor = new PublicMethodChangeClearor();
        $changeClearor->clearChanges($object);

    }


    /*
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
            BankingEventEnvelope::create(new AccountWasOpened('fixture-account-000', 25)),
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
    }*/

    public function testHappyReconstitution()
    {
        $object = $this
            ->getMockBuilder('Monii\AggregateEventStorage\Aggregate\Support\Reconstitution\AggregateReconstitution')
            ->setMethods(array('reconstituteAggregateFrom'))
            ->getMock();
        $object->expects($this->once())->method('reconstituteAggregateFrom');
        $reconstituter = new PublicMethodReconstituter();
        $reconstituter->reconstitute($object, array());
    }

    /** @expectedException AggregateNotSupported */
    public function testUnhappyReconstitution()
    {
        $this->setExpectedException('Monii\AggregateEventStorage\Aggregate\Error\AggregateNotSupported');
        $object = new \DateTimeImmutable();

        $reconstituter = new PublicMethodReconstituter();
        $reconstituter->reconstitute($object, array());

    }

    public function testHappyIdentification()
    {
        $object = $this
            ->getMockBuilder('Monii\AggregateEventStorage\Aggregate\Support\Identification\AggregateIdentity')
            ->setMethods(array('getAggregateIdentity'))
            ->getMock();
        $object->expects($this->once())->method('getAggregateIdentity');
        $identity = new PublicMethodIdentifier();
        $identity->identify($object);
    }

    /** @expectedException AggregateNotSupported */
    public function testUnhappyIdentification()
    {
        $this->setExpectedException('Monii\AggregateEventStorage\Aggregate\Error\AggregateNotSupported');
        $object = new \DateTimeImmutable();

        $identity = new PublicMethodIdentifier();
        $identity->identify($object);

    }
}
