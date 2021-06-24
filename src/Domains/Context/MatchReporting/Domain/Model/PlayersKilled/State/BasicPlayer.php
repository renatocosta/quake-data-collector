<?php

namespace Domains\Context\MatchReporting\Domain\Model\PlayersKilled\State;

class BasicPlayer implements Player
{
    private PlayerState $killedPlayer;

    private PlayerState $deadPlayer;

    private int $startKill = 0;

    private array $players = [];

    public function __construct(PlayerState $killedPlayer, PlayerState $deadPlayer)
    {
        $this->killedPlayer = $killedPlayer;
        $this->deadPlayer = $deadPlayer;
    }

    public function killUp(array $player): void
    {
        if (!isset($this->players[$player['who_killed']]['kills'])) {
            $this->players[$player['who_killed']]['kills'] = $this->startKill;
        }

        $this->killedPlayer->computeKills($this->players[$player['who_killed']]['kills']);
        $this->players[$player['who_killed']] = ['who_killed' => $player['who_killed'], 'who_died' => $player['who_died'], 'kills' => $this->killedPlayer->getKills()];
    }

    public function killDown(array $player): void
    {
        if (!isset($this->players[$player['who_died']]['kills'])) {
            $this->players[$player['who_died']]['kills'] = $this->startKill;
        }

        $this->deadPlayer->computeKills($this->players[$player['who_died']]['kills']);
        $this->players[$player['who_died']] = ['who_killed' => $player['who_died'], 'who_died' => $player['who_died'], 'kills' => $this->deadPlayer->getKills()];
    }

    public function getPlayers(): array
    {
        return $this->players;
    }
}
