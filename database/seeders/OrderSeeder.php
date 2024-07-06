<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('orders')->truncate();
        $orders=[
            [
                'product_ID'=>'1',
                'amount'=>'1',
                'cart_ID'=>'1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),

            ],
            [
                'product_ID'=>'3',
                'amount'=>'2',
                'cart_ID'=>'3',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'product_ID'=>'1',
                'amount'=>'3',
                'cart_ID'=>'2',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];
        DB::table('orders')->insert($orders);
    }
}
