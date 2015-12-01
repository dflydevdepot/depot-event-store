<?php

namespace Depot\EventStore\Transaction;

use PHPUnit_Framework_TestCase as TestCase;

class CallableCommitIdGeneratorTest extends TestCase
{
    public function test()
    {
        $commitIdGenerator = new CallableCommitIdGenerator(function () {
            return CommitId::fromString('TEST123');
        });

        $commitId = $commitIdGenerator->generateCommitId();

        $this->assertEquals('TEST123', (string) $commitId);
    }
}
