<?php

namespace Domains\Context\PlayersInformation\Application\UseCases\ReportingBySource;

use Domains\Context\PlayersInformation\Domain\Services\Reporting\DataSourceItemsReported;

final class ReportingBySourceUseCase implements IReportingBySourceUseCase
{
    private DataSourceItemsReported $dataSourceItemsReporting;

    public function __construct(DataSourceItemsReported $dataSourceItemsReporting)
    {
        $this->dataSourceItemsReporting = $dataSourceItemsReporting;
    }

    public function execute(string $sourceId): array
    {

        $this->dataSourceItemsReporting->find(['invoice_data_source_id' => $sourceId]);
        return $this->dataSourceItemsReporting->build();
    }
}