<?php

namespace Domains\Context\LogHandler\Application\EventHandlers\LogFile;

use Domains\Context\LogHandler\Application\UseCases\HumanLogFile\CreateHumanLogFileInput;
use Domains\Context\LogHandler\Application\UseCases\HumanLogFile\ICreateHumanLogFileUseCase;
use Domains\Context\LogHandler\Domain\Model\LogFile\LogFileSelected;
use Domains\CrossCutting\Domain\Application\Event\AbstractEvent;
use Domains\CrossCutting\Domain\Application\Event\DomainEventHandler;
use Domains\CrossCutting\Domain\Application\Services\Common\MessageHandler;
use Illuminate\Support\Facades\Log;

final class LogFileSelectedEventHandler implements DomainEventHandler
{

    private ICreateHumanLogFileUseCase $createHumanLogFileUseCase;

    private MessageHandler $messageHandler;

    public function __construct(ICreateHumanLogFileUseCase $createHumanLogFileUseCase, MessageHandler $messageHandler)
    {
        $this->createHumanLogFileUseCase = $createHumanLogFileUseCase;
        $this->messageHandler = $messageHandler;
    }

    public function handle(AbstractEvent $domainEvent): void
    {
        $logFile = $domainEvent->logFile;
        $metadata = $logFile->getMetadata();
        $inputCase = new CreateHumanLogFileInput($logFile->getContent(), ['size' => $metadata->size, 'extension' => $metadata->extension], $this->messageHandler);
        $this->createHumanLogFileUseCase->execute($inputCase);
    }

    public function isSubscribedTo(AbstractEvent $domainEvent): bool
    {
        return $domainEvent instanceof LogFileSelected;
    }
}
