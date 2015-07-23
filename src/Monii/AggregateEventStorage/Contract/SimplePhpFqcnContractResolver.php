<?php

namespace Monii\AggregateEventStorage\Contract;

class SimplePhpFqcnContractResolver implements ContractResolver
{
    /**
     * {@inheritdoc}
     */
    public function resolveFromContractName($contractName)
    {
        return new Contract($contractName, str_replace('.', '\\', $contractName));
    }

    /**
     * {@inheritdoc}
     */
    public function resolveFromClassName($className)
    {
        $className = trim($className, '\\');

        return new Contract(str_replace('\\', '.', $className), $className);
    }

    /**
     * {@inheritdoc}
     */
    public function resolveFromObject($object = null)
    {
        if (is_null($object)) {
            return null;
        }
        return $this->resolveFromClassName(get_class($object));
    }
}
