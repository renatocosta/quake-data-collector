<?php

namespace Domains\Context\LogHandler\Application\EventHandlers\HumanLogFile;

use Domains\Context\LogHandler\Domain\Model\HumanLogFile\HumanLogFileCreated;
use Domains\Context\MatchReporting\Application\UseCases\PlayersKilled\FindPlayersKilledInput;
use Domains\Context\MatchReporting\Application\UseCases\PlayersKilled\FindPlayersKilledUseCase;
use Domains\Context\MatchReporting\Application\UseCases\PlayersKilled\IFindPlayersKilledUseCase;
use Domains\CrossCutting\Domain\Application\Event\AbstractEvent;
use Domains\CrossCutting\Domain\Application\Event\DomainEventHandler;
use Illuminate\Support\Facades\Log;

final class HumanLogFileCreatedForPlayersKilledEventHandler implements DomainEventHandler
{

    private IFindPlayersKilledUseCase $findPlayersKilledUseCase;

    public function __construct(IFindPlayersKilledUseCase $findPlayersKilledUseCase)
    {
        $this->findPlayersKilledUseCase = $findPlayersKilledUseCase;
    }

    public function handle(AbstractEvent $domainEvent): void
    {
        Log::info(__CLASS__);
        $rows = array_map(function ($row) {
            return ['who_killed' => $row->getPlayerWhoKilled(), 'who_died' => $row->getPlayerWhoDied(), 'means' => $row->getMeanOfDeath()];
        }, $domainEvent->humanLogFile->getRows());

        $inputCase = new FindPlayersKilledInput($rows);
        $this->findPlayersKilledUseCase->execute($inputCase);
    }

    public function isSubscribedTo(AbstractEvent $domainEvent): bool
    {
        return $domainEvent instanceof HumanLogFileCreated;
    }
}
