<?php

namespace Tests\Unit\Services\Vendors\RecordedFuture\Api;

use App\Services\Vendors\RecordedFuture\Api\Response;
use App\Services\Vendors\RecordedFuture\Api\Entity;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use Tests\StubData\RecordedFuture\SingleInstance;

class ResponseTest extends \TestCase
{
    public function test_the_response_has_more_pages()
    {
        $this->assertTrue($this->getResponse(['next_page_start' => '123456'])->hasMorePages());
    }

    public function test_the_response_does_not_have_more_pages()
    {
        $this->assertFalse($this->getResponse([])->hasMorePages());
    }

    public function test_getting_the_next_start_page()
    {
        $this->assertEquals('123456', $this->getResponse(['next_page_start' => '123456'])->getNextPageStart());
    }

    public function test_count_of_current_references()
    {
        $this->assertEquals('1', $this->getResponse(SingleInstance::get())->countOfReferences());
    }

    public function test_total_count_of_references()
    {
        $this->assertEquals('5', $this->getResponse(SingleInstance::get())->totalReferences());
    }

    public function test_getting_instances()
    {
        $response = $this->getResponse(SingleInstance::get());
        $results = $response->getInstances();

        $this->assertInternalType('array', $results);

        $entities = $results[0]->getRelatedEntities();
        $this->assertCount(3, $entities);

        foreach ($entities as $entity) {
            $this->assertInstanceOf(Entity::class, $entity);
        }
    }

    public function test_getting_entites()
    {
        $response = $this->getResponse(SingleInstance::get());
        $results = $response->getEntities();

        $this->assertInternalType('array', $results);
        $this->assertCount(4, $results);

        foreach ($results as $entity) {
            $this->assertInstanceOf(Entity::class, $entity);
        }
    }

    /**
     * @param array $responseArray
     * @return Response
     */
    protected function getResponse(array $responseArray)
    {
        return new Response($responseArray);
    }
}
