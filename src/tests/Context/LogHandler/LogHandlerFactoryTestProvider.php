<?php

namespace Tests\Context\LogHandler;

use Domains\CrossCutting\Domain\Application\Services\Common\MessageHandler;
use Domains\Context\LogHandler\Application\Services\Spreadsheet\SpreadsheetExtractor;
use Domains\Context\LogHandler\Application\UseCases\Spreadsheet\ExtractSpreadsheetUseCase;
use Domains\Context\LogHandler\Domain\Model\SpreadsheetSource\SpreadsheetSource;

trait LogHandlerFactoryTestProvider
{

    protected $messageHandler;

    protected $spreadsheetSource;

    protected $spreadsheetExtractor;

    protected $extractSpreadsheetOutputPort;

    protected $extractSpreadsheetUseCase;

    protected $remoteFileId = 10911;

    public function loadDependencies()
    {
        $this->createMessageHandler();
        $this->createSpreadsheet();
        $this->createSpreadsheetExtractor();
        $this->createExtractSpreadsheetOutputPort();
        $this->createExtractSpreadsheetUseCase();
    }

    protected function createMessageHandler()
    {
        $this->messageHandler = new MessageHandler();
    }

    protected function createSpreadsheet()
    {
        $this->spreadsheetSource = new SpreadsheetSource();
    }

    protected function createSpreadsheetExtractor()
    {
        $this->spreadsheetExtractor = new SpreadsheetExtractor();
    }

    protected function createExtractSpreadsheetOutputPort()
    {
        $this->extractSpreadsheetOutputPort = new ExtractSpreadsheetOutputPortMocked();
    }

    protected function createExtractSpreadsheetUseCase()
    {
        $this->extractSpreadsheetUseCase = new ExtractSpreadsheetUseCase($this->spreadsheetSource, $this->spreadsheetExtractor, $this->extractSpreadsheetOutputPort);
    }
}
