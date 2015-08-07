<?php

namespace Monii\AggregateEventStorage\Aggregate\VersionReading;

use Monii\AggregateEventStorage\Aggregate\Error\AggregateNotSupported;
use Monii\AggregateEventStorage\Aggregate\Support\VersionReading\AggregateVersionReading;

class PublicMethodVersionReader implements VersionReader
{
    /**
     * @var string
     */
    private $readVersionMethod;

    /**
     * @var string
     */
    private $supportedObjectType;

    /**
     * @param string $readVersionMethod popRecordedChanges
     */
    public function __construct(
        $readVersionMethod = 'getAggregateVersion',
        $supportedObjectType = AggregateVersionReading::class
    ) {
        $this->readVersionMethod = $readVersionMethod;
        $this->supportedObjectType = $supportedObjectType;
    }

    /**
     * {@inheritdoc}
     */
    public function readVersion($object)
    {
        $this->assertObjectIsSupported($object);

        return call_user_func([$object, $this->readVersionMethod]);
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
