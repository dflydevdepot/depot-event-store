<?php

namespace Monii\AggregateEventStorage\Aggregate\Support;

use Monii\AggregateEventStorage\Aggregate\Support\ChangesClearing\AggregateChangesClearing;
use Monii\AggregateEventStorage\Aggregate\Support\ChangesExtraction\AggregateChangesExtraction;
use Monii\AggregateEventStorage\Aggregate\Support\Identification\AggregateIdentification;
use Monii\AggregateEventStorage\Aggregate\Support\Instantiation\AggregateInstantiation;
use Monii\AggregateEventStorage\Aggregate\Support\Reconstitution\AggregateReconstitution;
use Monii\AggregateEventStorage\Aggregate\Support\VersionReading\AggregateVersionReading;

interface AggregateEventStorage extends
    AggregateInstantiation,
    AggregateChangesClearing,
    AggregateChangesExtraction,
    AggregateIdentification,
    AggregateReconstitution,
    AggregateVersionReading
{
}
