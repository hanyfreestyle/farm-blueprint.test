<?php

namespace Database\Seeders\Sections;

use App\Models\QuestionnaireSection;
use Illuminate\Database\Seeder;
use RuntimeException;

class QuestionnaireFarmStructureSectionSeeder extends Seeder
{
    public function run(): void
    {
        $mainSection = QuestionnaireSection::query()->updateOrCreate(
            [
                'parent_id' => null,
                'name' => 'هيكل المزرعة',
            ],
            [
                'description' => <<<'MD'
يضم هذا القسم الكيانات الهيكلية الفعلية التي تمثل المزرعة ومواقع التشغيل داخلها. هذه الكيانات ليست قوائم Master Data مرجعية؛ بل تمثل البنية الفعلية التي ترتبط بها الحيوانات والحركات والسجلات التشغيلية تاريخيًا.
MD,
                'sort_order' => 2,
            ],
        );

        $subsections = [
            [
                'name' => 'بيانات المزرعة',
                'description' => <<<'MD'
تمثل المزرعة أعلى مستوى في الهيكل الفعلي للنظام، وترتبط بها العنابر وبقية المواقع التشغيلية التابعة لها.
MD,
                'sort_order' => 1,
            ],
            [
                'name' => 'بيانات العنبر',
                'description' => <<<'MD'
يمثل العنبر وحدة هيكلية داخل المزرعة تحتوي على البطاريات، وترتبط به إعدادات وتجهيزات وحالة تشغيلية مستقلة مع الحفاظ على علاقته بالمزرعة الأب.
MD,
                'sort_order' => 2,
            ],
            [
                'name' => 'بيانات البطارية',
                'description' => <<<'MD'
تمثل البطارية وحدة هيكلية فعلية داخل العنبر، ويحدد تكوينها الهيكلي عدد ومواقع الأقفاص التي يولدها النظام وتستمر هويتها في السجل التاريخي.
MD,
                'sort_order' => 3,
            ],
            [
                'name' => 'بيانات القفص / العين',
                'description' => <<<'MD'
القفص / العين هو الموقع الفعلي المباشر للحيوان داخل المزرعة. ينشأ من تكوين البطارية، وتكون له هوية ثابتة وسجل تشغيلي وتاريخ حركات وأحداث مستقل.
MD,
                'sort_order' => 4,
            ],
        ];

        foreach ($subsections as $subsection) {
            $matches = QuestionnaireSection::query()
                ->whereNotNull('parent_id')
                ->where('name', $subsection['name'])
                ->get();

            if ($matches->count() > 1) {
                throw new RuntimeException(
                    "Cannot seed subsection '{$subsection['name']}' safely because more than one existing subsection has the same name. Resolve duplicates before seeding."
                );
            }

            $section = $matches->first() ?? new QuestionnaireSection();

            // Migration-safe move: reuse the same subsection record and therefore
            // preserve its ID, questions, answers, reviews and other relationships.
            $section->parent_id = $mainSection->id;
            $section->fill([
                'name' => $subsection['name'],
                'description' => $subsection['description'],
                'sort_order' => $subsection['sort_order'],
            ]);
            $section->save();
        }
    }
}
