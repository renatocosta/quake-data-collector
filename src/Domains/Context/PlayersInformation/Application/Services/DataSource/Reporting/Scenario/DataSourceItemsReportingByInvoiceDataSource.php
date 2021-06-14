<?php

namespace Domains\Context\PlayersInformation\Application\Services\DataSource\Reporting\Scenario;

use Domains\Context\PlayersInformation\Domain\Services\Reporting\DataSourceItemsReported;

class DataSourceItemsReportingByInvoiceDataSource extends DataSourceItemsReported
{

    public function find(array $filter): void
    {
       $this->items = $this->dataSourceRepository->findByItemsByInvoiceDataSourceId($filter['invoice_data_source_id']);
    }

}