<?php

namespace Domains\Context\LogHandler\Domain\Model\SpreadsheetSource;

use Domains\CrossCutting\Domain\Model\Common\Validatable;

interface ISpreadsheet extends Validatable
{

    public function skipHeader(array $rows): array;

    /**
     * @param array $rows
     */
    public function extractFrom(array $rows): void;

    /**
     * @param array $row
     */
    public function addRow(array $row): void;

    /**
     * @return \SplDoublyLinkedList
     */
    public function getRows(): \SplDoublyLinkedList;

}