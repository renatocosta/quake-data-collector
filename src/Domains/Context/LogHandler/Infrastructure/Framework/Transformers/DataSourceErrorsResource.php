<?php

namespace Domains\Context\LogHandler\Infrastructure\Framework\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class DataSourceErrorsResource extends JsonResource
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
      '_type'               => 'DataSource',
      'errors' => $this->resource,
    ];
  }
}
