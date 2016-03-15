<?php

namespace App\Services\Vendors\RecordedFuture;

class Instance extends BaseRecord
{
    public function getId(): string
    {
        return $this->getFieldAsString('id');
    }

    public function getType(): string
    {
        return $this->getFieldAsString('type');
    }

    public function getFragment(): string
    {
        return $this->getFieldAsString('fragment');
    }

    public function getDocument(): Document
    {
        return new Document(array_get($this->record, 'document'));
    }

    public function getAttributes(): InstanceAttributes
    {
        return new InstanceAttributes(array_get($this->record, 'attributes'));
    }

    public function getCountry()
    {
        $countryName = $this->getDocument()->getSource()->getCountry();
        if (!$countryName && $entities = $this->getAttributes()->getEntities()) {
            foreach ($entities as $entity) {
                if ($entity->getType() == 'Country') {
                    return $entity;
                }
            }
        }

        return '';
    }

    /**
     * @return Entity|null
     */
    public function getContinent()
    {
        /** @var RecordedFutureApi $recordedFutureApi */
        $recordedFutureApi = app(RecordedFutureApi::class);
        return $recordedFutureApi->continentFromCountry($this->getCountry());
    }
}
