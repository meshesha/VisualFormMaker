<?php

use Illuminate\Database\Seeder;

class FormStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        DB::table('form_status')->insert([
            ['name' => "unpublished"],
            ['name' => "published"]
        ]);
    }
}
