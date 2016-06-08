<?php

use App\Entities\Role;
use App\Entities\Status;
use App\Entities\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@rrm.com',
            'password' => bcrypt('testing'),
            'status' => Status::ENABLED,
            'role' => Role::ADMIN
        ]);
    }
}
