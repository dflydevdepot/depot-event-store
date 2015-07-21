<?php

namespace Monii\AggregateEventStorage\Aggregate\Reconstitution;

use Monii\AggregateEventStorage\Aggregate\Error\AggregateNotSupported;
use Monii\AggregateEventStorage\Aggregate\Support\Reconstitution\AggregateReconstitution;

class PublicMethodReconstituter implements Reconstituter
{
    /**
     * @var string
     */
    private $reconstituteMethod;

    /**
     * @var string
     */
    private $supportedObjectType;

    /**
     * @param string $reconstituteMethod popRecordedChanges
     */
    public function __construct(
        $reconstituteMethod = 'reconstituteAggregateFrom',
        $supportedObjectType = AggregateReconstitution::class
    ) {
        $this->reconstituteMethod = $reconstituteMethod;
        $this->supportedObjectType = $supportedObjectType;
    }

    /**
     * {@inheritdoc}
     */
    public function reconstitute($object, array $events)
    {
        $this->assertObjectIsSupported($object);

        call_user_func([$object, $this->reconstituteMethod], $events);
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
