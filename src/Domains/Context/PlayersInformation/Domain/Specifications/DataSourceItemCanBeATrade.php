<?php

namespace Domains\Context\PlayersInformation\Domain\Specifications;

use Domains\CrossCutting\Model\Specification\CompositeSpecification;
use Domains\Context\Trade\Entities\PurchaseOrder;

final class DataSourceItemCanBeATrade extends CompositeSpecification
{

    private PurchaseOrder $purchaseOrderModel;

    private $filter;

    public $tradeId;

    private const TOTAL_PO_NUMBER_ALLOWED_IN_PROPRLI = 1;

    public bool $isDuplicatePoNumber;

    public function __construct(PurchaseOrder $purchaseOrderModel)
    {
        $this->purchaseOrderModel = $purchaseOrderModel;
    }

    /**
     * @param array $filter
     * @return bool
     */
    public function isSatisfiedBy($filter): bool
    {

        $this->filter = $filter;
        $this->tradeId = '';
        $this->isDuplicatePoNumber = false;

        $resultQuery = $this->purchaseOrderModel
            ->whereHas('trade', function ($query) {
                $query->whereHas('project', function ($query) {
                    $query->whereHas('projectAssets', function ($query) {
                        $query->whereHas('asset', function ($query) {
                            $query->where('ref', $this->filter['asset_code']);
                        });
                    });
                });
            })
            ->where('po_number', $this->filter['po_number'])
            ->get();

        if ($resultQuery->count() > self::TOTAL_PO_NUMBER_ALLOWED_IN_PROPRLI) {
            $needsToBeATrade = false;
            $this->isDuplicatePoNumber = true;
            return $needsToBeATrade;
        }

        $needsToBeATrade = !empty($resultQuery[0]->trade_id);

        if ($needsToBeATrade) {
            $this->tradeId = $resultQuery[0]->trade_id;
        }

        return $needsToBeATrade;
    }
}
