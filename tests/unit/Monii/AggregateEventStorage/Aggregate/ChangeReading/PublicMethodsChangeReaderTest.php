<?php

use Monii\AggregateEventStorage\Aggregate\ChangeReading\PublicMethodsChangeReader;
use PHPUnit_Framework_TestCase as TestCase;

class PublicMethodsChangeReaderTest extends TestCase
{
    public function testHappyChangeReaderCalledOnce()
    {
        // Create a mock of AggregateChangesReader
        // only mock the getAggregateEvent() method.
        $object = $this
            ->getMockBuilder('Monii\AggregateEventStorage\Aggregate\Support\ChangeReading\AggregateChangeReader')
            //->setMethods(array('getAggregateEvent'))
            ->getMock();
        // We only expect the getAggregateEvent method to be called once
        $object->expects($this->once())->method('getAggregateEvent');

        $changeClearor = new PublicMethodsChangeReader();
        $changeClearor->readEvent($object);

    }

    /** @expectedException AggregateNotSupported */
    public function testUnhappyChangeReaderCalledOnce()
    {
        $this->setExpectedException('Monii\AggregateEventStorage\Aggregate\Error\AggregateNotSupported');
        $object = new \DateTimeImmutable();

        $changeClearor = new PublicMethodsChangeReader();
        $changeClearor->readEvent($object);
    }
}