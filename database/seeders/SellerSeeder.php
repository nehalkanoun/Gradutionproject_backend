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
        $sellers = [
            [
                'username' => 'Vigo',
                'password' => Hash::make('123456789'),
                'phonenumber' => '0916683593',
                'location' => 'الوكالات',
                'details' => 'كافي يقدم انواع مميزه من المأكولات والمشروبات',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),

            ],
            [
                'username' => 'المطبخ العربي',
                'password' => Hash::make('123456789'),
                'phonenumber' => '0924555000',
                'location' => 'الرحبه',
                'details' => 'شركه تقدم المأكولات للمناسبات',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'username' => 'چميرا للتموين',
                'password' => Hash::make('123456789'),
                'phonenumber' => '0913092828',
                'location' => '
شارع الشرطة العسكرية - مقابل بوابة قصر السندباد',

                'details' => 'شركــة تمويـن وتعهدات
(شركات - مستشفيات - طيران - مؤتمرات)
',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'username' => 'Dania',
                'password' => Hash::make('123456789'),
                'phonenumber' => '0924212819',
                'location' => 'فينيسيا',

                'details' => 'شركه لتقديم انواع مختلفه من المشروبات والحلويات',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'username' => 'الفخامه الملكية',
                'password' => Hash::make('123456789'),
                'phonenumber' => '0924212119',
                'location' => 'بلعون',

                'details' => 'شركه لتقديم كروت الافراح',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'username' => 'deleted seller',
                'password' => Hash::make('123456789'),
                'phonenumber' => '0921212119',
                'location' => 'بلعون',

                'details' => 'شركه لتقديم كروت الافراح',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];
        DB::table('sellers')->insert($sellers);





    }
}
