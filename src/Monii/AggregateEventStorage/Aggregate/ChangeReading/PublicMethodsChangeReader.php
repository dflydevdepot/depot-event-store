<?php

namespace Monii\AggregateEventStorage\Aggregate\ChangeReading;

use Monii\AggregateEventStorage\Aggregate\Error\AggregateNotSupported;
use Monii\AggregateEventStorage\Aggregate\Support\ChangeReading\AggregateChangeReader;

class PublicMethodsChangeReader implements ChangeReader
{
    /**
     * @var string
     */
    private $readEventMethodName;

    /**
     * @var string
     */
    private $readMetadataMethodName;

    /**
     * @var string
     */
    private $supportedObjectType;

    /**
     * @param string $extractChangesMethod popRecordedChanges
     */
    public function __construct(
        $readEventMethodName = 'getAggregateEvent',
        $readMetadataMethodName = 'getAggregateMetadata',
        $supportedObjectType = AggregateChangeReader::class
    ) {
        $this->readEventMethodName = $readEventMethodName;
        $this->readMetadataMethodName = $readMetadataMethodName;
        $this->supportedObjectType = $supportedObjectType;
    }

    /**
     * {@inheritdoc}
     */
    public function readEvent($change)
    {
        $this->assertObjectIsSupported($change);

        return call_user_func([$change, $this->readEventMethodName]);
    }

    /**
     * {@inheritdoc}
     */
    public function readMetadata($change)
    {
        $this->assertObjectIsSupported($change);

        return call_user_func([$change, $this->readMetadataMethodName]);
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
