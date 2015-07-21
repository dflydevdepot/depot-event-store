<?php

use Monii\AggregateEventStorage\Aggregate\ChangesExtraction\PublicMethodChangeExtractor;
use PHPUnit_Framework_TestCase as TestCase;

class PublicMethodChangeExtractorTest extends TestCase
{
    public function testHappyChangeExtractorCalledOnce()
    {
        // Create a mock of AggregateChangesClearing
        // only mock the clearAggregateChanges() method.
        $object = $this
            ->getMockBuilder('Monii\AggregateEventStorage\Aggregate\Support\ChangesExtraction\AggregateChangesRecording')
            ->setMethods(array('getAggregateChanges'))
            ->getMock();
        // We only expect the clearAggregateChanges method to be called once
        $object->expects($this->once())->method('getAggregateChanges');

        $changeExtractor = new PublicMethodChangeExtractor();
        $changeExtractor->extractChanges($object);

    }

    /** @expectedException AggregateNotSupported */
    public function testUnhappyChangeExtractorCalledOnce()
    {
        $this->setExpectedException('Monii\AggregateEventStorage\Aggregate\Error\AggregateNotSupported');

        $object = new \DateTimeImmutable();

        $changeExtractor = new PublicMethodChangeExtractor();
        $changeExtractor->extractChanges($object);
    }
}