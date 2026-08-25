<?php

namespace Database\Seeders\Questions\Settings;

use App\Enums\Questionnaire\QuestionDependencyOperator;
use App\Enums\Questionnaire\QuestionType;
use App\Models\QuestionnaireQuestion;
use App\Models\QuestionnaireSection;
use App\Services\Questionnaire\QuestionSeederSyncService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GrowthWeightSortingReplacementRulesQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $questions = [
                [
                    'seed_key' => 'growth_rules.weight_schedule_components',
                    'title' => 'ما مكونات برنامج الوزن الفردي بعد الفطام التي يجب أن تدعمها قواعد التشغيل؟',
                    'help_text' => 'الوزن الفعلي يظل سجلًا Canonical في 4.3. هنا نحدد فقط كيف تستحق مواعيد الوزن بعد الفطام، دون تثبيت أمثلة الأعمار الموجودة في التصور.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 1,
                    'report_category' => 'growth_weight_schedule_rule',
                    'target_entity' => 'growth_weight_program',
                    'options' => [
                        ['label' => 'أول وزن مجدول بعد الفطام بفاصل أيام محدد', 'value' => 'first_postweaning_delay'],
                        ['label' => 'وزن دوري كل عدد أيام محدد', 'value' => 'periodic_interval'],
                        ['label' => 'أوزان عند أعمار / نقاط زمنية محددة', 'value' => 'age_checkpoints'],
                        ['label' => 'وزن مرتبط بمراحل الفرز / التقييم المعتمدة', 'value' => 'sorting_stage_weights'],
                        ['label' => 'لا يوجد برنامج وزن ثابت؛ الوزن عند الحاجة فقط', 'value' => 'weight_when_needed_only'],
                    ],
                ],
                [
                    'seed_key' => 'growth_rules.first_postweaning_weight_delay_days',
                    'title' => 'بعد كم يوم من الفطام يستحق أول وزن مجدول بعد الفطام؟',
                    'help_text' => 'هذه القيمة تستخدم فقط إذا اعتمدت المزرعة أول وزن مجدول بعد الفطام. وزن الفطام نفسه يبقى جزءًا من عملية الفطام وسجل الأوزان الموحد.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 2,
                    'report_category' => 'growth_weight_schedule_threshold',
                    'target_entity' => 'growth_weight_program',
                    'options' => [],
                ],
                [
                    'seed_key' => 'growth_rules.periodic_weight_interval_days',
                    'title' => 'كل كم يوم يستحق الوزن الدوري خلال مرحلة النمو؟',
                    'help_text' => 'القيمة تحدد الجدولة فقط. كل قياس منفذ فعليًا يسجل كسجل وزن مستقل في 4.3.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 3,
                    'report_category' => 'growth_weight_schedule_threshold',
                    'target_entity' => 'growth_weight_program',
                    'options' => [],
                ],
                [
                    'seed_key' => 'growth_rules.weight_checkpoint_ages',
                    'title' => 'ما الأعمار التي يجب استخدامها كنقاط وزن أساسية بعد الفطام؟',
                    'help_text' => 'اكتب الأعمار بالأيام حسب برنامج المزرعة الفعلي. أمثلة 45 و70 يومًا و3 أشهر في التصور ليست قيمًا مفروضة.',
                    'type' => QuestionType::TEXTAREA,
                    'is_required' => true,
                    'sort_order' => 4,
                    'report_category' => 'growth_weight_schedule_checkpoints',
                    'target_entity' => 'growth_weight_program',
                    'options' => [],
                ],
                [
                    'seed_key' => 'growth_rules.growth_metrics_used_for_evaluation',
                    'title' => 'ما مؤشرات النمو المشتقة من سجل الأوزان التي يجب أن تدخل في تقييم الحيوان أثناء النمو والفرز؟',
                    'help_text' => 'هذه المؤشرات تحسب من Weight History ولا تدخل يدويًا كأرقام مستقلة. 4.9 يحفظ سياق المؤشرات المستخدمة عند التقييم حسب النموذج المعتمد.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 5,
                    'report_category' => 'growth_metric_rule',
                    'target_entity' => 'growth_evaluation_rule',
                    'options' => [
                        ['label' => 'الزيادة منذ آخر وزن', 'value' => 'gain_since_previous_weight'],
                        ['label' => 'الزيادة منذ وزن الفطام', 'value' => 'gain_since_weaning'],
                        ['label' => 'متوسط الزيادة اليومية', 'value' => 'average_daily_gain'],
                        ['label' => 'نسبة / معدل النمو خلال الفترة', 'value' => 'growth_rate'],
                        ['label' => 'الوزن الحالي مقابل الوزن المستهدف للمرحلة', 'value' => 'current_vs_stage_target_weight'],
                    ],
                ],
                [
                    'seed_key' => 'growth_rules.low_growth_detection_model',
                    'title' => 'كيف يجب تحديد حالة «نمو أقل من المستهدف» التي تظهر أثناء المتابعة قبل قرار الفرز؟',
                    'help_text' => 'المصدر يؤكد أن انخفاض النمو لا يستبعد الحيوان تلقائيًا؛ هو مؤشر يدخل في التقييم. إنشاء Alert فعلي ومستواه وأولويته يعالج لاحقًا في 6.12.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 6,
                    'report_category' => 'growth_threshold_rule',
                    'target_entity' => 'growth_evaluation_rule',
                    'options' => [
                        ['label' => 'الوزن أقل من الحد / الهدف المعتمد للمرحلة', 'value' => 'weight_below_stage_threshold'],
                        ['label' => 'معدل النمو أقل من الحد المعتمد', 'value' => 'growth_rate_below_threshold'],
                        ['label' => 'يكفي تحقق أي من الوزن أو معدل النمو ليظهر المؤشر', 'value' => 'weight_or_growth_rate_below_threshold'],
                        ['label' => 'لا يستخدم مؤشر آلي لضعف النمو؛ يعرض النظام البيانات فقط أثناء الفرز', 'value' => 'no_automatic_low_growth_indicator'],
                    ],
                ],
                [
                    'seed_key' => 'growth_rules.deviation_threshold_enabled',
                    'title' => 'هل تحتاج المزرعة إلى نسبة انحراف محددة عن الوزن المستهدف لاستخدامها في تصنيف انخفاض النمو؟',
                    'help_text' => 'التصور يذكر نسبة الانحراف التي تستوجب الانتباه كقيمة قابلة للضبط. هذه النسبة لا تنشئ استبعادًا أو قرار مصير تلقائيًا.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 7,
                    'report_category' => 'growth_threshold_rule',
                    'target_entity' => 'growth_evaluation_rule',
                    'options' => [],
                ],
                [
                    'seed_key' => 'growth_rules.allowed_weight_deviation_percentage',
                    'title' => 'ما نسبة الانحراف المسموح بها عن الوزن المستهدف قبل تصنيف الوزن بأنه أقل من المستوى المطلوب؟',
                    'help_text' => 'سجل النسبة المئوية المستخدمة في المقارنة. التنبيه الناتج عن تجاوزها، إن اعتمد، يعالج في 6.12.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 8,
                    'report_category' => 'growth_threshold',
                    'target_entity' => 'growth_evaluation_rule',
                    'options' => [],
                ],
                [
                    'seed_key' => 'sorting_rules.stage_definition_model',
                    'title' => 'كيف يجب تعريف مراحل الفرز والتقييم بعد الفطام؟',
                    'help_text' => 'التصور يستخدم 45 يومًا و70 يومًا و3 أشهر كأمثلة فقط، ويقترح أن تكون المراحل قابلة للإعداد بدل تثبيتها في الكود.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 9,
                    'report_category' => 'sorting_stage_rule',
                    'target_entity' => 'sorting_stage',
                    'options' => [
                        ['label' => 'قائمة مراحل مرتبة وقابلة للتهيئة لكل مزرعة / نطاق إعدادات', 'value' => 'configurable_ordered_stages'],
                        ['label' => 'مراحل ثابتة في النظام مع قيم توقيت قابلة للتعديل', 'value' => 'fixed_stage_structure_configurable_timing'],
                        ['label' => 'مرحلة تقييم واحدة فقط قبل تحديد المصير', 'value' => 'single_evaluation_stage'],
                    ],
                ],
                [
                    'seed_key' => 'sorting_rules.stage_definitions',
                    'title' => 'ما مراحل الفرز التي تريد اعتمادها وما التوقيت المستهدف لكل مرحلة؟',
                    'help_text' => 'اكتب كل مرحلة في سطر مستقل مع اسمها والعمر المستهدف وفترة السماح قبل/بعد العمر عند الحاجة. لا تعتمد أمثلة التصور إلا إذا كانت مطابقة للتشغيل الفعلي.',
                    'type' => QuestionType::TEXTAREA,
                    'is_required' => true,
                    'sort_order' => 10,
                    'report_category' => 'sorting_stage_configuration',
                    'target_entity' => 'sorting_stage',
                    'options' => [],
                ],
                [
                    'seed_key' => 'sorting_rules.stage_configurable_fields',
                    'title' => 'ما القيم التي يجب أن يمكن ضبطها بصورة مستقلة لكل مرحلة فرز؟',
                    'help_text' => 'المصدر يقترح أن تحمل كل مرحلة تعريفها الزمني ومتطلبات الوزن والبيانات والقرارات المتاحة. موعد التنبيه نفسه يتكامل لاحقًا مع 6.12.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 11,
                    'report_category' => 'sorting_stage_configuration',
                    'target_entity' => 'sorting_stage',
                    'options' => [
                        ['label' => 'اسم المرحلة', 'value' => 'stage_name'],
                        ['label' => 'العمر المستهدف', 'value' => 'target_age'],
                        ['label' => 'فترة السماح قبل العمر المستهدف', 'value' => 'early_tolerance'],
                        ['label' => 'فترة السماح بعد العمر المستهدف قبل اعتبارها متأخرة', 'value' => 'late_tolerance'],
                        ['label' => 'هل الوزن مطلوب عند المرحلة', 'value' => 'weight_required'],
                        ['label' => 'الوزن المستهدف', 'value' => 'target_weight'],
                        ['label' => 'الحد الأدنى المقبول للوزن', 'value' => 'minimum_weight'],
                        ['label' => 'عوامل / بيانات التقييم المطلوبة', 'value' => 'evaluation_factors'],
                        ['label' => 'النتائج / القرارات المبدئية المسموح بها', 'value' => 'allowed_preliminary_results'],
                    ],
                ],
                [
                    'seed_key' => 'sorting_rules.evaluation_factors',
                    'title' => 'ما العوامل التي يجب أن تكون متاحة كمعايير عند تقييم الحيوان في مراحل الفرز؟',
                    'help_text' => '4.9 يعرض هذه المعلومات ويسجل نتيجة التقييم الفعلية. هنا نحدد العناصر التي يمكن أن تدخل في القاعدة أو التقييم دون تكرار بياناتها الأصلية.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 12,
                    'report_category' => 'sorting_evaluation_rule',
                    'target_entity' => 'growth_evaluation_rule',
                    'options' => [
                        ['label' => 'العمر', 'value' => 'age'],
                        ['label' => 'الوزن الحالي', 'value' => 'current_weight'],
                        ['label' => 'مؤشرات النمو المشتقة', 'value' => 'derived_growth_metrics'],
                        ['label' => 'الجنس', 'value' => 'sex'],
                        ['label' => 'الحالة الصحية', 'value' => 'health_condition'],
                        ['label' => 'التكوين العام / الصفات الظاهرية', 'value' => 'body_conformation'],
                        ['label' => 'وجود عيوب ظاهرة', 'value' => 'visible_defects'],
                        ['label' => 'السلالة', 'value' => 'breed'],
                        ['label' => 'النسب', 'value' => 'pedigree'],
                        ['label' => 'أداء الأب', 'value' => 'sire_performance'],
                        ['label' => 'أداء الأم', 'value' => 'dam_performance'],
                        ['label' => 'أداء البطن / مقارنة الإخوة', 'value' => 'litter_and_sibling_performance'],
                        ['label' => 'نتائج الفرز السابقة', 'value' => 'previous_evaluations'],
                    ],
                ],
                [
                    'seed_key' => 'sorting_rules.conformation_evaluation_model',
                    'title' => 'كيف يجب تقييم التكوين والصفات الظاهرية عند استخدامها في الفرز؟',
                    'help_text' => 'المصدر يضع طريقة تقييم التكوين والصفات الظاهرية كنقطة تحتاج حسمًا. المطلوب تحديد شكل المعيار دون افتراض صفات غير مذكورة أو إنشاء نظام تقييم بيطري جديد.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 13,
                    'report_category' => 'sorting_evaluation_rule',
                    'target_entity' => 'conformation_evaluation_rule',
                    'options' => [
                        ['label' => 'قائمة صفات / ملاحظات معرفة مسبقًا يمكن تحديدها أثناء الفرز', 'value' => 'configured_trait_checklist'],
                        ['label' => 'تقييم عام بنص / ملاحظات دون قائمة صفات منظمة', 'value' => 'general_free_form_observation'],
                        ['label' => 'لا تدخل الصفات الظاهرية كمعيار منظم في الفرز', 'value' => 'no_structured_conformation_criterion'],
                    ],
                ],
                [
                    'seed_key' => 'sorting_rules.stage_weight_requirement_model',
                    'title' => 'كيف يجب التعامل مع عدم وجود وزن حديث عند استحقاق مرحلة فرز تتطلب الوزن؟',
                    'help_text' => 'المصدر يفترض استخدام الوزن في مراحل الفرز. المطلوب حسم هل يمنع التقييم حتى يسجل وزن موثوق أم يسمح بالتقييم مع نقص واضح في البيانات.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 14,
                    'report_category' => 'sorting_validation_rule',
                    'target_entity' => 'growth_evaluation_rule',
                    'options' => [
                        ['label' => 'يجب تسجيل / ربط وزن حديث قبل إكمال المرحلة التي تشترط الوزن', 'value' => 'require_current_weight_before_stage_completion'],
                        ['label' => 'يسمح بالتقييم مع إظهار أن شرط الوزن غير مكتمل وتطبيق سياسة Warning / Override في 6.2', 'value' => 'allow_with_missing_weight_governed_rule'],
                        ['label' => 'الوزن للعرض فقط ولا يمنع إكمال أي مرحلة', 'value' => 'weight_is_context_only'],
                    ],
                ],
                [
                    'seed_key' => 'sorting_rules.overdue_stage_behavior',
                    'title' => 'إذا تجاوز الحيوان توقيت مرحلة الفرز دون تنفيذ التقييم، كيف يجب أن تتعامل القاعدة مع المسار؟',
                    'help_text' => 'التصور يؤكد أن تأخر الفرز لا ينقل الحيوان تلقائيًا إلى المرحلة التالية؛ الانتقال يحتاج تقييمًا وقرارًا مسجلًا.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 15,
                    'report_category' => 'sorting_overdue_rule',
                    'target_entity' => 'sorting_stage',
                    'options' => [
                        ['label' => 'يبقى الحيوان في المرحلة المستحقة ويصنف الفرز متأخرًا حتى التنفيذ', 'value' => 'remain_in_overdue_stage_until_evaluated'],
                        ['label' => 'يسمح بتجاوز المرحلة المتأخرة والانتقال للمرحلة التالية باستثناء موثق وفق 6.2', 'value' => 'allow_skip_with_governed_exception'],
                        ['label' => 'ينتقل تلقائيًا للمرحلة التالية عند تجاوز الموعد', 'value' => 'auto_advance_when_overdue'],
                    ],
                ],
                [
                    'seed_key' => 'sorting_rules.reevaluation_schedule_model',
                    'title' => 'عند اختيار «إعادة تقييم»، كيف يجب تحديد موعد المراجعة الجديدة؟',
                    'help_text' => '4.9 يسجل سبب إعادة التقييم والموعد الفعلي المرتبط بها. هنا نحدد قاعدة الموعد دون إجبار المستخدم على قرار نهائي غير مناسب.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 16,
                    'report_category' => 'reevaluation_rule',
                    'target_entity' => 'growth_reevaluation_rule',
                    'options' => [
                        ['label' => 'بعد عدد أيام افتراضي قابل للضبط مع إمكانية اختيار موعد آخر', 'value' => 'configured_default_interval_editable'],
                        ['label' => 'يحدد المستخدم موعد المراجعة في كل حالة دون فاصل افتراضي', 'value' => 'manual_review_date_per_case'],
                        ['label' => 'ترتبط إعادة التقييم تلقائيًا بموعد المرحلة التالية فقط', 'value' => 'use_next_sorting_stage_date'],
                    ],
                ],
                [
                    'seed_key' => 'sorting_rules.reevaluation_default_interval_days',
                    'title' => 'ما الفاصل الافتراضي بالأيام قبل إعادة تقييم الحيوان؟',
                    'help_text' => 'هذه قيمة افتراضية للجدولة ويمكن أن يظل الموعد الفعلي محفوظًا في حدث إعادة التقييم وفق 4.9.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 17,
                    'report_category' => 'reevaluation_interval',
                    'target_entity' => 'growth_reevaluation_rule',
                    'options' => [],
                ],
                [
                    'seed_key' => 'replacement_rules.preliminary_candidate_entry_model',
                    'title' => 'متى يمكن أن يحصل الحيوان على نتيجة «مرشح مبدئي للقطيع» أثناء مراحل الفرز؟',
                    'help_text' => 'المصدر يسمح بترشيح مبدئي مبكر مع استمرار المتابعة، ويؤكد أن الترشيح لا يعني الاعتماد النهائي للقطيع.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 18,
                    'report_category' => 'replacement_candidate_rule',
                    'target_entity' => 'replacement_candidate',
                    'options' => [
                        ['label' => 'يمكن الترشيح المبدئي من أي مرحلة تسمح به ضمن إعدادها', 'value' => 'any_stage_configured_for_preliminary_candidate'],
                        ['label' => 'لا يسمح بالترشيح إلا من مرحلة فرز محددة في الإعدادات', 'value' => 'configured_candidate_entry_stage_only'],
                        ['label' => 'الترشيح لا يبدأ إلا بعد إكمال آخر مرحلة فرز', 'value' => 'only_after_final_sorting_stage'],
                    ],
                ],
                [
                    'seed_key' => 'replacement_rules.selection_factors',
                    'title' => 'ما العوامل التي يجب أن تدخل في قرار ترشيح الحيوان للإحلال / القطيع؟',
                    'help_text' => 'هذه العوامل مستمدة من تصور الإحلال الداخلي. سجل الترشيح الفعلي يبقى في 4.11، بينما هنا نحدد ما الذي يجب تقييمه قبل الترشيح.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 19,
                    'report_category' => 'replacement_selection_rule',
                    'target_entity' => 'replacement_candidate',
                    'options' => [
                        ['label' => 'معدل النمو', 'value' => 'growth_performance'],
                        ['label' => 'الوزن', 'value' => 'weight'],
                        ['label' => 'الحالة الصحية', 'value' => 'health_condition'],
                        ['label' => 'التكوين / الصفات الجسمية المطلوبة', 'value' => 'body_conformation'],
                        ['label' => 'السلالة', 'value' => 'breed'],
                        ['label' => 'النسب', 'value' => 'pedigree'],
                        ['label' => 'أداء الأم', 'value' => 'dam_performance'],
                        ['label' => 'أداء الأب', 'value' => 'sire_performance'],
                        ['label' => 'أداء البطن / مقارنة الإخوة', 'value' => 'litter_and_sibling_performance'],
                        ['label' => 'احتياج المزرعة الفعلي للإحلال في الدور الإنتاجي المطلوب', 'value' => 'replacement_need'],
                    ],
                ],
                [
                    'seed_key' => 'replacement_rules.sex_specific_selection_model',
                    'title' => 'هل يجب أن تختلف معايير ترشيح الذكور عن معايير ترشيح الإناث للإحلال؟',
                    'help_text' => 'المصدر يضع اختلاف معايير اختيار ذكر الإحلال عن أنثى الإحلال كسؤال مفتوح يحتاج قرارًا.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 20,
                    'report_category' => 'replacement_selection_rule',
                    'target_entity' => 'replacement_candidate',
                    'options' => [
                        ['label' => 'نفس المعايير الأساسية لكلا الجنسين', 'value' => 'same_selection_criteria_for_both_sexes'],
                        ['label' => 'معايير أساسية مشتركة مع معايير إضافية مختلفة للذكور والإناث', 'value' => 'shared_core_plus_sex_specific_criteria'],
                        ['label' => 'معايير مستقلة بالكامل لكل جنس', 'value' => 'independent_criteria_by_sex'],
                    ],
                ],
                [
                    'seed_key' => 'replacement_rules.candidate_minimum_age_enabled',
                    'title' => 'هل يجب تحديد حد أدنى للعمر قبل السماح بترشيح الحيوان للإحلال؟',
                    'help_text' => 'عمر بداية الترشيح نقطة مفتوحة في المصدر. هذه القاعدة تخص الترشيح ولا تعني بلوغ عمر الاعتماد النهائي أو عمر أول تلقيح.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 21,
                    'report_category' => 'replacement_candidate_rule',
                    'target_entity' => 'replacement_candidate',
                    'options' => [],
                ],
                [
                    'seed_key' => 'replacement_rules.candidate_minimum_age_days',
                    'title' => 'ما الحد الأدنى لعمر ترشيح الحيوان للإحلال، بالأيام؟',
                    'help_text' => 'هذه قيمة خاصة ببداية مرحلة المرشح، وليست عمر الاعتماد النهائي أو جاهزية التلقيح.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 22,
                    'report_category' => 'replacement_candidate_threshold',
                    'target_entity' => 'replacement_candidate',
                    'options' => [],
                ],
                [
                    'seed_key' => 'replacement_rules.candidate_followup_duration_model',
                    'title' => 'كيف يجب التعامل مع مدة متابعة المرشح قبل الاعتماد النهائي؟',
                    'help_text' => 'المصدر يؤكد وجود مرحلة «مرشح تحت المتابعة» ويسأل عن مدتها. خلال هذه الفترة يستمر الوزن والنمو والصحة والنسب قبل قرار الاعتماد.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 23,
                    'report_category' => 'replacement_followup_rule',
                    'target_entity' => 'replacement_candidate',
                    'options' => [
                        ['label' => 'لا توجد مدة دنيا ثابتة؛ يمكن الاعتماد عند استيفاء شروط الاعتماد', 'value' => 'no_fixed_minimum_followup'],
                        ['label' => 'توجد مدة متابعة دنيا قبل السماح بالاعتماد', 'value' => 'configured_minimum_followup_period'],
                        ['label' => 'توجد مدة متابعة مستهدفة للمراجعة لكن يمكن الاعتماد قبلها إذا استوفيت الشروط', 'value' => 'target_followup_period_not_hard_minimum'],
                    ],
                ],
                [
                    'seed_key' => 'replacement_rules.candidate_followup_days',
                    'title' => 'ما مدة متابعة المرشح بالأيام عند استخدام مدة قابلة للضبط؟',
                    'help_text' => 'تستخدم كمدة دنيا أو مستهدفة حسب النموذج المختار، بينما تاريخ الاعتماد الفعلي يسجل في 4.11.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => false,
                    'sort_order' => 24,
                    'report_category' => 'replacement_followup_duration',
                    'target_entity' => 'replacement_candidate',
                    'options' => [],
                ],
                [
                    'seed_key' => 'replacement_rules.approval_factors',
                    'title' => 'ما الشروط التي يجب أن تدخل في فحص الاعتماد النهائي لمرشح الإحلال داخل القطيع الإنتاجي؟',
                    'help_text' => '4.11 ينفذ حدث الاعتماد ويحتفظ بنتيجة فحص القواعد. هنا نحدد معايير الاعتماد نفسها دون خلطها بجاهزية التلقيح في 6.5.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 25,
                    'report_category' => 'replacement_approval_rule',
                    'target_entity' => 'production_herd_approval',
                    'options' => [
                        ['label' => 'حد أدنى للعمر', 'value' => 'minimum_age'],
                        ['label' => 'حد أدنى للوزن', 'value' => 'minimum_weight'],
                        ['label' => 'الحالة الصحية مناسبة', 'value' => 'health_condition_acceptable'],
                        ['label' => 'لا يوجد سبب / قرار يمنع دخوله القطيع الإنتاجي', 'value' => 'no_blocking_exclusion_or_fate'],
                        ['label' => 'النسب مناسب للهدف الإنتاجي', 'value' => 'pedigree_acceptable'],
                        ['label' => 'نتيجة الفرز / التقييم مقبولة', 'value' => 'sorting_result_acceptable'],
                        ['label' => 'الصفات / التكوين المطلوب مستوفى عند استخدامه', 'value' => 'required_conformation_met'],
                        ['label' => 'الاحتياج التشغيلي للدور الإنتاجي يسمح بالاعتماد', 'value' => 'replacement_need_allows_approval'],
                    ],
                ],
                [
                    'seed_key' => 'replacement_rules.approval_age_weight_source_model',
                    'title' => 'كيف يجب تحديد حدود العمر والوزن المستخدمة لاعتماد الحيوان داخل القطيع الإنتاجي مقارنة بحدود جاهزية التلقيح في 6.5؟',
                    'help_text' => 'المصدر يوضح أن الحيوان قد يصبح فرد قطيع إنتاجي لكنه يظل غير جاهز لأول تلقيح. لذلك يجب حسم ما إذا كانت حدود الاعتماد مستقلة أم تعيد استخدام حدود الجاهزية التناسلية.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 26,
                    'report_category' => 'replacement_approval_threshold_rule',
                    'target_entity' => 'production_herd_approval',
                    'options' => [
                        ['label' => 'حدود اعتماد مستقلة عن حدود جاهزية التلقيح في 6.5', 'value' => 'independent_approval_thresholds'],
                        ['label' => 'إعادة استخدام حدود جاهزية التلقيح، فلا يعتمد الحيوان قبل بلوغها', 'value' => 'reuse_mating_readiness_thresholds'],
                        ['label' => 'لا يستخدم العمر والوزن كحدود منع في الاعتماد؛ يظهران ضمن سياق التقييم فقط', 'value' => 'age_weight_are_advisory_context_only'],
                    ],
                ],
                [
                    'seed_key' => 'replacement_rules.approval_threshold_scope',
                    'title' => 'عند استخدام حدود اعتماد مستقلة، هل تكون قيم العمر والوزن واحدة للذكور والإناث أم مختلفة؟',
                    'help_text' => 'هذا السؤال يظهر فقط عند اعتماد حدود مستقلة عن جاهزية التلقيح.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 27,
                    'report_category' => 'replacement_approval_threshold_rule',
                    'target_entity' => 'production_herd_approval',
                    'options' => [
                        ['label' => 'نفس الحدود للذكور والإناث', 'value' => 'shared_thresholds_by_sex'],
                        ['label' => 'حدود مختلفة للذكور والإناث', 'value' => 'sex_specific_thresholds'],
                    ],
                ],
                [
                    'seed_key' => 'replacement_rules.shared_minimum_approval_age_days',
                    'title' => 'ما الحد الأدنى المشترك لعمر الاعتماد داخل القطيع الإنتاجي، بالأيام؟',
                    'help_text' => 'يستخدم فقط عند اعتماد حدود مستقلة وموحدة لكلا الجنسين.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 28,
                    'report_category' => 'replacement_approval_threshold',
                    'target_entity' => 'production_herd_approval',
                    'options' => [],
                ],
                [
                    'seed_key' => 'replacement_rules.shared_minimum_approval_weight',
                    'title' => 'ما الحد الأدنى المشترك لوزن الاعتماد داخل القطيع الإنتاجي؟',
                    'help_text' => 'استخدم نفس وحدة الوزن القياسية في Weight History. القيمة Threshold وليست سجل وزن مستقلًا.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 29,
                    'report_category' => 'replacement_approval_threshold',
                    'target_entity' => 'production_herd_approval',
                    'options' => [],
                ],
                [
                    'seed_key' => 'replacement_rules.female_minimum_approval_age_days',
                    'title' => 'ما الحد الأدنى لعمر اعتماد الأنثى داخل القطيع الإنتاجي، بالأيام؟',
                    'help_text' => 'هذه القيمة مستقلة عن عمر أول تلقيح في 6.5 ويمكن أن تكون أقل منه إذا كان نموذج التشغيل يسمح بفترة بين الاعتماد والجاهزية التناسلية.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 30,
                    'report_category' => 'replacement_approval_threshold',
                    'target_entity' => 'female_production_herd_approval',
                    'options' => [],
                ],
                [
                    'seed_key' => 'replacement_rules.female_minimum_approval_weight',
                    'title' => 'ما الحد الأدنى لوزن اعتماد الأنثى داخل القطيع الإنتاجي؟',
                    'help_text' => 'هذه القيمة تخص الاعتماد للقطيع ولا تساوي بالضرورة حد وزن التلقيح في 6.5.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 31,
                    'report_category' => 'replacement_approval_threshold',
                    'target_entity' => 'female_production_herd_approval',
                    'options' => [],
                ],
                [
                    'seed_key' => 'replacement_rules.male_minimum_approval_age_days',
                    'title' => 'ما الحد الأدنى لعمر اعتماد الذكر داخل القطيع الإنتاجي، بالأيام؟',
                    'help_text' => 'هذه القيمة مستقلة عن عمر بدء استخدام الذكر في التلقيح في 6.5.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 32,
                    'report_category' => 'replacement_approval_threshold',
                    'target_entity' => 'male_production_herd_approval',
                    'options' => [],
                ],
                [
                    'seed_key' => 'replacement_rules.male_minimum_approval_weight',
                    'title' => 'ما الحد الأدنى لوزن اعتماد الذكر داخل القطيع الإنتاجي؟',
                    'help_text' => 'هذه القيمة تخص الاعتماد للقطيع ولا تساوي بالضرورة حد وزن استخدام الذكر في التلقيح.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 33,
                    'report_category' => 'replacement_approval_threshold',
                    'target_entity' => 'male_production_herd_approval',
                    'options' => [],
                ],
                [
                    'seed_key' => 'replacement_rules.parent_performance_influence_model',
                    'title' => 'كيف يجب أن يؤثر أداء الأب والأم والبطن على قرار ترشيح أو اعتماد حيوان للإحلال؟',
                    'help_text' => 'المصدر يضع مدى تأثير أداء الأب والأم على الاختيار كنقطة تحتاج مراجعة. المؤشرات نفسها تأتي من التقارير والتحليلات ولا تنسخ كحقول يدوية داخل المرشح.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 34,
                    'report_category' => 'replacement_selection_rule',
                    'target_entity' => 'replacement_candidate',
                    'options' => [
                        ['label' => 'معلومات مساعدة للقرار فقط دون شرط قبول أو رفض', 'value' => 'advisory_context_only'],
                        ['label' => 'يمكن تعريفها كمعايير اختيار مؤثرة لكنها ليست مانعًا منفردًا', 'value' => 'selection_criteria_not_standalone_block'],
                        ['label' => 'يمكن أن تحتوي القاعدة على حدود تجعل الأداء العائلي شرطًا إلزاميًا عند اعتمادها', 'value' => 'configurable_mandatory_family_performance_rule'],
                    ],
                ],
                [
                    'seed_key' => 'replacement_rules.need_integration_model',
                    'title' => 'كيف يجب أن يدخل احتياج المزرعة للإحلال في قرار الاحتفاظ بالمرشح أو اعتماده؟',
                    'help_text' => 'المصدر يوضح أن وجود حيوان جيد وحده لا يعني وجود حاجة فعلية لإحلاله إذا كانت أعداد الذكور أو الإناث كافية.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 35,
                    'report_category' => 'replacement_need_rule',
                    'target_entity' => 'replacement_planning',
                    'options' => [
                        ['label' => 'الاحتياج معلومة تخطيطية فقط ولا يمنع الترشيح أو الاعتماد', 'value' => 'need_is_advisory_only'],
                        ['label' => 'يؤثر الاحتياج في أولوية الترشيح والاعتماد لكن يمكن تجاوزه وفق السياسة العامة', 'value' => 'need_affects_priority_with_governed_override'],
                        ['label' => 'لا يتم الاعتماد النهائي إلا إذا كان هناك احتياج فعلي للدور الإنتاجي', 'value' => 'approval_requires_replacement_need'],
                    ],
                ],
                [
                    'seed_key' => 'replacement_rules.approval_enforcement_model',
                    'title' => 'كيف يجب تطبيق نتيجة فحص شروط الاعتماد النهائي قبل تنفيذ حدث الاعتماد في 4.11؟',
                    'help_text' => 'هذا السؤال يحدد علاقة القواعد بقرار الاعتماد. صلاحيات التجاوز وتوثيقه نفسها تظل تحت السياسة العامة في 6.2.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 36,
                    'report_category' => 'replacement_approval_rule',
                    'target_entity' => 'production_herd_approval',
                    'options' => [
                        ['label' => 'يجب اجتياز جميع شروط الاعتماد المعرّفة كإلزامية قبل الاعتماد', 'value' => 'all_mandatory_criteria_must_pass'],
                        ['label' => 'يمكن تصنيف شروط الاعتماد إلى Hard / Warning وفق 6.2، مع Override موثق للقواعد القابلة للتجاوز', 'value' => 'criteria_use_general_enforcement_and_override_policy'],
                        ['label' => 'يعرض النظام نتيجة الفحص كمعلومة فقط ويظل قرار الاعتماد بشريًا دون منع آلي', 'value' => 'advisory_rule_check_explicit_human_approval'],
                    ],
                ],
            ];

            app(QuestionSeederSyncService::class)->sync(
                mainSectionName: 'الإعدادات وقواعد التشغيل',
                sectionName: 'قواعد النمو والوزن والفرز والإحلال',
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
            ['growth_rules.weight_schedule_components', 'growth_rules.first_postweaning_weight_delay_days', QuestionDependencyOperator::CONTAINS, 'first_postweaning_delay'],
            ['growth_rules.weight_schedule_components', 'growth_rules.periodic_weight_interval_days', QuestionDependencyOperator::CONTAINS, 'periodic_interval'],
            ['growth_rules.weight_schedule_components', 'growth_rules.weight_checkpoint_ages', QuestionDependencyOperator::CONTAINS, 'age_checkpoints'],
            ['growth_rules.deviation_threshold_enabled', 'growth_rules.allowed_weight_deviation_percentage', QuestionDependencyOperator::EQUALS, '1'],
            ['sorting_rules.stage_definition_model', 'sorting_rules.stage_definitions', QuestionDependencyOperator::EQUALS, 'configurable_ordered_stages'],
            ['sorting_rules.reevaluation_schedule_model', 'sorting_rules.reevaluation_default_interval_days', QuestionDependencyOperator::EQUALS, 'configured_default_interval_editable'],
            ['replacement_rules.candidate_minimum_age_enabled', 'replacement_rules.candidate_minimum_age_days', QuestionDependencyOperator::EQUALS, '1'],
            ['replacement_rules.approval_age_weight_source_model', 'replacement_rules.approval_threshold_scope', QuestionDependencyOperator::EQUALS, 'independent_approval_thresholds'],
            ['replacement_rules.approval_threshold_scope', 'replacement_rules.shared_minimum_approval_age_days', QuestionDependencyOperator::EQUALS, 'shared_thresholds_by_sex'],
            ['replacement_rules.approval_threshold_scope', 'replacement_rules.shared_minimum_approval_weight', QuestionDependencyOperator::EQUALS, 'shared_thresholds_by_sex'],
            ['replacement_rules.approval_threshold_scope', 'replacement_rules.female_minimum_approval_age_days', QuestionDependencyOperator::EQUALS, 'sex_specific_thresholds'],
            ['replacement_rules.approval_threshold_scope', 'replacement_rules.female_minimum_approval_weight', QuestionDependencyOperator::EQUALS, 'sex_specific_thresholds'],
            ['replacement_rules.approval_threshold_scope', 'replacement_rules.male_minimum_approval_age_days', QuestionDependencyOperator::EQUALS, 'sex_specific_thresholds'],
            ['replacement_rules.approval_threshold_scope', 'replacement_rules.male_minimum_approval_weight', QuestionDependencyOperator::EQUALS, 'sex_specific_thresholds'],
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
            ->where('name', 'قواعد النمو والوزن والفرز والإحلال')
            ->first();
    }
}
