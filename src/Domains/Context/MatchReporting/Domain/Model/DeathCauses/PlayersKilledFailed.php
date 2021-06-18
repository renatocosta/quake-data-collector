<?php

namespace Domains\Context\MatchReporting\Domain\Model\PlayersKilled;

use Domains\Context\MatchReporting\Domain\Model\PlayersKilled\PlayersKilled;
use Domains\CrossCutting\Domain\Application\Event\AbstractEvent;

class PlayersKilledFailed extends AbstractEvent
{

    public PlayersKilled $playersKilled;

    public function __construct(PlayersKilled $playersKilled)
    {
        parent::__construct();
        $this->playersKilled = $playersKilled;
    }
}
