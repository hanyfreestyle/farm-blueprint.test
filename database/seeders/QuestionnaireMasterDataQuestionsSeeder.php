<?php

namespace Database\Seeders;

use Database\Seeders\Questions\FarmStructure\BarnDataQuestionsSeeder;
use Database\Seeders\Questions\FarmStructure\BatteryDataQuestionsSeeder;
use Database\Seeders\Questions\FarmStructure\BreedDataQuestionsSeeder;
use Database\Seeders\Questions\FarmStructure\BreedMetricsQuestionsSeeder;
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
use Database\Seeders\Questions\FarmStructure\ProductionPurposesQuestionsSeeder;
use Database\Seeders\Questions\FarmStructure\TransferReasonsQuestionsSeeder;
use Database\Seeders\Questions\FarmStructure\VentilationSystemsQuestionsSeeder;
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
         * Therefore this orchestrator must explicitly call every approved
         * Master Data question seeder that is expected to exist after a fresh
         * rebuild. Do not rely on questions left over from a previous database.
         *
         * CageDataQuestionsSeeder is intentionally excluded for now because
         * the cage questionnaire is being redesigned around the new structural
         * and action-based operating model.
         */
        $this->call([
            FarmDataQuestionsSeeder::class,
            OperationalActivitiesQuestionsSeeder::class,
            BarnDataQuestionsSeeder::class,
            BatteryDataQuestionsSeeder::class,
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
