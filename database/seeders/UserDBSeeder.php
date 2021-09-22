<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserDBSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public static function run()
    {

        DB::table('products')->insert([
            [
                'name' => 'Samsung Mobile',
                'price' => '310',
                'category_id' => '1',
            ],
            [
                'name' => 'Samsung TV',
                'price' => '510',
                'category_id' => '2',
            ],
            [
                'name' => 'Sony Mobile',
                'price' => '290',
                'category_id' => '1',
            ],
            [
                'name' => 'Sony TV',
                'price' => '590',
                'category_id' => '2',
            ]
        ]);
        DB::table('categories')->insert([
            [
                'name' => 'Mobile',
            ],
            [
                'name' => 'TV',
            ]
        ]);
    }
}
