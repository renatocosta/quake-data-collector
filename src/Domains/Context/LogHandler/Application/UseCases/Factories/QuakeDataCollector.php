<?php

namespace Domains\Context\LogHandler\Application\UseCases\Factories;

final class QuakeDataCollector extends QuakeDataCollectorFactory
{

    public function dispatch(): void
    {
        $this->addHumanLogFile();
        $this->addHumanLogFileUseCase();
        $this->addLogFile();
        $this->addLogFileUseCase();
    }
}
