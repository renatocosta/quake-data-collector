<?php

namespace Domains\Context\PlayersInformation\Inbound\WebApi\Controllers;

use App\Http\Controllers\Controller;
use Domains\Context\PlayersInformation\Application\UseCases\Reporting\IReportingUseCase;
use Domains\Context\PlayersInformation\Application\UseCases\ReportingBySource\IReportingBySourceUseCase;
use Domains\Context\PlayersInformation\Infrastructure\Framework\Transformers\DataSourceFilesResource;
use Domains\Context\PlayersInformation\Infrastructure\Framework\Transformers\DataSourceReportingResource;

class ReportingController extends Controller
{

    public function index(IReportingUseCase $reportingUseCase)
    {
        return DataSourceFilesResource::collection($reportingUseCase->execute());
    }

    public function source(string $sourceId, IReportingBySourceUseCase $reportingBySourceUseCase)
    {
        return new DataSourceReportingResource($reportingBySourceUseCase->execute($sourceId));
    }
}
