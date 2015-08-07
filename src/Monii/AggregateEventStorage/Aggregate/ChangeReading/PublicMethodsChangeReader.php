<?php

namespace Monii\AggregateEventStorage\Aggregate\ChangeReading;

use Monii\AggregateEventStorage\Aggregate\Error\AggregateNotSupported;
use Monii\AggregateEventStorage\Aggregate\Support\ChangeReading\AggregateChangeReading;

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
    private $canReadEventIdMethodName;

    /**
     * @var string
     */
    private $readEventIdMethodName;

    /**
     * @var string
     */
    private $canReadEventVersionMethodName;

    /**
     * @var string
     */
    private $readEventVersionMethodName;

    /**
     * @var string
     */
    private $supportedObjectType;

    /**
     * @param string $readEventMethodName
     * @param string $readMetadataMethodName
     * @param string $canReadEventIdMethodName
     * @param string $readEventIdMethodName
     * @param string $canReadEventVersionMethodName
     * @param string $readEventVersionMethodName
     * @param $supportedObjectType
     */
    public function __construct(
        $readEventMethodName = 'getAggregateEvent',
        $readMetadataMethodName = 'getAggregateMetadata',
        $canReadEventIdMethodName = 'getCanReadAggregateEventId',
        $readEventIdMethodName = 'getAggregateEventId',
        $canReadEventVersionMethodName = 'getAggregateEventVersion',
        $readEventVersionMethodName = 'getAggregateEventVersion',
        $supportedObjectType = AggregateChangeReading::class
    ) {
        $this->readEventMethodName = $readEventMethodName;
        $this->readMetadataMethodName = $readMetadataMethodName;
        $this->canReadEventIdMethodName = $canReadEventIdMethodName;
        $this->readEventIdMethodName = $readEventIdMethodName;
        $this->canReadEventVersionMethodName = $canReadEventVersionMethodName;
        $this->readEventVersionMethodName = $readEventVersionMethodName;
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

    /**
     * {@inheritdoc}
     */
    public function canReadEventVersion($change)
    {
        $this->assertObjectIsSupported($change);

        return call_user_func([$change, $this->canReadEventVersionMethodName]);
    }

    /**
     * {@inheritdoc}
     */
    public function readEventVersion($change)
    {
        $this->assertObjectIsSupported($change);

        return call_user_func([$change, $this->readEventVersionMethodName]);
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
