<?php

use App\Entities\Company;
use Illuminate\Database\Seeder;

class IndustryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Company::create([
            'name' => 'Consumer Goods',
            'entity_id' => 'I6fXbd'
        ]);
        Company::create([
            'name' => 'Media and Entertainment',
            'entity_id' => 'BEifq6'
        ]);
    }
}
