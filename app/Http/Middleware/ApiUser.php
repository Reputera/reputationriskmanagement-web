<?php

namespace App\Http\Middleware;

use Closure;

class ApiUser
{
    public function handle($request, Closure $next)
    {
        if(!$request->user()->hasRole(\App\Entities\Role::ADMIN)) {
            $request->merge([
                'companies_name' => $request->user()->company->name,
                'companies_id' => $request->user()->company_id
            ]);
        }

        return $next($request);
    }
}
