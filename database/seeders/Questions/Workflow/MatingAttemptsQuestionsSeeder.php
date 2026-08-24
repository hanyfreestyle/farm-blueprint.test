<?php

namespace Database\Seeders\Questions\Workflow;

use App\Enums\Questionnaire\QuestionDependencyOperator;
use App\Enums\Questionnaire\QuestionType;
use App\Models\QuestionnaireQuestion;
use App\Models\QuestionnaireSection;
use App\Services\Questionnaire\QuestionSeederSyncService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MatingAttemptsQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $questions = [
                [
                    'seed_key' => 'mating.structure_model',
                    'title' => 'كيف يجب تمثيل العلاقة بين دورة إنتاج الأنثى ومحاولة التلقيح وعمليات التلقيح الفعلية؟',
                    'help_text' => 'المرجع يفرق بين عملية التلقيح الواحدة Mating، ومحاولة التلقيح Attempt التي قد تحتوي على أكثر من عملية، ودورة الإنتاج Reproductive Cycle التي تمتد لاحقًا إلى فحص الحمل والولادة والبطن. هذا السؤال يحسم البنية الأساسية دون تحديد عدد التلقيحات أو توقيتها.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 1,
                    'report_category' => 'architecture_rule',
                    'target_entity' => 'reproductive_cycle',
                    'options' => [
                        ['label' => 'دورة الإنتاج تحتوي على محاولات، وكل محاولة تحتوي على عمليات تلقيح فعلية', 'value' => 'cycle_attempts_mating_events'],
                        ['label' => 'دورة الإنتاج تحتوي مباشرة على عمليات التلقيح دون كيان مستقل للمحاولة', 'value' => 'cycle_mating_events_only'],
                        ['label' => 'كل عملية تلقيح تعتبر محاولة مستقلة', 'value' => 'each_mating_is_attempt'],
                    ],
                ],
                [
                    'seed_key' => 'mating.reproductive_cycle_start_model',
                    'title' => 'متى يجب إنشاء دورة إنتاج جديدة للأنثى عند بدء مسار التلقيح؟',
                    'help_text' => 'التصور الحالي يقترح أن أول تلقيح فعلي يبدأ دورة إنتاج جديدة وترتبط بها الأحداث اللاحقة. المطلوب حسم هل يتم ذلك تلقائيًا من أول حدث تلقيح أم يحتاج المستخدم إلى بدء دورة قبل التنفيذ.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 2,
                    'report_category' => 'workflow_rule',
                    'target_entity' => 'reproductive_cycle',
                    'options' => [
                        ['label' => 'تنشأ الدورة تلقائيًا عند تسجيل أول عملية تلقيح في المسار الجديد', 'value' => 'auto_on_first_mating'],
                        ['label' => 'ينشئ المستخدم دورة الإنتاج صراحة قبل تسجيل أول تلقيح', 'value' => 'explicit_cycle_start_before_mating'],
                    ],
                ],
                [
                    'seed_key' => 'mating.attempt_event_cardinality',
                    'title' => 'كيف يجب تسجيل تكرار التلقيح داخل نفس المحاولة؟',
                    'help_text' => 'لا نريد افتراض حقول ثابتة مثل first_mating وsecond_mating إذا كان عدد مرات التلقيح قابلًا للاختلاف حسب التشغيل. هذا السؤال يحدد هل المحاولة تستوعب عددًا متغيرًا من أحداث التلقيح.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 3,
                    'report_category' => 'architecture_rule',
                    'target_entity' => 'mating_attempt',
                    'options' => [
                        ['label' => 'المحاولة تحتوي على عدد متغير من أحداث التلقيح المرتبة', 'value' => 'variable_ordered_mating_events'],
                        ['label' => 'المحاولة تحتوي على حقول ثابتة للتلقيح الأول والثاني فقط', 'value' => 'fixed_first_second_fields'],
                        ['label' => 'المحاولة تحتوي على حدث تلقيح واحد فقط، وأي تكرار يصبح محاولة جديدة', 'value' => 'single_mating_per_attempt'],
                    ],
                ],
                [
                    'seed_key' => 'mating.event_record_fields',
                    'title' => 'ما البيانات التي يجب أن يستطيع سجل عملية التلقيح الفعلية الاحتفاظ بها؟',
                    'help_text' => 'السجل يمثل ما حدث فعليًا، لذلك يجب أن يحتفظ بالذكر المستخدم بالفعل لا بمجرد الذكر المخصص تنظيميًا. الوزن إن استخدم في السياق يفضل ربطه بسجل الوزن الموثوق بدل إنشاء نسخة مستقلة غير مترابطة.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 4,
                    'report_category' => 'field',
                    'target_entity' => 'mating_event',
                    'options' => [
                        ['label' => 'الأنثى', 'value' => 'female'],
                        ['label' => 'الذكر المستخدم فعليًا', 'value' => 'actual_male'],
                        ['label' => 'تاريخ / وقت حدوث التلقيح', 'value' => 'occurred_at'],
                        ['label' => 'دورة الإنتاج المرتبطة', 'value' => 'reproductive_cycle'],
                        ['label' => 'محاولة التلقيح المرتبطة عند استخدام نموذج المحاولات', 'value' => 'mating_attempt'],
                        ['label' => 'ترتيب عملية التلقيح داخل المحاولة', 'value' => 'sequence_within_attempt'],
                        ['label' => 'النتيجة المباشرة للعملية', 'value' => 'result'],
                        ['label' => 'مرجع آخر وزن / وزن مرتبط للأنثى عند الحاجة', 'value' => 'female_weight_reference'],
                        ['label' => 'مرجع آخر وزن / وزن مرتبط للذكر عند الحاجة', 'value' => 'male_weight_reference'],
                        ['label' => 'المستخدم / المنفذ', 'value' => 'performed_by'],
                        ['label' => 'ملاحظات', 'value' => 'notes'],
                    ],
                ],
                [
                    'seed_key' => 'mating.event_result_categories',
                    'title' => 'ما النتائج المباشرة التي يجب أن يستطيع حدث التلقيح تسجيلها قبل معرفة نتيجة الحمل؟',
                    'help_text' => 'هذه نتيجة تنفيذ عملية التلقيح نفسها وليست نتيجة فحص الحمل. المرجع يذكر ضرورة تسجيل نتيجة العملية ومعالجة حالة رفض الأنثى للتلقيح بصورة واضحة.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 5,
                    'report_category' => 'workflow_outcome',
                    'target_entity' => 'mating_event',
                    'options' => [
                        ['label' => 'تم تنفيذ التلقيح', 'value' => 'mating_performed'],
                        ['label' => 'رفضت الأنثى التلقيح', 'value' => 'female_refused'],
                        ['label' => 'لم تكتمل عملية التلقيح لسبب آخر', 'value' => 'not_completed_other_reason'],
                    ],
                ],
                [
                    'seed_key' => 'mating.execution_context_fields',
                    'title' => 'ما المعلومات التي يحتاج منفذ التلقيح رؤيتها قبل تأكيد العملية دون إعادة إدخالها؟',
                    'help_text' => 'الهدف عرض سياق ميداني يساعد على التنفيذ الصحيح باستخدام بيانات موجودة بالفعل. لا تتحول هذه المعلومات إلى نسخ ثابتة جديدة داخل حدث التلقيح ما لم تكن جزءًا مطلوبًا من السجل.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 6,
                    'report_category' => 'workflow_context',
                    'target_entity' => 'mating_execution_context',
                    'options' => [
                        ['label' => 'كود الأنثى وموقعها الحالي', 'value' => 'female_identity_location'],
                        ['label' => 'عمر الأنثى', 'value' => 'female_age'],
                        ['label' => 'آخر وزن للأنثى وتاريخه', 'value' => 'female_last_weight'],
                        ['label' => 'الحالة الصحية / وجود عزل أو مانع ظاهر', 'value' => 'female_health_context'],
                        ['label' => 'آخر دورة إنتاجية ومحاولات التلقيح السابقة', 'value' => 'female_reproductive_history'],
                        ['label' => 'هل الأنثى مرضعة حاليًا', 'value' => 'female_lactation_context'],
                        ['label' => 'كود الذكر وعمره وسلالته', 'value' => 'male_identity_age_breed'],
                        ['label' => 'آخر وزن للذكر وتاريخه', 'value' => 'male_last_weight'],
                        ['label' => 'الحالة الصحية والاستخدامات الأخيرة للذكر', 'value' => 'male_health_recent_usage'],
                        ['label' => 'هل الذكر هو المخصص للأنثى / المجموعة', 'value' => 'assigned_male_indicator'],
                        ['label' => 'نتيجة فحص القرابة قبل التأكيد', 'value' => 'kinship_check_result'],
                    ],
                ],
                [
                    'seed_key' => 'mating.assigned_male_execution_role',
                    'title' => 'إذا كانت الأنثى مرتبطة بذكر مخصص ضمن تنظيم القطيع، كيف يجب استخدام هذا التخصيص أثناء تنفيذ التلقيح؟',
                    'help_text' => 'التخصيص هو خطة تنظيمية، بينما سجل التلقيح يجب أن يحتفظ دائمًا بالذكر المستخدم فعليًا. قواعد السماح باستخدام ذكر مختلف أو منع ذلك تبقى قابلة للحسم في Settings.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 7,
                    'report_category' => 'workflow_rule',
                    'target_entity' => 'mating_event',
                    'options' => [
                        ['label' => 'يظهر الذكر المخصص كاقتراح / أولوية، ويختار المستخدم الذكر الفعلي', 'value' => 'recommend_assigned_male'],
                        ['label' => 'يتم تحديد الذكر المخصص مسبقًا مع إمكانية تغييره وفق قواعد التشغيل', 'value' => 'preselect_assigned_male_editable_by_rules'],
                        ['label' => 'لا يؤثر تخصيص المجموعة على شاشة تنفيذ التلقيح', 'value' => 'group_assignment_not_used_in_execution'],
                    ],
                ],
                [
                    'seed_key' => 'mating.runtime_kinship_check',
                    'title' => 'هل يجب إعادة فحص القرابة بين الذكر والأنثى عند عملية التلقيح الفعلية حتى لو تم فحصها سابقًا عند تكوين المجموعة؟',
                    'help_text' => 'السبب أن المستخدم قد يختار ذكرًا مختلفًا عن المخصص. مستويات القرابة وما إذا كانت النتيجة Information أو Warning أو Block تظل قواعد في Settings / Reports حسب الوظيفة.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 8,
                    'report_category' => 'integrity_rule',
                    'target_entity' => 'mating_event',
                    'options' => [],
                ],
                [
                    'seed_key' => 'mating.multiple_males_paternity_policy',
                    'title' => 'إذا تم استخدام أكثر من ذكر داخل نفس محاولة التلقيح، هل يجب اعتبار الأبوة غير محسومة وعدم اختيار أب تلقائيًا من أحد الأحداث؟',
                    'help_text' => 'المرجع يفضل استخدام نفس الذكر داخل المحاولة لأن تغيير الذكر قد يجعل تحديد الأب غير مؤكد. هذا السؤال يحسم حماية بيانات النسب عند حدوث ذلك فعليًا؛ سياسة التحذير أو المنع قبل التنفيذ تبقى في Settings.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 9,
                    'report_category' => 'integrity_rule',
                    'target_entity' => 'mating_attempt',
                    'options' => [],
                ],
                [
                    'seed_key' => 'mating.attempt_completion_model',
                    'title' => 'كيف يجب أن تنتقل محاولة التلقيح من مرحلة تنفيذ التلقيحات إلى انتظار فحص الحمل؟',
                    'help_text' => 'عدد التلقيحات المطلوبة والفاصل بينها ومتى تعتبر المحاولة مكتملة هي قيم وقواعد تشغيل في Settings. هنا نحدد فقط كيف يسجل الانتقال الفعلي بعد استيفاء القاعدة.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 10,
                    'report_category' => 'workflow_rule',
                    'target_entity' => 'mating_attempt',
                    'options' => [
                        ['label' => 'تغلق مرحلة التلقيح تلقائيًا عند تحقق قواعد المحاولة وتنتقل إلى انتظار فحص الحمل', 'value' => 'auto_complete_by_configured_rules'],
                        ['label' => 'يحتاج المستخدم إلى إجراء صريح لإكمال المحاولة والانتقال لانتظار فحص الحمل', 'value' => 'explicit_attempt_completion'],
                        ['label' => 'تلقائي افتراضيًا مع إمكانية إكمال صريح بصلاحية عند الحاجة', 'value' => 'auto_with_authorized_explicit_completion'],
                    ],
                ],
                [
                    'seed_key' => 'mating.new_attempt_boundary',
                    'title' => 'متى يصبح التلقيح اللاحق محاولة جديدة بدل كونه عملية إضافية داخل نفس المحاولة؟',
                    'help_text' => 'المرجع يفرق بين تكرار التلقيح داخل المحاولة وبين إعادة التلقيح بعد فشل الحمل. هذا القرار يجب أن يحافظ على وضوح المحاولات للتقارير وتقييم الأنثى والذكر.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 11,
                    'report_category' => 'workflow_rule',
                    'target_entity' => 'mating_attempt',
                    'options' => [
                        ['label' => 'لا تبدأ محاولة جديدة إلا بعد إغلاق السابقة بنتيجة أو إلغاء معتمد', 'value' => 'after_previous_attempt_closed'],
                        ['label' => 'يمكن بدء محاولة جديدة بينما السابقة مفتوحة ويغلق النظام السابقة تلقائيًا', 'value' => 'new_attempt_auto_closes_previous'],
                        ['label' => 'يسمح بأكثر من محاولة مفتوحة لنفس الأنثى في نفس دورة الإنتاج', 'value' => 'multiple_open_attempts_allowed'],
                    ],
                ],
                [
                    'seed_key' => 'mating.attempt_cancellation_model',
                    'title' => 'كيف يجب التعامل مع إلغاء محاولة تلقيح بدأت بالفعل قبل الوصول إلى نتيجة حمل؟',
                    'help_text' => 'الإلغاء لا يجب أن يمحو عمليات التلقيح المسجلة. إذا كان مدعومًا كحدث تشغيلي، يحتفظ بالسبب وتاريخ/وقت الإلغاء والمستخدم والملاحظات، بينما صلاحيات الإلغاء والتجاوز تحدد لاحقًا في Settings.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 12,
                    'report_category' => 'lifecycle_rule',
                    'target_entity' => 'mating_attempt',
                    'options' => [
                        ['label' => 'يدعم إلغاءً تشغيليًا صريحًا مع السبب والتاريخ والمنفذ مع بقاء التاريخ السابق', 'value' => 'explicit_cancellation_with_history'],
                        ['label' => 'لا يوجد إلغاء تشغيلي؛ أي خطأ يعالج من خلال آلية التصحيح / التدقيق العامة', 'value' => 'correction_only_no_operational_cancel'],
                        ['label' => 'لا يسمح بالإلغاء أو التصحيح بعد بدء المحاولة', 'value' => 'no_cancellation_or_correction'],
                    ],
                ],
            ];

            app(QuestionSeederSyncService::class)->sync(
                mainSectionName: 'الحركات ودورة التشغيل الفعلية',
                sectionName: 'التلقيح وإدارة المحاولات',
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

        $eventFields = $questions->get('mating.event_record_fields');
        $eventResults = $questions->get('mating.event_result_categories');

        if ($eventFields && $eventResults) {
            $eventResults->forceFill([
                'depends_on_question_id' => $eventFields->id,
                'dependency_operator' => QuestionDependencyOperator::CONTAINS,
                'dependency_value' => 'result',
            ])->save();
        }

        $structureModel = $questions->get('mating.structure_model');

        if ($structureModel) {
            foreach ([
                'mating.attempt_event_cardinality',
                'mating.multiple_males_paternity_policy',
                'mating.attempt_completion_model',
                'mating.new_attempt_boundary',
                'mating.attempt_cancellation_model',
            ] as $dependentSeedKey) {
                $dependentQuestion = $questions->get($dependentSeedKey);

                if (! $dependentQuestion) {
                    continue;
                }

                $dependentQuestion->forceFill([
                    'depends_on_question_id' => $structureModel->id,
                    'dependency_operator' => QuestionDependencyOperator::EQUALS,
                    'dependency_value' => 'cycle_attempts_mating_events',
                ])->save();
            }
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
            ->where('name', 'التلقيح وإدارة المحاولات')
            ->first();
    }
}
