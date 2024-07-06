<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('customers')->truncate();
        $customers=[
            [
                'username'=>'mosab',
                'phonenumber'=>'0910513967',
                'password'=>Hash::make('mosab123'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),

            ],
            [
                'username'=>'nehal',
                'phonenumber'=>'0910513267',
                'password'=>Hash::make('nehal123'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'username'=>'nehal2',
                'phonenumber'=>'0912513967',
                'password'=>Hash::make('123456789'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];
        DB::table('customers')->insert($customers);    }
}
