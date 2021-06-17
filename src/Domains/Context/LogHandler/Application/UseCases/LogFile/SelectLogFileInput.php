<?php

namespace Domains\Context\LogHandler\Application\UseCases\LogFile;

use Domains\CrossCutting\Domain\Application\Services\Common\MessageHandler;

final class SelectLogFileInput
{

    public string $fileName;

    public function __construct(string $fileName)
    {
        $this->fileName = $fileName;
    }
}
