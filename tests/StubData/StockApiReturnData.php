<?php

class StockApiReturnData
{
    public static $date;

    public static function getGoodData($recordsToGenerate = 1)
    {
        if (!self::$date) {
            self::$date = date('Y-m-d', strtotime('today-1day'));
        }

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
                "end_date" => self::$date,
                "frequency" => "daily",
                "data" => [
                    0 => [
                        0 => self::$date,
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

    public static function generateRecords($number = 1)
    {

    }
}
