<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Resources\Json\Resource;

class Controller extends BaseController {
  use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


  protected function alert($alerts, $statusCode = 422) {
    abort( response(['alerts' => $alerts], $statusCode) );
  }


}
