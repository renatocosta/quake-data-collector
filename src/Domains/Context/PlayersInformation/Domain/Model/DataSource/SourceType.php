<?php

namespace Domains\Context\PlayersInformation\Domain\Model\DataSource;

use Domains\CrossCutting\Domain\Model\ValueObjects\Identity\FindValueIn;

class SourceType
{

    private string $value;

    public function __construct(string $value)
    {
        $findValueIn = new FindValueIn($value, SourceTypeEnum::TYPES);
        $this->value = $findValueIn->value();
    }

    public function __toString(): string
    {
        return $this->value;
    }

}