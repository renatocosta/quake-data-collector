<?php

namespace Domains\Context\PlayersInformation\Application\Services\DataSource\Reporting;

use Domains\Context\PlayersInformation\Domain\Model\DataSource\DataSourceItem\SourceItemStatusEnum;
use Domains\Context\PlayersInformation\Domain\Services\Reporting\DataSourceItemsSummarized;

class DataSourceItemsSummaryCounting implements DataSourceItemsSummarized
{

    public function filter($items): array
    {
        $items = $items->items;
        $total = $items->count();
        $invoiceDataSourceId = null;
        $statuses = SourceItemStatusEnum::STATUS;
        $skipedStatus = SourceItemStatusEnum::CREATED;
        $statusCounting = [];

        //Set default values
        array_walk($statuses, function ($value, $key) use (&$statusCounting, $skipedStatus) {
            if ($value != $skipedStatus) {
                $statusCounting[$value] = 0;
            }
        });

        array_filter($items->toArray(), function ($item) use (&$statusCounting) {
            $status = $item['status'];
            if (isset($statusCounting[$status])) {
                $statusCounting[$status]++;
            }
        });

        if ($total > 0) {
            $invoiceDataSourceId = $items[0]['invoice_data_source_id'];
        }

        return ['invoice_data_source_id' => $invoiceDataSourceId, 'counting' => ['total' => $total, 'total_by_statuses' => $statusCounting]];
    }
}
