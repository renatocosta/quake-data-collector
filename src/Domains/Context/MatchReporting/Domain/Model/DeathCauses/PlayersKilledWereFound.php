<?php

namespace Domains\Context\MatchReporting\Domain\Model\PlayersKilled;

use Domains\CrossCutting\Domain\Application\Event\AbstractEvent;

class PlayersKilledWereFound extends AbstractEvent
{

    public PlayersKilled $playersKilled;

    public function __construct(PlayersKilled $playersKilled)
    {
        parent::__construct();
        $this->playersKilled = $playersKilled;
    }
}
