<?php

namespace Monii\AggregateEventStorage\Aggregate\ChangesExtraction;

use Monii\AggregateEventStorage\Aggregate\Error\AggregateNotSupported;
use Monii\AggregateEventStorage\Aggregate\Support\ChangesExtraction\AggregateChangesRecording;

class PublicMethodChangeExtractor implements ChangesExtractor
{
    /**
     * @var string
     */
    private $extractChangesMethod;

    /**
     * @var string
     */
    private $supportedObjectType;

    /**
     * @param string $extractChangesMethod popRecordedChanges
     */
    public function __construct(
        $extractChangesMethod = 'getAggregateChanges',
        $supportedObjectType = AggregateChangesRecording::class
    ) {
        $this->extractChangesMethod = $extractChangesMethod;
        $this->supportedObjectType = $supportedObjectType;
    }

    /**
     * {@inheritdoc}
     */
    public function extractChanges($object)
    {
        $this->assertObjectIsSupported($object);

        return call_user_func([$object, $this->extractChangesMethod]);
    }

    private function assertObjectIsSupported($object)
    {
        if ($object instanceof $this->supportedObjectType) {
            return;
        }

        throw AggregateNotSupported::becauseObjectHasAnUnexpectedType(
            $object,
            $this->supportedObjectType
        );
    }
}
