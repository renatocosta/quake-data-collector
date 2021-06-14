<?php

namespace Domains\Context\LogHandler\Application\Services\Spreadsheet;

use Domains\Context\LogHandler\Domain\Services\SpreadsheetExtracted;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class SpreadsheetExtractor implements SpreadsheetExtracted
{

    private Spreadsheet $reader;

    private array $rows;

    public function setup($file): void
    {
      //  $this->reader = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
    }

    public function find(): void
    {
        $this->rows = $this->reader->getActiveSheet()->toArray();

        foreach ($this->rows as $index => $row) {
            $rawRow = json_encode($row, JSON_FORCE_OBJECT);
            array_push($this->rows[$index], $rawRow);
        }
    }

    public function rows(): array
    {
        return $this->rows;
    }
}
