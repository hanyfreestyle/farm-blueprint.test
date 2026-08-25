<?php

namespace Database\Seeders\Questions\Workflow;

use App\Enums\Questionnaire\QuestionDependencyOperator;
use App\Enums\Questionnaire\QuestionType;
use App\Models\QuestionnaireQuestion;
use App\Models\QuestionnaireSection;
use App\Services\Questionnaire\QuestionSeederSyncService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BirthLitterQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $questions = [
                [
                    'seed_key' => 'birth.event_record_fields',
                    'title' => 'ما البيانات التي يجب أن يحتفظ بها سجل الولادة الفعلية؟',
                    'help_text' => 'الولادة حدث فعلي ينهي مرحلة الحمل ويبدأ منها سجل البطن. البيانات المعروضة للمستخدم من سجل الحمل لا يلزم نسخها يدويًا إذا كان يمكن ربطها أو استنتاجها بصورة موثوقة.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 1,
                    'report_category' => 'field',
                    'target_entity' => 'birth_event',
                    'options' => [
                        ['label' => 'الأنثى / الأم', 'value' => 'mother'],
                        ['label' => 'سجل الحمل المرتبط', 'value' => 'pregnancy'],
                        ['label' => 'دورة الإنتاج المرتبطة', 'value' => 'reproductive_cycle'],
                        ['label' => 'مرجع الأبوة الناتج من مسار التلقيح والحمل', 'value' => 'paternity_reference'],
                        ['label' => 'تاريخ الولادة الفعلي', 'value' => 'birth_date'],
                        ['label' => 'وقت الولادة الفعلي إذا كان معروفًا أو مطلوبًا', 'value' => 'birth_time'],
                        ['label' => 'القفص / الموقع وقت الولادة', 'value' => 'birth_location'],
                        ['label' => 'المستخدم / منفذ التسجيل', 'value' => 'recorded_by'],
                        ['label' => 'ملاحظات', 'value' => 'notes'],
                    ],
                ],
                [
                    'seed_key' => 'birth.offspring_count_fields',
                    'title' => 'ما أعداد وبيانات المواليد التي يجب تسجيلها عند الولادة؟',
                    'help_text' => 'المرجع يميز بين إجمالي المواليد والأحياء والنافقين عند الولادة، ويشير إلى أن الحالات الخاصة مثل التشوه الظاهر قد تتداخل مع كون المولود حيًا أو نافقًا، لذلك لا تعامل تلقائيًا كفئة حسابية مستقلة.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 2,
                    'report_category' => 'field',
                    'target_entity' => 'birth_event',
                    'options' => [
                        ['label' => 'إجمالي المواليد', 'value' => 'total_born'],
                        ['label' => 'الأحياء عند الولادة', 'value' => 'live_born'],
                        ['label' => 'النافقون عند الولادة', 'value' => 'stillborn_at_birth'],
                        ['label' => 'حالات خاصة أو ملاحظات على بعض المواليد مثل عيب خلقي ظاهر', 'value' => 'special_conditions'],
                    ],
                ],
                [
                    'seed_key' => 'birth.count_entry_model',
                    'title' => 'كيف يجب إدخال والتحقق من أعداد المواليد الأساسية؟',
                    'help_text' => 'المصدر يقترح أن إجمالي المواليد يساوي الأحياء عند الولادة + النافقين عند الولادة، مع منع التعارض الحسابي. المطلوب حسم هل يحسب الإجمالي تلقائيًا أم يدخل ثم يتحقق منه.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 3,
                    'report_category' => 'validation_rule',
                    'target_entity' => 'birth_event',
                    'options' => [
                        ['label' => 'يدخل الأحياء والنافقون ويحسب النظام إجمالي المواليد تلقائيًا', 'value' => 'derive_total_from_live_and_stillborn'],
                        ['label' => 'يدخل المستخدم الإجمالي والأحياء والنافقين ويتحقق النظام من تطابق المعادلة', 'value' => 'enter_all_and_validate_total'],
                        ['label' => 'تسجل الأعداد بصورة مستقلة دون تحقق حسابي إلزامي', 'value' => 'independent_counts_without_hard_validation'],
                    ],
                ],
                [
                    'seed_key' => 'birth.special_condition_representation',
                    'title' => 'كيف يجب تمثيل الحالات الخاصة للمواليد عند الولادة؟',
                    'help_text' => 'الحالة الخاصة مثل وجود تشوه ظاهر قد تنطبق على مولود حي أو نافق، لذلك يجب حسم هل تسجل كصفة إضافية متداخلة أم كفئة عدد مستقلة. التصنيف الطبي التفصيلي غير مفترض هنا.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 4,
                    'report_category' => 'data_model_rule',
                    'target_entity' => 'birth_event',
                    'options' => [
                        ['label' => 'تسجل كصفة / عدد إضافي يمكن أن يتداخل مع حي أو نافق', 'value' => 'overlapping_additional_attribute'],
                        ['label' => 'تسجل كفئة عدد مستقلة ضمن معادلة إجمالي المواليد', 'value' => 'exclusive_count_category'],
                        ['label' => 'تسجل كملاحظات فقط دون قيمة عددية منظمة', 'value' => 'notes_only'],
                    ],
                ],
                [
                    'seed_key' => 'birth.outside_expected_window_behavior',
                    'title' => 'كيف يجب التعامل مع ولادة فعلية حدثت قبل أو بعد فترة الولادة المتوقعة؟',
                    'help_text' => 'المرجع يؤكد أن الولادة الفعلية تسجل بتاريخها الحقيقي حتى لو كانت خارج النطاق المتوقع، وأننا لا نعدل تاريخ التلقيح لجعل المدة تبدو طبيعية. هذا السؤال يحسم سلوك التسجيل والتحقق فقط.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 5,
                    'report_category' => 'workflow_rule',
                    'target_entity' => 'birth_event',
                    'options' => [
                        ['label' => 'يسمح بالتسجيل ويصنف النظام التوقيت تلقائيًا: داخل النطاق / مبكر / متأخر', 'value' => 'allow_and_derive_timing_classification'],
                        ['label' => 'يسمح بالتسجيل بعد تأكيد تحذير واضح مع الاحتفاظ بالتاريخ الحقيقي', 'value' => 'allow_after_warning_confirmation'],
                        ['label' => 'يمنع التسجيل خارج النطاق المتوقع حتى تتم معالجة الحالة بصورة منفصلة', 'value' => 'block_outside_expected_window'],
                    ],
                ],
                [
                    'seed_key' => 'birth.historical_location_model',
                    'title' => 'كيف يجب حفظ موقع الأنثى وقت الولادة تاريخيًا؟',
                    'help_text' => 'المصدر يذكر القفص ضمن بيانات الولادة، بينما الموقع الحالي للحيوان مشتق من حركات التسكين. المطلوب حسم هل يحفظ مرجع القفص داخل حدث الولادة أم يستنتج من حركة الإشغال عند وقت الحدث.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 6,
                    'report_category' => 'history_rule',
                    'target_entity' => 'birth_event',
                    'options' => [
                        ['label' => 'يحفظ مرجع القفص مباشرة داخل حدث الولادة كلقطة تاريخية', 'value' => 'store_birth_location_reference'],
                        ['label' => 'يستنتج الموقع من سجل الإشغال عند تاريخ ووقت الولادة دون تكرار المرجع', 'value' => 'derive_from_occupancy_history'],
                        ['label' => 'يحفظ المرجع داخل حدث الولادة ويقارنه النظام بسجل الإشغال للتحقق', 'value' => 'store_and_validate_against_occupancy'],
                    ],
                ],
                [
                    'seed_key' => 'litter.creation_model',
                    'title' => 'متى يجب إنشاء سجل البطن المرتبط بالولادة؟',
                    'help_text' => 'القسم المعتمد يفترض وجود Litter Record مستقل بعد الولادة. المطلوب حسم هل ينشأ تلقائيًا مع اعتماد حدث الولادة أم يحتاج خطوة مستقلة بعد التسجيل.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 7,
                    'report_category' => 'workflow_rule',
                    'target_entity' => 'litter',
                    'options' => [
                        ['label' => 'ينشأ سجل البطن تلقائيًا عند اعتماد حدث الولادة', 'value' => 'auto_create_on_birth'],
                        ['label' => 'يسجل حدث الولادة أولًا ثم ينشئ المستخدم البطن في خطوة مستقلة مرتبطة به', 'value' => 'explicit_create_after_birth'],
                    ],
                ],
                [
                    'seed_key' => 'litter.record_fields',
                    'title' => 'ما البيانات التي يجب أن يحتوي عليها سجل البطن عند إنشائه؟',
                    'help_text' => 'البطن هو سجل مستقل يستمر خلال الرضاعة حتى الفطام، ويجب أن يحتفظ بمرجعه إلى الولادة والأم والأب ودورة الإنتاج دون إعادة إدخال تاريخ منفصل غير موثوق.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 8,
                    'report_category' => 'field',
                    'target_entity' => 'litter',
                    'options' => [
                        ['label' => 'كود / رقم البطن', 'value' => 'litter_code'],
                        ['label' => 'حدث الولادة المصدر', 'value' => 'birth_event'],
                        ['label' => 'الأم البيولوجية', 'value' => 'biological_mother'],
                        ['label' => 'مرجع الأبوة', 'value' => 'paternity_reference'],
                        ['label' => 'دورة الإنتاج', 'value' => 'reproductive_cycle'],
                        ['label' => 'تاريخ الولادة', 'value' => 'birth_date'],
                        ['label' => 'القفص / موقع الولادة عند الحاجة', 'value' => 'birth_location'],
                        ['label' => 'إجمالي المواليد', 'value' => 'total_born'],
                        ['label' => 'الأحياء عند الولادة', 'value' => 'live_born'],
                        ['label' => 'النافقون عند الولادة', 'value' => 'stillborn_at_birth'],
                        ['label' => 'الحالات الخاصة عند الولادة', 'value' => 'special_conditions'],
                        ['label' => 'الحالة الحالية للبطن', 'value' => 'current_state'],
                        ['label' => 'ملاحظات', 'value' => 'notes'],
                    ],
                ],
                [
                    'seed_key' => 'litter.code_strategy',
                    'title' => 'كيف يجب إنشاء كود / رقم البطن؟',
                    'help_text' => 'السؤال يحسم مسؤولية إنشاء الكود فقط. شكل الكود ومكوناته وتسلسله إن كان قابلًا للتهيئة يراجع لاحقًا ضمن إعدادات الترقيم والأكواد.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 9,
                    'report_category' => 'identification_rule',
                    'target_entity' => 'litter',
                    'options' => [
                        ['label' => 'يولده النظام تلقائيًا', 'value' => 'automatic'],
                        ['label' => 'يدخله المستخدم يدويًا', 'value' => 'manual'],
                    ],
                ],
                [
                    'seed_key' => 'birth.no_live_offspring_behavior',
                    'title' => 'ماذا يجب أن يحدث إذا سجلت الولادة بدون أي مواليد أحياء؟',
                    'help_text' => 'المرجع يقترح الاحتفاظ بسجل الولادة والبطن تاريخيًا لأن النتيجة مهمة لتقييم الأم والأب، مع عدم بدء مسار رضاعة أو فطام لبطن بلا مواليد أحياء.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 10,
                    'report_category' => 'workflow_rule',
                    'target_entity' => 'litter',
                    'options' => [
                        ['label' => 'ينشأ البطن تاريخيًا ثم يغلق مباشرة كعدم وجود مواليد أحياء، ولا يبدأ رضاعة أو فطام', 'value' => 'create_and_close_no_live_offspring'],
                        ['label' => 'يسجل حدث الولادة فقط ولا ينشأ Litter Record', 'value' => 'birth_event_only_no_litter'],
                        ['label' => 'ينشأ البطن ويظل مفتوحًا حتى يغلقه المستخدم يدويًا', 'value' => 'create_litter_manual_close'],
                    ],
                ],
                [
                    'seed_key' => 'birth.post_registration_transitions',
                    'title' => 'ما الانتقالات التي يجب أن ينفذها النظام عند اعتماد تسجيل الولادة؟',
                    'help_text' => 'المطلوب تحديد النتائج المباشرة لحدث الولادة نفسه. قواعد إنشاء المهام ومواعيد الفطام أو إعادة التلقيح تظل في Settings، وتنفيذ الرضاعة والفطام له أقسام Workflow مستقلة.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 11,
                    'report_category' => 'workflow_transition',
                    'target_entity' => 'birth_event',
                    'options' => [
                        ['label' => 'إغلاق سجل الحمل بنتيجة «انتهى بولادة»', 'value' => 'close_pregnancy_as_birth'],
                        ['label' => 'تسجيل نتيجة دورة الإنتاج بأنها وصلت إلى ولادة', 'value' => 'record_cycle_birth_outcome'],
                        ['label' => 'إنشاء / تفعيل سجل البطن حسب نموذج الإنشاء المعتمد', 'value' => 'create_or_activate_litter'],
                        ['label' => 'بدء مرحلة الرضاعة تلقائيًا إذا كان هناك مواليد أحياء', 'value' => 'start_lactation_if_live_offspring'],
                        ['label' => 'حساب مدة الحمل الفعلية وتصنيف توقيت الولادة للتحليل', 'value' => 'derive_gestation_and_birth_timing'],
                    ],
                ],
            ];

            app(QuestionSeederSyncService::class)->sync(
                mainSectionName: 'الحركات ودورة التشغيل الفعلية',
                sectionName: 'تسجيل الولادة وإنشاء البطن',
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

        $offspringFields = $questions->get('birth.offspring_count_fields');
        $specialConditionRepresentation = $questions->get('birth.special_condition_representation');

        if ($offspringFields && $specialConditionRepresentation) {
            $specialConditionRepresentation->forceFill([
                'depends_on_question_id' => $offspringFields->id,
                'dependency_operator' => QuestionDependencyOperator::CONTAINS,
                'dependency_value' => 'special_conditions',
            ])->save();
        }

        $litterFields = $questions->get('litter.record_fields');
        $litterCodeStrategy = $questions->get('litter.code_strategy');

        if ($litterFields && $litterCodeStrategy) {
            $litterCodeStrategy->forceFill([
                'depends_on_question_id' => $litterFields->id,
                'dependency_operator' => QuestionDependencyOperator::CONTAINS,
                'dependency_value' => 'litter_code',
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
            ->where('name', 'تسجيل الولادة وإنشاء البطن')
            ->first();
    }
}
