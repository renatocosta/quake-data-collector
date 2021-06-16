<?php

namespace Domains\Context\LogHandler\Application\UseCases\HumanLogFile;

use Domains\CrossCutting\Domain\Application\Services\Common\MessageHandler;
use Generator;

final class CreateHumanLogFileInput
{

    public Generator $content;

    public array $metadata;

    public MessageHandler $modelState;

    public function __construct(Generator $content, array $metadata = [], MessageHandler $messageHandler)
    {
        $this->modelState = $messageHandler;
        $this->content = $content;
        $this->metadata = $metadata;
    }
}
