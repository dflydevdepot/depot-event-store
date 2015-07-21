<?php

namespace Monii\AggregateEventStorage\Aggregate\ChangesClearing;

interface ChangesClearor
{
    /**
     * @param mixed $object
     *
     * @return void
     */
    public function clearChanges($object);
}
