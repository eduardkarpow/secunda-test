<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Задаем корневые (верхнего уровня) виды деятельности
        $foodId = DB::table('activities')->insertGetId([
            'name' => 'Еда',
            'depth' => 1,
            'parent_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $servicesId = DB::table('activities')->insertGetId([
            'name' => 'Услуги',
            'depth' => 1,
            'parent_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Задаем дочерние виды деятельности для 'Еда'
        DB::table('activities')->insert([
            [
                'name' => 'Мясная продукция',
                'depth' => 2,
                'parent_id' => $foodId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Молочная продукция',
                'depth' => 2,
                'parent_id' => $foodId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
        
        // Задаем дочерние виды деятельности для 'Услуги'
        DB::table('activities')->insert([
            [
                'name' => 'Банковские услуги',
                'depth' => 2,
                'parent_id' => $servicesId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
