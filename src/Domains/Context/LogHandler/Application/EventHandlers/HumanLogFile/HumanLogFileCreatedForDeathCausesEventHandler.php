<?php

namespace Domains\Context\LogHandler\Application\EventHandlers\HumanLogFile;

use Domains\Context\LogHandler\Domain\Model\HumanLogFile\HumanLogFileCreated;
use Domains\Context\MatchReporting\Application\UseCases\DeathCauses\FindDeathCausesInput;
use Domains\Context\MatchReporting\Application\UseCases\DeathCauses\IFindDeathCausesUseCase;
use Domains\CrossCutting\Domain\Application\Event\AbstractEvent;
use Domains\CrossCutting\Domain\Application\Event\DomainEventHandler;
use Illuminate\Support\Facades\Log;

final class HumanLogFileCreatedForDeathCausesEventHandler implements DomainEventHandler
{

    private IFindDeathCausesUseCase $findDeathCausesUseCase;

    public function __construct(IFindDeathCausesUseCase $findDeathCausesUseCase)
    {
        $this->findDeathCausesUseCase = $findDeathCausesUseCase;
    }

    public function handle(AbstractEvent $domainEvent): void
    {
        Log::info(__CLASS__);
        $rows = array_map(function ($row) {
            return ['means_of_death' => $row->getMeanOfDeath()];
        }, $domainEvent->humanLogFile->getRows());

        $inputCase = new FindDeathCausesInput($rows);
        $this->findDeathCausesUseCase->execute($inputCase);
    }

    public function isSubscribedTo(AbstractEvent $domainEvent): bool
    {
        return $domainEvent instanceof HumanLogFileCreated;
    }
}
