<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('suppliers')->insert(
            [
                [
                    'name' => 'NXB Trẻ',
                ],
                [
                    'name' => 'NXB Kim Đồng',
                ],
                [
                    'name' => 'NXB Thanh Niên',
                ],
                [
                    'name' => 'NXB Văn Học',
                ],
                [
                    'name' => 'Thái Hà',
                ],
                [
                    'name' => 'Nhã Nam',
                ],
                [
                    'name' => 'AZ',
                ],
                [
                    'name' => 'SkyBooks',
                ],
                [
                    'name' => 'NXB Phụ nữ',
                ],
                [
                    'name' => 'Biết Nam',
                ],
            ]
        );
    }
}
