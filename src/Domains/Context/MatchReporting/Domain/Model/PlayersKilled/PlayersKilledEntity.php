<?php

namespace Domains\Context\MatchReporting\Domain\Model\PlayersKilled;

use Domains\Context\MatchReporting\Domain\Model\PlayersKilled\State\Player;
use Domains\CrossCutting\Domain\Application\Event\Bus\DomainEventBus;
use Domains\CrossCutting\Domain\Model\ValueObjects\AggregateRoot;

final class PlayersKilledEntity extends AggregateRoot implements PlayersKilled
{

    private array $errors = [];

    private int $totalKills = 0;

    private Player $playerState;

    private array $players = [];

    public function __construct(DomainEventBus $domainEventBus, Player $playerState)
    {
        parent::__construct($domainEventBus);
        $this->playerState = $playerState;
    }

    public function find(): void
    {

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

        if (!$this->isEligibleToBeAPlayer($match)) {
            $this->playerState->killDown($match);
        }

        $this->playerState->killUp($match);
    }

    public function consolidate(): void
    {
        $this->players = $this->playerState->getPlayers();
        $this->totalKills = array_sum(array_column($this->players, 'kills'));
        if (array_key_exists('world', $this->players)) {
            unset($this->players['world']);
        }
        array_multisort(array_column($this->players, 'kills'), SORT_DESC, $this->players);
    }

    public function isEligibleToBeAPlayer(Matchable $match): bool
    {
        return $match->getPlayerWhoKilled() != PlayerInfo::WORLD_KILLER;
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
