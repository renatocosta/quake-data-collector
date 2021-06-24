<?php

namespace Domains\Context\MatchReporting\Domain\Model\PlayersKilled\State;

use Domains\Context\MatchReporting\Domain\Model\PlayersKilled\Matchable;

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

    public function killUp(Matchable $match): void
    {
        if (!isset($this->players[$match->getPlayerWhoKilled()]['kills'])) {
            $this->players[$match->getPlayerWhoKilled()]['kills'] = $this->startKill;
        }

        $this->killedPlayer->computeKills($this->players[$match->getPlayerWhoKilled()]['kills']);
        $this->players[$match->getPlayerWhoKilled()] = ['who_killed' => $match->getPlayerWhoKilled(), 'who_died' => $match->getPlayerWhoDied(), 'kills' => $this->killedPlayer->getKills()];
    }

    public function killDown(Matchable $match): void
    {
        if (!isset($this->players[$match->getPlayerWhoDied()]['kills'])) {
            $this->players[$match->getPlayerWhoDied()]['kills'] = $this->startKill;
        }

        $this->deadPlayer->computeKills($this->players[$match->getPlayerWhoDied()]['kills']);
        $this->players[$match->getPlayerWhoDied()] = ['who_killed' => $match->getPlayerWhoDied(), 'who_died' => '', 'kills' => $this->deadPlayer->getKills()];
    }

    public function getPlayers(): array
    {
        return $this->players;
    }
}
