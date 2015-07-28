<?php

namespace Monii\AggregateEventStorage\Fixtures\Blogging;

class PostId
{
    /**
     * @var int|string
     */
    private $value;

    private function __construct($value)
    {
        $this->value = $value;
    }

    public static function fromString($string)
    {
        return new self($string);
    }

    public function __toString()
    {
        return (string)$this->value;
    }
}
