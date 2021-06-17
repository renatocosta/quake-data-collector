<?php

namespace Domains\Context\LogHandler\Domain\Model\HumanLogFile;

use Assert\Assert;
use Assert\AssertionFailedException;
use Domains\Context\LogHandler\Domain\Model\HumanLogFile\HumanLogFileInfo;
use Domains\CrossCutting\Domain\Model\ValueObjects\AggregateRoot;

final class HumanLogFileRow extends AggregateRoot implements HumanLogFileRowable
{

    private array $errors = [];

    private string $whoKilled;

    private string $whoDied;

    private string $means;

    public function __construct(string $whoKilled, string $whoDied, string $means)
    {

        $this->whoKilled = $whoKilled;
        $this->whoDied = $whoDied;
        $this->means = $means;

        try {
            Assert::lazy()->that($this->whoKilled, HumanLogFileInfo::WHO_KILLED_COLUMN)->notBlank()
                ->that($this->whoDied, HumanLogFileInfo::WHO_DIED_COLUMN)->notBlank()
                ->that($this->means, HumanLogFileInfo::MEANS_COLUMN)->notBlank()
                ->verifyNow();
        } catch (AssertionFailedException $e) {
            $this->errors[] = $e->getMessage();
        }
    }

    public function getWhoKilled(): string
    {
        return $this->whoKilled;
    }

    public function getPlayerWhoDied(): string
    {
        return $this->whoDied;
    }

    public function getMeanOfDeath(): string
    {
        return $this->means;
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
        return sprintf('Who killed %s Who died %s means %s', $this->whoKilled, $this->whoDied, $this->means);
    }
}
