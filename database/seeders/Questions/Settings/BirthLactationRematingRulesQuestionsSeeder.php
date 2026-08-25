<?php

namespace Database\Seeders\Questions\Settings;

use App\Enums\Questionnaire\QuestionDependencyOperator;
use App\Enums\Questionnaire\QuestionType;
use App\Models\QuestionnaireQuestion;
use App\Models\QuestionnaireSection;
use App\Services\Questionnaire\QuestionSeederSyncService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BirthLactationRematingRulesQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $questions = [
                [
                    'seed_key' => 'lactation_rules.duration_source_model',
                    'title' => 'كيف يجب تحديد المدة المستهدفة لمرحلة الرضاعة دون إنشاء معيار فطام مكرر؟',
                    'help_text' => 'المصدر يذكر مدة رضاعة مستهدفة، بينما العمر والوزن وشروط الفطام التفصيلية ستحدد في 6.8. المطلوب هنا حسم هل مدة الرضاعة مجرد انعكاس لموعد الفطام المستهدف أم قيمة تشغيلية مستقلة.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 1,
                    'report_category' => 'lactation_duration_rule',
                    'target_entity' => 'lactation_rule',
                    'options' => [
                        ['label' => 'تشتق مدة الرضاعة المستهدفة من العمر / الموعد المستهدف للفطام في 6.8', 'value' => 'derive_from_weaning_target'],
                        ['label' => 'توجد مدة رضاعة مستهدفة مستقلة قابلة للضبط', 'value' => 'independent_lactation_target_duration'],
                        ['label' => 'لا توجد مدة رضاعة مستهدفة مستقلة؛ تنتهي المرحلة فقط بالحدث الفعلي المناسب', 'value' => 'no_target_duration'],
                    ],
                ],
                [
                    'seed_key' => 'lactation_rules.target_duration_days',
                    'title' => 'كم يومًا تكون مدة الرضاعة المستهدفة عند اعتماد مدة مستقلة؟',
                    'help_text' => 'هذه قيمة تخطيطية ولا تنهي الرضاعة تلقائيًا بمجرد مرورها؛ انتهاء المرحلة الفعلي محفوظ في Workflow 4.7 و4.8.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 2,
                    'report_category' => 'lactation_duration_target',
                    'target_entity' => 'lactation_rule',
                    'options' => [],
                ],
                [
                    'seed_key' => 'lactation_rules.litter_followup_schedule_model',
                    'title' => 'كيف يجب جدولة متابعة البطن أثناء فترة الرضاعة؟',
                    'help_text' => 'المصدر يذكر دورية متابعة البطن كإعداد تشغيل. تنفيذ المتابعة نفسها يسجل في 4.7، بينما إنشاء المهام من المواعيد سيحسم في 6.12.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 3,
                    'report_category' => 'followup_schedule_rule',
                    'target_entity' => 'lactation_followup_rule',
                    'options' => [
                        ['label' => 'لا توجد متابعة دورية ثابتة؛ المتابعة عند الحاجة أو الأحداث فقط', 'value' => 'event_or_need_based_followup'],
                        ['label' => 'متابعة دورية كل عدد أيام محدد طوال الرضاعة', 'value' => 'periodic_interval_followup'],
                        ['label' => 'متابعة عند نقاط / أعمار محددة من عمر البطن بدل فاصل ثابت', 'value' => 'configured_age_checkpoint_followup'],
                    ],
                ],
                [
                    'seed_key' => 'lactation_rules.litter_followup_interval_days',
                    'title' => 'كل كم يوم تستحق متابعة البطن عند استخدام المتابعة الدورية؟',
                    'help_text' => 'القيمة تحدد الجدولة فقط ولا تنشئ سجل متابعة قبل التنفيذ الفعلي.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 4,
                    'report_category' => 'followup_interval',
                    'target_entity' => 'lactation_followup_rule',
                    'options' => [],
                ],
                [
                    'seed_key' => 'lactation_rules.followup_review_contents',
                    'title' => 'ما المعلومات التي يجب مراجعتها عند استحقاق متابعة البطن أثناء الرضاعة؟',
                    'help_text' => 'هذه ليست حقولًا جديدة داخل البطن؛ المطلوب تحديد سياق المراجعة باستخدام البيانات والسجلات الموجودة مثل النفوق والوزن وحالة الأم، مع الحفاظ على فصل النافق عند الولادة عن النفوق بعد الولادة.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 5,
                    'report_category' => 'followup_rule',
                    'target_entity' => 'lactation_followup_rule',
                    'options' => [
                        ['label' => 'العدد الحالي للمواليد الأحياء المشتق من الأحداث', 'value' => 'current_alive_count'],
                        ['label' => 'النفوق بعد الولادة خلال الرضاعة', 'value' => 'postbirth_mortality_context'],
                        ['label' => 'عمر البطن / المواليد', 'value' => 'litter_age'],
                        ['label' => 'الأوزان المسجلة للمواليد', 'value' => 'offspring_weight_history'],
                        ['label' => 'نسبة البقاء / التغير في العدد', 'value' => 'survival_context'],
                        ['label' => 'الحالة العامة للبطن ووجود مواليد ضعيفة', 'value' => 'litter_condition'],
                        ['label' => 'حالة الأم وقدرتها على الرعاية / الرضاعة', 'value' => 'maternal_condition'],
                        ['label' => 'الموعد المتوقع للفطام وسياق الاستعداد له', 'value' => 'expected_weaning_context'],
                    ],
                ],
                [
                    'seed_key' => 'lactation_rules.offspring_weight_schedule_model',
                    'title' => 'كيف يجب جدولة وزن المواليد أثناء فترة الرضاعة؟',
                    'help_text' => 'أي وزن منفذ فعليًا يبقى في Weight History الموحد 4.3. المطلوب هنا فقط تحديد هل توجد مواعيد وزن مرتبطة بالرضاعة وكيف تحدد.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 6,
                    'report_category' => 'weight_schedule_rule',
                    'target_entity' => 'lactation_weight_rule',
                    'options' => [
                        ['label' => 'لا يوجد برنامج وزن خاص أثناء الرضاعة', 'value' => 'no_lactation_weight_schedule'],
                        ['label' => 'وزن دوري بفاصل أيام ثابت قابل للضبط', 'value' => 'periodic_interval_weight_schedule'],
                        ['label' => 'وزن عند أعمار / نقاط متابعة محددة من عمر البطن', 'value' => 'configured_age_checkpoint_weight_schedule'],
                        ['label' => 'الوزن عند الحاجة فقط ضمن المتابعة دون جدول ثابت', 'value' => 'weight_when_needed'],
                    ],
                ],
                [
                    'seed_key' => 'lactation_rules.offspring_weight_interval_days',
                    'title' => 'كل كم يوم يستحق وزن المواليد عند استخدام برنامج وزن دوري أثناء الرضاعة؟',
                    'help_text' => 'هذه القيمة للجدولة فقط، ولا تكرر قيمة الوزن الفعلية خارج السجل الموحد.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 7,
                    'report_category' => 'weight_schedule_interval',
                    'target_entity' => 'lactation_weight_rule',
                    'options' => [],
                ],
                [
                    'seed_key' => 'lactation_rules.offspring_weight_checkpoint_ages',
                    'title' => 'ما أعمار المواليد التي تستخدم كنقاط وزن محددة أثناء الرضاعة؟',
                    'help_text' => 'اكتب أعمار نقاط الوزن بالأيام عند اعتماد نموذج الأعمار المحددة. لا تستخدم أرقامًا افتراضية لمجرد أنها شائعة؛ المطلوب تسجيل برنامج التشغيل الفعلي للمزرعة.',
                    'type' => QuestionType::TEXTAREA,
                    'is_required' => true,
                    'sort_order' => 8,
                    'report_category' => 'weight_schedule_checkpoints',
                    'target_entity' => 'lactation_weight_rule',
                    'options' => [],
                ],
                [
                    'seed_key' => 'lactation_rules.remating_readiness_model',
                    'title' => 'عند السماح بإعادة تلقيح الأم أثناء الرضاعة وفق 6.5، كيف يجب تقييم جاهزيتها في موعد إعادة التلقيح؟',
                    'help_text' => '6.5 حسم أصل السماح بالتلقيح أثناء الرضاعة وتوقيت الاستحقاق العام. هذا السؤال لا يعيد ذلك القرار، بل يحسم هل تكفي شروط الجاهزية العامة أم نحتاج شروطًا إضافية مرتبطة بحالة الأم والبطن بعد الولادة.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 9,
                    'report_category' => 'remating_readiness_rule',
                    'target_entity' => 'lactating_mother_remating_readiness',
                    'options' => [
                        ['label' => 'تطبق شروط جاهزية الأنثى العامة في 6.5 فقط', 'value' => 'use_general_female_readiness_only'],
                        ['label' => 'تطبق شروط 6.5 مع شروط إضافية خاصة بحالة الأم أثناء الرضاعة', 'value' => 'general_plus_lactation_specific_readiness'],
                        ['label' => 'تحتاج كل حالة مرضعة إلى تقييم تشغيلي صريح بجانب الشروط الآلية', 'value' => 'general_rules_plus_explicit_maternal_review'],
                    ],
                ],
                [
                    'seed_key' => 'lactation_rules.lactation_specific_remating_factors',
                    'title' => 'ما العوامل الإضافية الخاصة بفترة الرضاعة التي يجب أن تدخل في تقييم إعادة تلقيح الأم؟',
                    'help_text' => 'المصدر يذكر الوزن والحالة الصحية وضعف حالة الجسم ومشكلة البطن الحالية كأسباب قد تمنع أو تؤجل إعادة التلقيح. هذه عوامل تقييم وليست نتائج تلقيح أو حالات يدوية جديدة.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 10,
                    'report_category' => 'remating_readiness_rule',
                    'target_entity' => 'lactating_mother_remating_readiness',
                    'options' => [
                        ['label' => 'وزن الأم الحالي مقارنة بالحد المعتمد', 'value' => 'current_weight'],
                        ['label' => 'الحالة الصحية الحالية', 'value' => 'health_condition'],
                        ['label' => 'حالة الجسم / التعافي بعد الولادة', 'value' => 'body_condition_or_postpartum_recovery'],
                        ['label' => 'وجود مشكلة في البطن الحالية تستوجب تأجيل دورة جديدة', 'value' => 'active_litter_problem'],
                        ['label' => 'قرار راحة تشغيلي موثق للأم', 'value' => 'documented_maternal_rest_decision'],
                    ],
                ],
                [
                    'seed_key' => 'lactation_rules.remating_weight_policy',
                    'title' => 'كيف يجب استخدام وزن الأم عند تقييم إعادة التلقيح أثناء الرضاعة؟',
                    'help_text' => '6.5 يحتوي حد الوزن العام للأنثى عند التلقيح. المصدر يترك مفتوحًا هل الوزن وحده كافٍ أو مجرد عامل من عدة عوامل، وهل نحتاج حدًا خاصًا بفترة الرضاعة.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 11,
                    'report_category' => 'remating_weight_rule',
                    'target_entity' => 'lactating_mother_remating_readiness',
                    'options' => [
                        ['label' => 'يستخدم نفس حد الوزن العام المعتمد في 6.5', 'value' => 'use_general_mating_weight_threshold'],
                        ['label' => 'يستخدم حد وزن مستقل خاص بإعادة التلقيح أثناء الرضاعة', 'value' => 'dedicated_lactation_remating_weight_threshold'],
                        ['label' => 'الوزن مؤشر ضمن عدة عوامل ولا يكون شرطًا حاسمًا منفردًا', 'value' => 'weight_is_nonexclusive_indicator'],
                        ['label' => 'لا يدخل الوزن في هذا القرار', 'value' => 'weight_not_used_for_lactation_remating'],
                    ],
                ],
                [
                    'seed_key' => 'lactation_rules.lactation_remating_minimum_weight',
                    'title' => 'ما الحد الأدنى لوزن الأم عند إعادة التلقيح أثناء الرضاعة إذا كان له حد مستقل؟',
                    'help_text' => 'استخدم نفس وحدة الوزن القياسية المعتمدة في Weight History. هذه القيمة Threshold ولا تنشئ قياس وزن جديدًا.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 12,
                    'report_category' => 'remating_weight_threshold',
                    'target_entity' => 'lactating_mother_remating_readiness',
                    'options' => [],
                ],
                [
                    'seed_key' => 'lactation_rules.remating_not_ready_handling',
                    'title' => 'إذا وصل موعد إعادة التلقيح أثناء الرضاعة ولم تستوفِ الأم شروط الجاهزية، كيف يجب التعامل مع الحالة؟',
                    'help_text' => 'المصدر يفرق بين عدم الجاهزية وبين فشل محاولة تلقيح؛ إذا لم يتم التلقيح أصلًا فلا ينبغي إنشاء محاولة فاشلة. المطلوب تحديد مسار التأجيل والمراجعة.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 13,
                    'report_category' => 'remating_deferral_rule',
                    'target_entity' => 'lactating_mother_remating_review',
                    'options' => [
                        ['label' => 'تؤجل إعادة التلقيح مع تسجيل السبب وتحديد موعد مراجعة جديد', 'value' => 'defer_with_reason_and_new_review_date'],
                        ['label' => 'تؤجل حتى الفطام ثم يعاد تقييم الجاهزية وفق القواعد', 'value' => 'defer_until_weaning_then_reassess'],
                        ['label' => 'يحتاج المسؤول إلى قرار صريح لكل حالة مع الحفاظ على سبب القرار وتاريخه', 'value' => 'explicit_case_decision_with_history'],
                    ],
                ],
                [
                    'seed_key' => 'lactation_rules.remating_deferral_is_not_failed_attempt',
                    'title' => 'هل يجب منع احتساب تأجيل إعادة التلقيح قبل تنفيذ أي تلقيح كمحاولة تلقيح فاشلة؟',
                    'help_text' => 'الفشل في 6.5 و4.4 يتعلق بمحاولة تلقيح بدأت ثم لم تؤد للحمل وفق التعريف المعتمد. أما تأجيل أم غير جاهزة قبل تنفيذ التلقيح فهو قرار تشغيل مختلف ويجب ألا يشوه مؤشرات الخصوبة.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 14,
                    'report_category' => 'fertility_integrity_rule',
                    'target_entity' => 'lactating_mother_remating_review',
                    'options' => [],
                ],
                [
                    'seed_key' => 'lactation_rules.remating_review_schedule_model',
                    'title' => 'بعد تأجيل إعادة التلقيح، كيف يجب تحديد موعد المراجعة التالية؟',
                    'help_text' => 'المصدر يذكر إنشاء موعد مراجعة جديد بدل اعتبار العملية فاشلة. هذا السؤال يحسم مصدر الموعد، بينما توليد المهمة الفعلية من الموعد يعالج في 6.12.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 15,
                    'report_category' => 'remating_review_schedule_rule',
                    'target_entity' => 'lactating_mother_remating_review',
                    'options' => [
                        ['label' => 'بعد عدد أيام ثابت قابل للضبط من قرار التأجيل', 'value' => 'fixed_interval_after_deferral'],
                        ['label' => 'يحدد المسؤول تاريخ المراجعة عند تسجيل قرار التأجيل', 'value' => 'operator_selects_review_date'],
                        ['label' => 'يحدد الموعد حسب سبب التأجيل / حالة الأم وفق قواعد مختلفة', 'value' => 'review_timing_by_deferral_context'],
                    ],
                ],
                [
                    'seed_key' => 'lactation_rules.remating_review_interval_days',
                    'title' => 'بعد كم يوم تتم المراجعة التالية عند استخدام فاصل ثابت بعد التأجيل؟',
                    'help_text' => 'لا يغير هذا الرقم إعداد إعادة التلقيح العام للمزرعة؛ هو فاصل مراجعة للحالة الفردية بعد قرار التأجيل.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 16,
                    'report_category' => 'remating_review_interval',
                    'target_entity' => 'lactating_mother_remating_review',
                    'options' => [],
                ],
                [
                    'seed_key' => 'lactation_rules.repeated_deferral_review_threshold_enabled',
                    'title' => 'هل يجب وجود عدد محدد من مرات تأجيل إعادة التلقيح قبل اعتبار حالة الأم بحاجة إلى مراجعة إدارية أو إنتاجية أوسع؟',
                    'help_text' => 'المصدر يذكر عدد مرات التأجيل قبل إصدار تنبيه. هنا نحدد Threshold الحالة فقط؛ إنشاء التنبيه ومستواه وأولويته سيحسم في 6.12.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 17,
                    'report_category' => 'remating_review_threshold_rule',
                    'target_entity' => 'lactating_mother_remating_review',
                    'options' => [],
                ],
                [
                    'seed_key' => 'lactation_rules.deferrals_before_management_review',
                    'title' => 'بعد كم مرة تأجيل يجب اعتبار الحالة بحاجة إلى مراجعة أوسع؟',
                    'help_text' => 'هذه القيمة لا تنشئ Alert بنفسها؛ هي Threshold يمكن أن تستخدمه قواعد الأتمتة لاحقًا.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 18,
                    'report_category' => 'remating_review_threshold',
                    'target_entity' => 'lactating_mother_remating_review',
                    'options' => [],
                ],
                [
                    'seed_key' => 'lactation_rules.weaning_next_birth_overlap_detection_enabled',
                    'title' => 'هل يجب أن يكتشف النظام تقارب موعد فطام البطن الحالية مع موعد الولادة التالية المتوقع عندما تبدأ دورة جديدة أثناء الرضاعة؟',
                    'help_text' => 'المصدر يعتبر هذا التعارض الزمني حالة تحتاج الانتباه ولا يوصي بتغيير المواعيد تلقائيًا. موعد الفطام يأتي من قواعد 6.8، وموعد الولادة التالية من 6.6.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 19,
                    'report_category' => 'cycle_overlap_rule',
                    'target_entity' => 'reproductive_cycle_overlap',
                    'options' => [],
                ],
                [
                    'seed_key' => 'lactation_rules.minimum_weaning_birth_separation_days',
                    'title' => 'ما أقل عدد أيام بين موعد الفطام المتوقع والولادة التالية المتوقعة حتى لا تعتبر المواعيد متقاربة؟',
                    'help_text' => 'لا يفرض النظام رقمًا ثابتًا. هذه القيمة تستخدم فقط لاكتشاف حالة التقارب بين المسارين المتداخلين.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 20,
                    'report_category' => 'cycle_overlap_threshold',
                    'target_entity' => 'reproductive_cycle_overlap',
                    'options' => [],
                ],
                [
                    'seed_key' => 'lactation_rules.weaning_birth_overlap_enforcement_model',
                    'title' => 'عند اكتشاف تقارب بين الفطام المتوقع والولادة التالية، كيف يجب أن تؤثر الحالة على التشغيل؟',
                    'help_text' => 'المصدر ينص على أن النظام لا يغير المواعيد بنفسه. المطلوب تحديد مستوى التعامل مع الحالة باستخدام إطار Information / Warning / Block المعتمد في 6.2، بينما إنشاء Alert أو Task فعلي يعالج في 6.12.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 21,
                    'report_category' => 'cycle_overlap_rule',
                    'target_entity' => 'reproductive_cycle_overlap',
                    'options' => [
                        ['label' => 'Information فقط لعرض التقارب دون منع الإجراء', 'value' => 'information_only'],
                        ['label' => 'Warning يتطلب مراجعة / تأكيد قبل الاستمرار في القرار المرتبط', 'value' => 'warning_requires_review'],
                        ['label' => 'Block لبعض الإجراءات حتى يتم تسجيل قرار معالجة أو Override مسموح وفق 6.2', 'value' => 'block_until_review_or_allowed_override'],
                    ],
                ],
                [
                    'seed_key' => 'lactation_rules.postweaning_rest_with_active_next_cycle',
                    'title' => 'إذا تم تلقيح الأم أثناء الرضاعة وأصبح لديها دورة تناسلية جديدة نشطة عند تنفيذ الفطام، كيف تطبق قاعدة الراحة بعد الفطام الموجودة في 6.5؟',
                    'help_text' => 'المصدر يؤكد أن تطبيق راحة بعد الفطام بصورة عمياء لا معنى له على دورة بدأت بالفعل أثناء الرضاعة. المطلوب منع تعارض القاعدة مع الحمل أو المحاولة النشطة التالية.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 22,
                    'report_category' => 'rule_interaction',
                    'target_entity' => 'female_mating_readiness',
                    'options' => [
                        ['label' => 'قاعدة الراحة بعد الفطام تطبق فقط قبل بدء تلقيح جديد، ولا توقف أو تغير دورة بدأت بالفعل', 'value' => 'rest_applies_before_new_cycle_only'],
                        ['label' => 'إذا توجد دورة نشطة عند الفطام، تستبدل الراحة بمتابعة حالة الأم دون التأثير على الدورة القائمة', 'value' => 'active_cycle_replaces_rest_with_maternal_followup'],
                        ['label' => 'يحدد السلوك حسب Operational Settings Profile / برنامج التشغيل المستخدم', 'value' => 'behavior_by_operational_program'],
                    ],
                ],
                [
                    'seed_key' => 'lactation_rules.foster_mother_rule_model',
                    'title' => 'عند دعم نقل المواليد إلى أم حاضنة في 4.7، ما مستوى قواعد الأهلية والسعة المطلوب قبل تنفيذ النقل؟',
                    'help_text' => '4.7 يحفظ حركة النقل والأصل البيولوجي. هذا السؤال يحدد القواعد التي يجب فحصها قبل اختيار الأم الحاضنة، بما في ذلك الحد الأقصى المناسب لعدد المواليد الذي تركه المصدر كنقطة مراجعة.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 23,
                    'report_category' => 'foster_rule',
                    'target_entity' => 'foster_mother_eligibility',
                    'options' => [
                        ['label' => 'لا ينطبق لأن نقل المواليد إلى أم حاضنة غير معتمد', 'value' => 'not_applicable'],
                        ['label' => 'يكفي فحص السعة / الحد الأقصى للمواليد فقط', 'value' => 'capacity_rule_only'],
                        ['label' => 'تفحص أهلية الأم الحاضنة وسعتها معًا قبل النقل', 'value' => 'eligibility_and_capacity_rules'],
                        ['label' => 'يعرض النظام السياق فقط ويترك القرار للمستخدم دون قواعد منع آلية', 'value' => 'context_only_manual_decision'],
                    ],
                ],
                [
                    'seed_key' => 'lactation_rules.foster_mother_eligibility_factors',
                    'title' => 'ما العوامل التي يجب أن تدخل في فحص أهلية الأم الحاضنة عند استخدام قواعد أهلية منظمة؟',
                    'help_text' => 'المطلوب استخدام المعلومات الموجودة بالفعل عن الأم والبطن، دون تغيير الأم البيولوجية أو إنشاء علاقة نسب جديدة.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 24,
                    'report_category' => 'foster_rule',
                    'target_entity' => 'foster_mother_eligibility',
                    'options' => [
                        ['label' => 'وجود الأم فعليًا داخل المزرعة وقدرتها الحالية على رعاية مواليد', 'value' => 'present_and_available_for_fostering'],
                        ['label' => 'الحالة الصحية لا تمنع الرعاية', 'value' => 'health_allows_fostering'],
                        ['label' => 'عدم وجود عزل يمنع استقبال المواليد', 'value' => 'not_isolated'],
                        ['label' => 'وجود سعة متبقية وفق الحد الأقصى المعتمد للمواليد تحت الرعاية', 'value' => 'foster_capacity_available'],
                        ['label' => 'حالة البطن الحالية للأم لا تمنع استقبال مواليد إضافية', 'value' => 'current_litter_context_allows'],
                    ],
                ],
                [
                    'seed_key' => 'lactation_rules.foster_max_offspring_enabled',
                    'title' => 'هل يجب تحديد حد أقصى لعدد المواليد الموجودين تحت رعاية الأم الحاضنة بعد تنفيذ النقل؟',
                    'help_text' => 'المصدر يضع الحد الأقصى المناسب لعدد المواليد لدى الأم الحاضنة كنقطة تحتاج مراجعة. هذا الحد يخص الرعاية ولا يغير العدد البيولوجي الأصلي لكل بطن.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 25,
                    'report_category' => 'foster_capacity_rule',
                    'target_entity' => 'foster_mother_eligibility',
                    'options' => [],
                ],
                [
                    'seed_key' => 'lactation_rules.foster_max_total_offspring',
                    'title' => 'ما الحد الأقصى لإجمالي عدد المواليد تحت رعاية الأم الحاضنة بعد النقل؟',
                    'help_text' => 'القيمة تشمل مواليدها الحاليين والمواليد المستقبلة لأغراض فحص السعة، مع بقاء أصل كل مولود محفوظًا بصورة مستقلة.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 26,
                    'report_category' => 'foster_capacity_threshold',
                    'target_entity' => 'foster_mother_eligibility',
                    'options' => [],
                ],
                [
                    'seed_key' => 'lactation_rules.periodic_maternal_rest_policy',
                    'title' => 'هل تحتاج الأنثى إلى راحة إنتاجية دورية بعد عدد معين من البطون، بخلاف قاعدة الراحة العادية بعد الفطام في 6.5؟',
                    'help_text' => 'المصدر يضع الحاجة إلى فترات راحة دورية بعد عدد من البطون كسؤال مفتوح. هذه القاعدة مختلفة عن راحة ما بعد الفطام التي قد تطبق على كل دورة.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 27,
                    'report_category' => 'maternal_rest_rule',
                    'target_entity' => 'maternal_recovery_rule',
                    'options' => [
                        ['label' => 'لا توجد راحة دورية مرتبطة بعدد البطون', 'value' => 'no_periodic_rest_by_litter_count'],
                        ['label' => 'بعد عدد محدد من البطون تصبح الأنثى مستحقة لراحة إنتاجية', 'value' => 'rest_after_configured_litter_count'],
                        ['label' => 'بعد عدد محدد من البطون تصبح الأنثى مستحقة لمراجعة حالة، وتقرر الراحة حسب النتيجة', 'value' => 'condition_review_after_configured_litter_count'],
                    ],
                ],
                [
                    'seed_key' => 'lactation_rules.litters_before_periodic_rest_review',
                    'title' => 'بعد كم بطن تصبح الأنثى مستحقة للراحة أو مراجعة الحالة الدورية؟',
                    'help_text' => 'لا يفرض النظام عددًا ثابتًا؛ هذه قيمة تشغيلية تستخدم فقط إذا اعتمدت سياسة مرتبطة بعدد البطون.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => false,
                    'sort_order' => 28,
                    'report_category' => 'maternal_rest_threshold',
                    'target_entity' => 'maternal_recovery_rule',
                    'options' => [],
                ],
                [
                    'seed_key' => 'lactation_rules.periodic_rest_duration_model',
                    'title' => 'عند اعتماد راحة إنتاجية دورية للأم، كيف يجب تحديد نهايتها؟',
                    'help_text' => 'الهدف ألا تعود الأنثى للتلقيح لمجرد مرور وقت إذا كانت حالتها غير مناسبة، وألا تتحول الراحة إلى Status يدوي بلا قواعد.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 29,
                    'report_category' => 'maternal_rest_rule',
                    'target_entity' => 'maternal_recovery_rule',
                    'options' => [
                        ['label' => 'راحة لعدد أيام محدد ثم يعاد تطبيق شروط الجاهزية', 'value' => 'configured_rest_days_then_reassess'],
                        ['label' => 'لا توجد مدة ثابتة؛ تستمر الراحة حتى تجتاز الأنثى مراجعة الجاهزية', 'value' => 'rest_until_readiness_reassessment_passes'],
                    ],
                ],
                [
                    'seed_key' => 'lactation_rules.periodic_rest_days',
                    'title' => 'كم يومًا تستمر فترة الراحة الدورية عندما تستخدم مدة زمنية محددة؟',
                    'help_text' => 'هذه القيمة تختلف عن فاصل تأجيل حالة فردية وعن راحة ما بعد الفطام العامة، وتستخدم فقط لسياسة الراحة الدورية بعد عدد من البطون.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 30,
                    'report_category' => 'maternal_rest_duration',
                    'target_entity' => 'maternal_recovery_rule',
                    'options' => [],
                ],
            ];

            app(QuestionSeederSyncService::class)->sync(
                mainSectionName: 'الإعدادات وقواعد التشغيل',
                sectionName: 'قواعد الولادة والرضاعة وإعادة التلقيح',
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
            ['lactation_rules.duration_source_model', 'lactation_rules.target_duration_days', QuestionDependencyOperator::EQUALS, 'independent_lactation_target_duration'],
            ['lactation_rules.litter_followup_schedule_model', 'lactation_rules.litter_followup_interval_days', QuestionDependencyOperator::EQUALS, 'periodic_interval_followup'],
            ['lactation_rules.offspring_weight_schedule_model', 'lactation_rules.offspring_weight_interval_days', QuestionDependencyOperator::EQUALS, 'periodic_interval_weight_schedule'],
            ['lactation_rules.offspring_weight_schedule_model', 'lactation_rules.offspring_weight_checkpoint_ages', QuestionDependencyOperator::EQUALS, 'configured_age_checkpoint_weight_schedule'],
            ['lactation_rules.remating_readiness_model', 'lactation_rules.lactation_specific_remating_factors', QuestionDependencyOperator::EQUALS, 'general_plus_lactation_specific_readiness'],
            ['lactation_rules.remating_weight_policy', 'lactation_rules.lactation_remating_minimum_weight', QuestionDependencyOperator::EQUALS, 'dedicated_lactation_remating_weight_threshold'],
            ['lactation_rules.remating_review_schedule_model', 'lactation_rules.remating_review_interval_days', QuestionDependencyOperator::EQUALS, 'fixed_interval_after_deferral'],
            ['lactation_rules.repeated_deferral_review_threshold_enabled', 'lactation_rules.deferrals_before_management_review', QuestionDependencyOperator::EQUALS, '1'],
            ['lactation_rules.weaning_next_birth_overlap_detection_enabled', 'lactation_rules.minimum_weaning_birth_separation_days', QuestionDependencyOperator::EQUALS, '1'],
            ['lactation_rules.weaning_next_birth_overlap_detection_enabled', 'lactation_rules.weaning_birth_overlap_enforcement_model', QuestionDependencyOperator::EQUALS, '1'],
            ['lactation_rules.foster_max_offspring_enabled', 'lactation_rules.foster_max_total_offspring', QuestionDependencyOperator::EQUALS, '1'],
            ['lactation_rules.periodic_rest_duration_model', 'lactation_rules.periodic_rest_days', QuestionDependencyOperator::EQUALS, 'configured_rest_days_then_reassess'],
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
            ->where('name', 'قواعد الولادة والرضاعة وإعادة التلقيح')
            ->first();
    }
}
