<?php

namespace Database\Seeders\Questions\Reports;

use App\Enums\Questionnaire\QuestionType;
use App\Services\Questionnaire\QuestionSeederSyncService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FarmKeyPerformanceIndicatorsQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $questions = [
                [
                    'seed_key' => 'farm_kpi.categories',
                    'title' => 'ما مجموعات المؤشرات الرئيسية التي يجب أن تظهر ضمن KPIs المزرعة؟',
                    'help_text' => 'المصدر يقترح مجموعة صغيرة من المؤشرات الرئيسية موزعة على الإنتاج والنمو والقطيع والصحة والتشغيل. هذا السؤال يحدد نطاق الـKPI المختصر، بينما التفاصيل الكاملة لكل مجال تبقى في تقاريره المتخصصة.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 1,
                    'report_category' => 'kpi_scope',
                    'target_entity' => 'farm_kpi_dashboard',
                    'options' => [
                        ['label' => 'الإنتاج', 'value' => 'production'],
                        ['label' => 'النمو', 'value' => 'growth'],
                        ['label' => 'القطيع', 'value' => 'herd'],
                        ['label' => 'الصحة', 'value' => 'health'],
                        ['label' => 'التشغيل', 'value' => 'operations'],
                    ],
                ],
                [
                    'seed_key' => 'farm_kpi.production_metrics',
                    'title' => 'ما مؤشرات الإنتاج التي يجب أن تدخل ضمن مجموعة KPIs الرئيسية؟',
                    'help_text' => 'هذه القائمة مأخوذة مباشرة من التصور الوظيفي، مع بقاء طريقة الحساب التفصيلية لكل مؤشر في تعريفه التحليلي المعتمد وعدم إنشاء معادلة بديلة داخل شاشة الـKPI.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 2,
                    'report_category' => 'kpi_metric',
                    'target_entity' => 'production_kpi',
                    'options' => [
                        ['label' => 'نسبة الحمل', 'value' => 'pregnancy_rate'],
                        ['label' => 'متوسط عدد المواليد لكل ولادة', 'value' => 'average_total_born_per_birth'],
                        ['label' => 'متوسط الأحياء عند الولادة', 'value' => 'average_live_born_per_birth'],
                        ['label' => 'متوسط عدد المفطومين لكل بطن', 'value' => 'average_weaned_per_litter'],
                        ['label' => 'نسبة البقاء حتى الفطام', 'value' => 'survival_to_weaning_rate'],
                    ],
                ],
                [
                    'seed_key' => 'farm_kpi.growth_metrics',
                    'title' => 'ما مؤشرات النمو التي يجب أن تدخل ضمن مجموعة KPIs الرئيسية؟',
                    'help_text' => 'المصدر يحدد هذه المؤشرات كمقترحات أساسية لمتابعة النمو. الأوزان الفعلية مصدرها 4.3، والتحليل التفصيلي للنمو والتسمين في 5.5.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 3,
                    'report_category' => 'kpi_metric',
                    'target_entity' => 'growth_kpi',
                    'options' => [
                        ['label' => 'متوسط وزن الفطام', 'value' => 'average_weaning_weight'],
                        ['label' => 'متوسط معدل النمو اليومي', 'value' => 'average_daily_growth_rate'],
                        ['label' => 'متوسط الوزن عند مراحل الفرز', 'value' => 'average_weight_at_sorting_stages'],
                        ['label' => 'نسبة الأرانب التي تصل للوزن المستهدف', 'value' => 'target_weight_achievement_rate'],
                    ],
                ],
                [
                    'seed_key' => 'farm_kpi.herd_metrics',
                    'title' => 'ما مؤشرات القطيع التي يجب أن تدخل ضمن مجموعة KPIs الرئيسية؟',
                    'help_text' => 'المصدر يقترح هذه المؤشرات لمتابعة تكوين القطيع والإحلال. التعريفات والقواعد التي تحدد من هو منتج أو غير منتج أو مرشح للإحلال يجب أن تأتي من البيانات والـWorkflow والـSettings المعتمدة، لا من تعريف جديد داخل هذا التقرير.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 4,
                    'report_category' => 'kpi_metric',
                    'target_entity' => 'herd_kpi',
                    'options' => [
                        ['label' => 'عدد الإناث الإنتاجية', 'value' => 'productive_female_count'],
                        ['label' => 'عدد الذكور الإنتاجية', 'value' => 'productive_male_count'],
                        ['label' => 'نسبة الإحلال', 'value' => 'replacement_rate'],
                        ['label' => 'عدد مرشحي الإحلال', 'value' => 'replacement_candidate_count'],
                        ['label' => 'نسبة الحيوانات غير المنتجة', 'value' => 'nonproductive_animal_rate'],
                    ],
                ],
                [
                    'seed_key' => 'farm_kpi.health_metrics',
                    'title' => 'ما مؤشرات الصحة التي يجب أن تدخل ضمن مجموعة KPIs الرئيسية؟',
                    'help_text' => 'المصدر يذكر النفوق حسب المستوى العام والمرحلة، والإجهاض، والحالات الصحية والعزل. التقرير المختصر لا يستبدل التحليل التفصيلي في 5.6 ولا قواعد اكتشاف الحالات غير الطبيعية في 5.13.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 5,
                    'report_category' => 'kpi_metric',
                    'target_entity' => 'health_kpi',
                    'options' => [
                        ['label' => 'نسبة النفوق', 'value' => 'mortality_rate'],
                        ['label' => 'النفوق حسب المرحلة', 'value' => 'mortality_by_stage'],
                        ['label' => 'حالات الإجهاض / فقد الحمل المسجلة', 'value' => 'pregnancy_loss_or_abortion_count'],
                        ['label' => 'الحالات الصحية والعزل', 'value' => 'health_and_isolation_cases'],
                    ],
                ],
                [
                    'seed_key' => 'farm_kpi.operations_metrics',
                    'title' => 'ما مؤشرات التشغيل التي يجب أن تدخل ضمن مجموعة KPIs الرئيسية؟',
                    'help_text' => 'هذه المؤشرات تربط جودة التنفيذ اليومي باستخدام السعة. تنفيذ المهام مصدره 4.17، وتحليلها في 5.10، والإشغال والعجز المتوقع في 5.9.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 6,
                    'report_category' => 'kpi_metric',
                    'target_entity' => 'operations_kpi',
                    'options' => [
                        ['label' => 'نسبة المهام المنفذة في موعدها', 'value' => 'on_time_task_completion_rate'],
                        ['label' => 'عدد المهام المتأخرة', 'value' => 'overdue_task_count'],
                        ['label' => 'نسبة إشغال الأقفاص', 'value' => 'cage_occupancy_rate'],
                        ['label' => 'العجز المتوقع في السعة', 'value' => 'projected_capacity_shortage'],
                    ],
                ],
                [
                    'seed_key' => 'farm_kpi.calculation_source_model',
                    'title' => 'كيف يجب أن يحصل KPI الرئيسي على قيمته دون إنشاء منطق حساب موازٍ للتقرير المتخصص؟',
                    'help_text' => 'نفس المؤشر قد يظهر في تقرير متخصص وفي شاشة KPIs. وجود معادلتين منفصلتين لنفس المؤشر قد ينتج أرقامًا متعارضة، لذلك يجب حسم مصدر القيمة.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 7,
                    'report_category' => 'derivation_rule',
                    'target_entity' => 'farm_kpi',
                    'options' => [
                        ['label' => 'يستخدم الـKPI نفس تعريف وحساب المؤشر الموجود في التقرير المتخصص', 'value' => 'reuse_domain_metric_definition'],
                        ['label' => 'يكون لكل KPI تعريف حساب مستقل حتى لو كان له مؤشر مشابه في تقرير متخصص', 'value' => 'independent_kpi_formula'],
                        ['label' => 'الأصل هو إعادة استخدام المؤشر المتخصص، مع السماح بتعريف مستقل فقط لمؤشر لا يوجد له مصدر تحليلي سابق', 'value' => 'reuse_existing_allow_new_only_when_missing'],
                    ],
                ],
                [
                    'seed_key' => 'farm_kpi.unresolved_formula_policy',
                    'title' => 'كيف يجب التعامل مع KPI مطلوب وظيفيًا لكن معادلته الدقيقة أو مقامه ما زالا يحتاجان اعتمادًا؟',
                    'help_text' => 'التصور نفسه يذكر أن المعادلات العلمية الدقيقة لبعض المؤشرات ونطاقاتها الطبيعية تحتاج مراجعة. لا يجب تحويل مثال أو افتراض إلى معادلة نهائية بصمت.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 8,
                    'report_category' => 'definition_rule',
                    'target_entity' => 'farm_kpi_definition',
                    'options' => [
                        ['label' => 'يبقى المؤشر معتمدًا كاحتياج لكن لا يحسب حتى تعتمد معادلته بوضوح', 'value' => 'required_but_not_calculated_until_formula_approved'],
                        ['label' => 'يعرض كمؤشر قيد التعريف مع توضيح أن الحساب لم يعتمد بعد', 'value' => 'show_as_pending_definition'],
                        ['label' => 'يسمح باستخدام معادلة مؤقتة موثقة بوضوح إلى أن تعتمد المعادلة النهائية', 'value' => 'documented_provisional_formula'],
                    ],
                ],
                [
                    'seed_key' => 'farm_kpi.period_context_model',
                    'title' => 'كيف يجب تحديد الفترة الزمنية المستخدمة في عرض KPIs التي تعتمد على فترة؟',
                    'help_text' => 'بعض المؤشرات Current State مثل عدد الحيوانات، وبعضها Rate أو Average يحتاج فترة حساب. المصدر يذكر أن الفترة المناسبة لكل مؤشر ما زالت نقطة مراجعة، بينما خصائص اختيار النطاق الزمني العامة تعالج في 5.16.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 9,
                    'report_category' => 'period_rule',
                    'target_entity' => 'farm_kpi_period',
                    'options' => [
                        ['label' => 'كل KPI يستخدم الفترة المعتمدة الخاصة بتعريفه، مع إظهارها للمستخدم', 'value' => 'metric_specific_default_period'],
                        ['label' => 'كل KPIs ذات الطبيعة الزمنية تستخدم نطاق التقرير الذي يختاره المستخدم', 'value' => 'shared_selected_report_period'],
                        ['label' => 'فترة افتراضية خاصة بكل KPI مع إمكانية تطبيق نطاق زمني مختار عندما يكون ذلك صالحًا للمؤشر', 'value' => 'metric_default_with_compatible_selected_period'],
                    ],
                ],
                [
                    'seed_key' => 'farm_kpi.target_benchmark_presentation',
                    'title' => 'ما مقدار السياق الذي يجب أن يظهر مع قيمة KPI الرئيسية لمساعدة المستخدم على تفسيرها؟',
                    'help_text' => 'Targets وThresholds وBenchmarks القابلة للضبط تنتمي إلى Settings، والاتجاه والمقارنة التاريخية موجودان في 5.11. هذا السؤال يحسم ما الذي يستدعيه عرض الـKPI من هذه المصادر دون إعادة تعريفها.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 10,
                    'report_category' => 'presentation_rule',
                    'target_entity' => 'farm_kpi_dashboard',
                    'options' => [
                        ['label' => 'عرض القيمة الحالية فقط', 'value' => 'value_only'],
                        ['label' => 'القيمة + الهدف / المرجع المعتمد عند توفره', 'value' => 'value_with_target_or_benchmark'],
                        ['label' => 'القيمة + الهدف / المرجع + اتجاه أو تغير مختصر من 5.11 عند توفره', 'value' => 'value_target_and_trend_context'],
                    ],
                ],
                [
                    'seed_key' => 'farm_kpi.incomplete_data_handling',
                    'title' => 'كيف يجب أن يعرض النظام KPI عندما تكون البيانات اللازمة لحسابه ناقصة أو غير موثوقة؟',
                    'help_text' => '5.14 يكشف مشكلات جودة البيانات. الـKPI يجب ألا يقدم رقمًا دقيقًا ظاهريًا إذا كانت بياناته الأساسية غير كافية أو متعارضة.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 11,
                    'report_category' => 'data_quality_rule',
                    'target_entity' => 'farm_kpi',
                    'options' => [
                        ['label' => 'عدم حساب المؤشر وإظهار أنه غير متاح بسبب نقص / تعارض البيانات', 'value' => 'mark_unavailable_due_to_data_quality'],
                        ['label' => 'حساب المؤشر من البيانات المتاحة مع علامة واضحة بأنه جزئي', 'value' => 'calculate_partial_with_quality_warning'],
                        ['label' => 'إخفاء المؤشر بالكامل حتى تصبح البيانات كافية', 'value' => 'hide_until_data_is_sufficient'],
                    ],
                ],
                [
                    'seed_key' => 'farm_kpi.scope_model',
                    'title' => 'كيف يجب أن يتعامل عرض KPIs مع وجود أكثر من مزرعة ضمن نطاق المستخدم؟',
                    'help_text' => 'المزرعة هي نطاق تشغيلي مستقل، وقد تكون للمستخدم صلاحية على أكثر من مزرعة. هذا السؤال يخص عرض المؤشرات فقط ولا يحدد صلاحيات الوصول نفسها.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 12,
                    'report_category' => 'kpi_scope',
                    'target_entity' => 'farm_kpi_dashboard',
                    'options' => [
                        ['label' => 'عرض KPIs لمزرعة واحدة مختارة فقط', 'value' => 'single_selected_farm'],
                        ['label' => 'عرض مؤشرات مجمعة للمزارع المسموح بها مع إمكانية النزول لكل مزرعة', 'value' => 'aggregate_authorized_farms_with_drilldown'],
                        ['label' => 'دعم العرضين: إجمالي عام أو مزرعة محددة', 'value' => 'global_and_selected_farm_views'],
                    ],
                ],
                [
                    'seed_key' => 'farm_kpi.drilldown_model',
                    'title' => 'إلى أي مستوى يجب أن يستطيع المستخدم النزول من KPI الرئيسي لفهم الرقم؟',
                    'help_text' => 'الـKPI هو مدخل مختصر للمعلومة وليس بديلًا عن التقرير المتخصص. يجب حسم مدى القدرة على الرجوع إلى الحساب والبيانات الأصلية المفسرة له.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 13,
                    'report_category' => 'interaction_rule',
                    'target_entity' => 'farm_kpi_dashboard',
                    'options' => [
                        ['label' => 'عرض قيمة KPI فقط', 'value' => 'kpi_value_only'],
                        ['label' => 'فتح التقرير المتخصص الذي يحسب المؤشر', 'value' => 'drilldown_to_domain_report'],
                        ['label' => 'فتح التقرير المتخصص ثم الوصول إلى السجلات / الأحداث Canonical التي كونت المؤشر', 'value' => 'drilldown_to_domain_report_and_source_records'],
                    ],
                ],
            ];

            app(QuestionSeederSyncService::class)->sync(
                mainSectionName: 'التقارير والتحليلات والتنبيهات ومؤشرات الأداء',
                sectionName: 'المؤشرات الرئيسية للمزرعة KPIs',
                questions: $questions,
                prune: true,
                preserveAnswers: true,
            );
        });
    }
}
