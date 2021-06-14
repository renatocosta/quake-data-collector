<?php

namespace Domains\Context\PlayersInformation\Outbound;

use Domains\CrossCutting\Domain\Application\Services\Common\MessageHandler;
use Domains\Context\PlayersInformation\Domain\Model\DataSource\DataSourced;

interface IDataSourceOutputPort
{

    public function invalid(MessageHandler $messageHandler): void;

    public function ok(DataSourced $dataSourced, MessageHandler $messageHandler): void;

    public function result(): MessageHandler;
}
