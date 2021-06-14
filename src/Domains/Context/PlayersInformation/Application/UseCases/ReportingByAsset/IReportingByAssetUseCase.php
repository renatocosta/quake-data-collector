<?php

namespace Domains\Context\PlayersInformation\Application\UseCases\ReportingByAsset;

interface IReportingByAssetUseCase
{
    public function execute(string $assetCode): array;
}