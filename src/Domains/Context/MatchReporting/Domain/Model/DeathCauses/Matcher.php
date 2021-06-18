<?php

namespace Domains\Context\MatchReporting\Domain\Model\DeathCauses;

use Assert\Assert;
use Assert\AssertionFailedException;
use Domains\CrossCutting\Domain\Model\ValueObjects\Identity\FindValueIn;

final class Matcher implements Matchable
{

    private array $errors = [];

    private string $means;

    public function __construct(string $means)
    {

        $this->means = $means;

        try {
            Assert::lazy()->that($this->means, DeathCauseInfo::MEANS)->notBlank()
                ->verifyNow();
            new FindValueIn($this->means, DeathCauseInfo::CAUSES);
        } catch (AssertionFailedException $e) {
            $this->errors[] = $e->getMessage();
        }
    }

    public function means(): string
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
        return sprintf('Means %s', $this->means);
    }
}
