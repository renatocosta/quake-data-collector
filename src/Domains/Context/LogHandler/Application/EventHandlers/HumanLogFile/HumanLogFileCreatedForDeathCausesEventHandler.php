<?php

namespace Domains\Context\LogHandler\Application\EventHandlers\HumanLogFile;

use Domains\Context\LogHandler\Domain\Model\HumanLogFile\HumanLogFileCreated;
use Domains\CrossCutting\Domain\Application\Event\AbstractEvent;
use Domains\CrossCutting\Domain\Application\Event\DomainEventHandler;
use Illuminate\Support\Facades\Log;

final class HumanLogFileCreatedForDeathCausesEventHandler implements DomainEventHandler
{

    public function handle(AbstractEvent $domainEvent): void
    {
        Log::info(__CLASS__);
        dd($domainEvent->humanLogFile->getTotalKills());
    }

    public function isSubscribedTo(AbstractEvent $domainEvent): bool
    {
        return $domainEvent instanceof HumanLogFileCreated;
    }
}
