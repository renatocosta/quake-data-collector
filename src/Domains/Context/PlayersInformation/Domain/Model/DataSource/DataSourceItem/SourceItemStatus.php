<?php

namespace Domains\Context\PlayersInformation\Domain\Model\DataSource\DataSourceItem;

use Domains\CrossCutting\Domain\Model\ValueObjects\Identity\FindValueIn;

class SourceItemStatus
{

    public string $value;

    public function __construct(string $value)
    {
        $findValueIn = new FindValueIn($value, SourceItemStatusEnum::STATUS);
        $this->value = $findValueIn->value();
    }

    public function __toString(): string
    {
        return $this->value;
    }

}