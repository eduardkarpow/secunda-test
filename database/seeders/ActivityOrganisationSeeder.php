<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Organisation;
use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActivityOrganisationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('activity_organisation')->truncate();

        // Получаем ID видов деятельности, которые мы ранее создали
        $foodActivityId = Activity::where('name', 'Еда')->value('id');
        $servicesActivityId = Activity::where('name', 'Услуги')->value('id');
        $meatId = Activity::where('name', 'Мясная продукция')->value('id');
        $dairyId = Activity::where('name', 'Молочная продукция')->value('id');

        // Получаем ID организаций
        $organization1Id = Organisation::where('name', 'ООО "Ласточка"')->value('id');
        $organization2Id = Organisation::where('name', 'ЗАО "Прогресс"')->value('id');
        
        if ($organization1Id && $foodActivityId) {
            DB::table('activity_organisation')->insert([
                'activity_id' => $foodActivityId,
                'organisation_id' => $organization1Id,
            ]);
        }

        if ($organization2Id && $servicesActivityId) {
            DB::table('activity_organisation')->insert([
                'activity_id' => $servicesActivityId,
                'organisation_id' => $organization2Id,
            ]);
        }
        
        // Дополнительные связи для примера
        if ($organization1Id && $meatId) {
             DB::table('activity_organisation')->insert([
                'activity_id' => $meatId,
                'organisation_id' => $organization1Id,
            ]);
        }
         if ($organization1Id && $dairyId) {
             DB::table('activity_organisation')->insert([
                'activity_id' => $dairyId,
                'organisation_id' => $organization1Id,
            ]);
        }
    }
}
