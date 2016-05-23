<?php

namespace App\Http\Middleware;

use Closure;

class Role
{
    /**
     * Run the request filter.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $roles
     * @return mixed
     */
    public function handle($request, Closure $next, $roles)
    {
        $user = $request->user();

        foreach (explode('|', $roles) as $role) {
            if (!$user || !$user->hasRole($role)) {
                return redirect(route('landing'));
            }
        }

        return $next($request);
    }
}
