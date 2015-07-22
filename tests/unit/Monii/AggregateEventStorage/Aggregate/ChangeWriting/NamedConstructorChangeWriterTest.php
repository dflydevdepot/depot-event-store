<?php

use Monii\AggregateEventStorage\Aggregate\ChangeWriting\NamedConstructorChangeWriter;
use PHPUnit_Framework_TestCase as TestCase;

class NamedConstructorChangeWriterTest extends TestCase
{
    public function testHappyChangeWriteCalledOnce()
    {
        // Create a mock of AggregateChangesClearing
        // only mock the clearAggregateChanges() method.
       /* $object = $this
            ->getMockBuilder('Monii\AggregateEventStorage\Aggregate\Support\ChangeWriting\AggregateChangeWriter')
            ->setMethods(array('instantiateAggregateChangeFromEventAndMetadata'))
            ->getMock();
        // We only expect the clearAggregateChanges method to be called once
        $object->expects($this->once())->method('instantiateAggregateChangeFromEventAndMetadata');

        $changeClearor = new NamedConstructorChangeWriter();
        $changeClearor->writeChange($object);*/

    }

    ///** @expectedException AggregateNotSupported */
    public function testUnhappyChangeWriteCalledOnce()
    {
        /*$this->setExpectedException('Monii\AggregateEventStorage\Aggregate\Error\AggregateNotSupported');
        $object = new \DateTimeImmutable();

        $changeClearor = new NamedConstructorChangeWriter();
        $changeClearor->writeChange($object);*/
    }
}