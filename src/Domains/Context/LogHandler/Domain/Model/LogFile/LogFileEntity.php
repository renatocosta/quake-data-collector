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

    public function __construct(DomainEventBus $domainEventBus)
    {
        parent::__construct($domainEventBus);
    }

    public function extractOf(\SplFileObject $file, LogFileMetadata $metaData): void
    {

        $this->file = $file;
        $this->metaData = $metaData;

        try {
            Assert::that($file, LogFileInfo::NO_CONTENT_FILE_MESSAGE)->notBlank();
            $this->raise(new LogFileSelected($this));
        } catch (AssertionFailedException $e) {
            $this->errors[] = $e->getMessage();
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
}
