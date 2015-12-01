<?php

namespace Depot\EventStore\Persistence;

use RuntimeException;

class OptimisticConcurrencyFailed extends RuntimeException
{
    public static function becauseExpectedAggregateVersionDoesNotMatch(
        $expectedAggregateVersion,
        $actualAggregateVersion
    ) {
        $message = sprintf(
            'Expected aggregate version % s but got % s instead',
            $expectedAggregateVersion,
            $actualAggregateVersion
        );

         return new self($message);
    }
}
