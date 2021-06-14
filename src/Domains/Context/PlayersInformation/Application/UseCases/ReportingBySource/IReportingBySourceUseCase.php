<?php

namespace Domains\Context\PlayersInformation\Application\UseCases\ReportingBySource;

interface IReportingBySourceUseCase
{
    public function execute(string $sourceId): array;
}