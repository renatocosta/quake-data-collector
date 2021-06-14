<?php

namespace Domains\Context\PlayersInformation\Application\Services\DataSource;

use Domains\Context\PlayersInformation\Domain\Services\StatusItemCriteria;
use Domains\Context\PlayersInformation\Domain\Model\DataSource\DataSourceItem\SourceItemStatusEnum;

class DefaultItemStatusCriteria extends StatusItemCriteria
{

    public function matches(array $data)
    {
        return $this->status(SourceItemStatusEnum::CREATED);
    }    

}