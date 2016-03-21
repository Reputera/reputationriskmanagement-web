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
           'name' => 'Social Responsibility'
        ]);

        Vector::create([
            'name' => 'Influencers'
        ]);

        Vector::create([
            'name' => 'Social Intelligence'
        ]);

        Vector::create([
            'name' => 'Cybersecurity'
        ]);

        Vector::create([
            'name' => 'Operational Risks'
        ]);

        Vector::create([
            'name' => 'Geopolitics'
        ]);
    }
}
