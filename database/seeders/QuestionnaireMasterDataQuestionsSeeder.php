<?php

namespace Database\Seeders;

use Database\Seeders\Questions\MasterData\BatteryPhysicalTypesQuestionsSeeder;
use Database\Seeders\Questions\MasterData\BreedDataQuestionsSeeder;
use Database\Seeders\Questions\MasterData\BreedMetricsQuestionsSeeder;
use Database\Seeders\Questions\MasterData\CagePhysicalTypesQuestionsSeeder;
use Database\Seeders\Questions\MasterData\CageUsagesQuestionsSeeder;
use Database\Seeders\Questions\MasterData\CitiesQuestionsSeeder;
use Database\Seeders\Questions\MasterData\CoolingSystemsQuestionsSeeder;
use Database\Seeders\Questions\MasterData\ExclusionReasonsQuestionsSeeder;
use Database\Seeders\Questions\MasterData\ExitReasonsQuestionsSeeder;
use Database\Seeders\Questions\MasterData\GovernoratesQuestionsSeeder;
use Database\Seeders\Questions\MasterData\HealthProblemCategoriesQuestionsSeeder;
use Database\Seeders\Questions\MasterData\HeatingSystemsQuestionsSeeder;
use Database\Seeders\Questions\MasterData\IsolationReasonsQuestionsSeeder;
use Database\Seeders\Questions\MasterData\MaleChangeReasonsQuestionsSeeder;
use Database\Seeders\Questions\MasterData\MortalityReasonsQuestionsSeeder;
use Database\Seeders\Questions\MasterData\OperationalActivitiesQuestionsSeeder;
use Database\Seeders\Questions\MasterData\OperationalTaskTypesQuestionsSeeder;
use Database\Seeders\Questions\MasterData\ProductionPurposesQuestionsSeeder;
use Database\Seeders\Questions\MasterData\TransferReasonsQuestionsSeeder;
use Database\Seeders\Questions\MasterData\VentilationSystemsQuestionsSeeder;
use Database\Seeders\Sections\QuestionnaireMasterDataSectionSeeder;
use Illuminate\Database\Seeder;

class QuestionnaireMasterDataQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(QuestionnaireMasterDataSectionSeeder::class);

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
            CagePhysicalTypesQuestionsSeeder::class,
            CageUsagesQuestionsSeeder::class,
            BatteryPhysicalTypesQuestionsSeeder::class,
            OperationalTaskTypesQuestionsSeeder::class,
            IsolationReasonsQuestionsSeeder::class,
            HealthProblemCategoriesQuestionsSeeder::class,
        ]);
    }
}
