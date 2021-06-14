<?php

namespace Domains\Context\LogHandler\Infrastructure\Framework\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class DataSourceResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param \Illuminate\Http\Request
   * @return array
   */
  public function toArray($request)
  {
    $datasource = $this[0];
    return [
      '_type'               => 'DataSource',
      'id'                  => $datasource->invoice_data_source_id,
      'counting'            => $datasource->counting,
      'items' => $this->when(isset($datasource->items), function () use($datasource) {
        return DataSourceItemsResource::collection($datasource->items);
      })
    ];
  }
}
