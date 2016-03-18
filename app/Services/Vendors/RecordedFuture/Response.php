<?php

namespace App\Services\Vendors\RecordedFuture;

use GuzzleHttp\Psr7\Response as GuzzleResponse;

class Response extends BaseRecord
{
    public function __construct(GuzzleResponse $response)
    {
        $this->record = json_decode($response->getBody(), true);
    }

    public function hasMorePages(): bool
    {
        return $this->getFieldAsBool('next_page_start');
    }

    public function getNextPageStart(): string
    {
        return $this->getFieldAsString('next_page_start');
    }

    public function countOfReferences(): int
    {
        return $this->getFieldAsInt('count.references.returned');
    }

    public function totalReferences(): int
    {
        return $this->getFieldAsInt('count.references.total');
    }

    /**
     * @return array
     */
    public function getInstances(): array
    {
        $instancesToReturn = [];
        foreach (array_get($this->record, 'instances', []) as $instance) {
            $instance['entities'] = array_get($this->record, 'entities', []);
            $instancesToReturn[] = new Instance($instance);
        }

        return $instancesToReturn;
    }

    /**
     * @return array
     */
    public function getEntities(): array
    {
        $entities = array_get($this->record, 'entity_details', []);
        $entitiesToReturn = [];
        foreach ($entities as $entityKey => $entity) {
            $entitiesToReturn[] = new Entity($entityKey, $entity);
        }

        return $entitiesToReturn;
    }

    /**
     * @return Entity
     */
    public function getEntity()
    {
        $entities = array_get($this->record, 'entity_details', []);
        foreach ($entities as $entityKey => $entity) {
            return new Entity($entityKey, $entity);
        }

        return [];
    }
}
