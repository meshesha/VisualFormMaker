<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VisibilityTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        DB::table('visibility_type')->insert([
            ['name' => "Public"],
            ['name' => "Registered Users"],
            ['name' => "Groups"],
            ['name' => "Departments"]
        ]);
    }
}
