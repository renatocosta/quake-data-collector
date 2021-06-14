<?php

namespace Domains\Context\PlayersInformation\Application\EventHandlers\DataSource;

use Domains\CrossCutting\Domain\Application\Event\AbstractEvent;
use Domains\CrossCutting\Domain\Application\Event\DomainEventHandler;
use Domains\Context\PlayersInformation\Domain\Model\DataSource\DataSourceWasCreated;
use Domains\Context\PlayersInformation\Domain\Model\DataSource\IDataSourceRepository;

class DataSourceWasCreatedEventHandler implements DomainEventHandler
{

    private IDataSourceRepository $dataSourceRepository;

    public function __construct(IDataSourceRepository $dataSourceRepository)
    {
        $this->dataSourceRepository = $dataSourceRepository;
    }

    public function handle(AbstractEvent $domainEvent): void
    {
        $this->dataSourceRepository->create($domainEvent->dataSourced);
        $this->dataSourceRepository->createItems($domainEvent->dataSourced->getItems());
    }

    public function isSubscribedTo(AbstractEvent $domainEvent): bool
    {
        return $domainEvent instanceof DataSourceWasCreated;
    }
}