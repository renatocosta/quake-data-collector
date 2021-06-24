<?php

namespace Domains\Context\LogHandler\Domain\Model\LogFile;

use Assert\Assert;
use Assert\AssertionFailedException;
use Domains\Context\LogHandler\Domain\Model\LogFile\LogFile;
use Domains\CrossCutting\Domain\Application\Event\Bus\DomainEventBus;
use Domains\CrossCutting\Domain\Model\ValueObjects\AggregateRoot;
use Generator;

final class LogFileEntity extends AggregateRoot implements LogFile
{

    private \SplFileObject $file;

    private LogFileMetadata $metaData;

    private array $errors = [];

    public function extractOf(\SplFileObject $file, LogFileMetadata $metaData): void
    {

        $this->file = $file;
        $this->metaData = $metaData;

        try {
            $this->metaData->validation();
            $this->raise(new LogFileSelected($this));
        } catch (AssertionFailedException $e) {
            $this->errors[] = $e->getMessage();
            $this->raise(new LogFileRejected($this));
        }
    }

    public function getContent(): Generator
    {
        while (!$this->file->eof()) {
            yield $this->file->fgets();
        }
    }

    public function getMetadata(): LogFileMetadata
    {
        return $this->metaData;
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
       return sprintf('File %s Metadata %s', json_encode($this->file), json_encode($this->metaData));
    }
}
