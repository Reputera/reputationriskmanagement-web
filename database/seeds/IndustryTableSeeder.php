<?php

use App\Entities\Industry;
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
        Industry::create([
            'name' => 'Consumer Package Goods (CPG)',
        ]);
    }
}
