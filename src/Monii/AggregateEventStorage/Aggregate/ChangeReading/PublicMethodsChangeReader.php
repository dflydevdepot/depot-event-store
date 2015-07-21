<?php

namespace Monii\AggregateEventStorage\Aggregate\ChangeReading;

use Monii\AggregateEventStorage\Aggregate\Support\ChangeReading\AggregateChangeReader;

class PublicMethodsChangeReader implements ChangeReader
{
    /**
     * @var string
     */
    private $readEventIdMethodName;

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
        $readEventIdMethodName = 'getAggregateEventId',
        $readEventMethodName = 'getAggregateEvent',
        $readMetadataMethodName = 'getAggregateMetadata',
        $supportedObjectType = AggregateChangeReader::class
    ) {
        $this->readEventIdMethodName = $readEventIdMethodName;
        $this->readEventMethodName = $readEventMethodName;
        $this->readMetadataMethodName = $readMetadataMethodName;
        $this->supportedObjectType = $supportedObjectType;
    }

    /**
     * {@inheritdoc}
     */
    public function readEventId($change)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function readEvent($change)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function readMetadata($change)
    {
    }
}
