<?php

namespace Domains\Context\PlayersInformation\Domain\Model\DataSource\DataSourceItem;

use Domains\CrossCutting\Model\ValueObjects\Identity\FindValueIn;

final class SourceItemContract
{

    private array $values;

    public function __construct(string $value)
    {
        $findValueIn = new FindValueIn($value, SourceItemContractEnum::CONTRACTS);
        $this->value = $findValueIn->value();
        $this->values = SourceItemContractEnum::types[$this->value];
    }

    public function values(): array
    {
        return $this->values;
    }

}