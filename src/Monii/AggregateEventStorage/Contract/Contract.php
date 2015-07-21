<?php

namespace Monii\AggregateEventStorage\Contract;

class Contract
{
    /**
     * @var string
     */
    private $contractName;

    /**
     * @var string
     */
    private $className;

    /**
     * @param string $contractName
     * @param string $className
     */
    public function __construct($contractName, $className)
    {
        $this->contractName = $contractName;
        $this->className = $className;
    }

    /**
     * @return string
     */
    public function getContractName()
    {
        return $this->contractName;
    }

    public function getClassName()
    {
        return $this->className;
    }

    public function __toString()
    {
        return '['.$this->contractName.']['.$this->className.']';
    }
}
