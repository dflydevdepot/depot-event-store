<?php

namespace Monii\AggregateEventStorage\Aggregate\Support;

use Monii\AggregateEventStorage\Aggregate\Support\ChangesClearing\AggregateChangesClearing;
use Monii\AggregateEventStorage\Aggregate\Support\ChangesExtraction\AggregateChangesRecording;
use Monii\AggregateEventStorage\Aggregate\Support\Identification\AggregateIdentity;
use Monii\AggregateEventStorage\Aggregate\Support\Instantiation\AggregateInstantiation;
use Monii\AggregateEventStorage\Aggregate\Support\Reconstitution\AggregateReconstitution;

interface AggregateEventStorage extends
    AggregateInstantiation,
    AggregateChangesClearing,
    AggregateChangesRecording,
    AggregateIdentity,
    AggregateReconstitution
{
}
