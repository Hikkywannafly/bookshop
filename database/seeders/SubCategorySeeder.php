<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sub_categories')->insert(
            [
                [
                    'category_id' => 1,
                    'name' => 'văn học nước ngoài',
                    'slug' => 'van-hoc-nuoc-ngoai',
                ],
                [
                    'category_id' => 2,
                    'name' => 'tâm lý',
                    'slug' => 'tam-ly',
                ],
                [
                    'category_id' => 3,
                    'name' => 'kinh doanh',
                    'slug' => 'kinh-doanh',
                ],
                [
                    'category_id' => 4,
                    'name' => 'manga commic',
                    'slug' => 'manga-commic',
                ],
                [
                    'category_id' => 5,
                    'name' => 'sách giáo khoa',
                    'slug' => 'sach-giao-khoa',
                ],
                [
                    'category_id' => 6,
                    'name' => 'tiếng nhật',
                    'slug' => 'tieng-nhat',
                ]
            ]
        );
    }
}
