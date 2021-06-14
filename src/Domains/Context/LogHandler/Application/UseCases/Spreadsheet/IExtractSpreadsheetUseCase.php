<?php

namespace Domains\Context\LogHandler\Application\UseCases\Spreadsheet;

interface IExtractSpreadsheetUseCase
{

    public function execute(ExtractSpreadsheetInput $input);

}