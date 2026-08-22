<?php

namespace Database\Seeders\Questions\FarmStructure;

use App\Enums\Questionnaire\QuestionDependencyOperator;
use App\Enums\Questionnaire\QuestionType;
use App\Models\QuestionnaireQuestion;
use App\Models\QuestionnaireSection;
use App\Services\Questionnaire\QuestionSeederSyncService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BreedDataQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $this->adoptLegacySeedKeys();

            $questions = [
                [
                    'seed_key' => 'breed.fields',
                    'title' => 'ما البيانات والعلاقات التي يجب أن يحتوي عليها ملف السلالة؟',
                    'help_text' => 'حدد البيانات والعلاقات التي يجب أن يدعمها تعريف السلالة. الأغراض الإنتاجية ومؤشرات السلالات معرفة كـMaster Data مستقلة، لذلك يتم ربط السلالة بها بدل إعادة تعريفها داخل هذا القسم.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 1,
                    'report_category' => 'field',
                    'target_entity' => 'breed',
                    'options' => [
                        ['label' => 'اسم السلالة بالعربية والإنجليزية', 'value' => 'name'],
                        ['label' => 'كود مختصر للسلالة', 'value' => 'code'],
                        ['label' => 'نوع السلالة', 'value' => 'breed_type'],
                        ['label' => 'الأغراض الإنتاجية', 'value' => 'production_purpose'],
                        ['label' => 'القيم المرجعية لمؤشرات السلالة', 'value' => 'reference_metrics'],
                        ['label' => 'وصف السلالة بالعربية والإنجليزية', 'value' => 'description'],
                        ['label' => 'ملاحظات', 'value' => 'notes'],
                        ['label' => 'الحالة', 'value' => 'status'],
                    ],
                ],
                [
                    'seed_key' => 'breed.required_fields',
                    'title' => 'أي من البيانات الأساسية للسلالة يجب أن تكون إلزامية عند إنشائها؟',
                    'help_text' => 'حدد الحد الأدنى من البيانات المباشرة التي لا يسمح النظام بإنشاء السلالة بدونها. إلزام الغرض الإنتاجي والقيم المرجعية تحسمه الأسئلة المتخصصة التالية حتى لا تختلط مرحلة الإنشاء بمرحلة الاستخدام.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 2,
                    'report_category' => 'rule',
                    'target_entity' => 'breed',
                    'options' => [
                        ['label' => 'اسم السلالة بالعربية والإنجليزية', 'value' => 'name'],
                        ['label' => 'كود مختصر للسلالة', 'value' => 'code'],
                        ['label' => 'نوع السلالة', 'value' => 'breed_type'],
                        ['label' => 'وصف السلالة بالعربية والإنجليزية', 'value' => 'description'],
                        ['label' => 'ملاحظات', 'value' => 'notes'],
                        ['label' => 'الحالة', 'value' => 'status'],
                    ],
                ],
                [
                    'seed_key' => 'breed.code_strategy',
                    'title' => 'كيف يجب التعامل مع الكود المختصر للسلالة؟',
                    'help_text' => 'حدد هل تحتاج السلالة إلى كود مختصر للاستخدام في التعريف والتقارير، ومن المسؤول عن إنشائه.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 3,
                    'report_category' => 'field_rule',
                    'target_entity' => 'breed',
                    'options' => [
                        ['label' => 'لا نحتاج كودًا مختصرًا', 'value' => 'not_required'],
                        ['label' => 'يتم إدخال الكود يدويًا', 'value' => 'manual'],
                        ['label' => 'يقوم النظام بتوليد الكود تلقائيًا', 'value' => 'automatic'],
                    ],
                ],
                [
                    'seed_key' => 'breed.code_unique_scope',
                    'title' => 'ما نطاق عدم تكرار كود السلالة؟',
                    'help_text' => 'حدد نطاق عدم التكرار إذا كان كود السلالة مستخدمًا. اختر «لا ينطبق» إذا كانت إجابة السؤال السابق تقرر عدم استخدام الكود.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 4,
                    'report_category' => 'rule',
                    'target_entity' => 'breed',
                    'options' => [
                        ['label' => 'لا ينطبق لأن الكود غير مستخدم', 'value' => 'not_applicable'],
                        ['label' => 'يجب أن يكون الكود فريدًا على مستوى النظام بالكامل', 'value' => 'global_unique'],
                        ['label' => 'يسمح بتكرار الكود', 'value' => 'duplicates_allowed'],
                    ],
                ],
                [
                    'seed_key' => 'breed.unique_name',
                    'title' => 'هل يجب منع تكرار اسم السلالة داخل النظام؟',
                    'help_text' => 'حدد هل يجب منع إنشاء أكثر من تعريف للسلالة يحمل نفس الاسم حتى لا تظهر سلالات مرجعية مكررة.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 5,
                    'report_category' => 'rule',
                    'target_entity' => 'breed',
                    'options' => [],
                ],
                [
                    'seed_key' => 'breed.types',
                    'title' => 'ما أنواع السلالات التي يجب أن يدعمها النظام؟',
                    'help_text' => 'حدد التصنيفات الأساسية التي يجب أن تكون متاحة عند تعريف السلالة. المرجع الوظيفي يذكر السلالة النقية والهجين وغير المحددة.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 6,
                    'report_category' => 'lookup_values',
                    'target_entity' => 'breed_type',
                    'options' => [
                        ['label' => 'نقية', 'value' => 'pure'],
                        ['label' => 'هجين', 'value' => 'hybrid'],
                        ['label' => 'غير محددة', 'value' => 'unknown'],
                    ],
                ],
                [
                    'seed_key' => 'breed.type_management',
                    'title' => 'هل أنواع السلالات ثابتة أم قابلة للإدارة؟',
                    'help_text' => 'حدد هل أنواع السلالات تمثل بقيم ثابتة داخل النظام أم بقائمة مرجعية يستطيع المدير إضافتها وتعديلها.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 7,
                    'report_category' => 'value_management',
                    'target_entity' => 'breed_type',
                    'options' => [
                        ['label' => 'قيم ثابتة داخل النظام', 'value' => 'fixed'],
                        ['label' => 'قابلة للإضافة والتعديل من لوحة التحكم', 'value' => 'managed'],
                    ],
                ],
                [
                    'seed_key' => 'breed.production_purpose_requirement',
                    'title' => 'متى يجب تحديد الغرض الإنتاجي للسلالة؟',
                    'help_text' => 'الأغراض الإنتاجية معرفة مسبقًا كـMaster Data مستقلة؛ المطلوب هنا تحديد توقيت إلزام ربط السلالة بغرض إنتاجي دون إعادة تعريف قائمة الأغراض.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 8,
                    'report_category' => 'relationship_rule',
                    'target_entity' => 'breed_production_purpose',
                    'options' => [
                        ['label' => 'إلزامي عند إنشاء السلالة', 'value' => 'required_on_create'],
                        ['label' => 'يمكن إنشاء السلالة بدونه ولكن يجب تحديده قبل استخدامها في سجلات الحيوانات', 'value' => 'required_before_use'],
                        ['label' => 'اختياري دائمًا', 'value' => 'optional'],
                    ],
                ],
                [
                    'seed_key' => 'breed.multiple_production_purposes',
                    'title' => 'هل يمكن ربط السلالة بأكثر من غرض إنتاجي في نفس الوقت؟',
                    'help_text' => 'يحدد هذا القرار هل العلاقة النهائية Breed → ProductionPurpose أم Breed ↔ ProductionPurposes، دون إنشاء قيمة مركبة باسم «متعدد الأغراض» من تلقاء نفسها.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 9,
                    'report_category' => 'relationship_rule',
                    'target_entity' => 'breed_production_purpose',
                    'options' => [],
                ],
                [
                    'seed_key' => 'breed.reference_metrics_usage',
                    'title' => 'هل يجب أن يدعم ملف السلالة قيمًا مرجعية لمؤشرات السلالات؟',
                    'help_text' => 'مؤشرات السلالات معرفة مسبقًا كـMaster Data مستقلة. إذا كانت الإجابة نعم، يدعم النظام ربط السلالة بالمؤشرات وتخزين قيمة مرجعية خاصة بالسلالة دون خلطها بالأداء الفعلي للمزرعة.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 10,
                    'report_category' => 'relationship_rule',
                    'target_entity' => 'breed_metric_value',
                    'options' => [],
                ],
                [
                    'seed_key' => 'breed.metric_assignment_strategy',
                    'title' => 'كيف يتم تحديد المؤشرات المرجعية المستخدمة مع كل سلالة؟',
                    'help_text' => 'حدد هل كل المؤشرات النشطة تكون متاحة لكل سلالة، أم يتم اختيار مجموعة المؤشرات المناسبة لكل سلالة بصورة مستقلة.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 11,
                    'report_category' => 'relationship_rule',
                    'target_entity' => 'breed_metric_value',
                    'options' => [
                        ['label' => 'كل مؤشرات السلالات النشطة تكون متاحة لكل سلالة', 'value' => 'all_active_metrics'],
                        ['label' => 'يتم اختيار المؤشرات المناسبة لكل سلالة فقط', 'value' => 'selected_per_breed'],
                    ],
                ],
                [
                    'seed_key' => 'breed.metric_value_shape',
                    'title' => 'ما الشكل الذي يجب أن تدعمه القيمة المرجعية لمؤشر السلالة؟',
                    'help_text' => 'حدد شكل القيمة المرجعية المرتبطة بسلالة ومؤشر معين. هذا السؤال لا يحدد أرقامًا علمية أو قيمًا افتراضية؛ بل يحدد بنية القيمة فقط.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 12,
                    'report_category' => 'field_rule',
                    'target_entity' => 'breed_metric_value',
                    'options' => [
                        ['label' => 'قيمة رقمية واحدة فقط', 'value' => 'single_value'],
                        ['label' => 'نطاق من حد أدنى إلى حد أقصى فقط', 'value' => 'range_only'],
                        ['label' => 'يدعم النظام قيمة واحدة أو نطاقًا حسب المؤشر', 'value' => 'single_or_range'],
                    ],
                ],
                [
                    'seed_key' => 'breed.metric_value_required',
                    'title' => 'عند إسناد مؤشر إلى سلالة، هل يجب إدخال قيمة مرجعية له؟',
                    'help_text' => 'حدد هل مجرد ربط المؤشر بالسلالة يتطلب وجود قيمة مرجعية، أم يمكن إنشاء العلاقة أولًا واستكمال القيمة لاحقًا.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 13,
                    'report_category' => 'rule',
                    'target_entity' => 'breed_metric_value',
                    'options' => [],
                ],
                [
                    'seed_key' => 'breed.hybrid_origin_strategy',
                    'title' => 'إذا كان النظام يدعم السلالات الهجينة، كيف يجب تسجيل أصل التهجين؟',
                    'help_text' => 'هذا السؤال يخص تعريف السلالة الهجينة نفسها ولا يستبدل نسب الحيوان الفعلي. الأب والأم والأجداد تظل بيانات Pedigree مستقلة في سجل الحيوان عندما تكون متاحة.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 14,
                    'report_category' => 'relationship_rule',
                    'target_entity' => 'breed',
                    'options' => [
                        ['label' => 'لا نحتاج بيانات إضافية لأصل التهجين داخل تعريف السلالة', 'value' => 'not_recorded'],
                        ['label' => 'نسجل وصفًا نصيًا لمصدر أو أصل التهجين فقط', 'value' => 'text_only'],
                        ['label' => 'نربط السلالة الهجينة بسلالة أصلية واحدة أو أكثر', 'value' => 'linked_source_breeds'],
                        ['label' => 'نربطها بسلالات أصلية مع دعم وصف وملاحظات إضافية', 'value' => 'linked_source_breeds_with_notes'],
                    ],
                ],
                [
                    'seed_key' => 'breed.statuses',
                    'title' => 'ما الحالات التي يمكن أن تكون عليها السلالة داخل النظام؟',
                    'help_text' => 'حدد حالات Lifecycle التي يحتاج النظام إلى تمثيلها للسلالة، مع الفصل بين حالة التعريف المرجعي واستخدام الحيوانات المسجلة تاريخيًا.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 15,
                    'report_category' => 'lookup_values',
                    'target_entity' => 'breed_status',
                    'options' => [
                        ['label' => 'نشطة', 'value' => 'active'],
                        ['label' => 'غير نشطة', 'value' => 'inactive'],
                    ],
                ],
                [
                    'seed_key' => 'breed.status_management',
                    'title' => 'هل حالات السلالات ثابتة أم قابلة للإدارة؟',
                    'help_text' => 'حدد هل حالات السلالة قيم ثابتة داخل النظام أم قائمة يستطيع المدير إضافتها وتعديلها.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 16,
                    'report_category' => 'value_management',
                    'target_entity' => 'breed_status',
                    'options' => [
                        ['label' => 'قيم ثابتة داخل النظام', 'value' => 'fixed'],
                        ['label' => 'قابلة للإضافة والتعديل من لوحة التحكم', 'value' => 'managed'],
                    ],
                ],
                [
                    'seed_key' => 'breed.retirement_policy',
                    'title' => 'كيف يجب التعامل مع سلالة لم نعد نريد استخدامها؟',
                    'help_text' => 'حدد سياسة إخراج السلالة من الاستخدام المستقبلي مع الحفاظ على ارتباط الحيوانات والسجلات التاريخية بها.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 17,
                    'report_category' => 'lifecycle_rule',
                    'target_entity' => 'breed',
                    'options' => [
                        ['label' => 'لا يسمح بحذفها ويتم تحويلها إلى غير نشطة فقط', 'value' => 'disable_only'],
                        ['label' => 'يسمح بحذفها إذا لم تستخدم سابقًا، وإلا يتم تحويلها إلى غير نشطة', 'value' => 'delete_if_unused_otherwise_disable'],
                        ['label' => 'يسمح بالحذف المنطقي Soft Delete مع الاحتفاظ بالسجل التاريخي', 'value' => 'soft_delete'],
                    ],
                ],
                [
                    'seed_key' => 'breed.additional_requirements',
                    'title' => 'هل توجد بيانات أو قواعد أخرى تخص السلالات؟',
                    'help_text' => 'اكتب أي ملاحظة أو متطلب إضافي يخص تعريف السلالات أو علاقاتها ولم تغطه الأسئلة السابقة. هذا السؤال مخصص لمرحلة الدراسة فقط.',
                    'type' => QuestionType::TEXTAREA,
                    'is_required' => false,
                    'sort_order' => 18,
                    'report_category' => 'manual_review',
                    'target_entity' => 'breed',
                    'options' => [],
                ],
            ];

            app(QuestionSeederSyncService::class)->sync(
                mainSectionName: 'إدارة البيانات الأساسية',
                sectionName: 'بيانات السلالات',
                questions: $questions,
                prune: true,
                preserveAnswers: true,
            );

            $this->configureDependencies();
        });
    }

    /**
     * Reuse legacy questions only when their semantic meaning and answer shape are unchanged.
     *
     * Questions that were split, moved to another Master Data subsection, or changed in
     * meaning are intentionally not adopted. With preserveAnswers=true the sync will
     * refuse to remove any such legacy question if it already has an answer.
     */
    private function adoptLegacySeedKeys(): void
    {
        $section = $this->resolveBreedSection();

        if (! $section) {
            return;
        }

        $legacyMap = [
            'كيف تريد التعامل مع الكود المختصر للسلالة؟' => 'breed.code_strategy',
            'هل أنواع السلالات ثابتة أم قابلة للإدارة؟' => 'breed.type_management',
            'هل يمكن ربط السلالة بأكثر من غرض إنتاجي في نفس الوقت؟' => 'breed.multiple_production_purposes',
            'ما الحالات التي يمكن أن تكون عليها السلالة داخل النظام؟' => 'breed.statuses',
            'هل حالات السلالات ثابتة أم قابلة للإدارة؟' => 'breed.status_management',
            'هل توجد بيانات أو تصنيفات أو متطلبات أخرى تخص السلالات ولم نتطرق إليها؟' => 'breed.additional_requirements',
        ];

        foreach ($legacyMap as $legacyTitle => $seedKey) {
            $alreadyAdopted = QuestionnaireQuestion::query()
                ->where('section_id', $section->id)
                ->where('seed_key', $seedKey)
                ->exists();

            if ($alreadyAdopted) {
                continue;
            }

            $legacyQuestion = QuestionnaireQuestion::query()
                ->where('section_id', $section->id)
                ->whereNull('seed_key')
                ->where('title', $legacyTitle)
                ->first();

            if (! $legacyQuestion) {
                continue;
            }

            $legacyQuestion->seed_key = $seedKey;
            $legacyQuestion->save();
        }
    }

    private function configureDependencies(): void
    {
        $section = $this->resolveBreedSection();

        if (! $section) {
            return;
        }

        $questions = QuestionnaireQuestion::query()
            ->where('section_id', $section->id)
            ->whereNotNull('seed_key')
            ->get()
            ->keyBy('seed_key');

        $metricsUsage = $questions->get('breed.reference_metrics_usage');

        foreach ([
            'breed.metric_assignment_strategy',
            'breed.metric_value_shape',
            'breed.metric_value_required',
        ] as $dependentSeedKey) {
            $dependentQuestion = $questions->get($dependentSeedKey);

            if (! $dependentQuestion || ! $metricsUsage) {
                continue;
            }

            $dependentQuestion->forceFill([
                'depends_on_question_id' => $metricsUsage->id,
                'dependency_operator' => QuestionDependencyOperator::EQUALS,
                'dependency_value' => '1',
            ])->save();
        }

        $breedTypes = $questions->get('breed.types');
        $hybridOrigin = $questions->get('breed.hybrid_origin_strategy');

        if ($breedTypes && $hybridOrigin) {
            $hybridOrigin->forceFill([
                'depends_on_question_id' => $breedTypes->id,
                'dependency_operator' => QuestionDependencyOperator::CONTAINS,
                'dependency_value' => 'hybrid',
            ])->save();
        }
    }

    private function resolveBreedSection(): ?QuestionnaireSection
    {
        $mainSection = QuestionnaireSection::query()
            ->whereNull('parent_id')
            ->where('name', 'إدارة البيانات الأساسية')
            ->first();

        if (! $mainSection) {
            return null;
        }

        return QuestionnaireSection::query()
            ->where('parent_id', $mainSection->id)
            ->where('name', 'بيانات السلالات')
            ->first();
    }
}
