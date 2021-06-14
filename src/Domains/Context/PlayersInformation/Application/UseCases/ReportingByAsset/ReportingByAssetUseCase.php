<?php

namespace Domains\Context\PlayersInformation\Application\UseCases\ReportingByAsset;

use Domains\Context\PlayersInformation\Application\UseCases\ReportingByAsset\IReportingByAssetUseCase;
use Domains\Context\PlayersInformation\Domain\Services\Reporting\DataSourceItemsReported;

final class ReportingByAssetUseCase implements IReportingByAssetUseCase
{

    private DataSourceItemsReported $dataSourceItemsReporting;

    public function __construct(DataSourceItemsReported $dataSourceItemsReporting)
    {
        $this->dataSourceItemsReporting = $dataSourceItemsReporting;
    }

    public function execute(string $assetCode): array
    {
        $this->dataSourceItemsReporting->find(['asset_code' => $assetCode]);
        return $this->dataSourceItemsReporting->build();
    }
}