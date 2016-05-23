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
           'name' => 'Social Responsibility',
            'default_color' => '#4BE6A1'
        ]);

        Vector::create([
            'name' => 'Influencers',
            'default_color' => '#4BE6A1'
        ]);

        Vector::create([
            'name' => 'Social Intelligence',
            'default_color' => '#4BE6A1'
        ]);

        Vector::create([
            'name' => 'Cybersecurity',
            'default_color' => '#4BE6A1'
        ]);

        Vector::create([
            'name' => 'Operational Risks',
            'default_color' => '#4BE6A1'
        ]);

        Vector::create([
            'name' => 'Geopolitics',
            'default_color' => '#4BE6A1'
        ]);
    }
}
