<?php

namespace Domains\Context\MatchReporting\Domain\Model\PlayersKilled;

use Domains\Context\MatchReporting\Domain\Model\PlayersKilled\State\Player;
use Domains\CrossCutting\Domain\Application\Event\Bus\DomainEventBus;
use Domains\CrossCutting\Domain\Model\ValueObjects\AggregateRoot;

final class PlayersKilledEntity extends AggregateRoot implements PlayersKilled
{

    private array $errors = [];

    private int $totalKills = 0;

    private Player $player;

    private array $players = [];

    public function __construct(DomainEventBus $domainEventBus, Player $player)
    {
        parent::__construct($domainEventBus);
        $this->player = $player;
    }

    public function find(): void
    {

        if (count($this->player->getPlayers()) > 0 && $this->isValid()) {
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

        $matchData = ['who_killed' => $match->getPlayerWhoKilled(), 'who_died' => $match->getPlayerWhoDied()];

        if (!$this->isEligibleToBeAPlayer($matchData['who_killed'])) {
            $this->player->killDown($matchData);
        }

        $this->player->killUp($matchData);
    }

    public function consolidate(): void
    {
        $this->players = $this->player->getPlayers();
        $this->totalKills = array_sum(array_column($this->players, 'kills'));
        if (array_key_exists('world', $this->players)) {
            unset($this->players['world']);
        }
        array_multisort(array_column($this->players, 'kills'), SORT_DESC, $this->players);
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
