<?php

namespace Domains\Context\PlayersInformation\Domain\Model\DataSource;

use Domains\Context\PlayersInformation\Domain\Model\DataSource\DataSourceItem\DataSourcedItem;
use Domains\CrossCutting\Domain\Model\Common\Validatable;

interface DataSourced extends Validatable
{

    /**
     * @param SourceType $type
     * @param int $remoteFileId
     * @param int $companyId
     * @param array $items
     * @return DataSourced
     */
    public function createFrom(SourceType $type, int $remoteFileId, int $companyId, array $items): DataSourced;

    public function setId(int $id): void;
   
    /**
     * @return int
     */
    public function getId(): int;

    /**
     * @return SourceType
     */
    public function getType(): SourceType;

    /**
     * @return int
     */
    public function getCompanyId(): int;

    /**
     * @return string
     */
    public function getRemoteFileId(): int;

    /**
     * @param DataSourcedItem $item
     */
    public function addLineItem(DataSourcedItem $item): void;

    /**
     * @return \SplDoublyLinkedList
     */
    public function getItems(): \SplDoublyLinkedList;

}