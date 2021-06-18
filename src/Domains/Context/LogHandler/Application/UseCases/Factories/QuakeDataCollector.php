<?php

namespace Domains\Context\LogHandler\Application\UseCases\Factories;

use Domains\Context\LogHandler\Application\UseCases\LogFile\SelectLogFileInput;

final class QuakeDataCollector extends QuakeDataCollectorFactory
{

    protected function build(): void
    {
        $this->addLogFile();
        $this->addHumanLogFile();
        $this->addPlayersKilled();
        $this->addDeathCauses();
        
        $this->addHumanLogFileUseCase();
        $this->addLogFileUseCase();
        $this->addPlayersKilledUseCase();
        $this->addDeathCausesUseCase();
    }

    public function dispatch(): void
    {
        $this->selectLogFileUseCase->execute(new SelectLogFileInput($this->fileName));
    }
}
