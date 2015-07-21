<?php

namespace Monii\AggregateEventStorage\EventStore\Transaction;

interface CommitIdFactory
{
    /**
     * @return CommitId
     */
    public function createCommitId();
}
