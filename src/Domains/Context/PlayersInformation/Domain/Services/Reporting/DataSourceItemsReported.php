<?php

namespace Domains\Context\PlayersInformation\Domain\Services\Reporting;

use Domains\Context\PlayersInformation\Domain\Model\DataSource\IDataSourceRepository;

abstract class DataSourceItemsReported
{

    protected IDataSourceRepository $dataSourceRepository;

    protected $items;

    public function __construct(IDataSourceRepository $dataSourceRepository)
    {
        $this->dataSourceRepository = $dataSourceRepository;
    }

    /**
     * @param DataSourceItemsSummarized[]
     */
    protected array $summaries = [];

    public function addSummary(DataSourceItemsSummarized $summary): self
    {
        $this->summaries[] = $summary;
        return $this;
    }

    abstract public function find(array $filter): void;

    public function build(): array
    {

        $itemsFiltered = [];

        foreach ($this->summaries as $summary) {
            $itemsFiltered = array_merge($itemsFiltered, $summary->filter($this->items));
        }

        return $itemsFiltered;
    }
}
