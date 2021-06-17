<?php

namespace Domains\Context\LogHandler\Application\EventHandlers\HumanLogFile;

use Domains\Context\LogHandler\Domain\Model\HumanLogFile\HumanLogFileRejected;
use Domains\CrossCutting\Domain\Application\Event\AbstractEvent;
use Domains\CrossCutting\Domain\Application\Event\DomainEventHandler;
use Illuminate\Support\Facades\Log;

final class HumanLogFileRejectedEventHandler implements DomainEventHandler
{

    public function handle(AbstractEvent $domainEvent): void
    {
        $wholeLogErrors = [$domainEvent->humanLogFile->getErrors(), $domainEvent->humanLogFile->getRows()];
        Log::info(__CLASS__ . ' errors: ' . json_encode($wholeLogErrors));
    }

    public function isSubscribedTo(AbstractEvent $domainEvent): bool
    {
        return $domainEvent instanceof HumanLogFileRejected;
    }
}
