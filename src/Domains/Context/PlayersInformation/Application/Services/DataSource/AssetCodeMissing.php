<?php

namespace Domains\Context\PlayersInformation\Application\Services\DataSource;

use Domains\Context\PlayersInformation\Domain\Services\StatusItemCriteria;
use Domains\Context\PlayersInformation\Domain\Model\DataSource\DataSourceItem\SourceItemStatusEnum;

class AssetCodeMissing extends StatusItemCriteria
{

    public function matches(array $data)
    {
        if(empty($data['item']['asset_code'])){
            return $this->status(SourceItemStatusEnum::ASSET_CODE_MISSING);
        }    

        return parent::next($data);
    }    

}