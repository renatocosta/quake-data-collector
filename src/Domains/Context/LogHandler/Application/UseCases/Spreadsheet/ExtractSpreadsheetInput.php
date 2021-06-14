<?php

namespace Domains\Context\LogHandler\Application\UseCases\Spreadsheet;

use Domains\CrossCutting\Domain\Application\Services\Common\MessageHandler;
use Domains\Context\InvoiceImporter\Domain\Model\DataSource\DataSourceItem\SourceItemContractEnum;

final class ExtractSpreadsheetInput
{

    public $file;

    public string $contract;

    public MessageHandler $modelState;

    public function __construct($file, MessageHandler $messageHandler, string $contract = SourceItemContractEnum::V1000)
    {
        $this->modelState = $messageHandler;
        $this->file = $file;
        $this->contract = $contract;
    }
}
