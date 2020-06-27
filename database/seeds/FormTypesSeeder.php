<?php

use Illuminate\Database\Seeder;

class FormTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('form_type_list')->insert([
            ['name' => "Form"],
            ['name' => "Survey"],
            ['name' => "Approval workflow"]
        ]);
    }
}
