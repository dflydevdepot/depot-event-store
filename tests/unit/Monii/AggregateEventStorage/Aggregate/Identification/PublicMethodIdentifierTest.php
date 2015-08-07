<?php

namespace Monii\AggregateEventStorage\Aggregate\Identification;

use Monii\AggregateEventStorage\Aggregate\Identification\PublicMethodIdentifier;
use Monii\AggregateEventStorage\Aggregate\Support\Identification\AggregateIdentification;
use PHPUnit_Framework_TestCase as TestCase;

class PublicMethoddentifierTest extends TestCase
{
    public function testHappyIdentification()
    {
        $object = $this
            ->getMockBuilder(AggregateIdentification::class)
            ->setMethods(array('getAggregateIdentity'))
            ->getMock();
        $object->expects($this->once())->method('getAggregateIdentity');
        $identity = new PublicMethodIdentifier();
        $identity->identify($object);
    }

    /**
     * @expectedException \Monii\AggregateEventStorage\Aggregate\Error\AggregateNotSupported
     */
    public function testUnhappyIdentification()
    {
        $object = new \DateTimeImmutable();

        $identity = new PublicMethodIdentifier();
        $identity->identify($object);
    }
}
