<?php

use App\Entities\Company;
use Illuminate\Database\Seeder;

class CompanyCompetitorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Company::all() as $company) {
            foreach (Company::where('id', '!=', $company->id)->get() as $competitor) {
                \DB::table('company_competitor')->insert([
                    'company_id' => $company->id,
                    'competitor_company_id' => $competitor->id,
                ]);
            }
        }
    }
}
