<?php

namespace Monii\AggregateEventStorage\Aggregate\ChangesExtraction;

interface ChangesExtractor
{
    /**
     * @param mixed $object
     *
     * @return array
     */
    public function extractChanges($object);
}
