<?php

namespace Database\Seeders\Questions\Workflow;

use App\Enums\Questionnaire\QuestionDependencyOperator;
use App\Enums\Questionnaire\QuestionType;
use App\Models\QuestionnaireQuestion;
use App\Models\QuestionnaireSection;
use App\Services\Questionnaire\QuestionSeederSyncService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GrowthSortingEvaluationQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $questions = [
                [
                    'seed_key' => 'growth_sorting.program_entry_model',
                    'title' => 'كيف يجب أن يبدأ مسار النمو والفرز الفردي بعد تنفيذ الفطام؟',
                    'help_text' => 'بعد الفطام يصبح كل أرنب فردًا مستقلًا ويبدأ مسار النمو والفرز. المطلوب حسم هل ينتقل الحيوان تلقائيًا إلى هذا المسار من حدث الفطام أم يحتاج إجراء تشغيل مستقل. مواعيد المراحل نفسها تبقى في Settings.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 1,
                    'report_category' => 'workflow_rule',
                    'target_entity' => 'growth_tracking',
                    'options' => [
                        ['label' => 'يبدأ مسار النمو والفرز تلقائيًا عند نجاح الفطام وإنشاء السجل الفردي', 'value' => 'auto_start_after_successful_weaning'],
                        ['label' => 'يظل الحيوان مفطومًا ثم يدخله المستخدم إلى مسار النمو بإجراء مستقل', 'value' => 'explicit_growth_program_entry'],
                    ],
                ],
                [
                    'seed_key' => 'growth_sorting.stage_reference_model',
                    'title' => 'كيف يجب تحديد مرحلة الفرز أو التقييم التي نُفذت عند تسجيل الحدث؟',
                    'help_text' => 'الأعمار المذكورة في التصور مثل 45 يومًا و70 يومًا و3 أشهر أمثلة وليست قيمًا ثابتة. هذا السؤال يحسم كيف يشير حدث التقييم الفعلي إلى المرحلة، بينما تعريف المراحل وأعمارها ومعاييرها القابلة للضبط يبقى في Settings.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 2,
                    'report_category' => 'relationship_rule',
                    'target_entity' => 'growth_evaluation',
                    'options' => [
                        ['label' => 'يرتبط الحدث بمرحلة فرز معرفة في إعدادات المزرعة', 'value' => 'reference_configured_sorting_stage'],
                        ['label' => 'توجد أنواع مراحل ثابتة داخل Workflow لا تتغير', 'value' => 'fixed_workflow_stage_types'],
                        ['label' => 'يسجل اسم المرحلة كنص حر مع كل تقييم', 'value' => 'free_form_stage_label'],
                    ],
                ],
                [
                    'seed_key' => 'growth_sorting.evaluation_history_model',
                    'title' => 'كيف يجب حفظ تاريخ عمليات الفرز وإعادة التقييم لنفس الحيوان؟',
                    'help_text' => 'التصور يؤكد أن تغير القرار في مرحلة لاحقة لا يمسح نتيجة مرحلة سابقة. المطلوب حسم نموذج التاريخ بحيث يمكن معرفة ماذا كان تقييم الحيوان في كل نقطة زمنية.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 3,
                    'report_category' => 'history_rule',
                    'target_entity' => 'growth_evaluation',
                    'options' => [
                        ['label' => 'كل فرز أو إعادة تقييم سجل مستقل مع إمكانية الربط بالتقييم السابق', 'value' => 'independent_evaluation_events_with_links'],
                        ['label' => 'سجل تقييم حالي واحد مع سجل تدقيق كامل لكل تغيير', 'value' => 'single_current_record_with_full_audit'],
                        ['label' => 'تستبدل النتيجة القديمة بالنتيجة الأحدث', 'value' => 'overwrite_previous_evaluation'],
                    ],
                ],
                [
                    'seed_key' => 'growth_sorting.evaluation_record_fields',
                    'title' => 'ما البيانات التي يجب أن يحتفظ بها سجل الفرز أو التقييم الفعلي؟',
                    'help_text' => 'السجل يصف ما تم تقييمه فعليًا في تلك اللحظة. الأوزان نفسها تظل سجلات Canonical في 4.3، بينما يمكن لحدث التقييم الاحتفاظ بمراجعها أو بالسياق الذي استُخدم في القرار.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 4,
                    'report_category' => 'field',
                    'target_entity' => 'growth_evaluation',
                    'options' => [
                        ['label' => 'الحيوان', 'value' => 'animal'],
                        ['label' => 'مرحلة الفرز / التقييم', 'value' => 'sorting_stage'],
                        ['label' => 'تاريخ / وقت التقييم الفعلي', 'value' => 'evaluated_at'],
                        ['label' => 'العمر وقت التقييم', 'value' => 'age_at_evaluation'],
                        ['label' => 'مرجع الوزن الحالي المستخدم في التقييم', 'value' => 'current_weight_reference'],
                        ['label' => 'مراجع الأوزان السابقة المهمة للتقييم', 'value' => 'previous_weight_references'],
                        ['label' => 'مرجع / لقطة مؤشرات النمو المستخدمة', 'value' => 'growth_metrics_context'],
                        ['label' => 'السياق الصحي المستخدم في التقييم', 'value' => 'health_context'],
                        ['label' => 'سياق النسب أو الأصل المستخدم في التقييم', 'value' => 'pedigree_context'],
                        ['label' => 'مرجع التقييم السابق عند وجوده', 'value' => 'previous_evaluation_reference'],
                        ['label' => 'المستخدم / منفذ التقييم', 'value' => 'performed_by'],
                        ['label' => 'ملاحظات', 'value' => 'notes'],
                    ],
                ],
                [
                    'seed_key' => 'growth_sorting.evaluation_context_sources',
                    'title' => 'ما المعلومات التي يجب أن يستطيع المسؤول رؤيتها أو الرجوع إليها أثناء الفرز وإعادة التقييم؟',
                    'help_text' => 'المصدر يعرض التقييم باعتباره قرارًا يستفيد من الوزن والنمو والصحة والنسب ونتائج المراحل السابقة، وقد يستفيد أيضًا من أداء الأب والأم والبطن. اختيار الأوزان المستهدفة وحدود القبول ودرجة تأثير كل عنصر يبقى في Settings.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 5,
                    'report_category' => 'workflow_context',
                    'target_entity' => 'growth_evaluation',
                    'options' => [
                        ['label' => 'وزن الفطام', 'value' => 'weaning_weight'],
                        ['label' => 'آخر وزن والأوزان المسجلة في المراحل السابقة', 'value' => 'weight_history'],
                        ['label' => 'مؤشرات النمو المحسوبة من سجل الأوزان', 'value' => 'derived_growth_metrics'],
                        ['label' => 'الجنس', 'value' => 'sex'],
                        ['label' => 'الحالة / السياق الصحي الحالي', 'value' => 'health_context'],
                        ['label' => 'النسب والبطن الأصلية', 'value' => 'pedigree_and_litter_origin'],
                        ['label' => 'أداء الأب والأم عند توفر مؤشرات قابلة للاستخدام', 'value' => 'parent_performance_context'],
                        ['label' => 'أداء البطن أو مقارنة الإخوة عند توفرها', 'value' => 'litter_sibling_context'],
                        ['label' => 'نتائج الفرز والتقييم السابقة', 'value' => 'previous_evaluations'],
                    ],
                ],
                [
                    'seed_key' => 'growth_sorting.derived_growth_metric_model',
                    'title' => 'كيف يجب التعامل مع مؤشرات النمو المحسوبة عند استخدامها داخل عملية الفرز؟',
                    'help_text' => 'مثل الزيادة منذ آخر وزن أو منذ الفطام ومتوسط الزيادة اليومية. مصدرها الحقيقي هو سجلات الوزن في 4.3؛ المطلوب حسم هل يعاد حسابها عند العرض فقط أم نحفظ لقطة لما كان ظاهرًا وقت التقييم لأغراض التاريخ والتدقيق.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 6,
                    'report_category' => 'derivation_rule',
                    'target_entity' => 'growth_evaluation',
                    'options' => [
                        ['label' => 'تحسب دائمًا من سجل الأوزان عند العرض ولا تخزن داخل التقييم', 'value' => 'derive_live_from_weight_history'],
                        ['label' => 'تحسب من الأوزان ويحفظ التقييم لقطة من المؤشرات المستخدمة مع مراجع مصادرها', 'value' => 'store_derived_snapshot_with_source_references'],
                        ['label' => 'تدخل مؤشرات النمو يدويًا داخل كل تقييم كقيم مستقلة', 'value' => 'manual_growth_metrics_per_evaluation'],
                    ],
                ],
                [
                    'seed_key' => 'growth_sorting.preliminary_result_categories',
                    'title' => 'ما النتائج المبدئية التي يجب أن يستطيع حدث الفرز أو التقييم تسجيلها؟',
                    'help_text' => 'هذه نتائج تقييم وليست تنفيذًا نهائيًا للمصير. المصدر يذكر الاستمرار، الترشيح المبدئي للقطيع، التسمين، الاستبعاد، وإعادة التقييم، مع التأكيد أن الترشيح لا يعني اعتماد الحيوان داخل القطيع.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 7,
                    'report_category' => 'workflow_outcome',
                    'target_entity' => 'growth_evaluation',
                    'options' => [
                        ['label' => 'الاستمرار إلى المرحلة التالية / استمرار المتابعة', 'value' => 'continue_growth_monitoring'],
                        ['label' => 'مرشح مبدئي للإحلال / القطيع', 'value' => 'preliminary_replacement_candidate'],
                        ['label' => 'توصية مبدئية بالتحويل إلى التسمين', 'value' => 'preliminary_fattening'],
                        ['label' => 'توصية مبدئية بالاستبعاد من المسار', 'value' => 'preliminary_exclusion'],
                        ['label' => 'إعادة تقييم قبل حسم الاتجاه', 'value' => 'reevaluation'],
                    ],
                ],
                [
                    'seed_key' => 'growth_sorting.result_to_fate_link_model',
                    'title' => 'كيف يجب ربط نتيجة الفرز المبدئية بقرار المصير الفعلي في القسم 4.10؟',
                    'help_text' => 'المعمارية والمصدر يفصلان بين التقييم وبين تنفيذ المصير. المطلوب حسم شكل الربط فقط: هل تنشئ النتيجة قرارًا معلقًا أو مسودة، أم يفتح المستخدم قرار المصير لاحقًا دون إنشاء سجل تلقائي. تنفيذ النقل أو الإحلال أو التسمين لا يحدث داخل 4.9.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 8,
                    'report_category' => 'integration_rule',
                    'target_entity' => 'fate_decision',
                    'options' => [
                        ['label' => 'تنشئ نتيجة الفرز قرار مصير معلقًا يحتاج اعتمادًا صريحًا في 4.10', 'value' => 'create_pending_fate_decision'],
                        ['label' => 'تنشئ مسودة قرار في 4.10 يمكن تعديلها قبل الاعتماد', 'value' => 'create_draft_fate_decision'],
                        ['label' => 'لا تنشئ سجل قرار تلقائيًا؛ يبدأ المستخدم قرار المصير من 4.10 عند الحاجة', 'value' => 'no_automatic_fate_record'],
                    ],
                ],
                [
                    'seed_key' => 'growth_sorting.reevaluation_model',
                    'title' => 'عند اختيار «إعادة تقييم»، كيف يجب تسجيل المراجعة التالية دون فقد نتيجة التقييم الحالي؟',
                    'help_text' => 'المصدر يفضل عدم إجبار المسؤول على قرار غير مقتنع به، ويطلب حفظ سبب إعادة التقييم وموعد المراجعة والبيانات المطلوب متابعتها. النتيجة السابقة يجب أن تبقى تاريخيًا.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 9,
                    'report_category' => 'history_rule',
                    'target_entity' => 'growth_reevaluation',
                    'options' => [
                        ['label' => 'ينشأ طلب إعادة تقييم مرتبط بالتقييم الحالي، وعند التنفيذ ينشأ تقييم جديد مستقل', 'value' => 'create_linked_reevaluation_request_then_new_event'],
                        ['label' => 'ينشأ تقييم جديد مباشرة مع مرجع للتقييم السابق وموعده المخطط', 'value' => 'create_future_linked_evaluation'],
                        ['label' => 'يعاد فتح نفس سجل التقييم وتعديل نتيجته لاحقًا', 'value' => 'reopen_and_edit_same_evaluation'],
                    ],
                ],
                [
                    'seed_key' => 'growth_sorting.reevaluation_record_fields',
                    'title' => 'ما البيانات التي يجب الاحتفاظ بها عند طلب إعادة تقييم الحيوان؟',
                    'help_text' => 'هذه البيانات تشرح لماذا لم يحسم الاتجاه الآن وما الذي ينتظره المسؤول قبل المراجعة التالية. قواعد توقيت المهمة والتنبيه تبقى في Settings / 4.17.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 10,
                    'report_category' => 'field',
                    'target_entity' => 'growth_reevaluation',
                    'options' => [
                        ['label' => 'مرجع التقييم الذي أدى إلى طلب إعادة التقييم', 'value' => 'source_evaluation'],
                        ['label' => 'سبب إعادة التقييم', 'value' => 'reason'],
                        ['label' => 'موعد / تاريخ المراجعة المقترح', 'value' => 'planned_review_at'],
                        ['label' => 'البيانات أو المؤشرات المطلوب متابعتها حتى المراجعة', 'value' => 'monitoring_requirements'],
                        ['label' => 'المستخدم الذي طلب إعادة التقييم', 'value' => 'requested_by'],
                        ['label' => 'ملاحظات', 'value' => 'notes'],
                    ],
                ],
            ];

            app(QuestionSeederSyncService::class)->sync(
                mainSectionName: 'الحركات ودورة التشغيل الفعلية',
                sectionName: 'النمو والفرز وإعادة التقييم',
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

        $resultCategories = $questions->get('growth_sorting.preliminary_result_categories');

        if (! $resultCategories) {
            return;
        }

        foreach ([
            'growth_sorting.reevaluation_model',
            'growth_sorting.reevaluation_record_fields',
        ] as $dependentSeedKey) {
            $dependentQuestion = $questions->get($dependentSeedKey);

            if (! $dependentQuestion) {
                continue;
            }

            $dependentQuestion->forceFill([
                'depends_on_question_id' => $resultCategories->id,
                'dependency_operator' => QuestionDependencyOperator::CONTAINS,
                'dependency_value' => 'reevaluation',
            ])->save();
        }
    }

    private function resolveSection(): ?QuestionnaireSection
    {
        $mainSection = QuestionnaireSection::query()
            ->whereNull('parent_id')
            ->where('name', 'الحركات ودورة التشغيل الفعلية')
            ->first();

        if (! $mainSection) {
            return null;
        }

        return QuestionnaireSection::query()
            ->where('parent_id', $mainSection->id)
            ->where('name', 'النمو والفرز وإعادة التقييم')
            ->first();
    }
}
