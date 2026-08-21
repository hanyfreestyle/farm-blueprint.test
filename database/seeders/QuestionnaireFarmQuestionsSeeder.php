<?php

namespace Database\Seeders;

use Database\Seeders\Questions\FarmStructure\CitiesQuestionsSeeder;
use Database\Seeders\Questions\FarmStructure\CoolingSystemsQuestionsSeeder;
use Database\Seeders\Questions\FarmStructure\ExclusionReasonsQuestionsSeeder;
use Database\Seeders\Questions\FarmStructure\ExitReasonsQuestionsSeeder;
use Database\Seeders\Questions\FarmStructure\FarmDataQuestionsSeeder;
use Database\Seeders\Questions\FarmStructure\GovernoratesQuestionsSeeder;
use Database\Seeders\Questions\FarmStructure\HeatingSystemsQuestionsSeeder;
use Database\Seeders\Questions\FarmStructure\MaleChangeReasonsQuestionsSeeder;
use Database\Seeders\Questions\FarmStructure\MortalityReasonsQuestionsSeeder;
use Database\Seeders\Questions\FarmStructure\OperationalActivitiesQuestionsSeeder;
use Database\Seeders\Questions\FarmStructure\TransferReasonsQuestionsSeeder;
use Database\Seeders\Questions\FarmStructure\VentilationSystemsQuestionsSeeder;
use Illuminate\Database\Seeder;

class QuestionnaireFarmQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        /*
         * Review mode:
         * Seed only the question groups that have already been redesigned
         * and approved for the current Master Data review flow.
         */
        $this->call([
            FarmDataQuestionsSeeder::class,
            OperationalActivitiesQuestionsSeeder::class,
            TransferReasonsQuestionsSeeder::class,
            MortalityReasonsQuestionsSeeder::class,
            ExclusionReasonsQuestionsSeeder::class,
            ExitReasonsQuestionsSeeder::class,
            MaleChangeReasonsQuestionsSeeder::class,
            GovernoratesQuestionsSeeder::class,
            CitiesQuestionsSeeder::class,
            VentilationSystemsQuestionsSeeder::class,
            CoolingSystemsQuestionsSeeder::class,
            HeatingSystemsQuestionsSeeder::class,
        ]);
    }
}
