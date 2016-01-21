<?php

namespace Depot\EventStore\Management;

use Depot\Contract\Contract;
use Depot\EventStore\Persistence\CommittedEvent;

class Criteria
{
    private $aggregateRootTypes = array();
    private $aggregateRootIds = array();
    private $eventTypeContractNames = array();

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
     * @param Contract[] $eventTypes
     * @return Criteria
     */
    public function withEventTypes(array $eventTypes)
    {
        $instance = clone($this);
        $instance->eventTypeContractNames = array_map(function (Contract $eventType) {
            return $eventType->getContractName();
        }, $eventTypes);
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
    public function getEventTypeContractNames()
    {
        return $this->eventTypeContractNames;
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
        if ($this->aggregateRootIds && ! in_array($committedEvent->getAggregateRootId(), $this->aggregateRootIds)) {
            return false;
        }

        if ($this->eventTypeContractNames && ! in_array(
            $committedEvent->getEventEnvelope()->getEventType()->getContractName(),
            $this->eventTypeContractNames
        )) {
            return false;
        }

        return true;
    }
}
