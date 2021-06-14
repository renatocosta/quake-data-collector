<?php

namespace Domains\Context\PlayersInformation\Application\UseCases\Reporting;

use Domains\Context\PlayersInformation\Infrastructure\DataAccess\Repositories\DataSourceRepository;

final class ReportingUseCase implements IReportingUseCase
{
    private DataSourceRepository $dataSourceRepository;

    public function __construct(DataSourceRepository $dataSourceRepository)
    {
        $this->dataSourceRepository = $dataSourceRepository;
    }

    public function execute()
    {
        return $this->dataSourceRepository->findAll();
    }
}
