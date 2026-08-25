<?php

namespace Database\Seeders\Questions\Settings;

use App\Enums\Questionnaire\QuestionDependencyOperator;
use App\Enums\Questionnaire\QuestionType;
use App\Models\QuestionnaireQuestion;
use App\Models\QuestionnaireSection;
use App\Services\Questionnaire\QuestionSeederSyncService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WeaningIndividualTrackingRulesQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $questions = [
                [
                    'seed_key' => 'weaning_rules.temporal_parameters',
                    'title' => 'ما الحدود الزمنية التي يجب أن يستخدمها النظام عند تقييم استحقاق البطن للفطام؟',
                    'help_text' => 'المصدر يفرق بين العمر المستهدف، والحد الأدنى، والحد الأقصى قبل اعتبار الفطام متأخرًا. لا تفرض أي أرقام ثابتة؛ اختر فقط الحدود التي تحتاجها المزرعة.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 1,
                    'report_category' => 'weaning_timing_rule',
                    'target_entity' => 'weaning_readiness',
                    'options' => [
                        ['label' => 'عمر مستهدف للفطام', 'value' => 'target_age'],
                        ['label' => 'حد أدنى للعمر لا يسمح بالفطام قبله بصورة عادية', 'value' => 'minimum_age'],
                        ['label' => 'حد أقصى للعمر تعتبر بعده الحالة متأخرة عن الفطام', 'value' => 'maximum_age_before_overdue'],
                    ],
                ],
                [
                    'seed_key' => 'weaning_rules.target_age_days',
                    'title' => 'ما العمر المستهدف للفطام، بالأيام؟',
                    'help_text' => 'هذه قيمة تشغيلية مستهدفة وليست حدث فطام تلقائيًا. الفطام الفعلي لا يحدث إلا عند تنفيذ العملية في 4.8.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 2,
                    'report_category' => 'weaning_timing_threshold',
                    'target_entity' => 'weaning_readiness',
                    'options' => [],
                ],
                [
                    'seed_key' => 'weaning_rules.minimum_age_days',
                    'title' => 'ما الحد الأدنى لعمر الفطام، بالأيام؟',
                    'help_text' => 'هذه القيمة تستخدم كحد تشغيل عند اعتماد حد أدنى للعمر، ولا تعني أن الوصول إليها وحده يكفي للفطام إذا كانت هناك شروط أخرى مطلوبة.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 3,
                    'report_category' => 'weaning_timing_threshold',
                    'target_entity' => 'weaning_readiness',
                    'options' => [],
                ],
                [
                    'seed_key' => 'weaning_rules.maximum_age_days',
                    'title' => 'بعد أي عمر، بالأيام، تعتبر حالة الفطام متأخرة؟',
                    'help_text' => 'هذا Threshold لتصنيف التأخير. إنشاء Alert أو Task وأولويتها عند تجاوزه يحسم في 6.12، ولا ينشئ هذا الرقم حدث فطام أو إغلاقًا تلقائيًا.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 4,
                    'report_category' => 'weaning_overdue_threshold',
                    'target_entity' => 'weaning_readiness',
                    'options' => [],
                ],
                [
                    'seed_key' => 'weaning_rules.weight_parameters',
                    'title' => 'ما حدود الوزن التي يجب أن تدخل في تقييم الفطام؟',
                    'help_text' => 'المصدر يطرح وزنًا مستهدفًا وحدًا أدنى مقبولًا للفطام. الوزن الفعلي لكل فرد يبقى سجلًا Canonical في 4.3 ويرتبط بعملية الفطام عبر 4.8.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 5,
                    'report_category' => 'weaning_weight_rule',
                    'target_entity' => 'weaning_readiness',
                    'options' => [
                        ['label' => 'وزن مستهدف عند الفطام', 'value' => 'target_weight'],
                        ['label' => 'حد أدنى مقبول للوزن عند الفطام', 'value' => 'minimum_weight'],
                    ],
                ],
                [
                    'seed_key' => 'weaning_rules.target_weight',
                    'title' => 'ما الوزن المستهدف عند الفطام؟',
                    'help_text' => 'استخدم نفس وحدة الوزن القياسية المعتمدة في Weight History. هذه قيمة مقارنة وليست قياسًا فعليًا.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 6,
                    'report_category' => 'weaning_weight_threshold',
                    'target_entity' => 'weaning_readiness',
                    'options' => [],
                ],
                [
                    'seed_key' => 'weaning_rules.minimum_weight',
                    'title' => 'ما الحد الأدنى المقبول للوزن عند الفطام؟',
                    'help_text' => 'هذه القيمة تستخدم عند اعتماد حد أدنى للوزن، وتطبق على الوزن الفعلي المسجل لكل فرد وفق سياسة التقييم المختارة.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 7,
                    'report_category' => 'weaning_weight_threshold',
                    'target_entity' => 'weaning_readiness',
                    'options' => [],
                ],
                [
                    'seed_key' => 'weaning_rules.age_weight_decision_model',
                    'title' => 'إذا كانت المزرعة تستخدم العمر والوزن معًا، كيف يجب أن يجتمعا في قرار الجاهزية للفطام؟',
                    'help_text' => 'المصدر يضع هذه العلاقة صراحة كنقطة تحتاج مراجعة. المطلوب حسم منطق القرار دون افتراض أن العمر أو الوزن وحده هو المعيار الصحيح لكل المزارع.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 8,
                    'report_category' => 'weaning_readiness_rule',
                    'target_entity' => 'weaning_readiness',
                    'options' => [
                        ['label' => 'يجب استيفاء حدود العمر والوزن المعتمدة معًا عندما يكون كلاهما مستخدمًا', 'value' => 'require_age_and_weight_together'],
                        ['label' => 'يكفي استيفاء أحد المعيارين عندما يكون كلاهما مستخدمًا', 'value' => 'age_or_weight_is_sufficient'],
                        ['label' => 'العمر حد أساسي، والوزن يؤثر كWarning / استثناء محكوم وفق 6.2', 'value' => 'age_required_weight_governed_soft_rule'],
                        ['label' => 'لا يستخدم الوزن كشرط جاهزية؛ يبقى للمتابعة والتحليل فقط', 'value' => 'age_based_readiness_weight_for_monitoring_only'],
                    ],
                ],
                [
                    'seed_key' => 'weaning_rules.readiness_review_factors',
                    'title' => 'ما المعلومات التي يجب أن يراجعها النظام قبل اعتماد عملية الفطام؟',
                    'help_text' => 'المصدر يذكر العدد الحالي والعمر والوزن والحالة الصحية وتوفر مكان مناسب. هذه مراجعة للجاهزية باستخدام البيانات الحالية، وليست حقولًا جديدة داخل حدث الفطام.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 9,
                    'report_category' => 'weaning_readiness_rule',
                    'target_entity' => 'weaning_readiness',
                    'options' => [
                        ['label' => 'العدد الحالي للمواليد الأحياء ومطابقته للأحداث المسجلة', 'value' => 'current_alive_count_and_reconciliation'],
                        ['label' => 'العمر الحالي مقارنة بحدود الفطام', 'value' => 'age_against_weaning_thresholds'],
                        ['label' => 'الوزن المسجل عند استخدام الوزن ضمن القواعد', 'value' => 'weight_against_weaning_thresholds'],
                        ['label' => 'الحالة الصحية وعدم وجود مانع واضح', 'value' => 'health_allows_weaning'],
                        ['label' => 'وجود سعة مؤهلة في مواقع الفطام وفق قواعد 6.4', 'value' => 'eligible_postweaning_housing_capacity'],
                    ],
                ],
                [
                    'seed_key' => 'weaning_rules.weight_evaluation_scope',
                    'title' => 'عند استخدام الوزن في قرار الفطام، على أي مستوى يجب تقييمه؟',
                    'help_text' => 'المصدر يفضل وزن الفطام الفردي بعد تحول كل مولود إلى سجل مستقل، لأن متوسط البطن قد يخفي أفرادًا أقل من الحد المطلوب.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 10,
                    'report_category' => 'weaning_weight_rule',
                    'target_entity' => 'weaning_readiness',
                    'options' => [
                        ['label' => 'تقييم وزن كل فرد على حدة؛ المتوسط للعرض والتحليل فقط', 'value' => 'individual_weight_evaluation'],
                        ['label' => 'يكفي متوسط وزن البطن لاتخاذ قرار الفطام للبطن كلها', 'value' => 'litter_average_weight_evaluation'],
                        ['label' => 'يعرض المتوسط ويقيّم الأفراد أيضًا، ويستخدم الفردي عند وجود تعارض', 'value' => 'individual_and_litter_summary_individual_controls'],
                    ],
                ],
                [
                    'seed_key' => 'weaning_rules.target_age_low_weight_handling',
                    'title' => 'كيف يجب التعامل مع مولود بلغ عمر الفطام المستهدف لكنه لم يصل إلى حد الوزن المطلوب؟',
                    'help_text' => 'هذه من نقاط المراجعة الصريحة في المصدر. المطلوب تحديد المسار دون اعتبار بلوغ العمر سببًا لإغلاق الرضاعة تلقائيًا أو تجاهل الوزن إذا كان معتمدًا كشرط.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 11,
                    'report_category' => 'weaning_exception_rule',
                    'target_entity' => 'weaning_readiness',
                    'options' => [
                        ['label' => 'يؤجل فطام الفرد حتى يصل للحد المطلوب أو تتم مراجعة الحالة', 'value' => 'defer_individual_until_weight_or_review'],
                        ['label' => 'إذا كان الفطام الجزئي معتمدًا، يفطم المستوفون وتبقى الحالات الأقل وزنًا تحت المتابعة', 'value' => 'partial_wean_eligible_keep_low_weight_under_lactation'],
                        ['label' => 'يسمح بالفطام كاستثناء محكوم وفق 6.2 مع توثيق السبب', 'value' => 'allow_governed_weaning_exception'],
                        ['label' => 'الوزن لا يمنع الفطام بعد بلوغ العمر؛ يظهر كمعلومة فقط', 'value' => 'weight_does_not_block_after_target_age'],
                    ],
                ],
                [
                    'seed_key' => 'weaning_rules.preparation_schedule_model',
                    'title' => 'هل تحتاج المزرعة إلى موعد استعداد للفطام يسبق العمر المستهدف؟',
                    'help_text' => 'المصدر يذكر بدء الاستعداد قبل موعد الفطام. هذا السؤال يحدد موعد الاستحقاق التشغيلي فقط؛ تحويله إلى Task أو Alert وطريقة التذكير يحسم في 6.12.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 12,
                    'report_category' => 'weaning_preparation_rule',
                    'target_entity' => 'weaning_preparation',
                    'options' => [
                        ['label' => 'لا يوجد موعد استعداد مستقل قبل الفطام', 'value' => 'no_separate_preparation_due_date'],
                        ['label' => 'يستحق الاستعداد قبل العمر المستهدف بعدد أيام قابل للضبط', 'value' => 'configured_days_before_target_age'],
                    ],
                ],
                [
                    'seed_key' => 'weaning_rules.preparation_lead_days',
                    'title' => 'قبل عمر الفطام المستهدف بكم يوم يبدأ استحقاق الاستعداد للفطام؟',
                    'help_text' => 'هذه قيمة لحساب موعد الاستعداد، ولا تعني أن النظام يرسل تنبيهًا بنفسه قبل حسم قواعد المهام والتنبيهات في 6.12.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 13,
                    'report_category' => 'weaning_preparation_threshold',
                    'target_entity' => 'weaning_preparation',
                    'options' => [],
                ],
                [
                    'seed_key' => 'weaning_rules.partial_weaning_eligibility_model',
                    'title' => 'إذا كان الفطام الجزئي مدعومًا في 4.8، كيف يجب تحديد أهلية الأفراد الذين سيفطمون قبل بقية البطن؟',
                    'help_text' => '4.8 يحسم هل العملية الجزئية مدعومة أصلًا. هنا نحسم القاعدة عند استخدامها حتى لا يكون اختيار بعض الأفراد قرارًا عشوائيًا بلا شروط.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 14,
                    'report_category' => 'partial_weaning_rule',
                    'target_entity' => 'weaning_readiness',
                    'options' => [
                        ['label' => 'لا ينطبق لأن الفطام الجزئي غير معتمد', 'value' => 'not_applicable_partial_weaning_not_supported'],
                        ['label' => 'كل فرد يجب أن يستوفي قواعد الجاهزية نفسها قبل فطامه جزئيًا', 'value' => 'each_individual_must_pass_standard_readiness'],
                        ['label' => 'يمكن تعريف شروط خاصة للفطام الجزئي تختلف عن الفطام الكامل', 'value' => 'separate_partial_weaning_criteria'],
                        ['label' => 'الفطام الجزئي استثناء تشغيلي فقط ويخضع لسياسة 6.2 مع تسجيل السبب', 'value' => 'partial_weaning_as_governed_exception_only'],
                    ],
                ],
                [
                    'seed_key' => 'weaning_rules.early_weaning_exception_model',
                    'title' => 'كيف يجب التعامل مع الحاجة إلى فطام مبكر بسبب مشكلة في الأم أو البطن قبل استيفاء القواعد العادية؟',
                    'help_text' => '4.7 يسمح بأن تكون إحدى نتائج مشكلة الرضاعة الانتقال إلى فطام مبكر إذا سمحت القواعد. هذا السؤال يحدد حدود السماح دون اختراع سبب صحي أو حدث جديد؛ الواقعة نفسها تسجل في Workflow المناسب.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 15,
                    'report_category' => 'early_weaning_rule',
                    'target_entity' => 'weaning_readiness',
                    'options' => [
                        ['label' => 'لا يسمح بالفطام قبل الحد الأدنى المعتمد للعمر', 'value' => 'no_early_weaning_before_minimum_age'],
                        ['label' => 'يسمح كاستثناء محكوم وفق 6.2 عند وجود حالة موثقة تستوجب ذلك', 'value' => 'governed_exception_for_documented_case'],
                        ['label' => 'يمكن تعريف مسار فطام مبكر له شروط أهلية مستقلة ومراجعة صريحة', 'value' => 'configured_early_weaning_criteria_with_review'],
                    ],
                ],
                [
                    'seed_key' => 'weaning_rules.sex_confirmation_deadline_model',
                    'title' => 'إذا سمح 4.8 بإنشاء فرد بجنس غير محدد عند الفطام، متى يجب حسم الجنس لاحقًا؟',
                    'help_text' => 'المصدر يضع توقيت تحديد الجنس كنقطة تحتاج مراجعة. هذا السؤال لا يغير طريقة تسجيل الجنس في 4.8؛ بل يحدد آخر نقطة مقبولة لبقائه غير محدد.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 16,
                    'report_category' => 'sex_identification_rule',
                    'target_entity' => 'animal_sex_identification',
                    'options' => [
                        ['label' => 'يجب تأكيد الجنس ضمن عملية الفطام نفسها', 'value' => 'confirm_at_weaning'],
                        ['label' => 'يمكن أن يبقى غير محدد حتى عمر ثابت قابل للضبط', 'value' => 'confirm_by_configured_age'],
                        ['label' => 'يجب تأكيده قبل أول حركة أو تسكين يعتمد على الفصل حسب الجنس', 'value' => 'confirm_before_sex_sensitive_housing'],
                        ['label' => 'لا يوجد موعد ثابت؛ يبقى غير محدد حتى يتم تأكيده فعليًا', 'value' => 'no_fixed_confirmation_deadline'],
                    ],
                ],
                [
                    'seed_key' => 'weaning_rules.sex_confirmation_age_days',
                    'title' => 'عند استخدام عمر محدد لتأكيد الجنس، ما هذا العمر بالأيام؟',
                    'help_text' => 'هذه القيمة تحدد آخر موعد لتأكيد الجنس فقط، ولا تنشئ تغييرًا تلقائيًا في قيمة الجنس.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 17,
                    'report_category' => 'sex_identification_threshold',
                    'target_entity' => 'animal_sex_identification',
                    'options' => [],
                ],
                [
                    'seed_key' => 'weaning_rules.sex_separation_trigger_model',
                    'title' => 'متى يجب أن تصبح قاعدة فصل الذكور عن الإناث واجبة التطبيق في التسكين الجماعي؟',
                    'help_text' => '6.4 يحسم ماذا يحدث بعد وصول المجموعة إلى نقطة الفصل. هذا السؤال يحدد نقطة بدء تطبيق القاعدة: عند الفطام أو لاحقًا عند عمر محدد.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 18,
                    'report_category' => 'sex_separation_rule',
                    'target_entity' => 'sex_separation_housing_rule',
                    'options' => [
                        ['label' => 'الفصل واجب مباشرة عند الفطام', 'value' => 'separate_at_weaning'],
                        ['label' => 'يبدأ الفصل عند عمر محدد بعد الفطام', 'value' => 'separate_at_configured_age'],
                        ['label' => 'لا توجد نقطة فصل إلزامية حسب الجنس', 'value' => 'no_mandatory_sex_separation_trigger'],
                    ],
                ],
                [
                    'seed_key' => 'weaning_rules.sex_separation_age_days',
                    'title' => 'ما العمر الذي يجب عنده بدء الفصل الإلزامي بين الذكور والإناث، بالأيام؟',
                    'help_text' => 'هذه القيمة تحدد Trigger الفصل. آلية التحذير أو المهمة عند الاقتراب منه تعالج في 6.12، وقواعد منع التسكين المختلط بعده موجودة في 6.4.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 19,
                    'report_category' => 'sex_separation_threshold',
                    'target_entity' => 'sex_separation_housing_rule',
                    'options' => [],
                ],
                [
                    'seed_key' => 'weaning_rules.projected_housing_capacity_model',
                    'title' => 'كيف يجب فحص سعة مواقع الفطام قبل اعتماد الجاهزية أو بدء عملية الفطام؟',
                    'help_text' => 'المصدر يؤكد أن النظام لا ينبغي أن يطلب نقل عدد من المفطومين إلى سعة غير كافية. أهلية المواقع نفسها يحكمها 6.4؛ هنا نحدد نطاق الفحص المسبق الخاص بعملية الفطام.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 20,
                    'report_category' => 'weaning_capacity_rule',
                    'target_entity' => 'weaning_housing_capacity',
                    'options' => [
                        ['label' => 'يجب توفر سعة مؤهلة لكل العدد المتوقع فطامه قبل بدء العملية', 'value' => 'require_capacity_for_full_expected_weaned_count'],
                        ['label' => 'يكفي توفر سعة للدفعة التي ستفطم الآن عند دعم الفطام الجزئي', 'value' => 'require_capacity_for_current_weaning_batch'],
                        ['label' => 'يعرض النظام عجز السعة كتحذير فقط ويترك قرار التنفيذ لسياسة 6.4 و6.2', 'value' => 'capacity_shortage_as_precheck_warning'],
                    ],
                ],
                [
                    'seed_key' => 'weaning_rules.weaned_group_distribution_model',
                    'title' => 'إذا لم يتسع قفص فطام واحد لكل المفطومين لكن توجد سعة كافية موزعة على عدة أقفاص مؤهلة، كيف يجب توزيعهم؟',
                    'help_text' => 'المصدر يذكر أن توزيع المفطومين على الأقفاص يحتاج قواعد، بينما 4.8 يدعم اختيار قفص أو أكثر وينشئ حركة تسكين فردية لكل حيوان.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 21,
                    'report_category' => 'weaning_housing_rule',
                    'target_entity' => 'weaning_housing_distribution',
                    'options' => [
                        ['label' => 'يسمح بتوزيع المفطومين على عدة أقفاص مؤهلة حتى تكتمل السعة المطلوبة', 'value' => 'allow_distribution_across_multiple_eligible_cages'],
                        ['label' => 'يجب إبقاء أفراد البطن في قفص واحد ما أمكن، ولا يتم التقسيم إلا باستثناء موثق', 'value' => 'prefer_single_cage_split_by_governed_exception'],
                        ['label' => 'يطبق التوزيع وفق قواعد الجمع والفصل والسعة في 6.4 دون تفضيل لبقاء البطن معًا', 'value' => 'distribution_follows_general_housing_rules'],
                    ],
                ],
                [
                    'seed_key' => 'weaning_rules.readiness_state_model',
                    'title' => 'كيف يجب أن ينتج النظام حالة جاهزية الفطام المعروضة للبطن أو للأفراد؟',
                    'help_text' => 'المصدر يقترح حالات مثل غير مستحق، يقترب من الفطام، مستحق، مستحق والوزن غير مناسب، ومتأخر. المطلوب ألا تتحول هذه الحالات إلى Status يدوي يناقض العمر والوزن والسجلات الحالية.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 22,
                    'report_category' => 'derivation_rule',
                    'target_entity' => 'weaning_readiness',
                    'options' => [
                        ['label' => 'تشتق تلقائيًا من العمر والوزن والحالة والسعة والقواعد الفعالة مع إظهار أسباب عدم الجاهزية', 'value' => 'derive_from_current_data_and_rules'],
                        ['label' => 'تشتق مع السماح بـOverride مؤقت موثق وفق 6.2 عندما تسمح القاعدة', 'value' => 'derived_with_governed_temporary_override'],
                        ['label' => 'Status يدوي يحدده المستخدم بصورة مستقلة عن القواعد', 'value' => 'manual_weaning_readiness_status'],
                    ],
                ],
            ];

            app(QuestionSeederSyncService::class)->sync(
                mainSectionName: 'الإعدادات وقواعد التشغيل',
                sectionName: 'قواعد الفطام والانتقال للتتبع الفردي',
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
            ['weaning_rules.temporal_parameters', 'weaning_rules.target_age_days', QuestionDependencyOperator::CONTAINS, 'target_age'],
            ['weaning_rules.temporal_parameters', 'weaning_rules.minimum_age_days', QuestionDependencyOperator::CONTAINS, 'minimum_age'],
            ['weaning_rules.temporal_parameters', 'weaning_rules.maximum_age_days', QuestionDependencyOperator::CONTAINS, 'maximum_age_before_overdue'],
            ['weaning_rules.weight_parameters', 'weaning_rules.target_weight', QuestionDependencyOperator::CONTAINS, 'target_weight'],
            ['weaning_rules.weight_parameters', 'weaning_rules.minimum_weight', QuestionDependencyOperator::CONTAINS, 'minimum_weight'],
            ['weaning_rules.preparation_schedule_model', 'weaning_rules.preparation_lead_days', QuestionDependencyOperator::EQUALS, 'configured_days_before_target_age'],
            ['weaning_rules.sex_confirmation_deadline_model', 'weaning_rules.sex_confirmation_age_days', QuestionDependencyOperator::EQUALS, 'confirm_by_configured_age'],
            ['weaning_rules.sex_separation_trigger_model', 'weaning_rules.sex_separation_age_days', QuestionDependencyOperator::EQUALS, 'separate_at_configured_age'],
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
            ->where('name', 'قواعد الفطام والانتقال للتتبع الفردي')
            ->first();
    }
}
