<?php

namespace Domains\Context\PlayersInformation\Application\Services\DataSource;

use Domains\Context\PlayersInformation\Domain\Services\StatusItemCriteria;
use Domains\Context\PlayersInformation\Domain\Model\DataSource\DataSourceItem\SourceItemStatusEnum;
use Domains\CrossCutting\Domain\Model\ValueObjects\Money\Currency;

class InvoiceValueChanged extends StatusItemCriteria
{

    public function matches(array $data)
    {

        $item = $data['item'];

        $isAmountEqualThanPreviously = $this->isAmountEqualThanPreviously(new Currency($item['amount']), $data['item_previously_added']['amount']);

        if ($data['item_was_previously_added'] && !$isAmountEqualThanPreviously) {
            return $this->status(SourceItemStatusEnum::INVOICE_VALUE_CHANGED);
        }

        return parent::next($data);
    }

    private function isAmountEqualThanPreviously(Currency $amount, Currency $previouslyAmount): bool
    {
        return $amount == $previouslyAmount;
    }
}
