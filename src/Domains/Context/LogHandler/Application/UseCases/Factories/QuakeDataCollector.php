<?php

namespace Domains\Context\LogHandler\Application\UseCases\Factories;

use Domains\Context\LogHandler\Application\UseCases\LogFile\SelectLogFileInput;

final class QuakeDataCollector extends QuakeDataCollectorFactory
{

    protected function build(): void
    {
        $this->addLogFile();
        $this->addHumanLogFile();
        $this->addHumanLogFileUseCase();
        $this->addLogFileUseCase();
    }

    public function dispatch(): void
    {
        $this->selectLogFileUseCase->execute(new SelectLogFileInput($this->fileName));
    }
}
