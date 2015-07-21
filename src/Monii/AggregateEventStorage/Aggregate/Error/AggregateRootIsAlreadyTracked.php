<?php

namespace Monii\AggregateEventStorage\Aggregate\Error;

use InvalidArgumentException;

class AggregateRootIsAlreadyTracked extends InvalidArgumentException
{
}
