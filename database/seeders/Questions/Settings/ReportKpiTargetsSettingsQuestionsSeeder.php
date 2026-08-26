<?php

namespace Database\Seeders\Questions\Settings;

use App\Enums\Questionnaire\QuestionDependencyOperator;
use App\Enums\Questionnaire\QuestionType;
use App\Models\QuestionnaireQuestion;
use App\Models\QuestionnaireSection;
use App\Services\Questionnaire\QuestionSeederSyncService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReportKpiTargetsSettingsQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $questions = [
                [
                    'seed_key' => 'report_kpi_settings.evaluation_reference_types',
                    'title' => 'ما أنواع القيم المرجعية التي يجب أن تدعمها إعدادات التقارير وKPIs للحكم على النتائج؟',
                    'help_text' => 'التقارير تحدد ما الذي نقيسه، بينما هذا القسم يحدد القيم التي تساعد على تفسير النتيجة. لا يعني اختيار أي نوع هنا إنشاء KPI جديد أو تغيير معادلته.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 1,
                    'report_category' => 'kpi_reference_rule',
                    'target_entity' => 'kpi_evaluation_reference',
                    'options' => [
                        ['label' => 'قيمة مستهدفة Target', 'value' => 'target_value'],
                        ['label' => 'نطاق طبيعي / مقبول Minimum–Maximum', 'value' => 'acceptable_range'],
                        ['label' => 'Benchmark / خط أساس للمقارنة', 'value' => 'benchmark'],
                        ['label' => 'حدود تصنيف النتيجة مثل ضمن المتوقع / يحتاج متابعة / خارج المقبول', 'value' => 'classification_thresholds'],
                        ['label' => 'فترة افتراضية لحساب المؤشر عند كونه زمنيًا', 'value' => 'default_period'],
                    ],
                ],
                [
                    'seed_key' => 'report_kpi_settings.reference_source_model',
                    'title' => 'إذا كان للمؤشر هدف أو حد تشغيلي معرف بالفعل في Settings أخرى، من أين يجب أن يحصل التقرير على القيمة المرجعية؟',
                    'help_text' => 'مثال: وزن مستهدف أو مدة أو حد نمو تم حسمه في قسم تشغيلي سابق. المطلوب منع وجود قيمة تشغيلية وقيمة تقريرية متعارضتين لنفس المفهوم.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 2,
                    'report_category' => 'kpi_reference_source_rule',
                    'target_entity' => 'kpi_evaluation_reference',
                    'options' => [
                        ['label' => 'إعادة استخدام القيمة التشغيلية نفسها متى كان المفهوم واحدًا، ولا تنشأ نسخة تقريرية مستقلة', 'value' => 'reuse_operational_setting_when_same_concept'],
                        ['label' => 'كل قيم الحكم على التقارير تعرف مستقلة داخل 6.13 حتى لو وُجد هدف تشغيلي مشابه', 'value' => 'report_specific_references_only'],
                        ['label' => 'نموذج هجين: يعاد استخدام القيم التشغيلية عند التطابق، وتضاف قيم تقريرية مستقلة فقط للمؤشرات التي لا يوجد لها مرجع سابق', 'value' => 'hybrid_reuse_and_report_specific'],
                    ],
                ],
                [
                    'seed_key' => 'report_kpi_settings.configured_reference_categories',
                    'title' => 'لأي مجموعات من KPIs يجب أن تسمح المزرعة بتعريف Targets أو Ranges أو Benchmarks أو فترات افتراضية؟',
                    'help_text' => 'المجموعات نفسها معرفة في 5.15. هذا السؤال يحدد فقط أين نحتاج قيمًا مرجعية قابلة للضبط.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 3,
                    'report_category' => 'kpi_reference_scope',
                    'target_entity' => 'farm_kpi_reference_settings',
                    'options' => [
                        ['label' => 'الإنتاج', 'value' => 'production'],
                        ['label' => 'النمو', 'value' => 'growth'],
                        ['label' => 'القطيع', 'value' => 'herd'],
                        ['label' => 'الصحة', 'value' => 'health'],
                        ['label' => 'التشغيل', 'value' => 'operations'],
                    ],
                ],
                [
                    'seed_key' => 'report_kpi_settings.production_reference_values',
                    'title' => 'ما القيم المرجعية المطلوبة لمؤشرات الإنتاج؟',
                    'help_text' => 'اذكر فقط القيم التي تستخدمها المزرعة فعليًا لكل مؤشر مناسب، مثل Target أو Range أو حدود التصنيف أو الفترة الافتراضية. مؤشرات الإنتاج نفسها معرفة في 5.15، ولا تعاد معادلاتها هنا.',
                    'type' => QuestionType::TEXTAREA,
                    'is_required' => true,
                    'sort_order' => 4,
                    'report_category' => 'production_kpi_reference_values',
                    'target_entity' => 'production_kpi_reference_settings',
                    'options' => [],
                ],
                [
                    'seed_key' => 'report_kpi_settings.growth_reference_values',
                    'title' => 'ما القيم المرجعية المطلوبة لمؤشرات النمو؟',
                    'help_text' => 'يمكن أن تشمل أهدافًا أو نطاقات أو فترات للمؤشرات المعتمدة مثل وزن الفطام ومعدل النمو والوصول للوزن المستهدف، مع إعادة استخدام أهداف 6.9 و6.10 عندما تكون هي نفس القيمة التشغيلية.',
                    'type' => QuestionType::TEXTAREA,
                    'is_required' => true,
                    'sort_order' => 5,
                    'report_category' => 'growth_kpi_reference_values',
                    'target_entity' => 'growth_kpi_reference_settings',
                    'options' => [],
                ],
                [
                    'seed_key' => 'report_kpi_settings.herd_reference_values',
                    'title' => 'ما القيم المرجعية المطلوبة لمؤشرات القطيع؟',
                    'help_text' => 'اذكر Targets أو Ranges أو الفترات التي تساعد على تفسير مؤشرات القطيع المعتمدة في 5.15 مثل الإحلال أو الحيوانات غير المنتجة، دون تحويل أعداد القطيع الحالية إلى قيم تدخل يدويًا.',
                    'type' => QuestionType::TEXTAREA,
                    'is_required' => true,
                    'sort_order' => 6,
                    'report_category' => 'herd_kpi_reference_values',
                    'target_entity' => 'herd_kpi_reference_settings',
                    'options' => [],
                ],
                [
                    'seed_key' => 'report_kpi_settings.health_reference_values',
                    'title' => 'ما القيم المرجعية المطلوبة لمؤشرات الصحة والنفوق والعزل؟',
                    'help_text' => 'المصدر يضع الحدود الطبيعية ونسب النفوق والفترة الزمنية المستخدمة في الحكم ضمن النقاط التي تحتاج مراجعة. حدود اكتشاف Alert فعلي تظل تحت قواعد 6.12 إذا كانت مختلفة عن حدود عرض KPI.',
                    'type' => QuestionType::TEXTAREA,
                    'is_required' => true,
                    'sort_order' => 7,
                    'report_category' => 'health_kpi_reference_values',
                    'target_entity' => 'health_kpi_reference_settings',
                    'options' => [],
                ],
                [
                    'seed_key' => 'report_kpi_settings.operations_reference_values',
                    'title' => 'ما القيم المرجعية المطلوبة لمؤشرات التشغيل؟',
                    'help_text' => 'يمكن أن تشمل أهداف تنفيذ المهام في موعدها، حدود التأخير أو الإشغال أو السعة عند الحاجة. القيم الفعلية تظل مشتقة من Workflow وReports، وهنا نسجل مراجع الحكم فقط.',
                    'type' => QuestionType::TEXTAREA,
                    'is_required' => true,
                    'sort_order' => 8,
                    'report_category' => 'operations_kpi_reference_values',
                    'target_entity' => 'operations_kpi_reference_settings',
                    'options' => [],
                ],
                [
                    'seed_key' => 'report_kpi_settings.metric_direction_model',
                    'title' => 'كيف يجب أن يعرف النظام اتجاه الحكم لكل KPI عند مقارنة القيمة بالهدف أو الحدود؟',
                    'help_text' => 'ليس كل مؤشر يعمل بنفس الاتجاه؛ ارتفاع نسبة الحمل قد يكون مرغوبًا، بينما ارتفاع النفوق غير مرغوب، وبعض المؤشرات لها نطاق مقبول. هذا Metadata للحكم وليس معادلة KPI جديدة.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 9,
                    'report_category' => 'kpi_evaluation_rule',
                    'target_entity' => 'kpi_evaluation_definition',
                    'options' => [
                        ['label' => 'يحدد لكل KPI صراحة: الأعلى أفضل / الأقل أفضل / الأفضل داخل نطاق / معلوماتي دون حكم', 'value' => 'explicit_direction_per_kpi'],
                        ['label' => 'يستنتج الاتجاه تلقائيًا من نوع المرجع والقيم المدخلة فقط', 'value' => 'derive_direction_from_reference_configuration'],
                        ['label' => 'لا يستخدم اتجاه حكم؛ تعرض القيمة والمرجع فقط دون تصنيف أفضل أو أسوأ', 'value' => 'no_directional_evaluation'],
                    ],
                ],
                [
                    'seed_key' => 'report_kpi_settings.status_classification_model',
                    'title' => 'ما مستوى تصنيف KPI المطلوب عند توفر Targets أو Ranges معتمدة؟',
                    'help_text' => 'هذا التصنيف يخص تفسير قيمة التقرير. لا يعني تلقائيًا إنشاء Alert؛ علاقة التصنيف بالتنبيه تحسم في سؤال مستقل داخل هذا القسم مع 6.12.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 10,
                    'report_category' => 'kpi_classification_rule',
                    'target_entity' => 'kpi_evaluation_status',
                    'options' => [
                        ['label' => 'بدون تصنيف لوني/حكمي؛ تعرض القيمة مع المرجع فقط', 'value' => 'reference_only_no_status'],
                        ['label' => 'تصنيف ثنائي: ضمن المقبول / خارج المقبول', 'value' => 'two_state_acceptable_or_outside'],
                        ['label' => 'تصنيف ثلاثي: ضمن المتوقع / يحتاج متابعة / خارج الحد المقبول', 'value' => 'three_state_expected_watch_action'],
                        ['label' => 'يختلف نموذج التصنيف حسب KPI', 'value' => 'classification_model_by_kpi'],
                    ],
                ],
                [
                    'seed_key' => 'report_kpi_settings.threshold_value_model',
                    'title' => 'كيف يجب تعريف حدود التصنيف عندما لا تكون مجرد Target مباشر؟',
                    'help_text' => 'المصدر يطلب مراجعة الحدود الطبيعية وحدود التنبيه لكل مؤشر. هذا السؤال يحسم طريقة التعبير عن حدود الحكم التقريرّي فقط.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 11,
                    'report_category' => 'kpi_threshold_rule',
                    'target_entity' => 'kpi_evaluation_threshold',
                    'options' => [
                        ['label' => 'قيم مطلقة Min / Max أو حدود مستقلة حسب KPI', 'value' => 'absolute_threshold_values'],
                        ['label' => 'نسبة انحراف مسموحة عن Target أو Benchmark', 'value' => 'percentage_deviation_from_reference'],
                        ['label' => 'يختار كل KPI الطريقة المناسبة له', 'value' => 'threshold_model_by_kpi'],
                    ],
                ],
                [
                    'seed_key' => 'report_kpi_settings.benchmark_sources',
                    'title' => 'ما مصادر الـBenchmark التي يجب أن يدعمها النظام عند استخدام Benchmark للمقارنة؟',
                    'help_text' => 'لا يفترض هذا السؤال اتصالًا بمصدر خارجي. القيم المرجعية العلمية أو المهنية لا تعتمد إلا إذا تم اعتمادها صراحة وإدخالها كمراجع موثقة، كما يمكن استخدام تاريخ المزرعة نفسها كخط أساس.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 12,
                    'report_category' => 'kpi_benchmark_rule',
                    'target_entity' => 'kpi_benchmark',
                    'options' => [
                        ['label' => 'تاريخ المزرعة نفسها / Baseline داخلي', 'value' => 'farm_historical_baseline'],
                        ['label' => 'قيمة مرجعية معتمدة يدويًا من مصدر مهني أو علمي موثق', 'value' => 'approved_manual_reference'],
                    ],
                ],
                [
                    'seed_key' => 'report_kpi_settings.historical_benchmark_period_model',
                    'title' => 'عند استخدام تاريخ المزرعة كـBenchmark، كيف يجب تحديد الفترة المرجعية للمقارنة؟',
                    'help_text' => 'المطلوب تحديد خط الأساس فقط. تحليل الاتجاه والمقارنات نفسه يبقى في 5.11.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 13,
                    'report_category' => 'kpi_benchmark_period_rule',
                    'target_entity' => 'kpi_benchmark',
                    'options' => [
                        ['label' => 'الفترة السابقة المماثلة مباشرة', 'value' => 'previous_comparable_period'],
                        ['label' => 'فترة Baseline ثابتة يحددها المستخدم لكل KPI أو مجموعة', 'value' => 'configured_fixed_baseline_period'],
                        ['label' => 'يدعم الطريقتين ويحدد كل KPI أساس المقارنة المستخدم', 'value' => 'support_previous_and_fixed_baselines'],
                    ],
                ],
                [
                    'seed_key' => 'report_kpi_settings.approved_reference_governance',
                    'title' => 'كيف يجب التعامل مع القيم المرجعية العلمية أو المهنية التي لم يعتمد مصدرها أو نطاقها بعد؟',
                    'help_text' => 'التصور يذكر أن المعادلات والحدود الطبيعية الدقيقة تحتاج مراجعة. لا يجب إدخال رقم افتراضي وكأنه Benchmark معتمد.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 14,
                    'report_category' => 'kpi_reference_governance',
                    'target_entity' => 'kpi_benchmark',
                    'options' => [
                        ['label' => 'لا تستخدم القيمة في الحكم حتى يعتمد مصدرها وقيمتها صراحة', 'value' => 'inactive_until_explicitly_approved'],
                        ['label' => 'يمكن حفظها كمرجع مقترح لكن لا تؤثر على التصنيف أو التنبيهات حتى اعتمادها', 'value' => 'store_as_proposed_reference_until_approved'],
                        ['label' => 'يسمح باستخدام قيمة مؤقتة موثقة بوضوح مع تمييزها بأنها غير نهائية', 'value' => 'documented_provisional_reference'],
                    ],
                ],
                [
                    'seed_key' => 'report_kpi_settings.default_period_definition_model',
                    'title' => 'كيف يجب تعريف الفترة الافتراضية للمؤشرات التي تحتاج فترة زمنية للحساب؟',
                    'help_text' => '5.15 يحدد كيف يتعامل عرض الـKPI مع الفترة، وهنا نحدد أين تخزن القيمة الافتراضية نفسها. مؤشرات Current State لا تحتاج فترة حساب مصطنعة.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 15,
                    'report_category' => 'kpi_period_setting_rule',
                    'target_entity' => 'kpi_default_period',
                    'options' => [
                        ['label' => 'فترة افتراضية مستقلة لكل KPI زمني', 'value' => 'default_period_per_kpi'],
                        ['label' => 'فترة افتراضية لكل مجموعة KPIs: إنتاج / نمو / قطيع / صحة / تشغيل', 'value' => 'default_period_by_kpi_category'],
                        ['label' => 'فترة افتراضية عامة مشتركة مع استثناءات محددة عند الحاجة', 'value' => 'global_default_period_with_exceptions'],
                    ],
                ],
                [
                    'seed_key' => 'report_kpi_settings.supported_period_presets',
                    'title' => 'ما الفترات القياسية التي يجب أن تكون متاحة كمرجع افتراضي أو نطاق مراجعة للتقارير وKPIs؟',
                    'help_text' => 'التصور يذكر المتابعة اليومية والأسبوعية والشهرية، بينما 5.16 يدعم اختيار فترة للتصفية. Current State يستخدم للمؤشرات اللحظية التي لا تعتمد على فترة.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 16,
                    'report_category' => 'report_period_setting',
                    'target_entity' => 'report_period',
                    'options' => [
                        ['label' => 'الحالة الحالية Current State', 'value' => 'current_state'],
                        ['label' => 'يومي', 'value' => 'daily'],
                        ['label' => 'أسبوعي', 'value' => 'weekly'],
                        ['label' => 'شهري', 'value' => 'monthly'],
                        ['label' => 'نطاق تاريخ مخصص عند دعم التقرير لذلك', 'value' => 'custom_date_range'],
                    ],
                ],
                [
                    'seed_key' => 'report_kpi_settings.default_period_mapping',
                    'title' => 'ما الفترة الافتراضية المناسبة لكل KPI زمني أو مجموعة KPIs؟',
                    'help_text' => 'اذكر الـKPI أو المجموعة والفترة المناسبة لها. لا تضف فترة إلى مؤشر يمثل Current State فقط. اختيار المستخدم لفترة أخرى عند العرض يظل وفق النموذج المعتمد في 5.15 و5.16.',
                    'type' => QuestionType::TEXTAREA,
                    'is_required' => true,
                    'sort_order' => 17,
                    'report_category' => 'kpi_period_values',
                    'target_entity' => 'kpi_default_period',
                    'options' => [],
                ],
                [
                    'seed_key' => 'report_kpi_settings.historical_reference_interpretation_model',
                    'title' => 'عند عرض KPI لفترة تاريخية بعد أن تغير Target أو Range لاحقًا، أي مرجع يجب استخدامه للحكم على الفترة القديمة؟',
                    'help_text' => '6.1 يحسم Versioning وEffective Date وحماية التاريخ. هنا نحدد فقط أي نسخة من المرجع تستخدم عند تفسير تقرير تاريخي.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 18,
                    'report_category' => 'historical_kpi_reference_rule',
                    'target_entity' => 'kpi_evaluation_reference',
                    'options' => [
                        ['label' => 'استخدام Target / Range الذي كان فعالًا خلال الفترة التاريخية', 'value' => 'use_reference_effective_during_report_period'],
                        ['label' => 'إعادة تقييم التاريخ دائمًا مقابل المرجع الحالي', 'value' => 'reevaluate_history_against_current_reference'],
                        ['label' => 'إظهار الحكم التاريخي بمرجعه وقتها مع إمكانية مقارنة إضافية بالمرجع الحالي', 'value' => 'historical_reference_with_optional_current_comparison'],
                    ],
                ],
                [
                    'seed_key' => 'report_kpi_settings.segmentation_enabled',
                    'title' => 'هل يجب أن تسمح بعض Targets أو Ranges بالاختلاف حسب خصائص الحيوان أو السياق التشغيلي، بخلاف نطاق Settings العام في 6.1؟',
                    'help_text' => 'مثل اختلاف هدف أو نطاق حسب السلالة أو الجنس أو المرحلة. Farm/Barn/Profile Scope نفسه لا يعاد تعريفه هنا لأنه محسوم معماريًا في 6.1.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 19,
                    'report_category' => 'kpi_reference_segmentation_rule',
                    'target_entity' => 'kpi_evaluation_reference',
                    'options' => [],
                ],
                [
                    'seed_key' => 'report_kpi_settings.segmentation_dimensions',
                    'title' => 'ما الأبعاد التي يجب أن تستطيع القيم المرجعية الاختلاف حسبها عند الحاجة؟',
                    'help_text' => 'المصدر يطلب مؤشرات للذكور والإناث ويهتم بالمرحلة والسلالة وأغراض التشغيل. اختر فقط الأبعاد التي تحتاج اختلافًا حقيقيًا في Targets أو Ranges.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 20,
                    'report_category' => 'kpi_reference_segmentation_rule',
                    'target_entity' => 'kpi_evaluation_reference',
                    'options' => [
                        ['label' => 'السلالة', 'value' => 'breed'],
                        ['label' => 'الجنس', 'value' => 'sex'],
                        ['label' => 'المرحلة التشغيلية / الإنتاجية', 'value' => 'operational_stage'],
                        ['label' => 'الغرض / الدور الإنتاجي', 'value' => 'production_purpose_or_role'],
                    ],
                ],
                [
                    'seed_key' => 'report_kpi_settings.segmented_reference_resolution_model',
                    'title' => 'إذا انطبق أكثر من Target أو Range مخصص على نفس الحالة، كيف يجب اختيار المرجع الفعال؟',
                    'help_text' => 'هذا السؤال يخص تعارض أبعاد التخصيص مثل سلالة + جنس + مرحلة، وليس تعارض Farm/Barn/Profile الذي تحكمه Architecture 6.1.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 21,
                    'report_category' => 'kpi_reference_resolution_rule',
                    'target_entity' => 'kpi_evaluation_reference',
                    'options' => [
                        ['label' => 'المرجع الأكثر تخصيصًا الذي يطابق أكبر عدد من الأبعاد هو المستخدم', 'value' => 'most_specific_matching_reference_wins'],
                        ['label' => 'يحدد لكل KPI ترتيب أولوية واضح لأبعاد التخصيص', 'value' => 'explicit_dimension_precedence_per_kpi'],
                        ['label' => 'لا يسمح بتعريف مراجع متداخلة قد تنطبق في نفس الوقت', 'value' => 'disallow_overlapping_segmented_references'],
                    ],
                ],
                [
                    'seed_key' => 'report_kpi_settings.alert_threshold_relationship_model',
                    'title' => 'ما العلاقة بين حدود تصنيف KPI في التقارير وحدود إنشاء Alert في 6.12؟',
                    'help_text' => 'قد يكون مجرد خروج KPI عن Target كافيًا للتنبيه، وقد تحتاج المزرعة Alert Threshold أشد أو مختلفًا. المطلوب منع الخلط بين لون/تصنيف التقرير وبين شرط إنشاء التنبيه.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 22,
                    'report_category' => 'kpi_alert_integration_rule',
                    'target_entity' => 'kpi_alert_threshold',
                    'options' => [
                        ['label' => 'تستخدم نفس حدود KPI كشرط Alert عندما يكون التنبيه مفعلًا لهذا المؤشر', 'value' => 'reuse_kpi_thresholds_for_alerts'],
                        ['label' => 'حدود Alert مستقلة دائمًا عن Targets / Ranges الخاصة بعرض KPI', 'value' => 'separate_alert_thresholds'],
                        ['label' => 'يحدد السلوك لكل KPI: إعادة استخدام الحدود أو Alert Threshold مستقل', 'value' => 'relationship_configurable_per_kpi'],
                    ],
                ],
                [
                    'seed_key' => 'report_kpi_settings.missing_reference_behavior',
                    'title' => 'كيف يجب عرض KPI إذا كانت معادلته معتمدة لكن لم يتم بعد تحديد Target أو Range أو Benchmark له؟',
                    'help_text' => 'عدم وجود مرجع للحكم لا يعني أن القيمة الفعلية غير قابلة للحساب. يجب الفصل بين «لا توجد بيانات» وبين «لا يوجد Target معتمد».',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 23,
                    'report_category' => 'kpi_reference_fallback_rule',
                    'target_entity' => 'farm_kpi',
                    'options' => [
                        ['label' => 'عرض قيمة KPI دون تصنيف مع توضيح أن المرجع غير مضبوط', 'value' => 'show_value_without_classification_mark_reference_unconfigured'],
                        ['label' => 'عرض القيمة والاتجاه التاريخي فقط عند توفره، دون حكم جيد/سيئ', 'value' => 'show_value_and_trend_without_reference_judgment'],
                        ['label' => 'إخفاء KPI من العرض الرئيسي حتى يتم تحديد مرجع الحكم', 'value' => 'hide_from_primary_view_until_reference_configured'],
                    ],
                ],
                [
                    'seed_key' => 'report_kpi_settings.reference_completeness_review_model',
                    'title' => 'كيف يجب التعامل مع KPI له Target أو Range لكن بعض مكونات الحكم المطلوبة ما زالت غير محددة؟',
                    'help_text' => 'مثال: يوجد Target لكن لا يوجد حد «يحتاج متابعة»، أو توجد Range بلا فترة حساب معتمدة. المطلوب ألا يكمل النظام القيم الناقصة بافتراضات غير موثقة.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 24,
                    'report_category' => 'kpi_reference_validation_rule',
                    'target_entity' => 'kpi_evaluation_reference',
                    'options' => [
                        ['label' => 'يستخدم فقط الأجزاء المعتمدة ويعطل أي تصنيف يحتاج قيمة مفقودة', 'value' => 'use_approved_parts_disable_incomplete_classification'],
                        ['label' => 'يعتبر إعداد المرجع غير مكتمل ولا يستخدم أي جزء منه حتى تكتمل القيم المطلوبة', 'value' => 'reference_inactive_until_complete'],
                        ['label' => 'يسمح بحفظ إعداد غير مكتمل كمسودة، ولا يصبح فعالًا إلا بعد اعتماده كاملًا', 'value' => 'draft_until_complete_and_approved'],
                    ],
                ],
            ];

            app(QuestionSeederSyncService::class)->sync(
                mainSectionName: 'الإعدادات وقواعد التشغيل',
                sectionName: 'إعدادات التقارير وKPIs والأهداف',
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
            ['report_kpi_settings.configured_reference_categories', 'report_kpi_settings.production_reference_values', QuestionDependencyOperator::CONTAINS, 'production'],
            ['report_kpi_settings.configured_reference_categories', 'report_kpi_settings.growth_reference_values', QuestionDependencyOperator::CONTAINS, 'growth'],
            ['report_kpi_settings.configured_reference_categories', 'report_kpi_settings.herd_reference_values', QuestionDependencyOperator::CONTAINS, 'herd'],
            ['report_kpi_settings.configured_reference_categories', 'report_kpi_settings.health_reference_values', QuestionDependencyOperator::CONTAINS, 'health'],
            ['report_kpi_settings.configured_reference_categories', 'report_kpi_settings.operations_reference_values', QuestionDependencyOperator::CONTAINS, 'operations'],
            ['report_kpi_settings.evaluation_reference_types', 'report_kpi_settings.benchmark_sources', QuestionDependencyOperator::CONTAINS, 'benchmark'],
            ['report_kpi_settings.benchmark_sources', 'report_kpi_settings.historical_benchmark_period_model', QuestionDependencyOperator::CONTAINS, 'farm_historical_baseline'],
            ['report_kpi_settings.benchmark_sources', 'report_kpi_settings.approved_reference_governance', QuestionDependencyOperator::CONTAINS, 'approved_manual_reference'],
            ['report_kpi_settings.segmentation_enabled', 'report_kpi_settings.segmentation_dimensions', QuestionDependencyOperator::EQUALS, '1'],
            ['report_kpi_settings.segmentation_enabled', 'report_kpi_settings.segmented_reference_resolution_model', QuestionDependencyOperator::EQUALS, '1'],
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
            ->where('name', 'إعدادات التقارير وKPIs والأهداف')
            ->first();
    }
}
