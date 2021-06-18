<?php

namespace Domains\Context\MatchReporting\Application\UseCases\DeathCauses;

final class FindDeathCausesInput
{

    public array $rows = [];

    public function __construct(array $rows)
    {
        $this->rows = $rows;
    }
}
