<?php

namespace Database\Seeders;

use Database\Seeders\Questions\FarmStructure\BarnDataQuestionsSeeder;
use Database\Seeders\Questions\FarmStructure\BatteryDataQuestionsSeeder;
use Database\Seeders\Questions\FarmStructure\CageDataQuestionsSeeder;
use Database\Seeders\Questions\FarmStructure\FarmDataQuestionsSeeder;
use Illuminate\Database\Seeder;

class QuestionnaireFarmStructureQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            FarmDataQuestionsSeeder::class,
            BarnDataQuestionsSeeder::class,
            BatteryDataQuestionsSeeder::class,
            CageDataQuestionsSeeder::class,
        ]);
    }
}
