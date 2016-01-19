<?php

namespace Depot\EventStore\Persistence;

use RuntimeException;

class OptimisticConcurrencyFailed extends RuntimeException
{
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
