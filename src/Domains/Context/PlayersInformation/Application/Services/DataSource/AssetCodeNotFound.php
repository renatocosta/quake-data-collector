<?php

namespace Domains\Context\PlayersInformation\Application\Services\DataSource;

use Domains\Context\PlayersInformation\Domain\Services\StatusItemCriteria;
use Domains\Context\PlayersInformation\Domain\Model\DataSource\DataSourceItem\SourceItemStatusEnum;
use Domains\Context\Asset\Entities\Asset;

class AssetCodeNotFound extends StatusItemCriteria
{

    private Asset $assetModel;

    public function __construct(Asset $assetModel)
    {
        $this->assetModel = $assetModel;
    }

    public function matches(array $data)
    {

        $result = $this->assetModel->where('ref', $data['item']['asset_code'])->first();

        if (is_null($result)) {
            return $this->status(SourceItemStatusEnum::ASSET_CODE_NOT_FOUND);
        }

        return parent::next($data);
    }
}
