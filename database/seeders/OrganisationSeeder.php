<?php

namespace Database\Seeders;

use App\Models\Organisation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrganisationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Organisation::create([
            'name' => 'ООО "Ласточка"',
            'phone' => '+7 (495) 123-45-67',
            'building_id' => 1,
        ]);

        Organisation::create([
            'name' => 'ЗАО "Прогресс"',
            'phone' => '+7 (812) 987-65-43',
            'building_id' => 2,
        ]);

        Organisation::create([
            'name' => 'ЗАО "Регресс"',
            'phone' => '+7 (777) 627-42-21',
            'building_id' => 3,
        ]);
    }
}
