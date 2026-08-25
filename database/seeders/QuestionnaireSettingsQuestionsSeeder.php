<?php

namespace Database\Seeders;

use Database\Seeders\Questions\Settings\GeneralControlOverrideAuditQuestionsSeeder;
use Database\Seeders\Questions\Settings\HousingHerdReadinessRulesQuestionsSeeder;
use Database\Seeders\Questions\Settings\HousingSiteOperatingRulesQuestionsSeeder;
use Database\Seeders\Questions\Settings\SettingsScopeArchitectureQuestionsSeeder;
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
        ]);
    }
}
