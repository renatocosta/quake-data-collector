<?php

namespace Domains\Context\MatchReporting\Domain\Model\PlayersKilled;

use Assert\Assert;
use Assert\AssertionFailedException;

final class Matcher implements Matchable
{

    private array $errors = [];

    private string $whoKilled;

    private string $whoDied;

    public function __construct(string $whoKilled, string $whoDied)
    {

        $this->whoKilled = $whoKilled;
        $this->whoDied = $whoDied;

        try {
            Assert::lazy()->that($this->whoKilled, PlayerInfo::WHO_KILLED_COLUMN)->notBlank()
                ->that($this->whoDied, PlayerInfo::WHO_DIED_COLUMN)->notBlank()
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
        return sprintf('Who killed %s Who died %s', $this->whoKilled, $this->whoDied);
    }
}
