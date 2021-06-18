<?php

namespace Domains\Context\MatchReporting\Application\EventHandlers\PlayersKilled;

use Domains\Context\MatchReporting\Domain\Model\PlayersKilled\PlayersKilledWereFound;
use Domains\CrossCutting\Domain\Application\Event\AbstractEvent;
use Domains\CrossCutting\Domain\Application\Event\DomainEventHandler;
use Illuminate\Support\Facades\Log;

final class PlayersKilledEventHandler implements DomainEventHandler
{

    public function handle(AbstractEvent $domainEvent): void
    {
        Log::info(__CLASS__);
    }

    public function isSubscribedTo(AbstractEvent $domainEvent): bool
    {
        return $domainEvent instanceof PlayersKilledWereFound;
    }
}
