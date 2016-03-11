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
            'entity_id' => 'B_PuI'
        ]);

        Company::create([
            'name' => 'General Mills',
            'entity_id' => 'B_v7w'
        ]);

        Company::create([
            'name' => 'Godiva Chocolatier',
            'entity_id' => 'dlnAD'
        ]);

        Company::create([
            'name' => 'Heinz',
            'entity_id' => 'JRS_Gx'
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
            'entity_id' => 'PepsiCo'
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
