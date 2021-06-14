<?php

namespace Domains\Context\PlayersInformation\Application\UseCases\CreateDataSource;

interface ICreateDataSourceUseCase
{
    public function execute(CreateDataSourceInput $input);
}