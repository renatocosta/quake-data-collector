<?php

namespace Domains\Context\PlayersInformation\Application\Services\DataSource;

use Domains\Context\Core\Entities\Invoice;
use Domains\Context\PlayersInformation\Domain\Model\DataSource\DataSourceItem\DataSourcedItem;
use Domains\Context\PlayersInformation\Domain\Model\DataSource\DataSourceItem\SourceItemStatusEnum;
use Domains\Context\PlayersInformation\Domain\Model\DataSource\DataSourceItem\SourceItemStatus;
use Domains\Context\PlayersInformation\Domain\Services\InvoiceDataIntegration;
use Domains\Context\Trade\Entities\TradeInvoice;

class InvoiceData extends InvoiceDataIntegration
{

    protected function filteredStatus(DataSourcedItem $item): bool
    {
        return $item->getStatus() == new SourceItemStatus(SourceItemStatusEnum::CREATED);
    }

    protected function createInvoice(DataSourcedItem $item): Invoice
    {

        $currentDateTime = time();

        $invoice = $this->invoice->replicate();
        $invoice->amount = $item->getAmount();
        $invoice->sender_ref = $item->getExternalInvoiceId();
        $invoice->description = $item->getDescription();
        $invoice->created_at = $currentDateTime;
        $invoice->sent_at = $currentDateTime;
        $invoice->approved_at = $currentDateTime;
        $invoice->created_by = \Auth::id();
        $invoice->save();

        return $invoice;
    }

    protected function updateInvoiceDataSourceItem(DataSourcedItem $item, string $invoiceId): void
    {
        $invoiceDataSourceItems = $this->invoiceDataSourceItems->replicate();
        $itemRepo = $invoiceDataSourceItems->find($item->getId());
        $itemRepo->invoice_id = $invoiceId;
        $itemRepo->status = $this->filteredStatus($item) ? SourceItemStatusEnum::SUCCESS : $item->getStatus();
        $itemRepo->save();
    }

    protected function createTradeInvoice(DataSourcedItem $item, Invoice $invoice): void
    {
        $tradeInvoice = new TradeInvoice();
        $tradeInvoice->trade_id = $item->getTradeId();
        $tradeInvoice->invoice_id = $invoice->id;
        $tradeInvoice->save();
    }
}
