<?php

namespace Tests\Unit;

use App\Services\Vendors\RecordedFuture;
use GuzzleHttp\Client;
use Tests\StubData\RecordedFuture\EmptyEntity;
use Tests\StubData\RecordedFuture\SingleEntity;

class RecordedFutureTest extends \TestCase
{
    /** @var RecordedFuture */
    protected $rfApi;

    /** @var ClientInterface|\Mockery\MockInterface */
    protected $mockedClient;

    /** @var string */
    protected $apiKey = 'someAPIToken';

    public function setUp()
    {
        parent::setUp();
        $this->mockedClient = \Mockery::mock(Client::class);
        $this->rfApi = new RecordedFuture($this->mockedClient, $this->apiKey);
    }

    public function companyCodeDataProvider()
    {
        $entityArray = SingleEntity::getCompany(['entity_name' => 'Hasbro'], false);
        $jsonEncodedArray = json_encode($entityArray);
        return [
            ['Hasbro', current($entityArray['entities']), $jsonEncodedArray],
            ['SomeCompany', '', EmptyEntity::get()],
        ];
    }

    /** @dataProvider companyCodeDataProvider */
    public function test_getting_company_code($companyName, $expectedResults, $apiReturnData)
    {
        $expectedQueryParams = [
            'json' => [
                'entity' => [
                    'name' => $companyName,
                    'type' => 'Company',
                ],
                'token' => $this->apiKey
            ]
        ];

        $this->assertMockedApiCall($expectedQueryParams, $apiReturnData);

        $this->assertEquals(
            $expectedResults,
            $this->rfApi->entityCodeForCompany($companyName)
        );
    }

    protected function assertMockedApiCall(array $expectedQueryParams, $returnData)
    {
        $this->mockedClient
            ->shouldReceive('get')
            ->once()
            ->with('https://api.recordedfuture.com/query?q=', $expectedQueryParams)
            ->andReturnSelf();

        $this->mockedClient
            ->shouldReceive('getBody')
            ->withNoArgs()
            ->andReturn($returnData);
    }
}
