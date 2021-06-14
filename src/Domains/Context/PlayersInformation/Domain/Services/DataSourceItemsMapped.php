<?php

namespace Domains\Context\PlayersInformation\Domain\Services;

interface DataSourceItemsMapped
{

    /**
     * @param array $item
     * @return array
     */
    public function map(array $item): array;

}