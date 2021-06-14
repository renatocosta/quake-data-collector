<?php

namespace App\Extensions;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
  use UserTimezoneAware;


  public function getRawAttribute($key) {
    return $this->getAttributeFromArray($key);
  }

}
