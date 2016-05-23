<?php

namespace App\Http\Traits;

use Illuminate\Http\Request;

trait IsApiRequestChecker
{
    protected function isApiRequest(Request $request)
    {
        return ($request->route() && str_contains($request->route()->getPrefix(), '/api')  || $request->isJson() || $request->wantsJson());
    }
}
