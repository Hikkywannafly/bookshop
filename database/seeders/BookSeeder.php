<?php

namespace Database\Seeders;

use Faker\Factory;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Factory::create()->seed(20, 'App\Models\Book');
    }
}
