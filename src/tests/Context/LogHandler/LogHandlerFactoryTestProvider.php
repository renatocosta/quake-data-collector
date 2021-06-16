<?php

namespace Tests\Context\LogHandler;

use Domains\Context\LogHandler\Application\UseCases\LogFile\SelectLogFileUseCase;
use Domains\CrossCutting\Domain\Application\Services\Common\MessageHandler;
use Domains\Context\LogHandler\Domain\Model\LogFile\LogFileEntity;
use Domains\CrossCutting\Domain\Application\Event\Bus\DomainEventBus;

trait LogHandlerFactoryTestProvider
{

    protected $messageHandler;

    protected $logFile;

    protected $selectLogFileUseCase;

    protected DomainEventBus $domainEventBus;

    public function loadDependencies()
    {
       /* $this->createMessageHandler();
        $this->logFile();
        $this->selectLogFileUseCase();*/
        $this->createDomainEventBus();
    }

    protected function createMessageHandler()
    {
        $this->messageHandler = new MessageHandler();
    }

    protected function logFile()
    {
        $this->createDomainEventBus();
        $this->logFile = new LogFileEntity($this->domainEventBus);
    }

    protected function selectLogFileUseCase()
    {
        $this->selectLogFileUseCase = new SelectLogFileUseCase($this->logFile);
    }

    protected function createDomainEventBus()
    {
        $this->domainEventBus = new DomainEventBus();
    }
}
