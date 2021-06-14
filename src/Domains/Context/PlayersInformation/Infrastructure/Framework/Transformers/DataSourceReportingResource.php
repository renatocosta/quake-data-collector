<?php

namespace Domains\Context\PlayersInformation\Infrastructure\Framework\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class DataSourceReportingResource extends JsonResource
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
      '_type'               => 'DataSourceReporting',
      'id'                  => $this->resource['invoice_data_source_id'],
      'counting'            => $this->resource['counting'],
      'file' => ['name'     => $this->resource['remote_file']['name']],
      'items' => DataSourceReportingItemsResource::collection($this->resource['items'])
    ];
  }
}
