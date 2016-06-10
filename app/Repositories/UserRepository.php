<?php

namespace App\Repositories;


class UserRepository
{

    /**
     * Get an array of IDs for users that should be alerted for an instance based on given instanceId
     *
     * @param int $instanceId
     * @return array
     */
    public function getAlertedUserIdsForInstanceId(int $instanceId) {
        $userIds = \DB::table('users')
            ->select('users.id')
            ->join('companies', 'companies.id', '=', 'users.company_id')
            ->join('instances', 'instances.company_id', '=', 'users.company_id')
            ->where(function($query) {
                $query->whereRaw('instances.risk_score < companies.min_alert_threshold')
                    ->orWhereRaw('instances.risk_score > companies.max_alert_threshold');
            })
            ->where('instances.id', '=', $instanceId)
            ->get();

        $returnIds = [];
        foreach($userIds as $userId) {
            $returnIds[] = $userId->id;
        }
        return $returnIds;
    }

}