<?php

namespace Features\StockMarket;

class CanGetTodaysStockInfoForACompanyTest extends \TestCase
{
    public function testGettingTodaysStockInfoForACompany()
    {
        $mockedClient = \Mockery::mock(\GuzzleHttp\Client::class);

        $mockedClient->shouldReceive('get')
            ->with('https://www.quandl.com/api/v3/datasets/WIKI/HAS/data.json?limit=1&end_date='.date('Y-m-d').'&api_key=SomeTestingKey')
            ->andReturnSelf();

        $mockedClient->shouldReceive('getBody')
            ->withNoArgs()
            ->andReturn($this->getMockedApiResponse());

        /** @var \App\Services\Vendors\Quandl $quandl */
        $quandl = new \App\Services\Vendors\Quandl($mockedClient);

        $expectedResults = [
            "Date" => date('Y-m-d', strtotime('today-1day')),
            "Open" => 75.68,
            "High" => 76.21,
            "Low" => 74.66,
            "Close" => 76.13,
            "Volume" => 866085,
            "Ex-Dividend" => 0,
            "Split Ratio" => 1,
            "Adj. Open" => 75.68,
            "Adj. High" => 76.21,
            "Adj. Low" => 74.66,
            "Adj. Close" => 76.13,
            "Adj. Volume" => 866085,
        ];

        $this->assertEquals($expectedResults, $quandl->latest('HAS'));
    }

    protected function getMockedApiResponse()
    {
        return json_encode([
            "dataset_data" => [
                "limit" => 1,
                "transform" => null,
                "column_index" => null,
                "column_names" => [
                    0 => "Date",
                    1 => "Open",
                    2 => "High",
                    3 => "Low",
                    4 => "Close",
                    5 => "Volume",
                    6 => "Ex-Dividend",
                    7 => "Split Ratio",
                    8 => "Adj. Open",
                    9 => "Adj. High",
                    10 => "Adj. Low",
                    11 => "Adj. Close",
                    12 => "Adj. Volume",
                ],
                "start_date" => "1984-12-18",
                "end_date" => date('Y-m-d', strtotime('today-1day')),
                "frequency" => "daily",
                "data" => [
                    0 => [
                        0 => date('Y-m-d', strtotime('today-1day')),
                        1 => 75.68,
                        2 => 76.21,
                        3 => 74.66,
                        4 => 76.13,
                        5 => 866085.0,
                        6 => 0.0,
                        7 => 1.0,
                        8 => 75.68,
                        9 => 76.21,
                        10 => 74.66,
                        11 => 76.13,
                        12 => 866085.0,
                      ]
                ],
                "collapse" => null,
                "order" => "desc"
            ]
        ]);
    }
}
