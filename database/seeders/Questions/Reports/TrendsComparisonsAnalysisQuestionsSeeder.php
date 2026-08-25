<?php

namespace Database\Seeders\Questions\Reports;

use App\Enums\Questionnaire\QuestionType;
use App\Services\Questionnaire\QuestionSeederSyncService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TrendsComparisonsAnalysisQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $questions = [
                [
                    'seed_key' => 'trends_comparisons_report.primary_views',
                    'title' => 'ما العروض الرئيسية التي يجب أن يوفرها قسم الاتجاهات والمقارنات والتحليل عبر الزمن؟',
                    'help_text' => 'هذا القسم Capability تحليلية تجمع المقارنة الزمنية والمقارنة بين أجزاء المزرعة، وتعتمد على المؤشرات المحسوبة أصلًا في تقارير 5.2 إلى 5.10 دون إعادة تعريف طريقة حساب كل مؤشر.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 1,
                    'report_category' => 'report_scope',
                    'target_entity' => 'trends_comparisons_report',
                    'options' => [
                        ['label' => 'اتجاه المؤشر عبر عدة فترات متتالية', 'value' => 'multi_period_trend'],
                        ['label' => 'مقارنة فترة بفترة سابقة', 'value' => 'period_over_period_comparison'],
                        ['label' => 'مقارنة دورة إنتاجية بأخرى', 'value' => 'reproductive_cycle_comparison'],
                        ['label' => 'مقارنة نفس المؤشر بين أجزاء المزرعة أو المجموعات', 'value' => 'spatial_or_group_comparison'],
                        ['label' => 'مقارنة تجمع البعد الزمني مع الجزء / المجموعة المختارة', 'value' => 'time_and_unit_combined_comparison'],
                    ],
                ],
                [
                    'seed_key' => 'trends_comparisons_report.supported_domains',
                    'title' => 'أي مجالات التقارير يجب أن تكون قابلة للاستخدام داخل الاتجاهات والمقارنات؟',
                    'help_text' => 'المطلوب تحديد المجالات التي يمكن أخذ مؤشراتها المحسوبة وعرضها بصورة مقارنة، وليس إنشاء مؤشرات جديدة داخل هذا القسم.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 2,
                    'report_category' => 'analysis_scope',
                    'target_entity' => 'trends_comparisons_report',
                    'options' => [
                        ['label' => 'القطيع والجاهزية وحركة القطيع', 'value' => 'herd_readiness_and_movement'],
                        ['label' => 'الخصوبة والتلقيح والحمل', 'value' => 'fertility_mating_and_pregnancy'],
                        ['label' => 'الولادة والرضاعة والفطام', 'value' => 'birth_lactation_and_weaning'],
                        ['label' => 'النمو والأوزان والتسمين', 'value' => 'growth_weight_and_fattening'],
                        ['label' => 'الصحة والنفوق والعزل', 'value' => 'health_mortality_and_isolation'],
                        ['label' => 'أداء الحيوانات الإنتاجية', 'value' => 'productive_animal_performance'],
                        ['label' => 'النسب والإحلال', 'value' => 'pedigree_and_replacement'],
                        ['label' => 'الإشغال والسعة وتشغيل مواقع الإيواء', 'value' => 'housing_occupancy_capacity_and_operations'],
                        ['label' => 'الأداء التشغيلي وتنفيذ المهام', 'value' => 'operational_task_performance'],
                    ],
                ],
                [
                    'seed_key' => 'trends_comparisons_report.temporal_comparison_modes',
                    'title' => 'ما أنماط المقارنة الزمنية القياسية التي يجب أن يدعمها النظام؟',
                    'help_text' => 'المصدر يذكر صراحة المقارنة بين الشهر الحالي والسابق، وآخر 3 أشهر، وآخر 6 أشهر، وسنة بسنة، ودورة إنتاجية بأخرى. اختيار نطاق زمني حر وخصائص الفلترة العامة سيعالج في 5.16.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 3,
                    'report_category' => 'comparison_scope',
                    'target_entity' => 'temporal_comparison',
                    'options' => [
                        ['label' => 'هذا الشهر مقارنة بالشهر السابق', 'value' => 'current_month_vs_previous_month'],
                        ['label' => 'اتجاه آخر 3 أشهر', 'value' => 'last_three_months'],
                        ['label' => 'اتجاه آخر 6 أشهر', 'value' => 'last_six_months'],
                        ['label' => 'سنة مقارنة بسنة', 'value' => 'year_over_year'],
                        ['label' => 'دورة إنتاجية مقارنة بدورة إنتاجية أخرى', 'value' => 'cycle_over_cycle'],
                    ],
                ],
                [
                    'seed_key' => 'trends_comparisons_report.period_change_display',
                    'title' => 'عند مقارنة قيمة مؤشر بين فترتين، ما النتائج التي يجب أن يعرضها النظام بجانب القيم الأصلية؟',
                    'help_text' => 'الهدف ألا يكتفي المستخدم برؤية قيم الفترات منفصلة إذا كان المطلوب فهم مقدار التحسن أو التراجع. هذا السؤال لا يغير طريقة حساب المؤشر الأساسي.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 4,
                    'report_category' => 'comparison_metric',
                    'target_entity' => 'temporal_comparison',
                    'options' => [
                        ['label' => 'قيمة المؤشر في كل فترة', 'value' => 'period_values'],
                        ['label' => 'الفرق العددي بين الفترتين', 'value' => 'absolute_difference'],
                        ['label' => 'نسبة التغير بين الفترتين عندما تكون قابلة للحساب', 'value' => 'percentage_change'],
                        ['label' => 'اتجاه الحركة: ارتفاع / انخفاض / بدون تغير مهم', 'value' => 'direction_of_change'],
                    ],
                ],
                [
                    'seed_key' => 'trends_comparisons_report.improvement_direction_model',
                    'title' => 'كيف يجب أن يميز النظام بين مجرد «ارتفاع / انخفاض» وبين «تحسن / تراجع» في المؤشر؟',
                    'help_text' => 'ارتفاع بعض المؤشرات مثل معدل البقاء قد يكون إيجابيًا، بينما ارتفاع مؤشرات مثل النفوق قد يكون سلبيًا. المصدر يستخدم مفهوم التحسن والتراجع، لذلك يجب ألا يفترض النظام أن الارتفاع جيد دائمًا.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 5,
                    'report_category' => 'interpretation_rule',
                    'target_entity' => 'trend_interpretation',
                    'options' => [
                        ['label' => 'يعرض النظام الارتفاع / الانخفاض فقط دون وصفه كتحسن أو تراجع', 'value' => 'show_numeric_direction_only'],
                        ['label' => 'يحدد التحسن / التراجع وفق دلالة كل مؤشر أو قواعده المعتمدة مع بقاء القيمة الأصلية ظاهرة', 'value' => 'interpret_using_metric_direction_rules'],
                        ['label' => 'يعرض الاتجاه الرقمي، ويستخدم التحسن / التراجع فقط للمؤشرات التي لديها دلالة معتمدة بوضوح', 'value' => 'interpret_only_when_metric_semantics_defined'],
                    ],
                ],
                [
                    'seed_key' => 'trends_comparisons_report.spatial_comparison_scopes',
                    'title' => 'ما المستويات التي يجب أن يدعم النظام المقارنة بينها عند تحليل نفس المؤشر داخل المزرعة؟',
                    'help_text' => 'المصدر يذكر المقارنة بين العنابر والبطاريات والمجموعات الإنتاجية، كما أن النظام مصمم لإدارة أكثر من مزرعة عند وجودها. المقارنة تستخدم نفس المؤشر ونفس نطاق التحليل دون تغيير السجلات الأصلية.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 6,
                    'report_category' => 'comparison_dimension',
                    'target_entity' => 'spatial_comparison',
                    'options' => [
                        ['label' => 'مزرعة مقابل مزرعة عند وجود أكثر من مزرعة', 'value' => 'farm'],
                        ['label' => 'عنبر مقابل عنبر', 'value' => 'barn'],
                        ['label' => 'بطارية مقابل بطارية', 'value' => 'battery'],
                        ['label' => 'مجموعة إنتاجية مقابل مجموعة إنتاجية', 'value' => 'production_group'],
                    ],
                ],
                [
                    'seed_key' => 'trends_comparisons_report.multi_unit_comparison_model',
                    'title' => 'كم عدد الأجزاء أو المجموعات التي يجب أن يستطيع المستخدم مقارنتها في نفس العرض؟',
                    'help_text' => 'المصدر يعرض أمثلة لمقارنة جزء بجزء لكنه يطرح المقارنة بين أجزاء المزرعة كقدرة عامة. المطلوب حسم ما إذا كان العرض ثنائيًا فقط أم يدعم عدة وحدات في وقت واحد.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 7,
                    'report_category' => 'comparison_model',
                    'target_entity' => 'spatial_comparison',
                    'options' => [
                        ['label' => 'مقارنة وحدتين فقط في كل مرة', 'value' => 'pairwise_only'],
                        ['label' => 'اختيار عدة وحدات من نفس المستوى وعرضها جنبًا إلى جنب', 'value' => 'multiple_same_level_units'],
                        ['label' => 'دعم المقارنة الثنائية والمتعددة حسب حاجة المستخدم', 'value' => 'pairwise_and_multi_unit'],
                    ],
                ],
                [
                    'seed_key' => 'trends_comparisons_report.normalized_comparison_model',
                    'title' => 'عند مقارنة أجزاء تختلف في حجم القطيع أو السعة، كيف يجب تجنب تضليل المستخدم بالأعداد الخام وحدها؟',
                    'help_text' => 'المصدر يستخدم أمثلة مثل نسبة النفوق ومتوسط النمو عند مقارنة أجزاء المزرعة، وهو ما يسمح بالمقارنة حتى مع اختلاف الحجم. الأعداد الخام يمكن أن تبقى كسياق إضافي.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 8,
                    'report_category' => 'calculation_rule',
                    'target_entity' => 'normalized_comparison',
                    'options' => [
                        ['label' => 'استخدام المعدلات / النسب / المتوسطات المناسبة للمقارنة مع إظهار العدد الخام كسياق عند الحاجة', 'value' => 'normalized_metrics_with_raw_context'],
                        ['label' => 'عرض القيم الخام والمؤشرات النسبية معًا دائمًا', 'value' => 'raw_and_normalized_side_by_side'],
                        ['label' => 'ترك نوع القيمة للمؤشر الأصلي دون قاعدة عامة للمقارنات', 'value' => 'use_source_metric_without_cross_comparison_rule'],
                    ],
                ],
                [
                    'seed_key' => 'trends_comparisons_report.cycle_comparison_scope',
                    'title' => 'عند اختيار «دورة إنتاجية بأخرى»، ما نطاق المقارنة الذي يجب أن يدعمه النظام؟',
                    'help_text' => 'المصدر يذكر المقارنة دورة بدورة دون تحديد هل المقصود دورات نفس الأنثى فقط أم مقارنة دورات مختلفة أوسع. المطلوب تحويل هذه النقطة غير المحسومة إلى قرار تصميم واضح.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 9,
                    'report_category' => 'comparison_scope',
                    'target_entity' => 'reproductive_cycle_comparison',
                    'options' => [
                        ['label' => 'مقارنة دورات نفس الأنثى فقط لمتابعة تغير أدائها', 'value' => 'same_female_cycles_only'],
                        ['label' => 'مقارنة دورات مختارة حتى لو كانت لحيوانات مختلفة عندما يكون المؤشر قابلًا للمقارنة', 'value' => 'selected_cycles_across_animals'],
                        ['label' => 'دعم النوعين: داخل نفس الأنثى أو بين دورات مختارة', 'value' => 'same_female_and_cross_animal_cycles'],
                    ],
                ],
                [
                    'seed_key' => 'trends_comparisons_report.comparability_policy',
                    'title' => 'ماذا يجب أن يحدث إذا لم تكن قيم المؤشر بين الفترات أو الوحدات مبنية على تعريف حسابي متوافق؟',
                    'help_text' => 'المقارنة تكون مضللة إذا تغير تعريف المؤشر أو مقام النسبة أو مصدر البيانات بين الطرفين. المطلوب حماية دلالة التقرير دون تعديل البيانات الأصلية أو محاولة اختراع قيمة بديلة.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 10,
                    'report_category' => 'data_quality_rule',
                    'target_entity' => 'comparison_result',
                    'options' => [
                        ['label' => 'منع المقارنة وبيان أن القيم غير قابلة للمقارنة', 'value' => 'block_incompatible_comparison'],
                        ['label' => 'السماح بالمقارنة مع تحذير واضح يشرح اختلاف التعريف أو مصدر البيانات', 'value' => 'allow_with_comparability_warning'],
                        ['label' => 'عرض القيم جنبًا إلى جنب دون حساب فرق أو اتجاه بينها', 'value' => 'side_by_side_without_change_calculation'],
                    ],
                ],
                [
                    'seed_key' => 'trends_comparisons_report.difference_highlighting',
                    'title' => 'ما أشكال إبراز الفروق المهمة التي يجب أن يوفرها عرض المقارنة دون تحويلها تلقائيًا إلى تنبيه؟',
                    'help_text' => 'المصدر يوضح أن المقارنات تساعد على كشف التحسن أو التراجع والفروق بين أجزاء المزرعة. إنشاء Alert أو تحديد Threshold التنبيه يعالج في 5.13 وSettings، أما هنا فالمطلوب أسلوب عرض التحليل فقط.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 11,
                    'report_category' => 'analysis_display',
                    'target_entity' => 'comparison_result',
                    'options' => [
                        ['label' => 'ترتيب الوحدات من الأعلى إلى الأدنى حسب المؤشر', 'value' => 'rank_units_by_metric'],
                        ['label' => 'إظهار أكبر تحسن خلال الفترة', 'value' => 'highlight_largest_improvement'],
                        ['label' => 'إظهار أكبر تراجع خلال الفترة', 'value' => 'highlight_largest_decline'],
                        ['label' => 'إظهار أكبر فرق بين الوحدات المقارنة', 'value' => 'highlight_largest_unit_gap'],
                        ['label' => 'عرض الفروق فقط دون تصنيفها كحالة تحتاج تنبيه', 'value' => 'show_differences_without_alert_classification'],
                    ],
                ],
                [
                    'seed_key' => 'trends_comparisons_report.drilldown_model',
                    'title' => 'إلى أي مستوى يجب أن يستطيع المستخدم النزول من نتيجة الاتجاه أو المقارنة إلى البيانات المفسرة لها؟',
                    'help_text' => 'الهدف أن يظل الفرق أو الاتجاه قابلًا للتفسير بالرجوع إلى التقرير المتخصص ثم الوحدات والحيوانات أو الأحداث والسجلات Canonical التي كونت المؤشر.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 12,
                    'report_category' => 'interaction_rule',
                    'target_entity' => 'trends_comparisons_report',
                    'options' => [
                        ['label' => 'عرض الاتجاه أو المقارنة فقط', 'value' => 'comparison_only'],
                        ['label' => 'النزول إلى التقرير المتخصص والقيم التي كونت كل طرف من المقارنة', 'value' => 'drilldown_to_source_domain_report'],
                        ['label' => 'النزول إلى التقرير المتخصص ثم إلى الحيوانات / الوحدات / الأحداث والسجلات Canonical المفسرة للنتيجة', 'value' => 'drilldown_to_underlying_entities_and_events'],
                    ],
                ],
            ];

            app(QuestionSeederSyncService::class)->sync(
                mainSectionName: 'التقارير والتحليلات والتنبيهات ومؤشرات الأداء',
                sectionName: 'الاتجاهات والمقارنات والتحليل عبر الزمن',
                questions: $questions,
                prune: true,
                preserveAnswers: true,
            );
        });
    }
}
