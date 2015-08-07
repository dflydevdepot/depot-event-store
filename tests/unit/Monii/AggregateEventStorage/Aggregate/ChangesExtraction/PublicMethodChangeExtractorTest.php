<?php

namespace Monii\AggregateEventStorage\Aggregate\ChangesExtraction;

use Monii\AggregateEventStorage\Aggregate\ChangesExtraction\PublicMethodChangesExtractor;
use Monii\AggregateEventStorage\Aggregate\Support\ChangesExtraction\AggregateChangesExtraction;
use PHPUnit_Framework_TestCase as TestCase;

class PublicMethodChangeExtractorTest extends TestCase
{
    public function testHappyChangeExtractorCalledOnce()
    {
        // Create a mock of AggregateChangesClearing
        // only mock the clearAggregateChanges() method.
        $object = $this
            ->getMockBuilder(AggregateChangesExtraction::class)
            ->setMethods(array('getAggregateChanges'))
            ->getMock();
        // We only expect the clearAggregateChanges method to be called once
        $object->expects($this->once())->method('getAggregateChanges');

        $changeExtractor = new PublicMethodChangesExtractor();
        $changeExtractor->extractChanges($object);

    }

    /** @expectedException \Monii\AggregateEventStorage\Aggregate\Error\AggregateNotSupported */
    public function testUnhappyChangeExtractorCalledOnce()
    {
        $object = new \DateTimeImmutable();

        $changeExtractor = new PublicMethodChangesExtractor();
        $changeExtractor->extractChanges($object);
    }
}
