<?php

namespace Domains\Context\MatchReporting\Domain\Model\PlayersKilled\State;

interface PlayerState
{

    public function computeKills(int $amount): void;

    public function getKills(): int;
}
