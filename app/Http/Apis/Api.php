<?php

namespace App\Http\Apis;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Api extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
