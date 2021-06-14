<?php

namespace Domains\Context\PlayersInformation\Application\Services\DataSource;

use Domains\Context\PlayersInformation\Domain\Model\DataSource\DataSourceItem\SourceItemStatusEnum;
use Domains\Context\PlayersInformation\Domain\Services\StatusItemCriteria;

class PoNumberNotFound extends StatusItemCriteria
{

    public function matches(array $data)
    {

        if (empty($data['trade_id'])) {
            return $this->status(SourceItemStatusEnum::PO_NUMBER_NOT_FOUND);
        }

        return parent::next($data);
    }
}
