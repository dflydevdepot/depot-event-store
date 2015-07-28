<?php

use Monii\AggregateEventStorage\Aggregate\ChangesClearing\PublicMethodChangeClearor;
use PHPUnit_Framework_TestCase as TestCase;

class PublicMethodChangeClearorTest extends TestCase
{
    public function testHappyChangeClearorCalledOnce()
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
    public function testUnhappyChangeClearorCalledOnce()
    {
        $this->setExpectedException('Monii\AggregateEventStorage\Aggregate\Error\AggregateNotSupported');
        $object = new \DateTimeImmutable();

        $changeClearor = new PublicMethodChangeClearor();
        $changeClearor->clearChanges($object);
    }
}