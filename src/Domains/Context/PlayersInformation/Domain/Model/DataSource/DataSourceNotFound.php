<?php

namespace Domains\Context\PlayersInformation\Domain\Model\DataSource;

use Domains\Context\PlayersInformation\Domain\Model\DataSource\DataSourceItem\DataSourcedItem;

final class DataSourceNotFound implements DataSourced
{

    public function createFrom(SourceType $type, int $remoteFileId, int $companyId, array $items): DataSourced
    {
        return $this;
    }

    public function setId(int $id): void
    {

    }
   
    public function getId(): int
    {
        return 0;
    }

    public function getType(): SourceType
    {
        return new SourceType('');
    }

    public function getCompanyId(): int
    {
        return 0;
    }

    public function getRemoteFileId(): int
    {
        return 0;
    }

    public function addLineItem(DataSourcedItem $item): void
    {
    }

    public function getItems(): \SplDoublyLinkedList
    {
        return new \SplDoublyLinkedList();
    }

    public function isValid(): bool
    {
        return false;
    }

    public function getErrors(): array
    {
        return [];
    }
}