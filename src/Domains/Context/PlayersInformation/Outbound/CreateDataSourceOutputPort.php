<?php

namespace Domains\Context\PlayersInformation\Outbound;

use Domains\CrossCutting\Domain\Application\Services\Common\MessageHandler;
use Domains\Context\PlayersInformation\Domain\Model\DataSource\DataSourced;
use Domains\Context\PlayersInformation\Domain\Services\InvoiceDataIntegration;
use Domains\Context\PlayersInformation\Domain\Services\Reporting\DataSourceItemsReported;

final class CreateDataSourceOutputPort implements IDataSourceOutputPort
{

    private InvoiceDataIntegration $invoiceData;

    private MessageHandler $result;

    private DataSourceItemsReported $dataSourceItemsReporting;

    public function __construct(InvoiceDataIntegration $invoiceData, DataSourceItemsReported $dataSourceItemsReporting)
    {
        $this->invoiceData = $invoiceData;
        $this->dataSourceItemsReporting = $dataSourceItemsReporting;
    }

    public function invalid(MessageHandler $messageHandler): void
    {
        $this->result = $messageHandler;
    }

    public function ok(DataSourced $dataSourced, MessageHandler $messageHandler): void
    {
        $this->invoiceData->collectItems($dataSourced);
        $this->invoiceData->create();

        $this->dataSourceItemsReporting->find(['invoice_data_source_id' => $dataSourced->getId()]);
        $itemsReported = $this->dataSourceItemsReporting->build();

        $this->result = $messageHandler;
        $this->result->addList($itemsReported);
    }

    public function result(): MessageHandler
    {
        return $this->result;
    }
}
