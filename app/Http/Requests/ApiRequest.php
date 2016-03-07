<?php

namespace App\Http\Requests;

use App\Http\Traits\ErrorResponses;

abstract class ApiRequest extends Request
{
    use ErrorResponses;
}
