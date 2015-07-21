<?php

namespace Monii\AggregateEventStorage\Aggregate\Instantiation;

class PublicConstructorInstantiator implements Instantiator
{
    /**
     * {@inheritdoc}
     */
    public function instantiateForReconstitution($className)
    {
        return new $className();
    }
}
