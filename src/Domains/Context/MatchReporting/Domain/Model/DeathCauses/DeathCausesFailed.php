<?php

namespace Domains\Context\MatchReporting\Domain\Model\DeathCauses;

use Domains\CrossCutting\Domain\Application\Event\AbstractEvent;

class DeathCausesFailed extends AbstractEvent
{

    public DeathCauses $deathCauses;

    public function __construct(DeathCauses $deathCauses)
    {
        parent::__construct();
        $this->deathCauses = $deathCauses;
    }
}
