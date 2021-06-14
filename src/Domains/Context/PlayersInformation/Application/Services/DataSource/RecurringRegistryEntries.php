<?php

namespace Domains\Context\PlayersInformation\Application\Services\DataSource;

use Domains\Context\PlayersInformation\Domain\Services\StatusItemCriteria;
use Domains\Context\PlayersInformation\Domain\Model\DataSource\DataSourceItem\SourceItemStatusEnum;

class RecurringRegistryEntries extends StatusItemCriteria
{

    public function matches(array $data)
    {

        if ($data['recurring_registry_entries']) {
            return $this->status(SourceItemStatusEnum::RECURRING_REGISTRY_ENTRIES);
        }

        return parent::next($data);
    }
}
