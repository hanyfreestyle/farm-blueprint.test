<?php

namespace Database\Seeders\Questions\Workflow;

use App\Enums\Questionnaire\QuestionDependencyOperator;
use App\Enums\Questionnaire\QuestionType;
use App\Models\QuestionnaireQuestion;
use App\Models\QuestionnaireSection;
use App\Services\Questionnaire\QuestionSeederSyncService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PregnancyFollowUpQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $questions = [
                [
                    'seed_key' => 'pregnancy_check.history_model',
                    'title' => 'كيف يجب تمثيل فحوص الحمل المتكررة داخل نفس محاولة التلقيح؟',
                    'help_text' => 'المرجع يدعم إمكانية وجود أكثر من فحص حمل داخل نفس المحاولة، مثل فحص أول غير مؤكد ثم فحص لاحق. المطلوب حسم هل يحتفظ كل فحص كسجل مستقل أم يتم استبدال نتيجة سابقة.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 1,
                    'report_category' => 'architecture_rule',
                    'target_entity' => 'pregnancy_check',
                    'options' => [
                        ['label' => 'كل فحص حمل سجل مستقل مرتبط بنفس محاولة التلقيح مع الاحتفاظ بالتاريخ الكامل', 'value' => 'multiple_check_records_with_history'],
                        ['label' => 'يحتفظ النظام بنتيجة فحص حالية واحدة ويتم استبدالها عند إعادة الفحص', 'value' => 'single_current_result_overwritten'],
                    ],
                ],
                [
                    'seed_key' => 'pregnancy_check.record_fields',
                    'title' => 'ما البيانات التي يجب أن يحتفظ بها سجل فحص الحمل الفعلي؟',
                    'help_text' => 'السجل يمثل فحصًا تم تنفيذه فعليًا. عدد الأيام منذ التلقيح يمكن حسابه من التاريخ المرجعي، بينما اختيار التاريخ المرجعي نفسه وقواعد موعد الجس تبقى في Settings.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 2,
                    'report_category' => 'field',
                    'target_entity' => 'pregnancy_check',
                    'options' => [
                        ['label' => 'الأنثى', 'value' => 'female'],
                        ['label' => 'محاولة التلقيح المرتبطة', 'value' => 'mating_attempt'],
                        ['label' => 'دورة الإنتاج المرتبطة', 'value' => 'reproductive_cycle'],
                        ['label' => 'تاريخ / وقت تنفيذ الفحص فعليًا', 'value' => 'performed_at'],
                        ['label' => 'عدد الأيام منذ تاريخ التلقيح المرجعي', 'value' => 'days_from_reference_mating'],
                        ['label' => 'نتيجة الفحص', 'value' => 'result'],
                        ['label' => 'المستخدم / منفذ الفحص', 'value' => 'performed_by'],
                        ['label' => 'ملاحظات', 'value' => 'notes'],
                    ],
                ],
                [
                    'seed_key' => 'pregnancy_check.result_categories',
                    'title' => 'ما النتائج التي يجب أن يدعمها فحص الحمل عند تنفيذه؟',
                    'help_text' => 'هذه نتائج الفحص الفعلي. حالة «لم يتم الفحص» تعالج في سؤال مستقل لأن عدم تنفيذ المهمة لا يعني وجود نتيجة فحص طبية.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 3,
                    'report_category' => 'workflow_outcome',
                    'target_entity' => 'pregnancy_check',
                    'options' => [
                        ['label' => 'حامل / حمل مؤكد', 'value' => 'positive'],
                        ['label' => 'غير حامل', 'value' => 'negative'],
                        ['label' => 'النتيجة غير مؤكدة وتحتاج إعادة فحص', 'value' => 'uncertain'],
                    ],
                ],
                [
                    'seed_key' => 'pregnancy_check.not_performed_handling',
                    'title' => 'كيف يجب تمثيل حالة حلول موعد فحص الحمل دون تنفيذ الفحص فعليًا؟',
                    'help_text' => 'المرجع يؤكد أن مرور الموعد لا يسمح بافتراض حامل أو غير حامل. المطلوب الفصل بين Clinical Result وبين حالة المهمة غير المنفذة.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 4,
                    'report_category' => 'workflow_rule',
                    'target_entity' => 'pregnancy_check_task',
                    'options' => [
                        ['label' => 'لا ينشأ Pregnancy Check Record؛ تظل مهمة الفحص متأخرة حتى التنفيذ أو معالجة عدم التنفيذ', 'value' => 'no_check_record_task_overdue'],
                        ['label' => 'تغلق المهمة كغير منفذة مع سبب، دون إنشاء نتيجة حمل', 'value' => 'task_closed_not_performed_with_reason'],
                        ['label' => 'ينشأ سجل فحص بنتيجة «لم يتم الفحص» رغم عدم حدوث فحص فعلي', 'value' => 'create_not_performed_check_record'],
                    ],
                ],
                [
                    'seed_key' => 'pregnancy_check.uncertain_result_flow',
                    'title' => 'ماذا يجب أن يحدث للمحاولة عند تسجيل نتيجة فحص حمل «غير مؤكدة»؟',
                    'help_text' => 'المصدر يقترح بقاء المحاولة مفتوحة وتسجيل فحص جديد لاحقًا دون فقد نتيجة الفحص الأول. توقيت إعادة الفحص وقواعد إنشاء المهمة تبقى في Settings.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 5,
                    'report_category' => 'workflow_rule',
                    'target_entity' => 'mating_attempt',
                    'options' => [
                        ['label' => 'تبقى المحاولة مفتوحة ويسمح بفحص جديد مرتبط بنفس المحاولة', 'value' => 'keep_attempt_open_allow_recheck'],
                        ['label' => 'تغلق المحاولة ويبدأ مسار جديد لإعادة التقييم', 'value' => 'close_attempt_start_new_flow'],
                        ['label' => 'لا يغير النظام حالة المحاولة تلقائيًا ويحتاج قرارًا يدويًا', 'value' => 'manual_decision'],
                    ],
                ],
                [
                    'seed_key' => 'pregnancy_check.positive_result_flow',
                    'title' => 'ماذا يجب أن يحدث عند تسجيل فحص حمل إيجابي؟',
                    'help_text' => 'التصور الحالي يربط النتيجة الإيجابية بمحاولة التلقيح الناجحة، وينشئ سجل حمل ويحسب مواعيد الولادة المتوقعة وفق الإعدادات. المطلوب حسم درجة الأتمتة في الانتقال.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 6,
                    'report_category' => 'workflow_rule',
                    'target_entity' => 'pregnancy',
                    'options' => [
                        ['label' => 'تغلق المحاولة كناجحة وينشأ سجل الحمل تلقائيًا من نتيجة الفحص', 'value' => 'close_attempt_success_create_pregnancy_automatically'],
                        ['label' => 'تسجل النتيجة الإيجابية ثم يحتاج المستخدم لإجراء مستقل لتأكيد وإنشاء سجل الحمل', 'value' => 'positive_check_then_explicit_pregnancy_confirmation'],
                    ],
                ],
                [
                    'seed_key' => 'pregnancy_check.negative_result_flow',
                    'title' => 'ماذا يجب أن يحدث عند تسجيل فحص حمل سلبي؟',
                    'help_text' => 'المصدر يقترح إغلاق محاولة التلقيح الحالية كغير ناجحة ثم إعادة تقييم مسار التلقيح حسب الإعدادات، دون اعتبار النتيجة حذفًا لتاريخ المحاولة.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 7,
                    'report_category' => 'workflow_rule',
                    'target_entity' => 'mating_attempt',
                    'options' => [
                        ['label' => 'تغلق المحاولة كغير ناجحة وتصبح الأنثى مؤهلة لمسار محاولة جديدة وفق القواعد', 'value' => 'close_attempt_unsuccessful_return_to_remating_flow'],
                        ['label' => 'تغلق المحاولة فقط ويحتاج المسار التالي إلى قرار يدوي', 'value' => 'close_attempt_manual_next_step'],
                        ['label' => 'تبقى المحاولة مفتوحة حتى إجراء آخر', 'value' => 'keep_attempt_open'],
                    ],
                ],
                [
                    'seed_key' => 'pregnancy.record_fields',
                    'title' => 'ما البيانات التي يجب أن يحتفظ بها سجل الحمل المؤكد؟',
                    'help_text' => 'سجل الحمل يمثل فترة مستقلة في تاريخ الأنثى بعد تأكيد الحمل. القيم الزمنية المتوقعة تحسب من القاعدة المعتمدة، لكن يمكن حفظ الناتج أو مرجعه تاريخيًا حسب القرار النهائي في Settings.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 8,
                    'report_category' => 'field',
                    'target_entity' => 'pregnancy',
                    'options' => [
                        ['label' => 'الأنثى', 'value' => 'female'],
                        ['label' => 'دورة الإنتاج', 'value' => 'reproductive_cycle'],
                        ['label' => 'محاولة التلقيح الناجحة', 'value' => 'successful_mating_attempt'],
                        ['label' => 'الذكر / مرجع الأبوة الناتج من محاولة التلقيح', 'value' => 'paternity_reference'],
                        ['label' => 'تاريخ التلقيح المرجعي المستخدم للحساب', 'value' => 'reference_mating_at'],
                        ['label' => 'تاريخ تأكيد الحمل', 'value' => 'confirmed_at'],
                        ['label' => 'تاريخ الولادة المتوقع', 'value' => 'expected_birth_date'],
                        ['label' => 'بداية فترة الولادة المتوقعة', 'value' => 'expected_birth_window_start'],
                        ['label' => 'نهاية فترة الولادة المتوقعة', 'value' => 'expected_birth_window_end'],
                        ['label' => 'الحالة الحالية للحمل', 'value' => 'current_state'],
                        ['label' => 'ملاحظات', 'value' => 'notes'],
                    ],
                ],
                [
                    'seed_key' => 'pregnancy.current_state_model',
                    'title' => 'كيف يجب تحديد الحالة الحالية للحمل أثناء تقدمه؟',
                    'help_text' => 'التصور يذكر حالات مثل حمل مؤكد، اقتراب الولادة، الدخول في الفترة المتوقعة، التأخر، والانتهاء. المطلوب حسم هل تنتج هذه الحالة من الأحداث والتواريخ أم تعدل كقيمة يدوية.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 9,
                    'report_category' => 'lifecycle_rule',
                    'target_entity' => 'pregnancy',
                    'options' => [
                        ['label' => 'تستنتج من الفحوص والأحداث والتواريخ ولا تعدل يدويًا كحالة مستقلة', 'value' => 'derived_from_events_and_dates'],
                        ['label' => 'تخزن كحالة حالية لكن لا تتغير إلا عبر Actions / Transitions مسجلة', 'value' => 'stored_state_changed_by_recorded_transitions'],
                        ['label' => 'يمكن تعديل الحالة الحالية مباشرة من المستخدم', 'value' => 'direct_manual_state'],
                    ],
                ],
                [
                    'seed_key' => 'pregnancy.followup_event_types',
                    'title' => 'ما أحداث المتابعة الفعلية أثناء الحمل التي يجب أن يدعم هذا المسار تسجيلها أو الربط بها؟',
                    'help_text' => 'المصدر يذكر متابعة الحالة، الوزن، تجهيز الأنثى للولادة، وتركيب بيت الولادة. الوزن الفعلي يبقى في سجل الأوزان العام 4.3 ويشار إليه من سياق الحمل بدل تكرار القيمة.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 10,
                    'report_category' => 'workflow_model',
                    'target_entity' => 'pregnancy_followup',
                    'options' => [
                        ['label' => 'متابعة الحالة العامة للأنثى أثناء الحمل', 'value' => 'general_condition_followup'],
                        ['label' => 'ربط وزن فعلي مسجل في Weight History بسياق الحمل', 'value' => 'pregnancy_weight_reference'],
                        ['label' => 'تجهيز موقع الأنثى للولادة', 'value' => 'birth_site_preparation'],
                        ['label' => 'تركيب / تجهيز بيت الولادة', 'value' => 'nest_box_installation'],
                        ['label' => 'متابعة اقتراب موعد الولادة', 'value' => 'approaching_birth_followup'],
                    ],
                ],
                [
                    'seed_key' => 'pregnancy.followup_event_record_fields',
                    'title' => 'ما البيانات المشتركة التي يجب تسجيلها عند تنفيذ حدث متابعة أو تجهيز فعلي أثناء الحمل؟',
                    'help_text' => 'الهدف وجود أثر تاريخي لما تم تنفيذه بالفعل، وليس مجرد وجود موعد أو مهمة. تفاصيل الصيانة أو التطهير العامة للموقع تظل في Workflow مواقع الإيواء 4.16.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 11,
                    'report_category' => 'field',
                    'target_entity' => 'pregnancy_followup',
                    'options' => [
                        ['label' => 'الحمل المرتبط', 'value' => 'pregnancy'],
                        ['label' => 'نوع حدث المتابعة / التجهيز', 'value' => 'event_type'],
                        ['label' => 'تاريخ / وقت التنفيذ الفعلي', 'value' => 'performed_at'],
                        ['label' => 'القفص / الموقع وقت التنفيذ عند الحاجة', 'value' => 'cage'],
                        ['label' => 'مرجع سجل الوزن عند ارتباط الحدث بوزن', 'value' => 'weight_measurement_reference'],
                        ['label' => 'نتيجة / حالة ما تم تنفيذه', 'value' => 'result'],
                        ['label' => 'المستخدم / المنفذ', 'value' => 'performed_by'],
                        ['label' => 'ملاحظات', 'value' => 'notes'],
                    ],
                ],
                [
                    'seed_key' => 'pregnancy.expected_window_birth_behavior',
                    'title' => 'عند دخول الأنثى فترة الولادة المتوقعة أو تجاوزها دون تسجيل ولادة، كيف يجب أن يتصرف النظام؟',
                    'help_text' => 'الوصول إلى التاريخ المتوقع ليس حدث ولادة. المرجع يؤكد بقاء الأنثى في انتظار واقعة فعلية: تسجيل ولادة في 4.6 أو حالة استثنائية في 4.14، مع التنبيهات والمهام حسب Settings.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 12,
                    'report_category' => 'workflow_rule',
                    'target_entity' => 'pregnancy',
                    'options' => [
                        ['label' => 'تظل في انتظار حدث فعلي، ولا ينشئ النظام ولادة أو نهاية حمل تلقائيًا', 'value' => 'wait_for_actual_birth_or_exception_event'],
                        ['label' => 'ينشئ النظام واقعة ولادة متوقعة تلقائيًا عند نهاية الفترة ثم يعدلها المستخدم', 'value' => 'auto_create_expected_birth_event'],
                        ['label' => 'يغلق الحمل تلقائيًا عند تجاوز الفترة دون ولادة', 'value' => 'auto_close_pregnancy_after_window'],
                    ],
                ],
            ];

            app(QuestionSeederSyncService::class)->sync(
                mainSectionName: 'الحركات ودورة التشغيل الفعلية',
                sectionName: 'فحص الحمل ومتابعة الحمل وتجهيز الولادة',
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

        $resultCategories = $questions->get('pregnancy_check.result_categories');

        if (! $resultCategories) {
            return;
        }

        $dependencies = [
            'pregnancy_check.uncertain_result_flow' => 'uncertain',
            'pregnancy_check.positive_result_flow' => 'positive',
            'pregnancy_check.negative_result_flow' => 'negative',
        ];

        foreach ($dependencies as $dependentSeedKey => $dependencyValue) {
            $dependentQuestion = $questions->get($dependentSeedKey);

            if (! $dependentQuestion) {
                continue;
            }

            $dependentQuestion->forceFill([
                'depends_on_question_id' => $resultCategories->id,
                'dependency_operator' => QuestionDependencyOperator::CONTAINS,
                'dependency_value' => $dependencyValue,
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
            ->where('name', 'فحص الحمل ومتابعة الحمل وتجهيز الولادة')
            ->first();
    }
}
