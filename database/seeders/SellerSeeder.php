<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class SellerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sellers')->truncate();
        $sellers=[
            [
                'username' => 'vigo',
                'password' => Hash::make('123456789'),
                'phonenumber' => '0922401012',
                'location'=>'الوكالات',
                'details'=>'cafe',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),

            ],
            [
                'username' => 'مطبخ العربي',
                'password' => Hash::make('123456789'),
                'phonenumber' => '0922403013',
                'location'=>'رحبه',
                'details'=>'resturant',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'username' => 'colombia',
                'password' => Hash::make('123456789'),
                'phonenumber' => '0923401013',
                'location'=>'بلعون',
                'details'=>'resturant',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];
        DB::table('sellers')->insert($sellers);





    }
}
