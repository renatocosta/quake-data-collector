<?php

namespace Domains\Context\LogHandler\Application\UseCases\HumanLogFile;

use Generator;

final class CreateHumanLogFileInput
{

    public Generator $content;

    public array $metadata;

    public function __construct(Generator $content, array $metadata = [])
    {
        $this->content = $content;
        $this->metadata = $metadata;
    }
}
