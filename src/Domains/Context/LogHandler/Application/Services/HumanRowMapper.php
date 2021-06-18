<?php

namespace Domains\Context\LogHandler\Application\Services;

use Domains\Context\LogHandler\Domain\Services\RowMapped;

class HumanRowMapper implements RowMapped
{

    private array $noResult = [];

    public function map(string $rawRow): array
    {

        $rowMapped = $this->noResult;

        //remove 'greater than' and 'less than' symbols of the <world>
        $replacements = ['<', '>'];
        $rawRow = str_replace($replacements, '', $rawRow);
        preg_match("/Kill: (\w+) (\w+) (\w+): (.+) killed (.+) by (.+)/", $rawRow, $matches);

        if (count($matches) > 0) {
            $rowMapped = ['who_killed' => $matches[4], 'who_died' => $matches[5], 'means_of_death' => $matches[6]];
        }

        return $rowMapped;
    }
}
