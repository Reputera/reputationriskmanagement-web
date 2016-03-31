<?php

namespace App\Services\Vendors\RecordedFuture\Api;

class Entity extends BaseRecord
{
    /**
     * @var string
     */
    protected $id;

    public function __construct(string $entityKey, array $record)
    {
        $this->id = $entityKey;
        parent::__construct($record);
    }

    /**
     * Gets the Recorded Future identifier.
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Gets the name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->getFieldAsString('name');
    }

    /**
     * Gets the hits.
     *
     * @return int
     */
    public function getHits(): int
    {
        return $this->getFieldAsInt('hits');
    }

    /**
     * Gets the type.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->getFieldAsString('type');
    }

    /**
     * Gets the Recorded Future container codes/IDs.
     *
     * @return array
     */
    public function getContainerCodes(): array
    {
        return $this->getFieldAsArray('containers');
    }
}
