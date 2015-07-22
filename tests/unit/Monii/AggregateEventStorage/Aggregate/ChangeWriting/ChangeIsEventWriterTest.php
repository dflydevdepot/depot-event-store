<?php

use Monii\AggregateEventStorage\Fixtures\Banking\Account\AccountWasOpened;
use Monii\AggregateEventStorage\Aggregate\ChangeWriting\ChangeIsEventWriter;
use PHPUnit_Framework_TestCase as TestCase;

class ChangeIsEventWriterTest extends TestCase
{
    public function testChangeIsEventReader()
    {
        $original_event = new AccountWasOpened('fixture-account-000', 25);

        $passthrough = new ChangeIsEventWriter;

        $this->assertEquals($original_event, $passthrough->writeChange($original_event));
    }
}