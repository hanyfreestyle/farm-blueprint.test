<?php

namespace Database\Seeders\Questions\AnimalHerd;

use App\Enums\Questionnaire\QuestionDependencyOperator;
use App\Enums\Questionnaire\QuestionType;
use App\Models\QuestionnaireQuestion;
use App\Models\QuestionnaireSection;
use App\Services\Questionnaire\QuestionSeederSyncService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AnimalSourceQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $questions = [
                [
                    'seed_key' => 'animal.source_origin_categories',
                    'title' => 'ما فئات مصدر الحيوان التي يجب أن يدعمها النظام عند بداية السجل؟',
                    'help_text' => 'حدد الفئات الأساسية التي يجب أن يستطيع النظام التمييز بينها عند تحديد أصل سجل الحيوان. هذا السؤال يصف مصدر الحيوان ولا يسجل حدث الاستقبال أو التسكين الفعلي.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 1,
                    'report_category' => 'source_classification',
                    'target_entity' => 'animal_source',
                    'options' => [
                        ['label' => 'مولود داخل المزرعة', 'value' => 'born_in_farm'],
                        ['label' => 'قادم من خارج المزرعة الحالية', 'value' => 'outside_current_farm'],
                    ],
                ],
                [
                    'seed_key' => 'animal.outside_source_types',
                    'title' => 'عندما يكون الحيوان قادمًا من خارج المزرعة الحالية، ما أنواع المصدر التي يجب تمييزها؟',
                    'help_text' => 'حدد أنواع المصدر الخارجي التي يجب أن تظهر كتصنيف واضح عند بداية سجل الحيوان. تفاصيل عملية الوصول والفحص والتسكين نفسها تنتمي إلى Workflow.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 2,
                    'report_category' => 'source_classification',
                    'target_entity' => 'animal_source',
                    'options' => [
                        ['label' => 'شراء خارجي', 'value' => 'external_purchase'],
                        ['label' => 'نقل من مزرعة أخرى', 'value' => 'inter_farm_transfer'],
                        ['label' => 'مصدر آخر', 'value' => 'other'],
                    ],
                ],
                [
                    'seed_key' => 'animal.internal_source_derivation',
                    'title' => 'كيف يجب تحديد مصدر الحيوان المولود داخل المزرعة؟',
                    'help_text' => 'الحيوان الناتج داخل المزرعة يكون له تاريخ سابق داخل النظام من الولادة والبطن. المطلوب تحديد هل يستنتج النظام هذا المصدر من السجل أم يطلب إدخاله يدويًا مرة أخرى.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 3,
                    'report_category' => 'derivation_rule',
                    'target_entity' => 'animal_source',
                    'options' => [
                        ['label' => 'يستنتجه النظام تلقائيًا من سجل الولادة / البطن', 'value' => 'automatic_from_birth_record'],
                        ['label' => 'يحدده المستخدم يدويًا', 'value' => 'manual'],
                    ],
                ],
                [
                    'seed_key' => 'animal.outside_source_fields',
                    'title' => 'ما بيانات المصدر التي يجب الاحتفاظ بها للحيوان القادم من خارج المزرعة؟',
                    'help_text' => 'حدد البيانات التي تصف الجهة أو المصدر السابق للحيوان. تاريخ الدخول الفعلي لا يكرر هنا؛ فهو ينتج من حدث الاستقبال في Workflow.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 4,
                    'report_category' => 'field',
                    'target_entity' => 'animal_source',
                    'options' => [
                        ['label' => 'المزرعة / المربي / الجهة المصدر', 'value' => 'source_party'],
                        ['label' => 'كود الحيوان لدى المصدر', 'value' => 'source_animal_code'],
                        ['label' => 'ملاحظات عن المصدر', 'value' => 'source_notes'],
                    ],
                ],
                [
                    'seed_key' => 'animal.interfarm_source_reference',
                    'title' => 'عند النقل بين مزرعتين يديرهما نفس النظام، هل يجب ربط المصدر بالمزرعة الأصلية المسجلة؟',
                    'help_text' => 'هذا القرار يحدد هل يستخدم النظام علاقة مباشرة بالمزرعة الأصلية عندما تكون معروفة داخله، بدل الاكتفاء باسم مصدر نصي فقط.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 5,
                    'report_category' => 'relationship_rule',
                    'target_entity' => 'animal_source_farm',
                    'options' => [],
                ],
                [
                    'seed_key' => 'animal.pre_entry_history_policy',
                    'title' => 'كيف نتعامل مع معلومات موثوقة عن الحيوان ترجع إلى ما قبل دخوله المزرعة؟',
                    'help_text' => 'لا يتم اختراع تاريخ غير معروف. المطلوب تحديد هل يبدأ السجل من الدخول فقط، أم يسمح بإضافة معلومات سابقة عندما تكون موثوقة مع تمييزها بوضوح عن الأحداث المسجلة داخل المزرعة.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 6,
                    'report_category' => 'history_rule',
                    'target_entity' => 'animal_history',
                    'options' => [
                        ['label' => 'يبدأ التاريخ من حدث الدخول فقط', 'value' => 'start_from_entry'],
                        ['label' => 'يسمح بتسجيل معلومات سابقة موثوقة مع تمييزها بأنها سابقة للدخول', 'value' => 'allow_trustworthy_pre_entry_history'],
                    ],
                ],
                [
                    'seed_key' => 'animal.other_source_description_required',
                    'title' => 'عند اختيار «مصدر آخر»، هل يجب إلزام المستخدم بتوضيح المصدر؟',
                    'help_text' => 'يمنع هذا القرار أن تصبح قيمة «مصدر آخر» غير قابلة للتفسير لاحقًا في المراجعة والتقارير.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 7,
                    'report_category' => 'validation_rule',
                    'target_entity' => 'animal_source',
                    'options' => [],
                ],
            ];

            app(QuestionSeederSyncService::class)->sync(
                mainSectionName: 'بيانات الحيوان وتكوين القطيع',
                sectionName: 'مصدر الحيوان وبداية السجل',
                questions: $questions,
                prune: true,
                preserveAnswers: true,
            );

            $this->configureDependencies();
        });
    }

    private function configureDependencies(): void
    {
        $section = $this->resolveSection();

        if (! $section) {
            return;
        }

        $questions = QuestionnaireQuestion::query()
            ->where('section_id', $section->id)
            ->whereNotNull('seed_key')
            ->get()
            ->keyBy('seed_key');

        $sourceCategories = $questions->get('animal.source_origin_categories');
        $outsideSourceTypes = $questions->get('animal.outside_source_types');

        if ($sourceCategories) {
            foreach ([
                'animal.outside_source_types' => 'outside_current_farm',
                'animal.outside_source_fields' => 'outside_current_farm',
                'animal.pre_entry_history_policy' => 'outside_current_farm',
                'animal.internal_source_derivation' => 'born_in_farm',
            ] as $dependentSeedKey => $dependencyValue) {
                $dependentQuestion = $questions->get($dependentSeedKey);

                if (! $dependentQuestion) {
                    continue;
                }

                $dependentQuestion->forceFill([
                    'depends_on_question_id' => $sourceCategories->id,
                    'dependency_operator' => QuestionDependencyOperator::CONTAINS,
                    'dependency_value' => $dependencyValue,
                ])->save();
            }
        }

        if ($outsideSourceTypes) {
            foreach ([
                'animal.interfarm_source_reference' => 'inter_farm_transfer',
                'animal.other_source_description_required' => 'other',
            ] as $dependentSeedKey => $dependencyValue) {
                $dependentQuestion = $questions->get($dependentSeedKey);

                if (! $dependentQuestion) {
                    continue;
                }

                $dependentQuestion->forceFill([
                    'depends_on_question_id' => $outsideSourceTypes->id,
                    'dependency_operator' => QuestionDependencyOperator::CONTAINS,
                    'dependency_value' => $dependencyValue,
                ])->save();
            }
        }
    }

    private function resolveSection(): ?QuestionnaireSection
    {
        $mainSection = QuestionnaireSection::query()
            ->whereNull('parent_id')
            ->where('name', 'بيانات الحيوان وتكوين القطيع')
            ->first();

        if (! $mainSection) {
            return null;
        }

        return QuestionnaireSection::query()
            ->where('parent_id', $mainSection->id)
            ->where('name', 'مصدر الحيوان وبداية السجل')
            ->first();
    }
}
