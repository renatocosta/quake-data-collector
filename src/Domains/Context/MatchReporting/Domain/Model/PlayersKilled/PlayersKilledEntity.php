<?php

namespace Domains\Context\MatchReporting\Domain\Model\PlayersKilled;

use Domains\CrossCutting\Domain\Application\Event\Bus\DomainEventBus;
use Domains\CrossCutting\Domain\Model\ValueObjects\AggregateRoot;

final class PlayersKilledEntity extends AggregateRoot implements PlayersKilled
{

    private array $errors = [];

    private int $totalKills = 0;

    private array $matches = [];

    private array $players = [];

    private int $defaultTotalKillForMatch = 1;

    public function __construct(DomainEventBus $domainEventBus)
    {
        parent::__construct($domainEventBus);
    }

    public function find(): void
    {

        $this->computeKillsForOnlyAcceptedPlayers();
        $this->consolidate();

        if (count($this->players) > 0 && $this->isValid()) {
            $this->raise(new PlayersKilledWereFound($this));
        } else {
            $this->raise(new PlayersKilledFailed($this));
        }
    }

    public function computeKills(Matchable $match): void
    {

        if (!$match->isValid()) {
            $this->errors[] = $match->getErrors();
            return;
        }

        $killer = $match->getPlayerWhoKilled();

        $defaultMatch = ['who_killed' => $killer, 'who_died' => $match->getPlayerWhoDied(), 'kills' => $this->defaultTotalKillForMatch];
        $this->matches[] = $defaultMatch;

        if (!$this->isKillerFound($killer)) {
            $this->players[$killer] = $defaultMatch;
            return;
        }

        $this->players[$killer]['kills']++;
    }

    public function computeKillsForOnlyAcceptedPlayers(): void
    {
        foreach ($this->matches as $match) {

            if (!$this->isEligibleToBeAPlayer($match['who_killed'])) {
                if ($this->isKillerFound($match['who_died'])) {
                    $this->players[$match['who_died']]['kills']--;
                }
            }
        }
    }

    public function consolidate(): void
    {
        $this->totalKills = array_sum(array_column($this->players, 'kills'));
        if (array_key_exists('world', $this->players)) {
            unset($this->players['world']);
        }
        $this->players = array_map(function ($player) {
            return $player['kills'];
        }, $this->players);
    }

    public function isKillerFound(string $killer): bool
    {
        return isset($this->players[$killer]);
    }

    public function isEligibleToBeAPlayer(string $killer): bool
    {
        return $killer != PlayerInfo::WORLD_KILLER;
    }

    public function getTotalKills(): int
    {
        return $this->totalKills;
    }

    public function getPlayers(): array
    {
        return $this->players;
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
        return sprintf('Total kills %s Players %s', $this->totalKills, json_encode($this->players));
    }
}
