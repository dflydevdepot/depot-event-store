<?php

namespace Monii\AggregateEventStorage\Aggregate\Error;

use InvalidArgumentException;

class SerializationNotPossible extends InvalidArgumentException
{
    public static function becauseClassDoesNotHaveAnExpectedStaticMethod(
        $className,
        $expectedStaticMethodName
    ) {
        return new self(sprintf(
            'Aggregate class "%s" does not have a static method named "%s".',
            $className,
            $expectedStaticMethodName
        ));
    }
}
