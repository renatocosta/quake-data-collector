<?php

namespace Domains\Context\MatchReporting\Application\EventHandlers\DeathCauses;

use Domains\Context\MatchReporting\Domain\Model\DeathCauses\DeathCausesFailed;
use Domains\CrossCutting\Domain\Application\Event\AbstractEvent;
use Domains\CrossCutting\Domain\Application\Event\DomainEventHandler;
use Illuminate\Support\Facades\Log;

final class DeathCausesFailedEventHandler implements DomainEventHandler
{

    public function handle(AbstractEvent $domainEvent): void
    {
        $wholeLogErrors = [$domainEvent->deathCauses->getErrors()];
        Log::info(__CLASS__ . ' errors: ' . json_encode($wholeLogErrors));
    }

    public function isSubscribedTo(AbstractEvent $domainEvent): bool
    {
        return $domainEvent instanceof DeathCausesFailed;
    }
}
