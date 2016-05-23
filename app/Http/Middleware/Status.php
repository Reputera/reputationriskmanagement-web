<?php

namespace App\Http\Middleware;

use Closure;

class Status
{
    /**
     * Run the request filter.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $statuses
     * @return mixed
     */
    public function handle($request, Closure $next, $statuses)
    {
        $user = $request->user();

        foreach (explode('|', $statuses) as $status) {
            if (!$user || !$user->isOfStatus($status)) {
                return redirect(route('landing'));
            }
        }

        return $next($request);
    }
}
