<?php

namespace App\Services\Vendors\RecordedFuture;

class Source extends BaseRecord
{
    public function getId(): string
    {
        return $this->getFieldAsString('id');
    }

    public function getName(): string
    {
        return $this->getFieldAsString('name');
    }

    public function getDescription(): string
    {
        return $this->getFieldAsString('description');
    }

    public function getMediaType(): string
    {
        return $this->getFieldAsString('media_type');
    }

    public function getTopic(): string
    {
        return array_get($this->source, 'topic');
    }

    public function getCountry(): string
    {
        return $this->getFieldAsString('country');
    }
}
