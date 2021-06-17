<?php

namespace Domains\Context\LogHandler\Domain\Model\HumanLogFile;

use Domains\CrossCutting\Domain\Application\Event\AbstractEvent;

class HumanLogFileRejected extends AbstractEvent
{

    public HumanLogFile $humanLogFile;

    public function __construct(HumanLogFile $humanLogFile)
    {
        parent::__construct();
        $this->humanLogFile = $humanLogFile;
    }
}
