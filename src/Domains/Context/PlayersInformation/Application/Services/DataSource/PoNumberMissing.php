<?php

namespace Domains\Context\PlayersInformation\Application\Services\DataSource;

use Assert\Assert;
use Assert\AssertionFailedException;
use Domains\Context\PlayersInformation\Domain\Services\StatusItemCriteria;
use Domains\Context\PlayersInformation\Domain\Model\DataSource\DataSourceItem\SourceItemStatusEnum;

class PoNumberMissing extends StatusItemCriteria
{

    public function matches(array $data)
    {

        try {
            Assert::that(strtoupper($data['item']['po_number']))
                ->notBlank()
                ->notNull()
                ->notSame('NULL');
        } catch (AssertionFailedException $e) {
            return $this->status(SourceItemStatusEnum::PO_MISSING);
        }

        return parent::next($data);
    }
}
