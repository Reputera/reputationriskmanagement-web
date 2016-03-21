<?php

namespace App\Services\Vendors\RecordedFuture;

use GuzzleHttp\Psr7\Response as GuzzleResponse;

class Response extends BaseRecord
{
    public function __construct(GuzzleResponse $response)
    {
        $this->record = json_decode($response->getBody(), true);
    }

    /**
     * Determines if there are more results to be queried from the API.
     *
     * @return bool
     */
    public function hasMorePages(): bool
    {
        return $this->getFieldAsBool('next_page_start');
    }

    /**
     * Gets the hey used for subsequent queries on queries that have paginated results.
     *
     * @return string
     */
    public function getNextPageStart(): string
    {
        return $this->getFieldAsString('next_page_start');
    }

    /**
     * Gets the count of records the API returns for the current response.
     *
     * @return int
     */
    public function countOfReferences(): int
    {
        return $this->getFieldAsInt('count.references.returned');
    }

    /**
     * Gets the total count of records the API will return for a given query. This is the total result count, not the
     * count that is in the current result set.
     *
     * @return int
     */
    public function totalReferences(): int
    {
        return $this->getFieldAsInt('count.references.total');
    }

    /**
     * Gets the instances from a query.
     *
     * @return array
     */
    public function getInstances(): array
    {
        $instancesToReturn = [];
        $requestEntities = $this->getEntities();
        foreach (array_get($this->record, 'instances', []) as $instance) {
            $newInstance = new Instance($instance);

            if ($instanceEntityCodes = $newInstance->getAttributes()->getEntityCodes()) {
                $allInstanceEntities = [];
                $thisInstanceEntities = array_only($requestEntities, $instanceEntityCodes);
                foreach ($thisInstanceEntities as $entity) {
                    $allInstanceEntities = array_merge(
                        $allInstanceEntities,
                        array_only($requestEntities, $entity->getContainerCodes())
                    );
                }

                $newInstance->setRelatedEntities(array_merge($thisInstanceEntities, $allInstanceEntities));
            }

            $instancesToReturn[] = $newInstance;
        }

        return $instancesToReturn;
    }

    /**
     * Gets the entities from a query.
     *
     * @return array
     */
    public function getEntities(): array
    {
        $entities = array_get($this->record, 'entities', []);
        $entitiesToReturn = [];
        foreach ($entities as $entityKey => $entity) {
            $entitiesToReturn[$entityKey] = new Entity($entityKey, $entity);
        }

        return $entitiesToReturn;
    }
}
