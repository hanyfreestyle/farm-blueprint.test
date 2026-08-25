<?php

namespace Database\Seeders\Questions\Settings;

use App\Enums\Questionnaire\QuestionDependencyOperator;
use App\Enums\Questionnaire\QuestionType;
use App\Models\QuestionnaireQuestion;
use App\Models\QuestionnaireSection;
use App\Services\Questionnaire\QuestionSeederSyncService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MatingFertilityReadinessRulesQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $questions = [
                [
                    'seed_key' => 'mating_rules.female_readiness_factors',
                    'title' => 'ما الشروط التي يجب أن تدخل في حساب جاهزية الأنثى للتلقيح الآن؟',
                    'help_text' => 'الجاهزية هنا نتيجة مشتقة من البيانات والسجلات الحالية وليست Status يدويًا. اختر الشروط التي يجب فحصها قبل إظهار الأنثى كجاهزة أو السماح بالتلقيح الفعلي في 4.4.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 1,
                    'report_category' => 'readiness_rule',
                    'target_entity' => 'female_mating_readiness',
                    'options' => [
                        ['label' => 'الحيوان موجود حاليًا داخل المزرعة', 'value' => 'present_in_farm'],
                        ['label' => 'معتمد داخل القطيع الإنتاجي', 'value' => 'approved_in_production_herd'],
                        ['label' => 'بلوغ حد أدنى للعمر', 'value' => 'minimum_age'],
                        ['label' => 'بلوغ حد أدنى للوزن', 'value' => 'minimum_weight'],
                        ['label' => 'الحالة الصحية تسمح بالتلقيح', 'value' => 'health_allows_mating'],
                        ['label' => 'ليست في العزل', 'value' => 'not_isolated'],
                        ['label' => 'ليست مستبعدة من الإنتاج', 'value' => 'not_excluded_from_production'],
                        ['label' => 'سياق دورة الإنتاج الحالية لا يمنع التلقيح', 'value' => 'reproductive_cycle_context_allows'],
                        ['label' => 'استيفاء قواعد التوقيت بعد الولادة أو الفطام عند انطباقها', 'value' => 'postpartum_or_postweaning_timing_allows'],
                    ],
                ],
                [
                    'seed_key' => 'mating_rules.female_minimum_age_days',
                    'title' => 'ما الحد الأدنى لعمر الأنثى عند أول تلقيح، بالأيام؟',
                    'help_text' => 'لا يفرض النظام رقمًا ثابتًا. هذه القيمة تستخدم فقط إذا تم اعتماد العمر ضمن شروط جاهزية الأنثى.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 2,
                    'report_category' => 'readiness_threshold',
                    'target_entity' => 'female_mating_readiness',
                    'options' => [],
                ],
                [
                    'seed_key' => 'mating_rules.female_minimum_weight',
                    'title' => 'ما الحد الأدنى لوزن الأنثى عند التلقيح؟',
                    'help_text' => 'استخدم نفس وحدة الوزن القياسية المعتمدة في سجل الأوزان Canonical في 4.3. لا تنشئ هذه القيمة سجل وزن جديدًا؛ هي Threshold للمقارنة فقط.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 3,
                    'report_category' => 'readiness_threshold',
                    'target_entity' => 'female_mating_readiness',
                    'options' => [],
                ],
                [
                    'seed_key' => 'mating_rules.lactation_mating_policy',
                    'title' => 'كيف يجب أن تتعامل قواعد الجاهزية مع تلقيح الأنثى أثناء الرضاعة؟',
                    'help_text' => 'التصور يسمح بدراسة تداخل دورة تناسلية جديدة مع استمرار رضاعة البطن. هذا السؤال يحسم القاعدة فقط؛ تنفيذ التلقيح نفسه يبقى في 4.4 وتداخل الدورات الفعلي يبقى محفوظًا تاريخيًا.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 4,
                    'report_category' => 'reproductive_timing_rule',
                    'target_entity' => 'female_mating_readiness',
                    'options' => [
                        ['label' => 'لا يسمح بالتلقيح طوال فترة الرضاعة', 'value' => 'not_allowed_while_lactating'],
                        ['label' => 'يسمح بعد مرور عدد أيام محدد من الولادة مع استيفاء باقي شروط الجاهزية', 'value' => 'allowed_after_configured_postpartum_delay'],
                        ['label' => 'يمكن التلقيح أثناء الرضاعة متى استوفت الأنثى باقي شروط الجاهزية دون فترة ثابتة بعد الولادة', 'value' => 'allowed_when_other_readiness_rules_pass'],
                    ],
                ],
                [
                    'seed_key' => 'mating_rules.minimum_days_after_birth_for_remating',
                    'title' => 'ما الحد الأدنى لعدد الأيام بعد الولادة قبل السماح بإعادة تلقيح الأم أثناء الرضاعة؟',
                    'help_text' => 'هذه القيمة تطبق فقط عند اعتماد سياسة تسمح بالتلقيح أثناء الرضاعة بعد فترة محددة من الولادة.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 5,
                    'report_category' => 'reproductive_timing_threshold',
                    'target_entity' => 'female_mating_readiness',
                    'options' => [],
                ],
                [
                    'seed_key' => 'mating_rules.postweaning_rest_policy',
                    'title' => 'هل تحتاج الأنثى إلى فترة راحة بعد الفطام قبل أن تصبح جاهزة لتلقيح جديد؟',
                    'help_text' => 'المرجع يذكر مدة الراحة بعد الفطام كقاعدة محتملة وليس كرقم ثابت. اختر هل توجد مدة ثابتة، أم تعتمد الجاهزية على حالة الأنثى دون فترة إلزامية.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 6,
                    'report_category' => 'reproductive_timing_rule',
                    'target_entity' => 'female_mating_readiness',
                    'options' => [
                        ['label' => 'لا توجد فترة راحة إلزامية بعد الفطام', 'value' => 'no_required_postweaning_rest'],
                        ['label' => 'توجد فترة راحة ثابتة قابلة للضبط قبل التلقيح التالي', 'value' => 'configured_postweaning_rest_period'],
                        ['label' => 'لا توجد مدة ثابتة؛ الجاهزية بعد الفطام تعتمد على تقييم حالة الأنثى وباقي الشروط', 'value' => 'readiness_based_on_current_condition'],
                    ],
                ],
                [
                    'seed_key' => 'mating_rules.postweaning_rest_days',
                    'title' => 'كم يومًا يجب أن تستمر فترة الراحة بعد الفطام قبل التلقيح التالي؟',
                    'help_text' => 'تظهر هذه القيمة فقط إذا تم اعتماد فترة راحة ثابتة بعد الفطام.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 7,
                    'report_category' => 'reproductive_timing_threshold',
                    'target_entity' => 'female_mating_readiness',
                    'options' => [],
                ],
                [
                    'seed_key' => 'mating_rules.male_readiness_factors',
                    'title' => 'ما الشروط التي يجب أن تدخل في حساب جاهزية الذكر للاستخدام في التلقيح الآن؟',
                    'help_text' => 'وجود الذكر في مجموعة إنتاجية لا يعني أنه متاح دائمًا. المرجع يذكر الصحة والعزل والراحة وعدد الاستخدامات والخروج من المزرعة ضمن أسباب عدم الجاهزية.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 8,
                    'report_category' => 'readiness_rule',
                    'target_entity' => 'male_mating_readiness',
                    'options' => [
                        ['label' => 'الحيوان موجود حاليًا داخل المزرعة', 'value' => 'present_in_farm'],
                        ['label' => 'معتمد داخل القطيع الإنتاجي', 'value' => 'approved_in_production_herd'],
                        ['label' => 'بلوغ حد أدنى للعمر', 'value' => 'minimum_age'],
                        ['label' => 'بلوغ حد أدنى للوزن', 'value' => 'minimum_weight'],
                        ['label' => 'الحالة الصحية تسمح بالاستخدام', 'value' => 'health_allows_mating'],
                        ['label' => 'ليس في العزل', 'value' => 'not_isolated'],
                        ['label' => 'لم يخرج من المزرعة', 'value' => 'present_not_exited'],
                        ['label' => 'استوفى فترة الراحة المطلوبة منذ آخر استخدام عند وجودها', 'value' => 'required_rest_satisfied'],
                        ['label' => 'لم يتجاوز حد الاستخدام خلال الفترة المحددة عند وجوده', 'value' => 'usage_limit_not_exceeded'],
                    ],
                ],
                [
                    'seed_key' => 'mating_rules.male_minimum_age_days',
                    'title' => 'ما الحد الأدنى لعمر بدء استخدام الذكر في التلقيح، بالأيام؟',
                    'help_text' => 'هذه القيمة تستخدم فقط عند اعتماد العمر ضمن شروط جاهزية الذكر.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 9,
                    'report_category' => 'readiness_threshold',
                    'target_entity' => 'male_mating_readiness',
                    'options' => [],
                ],
                [
                    'seed_key' => 'mating_rules.male_minimum_weight',
                    'title' => 'ما الحد الأدنى لوزن الذكر عند بدء استخدامه في التلقيح؟',
                    'help_text' => 'استخدم نفس وحدة الوزن القياسية المعتمدة في سجل الأوزان Canonical في 4.3. القيمة هنا Threshold للجاهزية وليست سجل وزن مستقلًا.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 10,
                    'report_category' => 'readiness_threshold',
                    'target_entity' => 'male_mating_readiness',
                    'options' => [],
                ],
                [
                    'seed_key' => 'mating_rules.male_usage_limit_enabled',
                    'title' => 'هل يجب تحديد حد أقصى لعدد عمليات التلقيح التي يستخدم فيها الذكر خلال فترة زمنية معينة؟',
                    'help_text' => 'المرجع يطرح الحد الأقصى للاستخدام كقاعدة تشغيل قابلة للضبط. هذا الحد يؤثر على جاهزية الذكر ولا يغير سجل العمليات الفعلية في 4.4.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 11,
                    'report_category' => 'male_usage_rule',
                    'target_entity' => 'male_mating_readiness',
                    'options' => [],
                ],
                [
                    'seed_key' => 'mating_rules.male_max_matings_per_window',
                    'title' => 'ما الحد الأقصى لعدد عمليات التلقيح المسموح بها للذكر داخل فترة القياس؟',
                    'help_text' => 'حدد عدد العمليات المسموح بها قبل اعتبار حد الاستخدام قد تم بلوغه.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 12,
                    'report_category' => 'male_usage_threshold',
                    'target_entity' => 'male_mating_readiness',
                    'options' => [],
                ],
                [
                    'seed_key' => 'mating_rules.male_usage_window_days',
                    'title' => 'ما طول الفترة الزمنية بالأيام التي يحسب داخلها حد استخدام الذكر؟',
                    'help_text' => 'مثال: حد استخدام خلال يوم أو عدة أيام. لا يفترض النظام فترة ثابتة مسبقًا.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 13,
                    'report_category' => 'male_usage_threshold',
                    'target_entity' => 'male_mating_readiness',
                    'options' => [],
                ],
                [
                    'seed_key' => 'mating_rules.male_rest_between_uses_enabled',
                    'title' => 'هل يجب فرض فترة راحة دنيا بين استخدام الذكر في عمليتي تلقيح متتاليتين؟',
                    'help_text' => 'المرجع يذكر فترة الراحة بين الاستخدامات كقاعدة اختيارية. إذا لم تعتمد فلا يمنع النظام الذكر بسبب الزمن منذ آخر استخدام وحده.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 14,
                    'report_category' => 'male_usage_rule',
                    'target_entity' => 'male_mating_readiness',
                    'options' => [],
                ],
                [
                    'seed_key' => 'mating_rules.male_minimum_rest_hours',
                    'title' => 'كم ساعة يجب أن تكون فترة الراحة الدنيا بين استخدامات الذكر؟',
                    'help_text' => 'تطبق هذه القيمة فقط إذا تم اعتماد فترة راحة دنيا بين الاستخدامات.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 15,
                    'report_category' => 'male_usage_threshold',
                    'target_entity' => 'male_mating_readiness',
                    'options' => [],
                ],
                [
                    'seed_key' => 'mating_rules.repeat_mating_within_attempt',
                    'title' => 'هل يجب تكرار التلقيح أكثر من مرة داخل نفس محاولة التلقيح؟',
                    'help_text' => 'هذا يخص تكرار التلقيح داخل نفس Attempt، وليس إعادة التلقيح بعد ظهور نتيجة حمل سلبية؛ الأخيرة محاولة جديدة وفق Workflow 4.4 و4.5.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 16,
                    'report_category' => 'attempt_rule',
                    'target_entity' => 'mating_attempt',
                    'options' => [],
                ],
                [
                    'seed_key' => 'mating_rules.mating_events_per_attempt',
                    'title' => 'كم عملية تلقيح يجب تنفيذها داخل المحاولة الواحدة عند اعتماد التكرار؟',
                    'help_text' => '4.4 يسمح بتمثيل أحداث التلقيح داخل المحاولة. هذه القيمة تحدد العدد المطلوب تشغيليًا ولا تغير بنية السجل التاريخي.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 17,
                    'report_category' => 'attempt_threshold',
                    'target_entity' => 'mating_attempt',
                    'options' => [],
                ],
                [
                    'seed_key' => 'mating_rules.mating_interval_hours',
                    'title' => 'ما الفاصل الزمني المطلوب بالساعات بين عمليات التلقيح داخل نفس المحاولة؟',
                    'help_text' => 'التصور يذكر نحو 6 ساعات كمثال فقط. لا تثبت هذه القيمة مسبقًا؛ الإجابة هنا هي التي تحدد الفاصل المطلوب.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 18,
                    'report_category' => 'attempt_threshold',
                    'target_entity' => 'mating_attempt',
                    'options' => [],
                ],
                [
                    'seed_key' => 'mating_rules.nonassigned_male_policy',
                    'title' => 'إذا كانت الأنثى مرتبطة بذكر مخصص ضمن المجموعة، ما قاعدة استخدام ذكر مختلف في التلقيح الفعلي؟',
                    'help_text' => '3.5 يحفظ علاقة المجموعة، و4.4 يسجل الذكر المستخدم فعليًا. المطلوب هنا فقط قاعدة السماح باستخدام ذكر غير المخصص قبل تنفيذ الحدث.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 19,
                    'report_category' => 'male_selection_rule',
                    'target_entity' => 'mating_execution_rule',
                    'options' => [
                        ['label' => 'لا يسمح بذكر مختلف طالما يوجد ذكر مخصص صالح', 'value' => 'assigned_male_required_when_eligible'],
                        ['label' => 'الذكر المخصص هو الافتراضي، ويسمح بذكر مختلف وفق Warning / Override Policy مع تسجيل السبب', 'value' => 'assigned_preferred_other_requires_governed_exception'],
                        ['label' => 'يمكن اختيار أي ذكر جاهز يستوفي القواعد دون اشتراط الذكر المخصص', 'value' => 'any_eligible_male_allowed'],
                    ],
                ],
                [
                    'seed_key' => 'mating_rules.multiple_males_same_attempt_policy',
                    'title' => 'ما السياسة الوقائية لاستخدام أكثر من ذكر داخل نفس محاولة التلقيح؟',
                    'help_text' => '4.4 يحمي النسب إذا حدث ذلك فعليًا بعدم افتراض أب مؤكد. هنا نحسم هل يسمح النظام بالوصول لهذه الحالة أصلًا، لأن استخدام أكثر من ذكر قد يجعل الأبوة غير محسومة.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 20,
                    'report_category' => 'paternity_protection_rule',
                    'target_entity' => 'mating_attempt',
                    'options' => [
                        ['label' => 'Hard Constraint: يجب استخدام نفس الذكر داخل المحاولة ولا يسمح بتغييره', 'value' => 'same_male_required_no_override'],
                        ['label' => 'يسمح بتغيير الذكر فقط كاستثناء محكوم وفق 6.2 مع اعتبار الأبوة غير محسومة', 'value' => 'different_male_governed_exception'],
                        ['label' => 'يسمح بأكثر من ذكر مع تطبيق سياسة الأبوة غير المحسومة في 4.4', 'value' => 'multiple_males_allowed_with_uncertain_paternity'],
                    ],
                ],
                [
                    'seed_key' => 'mating_rules.failed_attempt_definition',
                    'title' => 'متى يجب اعتبار محاولة التلقيح غير ناجحة لأغراض العد وإعادة التلقيح وتقييم الخصوبة؟',
                    'help_text' => 'المرجع يربط الحمل السلبي بإغلاق المحاولة كغير ناجحة، مع الحاجة إلى تعريف واضح يمنع احتساب الرفض أو الإلغاء أو النتيجة غير المؤكدة بالطريقة نفسها دون قرار.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 21,
                    'report_category' => 'fertility_rule',
                    'target_entity' => 'mating_attempt',
                    'options' => [
                        ['label' => 'تعتبر المحاولة فاشلة فقط عند نتيجة فحص حمل سلبية مرتبطة بها', 'value' => 'negative_pregnancy_check_only'],
                        ['label' => 'تعتبر فاشلة عند نتيجة حمل سلبية أو عند إغلاقها صراحة كغير ناجحة بسبب عدم الوصول للحمل', 'value' => 'negative_check_or_explicit_unsuccessful_closure'],
                        ['label' => 'تحدد أسباب / نتائج الفشل المعتمدة كقواعد قابلة للتهيئة حسب نوع الإغلاق', 'value' => 'configured_failure_outcomes'],
                    ],
                ],
                [
                    'seed_key' => 'mating_rules.failed_attempt_review_threshold_enabled',
                    'title' => 'هل يجب وجود عدد محدد من محاولات التلقيح غير الناجحة قبل اعتبار الحالة بحاجة إلى مراجعة خصوبة؟',
                    'help_text' => 'هذا يحدد Threshold الحالة فقط. إنشاء Alert فعلي ومستواه وأولويته ومواعيد التذكير تعالج في 6.12، وعرض المؤشرات والتحليل في قسم التقارير.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 22,
                    'report_category' => 'fertility_threshold_rule',
                    'target_entity' => 'female_fertility_review',
                    'options' => [],
                ],
                [
                    'seed_key' => 'mating_rules.failed_attempts_before_fertility_review',
                    'title' => 'بعد كم محاولة تلقيح غير ناجحة يجب اعتبار الأنثى بحاجة إلى مراجعة خصوبة؟',
                    'help_text' => 'لا يفرض النظام عددًا ثابتًا. هذا الرقم يستخدم كThreshold للحالة عند اعتماد القاعدة.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 23,
                    'report_category' => 'fertility_threshold',
                    'target_entity' => 'female_fertility_review',
                    'options' => [],
                ],
                [
                    'seed_key' => 'mating_rules.kinship_enforcement_model',
                    'title' => 'كيف يجب تحويل نتيجة فحص القرابة بين الذكر والأنثى إلى قاعدة سماح أو تحذير أو منع قبل التلقيح؟',
                    'help_text' => 'المصدر يذكر علاقات مثل أب × ابنة وأم × ابن والأشقاء وأنصاف الأشقاء، لكنه يترك درجات المنع والتحذير للمراجعة. فحص القرابة نفسه يعاد عند التلقيح الفعلي وفق 4.4.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 24,
                    'report_category' => 'kinship_rule',
                    'target_entity' => 'mating_kinship_rule',
                    'options' => [
                        ['label' => 'مصفوفة قابلة للضبط تحدد لكل درجة / علاقة: مسموح أو Warning أو Block', 'value' => 'configurable_relationship_enforcement_matrix'],
                        ['label' => 'كل قرابة يتم اكتشافها ضمن العلاقات المحددة تكون Warning فقط', 'value' => 'all_detected_kinship_warning'],
                        ['label' => 'كل قرابة يتم اكتشافها ضمن العلاقات المحددة تكون Block', 'value' => 'all_detected_kinship_block'],
                        ['label' => 'يعرض النظام القرابة كمعلومة فقط دون تأثير على السماح بالتلقيح', 'value' => 'kinship_information_only'],
                    ],
                ],
                [
                    'seed_key' => 'mating_rules.breed_variation_model',
                    'title' => 'هل يجب أن تستطيع قواعد الجاهزية والتلقيح أن تختلف حسب السلالة عند الحاجة؟',
                    'help_text' => 'المراجعة المعمارية تسمح باختلاف القواعد حسب السلالة إذا ثبتت الحاجة. بيانات السلالة ومؤشراتها تبقى في Master Data؛ هنا نحدد فقط هل يمكن أن تختلف قيم التشغيل عنها.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 25,
                    'report_category' => 'rule_scope',
                    'target_entity' => 'mating_operational_rule',
                    'options' => [
                        ['label' => 'نفس قواعد التلقيح والجاهزية لكل السلالات داخل نطاق الإعدادات', 'value' => 'uniform_rules_across_breeds'],
                        ['label' => 'يمكن أن تختلف القيم الرقمية فقط حسب السلالة', 'value' => 'breed_specific_numeric_thresholds'],
                        ['label' => 'يمكن أن تختلف القيم وبعض القواعد المحددة حسب السلالة عندما تكون هناك حاجة تشغيلية موثقة', 'value' => 'breed_specific_selected_rules_and_thresholds'],
                    ],
                ],
            ];

            app(QuestionSeederSyncService::class)->sync(
                mainSectionName: 'الإعدادات وقواعد التشغيل',
                sectionName: 'قواعد التلقيح والخصوبة والجاهزية التناسلية',
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
            ['mating_rules.female_readiness_factors', 'mating_rules.female_minimum_age_days', QuestionDependencyOperator::CONTAINS, 'minimum_age'],
            ['mating_rules.female_readiness_factors', 'mating_rules.female_minimum_weight', QuestionDependencyOperator::CONTAINS, 'minimum_weight'],
            ['mating_rules.lactation_mating_policy', 'mating_rules.minimum_days_after_birth_for_remating', QuestionDependencyOperator::EQUALS, 'allowed_after_configured_postpartum_delay'],
            ['mating_rules.postweaning_rest_policy', 'mating_rules.postweaning_rest_days', QuestionDependencyOperator::EQUALS, 'configured_postweaning_rest_period'],
            ['mating_rules.male_readiness_factors', 'mating_rules.male_minimum_age_days', QuestionDependencyOperator::CONTAINS, 'minimum_age'],
            ['mating_rules.male_readiness_factors', 'mating_rules.male_minimum_weight', QuestionDependencyOperator::CONTAINS, 'minimum_weight'],
            ['mating_rules.male_usage_limit_enabled', 'mating_rules.male_max_matings_per_window', QuestionDependencyOperator::EQUALS, '1'],
            ['mating_rules.male_usage_limit_enabled', 'mating_rules.male_usage_window_days', QuestionDependencyOperator::EQUALS, '1'],
            ['mating_rules.male_rest_between_uses_enabled', 'mating_rules.male_minimum_rest_hours', QuestionDependencyOperator::EQUALS, '1'],
            ['mating_rules.repeat_mating_within_attempt', 'mating_rules.mating_events_per_attempt', QuestionDependencyOperator::EQUALS, '1'],
            ['mating_rules.repeat_mating_within_attempt', 'mating_rules.mating_interval_hours', QuestionDependencyOperator::EQUALS, '1'],
            ['mating_rules.failed_attempt_review_threshold_enabled', 'mating_rules.failed_attempts_before_fertility_review', QuestionDependencyOperator::EQUALS, '1'],
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
            ->where('name', 'قواعد التلقيح والخصوبة والجاهزية التناسلية')
            ->first();
    }
}
