<?php

namespace Domains\Context\MatchReporting\Domain\Model\DeathCauses;

use Domains\CrossCutting\Domain\Model\Common\Validatable;

interface Matchable extends Validatable
{

    public function means(): string;

}
