<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;



class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('rooms')->insert([
            [
                'room_name' => 'Room A',
                'description' => 'This is Room A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'room_name' => 'Room B',
                'description' => 'This is Room B',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'room_name' => 'Room C',
                'description' => 'This is Room C',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
