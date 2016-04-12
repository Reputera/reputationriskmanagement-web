<?php

namespace App\Http\Queries;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class QueryFilter
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Builder
     */
    protected $builder;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Applies query constraints based on the request.
     *
     * @param Builder $builder
     * @return Builder
     */
    public function apply(Builder $builder): Builder
    {
        $this->builder = $builder;

        foreach ($this->filters() as $name => $value) {
            if (!method_exists($this, $name)) {
                continue;
            }

            if ($trimmedValue = trim($value)) {
                $this->$name($trimmedValue);
            } else {
                $this->$name();
            }
        }

        return $this->builder;
    }

    /**
     * Gets all the request parameters if there are any.
     *
     * @return array
     */
    public function filters(): array
    {
        if ($filters = $this->request->all()) {
            return $filters;
        } else {
            return [];
        }
    }
}
