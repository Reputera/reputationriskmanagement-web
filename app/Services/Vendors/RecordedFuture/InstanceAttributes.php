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

    public function getEntityCodes(): array
    {
        return $this->getFieldAsArray('entities', []);
    }
}
