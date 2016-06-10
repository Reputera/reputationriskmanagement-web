<?php

namespace App\Entities\Traits;


use App\Entities\Instance;

trait AlertTrait
{

    /**
     * @param bool $dismissed
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAlertedInstances($dismissed = false)
    {
        return Instance::select('instances.*')
            ->where('user_instance_alerts.user_id', '=', $this->id)
            ->where('user_instance_alerts.dismissed', '=', $dismissed)
            ->join('user_instance_alerts', 'instances.id', '=', 'user_instance_alerts.instance_id')
            ->join('users', 'user_instance_alerts.user_id', '=', 'users.id')
            ->get();
    }

    /**
     * @param $instanceId
     */
    public function dismissAlert($instanceId)
    {
        \DB::table('user_instance_alerts')
            ->update(['dismissed' => true])
            ->where([
                'instance_id' => $instanceId,
                'user_id' => $this->id
            ]);
    }

}