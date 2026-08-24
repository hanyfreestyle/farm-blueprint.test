<?php

namespace Database\Seeders;

use Database\Seeders\Questions\MasterData\BreedDataQuestionsSeeder;
use Database\Seeders\Questions\MasterData\BreedMetricsQuestionsSeeder;
use Database\Seeders\Questions\MasterData\CitiesQuestionsSeeder;
use Database\Seeders\Questions\MasterData\CoolingSystemsQuestionsSeeder;
use Database\Seeders\Questions\MasterData\ExclusionReasonsQuestionsSeeder;
use Database\Seeders\Questions\MasterData\ExitReasonsQuestionsSeeder;
use Database\Seeders\Questions\MasterData\GovernoratesQuestionsSeeder;
use Database\Seeders\Questions\MasterData\HeatingSystemsQuestionsSeeder;
use Database\Seeders\Questions\MasterData\MaleChangeReasonsQuestionsSeeder;
use Database\Seeders\Questions\MasterData\MortalityReasonsQuestionsSeeder;
use Database\Seeders\Questions\MasterData\OperationalActivitiesQuestionsSeeder;
use Database\Seeders\Questions\MasterData\ProductionPurposesQuestionsSeeder;
use Database\Seeders\Questions\MasterData\TransferReasonsQuestionsSeeder;
use Database\Seeders\Questions\MasterData\VentilationSystemsQuestionsSeeder;
use Illuminate\Database\Seeder;

class QuestionnaireMasterDataQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        /*
         * Current local development workflow:
         *
         *   php artisan migrate:refresh --seed
         *
         * This orchestrator owns reference/master-data question groups only.
         * Structural entities are seeded through QuestionnaireFarmStructureQuestionsSeeder.
         * Operation settings belong to their own main section and are intentionally
         * not represented as a Master Data subsection here.
         */
        $this->call([
            OperationalActivitiesQuestionsSeeder::class,
            ProductionPurposesQuestionsSeeder::class,
            BreedMetricsQuestionsSeeder::class,
            BreedDataQuestionsSeeder::class,
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
