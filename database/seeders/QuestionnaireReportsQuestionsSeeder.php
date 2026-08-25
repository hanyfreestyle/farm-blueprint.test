<?php

namespace Database\Seeders;

use Database\Seeders\Sections\QuestionnaireReportsSectionSeeder;
use Illuminate\Database\Seeder;

class QuestionnaireReportsQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(QuestionnaireReportsSectionSeeder::class);

        // Question seeders are added here progressively in section order (5.1 → 5.16).
    }
}
