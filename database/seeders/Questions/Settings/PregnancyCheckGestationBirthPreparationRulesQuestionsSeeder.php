<?php

namespace Database\Seeders\Questions\Settings;

use App\Enums\Questionnaire\QuestionDependencyOperator;
use App\Enums\Questionnaire\QuestionType;
use App\Models\QuestionnaireQuestion;
use App\Models\QuestionnaireSection;
use App\Services\Questionnaire\QuestionSeederSyncService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PregnancyCheckGestationBirthPreparationRulesQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $questions = [
                [
                    'seed_key' => 'pregnancy_rules.reference_mating_event_model',
                    'title' => 'أي عملية تلقيح فعلية يجب أن يستخدمها النظام كمرجع زمني لحساب مواعيد فحص الحمل والحمل المتوقع؟',
                    'help_text' => 'التصور يترك مفتوحًا هل يبدأ حساب موعد الجس من أول تلقيح أم آخر تلقيح داخل المحاولة. المطلوب اختيار مرجع واحد واضح من أحداث 4.4 حتى لا تختلف المواعيد بين الشاشات والمهام والتقارير.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 1,
                    'report_category' => 'timing_reference_rule',
                    'target_entity' => 'pregnancy_timing_rule',
                    'options' => [
                        ['label' => 'أول عملية تلقيح فعلية داخل المحاولة', 'value' => 'first_mating_event_in_attempt'],
                        ['label' => 'آخر عملية تلقيح فعلية داخل المحاولة', 'value' => 'last_mating_event_in_attempt'],
                        ['label' => 'عملية تلقيح مرجعية Canonical تحددها قاعدة المحاولة وتُحفظ صراحة', 'value' => 'canonical_reference_mating_event'],
                    ],
                ],
                [
                    'seed_key' => 'pregnancy_rules.first_check_target_days',
                    'title' => 'بعد كم يوم من عملية التلقيح المرجعية يكون الموعد المستهدف لأول فحص حمل / جس؟',
                    'help_text' => 'لا يفرض النظام رقمًا ثابتًا. القيمة تستخدم لحساب الموعد المستهدف انطلاقًا من مرجع التلقيح المعتمد في السؤال السابق.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 2,
                    'report_category' => 'pregnancy_check_timing',
                    'target_entity' => 'pregnancy_check_rule',
                    'options' => [],
                ],
                [
                    'seed_key' => 'pregnancy_rules.check_window_model',
                    'title' => 'هل موعد فحص الحمل يجب أن يكون يومًا مستهدفًا فقط أم نافذة زمنية مسموحة حوله؟',
                    'help_text' => 'التصور يسأل صراحة هل يوجد نطاق أيام مناسب بدل يوم واحد. النافذة تحدد متى يمكن تنفيذ الفحص بصورة طبيعية، بينما سياسة التنفيذ قبلها أو بعد انتهائها تحسم في أسئلة منفصلة.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 3,
                    'report_category' => 'pregnancy_check_timing',
                    'target_entity' => 'pregnancy_check_rule',
                    'options' => [
                        ['label' => 'يوم مستهدف فقط دون نافذة مستقلة', 'value' => 'target_day_only'],
                        ['label' => 'يوم مستهدف مع بداية ونهاية لنافذة التنفيذ المسموحة', 'value' => 'target_day_with_allowed_window'],
                    ],
                ],
                [
                    'seed_key' => 'pregnancy_rules.check_window_start_days_from_reference',
                    'title' => 'من اليوم رقم كام بعد التلقيح المرجعي تبدأ نافذة فحص الحمل المسموحة؟',
                    'help_text' => 'هذه القيمة تمثل أبكر يوم داخل النافذة الطبيعية للفحص عند اعتماد نموذج النافذة، وليست تاريخ التنفيذ الفعلي.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 4,
                    'report_category' => 'pregnancy_check_window',
                    'target_entity' => 'pregnancy_check_rule',
                    'options' => [],
                ],
                [
                    'seed_key' => 'pregnancy_rules.check_window_end_days_from_reference',
                    'title' => 'حتى اليوم رقم كام بعد التلقيح المرجعي تظل نافذة فحص الحمل الطبيعية مفتوحة؟',
                    'help_text' => 'هذه القيمة تحدد نهاية النافذة الطبيعية عند اعتمادها. اعتبار المهمة متأخرة بعد ذلك يخضع لقاعدة التأخير أدناه.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 5,
                    'report_category' => 'pregnancy_check_window',
                    'target_entity' => 'pregnancy_check_rule',
                    'options' => [],
                ],
                [
                    'seed_key' => 'pregnancy_rules.early_check_policy',
                    'title' => 'إذا حاول المستخدم تسجيل فحص حمل قبل الموعد أو قبل بداية النافذة المعتمدة، كيف يجب أن تتعامل القاعدة مع ذلك؟',
                    'help_text' => 'المصدر يسأل هل يسمح بإجراء الجس مبكرًا لاحتمال اختلاف الدقة أو ملاءمة التوقيت. سياسة الـOverride العامة وتوثيقه موجودان في 6.2؛ هنا نحدد طبيعة هذه القاعدة تحديدًا.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 6,
                    'report_category' => 'pregnancy_check_validation',
                    'target_entity' => 'pregnancy_check_rule',
                    'options' => [
                        ['label' => 'Hard Constraint: يمنع تسجيل الفحص كفحص صالح قبل أبكر وقت مسموح', 'value' => 'hard_block_before_allowed_time'],
                        ['label' => 'Warning مع إمكانية Override موثق وفق 6.2', 'value' => 'warning_with_governed_override'],
                        ['label' => 'يسمح بالتسجيل مع إظهار أنه تم مبكرًا دون منع', 'value' => 'allow_and_flag_as_early'],
                    ],
                ],
                [
                    'seed_key' => 'pregnancy_rules.overdue_check_model',
                    'title' => 'متى يعتبر فحص الحمل مستحقًا متأخرًا إذا لم يتم تنفيذه؟',
                    'help_text' => 'عدم تنفيذ الفحص لا ينشئ نتيجة حمل. المطلوب فقط تعريف نقطة التأخير التي تستخدمها منظومة المهام والتنبيهات لاحقًا في 6.12.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 7,
                    'report_category' => 'pregnancy_check_overdue_rule',
                    'target_entity' => 'pregnancy_check_task_rule',
                    'options' => [
                        ['label' => 'يصبح متأخرًا فور تجاوز الموعد المستهدف أو نهاية النافذة المعتمدة', 'value' => 'overdue_immediately_after_due_boundary'],
                        ['label' => 'يصبح متأخرًا بعد فترة سماح قابلة للضبط بعد الموعد / نهاية النافذة', 'value' => 'overdue_after_configured_grace_period'],
                    ],
                ],
                [
                    'seed_key' => 'pregnancy_rules.overdue_check_grace_days',
                    'title' => 'كم يوم سماح بعد موعد فحص الحمل أو نهاية نافذته قبل اعتباره متأخرًا؟',
                    'help_text' => 'تستخدم هذه القيمة فقط عند اعتماد فترة سماح قبل تصنيف الفحص كمتأخر.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 8,
                    'report_category' => 'pregnancy_check_overdue_threshold',
                    'target_entity' => 'pregnancy_check_task_rule',
                    'options' => [],
                ],
                [
                    'seed_key' => 'pregnancy_rules.uncertain_recheck_delay_days',
                    'title' => 'بعد كم يوم من فحص حمل نتيجته «غير مؤكدة» يجب استحقاق إعادة الفحص؟',
                    'help_text' => '4.5 يحتفظ بالفحص الأول ويسمح بفحص جديد داخل نفس المحاولة. هذه القيمة تحدد فقط موعد إعادة الفحص ولا تستبدل النتيجة السابقة.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 9,
                    'report_category' => 'pregnancy_recheck_timing',
                    'target_entity' => 'pregnancy_check_rule',
                    'options' => [],
                ],
                [
                    'seed_key' => 'pregnancy_rules.uncertain_recheck_limit_model',
                    'title' => 'هل يجب وضع حد أقصى لعدد مرات إعادة فحص الحمل بسبب النتائج غير المؤكدة داخل نفس محاولة التلقيح؟',
                    'help_text' => 'التصور يضع هذا كقرار يحتاج معنى تشغيليًا واضحًا. تجاوز الحد لا ينشئ تشخيصًا تلقائيًا؛ أي إجراء أو تنبيه لاحق تحكمه قواعده الخاصة.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 10,
                    'report_category' => 'pregnancy_recheck_rule',
                    'target_entity' => 'pregnancy_check_rule',
                    'options' => [
                        ['label' => 'لا يوجد حد أقصى ثابت؛ يستمر إعادة الفحص عند الحاجة', 'value' => 'no_fixed_recheck_limit'],
                        ['label' => 'يوجد حد أقصى قابل للضبط لعدد مرات إعادة الفحص غير المؤكد', 'value' => 'configured_maximum_rechecks'],
                    ],
                ],
                [
                    'seed_key' => 'pregnancy_rules.uncertain_recheck_max_count',
                    'title' => 'ما الحد الأقصى لمرات إعادة فحص الحمل بسبب نتيجة غير مؤكدة داخل نفس المحاولة؟',
                    'help_text' => 'لا يشمل الفحص الأول؛ المقصود عدد إعادة الفحوص الإضافية المسموح بها عند اعتماد حد أقصى.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 11,
                    'report_category' => 'pregnancy_recheck_limit',
                    'target_entity' => 'pregnancy_check_rule',
                    'options' => [],
                ],
                [
                    'seed_key' => 'pregnancy_rules.gestation_parameters',
                    'title' => 'ما المخرجات الزمنية التي يجب أن يحسبها النظام للحمل المؤكد اعتمادًا على مدة الحمل؟',
                    'help_text' => 'المصدر يقترح متوسط مدة حمل ونطاقًا متوقعًا للولادة، وينبه إلى أن الفترة قد تكون أدق من الاعتماد على يوم واحد فقط. اختر المخرجات التي نحتاجها دون فرض قيم مسبقة.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 12,
                    'report_category' => 'gestation_rule',
                    'target_entity' => 'pregnancy_timing_rule',
                    'options' => [
                        ['label' => 'تاريخ ولادة متوقع اسمي محسوب من متوسط مدة الحمل', 'value' => 'nominal_expected_birth_date'],
                        ['label' => 'بداية ونهاية فترة الولادة المتوقعة', 'value' => 'expected_birth_window'],
                    ],
                ],
                [
                    'seed_key' => 'pregnancy_rules.gestation_average_days',
                    'title' => 'ما متوسط مدة الحمل بالأيام المستخدم لحساب تاريخ الولادة المتوقع الاسمي؟',
                    'help_text' => 'هذه قيمة إعداد قابلة للضبط وليست رقمًا ثابتًا في النظام.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 13,
                    'report_category' => 'gestation_target',
                    'target_entity' => 'pregnancy_timing_rule',
                    'options' => [],
                ],
                [
                    'seed_key' => 'pregnancy_rules.expected_birth_window_start_days',
                    'title' => 'بعد كم يوم من التلقيح المرجعي تبدأ فترة الولادة المتوقعة؟',
                    'help_text' => 'هذه القيمة تحدد أول يوم داخل الفترة المتوقعة عند اعتماد Expected Birth Window.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 14,
                    'report_category' => 'gestation_window',
                    'target_entity' => 'pregnancy_timing_rule',
                    'options' => [],
                ],
                [
                    'seed_key' => 'pregnancy_rules.expected_birth_window_end_days',
                    'title' => 'بعد كم يوم من التلقيح المرجعي تنتهي فترة الولادة المتوقعة؟',
                    'help_text' => 'هذه القيمة تحدد آخر يوم داخل الفترة المتوقعة عند اعتماد Expected Birth Window.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 15,
                    'report_category' => 'gestation_window',
                    'target_entity' => 'pregnancy_timing_rule',
                    'options' => [],
                ],
                [
                    'seed_key' => 'pregnancy_rules.late_birth_basis_model',
                    'title' => 'ما المرجع الذي يجب استخدامه لتحديد أن الولادة أصبحت متأخرة دون أن نفترض حدوث ولادة تلقائيًا؟',
                    'help_text' => '4.5 يقرر أن تجاوز الموعد لا ينشئ Birth Event ولا يغلق الحمل. المطلوب هنا تحديد المرجع الزمني الذي تبدأ بعده حالة التأخر فقط.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 16,
                    'report_category' => 'late_birth_rule',
                    'target_entity' => 'pregnancy_timing_rule',
                    'options' => [
                        ['label' => 'من تاريخ الولادة المتوقع الاسمي', 'value' => 'nominal_expected_birth_date'],
                        ['label' => 'من نهاية فترة الولادة المتوقعة', 'value' => 'expected_birth_window_end'],
                        ['label' => 'عند وجود الاثنين يستخدم النظام نهاية الفترة المتوقعة كحد نهائي', 'value' => 'window_end_when_available_otherwise_nominal_date'],
                    ],
                ],
                [
                    'seed_key' => 'pregnancy_rules.late_birth_grace_days',
                    'title' => 'بعد كم يوم من مرجع التأخر المعتمد تعتبر الولادة متأخرة وتحتاج الحالة إلى متابعة؟',
                    'help_text' => 'يمكن أن تكون القيمة صفرًا إذا كان التأخر يبدأ مباشرة بعد المرجع. إنشاء Alert أو Task ومستواه وأولويته يعالج في 6.12.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 17,
                    'report_category' => 'late_birth_threshold',
                    'target_entity' => 'pregnancy_timing_rule',
                    'options' => [],
                ],
                [
                    'seed_key' => 'pregnancy_rules.prebirth_preparation_model',
                    'title' => 'هل يجب أن توجد مرحلة استعداد مجدولة قبل الولادة المتوقعة؟',
                    'help_text' => 'المصدر يقترح بدء الاستعداد قبل الولادة بعدد أيام محدد. هذا السؤال يحدد وجود القاعدة نفسها؛ تنفيذ أعمال التجهيز فعليًا يظل في 4.5.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 18,
                    'report_category' => 'prebirth_preparation_rule',
                    'target_entity' => 'pregnancy_followup_rule',
                    'options' => [
                        ['label' => 'لا توجد مرحلة استعداد مستقلة مجدولة', 'value' => 'no_separate_scheduled_preparation_stage'],
                        ['label' => 'تبدأ مرحلة الاستعداد قبل الولادة بفترة زمنية قابلة للضبط', 'value' => 'configured_prebirth_preparation_lead_time'],
                    ],
                ],
                [
                    'seed_key' => 'pregnancy_rules.prebirth_preparation_lead_days',
                    'title' => 'قبل مرجع الولادة المتوقع بكم يوم تبدأ مرحلة الاستعداد للولادة؟',
                    'help_text' => 'تستخدم هذه القيمة عند اعتماد مرحلة استعداد مجدولة قبل الولادة.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 19,
                    'report_category' => 'prebirth_preparation_timing',
                    'target_entity' => 'pregnancy_followup_rule',
                    'options' => [],
                ],
                [
                    'seed_key' => 'pregnancy_rules.nest_box_preparation_model',
                    'title' => 'كيف يجب أن تعمل قاعدة تجهيز / تركيب بيت الولادة قبل الولادة؟',
                    'help_text' => 'التصور يفرق بين بيت ولادة ثابت وبين تجهيز يركب قبل الولادة. إذا كان التركيب حدثًا تشغيليًا، يسجل تنفيذه في 4.5؛ هنا نحدد فقط هل له موعد مستقل محسوب.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 20,
                    'report_category' => 'nest_box_rule',
                    'target_entity' => 'nest_box_preparation_rule',
                    'options' => [
                        ['label' => 'لا توجد قاعدة تركيب مجدولة مستقلة لأن بيت الولادة ثابت أو غير متتبع بهذه الطريقة', 'value' => 'no_separate_scheduled_nest_box_installation'],
                        ['label' => 'يعتبر تجهيز بيت الولادة جزءًا من مرحلة الاستعداد العامة دون موعد مستقل', 'value' => 'use_general_prebirth_preparation_timing'],
                        ['label' => 'لتركيب بيت الولادة موعد مستقل قبل الولادة بفترة قابلة للضبط', 'value' => 'scheduled_separate_nest_box_installation'],
                    ],
                ],
                [
                    'seed_key' => 'pregnancy_rules.nest_box_installation_lead_days',
                    'title' => 'قبل مرجع الولادة المتوقع بكم يوم يستحق تركيب / تجهيز بيت الولادة؟',
                    'help_text' => 'هذه القيمة تستخدم فقط إذا كان لبيت الولادة موعد تجهيز مستقل عن بداية الاستعداد العامة.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 21,
                    'report_category' => 'nest_box_timing',
                    'target_entity' => 'nest_box_preparation_rule',
                    'options' => [],
                ],
                [
                    'seed_key' => 'pregnancy_rules.prebirth_followup_model',
                    'title' => 'كيف يجب جدولة متابعة الأنثى في الأيام الأخيرة قبل الولادة؟',
                    'help_text' => 'المصدر يضع دورية متابعة الأنثى في الأيام الأخيرة كنقطة تحتاج مراجعة. أحداث المتابعة المنفذة تحفظ في 4.5؛ هنا نحدد قاعدة الجدولة فقط.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 22,
                    'report_category' => 'prebirth_followup_rule',
                    'target_entity' => 'pregnancy_followup_rule',
                    'options' => [
                        ['label' => 'لا توجد متابعة دورية مجدولة؛ تكتفى بالمتابعة عند الحاجة', 'value' => 'no_recurring_prebirth_followup'],
                        ['label' => 'متابعة واحدة عند بدء مرحلة الاستعداد للولادة', 'value' => 'single_followup_at_preparation_start'],
                        ['label' => 'متابعة دورية بفاصل أيام قابل للضبط خلال الفترة الأخيرة', 'value' => 'recurring_prebirth_followup'],
                    ],
                ],
                [
                    'seed_key' => 'pregnancy_rules.prebirth_followup_interval_days',
                    'title' => 'كل كم يوم تتكرر متابعة الأنثى خلال الفترة الأخيرة قبل الولادة؟',
                    'help_text' => 'تستخدم هذه القيمة فقط عند اعتماد متابعة دورية قبل الولادة.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 23,
                    'report_category' => 'prebirth_followup_interval',
                    'target_entity' => 'pregnancy_followup_rule',
                    'options' => [],
                ],
                [
                    'seed_key' => 'pregnancy_rules.pregnancy_weight_followup_model',
                    'title' => 'هل يحتاج الحمل إلى برنامج وزن مجدول للأنثى ضمن المتابعة؟',
                    'help_text' => 'المصدر يترك متابعة الوزن أثناء الحمل سؤالًا مفتوحًا. أي وزن فعلي يسجل في Weight History 4.3؛ هذا السؤال يحدد فقط هل توجد قاعدة جدولة مرتبطة بالحمل.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 24,
                    'report_category' => 'pregnancy_weight_rule',
                    'target_entity' => 'pregnancy_followup_rule',
                    'options' => [
                        ['label' => 'لا يوجد برنامج وزن مجدول خاص بالحمل', 'value' => 'no_pregnancy_specific_weight_schedule'],
                        ['label' => 'تحدد نقاط / مراحل وزن معينة أثناء الحمل', 'value' => 'configured_pregnancy_weight_checkpoints'],
                        ['label' => 'وزن دوري بفاصل أيام قابل للضبط أثناء الحمل', 'value' => 'periodic_pregnancy_weight_schedule'],
                    ],
                ],
                [
                    'seed_key' => 'pregnancy_rules.pregnancy_weight_interval_days',
                    'title' => 'كل كم يوم يستحق وزن الأنثى أثناء الحمل عند استخدام برنامج وزن دوري؟',
                    'help_text' => 'هذه القيمة تحدد الجدولة فقط. الوزن المنفذ نفسه يبقى سجلًا Canonical في 4.3.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 25,
                    'report_category' => 'pregnancy_weight_interval',
                    'target_entity' => 'pregnancy_followup_rule',
                    'options' => [],
                ],
            ];

            app(QuestionSeederSyncService::class)->sync(
                mainSectionName: 'الإعدادات وقواعد التشغيل',
                sectionName: 'قواعد فحص الحمل والحمل وتجهيز الولادة',
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

        $dependencies = [
            ['pregnancy_rules.check_window_model', 'pregnancy_rules.check_window_start_days_from_reference', QuestionDependencyOperator::EQUALS, 'target_day_with_allowed_window'],
            ['pregnancy_rules.check_window_model', 'pregnancy_rules.check_window_end_days_from_reference', QuestionDependencyOperator::EQUALS, 'target_day_with_allowed_window'],
            ['pregnancy_rules.overdue_check_model', 'pregnancy_rules.overdue_check_grace_days', QuestionDependencyOperator::EQUALS, 'overdue_after_configured_grace_period'],
            ['pregnancy_rules.uncertain_recheck_limit_model', 'pregnancy_rules.uncertain_recheck_max_count', QuestionDependencyOperator::EQUALS, 'configured_maximum_rechecks'],
            ['pregnancy_rules.gestation_parameters', 'pregnancy_rules.gestation_average_days', QuestionDependencyOperator::CONTAINS, 'nominal_expected_birth_date'],
            ['pregnancy_rules.gestation_parameters', 'pregnancy_rules.expected_birth_window_start_days', QuestionDependencyOperator::CONTAINS, 'expected_birth_window'],
            ['pregnancy_rules.gestation_parameters', 'pregnancy_rules.expected_birth_window_end_days', QuestionDependencyOperator::CONTAINS, 'expected_birth_window'],
            ['pregnancy_rules.prebirth_preparation_model', 'pregnancy_rules.prebirth_preparation_lead_days', QuestionDependencyOperator::EQUALS, 'configured_prebirth_preparation_lead_time'],
            ['pregnancy_rules.nest_box_preparation_model', 'pregnancy_rules.nest_box_installation_lead_days', QuestionDependencyOperator::EQUALS, 'scheduled_separate_nest_box_installation'],
            ['pregnancy_rules.prebirth_followup_model', 'pregnancy_rules.prebirth_followup_interval_days', QuestionDependencyOperator::EQUALS, 'recurring_prebirth_followup'],
            ['pregnancy_rules.pregnancy_weight_followup_model', 'pregnancy_rules.pregnancy_weight_interval_days', QuestionDependencyOperator::EQUALS, 'periodic_pregnancy_weight_schedule'],
        ];

        foreach ($dependencies as [$parentSeedKey, $childSeedKey, $operator, $value]) {
            $parent = $questions->get($parentSeedKey);
            $child = $questions->get($childSeedKey);

            if (! $parent || ! $child) {
                continue;
            }

            $child->forceFill([
                'depends_on_question_id' => $parent->id,
                'dependency_operator' => $operator,
                'dependency_value' => $value,
            ])->save();
        }
    }

    private function resolveSection(): ?QuestionnaireSection
    {
        $mainSection = QuestionnaireSection::query()
            ->whereNull('parent_id')
            ->where('name', 'الإعدادات وقواعد التشغيل')
            ->first();

        if (! $mainSection) {
            return null;
        }

        return QuestionnaireSection::query()
            ->where('parent_id', $mainSection->id)
            ->where('name', 'قواعد فحص الحمل والحمل وتجهيز الولادة')
            ->first();
    }
}
