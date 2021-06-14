<?php

namespace Domains\Context\PlayersInformation\Application\Services\DataSource;

use Domains\Context\PlayersInformation\Domain\Model\DataSource\DataSourceItem\SourceItemStatusEnum;
use Domains\Context\PlayersInformation\Domain\Services\StatusItemCriteria;

class PoNumberDuplicate extends StatusItemCriteria
{

    public function matches(array $data)
    {

        if ($data['is_duplicate_po_number']) {
            return $this->status(SourceItemStatusEnum::DUPLICATE_PO_IN_PROPRLI);
        }

        return parent::next($data);
    }
}
