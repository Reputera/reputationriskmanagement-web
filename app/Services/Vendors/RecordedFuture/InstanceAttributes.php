<?php

namespace App\Services\Vendors\RecordedFuture;

class InstanceAttributes extends BaseRecord
{
    public function getPositiveSentiment(): float
    {
        return $this->getFieldAsFloat('general_positive');
    }

    public function getNegativeSentiment(): float
    {
        return $this->getFieldAsFloat('general_negative');
    }

    public function getEntities(): array
    {
        /** @var RecordedFutureApi $recordedFutureApi */
        $recordedFutureApi = app(RecordedFutureApi::class);
        if ($response = $recordedFutureApi->getEntitiesByCodes($this->getFieldAsArray('entities'))) {
            return $response->getEntities();
        }

        return [];
    }
}
