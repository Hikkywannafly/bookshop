<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FormalitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('formalities')->insert(
            [
                [
                    'name' => 'bìa cứng'
                ],
                [
                    'name' => 'bìa mềm'
                ],
                [
                    'name' => 'full box'
                ]
            ]

        );
    }
}
