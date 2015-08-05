<?php

namespace Monii\AggregateEventStorage\EventStore\Transaction;

interface CommitIdGenerator
{
    /**
     * @return CommitId
     */
    public function generateCommitId();
}
