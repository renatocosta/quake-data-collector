<?php

namespace Domains\Context\LogHandler\Domain\Model\SpreadsheetSource;

use Domains\CrossCutting\Domain\Application\Event\AbstractEvent;

class SpreadsheetWasExtracted extends AbstractEvent
{

    public ISpreadsheet $data;

    public function __construct(ISpreadsheet $data)
    {
        parent::__construct();
        $this->data = $data;
    }

}