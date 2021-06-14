<?php

namespace Domains\Context\PlayersInformation\Domain\Model\DataSource;

use Domains\CrossCutting\Domain\Application\Event\AbstractEvent;

class DataSourceWasCreated extends AbstractEvent
{

    public DataSourced $dataSourced;

    public function __construct(DataSourced $dataSourced)
    {
        parent::__construct();
        $this->dataSourced = $dataSourced;
    }

}