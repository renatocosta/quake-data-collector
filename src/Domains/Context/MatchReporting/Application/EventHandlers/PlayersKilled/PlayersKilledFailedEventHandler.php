<?php

namespace Domains\Context\MatchReporting\Application\EventHandlers\PlayersKilled;

use Domains\Context\MatchReporting\Domain\Model\PlayersKilled\FindPlayersKilledFailed;
use Domains\Context\MatchReporting\Domain\Model\PlayersKilled\PlayersKilledFailed;
use Domains\CrossCutting\Domain\Application\Event\AbstractEvent;
use Domains\CrossCutting\Domain\Application\Event\DomainEventHandler;
use Illuminate\Support\Facades\Log;

final class PlayersKilledFailedEventHandler implements DomainEventHandler
{

    public function handle(AbstractEvent $domainEvent): void
    {
        $wholeLogErrors = [$domainEvent->playersKilled->getErrors()];
        Log::info(__CLASS__ . ' errors: ' . json_encode($wholeLogErrors));
    }

    public function isSubscribedTo(AbstractEvent $domainEvent): bool
    {
        return $domainEvent instanceof PlayersKilledFailed;
    }
}
