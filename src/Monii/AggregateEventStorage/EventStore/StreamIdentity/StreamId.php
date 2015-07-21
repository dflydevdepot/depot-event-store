<?php

namespace Monii\AggregateEventStorage\EventStore\StreamIdentity;

class StreamId
{
    /**
     * @var string
     */
    private $streamId;

    /**
     * @param string $streamId
     */
    private function __construct($streamId)
    {
        $this->streamId = $streamId;
    }

    public static function fromString($string)
    {
        return new self($string);
    }

    public function __toString()
    {
        return $this->streamId;
    }
}
