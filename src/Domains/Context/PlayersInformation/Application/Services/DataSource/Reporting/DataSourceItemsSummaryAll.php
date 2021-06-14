<?php

namespace Domains\Context\PlayersInformation\Application\Services\DataSource\Reporting;

use Domains\Context\PlayersInformation\Domain\Services\Reporting\DataSourceItemsSummarized;

class DataSourceItemsSummaryAll implements DataSourceItemsSummarized
{

    public function filter($items): array
    {
        return $items->toArray();
    }

}