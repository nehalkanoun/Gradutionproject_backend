<?php

namespace Database\Seeders;


use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;


class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('admins')->truncate();
        $admins=[
            [
                'username'=>'nehalkanoun',
                'password'=>Hash::make('123456789'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),

            ],
            [
                'username'=>'mosabsalem',
                'password'=>Hash::make('123456789'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'username'=>'testadmin',
                'password'=>Hash::make('123456789'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'username'=>'deleteadmin',
                'password'=>Hash::make('123456789'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];
        DB::table('admins')->insert($admins);
    }
}


