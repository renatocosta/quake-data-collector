<?php

namespace Domains\Context\PlayersInformation\Domain\Specifications;

use Domains\CrossCutting\Model\Specification\CompositeSpecification;
use Domains\Context\PlayersInformation\Domain\Model\DataSource\DataSourceItem\DataSourcedItem;
use Domains\Context\PlayersInformation\Domain\Model\DataSource\DataSourceItem\DataSourceItemNotFound;
use Domains\Context\PlayersInformation\Domain\Model\DataSource\IDataSourceRepository;

final class DataSourceItemWasPreviouslyAddedInRepo extends CompositeSpecification
{

    private IDataSourceRepository $dataSourceRepository;

    public DataSourcedItem $dataSourcedItem;

    public function __construct(IDataSourceRepository $dataSourceRepository)
    {
        $this->dataSourceRepository = $dataSourceRepository;
    }

    /**
     * @param mixed $filter
     * @return bool
     */
    public function isSatisfiedBy($filter): bool
    {
        $item = $filter['item'];
        $this->dataSourcedItem = $this->dataSourceRepository->findByAssetCodeAndPoNumberAndExternalInvoiceId($item['asset_code'], $item['po_number'], $item['external_invoice_id']);
        return !$this->dataSourcedItem instanceof DataSourceItemNotFound;
    }
}
