<?php

namespace Depot\EventStore\Persistence;

use RuntimeException;

class OptimisticConcurrencyFailed extends RuntimeException
{
    public function __construct($aggregateRootType, $aggregateRootId, $message = null, \Exception $previous = null)
    {
        $formattedMessage = sprintf('%s (%s:%s)', $message, $aggregateRootType, $aggregateRootId);
        parent::__construct($formattedMessage, 0, $previous);
    }

    public static function becauseExpectedAggregateRootVersionDoesNotMatch(
        $expectedAggregateRootVersion,
        $actualAggregateRootVersion
    ) {
        $message = sprintf(
            'Expected aggregate root version % s but got % s instead',
            $expectedAggregateRootVersion,
            $actualAggregateRootVersion
        );

         return new self($message);
    }
}
