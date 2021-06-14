<?php

namespace Tests\Context\PlayersInformation;

use Domains\CrossCutting\Domain\Application\Services\Common\MessageHandler;
use Domains\Context\PlayersInformation\Domain\Model\DataSource\DataSourced;
use Domains\Context\PlayersInformation\Outbound\IDataSourceOutputPort;

final class CreateDataSourceOutputPortMocked implements IDataSourceOutputPort
{
    private MessageHandler $result;

    public function invalid(MessageHandler $messageHandler): void
    {
        $this->result = $messageHandler;
    }

    public function ok(DataSourced $dataSourced, MessageHandler $messageHandler): void
    {
        $this->result = $messageHandler;
    }

    public function result(): MessageHandler
    {
        return $this->result;
    }
}
