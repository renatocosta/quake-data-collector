<?php

namespace Domains\Context\PlayersInformation\Application\Services\DataSource\Reporting;

use Domains\Context\PlayersInformation\Domain\Model\DataSource\DataSourceItem\SourceItemStatusEnum;
use Domains\Context\PlayersInformation\Domain\Services\Reporting\DataSourceItemsSummarized;

class DataSourceItemsSummaryBySuccess implements DataSourceItemsSummarized
{

    public function filter($items): array
    {
        
        $items = array_filter($items, function($item){
           return $item->getStatus() == SourceItemStatusEnum::SUCCESS;
        });

        return ['items' => $items];
    }

}