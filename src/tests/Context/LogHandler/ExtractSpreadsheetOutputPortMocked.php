<?php

namespace Tests\Context\LogHandler;

use Domains\CrossCutting\Domain\Application\Services\Common\MessageHandler;
use Domains\Context\LogHandler\Domain\Model\SpreadsheetSource\ISpreadsheet;
use Domains\Context\LogHandler\Outbound\ISpreadsheetOutputPort;

final class ExtractSpreadsheetOutputPortMocked implements ISpreadsheetOutputPort
{

    private MessageHandler $result;

    public ISpreadsheet $dataSourced;

    public function invalid(MessageHandler $messageHandler): void
    {
        $this->result = $messageHandler;
    }

    public function ok(ISpreadsheet $dataSourced, int $remoteFileId, string $contract, MessageHandler $messageHandler): void
    {
        $this->dataSourced = $dataSourced;
        $this->result = $messageHandler;
    }

    public function result(): MessageHandler
    {
        return $this->result;
    }
}
