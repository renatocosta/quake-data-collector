<?php

namespace Domains\Context\PlayersInformation\Domain\Services;

use Domains\Context\Core\Entities\Invoice;
use Domains\Context\PlayersInformation\Domain\Model\DataSource\DataSourceItem\DataSourcedItem;
use Domains\Context\PlayersInformation\Infrastructure\Framework\Entities\InvoiceDataSourceItems;
use Domains\Context\PlayersInformation\Domain\Model\DataSource\DataSourced;

abstract class InvoiceDataIntegration
{

    protected Invoice $invoice;

    protected InvoiceDataSourceItems $invoiceDataSourceItems;

    private DataSourced $dataSource;

    private \SplDoublyLinkedList $items;

    public function __construct(Invoice $invoice, InvoiceDataSourceItems $invoiceDataSourceItems)
    {
        $this->invoice = $invoice;
        $this->invoiceDataSourceItems = $invoiceDataSourceItems;
    }

    public function collectItems(DataSourced $dataSource): void
    {
        $this->dataSource = $dataSource;
        $this->items = $this->dataSource->getItems();
    }

    abstract protected function filteredStatus(DataSourcedItem $item): bool;

    private function isEligibleToCreateInvoice(DataSourcedItem $item): bool
    {
        return !$item->wasPreviouslyInvoiced();
    }

    abstract protected function createInvoice(DataSourcedItem $item): Invoice;

    abstract protected function updateInvoiceDataSourceItem(DataSourcedItem $item, string $invoiceId): void;

    abstract protected function createTradeInvoice(DataSourcedItem $item, Invoice $invoice): void;

    public function create(): void
    {

        for ($this->items->rewind(); $this->items->valid(); $this->items->next()) {
            $item = $this->items->current();

            $invoiceId = $item->getInvoiceId();

            if ($this->filteredStatus($item)) {

                if ($this->isEligibleToCreateInvoice($item)) {
                    $savedInvoice = $this->createInvoice($item);
                    $invoiceId = $savedInvoice->id;
                    $this->createTradeInvoice($item, $savedInvoice);
                }
            }

            if ($invoiceId > 0) {
                $this->updateInvoiceDataSourceItem($item, $invoiceId);
            }
        }
    }
}
