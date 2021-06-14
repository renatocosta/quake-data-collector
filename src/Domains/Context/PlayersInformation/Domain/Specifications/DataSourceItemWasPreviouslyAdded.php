<?php

namespace Domains\Context\PlayersInformation\Domain\Specifications;

use Domains\CrossCutting\Model\Specification\CompositeSpecification;
use Domains\Context\PlayersInformation\Domain\Model\DataSource\DataSourceItem\DataSourcedItem;
use Domains\Context\PlayersInformation\Domain\Model\DataSource\DataSourceItem\DataSourceItemNotFound;

final class DataSourceItemWasPreviouslyAdded extends CompositeSpecification
{

    private DataSourceItemWasPreviouslyAddedInRepo $dataSourceItemWasPreviouslyAddedInRepo;

    public DataSourceRecurringRegistryEntries $dataSourceRecurringRegistryEntries;

    public DataSourcedItem $dataSourcedItem;

    public array $dataSourceFileItem;

    public function __construct(DataSourceItemWasPreviouslyAddedInRepo $dataSourceItemWasPreviouslyAddedInRepo, DataSourceRecurringRegistryEntries $dataSourceRecurringRegistryEntries)
    {
        $this->dataSourceItemWasPreviouslyAddedInRepo = $dataSourceItemWasPreviouslyAddedInRepo;
        $this->dataSourceRecurringRegistryEntries = $dataSourceRecurringRegistryEntries;
    }

    /**
     * @param mixed $filter
     * @return bool
     */
    public function isSatisfiedBy($filter): bool
    {
        $this->dataSourceItemWasPreviouslyAddedInRepo->dataSourcedItem = new DataSourceItemNotFound();
        $needsToBeSatisfied = $this->dataSourceRecurringRegistryEntries->orSpecification($this->dataSourceItemWasPreviouslyAddedInRepo)->isSatisfiedBy($filter);
        $this->dataSourcedItem = $this->dataSourceItemWasPreviouslyAddedInRepo->dataSourcedItem;
        $this->dataSourceFileItem = $this->dataSourceRecurringRegistryEntries->matchedItems;

        return $needsToBeSatisfied;
    }
}
