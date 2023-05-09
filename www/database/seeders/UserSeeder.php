<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'user_name' => '朴相一',
                'email' => 'park930727@yahoo.co.jp',
                'password' => Hash::make('asd369'),
                'tel' => '070-1234-5678',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_name' => 'こうぎ',
                'email' => 'kogi@gmail.com',
                'password' => Hash::make('abcd1234'),
                'tel' => '070-000-0000',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_name' => '太郎',
                'email' => 'taro@example.com',
                'password' => Hash::make('abcd1234'),
                'tel' => '987-654-3210',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
