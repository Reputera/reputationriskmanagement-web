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
            'name' => 'Asia',
            'entity_id' => 'B_Fa4'
        ]);
        Region::create([
            'name' => 'Asia and east Africa',
            'entity_id' => 'HwAhdb'
        ]);
        Region::create([
            'name' => 'Antarctica',
            'entity_id' => 'CAvQc'
        ]);
        Region::create([
            'name' => 'Oceania',
            'entity_id' => 'Bch5HB'
        ]);
        Region::create([
            'name' => 'Africa',
            'entity_id' => 'B_FGS'
        ]);
        Region::create([
            'name' => 'Europe',
            'entity_id' => 'B_FA-'
        ]);
        Region::create([
            'name' => 'South America',
            'entity_id' => 'B_KvE'
        ]);
        Region::create([
            'name' => 'Europe; Asia',
            'entity_id' => 'I_aUUN'
        ]);
        Region::create([
            'name' => 'North America',
            'entity_id' => 'B_GRZ'
        ]);
    }
}
