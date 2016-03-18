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
            'id' => 1,
            'name' => 'Asia',
            'entity_id' => 'B_Fa4'
        ]);
        Region::create([
            'id' => 2,
            'name' => 'Asia and east Africa',
            'entity_id' => 'HwAhdb'
        ]);
        Region::create([
            'id' => 3,
            'name' => 'Antarctica',
            'entity_id' => 'CAvQc'
        ]);
        Region::create([
            'id' => 4,
            'name' => 'Oceania',
            'entity_id' => 'Bch5HB'
        ]);
        Region::create([
            'id' => 5,
            'name' => 'Africa',
            'entity_id' => 'B_FGS'
        ]);
        Region::create([
            'id' => 6,
            'name' => 'Europe',
            'entity_id' => 'B_FA-'
        ]);
        Region::create([
            'id' => 7,
            'name' => 'South America',
            'entity_id' => 'B_KvE'
        ]);
        Region::create([
            'id' => 8,
            'name' => 'Europe; Asia',
            'entity_id' => 'I_aUUN'
        ]);
        Region::create([
            'id' => 9,
            'name' => 'North America',
            'entity_id' => 'B_GRZ'
        ]);
    }
}
