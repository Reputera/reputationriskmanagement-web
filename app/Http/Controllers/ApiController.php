<?php

namespace App\Http\Controllers;

use App\Http\Traits\ErrorResponses;
use App\Http\Traits\Transformer;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

class ApiController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, Transformer, ErrorResponses;

    public function base64Decode($string)
    {
        return base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $string));
    }
}
