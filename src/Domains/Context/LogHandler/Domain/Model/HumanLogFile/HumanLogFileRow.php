<?php

namespace Domains\Context\LogHandler\Domain\Model\HumanLogFile;

use Assert\Assert;
use Assert\AssertionFailedException;
use Domains\Context\LogHandler\Domain\Model\HumanLogFile\HumanLogFileInfo;

final class HumanLogFileRow implements HumanLogFileRowable
{

    private array $errors = [];

    private string $whoKilled;

    private string $whoDied;

    private string $meansOfDeath;

    public function __construct(string $whoKilled, string $whoDied, string $meansOfDeath)
    {

        $this->whoKilled = $whoKilled;
        $this->whoDied = $whoDied;
        $this->meansOfDeath = $meansOfDeath;

        try {
            Assert::lazy()->that($this->whoKilled, HumanLogFileInfo::WHO_KILLED_COLUMN)->notBlank()
                ->that($this->whoDied, HumanLogFileInfo::WHO_DIED_COLUMN)->notBlank()
                ->that($this->meansOfDeath, HumanLogFileInfo::MEANS_OF_DEATH_COLUMN)->notBlank()
                ->verifyNow();
        } catch (AssertionFailedException $e) {
            $this->errors[] = $e->getMessage();
        }
    }

    public function getPlayerWhoKilled(): string
    {
        return $this->whoKilled;
    }

    public function getPlayerWhoDied(): string
    {
        return $this->whoDied;
    }

    public function getMeanOfDeath(): string
    {
        return $this->meansOfDeath;
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
        return sprintf('Who killed %s Who died %s means of death %s', $this->whoKilled, $this->whoDied, $this->meansOfDeath);
    }
}
