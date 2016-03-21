<?php

namespace App\Services\Vendors\RecordedFuture;

class Instance extends BaseRecord
{
    /**
     * @var array
     */
    protected $relatedEntities = [];

    /**
     * Get the Recorded Future instance ID.
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->getFieldAsString('id');
    }

    /**
     * Get the instance type.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->getFieldAsString('type');
    }

    /**
     * Get the fragment.
     *
     * @return string
     */
    public function getFragment(): string
    {
        return $this->getFieldAsString('fragment');
    }

    /**
     * Get the Document object.
     *
     * @return Document
     */
    public function getDocument(): Document
    {
        return new Document(array_get($this->record, 'document'));
    }

    /**
     * Get the attributes.
     *
     * @return InstanceAttributes
     */
    public function getAttributes(): InstanceAttributes
    {
        return new InstanceAttributes(array_get($this->record, 'attributes'));
    }

    /**
     * Adds entities that are involved in the instance.
     *
     * @param array $entities
     */
    public function setRelatedEntities(array $entities)
    {
        $this->relatedEntities = $entities;
    }

    /**
     * Gets all the entities that are countries.
     *
     * @return array
     */
    public function getCountries(): array
    {
        $countries = [];
        foreach ($this->relatedEntities as $entity) {
            if ($entity->getType() == 'Country' && $entity->getName()) {
                $countries[] = $entity;
            }
        }

        return $countries;
    }
}
