<?php

namespace App\Http\Traits;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait PaginationTrait
{
    /**
     * The maximum pagination results that can be returned.
     *
     * @var int
     */
    protected $maxPaginationLimit = 100;

    /**
     * The default number of results for pagination.
     *
     * @var int
     */
    protected $paginationLimit = 10;

    /**
     * Sets a new pagination limit.
     *
     * @param $newLimit
     * @return int
     */
    public function setPaginationLimit($newLimit)
    {
        if (is_numeric($newLimit) && $newLimit > 0 && $newLimit <= $this->maxPaginationLimit) {
            $this->paginationLimit = $newLimit;
        }

        return $this->paginationLimit;
    }

    /**
     * Gets the pagination limit for API resources.
     *
     * @return int
     */
    public function getPaginationLimit()
    {
        return $this->paginationLimit;
    }

    public function paginateBuilder($builder, Request $request)
    {
        $paginator = $builder->paginate(
            $request->input('count', $this->paginationLimit),
            ['*'],
            'page',
            $request->input('page')
        );
//        This check needs to be done, so if a user selects a page outside range, page resets to 0.
        if (($paginator->currentPage() - 1) * $paginator->perPage() > $paginator->total()) {
            return $builder->paginate(
                $request->input('count', $this->paginationLimit),
                ['*'],
                'page',
                1
            );
        }
        return $paginator;
    }
}