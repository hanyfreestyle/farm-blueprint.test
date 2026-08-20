<?php

namespace Database\Seeders;

use Database\Seeders\Sections\QuestionnaireHerdSetupSectionSeeder;
use Database\Seeders\Sections\QuestionnaireMasterDataSectionSeeder;
use Database\Seeders\Sections\QuestionnaireOperationSettingsSectionSeeder;
use Database\Seeders\Sections\QuestionnaireReportsSectionSeeder;
use Database\Seeders\Sections\QuestionnaireWorkflowSectionSeeder;
use Illuminate\Database\Seeder;

class QuestionnaireSectionSeeder extends Seeder {
  public function run(): void {
    $this->call([
      QuestionnaireMasterDataSectionSeeder::class,
      QuestionnaireOperationSettingsSectionSeeder::class,
      QuestionnaireHerdSetupSectionSeeder::class,
      QuestionnaireWorkflowSectionSeeder::class,
      QuestionnaireReportsSectionSeeder::class,
    ]);


  }
}
