<?php

namespace Database\Seeders\Questions\AnimalHerd;

use App\Enums\Questionnaire\QuestionDependencyOperator;
use App\Enums\Questionnaire\QuestionType;
use App\Models\QuestionnaireQuestion;
use App\Models\QuestionnaireSection;
use App\Services\Questionnaire\QuestionSeederSyncService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductionHerdGroupsQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $questions = [
                [
                    'seed_key' => 'production_herd.organization_methods',
                    'title' => 'ما طرق تنظيم أفراد القطيع الإنتاجي التي يجب أن يدعمها النظام؟',
                    'help_text' => 'حدد هل تتم إدارة أفراد القطيع بصورة فردية فقط، أم توجد أيضًا مجموعات إنتاجية لتنظيم العلاقة بين الذكور والإناث. المجموعة تنظيم تشغيلي للقطيع وليست سجل تلقيح بحد ذاتها.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 1,
                    'report_category' => 'organization_model',
                    'target_entity' => 'production_herd',
                    'options' => [
                        ['label' => 'إدارة أفراد القطيع الإنتاجي بصورة فردية دون اشتراط مجموعة', 'value' => 'individual_management'],
                        ['label' => 'استخدام مجموعات إنتاجية لتنظيم العلاقة بين الذكور والإناث', 'value' => 'production_groups'],
                    ],
                ],
                [
                    'seed_key' => 'production_group.record_fields',
                    'title' => 'ما البيانات والعلاقات التي يجب أن يحتوي عليها سجل المجموعة الإنتاجية؟',
                    'help_text' => 'حدد مكونات تعريف المجموعة نفسها. النسب المستهدفة للإناث لكل ذكر وقواعد الجاهزية لا تحفظ كبيانات ثابتة هنا؛ فهي قواعد تشغيل تراجع لاحقًا داخل Settings.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 2,
                    'report_category' => 'field',
                    'target_entity' => 'production_group',
                    'options' => [
                        ['label' => 'اسم المجموعة', 'value' => 'group_name'],
                        ['label' => 'كود المجموعة', 'value' => 'group_code'],
                        ['label' => 'الذكر الأساسي عند استخدام هذا النموذج', 'value' => 'primary_male'],
                        ['label' => 'الإناث المرتبطة بالمجموعة', 'value' => 'female_members'],
                        ['label' => 'تاريخ بدء / تكوين المجموعة', 'value' => 'formed_at'],
                        ['label' => 'الحالة الحالية للمجموعة', 'value' => 'status'],
                        ['label' => 'ملاحظات', 'value' => 'notes'],
                    ],
                ],
                [
                    'seed_key' => 'production_group.code_policy',
                    'title' => 'كيف يجب إنشاء كود المجموعة الإنتاجية وما نطاق عدم تكراره؟',
                    'help_text' => 'هذا السؤال يظهر عند اعتماد كود للمجموعة، ويجمع قرار طريقة إنشاء الكود مع نطاق فريدته حتى لا نفصل قرارًا واحدًا إلى عدة أسئلة صغيرة.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 3,
                    'report_category' => 'identification_rule',
                    'target_entity' => 'production_group',
                    'options' => [
                        ['label' => 'يدخله المستخدم ويكون فريدًا داخل المزرعة', 'value' => 'manual_unique_within_farm'],
                        ['label' => 'يولده النظام تلقائيًا ويكون فريدًا داخل المزرعة', 'value' => 'automatic_unique_within_farm'],
                        ['label' => 'يدخله المستخدم ويكون فريدًا على مستوى النظام', 'value' => 'manual_globally_unique'],
                        ['label' => 'يولده النظام تلقائيًا ويكون فريدًا على مستوى النظام', 'value' => 'automatic_globally_unique'],
                    ],
                ],
                [
                    'seed_key' => 'production_group.female_membership_model',
                    'title' => 'كيف يجب تنظيم عضوية الأنثى الإنتاجية داخل المجموعات؟',
                    'help_text' => 'المطلوب تحديد العلاقة التنظيمية الحالية فقط. نقل الأنثى فعليًا بين المجموعات وتاريخ هذا النقل يسجل كحركة تشغيلية، وليس تعديلًا صامتًا على البيانات.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 4,
                    'report_category' => 'relationship_rule',
                    'target_entity' => 'production_group_membership',
                    'options' => [
                        ['label' => 'العضوية اختيارية، وإذا انضمت الأنثى تكون في مجموعة نشطة واحدة كحد أقصى', 'value' => 'optional_max_one_active_group'],
                        ['label' => 'كل أنثى إنتاجية يجب أن تكون في مجموعة نشطة واحدة', 'value' => 'required_exactly_one_active_group'],
                        ['label' => 'يسمح للأنثى بالانضمام إلى أكثر من مجموعة نشطة في الوقت نفسه', 'value' => 'multiple_active_groups_allowed'],
                    ],
                ],
                [
                    'seed_key' => 'production_group.primary_male_model',
                    'title' => 'ما علاقة الذكر الأساسي بالمجموعة الإنتاجية؟',
                    'help_text' => 'التصور الحالي يستخدم نموذج ذكر مع مجموعة إناث، لكنه لا يفترض أن هذا النموذج إلزامي لكل مزرعة. هذا السؤال يحدد فقط شكل العلاقة التنظيمية.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 5,
                    'report_category' => 'relationship_rule',
                    'target_entity' => 'production_group',
                    'options' => [
                        ['label' => 'كل مجموعة نشطة يجب أن يكون لها ذكر أساسي واحد', 'value' => 'required_one_primary_male'],
                        ['label' => 'يمكن تحديد ذكر أساسي واحد، لكن يسمح أن تبقى المجموعة مؤقتًا بدونه', 'value' => 'optional_one_primary_male'],
                        ['label' => 'لا يوجد ذكر أساسي ثابت للمجموعة، ويتم اختيار الذكر عند العمليات الفعلية', 'value' => 'no_fixed_primary_male'],
                    ],
                ],
                [
                    'seed_key' => 'production_group.primary_male_assignment_scope',
                    'title' => 'هل يمكن أن يكون نفس الذكر الأساسي مرتبطًا بأكثر من مجموعة إنتاجية نشطة؟',
                    'help_text' => 'هذا يحدد نطاق العلاقة التنظيمية للذكر، ولا يحدد العدد المستهدف للإناث لكل ذكر أو حدود استخدامه التناسلي؛ هذه قواعد تشغيل مكانها Settings.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 6,
                    'report_category' => 'relationship_rule',
                    'target_entity' => 'production_group_membership',
                    'options' => [
                        ['label' => 'لا ينطبق لأن المجموعات لا تستخدم ذكرًا أساسيًا ثابتًا', 'value' => 'not_applicable'],
                        ['label' => 'الذكر الأساسي يرتبط بمجموعة نشطة واحدة كحد أقصى', 'value' => 'max_one_active_group'],
                        ['label' => 'يمكن أن يكون الذكر الأساسي مرتبطًا بأكثر من مجموعة نشطة', 'value' => 'multiple_active_groups_allowed'],
                    ],
                ],
                [
                    'seed_key' => 'production_group.alternate_male_model',
                    'title' => 'كيف يجب التعامل مع الذكر البديل للمجموعة عند الحاجة؟',
                    'help_text' => 'المرجع يطرح احتمال وجود ذكر بديل ثابت أو اختيار بديل وقت الحاجة. هذا السؤال يحسم نموذج التنظيم فقط؛ استخدام الذكر فعليًا في التلقيح يبقى حدثًا مستقلًا.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 7,
                    'report_category' => 'relationship_rule',
                    'target_entity' => 'production_group',
                    'options' => [
                        ['label' => 'لا ينطبق لأن المجموعة لا تستخدم ذكرًا أساسيًا ثابتًا', 'value' => 'not_applicable'],
                        ['label' => 'لا يوجد ذكر بديل ثابت، ويتم اختيار البديل وقت الحاجة', 'value' => 'choose_when_needed'],
                        ['label' => 'يمكن ربط ذكر بديل ثابت بالمجموعة بصورة اختيارية', 'value' => 'optional_fixed_alternate'],
                    ],
                ],
                [
                    'seed_key' => 'production_group.statuses',
                    'title' => 'ما الحالات التي يجب أن تدعمها دورة حياة المجموعة الإنتاجية؟',
                    'help_text' => 'هذه حالات المجموعة التنظيمية نفسها، وليست جاهزية الحيوانات أو نتيجة دورة إنتاج. تغيير الحالة الفعلي يجب أن يحافظ على التاريخ وفق سياسة المجموعة.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 8,
                    'report_category' => 'lifecycle_rule',
                    'target_entity' => 'production_group',
                    'options' => [
                        ['label' => 'نشطة', 'value' => 'active'],
                        ['label' => 'متوقفة', 'value' => 'stopped'],
                        ['label' => 'تم حلها / إنهاؤها', 'value' => 'dissolved'],
                    ],
                ],
                [
                    'seed_key' => 'production_group.assignment_vs_mating',
                    'title' => 'هل يجب اعتبار تخصيص الذكر والإناث داخل المجموعة تنظيمًا فقط دون إنشاء تلقيح أو إثبات أبوة تلقائيًا؟',
                    'help_text' => 'إذا كانت الإجابة نعم، فإن التلقيح الفعلي يسجل دائمًا الأنثى والذكر المستخدم والتاريخ بصورة مستقلة، ويأتي الأب من سجل التلقيح الفعلي لا من مجرد عضوية المجموعة.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 9,
                    'report_category' => 'integrity_rule',
                    'target_entity' => 'production_group',
                    'options' => [],
                ],
                [
                    'seed_key' => 'production_group.history_policy',
                    'title' => 'كيف يجب الاحتفاظ بتاريخ تغير تكوين المجموعة الإنتاجية؟',
                    'help_text' => 'المقصود حماية التاريخ عند تغيير الذكر الأساسي أو نقل أنثى أو حل المجموعة. الحدث الفعلي ينتمي إلى Workflow، بينما هذا السؤال يحدد هل يحتفظ النظام بتاريخ العلاقات أم بالقيم الحالية فقط.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 10,
                    'report_category' => 'history_rule',
                    'target_entity' => 'production_group_history',
                    'options' => [
                        ['label' => 'الاحتفاظ بالتكوين الحالي فقط', 'value' => 'current_composition_only'],
                        ['label' => 'الاحتفاظ بتاريخ كامل لتغير الذكر وعضوية الإناث وحالة المجموعة عبر الزمن', 'value' => 'full_effective_history'],
                    ],
                ],
            ];

            app(QuestionSeederSyncService::class)->sync(
                mainSectionName: 'بيانات الحيوان وتكوين القطيع',
                sectionName: 'تكوين القطيع الإنتاجي وتنظيم المجموعات',
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

        $organizationMethods = $questions->get('production_herd.organization_methods');

        if ($organizationMethods) {
            foreach ([
                'production_group.record_fields',
                'production_group.female_membership_model',
                'production_group.primary_male_model',
                'production_group.primary_male_assignment_scope',
                'production_group.alternate_male_model',
                'production_group.statuses',
                'production_group.assignment_vs_mating',
                'production_group.history_policy',
            ] as $dependentSeedKey) {
                $dependentQuestion = $questions->get($dependentSeedKey);

                if (! $dependentQuestion) {
                    continue;
                }

                $dependentQuestion->forceFill([
                    'depends_on_question_id' => $organizationMethods->id,
                    'dependency_operator' => QuestionDependencyOperator::CONTAINS,
                    'dependency_value' => 'production_groups',
                ])->save();
            }
        }

        $recordFields = $questions->get('production_group.record_fields');
        $codePolicy = $questions->get('production_group.code_policy');

        if ($recordFields && $codePolicy) {
            $codePolicy->forceFill([
                'depends_on_question_id' => $recordFields->id,
                'dependency_operator' => QuestionDependencyOperator::CONTAINS,
                'dependency_value' => 'group_code',
            ])->save();
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
            ->where('name', 'تكوين القطيع الإنتاجي وتنظيم المجموعات')
            ->first();
    }
}
