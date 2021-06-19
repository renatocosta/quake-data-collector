<?php

namespace Domains\Context\LogHandler\Domain\Model\HumanLogFile;

use Domains\CrossCutting\Domain\Model\Common\Validatable;

interface HumanLogFile extends Validatable
{

    /**
     * @param HumanLogFileRow $row
     */
    public function addRow(HumanLogFileRow $row): void;

    public function create(): void;

    public function getTotalKills(): int;

    /**
     * @return HumanLogFileRow[]
     */
    public function getRows(): array;
}
