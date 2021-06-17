<?php

namespace Domains\Context\LogHandler\Domain\Services;

interface RowMapped
{

    /**
     * @param strign $rawRow
     * @return array
     */
    public function map(string $rawRow): array;
}
