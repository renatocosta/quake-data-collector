<?php

namespace Domains\Context\MatchReporting\Domain\Model\PlayersKilled\State;

interface Player
{

    public function killUp(array $player): void;

    public function killDown(array $player): void;

    public function getPlayers(): array;
}
