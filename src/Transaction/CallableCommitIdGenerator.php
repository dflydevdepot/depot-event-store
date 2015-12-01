<?php

namespace Depot\EventStore\Transaction;

class CallableCommitIdGenerator implements CommitIdGenerator
{
    /**
     * @var callable
     */
    private $callable;

    /**
     * @param callable $callable
     */
    public function __construct(callable $callable)
    {
        $this->callable = $callable;
    }

    public function __invoke()
    {
        return call_user_func($this->callable);
    }

    /**
     * {@inheritdoc}
     */
    public function generateCommitId()
    {
        return $this();
    }
}
