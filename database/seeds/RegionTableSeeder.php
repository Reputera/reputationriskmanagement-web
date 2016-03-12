<?php

use App\Entities\Region;
use Illuminate\Database\Seeder;

class RegionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Region::create([
            'name' => 'Asia'
        ]);

        Region::create([
            'name' => 'Africa'
        ]);

        Region::create([
            'name' => 'North America'
        ]);

        Region::create([
            'name' => 'South America'
        ]);

        Region::create([
            'name' => 'Antarctica'
        ]);

        Region::create([
            'name' => 'Europe'
        ]);

        Region::create([
            'name' => 'Australia'
        ]);
    }
}
