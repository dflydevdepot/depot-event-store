<?php

namespace Monii\AggregateEventStorage\Contract;

interface ContractResolver
{
    /**
     * @param $contractName
     *
     * @return Contract
     */
    public function resolveFromContractName($contractName);

    /**
     * @param $className
     *
     * @return Contract
     */
    public function resolveFromClassName($className);

    /**
     * @param $object
     *
     * @return Contract|null
     */
    public function resolveFromObject($object = null);
}
