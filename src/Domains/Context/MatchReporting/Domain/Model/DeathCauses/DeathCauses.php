<?php

namespace Domains\Context\MatchReporting\Domain\Model\DeathCauses;

use Domains\CrossCutting\Domain\Model\Common\Validatable;

interface DeathCauses extends Validatable
{

    public function find(): void;

    public function computeCause(Matchable $match): void;

    public function isMeansFound(string $means): bool;

    public function getCauses(): array;
}
