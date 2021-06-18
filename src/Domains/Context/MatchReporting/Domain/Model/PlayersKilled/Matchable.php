<?php

namespace Domains\Context\MatchReporting\Domain\Model\PlayersKilled;

use Domains\CrossCutting\Domain\Model\Common\Validatable;

interface Matchable extends Validatable
{

    public function getPlayerWhoKilled(): string;

    public function getPlayerWhoDied(): string;

}
