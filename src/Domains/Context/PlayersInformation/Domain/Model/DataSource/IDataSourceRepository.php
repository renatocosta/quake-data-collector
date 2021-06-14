<?php

namespace Domains\Context\PlayersInformation\Domain\Model\DataSource;

use Domains\Context\PlayersInformation\Domain\Model\DataSource\DataSourceItem\DataSourcedItem;

interface IDataSourceRepository
{

    /**
     * @param int $id
     * @return array
     */
    public function findAll();

    /**
     * @param DataSourced $dataSourced
     */
    public function update(DataSourced $dataSourced): void;

    /**
     * @param DataSourced $dataSourced
     */
    public function create(DataSourced $dataSourced): void;

    public function findByItemsByAssetCode(string $code): array;

    /**
     * @param int $id
     * @return DataSourcedItem[]
     */
    public function findByItemsByInvoiceDataSourceId(int $id);

    /**
     * @param string $asset
     * @param string $poNumber
     * @param string $externalInvoice
     * @return DataSourcedItem
     */
    public function findByAssetCodeAndPoNumberAndExternalInvoiceId(string $asset, string $poNumber, string $externalInvoice): DataSourcedItem;

    /**
     * @param DataSourcedItem $dataSourcedItem
     */
    public function updateByItem(DataSourcedItem $dataSourcedItem): void;

    /**
     * @param \SplDoublyLinkedList DataSourcedItem[] $items
     */
    public function createItems(\SplDoublyLinkedList $items): void;

}