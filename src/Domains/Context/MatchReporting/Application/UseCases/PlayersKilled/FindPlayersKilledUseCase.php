<?php

namespace Domains\Context\MatchReporting\Application\UseCases\PlayersKilled;

use Domains\Context\MatchReporting\Domain\Model\PlayersKilled\Matcher;
use Domains\Context\MatchReporting\Domain\Model\PlayersKilled\PlayersKilled;
use Exception;

final class FindPlayersKilledUseCase implements IFindPlayersKilledUseCase
{
    private PlayersKilled $playersKilled;

    public function __construct(PlayersKilled $playersKilled)
    {
        $this->playersKilled = $playersKilled;
    }

    public function execute(FindPlayersKilledInput $input): void
    {
        foreach ($input->rows as $row) {
            $this->playersKilled->computeKills(new Matcher($row['who_killed'], $row['who_died']));
        }
        $this->playersKilled->find();
    }
}
