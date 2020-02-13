<?php

use Illuminate\Database\Seeder;

class OptionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('bsoft_options')->insert([
            'option_name'       => 'company_name',
            'option_content'    => 'BD SOFT IT'
        ]);
    }
}
