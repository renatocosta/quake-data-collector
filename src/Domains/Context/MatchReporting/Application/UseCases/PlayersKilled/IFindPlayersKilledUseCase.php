<?php

namespace Domains\Context\MatchReporting\Application\UseCases\PlayersKilled;

interface IFindPlayersKilledUseCase
{

    public function execute(FindPlayersKilledInput $input): void;

}