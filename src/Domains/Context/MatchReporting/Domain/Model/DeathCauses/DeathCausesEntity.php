<?php

namespace Domains\Context\MatchReporting\Domain\Model\DeathCauses;

use Domains\CrossCutting\Domain\Application\Event\Bus\DomainEventBus;
use Domains\CrossCutting\Domain\Model\ValueObjects\AggregateRoot;

final class DeathCausesEntity extends AggregateRoot implements DeathCauses
{

    private array $errors = [];

    private array $causes = [];

    public function find(): void
    {
        arsort($this->causes);

        if (count($this->causes) > 0 && $this->isValid()) {
            $this->raise(new DeathCausesWereFound($this));
        } else {
            $this->raise(new DeathCausesFailed($this));
        }
    }

    public function computeCause(Matchable $match): void
    {

        if (!$match->isValid()) {
            $this->errors[] = $match->getErrors();
            return;
        }

        $means = $match->means();

        if (!$this->isMeansFound($means)) {
            $this->causes[$means] = 1;
            return;
        }

        $this->causes[$match->means()]++;
    }

    public function isMeansFound(string $means): bool
    {
        return isset($this->causes[$means]);
    }

    public function getCauses(): array
    {
        return $this->causes;
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
        return sprintf('Causes %s', json_encode($this->causes));
    }
}
