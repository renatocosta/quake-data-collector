<?php

$envAppUrl = env('APP_URL');
$localVueUrl = 'http://localhost:8080';
$reactNativeDebugJs = 'http://localhost:8081';

$envAllowedOrigins = [
  'default' => [ $envAppUrl ],
  'local' => [ $localVueUrl ],
  'staging' => [ $envAppUrl, $reactNativeDebugJs ],
  'production' => [ $envAppUrl, $reactNativeDebugJs ],
];

$allowedOrigins = $envAllowedOrigins[env('APP_ENV')] ?? $envAllowedOrigins['default'];

//$allowedOrigins = ['http://10.211.55.2:8080'];

return [

  /*
  |--------------------------------------------------------------------------
  | Laravel CORS
  |--------------------------------------------------------------------------
  |
  | allowedOrigins, allowedHeaders and allowedMethods can be set to array('*')
  | to accept any value.
  |
  */
  'paths' => ['api/*', 'web/*'],
  'supports_credentials' => false,
  'allowed_origins' => $allowedOrigins,
  'allowed_origins_patterns' => [],
  'allowed_headers' => ['Content-Type', 'X-Requested-With', 'Authorization', 'Accept', 'Application'],
  'allowed_methods' => ['*'],
  'exposed_headers' => ['Terminal-Version', 'Pragma', 'Cache-Control', 'Expires', 'Content-Disposition'],
  'max_age' => 0,

];
