<?php

if( ! function_exists('_t') ) {

  function _t(string $key, $params = [], string $fallbackLocale = null) {
    if( empty($fallbackLocale) ) $fallbackLocale = config('app.fallback_locale');
    $t = __($key, $params); // uses current locale, e.g. app()->getLocale()
    if( $t !== $key ) return $t;
    if( $fallbackLocale  ) return __($key, $params, $fallbackLocale);
    return $key;
   
  }

}


if( ! function_exists('br2nl') ) {

  function br2nl(string $text): string {
    $breaks = array("<br />", "<br>", "<br/>");
    return str_ireplace($breaks, "\r\n", $text);
  }

}
