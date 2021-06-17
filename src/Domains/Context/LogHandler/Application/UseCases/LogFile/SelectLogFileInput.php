<?php

namespace Domains\Context\LogHandler\Application\UseCases\LogFile;

use Domains\CrossCutting\Domain\Application\Services\Common\MessageHandler;

final class SelectLogFileInput
{

    public string $fileName;

    public MessageHandler $modelState;

    public function __construct(MessageHandler $messageHandler, string $fileName)
    {
        $this->modelState = $messageHandler;
        $this->fileName = $fileName;
    }
}
