<?php

namespace Domains\Context\MatchReporting\Domain\Model\PlayersKilled\State;

use Domains\Context\MatchReporting\Domain\Model\PlayersKilled\Matchable;

interface Player
{

    public function killUp(Matchable $player): void;

    public function killDown(Matchable $player): void;

    public function getPlayers(): array;
}
