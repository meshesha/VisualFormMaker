<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        DB::table('users')->insert([
             [
                'id' => 1,
                'name' => "Admin",
                'email' => "admin@localhost",
                'password' => Hash::make("admin"),
                'groups' => "1",
                'status' => "1"
            ],
        ]);
    }
}
