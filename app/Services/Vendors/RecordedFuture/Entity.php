<?php

namespace App\Services\Vendors\RecordedFuture;

class Entity extends BaseRecord
{
    /**
     * @var
     */
    protected $id;

    public function __construct(string $entityKey, array $record)
    {
        $this->id = $entityKey;
        parent::__construct($record);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->getFieldAsString('name');
    }

    public function getHits(): int
    {
        return $this->getFieldAsInt('hits');
    }

    public function getType(): string
    {
        return $this->getFieldAsString('type');
    }

    public function getContainers(): array
    {
        return $this->getFieldAsArray('containers');
    }
}
