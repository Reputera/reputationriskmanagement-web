<?php

use App\Entities\Company;
use Illuminate\Database\Seeder;

class CompanyIndustryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Company::all() as $company) {
            \DB::table('company_industry')->insert([
                'company_id' => $company->id,
                'industry_id' => 1
            ]);
        }
    }
}
