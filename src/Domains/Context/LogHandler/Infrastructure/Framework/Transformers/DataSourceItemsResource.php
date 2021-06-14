<?php

namespace Domains\Context\LogHandler\Infrastructure\Framework\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class DataSourceItemsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            '_type'                 => 'DataSourceItems',
            'id'                    => $this->id,
            'asset_code'            => $this->asset_code,
            'po_number'             => $this->po_number,
            'amount'                => $this->amount,
            'external_invoice_id'   => $this->external_invoice_id,
            'status'                => $this->status,
            'raw_data'      => $this->raw_data,
            'invoice_id'    => $this->invoice_id
        ];
    }
}
