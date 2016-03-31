<?php

namespace App\Services\Vendors\RecordedFuture\Api;

class Document extends BaseRecord
{
    public function getId(): string
    {
        return $this->getFieldAsString('id');
    }

    public function getTitle(): string
    {
        return $this->getFieldAsString('title');
    }

    public function getLanguage(): string
    {
        return $this->getFieldAsString('language');
    }

    public function getPublished(): string
    {
        return $this->getFieldAsString('published');
    }

    public function getUrl(): string
    {
        return $this->getFieldAsString('url');
    }

    public function getSource(): Source
    {
        return new Source($this->getFieldAsArray('sourceId'));
    }
}
