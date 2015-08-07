<?php

namespace Monii\AggregateEventStorage\Aggregate\ChangesClearing;

use Monii\AggregateEventStorage\Aggregate\Error\AggregateNotSupported;
use Monii\AggregateEventStorage\Aggregate\Support\ChangesClearing\AggregateChangesClearing;

class PublicMethodChangesClearor implements ChangesClearor
{
    /**
     * @var string
     */
    private $clearChangesMethod;

    /**
     * @var string
     */
    private $supportedObjectType;

    /**
     * @param string $extractChangesMethod popRecordedChanges
     */
    public function __construct(
        $extractChangesMethod = 'clearAggregateChanges',
        $supportedObjectType = AggregateChangesClearing::class
    ) {
        $this->clearChangesMethod = $extractChangesMethod;
        $this->supportedObjectType = $supportedObjectType;
    }

    /**
     * {@inheritdoc}
     */
    public function clearChanges($object)
    {
        $this->assertObjectIsSupported($object);

        return call_user_func([$object, $this->clearChangesMethod]);
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
