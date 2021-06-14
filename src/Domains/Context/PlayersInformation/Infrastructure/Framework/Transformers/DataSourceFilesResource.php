<?php

namespace Domains\Context\PlayersInformation\Infrastructure\Framework\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class DataSourceFilesResource extends JsonResource
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
      '_type'               => 'DataSourceReportingFiles',
      'id'                  => $this->id,
      'type'            => $this->type,
      'remote_file_id' => $this->remote_file_id,
      'created_at' => $this->created_at,
      'file' => [
        'id' => $this->remoteFile->id,
        'name' => $this->remoteFile->name,
        'size' => $this->remoteFile->size,
        'mime_type' => $this->remoteFile->mime_type
      ],
      'summarized_item' => isset($this->items[0]) ? $this->items[0] : null,
    ];
  }
}
