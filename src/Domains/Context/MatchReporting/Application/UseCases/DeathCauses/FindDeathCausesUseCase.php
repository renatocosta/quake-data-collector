<?php

namespace Domains\Context\MatchReporting\Application\UseCases\DeathCauses;

use Domains\Context\MatchReporting\Domain\Model\DeathCauses\Matcher;
use Domains\Context\MatchReporting\Domain\Model\DeathCauses\DeathCauses;

final class FindDeathCausesUseCase implements IFindDeathCausesUseCase
{
    private DeathCauses $deathCauses;

    public function __construct(DeathCauses $deathCauses)
    {
        $this->deathCauses = $deathCauses;
    }

    public function execute(FindDeathCausesInput $input): void
    {
        foreach ($input->rows as $row) {
            $this->deathCauses->computeCause(new Matcher($row['means_of_death']));
        }
        $this->deathCauses->find();
    }
}
