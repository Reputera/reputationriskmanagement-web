<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserTableSeeder::class);
        $this->call(CompanyTableSeeder::class);
        $this->call(IndustryTableSeeder::class);
        $this->call(RegionTableSeeder::class);
        $this->call(VectorTableSeeder::class);
    }
}
