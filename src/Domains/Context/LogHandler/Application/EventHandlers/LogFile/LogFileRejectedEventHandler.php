<?php

namespace Domains\Context\LogHandler\Application\EventHandlers\LogFile;

use Domains\Context\LogHandler\Domain\Model\LogFile\LogFileRejected;
use Domains\CrossCutting\Domain\Application\Event\AbstractEvent;
use Domains\CrossCutting\Domain\Application\Event\DomainEventHandler;
use Illuminate\Support\Facades\Log;

final class LogFileRejectedEventHandler implements DomainEventHandler
{

    public function handle(AbstractEvent $domainEvent): void
    {
        $wholeLogErrors = [$domainEvent->logFile->getErrors()];
        Log::info(__CLASS__ . ' errors: ' . json_encode($wholeLogErrors));
    }

    public function isSubscribedTo(AbstractEvent $domainEvent): bool
    {
        return $domainEvent instanceof LogFileRejected;
    }
}
