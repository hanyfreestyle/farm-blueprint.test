<?php

namespace Database\Seeders\Questions\Workflow;

use App\Enums\Questionnaire\QuestionDependencyOperator;
use App\Enums\Questionnaire\QuestionType;
use App\Models\QuestionnaireQuestion;
use App\Models\QuestionnaireSection;
use App\Services\Questionnaire\QuestionSeederSyncService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AnimalIntakeQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $questions = [
                [
                    'seed_key' => 'animal_intake.reception_modes',
                    'title' => 'ما طرق تنفيذ استقبال الحيوانات الواردة من خارج المزرعة التي يجب أن يدعمها النظام؟',
                    'help_text' => 'حدد هل يتم تسجيل كل حيوان بصورة منفردة فقط أم يحتاج النظام أيضًا إلى استقبال مجموعة حيوانات في عملية واحدة مع الاحتفاظ بسجل مستقل لكل حيوان. النقل الداخلي بين مزارع يديرها نفس النظام لا يعامل تلقائيًا كحيوان جديد.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 1,
                    'report_category' => 'workflow_model',
                    'target_entity' => 'animal_intake_event',
                    'options' => [
                        ['label' => 'استقبال حيوان واحد بصورة مستقلة', 'value' => 'individual_reception'],
                        ['label' => 'استقبال جماعي لدفعة / صفقة مع إنشاء سجل استقبال مستقل لكل حيوان', 'value' => 'batch_reception_with_individual_records'],
                    ],
                ],
                [
                    'seed_key' => 'animal_intake.batch_shared_fields',
                    'title' => 'ما البيانات التي يمكن مشاركتها على مستوى دفعة الاستقبال بدل إعادة إدخالها لكل حيوان؟',
                    'help_text' => 'يظهر هذا السؤال عند دعم الاستقبال الجماعي. تبقى لكل حيوان هويته وسجل استقباله ونتيجة تقييمه المستقلة، بينما يمكن الاحتفاظ ببعض بيانات الصفقة أو الوصول مرة واحدة على مستوى الدفعة.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 2,
                    'report_category' => 'field',
                    'target_entity' => 'animal_intake_batch',
                    'options' => [
                        ['label' => 'مرجع / رقم الدفعة أو الصفقة', 'value' => 'batch_reference'],
                        ['label' => 'تاريخ ووقت الوصول المشترك', 'value' => 'arrival_datetime'],
                        ['label' => 'المصدر / الجهة المشتركة للحيوانات', 'value' => 'shared_source_party'],
                        ['label' => 'ملاحظات مشتركة', 'value' => 'shared_notes'],
                    ],
                ],
                [
                    'seed_key' => 'animal_intake.entry_weight_policy',
                    'title' => 'كيف يجب أن يتعامل مسار الاستقبال مع وزن الدخول؟',
                    'help_text' => 'إذا تم تسجيل وزن الدخول فإنه يحفظ كسجل وزن فعلي من نوع «وزن دخول» داخل Weight History ولا يكتب فوق قيمة ثابتة في ملف الحيوان. هذا السؤال يحسم فقط مدى إلزام القياس أثناء الاستقبال.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 3,
                    'report_category' => 'workflow_rule',
                    'target_entity' => 'animal_weight_event',
                    'options' => [
                        ['label' => 'وزن الدخول إلزامي قبل إكمال الاستقبال', 'value' => 'required_during_intake'],
                        ['label' => 'وزن الدخول اختياري أثناء الاستقبال', 'value' => 'optional_during_intake'],
                        ['label' => 'لا يرتبط إكمال الاستقبال بوجود وزن دخول', 'value' => 'not_required_for_intake_completion'],
                    ],
                ],
                [
                    'seed_key' => 'animal_intake.initial_evaluation_required',
                    'title' => 'هل يجب أن يحتوي استقبال الحيوان الخارجي على تقييم أولي مستقل قبل الاعتماد؟',
                    'help_text' => 'المقصود تقييم مبدئي تشغيلي عند الوصول يحدد ما إذا كان الحيوان يمكنه الاستمرار مباشرة أو يحتاج ملاحظة / عزل / رفض. التفاصيل الطبية الدقيقة خارج نطاق هذا القسم.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 4,
                    'report_category' => 'workflow_rule',
                    'target_entity' => 'animal_intake_evaluation',
                    'options' => [],
                ],
                [
                    'seed_key' => 'animal_intake.initial_evaluation_fields',
                    'title' => 'ما البيانات التي يجب أن يستطيع التقييم الأولي للحيوان تسجيلها؟',
                    'help_text' => 'حدد عناصر التقييم المبدئي عند الوصول فقط. لا يحول هذا السؤال النظام إلى وحدة علاج بيطري كاملة، بل يثبت الحد التشغيلي المطلوب قبل اتخاذ قرار الاستقبال.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 5,
                    'report_category' => 'field',
                    'target_entity' => 'animal_intake_evaluation',
                    'options' => [
                        ['label' => 'الحالة العامة', 'value' => 'general_condition'],
                        ['label' => 'وجود إصابة ظاهرة', 'value' => 'visible_injury'],
                        ['label' => 'وجود أعراض مرضية ظاهرة', 'value' => 'visible_disease_signs'],
                        ['label' => 'حالة الجسم', 'value' => 'body_condition'],
                        ['label' => 'ملاحظات التقييم', 'value' => 'notes'],
                    ],
                ],
                [
                    'seed_key' => 'animal_intake.initial_decision_outcomes',
                    'title' => 'ما النتائج التي يجب أن يدعمها القرار الأولي بعد تقييم الحيوان عند الاستقبال؟',
                    'help_text' => 'هذه نتائج واقعة التقييم الفعلية. قواعد متى تستخدم كل نتيجة ومعايير القبول أو الرفض تحدد لاحقًا في Settings إذا كانت قابلة للضبط.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 6,
                    'report_category' => 'workflow_outcome',
                    'target_entity' => 'animal_intake_evaluation',
                    'options' => [
                        ['label' => 'مقبول للاستمرار في مسار الإدخال', 'value' => 'accepted'],
                        ['label' => 'تحت الملاحظة / يحتاج إعادة تقييم', 'value' => 'observation'],
                        ['label' => 'يحتاج عزل صحي', 'value' => 'health_isolation'],
                        ['label' => 'غير مناسب للإدخال / مرفوض', 'value' => 'rejected'],
                    ],
                ],
                [
                    'seed_key' => 'animal_intake.temporary_monitoring_stage',
                    'title' => 'هل يجب أن يدعم مسار الاستقبال مرحلة مؤقتة للملاحظة أو الحجر قبل الاعتماد النهائي عند بعض الحالات؟',
                    'help_text' => 'هذا السؤال لا يحدد أن الحجر إلزامي لكل حيوان ولا يحدد مدته؛ هذه قواعد تشغيل مكانها Settings. المطلوب فقط حسم وجود مرحلة فعلية يمكن أن يمر بها الحيوان عند تطبيق القواعد.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 7,
                    'report_category' => 'workflow_model',
                    'target_entity' => 'animal_intake_monitoring',
                    'options' => [],
                ],
                [
                    'seed_key' => 'animal_intake.temporary_monitoring_events',
                    'title' => 'ما الأحداث والنتائج التي يجب أن يدعمها سجل الملاحظة / الحجر أثناء الاستقبال؟',
                    'help_text' => 'مواعيد إعادة التقييم ومدة الحجر وشروط الخروج منه تظل Settings. هنا نحدد فقط ما يمكن تسجيله عندما يحدث فعليًا أثناء مرحلة الاستقبال المؤقتة.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 8,
                    'report_category' => 'workflow_event',
                    'target_entity' => 'animal_intake_monitoring',
                    'options' => [
                        ['label' => 'بدء مرحلة الملاحظة / الحجر', 'value' => 'monitoring_started'],
                        ['label' => 'إعادة تقييم الحيوان', 'value' => 're_evaluated'],
                        ['label' => 'تمديد الملاحظة / الحجر', 'value' => 'monitoring_extended'],
                        ['label' => 'اجتياز المرحلة والانتقال للاعتماد', 'value' => 'passed'],
                        ['label' => 'تحويل إلى مسار العزل / المتابعة الصحية', 'value' => 'transferred_to_health_workflow'],
                        ['label' => 'رفض الإدخال', 'value' => 'rejected'],
                    ],
                ],
                [
                    'seed_key' => 'animal_intake.preventive_actions_recording',
                    'title' => 'هل يحتاج مسار الاستقبال إلى تسجيل الفحوص أو الإجراءات الوقائية التي تم تنفيذها فعليًا قبل الاعتماد؟',
                    'help_text' => 'المقصود إثبات أن إجراءً أو فحصًا تم فعليًا عند الاستقبال عند الحاجة، دون افتراض قائمة طبية أو علاجية غير معتمدة. نوع الإجراء المطلوب وقواعد إلزامه يمكن تحديدها لاحقًا إذا ثبت الاحتياج.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 9,
                    'report_category' => 'workflow_event',
                    'target_entity' => 'animal_intake_check',
                    'options' => [],
                ],
                [
                    'seed_key' => 'animal_intake.finalization_model',
                    'title' => 'كيف يجب إغلاق مسار استقبال الحيوان بعد اجتياز الخطوات المطلوبة؟',
                    'help_text' => 'اعتماد الاستقبال يعني أن الحيوان اجتاز مسار الإدخال ويمكن الانتقال به إلى التسكين والتشغيل المناسب، لكنه لا يعني تلقائيًا أنه جاهز للتلقيح أو مستوفٍ لكل شروط القطيع الإنتاجي.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 10,
                    'report_category' => 'workflow_rule',
                    'target_entity' => 'animal_intake_event',
                    'options' => [
                        ['label' => 'يحتاج حدث اعتماد نهائي صريح بعد اكتمال الخطوات المطلوبة', 'value' => 'explicit_final_approval'],
                        ['label' => 'يغلق تلقائيًا عند تسجيل آخر نتيجة مستوفية لشروط الاستقبال', 'value' => 'auto_close_on_qualifying_result'],
                    ],
                ],
                [
                    'seed_key' => 'animal_intake.reentry_process_model',
                    'title' => 'كيف يجب أن يبدأ مسار إعادة إدخال حيوان ثبت أنه نفس الحيوان المسجل سابقًا؟',
                    'help_text' => 'هوية الحيوان لا يعاد إنشاؤها عند إثبات أنه نفس الحيوان؛ يستمر نفس Animal Record. المطلوب هنا حسم مستوى إعادة تطبيق مسار الاستقبال عند عودته بعد خروج سابق.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 11,
                    'report_category' => 'workflow_rule',
                    'target_entity' => 'animal_reentry_event',
                    'options' => [
                        ['label' => 'يمر بدورة استقبال وتقييم كاملة من جديد على نفس سجل الحيوان', 'value' => 'full_intake_cycle_same_animal'],
                        ['label' => 'يبدأ دورة استقبال على نفس السجل وتحدد القواعد الخطوات المطلوبة حسب حالة العودة', 'value' => 'intake_cycle_steps_by_rules'],
                        ['label' => 'يسمح بإعادة الإدخال المباشر على نفس السجل بعد تأكيد الهوية دون إعادة دورة الاستقبال كاملة', 'value' => 'direct_reentry_after_identity_confirmation'],
                    ],
                ],
            ];

            app(QuestionSeederSyncService::class)->sync(
                mainSectionName: 'الحركات ودورة التشغيل الفعلية',
                sectionName: 'استقبال الحيوان من الخارج وإعادة الإدخال',
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

        $receptionModes = $questions->get('animal_intake.reception_modes');
        $batchSharedFields = $questions->get('animal_intake.batch_shared_fields');

        if ($receptionModes && $batchSharedFields) {
            $batchSharedFields->forceFill([
                'depends_on_question_id' => $receptionModes->id,
                'dependency_operator' => QuestionDependencyOperator::CONTAINS,
                'dependency_value' => 'batch_reception_with_individual_records',
            ])->save();
        }

        $initialEvaluationRequired = $questions->get('animal_intake.initial_evaluation_required');

        if ($initialEvaluationRequired) {
            foreach ([
                'animal_intake.initial_evaluation_fields',
                'animal_intake.initial_decision_outcomes',
            ] as $dependentSeedKey) {
                $dependentQuestion = $questions->get($dependentSeedKey);

                if (! $dependentQuestion) {
                    continue;
                }

                $dependentQuestion->forceFill([
                    'depends_on_question_id' => $initialEvaluationRequired->id,
                    'dependency_operator' => QuestionDependencyOperator::EQUALS,
                    'dependency_value' => '1',
                ])->save();
            }
        }

        $temporaryMonitoringStage = $questions->get('animal_intake.temporary_monitoring_stage');
        $temporaryMonitoringEvents = $questions->get('animal_intake.temporary_monitoring_events');

        if ($temporaryMonitoringStage && $temporaryMonitoringEvents) {
            $temporaryMonitoringEvents->forceFill([
                'depends_on_question_id' => $temporaryMonitoringStage->id,
                'dependency_operator' => QuestionDependencyOperator::EQUALS,
                'dependency_value' => '1',
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
            ->where('name', 'استقبال الحيوان من الخارج وإعادة الإدخال')
            ->first();
    }
}
