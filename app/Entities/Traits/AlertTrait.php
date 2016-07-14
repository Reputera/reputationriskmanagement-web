<?php

namespace App\Entities\Traits;


use App\Entities\Instance;

trait AlertTrait
{

    /**
     * @param bool $dismissed
     * @param null $start
     * @param null $end
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAlertedInstances($dismissed = false, $start = null, $end = null)
    {
        $builder = Instance::select('instances.*')
            ->where('user_instance_alerts.user_id', '=', $this->id)
            ->where('user_instance_alerts.dismissed', '=', $dismissed)
            ->join('user_instance_alerts', 'instances.id', '=', 'user_instance_alerts.instance_id')
            ->join('users', 'user_instance_alerts.user_id', '=', 'users.id');

        if ($start) {
            $builder->where('instances.start', '>', $start);
        }
        if ($end) {
            $builder->where('instances.start', '<', $end);
        }
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