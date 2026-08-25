<?php

namespace Database\Seeders;

use Database\Seeders\Questions\Reports\BirthLactationWeaningReportsQuestionsSeeder;
use Database\Seeders\Questions\Reports\DailyOperationsDashboardQuestionsSeeder;
use Database\Seeders\Questions\Reports\FertilityMatingPregnancyReportsQuestionsSeeder;
use Database\Seeders\Questions\Reports\GrowthWeightFatteningReportsQuestionsSeeder;
use Database\Seeders\Questions\Reports\HealthMortalityIsolationReportsQuestionsSeeder;
use Database\Seeders\Questions\Reports\HerdReadinessMovementReportsQuestionsSeeder;
use Database\Seeders\Questions\Reports\HousingOccupancyCapacityOperationsReportsQuestionsSeeder;
use Database\Seeders\Questions\Reports\OperationalTaskPerformanceReportsQuestionsSeeder;
use Database\Seeders\Questions\Reports\PedigreeReplacementReportsQuestionsSeeder;
use Database\Seeders\Questions\Reports\ProductiveAnimalPerformanceAnalysisQuestionsSeeder;
use Database\Seeders\Sections\QuestionnaireReportsSectionSeeder;
use Illuminate\Database\Seeder;

class QuestionnaireReportsQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(QuestionnaireReportsSectionSeeder::class);

        $this->call([
            DailyOperationsDashboardQuestionsSeeder::class,
            HerdReadinessMovementReportsQuestionsSeeder::class,
            FertilityMatingPregnancyReportsQuestionsSeeder::class,
            BirthLactationWeaningReportsQuestionsSeeder::class,
            GrowthWeightFatteningReportsQuestionsSeeder::class,
            HealthMortalityIsolationReportsQuestionsSeeder::class,
            ProductiveAnimalPerformanceAnalysisQuestionsSeeder::class,
            PedigreeReplacementReportsQuestionsSeeder::class,
            HousingOccupancyCapacityOperationsReportsQuestionsSeeder::class,
            OperationalTaskPerformanceReportsQuestionsSeeder::class,
        ]);
    }
}
