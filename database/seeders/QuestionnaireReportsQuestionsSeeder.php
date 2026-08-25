<?php

namespace Database\Seeders;

use Database\Seeders\Questions\Reports\DailyOperationsDashboardQuestionsSeeder;
use Database\Seeders\Sections\QuestionnaireReportsSectionSeeder;
use Illuminate\Database\Seeder;

class QuestionnaireReportsQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(QuestionnaireReportsSectionSeeder::class);

        $this->call([
            DailyOperationsDashboardQuestionsSeeder::class,
        ]);
    }
}
