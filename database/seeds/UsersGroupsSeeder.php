<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersGroupsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users_groups')->insert([
            ['id' => 1, 'name' => "Administrator", "group_status" => 1, "admin_user_ids" => "1"],
            ['id' => 2, 'name' => "Manager", "group_status" => 1, "admin_user_ids" => "1"],
            ['id' => 3, 'name' => "Editor", "group_status" => 1, "admin_user_ids" => "1"],
            ['id' => 4, 'name' => "Registered", "group_status" => 1, "admin_user_ids" => "1"]
        ]);
    }
}
