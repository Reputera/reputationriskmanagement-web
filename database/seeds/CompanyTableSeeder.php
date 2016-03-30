<?php

use App\Entities\Company;
use Illuminate\Database\Seeder;

class CompanyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Company::create([
            'name' => 'Tyson Foods',
            'entity_id' => 'I37WRG'
        ]);

        Company::create([
            'name' => 'General Mills',
            'entity_id' => 'B_v7W'
        ]);

        Company::create([
            'name' => 'Godiva Chocolatier',
            'entity_id' => 'DtYnO'
        ]);

        Company::create([
            'name' => 'Heinz Corporation',
            'entity_id' => 'LbbFzW'
        ]);

        Company::create([
            'name' => 'Kraft Foods Group Inc',
            'entity_id' => 'I21w2T'
        ]);

        Company::create([
            'name' => 'Mars',
            'entity_id' => 'CAfcF'
        ]);

        Company::create([
            'name' => 'PepsiCo',
            'entity_id' => 'B_E_A'
        ]);

        Company::create([
            'name' => 'Mondelez International Inc',
            'entity_id' => 'B_iTw'
        ]);

        Company::create([
            'name' => 'Hershey Co',
            'entity_id' => 'B_7KO'
        ]);

        Company::create([
            'name' => 'Ferrero USA',
            'entity_id' => 'I4FZG0'
        ]);
    }
}
