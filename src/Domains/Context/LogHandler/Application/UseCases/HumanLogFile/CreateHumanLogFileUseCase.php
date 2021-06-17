<?php

namespace Domains\Context\LogHandler\Application\UseCases\HumanLogFile;

use Domains\Context\LogHandler\Domain\Model\HumanLogFile\HumanLogFile;
use Domains\Context\LogHandler\Domain\Model\HumanLogFile\HumanLogFileRow;
use Domains\Context\LogHandler\Domain\Services\RowMapped;

final class CreateHumanLogFileUseCase implements ICreateHumanLogFileUseCase
{

    private HumanLogFile $humanLogFile;

    private RowMapped $rowMapper;

    public function __construct(HumanLogFile $humanLogFile, RowMapped $rowMapper)
    {
        $this->humanLogFile = $humanLogFile;
        $this->rowMapper = $rowMapper;
    }

    public function execute(CreateHumanLogFileInput $input): void
    {

        $rows = [];

        for ($input->content->rewind(); $input->content->valid(); $input->content->next()) {

            $rowMapped = $this->rowMapper->map($input->content->current());
            if (count($rowMapped) > 0) {

                $humanLogFile = new HumanLogFileRow($rowMapped['who_killed'], $rowMapped['who_died'], $rowMapped['means']);

                if (!$humanLogFile->isValid()) {
                    $this->humanLogFile->setErrorInRowsFound(true);
                    $input->content->next();
                }

                $rows[] = $humanLogFile;
            }
        }

        $this->humanLogFile->create($rows);
    }
}
