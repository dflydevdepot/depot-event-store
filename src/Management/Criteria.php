<?php

namespace Depot\EventStore\Management;

use Depot\EventStore\Management\Error\CriteriaNotSupported;
use Depot\EventStore\Persistence\CommittedEvent;

class Criteria
{
    private $aggregateRootTypes = array();
    private $aggregateRootIds = array();
    private $eventTypes = array();

    /**
     * @param array $aggregateRootTypes
     * @return static
     */
    public function withAggregateRootTypes(array $aggregateRootTypes)
    {
        $instance = clone($this);
        $instance->aggregateRootTypes = $aggregateRootTypes;

        return $instance;
    }

    /**
     * @param array $aggregateRootIds
     * @return Criteria
     */
    public function withAggregateRootIds(array $aggregateRootIds)
    {
        $instance = clone($this);
        $instance->aggregateRootIds = $aggregateRootIds;

        return $instance;
    }

    /**
     * @param array $eventTypes
     * @return Criteria
     */
    public function withEventTypes(array $eventTypes)
    {
        $instance = clone($this);
        $instance->eventTypes = $eventTypes;

        return $instance;
    }

    /**
     * @return string[]
     */
    public function getAggregateRootTypes()
    {
        return $this->aggregateRootTypes;
    }

    /**
     * @return array
     */
    public function getAggregateRootIds()
    {
        return $this->aggregateRootIds;
    }

    /**
     * @return array
     */
    public function getEventTypes()
    {
        return $this->eventTypes;
    }

    /**
     * @return static
     */
    public static function create()
    {
        return new static();
    }

    /**
     * @param CommittedEvent $committedEvent
     * @return bool
     */
    public function isMatchedBy(CommittedEvent $committedEvent)
    {
        if ($this->aggregateRootTypes) {
            throw new CriteriaNotSupported(
                'Cannot match criteria based on aggregate root types.'
            );
        }

        if ($this->aggregateRootIds && ! in_array($committedEvent->getAggregateRootId(), $this->aggregateRootIds)) {
            return false;
        }

        if ($this->eventTypes && ! in_array($committedEvent->getEventEnvelope()->getEventType(), $this->eventTypes)) {
            return false;
        }

        return true;
    }
}
