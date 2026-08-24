<?php

namespace Database\Seeders;

use App\Models\QuestionnaireSection;
use Database\Seeders\Sections\QuestionnaireAnimalHerdSectionSeeder;
use Database\Seeders\Sections\QuestionnaireFarmStructureSectionSeeder;
use Database\Seeders\Sections\QuestionnaireMasterDataSectionSeeder;
use Database\Seeders\Sections\QuestionnaireReportsSectionSeeder;
use Database\Seeders\Sections\QuestionnaireSettingsSectionSeeder;
use Database\Seeders\Sections\QuestionnaireWorkflowSectionSeeder;
use Illuminate\Database\Seeder;

class QuestionnaireSectionSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            QuestionnaireMasterDataSectionSeeder::class,
            QuestionnaireFarmStructureSectionSeeder::class,
            QuestionnaireAnimalHerdSectionSeeder::class,
            QuestionnaireWorkflowSectionSeeder::class,
            QuestionnaireReportsSectionSeeder::class,
            QuestionnaireSettingsSectionSeeder::class,
        ]);

        $mainSectionOrder = [
            'إدارة البيانات الأساسية' => 1,
            'هيكل المزرعة' => 2,
            'بيانات الحيوان وتكوين القطيع' => 3,
            'الحركات ودورة التشغيل الفعلية' => 4,
            'التقارير والتحليلات والتنبيهات ومؤشرات الأداء' => 5,
            'الإعدادات وقواعد التشغيل' => 6,
        ];

        foreach ($mainSectionOrder as $name => $sortOrder) {
            QuestionnaireSection::query()
                ->whereNull('parent_id')
                ->where('name', $name)
                ->update(['sort_order' => $sortOrder]);
        }
    }
}
