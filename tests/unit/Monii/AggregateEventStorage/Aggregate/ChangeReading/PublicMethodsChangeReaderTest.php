<?php

namespace Monii\AggregateEventStorage\Aggregate\ChangeReading;

use Monii\AggregateEventStorage\Aggregate\Support\ChangeReading\AggregateChangeReading;
use PHPUnit_Framework_TestCase as TestCase;

class PublicMethodsChangeReaderTest extends TestCase
{
    public function testHappyChangeReaderCalledOnce()
    {
        // Create a mock of AggregateChangesReader
        // only mock the getAggregateEvent() method.
        $object = $this
            ->getMockBuilder(AggregateChangeReading::class)
            //->setMethods(array('getAggregateEvent'))
            ->getMock();
        // We only expect the getAggregateEvent method to be called once
        $object->expects($this->once())->method('getAggregateEvent');

        $changeClearor = new PublicMethodsChangeReader();
        $changeClearor->readEvent($object);

    }

    /**
     * @expectedException \Monii\AggregateEventStorage\Aggregate\Error\AggregateNotSupported
     */
    public function testUnhappyChangeReaderCalledOnce()
    {
        $object = new \DateTimeImmutable();

        $changeClearor = new PublicMethodsChangeReader();
        $changeClearor->readEvent($object);
    }
}
