<?php

namespace Monii\AggregateEventStorage\Aggregate;

use Monii\AggregateEventStorage\Aggregate\ChangesClearing\ChangesClearor;
use Monii\AggregateEventStorage\Aggregate\ChangesExtraction\ChangesExtractor;
use Monii\AggregateEventStorage\Aggregate\Identification\Identifier;
use Monii\AggregateEventStorage\Aggregate\Instantiation\Instantiator;
use Monii\AggregateEventStorage\Aggregate\Reconstitution\Reconstituter;

class AggregateManipulator implements Instantiator, Reconstituter, Identifier, ChangesExtractor, ChangesClearor
{
    /**
     * @var Instantiator
     */
    private $instantiator;

    /**
     * @var Reconstituter
     */
    private $reconstituter;

    /**
     * @var Identifier
     */
    private $identifier;

    /**
     * @var ChangesExtractor
     */
    private $changesExtractor;

    /**
     * @var ChangesClearor
     */
    private $changesClearor;

    public function __construct(
        Instantiator $instantiator,
        Reconstituter $reconstituter,
        Identifier $identifier,
        ChangesExtractor $changesExtractor,
        ChangesClearor $changesClearor
    ) {
        $this->instantiator = $instantiator;
        $this->reconstituter = $reconstituter;
        $this->identifier = $identifier;
        $this->changesExtractor = $changesExtractor;
        $this->changesClearor = $changesClearor;
    }

    /**
     * {@inheritdoc}
     */
    public function clearChanges($object)
    {
        $this->changesClearor->clearChanges($object);
    }

    /**
     * {@inheritdoc}
     */
    public function extractChanges($object)
    {
        return $this->changesExtractor->extractChanges($object);
    }

    /**
     * {@inheritdoc}
     */
    public function identify($object)
    {
        return $this->identifier->identify($object);
    }

    /**
     * {@inheritdoc}
     */
    public function instantiateForReconstitution($className)
    {
        return $this->instantiator->instantiateForReconstitution($className);
    }

    /**
     * {@inheritdoc}
     */
    public function reconstitute($object, array $events)
    {
        return $this->reconstituter->reconstitute($object, $events);
    }
}
