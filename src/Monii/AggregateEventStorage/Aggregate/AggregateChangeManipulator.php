<?php

namespace Monii\AggregateEventStorage\Aggregate;

use Monii\AggregateEventStorage\Aggregate\ChangeReading\ChangeReader;
use Monii\AggregateEventStorage\Aggregate\ChangeWriting\ChangeWriter;

class AggregateChangeManipulator implements ChangeReader, ChangeWriter
{
    /**
     * @var ChangeReader
     */
    private $changeReader;

    /**
     * @var ChangeWriter
     */
    private $changeWriter;

    public function __construct(
        ChangeReader $changeReader,
        ChangeWriter $changeWriter
    ) {
        $this->changeReader = $changeReader;
        $this->changeWriter = $changeWriter;
    }

    /**
     * {@inheritdoc}
     */
    public function readEvent($change)
    {
        return $this->changeReader->readEvent($change);
    }

    /**
     * {@inheritdoc}
     */
    public function canReadEventId($change)
    {
        return $this->changeReader->canReadEventId($change);
    }

    /**
     * {@inheritdoc}
     */
    public function readEventId($change)
    {
        return $this->changeReader->readEventId($change);
    }

    /**
     * {@inheritdoc}
     */
    public function canReadEventVersion($change)
    {
        return $this->changeReader->canReadEventVersion($change);
    }

    /**
     * {@inheritdoc}
     */
    public function readEventVersion($change)
    {
        return $this->changeReader->readEventVersion($change);
    }

    /**
     * {@inheritdoc}
     */
    public function readMetadata($change)
    {
        return $this->changeReader->readMetadata($change);
    }

    /**
     * {@inheritdoc}
     */
    public function readWhen($change)
    {
        return $this->changeReader->readWhen($change);
    }

    /**
     * {@inheritdoc}
     */
    public function writeChange($eventId, $event, $metadata = null, $version = null)
    {
        return $this->changeWriter->writeChange($eventId, $event, $metadata, $version);
    }
}
