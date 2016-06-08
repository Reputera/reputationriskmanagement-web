<?php

namespace App\Http\Queries;

use Carbon\Carbon;

class Instance extends QueryFilter
{
    /**
     * Adds a constraint for a given Vector ID.
     *
     * @param $vectorId
     */
    public function vector($vectorId)
    {
        $this->builder->where('instances.vector_id', $vectorId);
    }

    /**
     * Adds a constraint for the start day to be between "today" and a given number fo days back.
     *
     * @param int $days
     */
    public function lastDays(int $days)
    {
        $this->builder->whereBetween('instances.start', [
            Carbon::now()->subDay($days)->toDateString().' 00:00:00',
            Carbon::now()->toDateTimeString()
        ]);
    }

    /**
     * Constrain instances to be after given datetime.
     *
     * @param $start
     */
    public function dateStart($start)
    {
        $this->builder->where('instances.start', '>', $start);
    }

    /**
     * Constrain instances to be before given datetime.
     *
     * @param $end
     */
    public function dateEnd($end)
    {
        $this->builder->where('instances.end', '<', $end);
    }

}
