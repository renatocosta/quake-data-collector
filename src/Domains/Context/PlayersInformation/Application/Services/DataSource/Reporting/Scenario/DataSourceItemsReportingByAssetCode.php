<?php

namespace Domains\Context\PlayersInformation\Application\Services\DataSource\Reporting\Scenario;

use Domains\Context\PlayersInformation\Domain\Services\Reporting\DataSourceItemsReported;

class DataSourceItemsReportingByAssetCode extends DataSourceItemsReported
{

    public function find(array $filter): void
    {
       $this->items = $this->dataSourceRepository->findByItemsByAssetCode($filter['asset_code']);
    }

}