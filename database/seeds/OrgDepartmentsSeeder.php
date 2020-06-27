<?php

use Illuminate\Database\Seeder;

class OrgDepartmentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('organization_tree')->insert([
            ['name' => "CEO", "parent_id" => 0, "admin_user_id" => 1],
            ['name' => "HR", "parent_id" => 1, "admin_user_id" => 1],
            ['name' => "IT", "parent_id" => 1, "admin_user_id" => 1]
        ]);
    }
}
