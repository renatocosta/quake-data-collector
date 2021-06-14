<?php

namespace Domains\Context\PlayersInformation\Application\Services\DataSource;

use Domains\Context\PlayersInformation\Domain\Services\StatusItemCriteria;
use Domains\Context\PlayersInformation\Domain\Model\DataSource\DataSourceItem\SourceItemStatusEnum;

class SuccessfullyImportedPreviously extends StatusItemCriteria
{

    public function matches(array $data)
    {

        if ($data['item_was_previously_added_in_repo']) {
            return $this->status(SourceItemStatusEnum::SUCCESSFULLY_IMPORTED_PREVIOUSLY);
        }

        return parent::next($data);
    }
}
