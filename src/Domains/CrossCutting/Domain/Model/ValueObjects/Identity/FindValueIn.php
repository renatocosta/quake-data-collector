<?php

namespace Domains\CrossCutting\Domain\Model\ValueObjects\Identity;

use Assert\Assertion;

class FindValueIn
{

    private $value;

    public function __construct(string $value, array $inList)
    {
        Assertion::inArray($value, $inList);
        $this->value = $value;

    }

    /**
     * @return mixed
     */
    public function value()
    {
        return $this->value;
    }

}