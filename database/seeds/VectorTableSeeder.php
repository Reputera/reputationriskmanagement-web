<?php

use App\Entities\Vector;
use Illuminate\Database\Seeder;

class VectorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Vector::create([
           'name' => 'Compliance'
        ]);

        Vector::create([
            'name' => 'Influencers'
        ]);

        Vector::create([
            'name' => 'Media'
        ]);

        Vector::create([
            'name' => 'Information'
        ]);

        Vector::create([
            'name' => 'Operations'
        ]);

        Vector::create([
            'name' => 'Political'
        ]);
    }
}
