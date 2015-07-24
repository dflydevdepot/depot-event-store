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
     * @var string
     */
    private $canReadEventIdMethodName;

    /**
     * @var string
     */
    private $readEventIdMethodName;

    /**
     * @param string $readEventMethodName
     * @param string $readMetadataMethodName
     * @param $supportedObjectType
     * @param string $canReadEventIdMethodName
     * @param string $readEventIdMethodName
     */
    public function __construct(
        $readEventMethodName = 'getAggregateEvent',
        $readMetadataMethodName = 'getAggregateMetadata',
        $supportedObjectType = AggregateChangeReader::class,
        $canReadEventIdMethodName = 'getCanReadAggregateEventId',
        $readEventIdMethodName = 'getAggregateEventId'
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

    /**
     * {@inheritdoc}
     */
    public function canReadEventId($change)
    {
        $this->assertObjectIsSupported($change);

        return call_user_func([$change, $this->canReadEventIdMethodName]);
    }

    /**
     * {@inheritdoc}
     */
    public function readEventId($change)
    {
        $this->assertObjectIsSupported($change);

        return call_user_func([$change, $this->readEventIdMethodName]);
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
