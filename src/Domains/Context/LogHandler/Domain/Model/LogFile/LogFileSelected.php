<?php

namespace Domains\Context\LogHandler\Domain\Model\LogFile;

use Domains\CrossCutting\Domain\Application\Event\AbstractEvent;

class LogFileSelected extends AbstractEvent
{

    public LogFile $logFile;

    public function __construct(LogFile $logFile)
    {
        parent::__construct();
        $this->logFile = $logFile;
    }
}
