<?php

namespace Domains\Context\LogHandler\Domain\Model\LogFile;

use Assert\Assert;

final class LogFileMetadata
{

    public int $size;

    public string $extension;

    public function __construct(int $size, string $extension = LogFileInfo::DEFAULT_FILE_EXTENSION)
    {
        $this->size = $size;
        $this->extension = $extension;
    }

    public function validation()
    {
        Assert::that($this->size, sprintf(LogFileInfo::MIN_SIZE_REQUIRED_MESSAGE, LogFileInfo::MIN_SIZE_REQUIRED))->greaterOrEqualThan(LogFileInfo::MIN_SIZE_REQUIRED);
        Assert::that($this->extension, sprintf(LogFileInfo::MISSING_FILE_EXTENSION_MESSAGE, LogFileInfo::DEFAULT_FILE_EXTENSION))->eq(LogFileInfo::DEFAULT_FILE_EXTENSION);
    }

    public function __toString(): string
    {
        return sprintf('Size %s Extension %s', $this->size, $this->extension);
    }
}
