<?php

namespace Monii\AggregateEventStorage\Aggregate\Identification;

use Monii\AggregateEventStorage\Aggregate\Error\AggregateNotSupported;
use Monii\AggregateEventStorage\Aggregate\Support\Identification\AggregateIdentity;

class PublicMethodIdentifier implements Identifier
{
    /**
     * @var string
     */
    private $identifyMethod;

    /**
     * @var string
     */
    private $supportedObjectType;

    /**
     * @param string $extractChangesMethod getAggregateIdentity
     */
    public function __construct(
        $extractChangesMethod = 'getAggregateIdentity',
        $supportedObjectType = AggregateIdentity::class
    ) {
        $this->identifyMethod = $extractChangesMethod;
        $this->supportedObjectType = $supportedObjectType;
    }

    /**
     * {@inheritdoc}
     */
    public function identify($object)
    {
        $this->assertObjectIsSupported($object);

        return call_user_func([$object, $this->identifyMethod]);
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
