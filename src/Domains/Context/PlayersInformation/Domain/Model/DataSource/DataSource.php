<?php

namespace Domains\Context\PlayersInformation\Domain\Model\DataSource;

use Domains\CrossCutting\Domain\Application\Event\Bus\DomainEventBus;
use Domains\CrossCutting\Domain\Model\ValueObjects\AggregateRoot;
use Assert\Assert;
use Assert\AssertionFailedException;
use Domains\Context\PlayersInformation\Domain\Model\DataSource\DataSourceItem\DataSourcedItem;
use Domains\Context\PlayersInformation\Domain\Model\DataSource\DataSourceItem\DataSourceItem;
use Domains\CrossCutting\Model\ValueObjects\Money\Currency;

final class DataSource extends AggregateRoot implements DataSourced
{

    private int $id;

    private SourceType $type;

    private int $companyId;

    private int $remoteFileId;

    private \SplDoublyLinkedList $sourceItems;

    private array $errors = [];

    public function __construct(DomainEventBus $domainEventBus)
    {
        parent::__construct($domainEventBus);
    }

    public function createFrom(SourceType $type, int $remoteFileId, int $companyId, array $items): DataSourced
    {

        $this->sourceItems = new \SplDoublyLinkedList();

        $this->type = $type;
        $this->remoteFileId = $remoteFileId;
        $this->companyId = $companyId;

        try {
            Assert::that($this->remoteFileId, 'integration_yardi-invoice-importer-message-REMOTE_ID_CAN_NOT_BE_ZERO')->greaterThan(0);
            Assert::that($this->companyId, 'integration_yardi-invoice-importer-message-COMPANY_ID_CAN_NOT_BE_ZERO')->greaterThan(0);
            Assert::that($items, 'integration_yardi-invoice-importer-message-AT_LEAST_ONE_DATA_SOURCE_ITEM_REQUIRED')->minCount(1);

            foreach ($items as $item) {
                $dataSourceItem = new DataSourceItem($item['asset_code'], $item['po_number'], new Currency($item['amount']), $item['description'], $item['external_invoice_id'], $item['raw'], $item['invoice_id'], $item['invoiced'], $item['trade_id'], $item['status']);
                $this->addLineItem($dataSourceItem);
            }
            $this->raise(
                new DataSourceWasCreated($this)
            );
        } catch (AssertionFailedException $e) {
            $this->errors[] = $e->getMessage();
        }

        return $this;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getType(): SourceType
    {
        return $this->type;
    }

    public function getCompanyId(): int
    {
        return $this->companyId;
    }

    public function getRemoteFileId(): int
    {
        return $this->remoteFileId;
    }

    public function addLineItem(DataSourcedItem $item): void
    {
        $this->sourceItems->push($item);
    }

    public function getItems(): \SplDoublyLinkedList
    {
        return $this->sourceItems;
    }

    public function isValid(): bool
    {
        return count($this->errors) === 0;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function __toString(): string
    {
        return sprintf('Source type %s | CompanyId %s | Items %s', 'ss', $this->companyId, $this->sourceItems->serialize());
    }
}
