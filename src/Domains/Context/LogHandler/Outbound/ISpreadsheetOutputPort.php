<?php

namespace Domains\Context\LogHandler\Outbound;

use Domains\Context\LogHandler\Domain\Model\SpreadsheetSource\ISpreadsheet;
use Domains\CrossCutting\Domain\Application\Services\Common\MessageHandler;

interface ISpreadsheetOutputPort
{

    public function invalid(MessageHandler $messageHandler): void;

    public function ok(ISpreadsheet $dataSource, int $remoteFileId, string $contract, MessageHandler $messageHandler): void;

    public function result(): MessageHandler;

}