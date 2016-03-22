<?php

namespace App\Http\Pipelines\Query;


use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;

class SortingPipeline
{
    /**
     * Handle the process of the Pipe
     *
     * @param Request $request
     * @param \Closure $next
     * @return Builder
     */
    public function handle(Request $request, \Closure $next)
    {
        $builder = $next($request);

        if ($sortBy = $request->input('sort_by')) {
                $builder->orderBy($sortBy, $request->input('sort_direction') ?: 'DESC');
        }

        return $builder;
    }
}