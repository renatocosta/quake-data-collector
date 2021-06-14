<?php

namespace Domains\Context\PlayersInformation\Application\Services\DataSource;

use Domains\Context\PlayersInformation\Domain\Services\StatusItemCriteria;
use Domains\Context\PlayersInformation\Domain\Model\DataSource\DataSourceItem\SourceItemStatusEnum;

class ExternalInvoiceIdMissing extends StatusItemCriteria
{

    public function matches(array $data)
    {

        if(empty($data['item']['external_invoice_id'])){
            return $this->status(SourceItemStatusEnum::EXTERNAL_INVOICE_ID_MISSING);
        }    
        
        return parent::next($data);
    }   

}