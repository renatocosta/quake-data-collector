<?php

namespace Domains\Context\LogHandler\Application\UseCases\LogFile;

interface ISelectLogFileUseCase
{

    public function execute(SelectLogFileInput $input): void;
}
