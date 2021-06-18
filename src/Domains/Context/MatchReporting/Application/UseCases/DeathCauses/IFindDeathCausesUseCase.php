<?php

namespace Domains\Context\MatchReporting\Application\UseCases\DeathCauses;

interface IFindDeathCausesUseCase
{

    public function execute(FindDeathCausesInput $input): void;

}