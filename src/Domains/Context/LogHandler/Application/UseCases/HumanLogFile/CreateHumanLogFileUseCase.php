<?php

namespace Domains\Context\LogHandler\Application\UseCases\HumanLogFile;

use Domains\Context\LogHandler\Domain\Model\HumanLogFile\HumanLogFile;
use Illuminate\Support\Facades\Log;

final class CreateHumanLogFileUseCase implements ICreateHumanLogFileUseCase
{

    private HumanLogFile $humanLogFile;

    public function __construct(HumanLogFile $humanLogFile)
    {
        $this->humanLogFile = $humanLogFile;
    }

    public function execute(CreateHumanLogFileInput $input): void
    {

        $this->humanLogFile->create($input->content);

        for ($input->content->rewind(); $input->content->valid(); $input->content->next()) {
            // echo ($input->content->current());exit;
        }
    }
}
