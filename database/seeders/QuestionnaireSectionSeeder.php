<?php

namespace Database\Seeders;

use App\Models\QuestionnaireSection;
use Database\Seeders\Sections\QuestionnaireFarmStructureSectionSeeder;
use Database\Seeders\Sections\QuestionnaireHerdSetupSectionSeeder;
use Database\Seeders\Sections\QuestionnaireMasterDataSectionSeeder;
use Database\Seeders\Sections\QuestionnaireOperationSettingsSectionSeeder;
use Database\Seeders\Sections\QuestionnaireReportsSectionSeeder;
use Database\Seeders\Sections\QuestionnaireWorkflowSectionSeeder;
use Illuminate\Database\Seeder;

class QuestionnaireSectionSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            QuestionnaireMasterDataSectionSeeder::class,
            QuestionnaireFarmStructureSectionSeeder::class,
            QuestionnaireOperationSettingsSectionSeeder::class,
            QuestionnaireHerdSetupSectionSeeder::class,
            QuestionnaireWorkflowSectionSeeder::class,
            QuestionnaireReportsSectionSeeder::class,
        ]);

        $mainSectionOrder = [
            'إدارة البيانات الأساسية' => 1,
            'هيكل المزرعة' => 2,
            'إعدادات التشغيل ودورة الإنتاج' => 3,
            'تكوين وإدخال القطيع' => 4,
            'الحركات ودورة التشغيل الفعلية' => 5,
            'التقارير والإشعارات ومؤشرات الأداء' => 6,
        ];

        foreach ($mainSectionOrder as $name => $sortOrder) {
            QuestionnaireSection::query()
                ->whereNull('parent_id')
                ->where('name', $name)
                ->update(['sort_order' => $sortOrder]);
        }
    }
}
