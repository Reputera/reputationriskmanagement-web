<?php

namespace App\Http\Middleware;

use App\Entities\Status as UserStatus;
use App\Http\Traits\ErrorResponses;
use App\Http\Traits\IsApiRequestChecker;
use Closure;

class Status
{
    use ErrorResponses, IsApiRequestChecker;

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
            if ($user && $user->isOfStatus($status)) {
                return $next($request);
            }
        }
        
        if ($this->isApiRequest($request)) {
            if ($user->isOfStatus(UserStatus::EMAIL_NOT_CHANGED)) {
                return $this->unauthorizedResponse('You must change your email to have access to the system.');
            }
            return $this->unauthorizedResponse();
        } else {
            if ($user->isOfStatus(UserStatus::EMAIL_NOT_CHANGED)) {
                return redirect(route('password.force-reset.get'));
            }
            return redirect(route('landing'));
        }
    }
}
