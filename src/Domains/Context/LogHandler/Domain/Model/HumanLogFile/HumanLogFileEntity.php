<?php

namespace Domains\Context\LogHandler\Domain\Model\HumanLogFile;

use Assert\Assert;
use Assert\AssertionFailedException;
use Domains\CrossCutting\Domain\Application\Event\Bus\DomainEventBus;
use Domains\CrossCutting\Domain\Model\ValueObjects\AggregateRoot;

final class HumanLogFileEntity extends AggregateRoot implements HumanLogFile
{

    private array $errors = [];

    private int $totalKills;

    private array $rows = [];

    private bool $errorInRows = false;

    public function addRow(HumanLogFileRow $row): void
    {
        $row->validation();
        if (!$row->isValid()) $this->errorInRows = true;
        $this->rows[] = $row;
    }

    public function create(): void
    {

        try {
            Assert::lazy()
                ->that($this->rows, HumanLogFileInfo::ROWS_KEY)->minCount(1)
                ->that($this->errorInRows, HumanLogFileInfo::SOMETHING_WENT_WRONG_WHILE_READING_ROWS_MESSAGE)->false()
                ->verifyNow();
            $this->totalKills = count($this->rows);
            $this->raise(new HumanLogFileCreated($this));
        } catch (AssertionFailedException $e) {
            $this->errors[] = $e->getMessage();
            $this->raise(new HumanLogFileRejected($this));
        }
    }

    public function getTotalKills(): int
    {
        return $this->totalKills;
    }

    public function getRows(): array
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

    public function __toString(): string
    {
        return sprintf('Total rows %s Rows %s', $this->totalKills, json_encode($this->rows));
    }
}
