<?php

namespace Domains\Context\LogHandler\Application\UseCases\HumanLogFile;

interface ICreateHumanLogFileUseCase
{

    public function execute(CreateHumanLogFileInput $input): void;

}