<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateFieldsInProductLines extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('line_types')->insert([
            ['type' => 'firstname'],
            ['type' => 'middlename'],
            ['type' => 'lastname'],
            ['type' => 'fullname'],
            ['type' => 'date of birth'],
            ['type' => 'date of death'],
            ['type' => 'custom'],
        ]);
    }
}
