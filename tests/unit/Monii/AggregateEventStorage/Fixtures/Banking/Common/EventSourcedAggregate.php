<?php

namespace Monii\AggregateEventStorage\Fixtures\Banking\Common;

use Monii\AggregateEventStorage\Aggregate\Support\AggregateEventStorage;

abstract class EventSourcedAggregate implements AggregateEventStorage
{
    private $recordedEvents = [];

    private $handledEvents = [];

    protected function __construct()
    {
    }

    /**
     * @param object $event
     */
    protected function recordEvent($eventId, $event)
    {
        $this->recordedEvents[] = BankingEventEnvelope::create($eventId, $event);
        $this->handle($event);
    }

    /**
     * @return array
     */
    public function clearAggregateChanges()
    {
        $this->recordedEvents = [];
    }

    /**
     * @return array
     */
    public function getAggregateChanges()
    {
        return $this->recordedEvents;
    }

    /**
     * @return string
     */
    abstract public function getAggregateIdentity();

    /**
     * @param array|BankingEventEnvelope[] $events
     *
     * @return void
     */
    public function reconstituteAggregateFrom(array $events)
    {
        foreach ($events as $event) {
            if (! $event instanceof BankingEventEnvelope) {
                throw new \InvalidArgumentException('Cannot reconstitute from an unexpected event type.');
            }

            $this->handle($event->getAggregateEvent());
        }
    }

    protected function handle($event)
    {
        $method = $this->getHandleMethod($event);
        if (! method_exists($this, $method)) {
            return;
        }
        $this->$method($event, $event);
        $this->handledEvents[] = $event;
    }

    private function getHandleMethod($event)
    {
        $classParts = explode('\\', get_class($event));
        return 'apply' . end($classParts);
    }

    /**
     * @return static
     */
    public static function instantiateAggregateForReconstitution()
    {
        return new static();
    }

    public function getHandledEvents()
    {
        return $this->handledEvents;
    }
}
