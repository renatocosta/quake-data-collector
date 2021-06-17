<?php

namespace Domains\Context\LogHandler\Domain\Model\HumanLogFile;

use Domains\CrossCutting\Domain\Model\Common\Validatable;

interface HumanLogFile extends Validatable
{

    /**
     * @param HumanLogFileRow[] $rows
     */
    public function create(array $rows): void;

    public function getTotalKills(): int;

    public function setErrorInRowsFound(bool $errorState): void;

    public function errorInRowsFound(): bool;

    /**
     * @return HumanLogFileRow[]
     */
    public function getRows(): array;

}
