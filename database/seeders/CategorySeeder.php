<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('categories')->insert(
            [
                [
                    'name' => 'văn học',
                    'slug' => 'van-hoc',
                ],
                [
                    'name' => 'tâm lý - kĩ năng sống',
                    'slug' => 'tam-ly-ki-nang-song',
                ],
                [
                    'name' => 'kinh tế',
                    'slug' => 'kinh-te',
                ],
                [
                    'name' => 'sách thiếu nhi',
                    'slug' => 'sach-thieu-nhi',
                ],
                [
                    'name' => 'giáo khoa - tham khảo',
                    'slug' => 'giao-khoa-tham-khao',
                ],
                [
                    'name' => 'ngoại ngữ',
                    'slug' => 'ngoai-ngu',
                ]

            ]

        );
    }
}
