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

    public function __construct(DomainEventBus $domainEventBus)
    {
        parent::__construct($domainEventBus);
    }

    public function create(array $rows): void
    {
        $this->rows = $rows;

        try {
            Assert::lazy()
                ->that($rows, HumanLogFileInfo::ROWS_KEY)->minCount(1)
                ->that($this->errorInRows, HumanLogFileInfo::SOMETHING_WENT_WRONG_WHILE_READING_ROWS_MESSAGE)->false()
                ->verifyNow();
            $this->totalKills = count($rows);
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

    public function setErrorInRowsFound(bool $errorState): void
    {
        $this->errorInRows = $errorState;
    }

    public function errorInRowsFound(): bool
    {
        return $this->errorInRows;
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
