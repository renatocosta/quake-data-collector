<?php

namespace Tests\Context\LogHandler;

use Domains\Context\LogHandler\Application\UseCases\LogFile\SelectLogFileUseCase;
use Domains\CrossCutting\Domain\Application\Services\Common\MessageHandler;
use Domains\Context\LogHandler\Domain\Model\LogFile\LogFileEntity;
use Domains\CrossCutting\Domain\Application\Event\Bus\DomainEventBus;

trait LogHandlerFactoryTestProvider
{

    protected DomainEventBus $domainEventBus;

    public function loadDependencies()
    {
        $this->createDomainEventBus();
    }

    protected function createDomainEventBus()
    {
        $this->domainEventBus = new DomainEventBus();
    }
}
