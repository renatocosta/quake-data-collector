<?php

namespace Domains\Context\LogHandler\Domain\Model\SpreadsheetSource;

use Assert\Assert;
use Assert\AssertionFailedException;

final class SpreadsheetSource implements ISpreadsheet
{

    private \SplDoublyLinkedList $rows;

    private array $errors = [];

    public bool $isSkipHeader = false;

    public function skipHeader(array $rows): array
    {
        if ($this->isSkipHeader) {
            $rows = array_slice($rows, 1);
        }
        return $rows;
    }

    public function extractFrom(array $rows): void
    {
        $rows = $this->skipHeader($rows);

        $this->rows = new \SplDoublyLinkedList();

        try {
            Assert::that($rows, 'integration_yardi-spreadsheet-message-MUST_HAVE_ONE_ROW_PER_FILE')->minCount(1);

            foreach ($rows as $row) {
                $this->addRow($row);
            }
        } catch (AssertionFailedException $e) {
            $this->errors[] = $e->getMessage();
        }
    }

    public function addRow(array $row): void
    {
        $this->rows->push($row);
    }

    public function getRows(): \SplDoublyLinkedList
    {
        return $this->rows;
    }

    public function isValid(): bool
    {
        return count($this->errors) === 0;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
