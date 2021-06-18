<?php

namespace Domains\Context\MatchReporting\Application\EventHandlers\DeathCauses;

use Domains\Context\MatchReporting\Domain\Model\DeathCauses\DeathCausesWereFound;
use Domains\CrossCutting\Domain\Application\Event\AbstractEvent;
use Domains\CrossCutting\Domain\Application\Event\DomainEventHandler;
use Illuminate\Support\Facades\Log;

final class DeathCausesEventHandler implements DomainEventHandler
{

    public function handle(AbstractEvent $domainEvent): void
    {
        Log::info(__CLASS__);
    }

    public function isSubscribedTo(AbstractEvent $domainEvent): bool
    {
        return $domainEvent instanceof DeathCausesWereFound;
    }
}
