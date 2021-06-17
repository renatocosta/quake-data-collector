<?php

namespace Domains\Context\LogHandler\Application\EventHandlers\HumanLogFile;

use Domains\Context\LogHandler\Domain\Model\HumanLogFile\HumanLogFileRejected;
use Domains\CrossCutting\Domain\Application\Event\AbstractEvent;
use Domains\CrossCutting\Domain\Application\Event\DomainEventHandler;
use Domains\CrossCutting\Domain\Application\Services\Common\MessageHandler;
use Illuminate\Support\Facades\Log;

final class HumanLogFileRejectedEventHandler implements DomainEventHandler
{

    private MessageHandler $messageHandler;

    public function __construct(MessageHandler $messageHandler)
    {
        $this->messageHandler = $messageHandler;
    }

    public function handle(AbstractEvent $domainEvent): void
    {
        Log::info(__CLASS__);
        $wholeLogErrors = [$domainEvent->humanLogFile->getErrors(), $domainEvent->humanLogFile->getRows()];
        dd($wholeLogErrors);
    }

    public function isSubscribedTo(AbstractEvent $domainEvent): bool
    {
        return $domainEvent instanceof HumanLogFileRejected;
    }
}
