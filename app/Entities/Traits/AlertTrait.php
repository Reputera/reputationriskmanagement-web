<?php

namespace App\Entities\Traits;


use App\Entities\Instance;
use Carbon\Carbon;

trait AlertTrait
{

    /**
     * @param bool $dismissed
     * @param null $start
     * @param null $end
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAlertedInstances($dismissed = false, $start = null, $end = null, $vectorName = null)
    {
        $builder = Instance::select('instances.*')
            ->where('user_instance_alerts.user_id', '=', $this->id)
            ->join('user_instance_alerts', 'instances.id', '=', 'user_instance_alerts.instance_id')
            ->join('users', 'user_instance_alerts.user_id', '=', 'users.id');

        if($dismissed == false) {
            $builder->where('user_instance_alerts.dismissed', '=', false);
        }

        if($vectorName) {
            $builder->join('vectors', 'vectors.id', '=', 'instances.vector_id')
                ->where('vectors.name', '=', $vectorName);
        }

        if ($start) {
            $builder->where('instances.start', '>=', (new Carbon($start))->toDateString().' 00:00:00');
        }
        if ($end) {
            $builder->where('instances.start', '<=', (new Carbon($end))->toDateString().' 23:59:59');
        }
        $builder->orderBy('instances.start', 'desc');
        return $builder->get();
    }

    /**
     * @param $instanceId
     */
    public function dismissAlert($instanceId)
    {
        \DB::table('user_instance_alerts')
            ->where([
                'instance_id' => $instanceId,
                'user_id' => $this->id
            ])
            ->update(['dismissed' => true]);
    }

}