<?php

use Monii\AggregateEventStorage\Aggregate\Reconstitution\PublicMethodReconstituter;
use PHPUnit_Framework_TestCase as TestCase;

class PublicMethodReconstituterTest extends TestCase
{
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
}