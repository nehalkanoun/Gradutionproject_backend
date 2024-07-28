<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->truncate();
        $products=[
            [
                'name'=>'موهيتو بلو',
                'price'=>'6',
                'seller_ID'=>'1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),

            ],
            [
                'name'=>' عصير برتقال ',
                'price'=>'8',
                'seller_ID'=>'1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name'=>'كوروكيت البطاطا',
                'price'=>'4',
                'seller_ID'=>'2',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name'=>' مقبلات ',
                'price'=>'4',
                'seller_ID'=>'2',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name'=>' وجبه رئيسيه رز باللحم والمكسرات',
                'price'=>'4',
                'seller_ID'=>'2',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name'=>' طبق رئيسي ثلاث انواع ',
                'price'=>'12',
                'seller_ID'=>'3',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name'=>'شربه ليبيه',
                'price'=>'12',
                'seller_ID'=>'3',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name'=>'عصير طبيعي',
                'price'=>'5',
                'seller_ID'=>'4',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name'=>'كرت ',
                'price'=>'4',
                'seller_ID'=>'5',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name'=>'كرت ',
                'price'=>'5',
                'seller_ID'=>'5',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

        ];
        DB::table('products')->insert($products);
    }
}
