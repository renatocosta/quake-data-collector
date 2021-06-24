<?php

namespace Domains\Context\MatchReporting\Domain\Model\PlayersKilled\State;

class DeadPlayer implements PlayerState
{

    private int $amount = 0;

    public function computeKills(int $amount): void
    {
        $this->amount = $amount - 1;
    }

    public function getKills(): int
    {
        return $this->amount;
    }
}
