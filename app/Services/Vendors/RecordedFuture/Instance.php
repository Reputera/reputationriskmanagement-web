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

    public function getCountry(): string
    {
        if ($country = $this->getDocument()->getSource()->getCountry()) {
            return $country;
        }

        foreach ($this->getFieldAsArray('entities') as $entity) {
            if (array_get($entity, 'type') == 'Country' && array_has($entity, 'name')) {
                return array_get($entity, 'name');
            }
        }

        return '';
    }
}
