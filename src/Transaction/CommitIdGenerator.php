<?php

namespace Depot\EventStore\Transaction;

interface CommitIdGenerator
{
    /**
     * @return CommitId
     */
    public function generateCommitId();
}
