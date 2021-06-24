<?php

namespace Domains\Context\MatchReporting\Domain\Model\PlayersKilled;

use Domains\CrossCutting\Domain\Model\Common\Validatable;

interface PlayersKilled extends Validatable
{

    public function find(): void;

    public function getTotalKills(): int;

    public function computeKills(Matchable $match): void;

    public function isEligibleToBeAPlayer(Matchable $match): bool;

    public function consolidate(): void;

    public function getPlayers(): array;
}
