<?php

namespace Domains\Context\MatchReporting\Application\UseCases\PlayersKilled;

final class FindPlayersKilledInput
{

    public array $rows = [];

    public function __construct(array $rows)
    {
        $this->rows = $rows;
    }
}
