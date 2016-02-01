<?php

namespace Depot\EventStore\Transaction;

class CommitId
{
    /**
     * @var string
     */
    private $commitId;

    private function __construct($commitId)
    {
        $this->commitId = $commitId;
    }
    public static function fromString($string)
    {
        return new self($string);
    }
    public function __toString()
    {
        return (string) $this->commitId;
    }
}
