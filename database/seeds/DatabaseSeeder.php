<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        
        $this->call([
            UsersGroupsSeeder::class,
            AdminUserSeeder::class,
            VisibilityTypesSeeder::class,
            OrgDepartmentsSeeder::class,
            FormStatusSeeder::class,
            FormTypesSeeder::class,
            SettingSeeder::class,
            AdminRoleSeeder::class,
        ]);
    }
}
