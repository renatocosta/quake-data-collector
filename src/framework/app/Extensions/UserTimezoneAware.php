<?php

namespace App\Extensions;

use DateTimeInterface;
use Illuminate\Support\Carbon;

trait UserTimezoneAware
{
  public function getAttribute($key) {
    $value = parent::getAttribute($key);

    if ((isset($this->attributes[$key]) && in_array($key, $this->getDates()))) {
      $value = $value->toDateTimeLocalString();
    }

    return $value;
  }



  /**
   * @param $value
   * @return Carbon
   * @throws \Exception
   */
  protected function asDateTime($value): Carbon {
    $defaultTimezone = 'UTC';
    $timezone        = auth()->check() ? auth()->user()->timezone : config('app.timezone');

    if ($value instanceof Carbon) {
      return $value->timezone($defaultTimezone)->setTimezone($timezone);
    }

    if ($value instanceof DateTimeInterface) {
      return (new Carbon(
        $value->format('Y-m-d H:i:s.u'), $defaultTimezone
      ))->setTimezone($timezone);
    }

    if (is_numeric($value)) {
      return Carbon::createFromTimestamp($value)->timezone($defaultTimezone)->setTimezone($timezone);
    }

    if ($this->isStandardDateFormat($value)) {
      return Carbon::createFromFormat('Y-m-d', $value)->startOfDay()->timezone($defaultTimezone)->setTimezone($timezone);
    }

    return Carbon::createFromFormat(
      str_replace('.v', '.u', $this->getDateFormat()), $value
    )->timezone($defaultTimezone)->setTimezone($timezone);
  }
}
