<?php

use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insert([
            ['group' => "dashboard", "name" => "forms", "value" => "1"],
            ['group' => "dashboard", "name" => "users", "value" => "1"],
            ['group' => "dashboard", "name" => "groups", "value" => "1"],
            ['group' => "dashboard", "name" => "departments", "value" => "1"],
            ['group' => "dashboard", "name" => "submitted", "value" => "1"],
            ['group' => "forms", "name" => "paginate", "value" => "10"],
            ['group' => "forms", "name" => "default_allows_edit", "value" => "0"],
            ['group' => "my_submissions", "name" => "paginate", "value" => "10"],
            ['group' => "my_submissions", "name" => "enable_form_details_view", "value" => "1"],
            ['group' => "my_submissions", "name" => "enable_delete", "value" => "1"],
            ['group' => "users", "name" => "paginate", "value" => "10"],
            ['group' => "users", "name" => "default_groups", "value" => "4"],
            ['group' => "users", "name" => "default_user_status", "value" => "0"],
            ['group' => "users", "name" => "allows_registration", "value" => "1"],
            ['group' => "users", "name" => "allow_reset_password", "value" => "1"],
            ['group' => "users", "name" => "allow_remember", "value" => "1"],
            ['group' => "groups", "name" => "paginate", "value" => "10"],
            ['group' => "departments_table", "name" => "paginate", "value" => "10"],
            ['group' => "departments_tree", "name" => "direction", "value" => "NORTH"],
            ['group' => "departments_tree", "name" => "profile_link", "value" => "1"],
            ['group' => "departments_tree", "name" => "title", "value" => "1"],
            ['group' => "departments_tree", "name" => "email", "value" => "1"],
            ['group' => "departments_tree", "name" => "image", "value" => "1"],
            ['group' => "departments_tree", "name" => "name", "value" => "1"]
        ]);
    }
}
