<?php

namespace Domains\Context\LogHandler\Application\UseCases\Factories;

use Domains\CrossCutting\Domain\Application\Event\Bus\DomainEventBus;
use Domains\CrossCutting\Domain\Application\Event\DomainEventHandler;
use Domains\CrossCutting\Domain\Application\Services\Common\MessageHandler;
use Domains\Context\LogHandler\Domain\Model\LogFile\LogFile;
use Domains\Context\LogHandler\Domain\Model\LogFile\LogFileEntity;
use Domains\Context\LogHandler\Application\EventHandlers\LogFile\LogFileSelectedEventHandler;
use Domains\Context\LogHandler\Application\Services\HumanRowMapper;
use Domains\Context\LogHandler\Application\UseCases\HumanLogFile\CreateHumanLogFileUseCase;
use Domains\Context\LogHandler\Application\UseCases\HumanLogFile\ICreateHumanLogFileUseCase;
use Domains\Context\LogHandler\Application\UseCases\LogFile\ISelectLogFileUseCase;
use Domains\Context\LogHandler\Application\UseCases\LogFile\SelectLogFileInput;
use Domains\Context\LogHandler\Application\UseCases\LogFile\SelectLogFileUseCase;
use Domains\Context\LogHandler\Domain\Model\HumanLogFile\HumanLogFile;
use Domains\Context\LogHandler\Domain\Model\HumanLogFile\HumanLogFileEntity;
use Domains\Context\LogHandler\Domain\Model\LogFile\LogFileInfo;

abstract class QuakeDataCollectorFactory
{

    protected DomainEventBus $domainEventBus;

    protected HumanLogFile $humanLogFile;

    protected ICreateHumanLogFileUseCase $createHumanLogFileUseCase;

    protected LogFile $logFile;

    protected ISelectLogFileUseCase $selectLogFileUseCase;

    protected string $fileName;

    public function __construct(DomainEventBus $domainEventBus, string $fileName = LogFileInfo::DEFAULT_FILE_NAME)
    {
        $this->domainEventBus = $domainEventBus;
        $this->fileName = $fileName;
    }

    public function attachEventHandlerToHumanLogFile(DomainEventHandler $eventHandlerAttachment): void
    {
        $this->domainEventBus->subscribe($eventHandlerAttachment);
    }

    protected function addHumanLogFile(): void
    {
        $this->humanLogFile = new HumanLogFileEntity($this->domainEventBus);
    }

    protected function addHumanLogFileUseCase(): void
    {
        $this->createHumanLogFileUseCase = new CreateHumanLogFileUseCase($this->humanLogFile, new HumanRowMapper());
    }

    protected function addLogFile(): void
    {
        $this->domainEventBus->subscribe(new LogFileSelectedEventHandler($this->createHumanLogFileUseCase, new MessageHandler()));
        $this->logFile = new LogFileEntity($this->domainEventBus);
    }

    protected function addLogFileUseCase(): void
    {
        $this->selectLogFileUseCase = new SelectLogFileUseCase($this->logFile);
        $this->selectLogFileUseCase->execute(new SelectLogFileInput(new MessageHandler(), $this->fileName));
    }

    public abstract function dispatch(): void;
}
