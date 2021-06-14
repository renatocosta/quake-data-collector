<?php

namespace Domains\Context\PlayersInformation\Domain\Specifications;

use Domains\CrossCutting\Model\Specification\CompositeSpecification;

final class DataSourceRecurringRegistryEntries extends CompositeSpecification
{

    public array $matchedItems = [];

    public const MIN_COUNT_TO_BE_A_REPEATED_ITEM = 2;

    public bool $isRecurring = false;

    /**
     * @param mixed $filter
     * @return bool
     */
    public function isSatisfiedBy($filter): bool
    {

        $this->matchedItems = [];

        $matchItem = $filter['item'];

        foreach ($filter['items'] as $item) {
            if ($item['asset_code'] == $matchItem['asset_code'] && $item['external_invoice_id'] == $matchItem['external_invoice_id'] && $item['po_number'] == $matchItem['po_number']) {
                $this->matchedItems[] = $item;
            }
        }

        $this->isRecurring = count($this->matchedItems) >= self::MIN_COUNT_TO_BE_A_REPEATED_ITEM;

        return $this->isRecurring;
    }
}
