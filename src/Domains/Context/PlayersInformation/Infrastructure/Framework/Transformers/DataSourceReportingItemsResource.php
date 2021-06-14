<?php

namespace Domains\Context\PlayersInformation\Infrastructure\Framework\Transformers;

use App\Config\Frontend;
use Illuminate\Http\Resources\Json\JsonResource;

class DataSourceReportingItemsResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param \Illuminate\Http\Request
   * @return array
   */
  public function toArray($request)
  {

    return [
      '_type' => 'DataSourceReportingItems',
      'id' => $this->resource['id'],
      'asset_code' => $this->resource['asset_code'],
      'po_number' => $this->resource['po_number'],
      'amount' => $this->resource['amount'],
      'external_invoice_id' => $this->resource['external_invoice_id'],
      'status' => $this->resource['status'],
      'invoice_data_source_id' => $this->resource['invoice_data_source_id'],
      'raw_data' => $this->resource['raw_data'],
      'invoice_id' => $this->resource['invoice_id'],
      'invoiced' => !is_null($this->resource['invoice_id']),
      'invoiced_url' => $this->when(!is_null($this->resource['invoice_id']), function () {
        return resolveSpaUrl(Frontend::TRADE_CONTRACT_INVOICE_VIEW_URL, ['project' => $this->resource['trade_invoice']['trade_']['project_id'], 'trade' => $this->resource['trade_invoice']['trade_']['id'], 'invoice' => $this->resource['invoice_id']]);
      })
    ];
  }
}
