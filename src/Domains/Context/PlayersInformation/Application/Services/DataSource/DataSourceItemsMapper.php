<?php

namespace Domains\Context\PlayersInformation\Application\Services\DataSource;

use Domains\CrossCutting\Model\ValueObjects\Money\Currency;
use Illuminate\Support\Facades\Log;
use Domains\Context\PlayersInformation\Domain\Model\DataSource\DataSourceItem\DataSourceItemNotFound;
use Domains\Context\PlayersInformation\Domain\Services\DataSourceItemsMapped;
use Domains\Context\PlayersInformation\Domain\Services\StatusItemCriteria;
use Domains\Context\PlayersInformation\Domain\Specifications\DataSourceItemCanBeATrade;
use Domains\Context\PlayersInformation\Domain\Specifications\DataSourceItemWasPreviouslyAdded;

class DataSourceItemsMapper implements DataSourceItemsMapped
{

    private StatusItemCriteria $statusItemCriteria;

    private DataSourceItemWasPreviouslyAdded $dataSourceItemWasPreviouslyAdded;

    private DataSourceItemCanBeATrade $dataSourceItemCanBeATrade;

    public function __construct(StatusItemCriteria $statusItemCriteria, DataSourceItemWasPreviouslyAdded $dataSourceItemWasPreviouslyAdded, DataSourceItemCanBeATrade $dataSourceItemCanBeATrade)
    {
        $this->statusItemCriteria = $statusItemCriteria;
        $this->dataSourceItemWasPreviouslyAdded = $dataSourceItemWasPreviouslyAdded;
        $this->dataSourceItemCanBeATrade = $dataSourceItemCanBeATrade;
    }

    public function map(array $data): array
    {

        $items = [];

        foreach ($data['items'] as $index => $item) {

            $items[$index] = $item;

            $items[$index]['trade_id'] = '';
            $isDuplicatePoNumber = false;
            $itemPreviouslyAdded = ['amount' => new Currency(0)];
            $itemWasPreviouslyAddedInRepo = false;
            $recurringRegistryEntries = false;

            //Assign trade id
            $needsToBeATrade = $this->dataSourceItemCanBeATrade->isSatisfiedBy($items[$index]);
            if ($needsToBeATrade) {
                $tradeId = $this->dataSourceItemCanBeATrade->tradeId;
                $items[$index]['trade_id'] = $tradeId;
            }

            $isDuplicatePoNumber = $this->dataSourceItemCanBeATrade->isDuplicatePoNumber;

            //If there is a previously item related then 
            $itemWasPreviouslyAdded = $this->dataSourceItemWasPreviouslyAdded->isSatisfiedBy(['item' => $items[$index], 'items' => $data['items']]);

            if ($itemWasPreviouslyAdded) {
                if ($this->dataSourceItemWasPreviouslyAdded->dataSourceRecurringRegistryEntries->isRecurring) {
                    //Item has been found into uploaded file at least twice
                    $dataSourcedFileItem = $this->dataSourceItemWasPreviouslyAdded->dataSourceFileItem;
                    $itemPreviouslyAdded['amount'] = new Currency($dataSourcedFileItem[0]['amount']);
                    $recurringRegistryEntries = true;
                } else if (!$this->dataSourceItemWasPreviouslyAdded->dataSourcedItem instanceof DataSourceItemNotFound) {
                    //Item has been found into repo
                    $itemWasPreviouslyAddedInRepo = true;
                    $dataSourcedItem = $this->dataSourceItemWasPreviouslyAdded->dataSourcedItem;
                    $itemPreviouslyAdded['amount'] = $dataSourcedItem->getAmount();
                    if ($dataSourcedItem->wasPreviouslyInvoiced() && $dataSourcedItem->getInvoiceId() > 0) {
                        $items[$index]['invoice_id'] = $dataSourcedItem->getInvoiceId();
                        $items[$index]['invoiced'] = true;
                    }
                }
            }

            //Assign a final status to this item
            $status = $this->statusItemCriteria->matches([
                'additional_info' => $data['additional_info'], 'item' => $item,
                'is_duplicate_po_number' => $isDuplicatePoNumber,
                'recurring_registry_entries' => $recurringRegistryEntries,
                'item_was_previously_added' => $itemWasPreviouslyAdded,
                'item_previously_added' => $itemPreviouslyAdded,
                'item_was_previously_added_in_repo' => $itemWasPreviouslyAddedInRepo,
                'trade_id' => $items[$index]['trade_id']
            ]);

            $items[$index]['status'] = $status;
        }

        $data['items'] = $items;

        return $data;
    }
}
