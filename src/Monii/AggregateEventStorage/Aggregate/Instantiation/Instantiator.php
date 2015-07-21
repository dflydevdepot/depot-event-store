<?php

namespace Monii\AggregateEventStorage\Aggregate\Instantiation;

interface Instantiator
{
    /**
     * @param string $className
     *
     * @return mixed
     */
    public function instantiateForReconstitution($className);
}
