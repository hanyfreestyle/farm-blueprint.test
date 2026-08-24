<?php

namespace Database\Seeders;

use Database\Seeders\Questions\FarmStructure\BarnDataQuestionsSeeder;
use Database\Seeders\Questions\FarmStructure\BatteryDataQuestionsSeeder;
use Database\Seeders\Questions\FarmStructure\FarmDataQuestionsSeeder;
use Illuminate\Database\Seeder;

class QuestionnaireFarmStructureQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        /*
         * Structural entities are seeded separately from reference Master Data.
         *
         * CageDataQuestionsSeeder is intentionally excluded until the cage
         * questionnaire is rebuilt around the approved generated-identity,
         * QR-code and action/history based model.
         */
        $this->call([
            FarmDataQuestionsSeeder::class,
            BarnDataQuestionsSeeder::class,
            BatteryDataQuestionsSeeder::class,
        ]);
    }
}
