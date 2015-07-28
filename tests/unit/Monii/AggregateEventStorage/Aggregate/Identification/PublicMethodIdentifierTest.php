<?php

use Monii\AggregateEventStorage\Aggregate\Identification\PublicMethodIdentifier;
use PHPUnit_Framework_TestCase as TestCase;

class PublicMethodChangeIdentifierTest extends TestCase
{
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