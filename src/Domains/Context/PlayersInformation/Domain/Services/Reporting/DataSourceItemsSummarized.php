<?php

namespace Domains\Context\PlayersInformation\Domain\Services\Reporting;

interface DataSourceItemsSummarized
{

    public function filter($items): array;

}