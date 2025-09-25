<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BuildingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('buildings')->insert([
            [
                'address' => 'г. Москва, ул. Ленина 1, офис 3',
                'latitude' => 55.7558,
                'longitude' => 37.6173,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'address' => 'г. Санкт-Петербург, Невский пр. 25, офис 10',
                'latitude' => 59.9343,
                'longitude' => 30.3351,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'address' => 'г. Казань, ул. Баумана 5, офис 55',
                'latitude' => 55.7887,
                'longitude' => 49.1221,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
