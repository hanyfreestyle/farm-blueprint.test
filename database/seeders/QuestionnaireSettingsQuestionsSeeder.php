<?php

namespace Database\Seeders;

use Database\Seeders\Questions\Settings\BirthLactationRematingRulesQuestionsSeeder;
use Database\Seeders\Questions\Settings\FatteningSaleReadinessRulesQuestionsSeeder;
use Database\Seeders\Questions\Settings\GeneralControlOverrideAuditQuestionsSeeder;
use Database\Seeders\Questions\Settings\GrowthWeightSortingReplacementRulesQuestionsSeeder;
use Database\Seeders\Questions\Settings\HousingHerdReadinessRulesQuestionsSeeder;
use Database\Seeders\Questions\Settings\HousingSiteOperatingRulesQuestionsSeeder;
use Database\Seeders\Questions\Settings\MatingFertilityReadinessRulesQuestionsSeeder;
use Database\Seeders\Questions\Settings\PregnancyCheckGestationBirthPreparationRulesQuestionsSeeder;
use Database\Seeders\Questions\Settings\SettingsScopeArchitectureQuestionsSeeder;
use Database\Seeders\Questions\Settings\WeaningIndividualTrackingRulesQuestionsSeeder;
use Database\Seeders\Sections\QuestionnaireSettingsSectionSeeder;
use Illuminate\Database\Seeder;

class QuestionnaireSettingsQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(QuestionnaireSettingsSectionSeeder::class);

        $this->call([
            SettingsScopeArchitectureQuestionsSeeder::class,
            GeneralControlOverrideAuditQuestionsSeeder::class,
            HousingSiteOperatingRulesQuestionsSeeder::class,
            HousingHerdReadinessRulesQuestionsSeeder::class,
            MatingFertilityReadinessRulesQuestionsSeeder::class,
            PregnancyCheckGestationBirthPreparationRulesQuestionsSeeder::class,
            BirthLactationRematingRulesQuestionsSeeder::class,
            WeaningIndividualTrackingRulesQuestionsSeeder::class,
            GrowthWeightSortingReplacementRulesQuestionsSeeder::class,
            FatteningSaleReadinessRulesQuestionsSeeder::class,
        ]);
    }
}
