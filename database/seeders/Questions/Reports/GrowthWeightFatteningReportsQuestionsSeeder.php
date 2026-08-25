<?php

namespace Database\Seeders\Questions\Reports;

use App\Enums\Questionnaire\QuestionType;
use App\Services\Questionnaire\QuestionSeederSyncService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GrowthWeightFatteningReportsQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $questions = [
                [
                    'seed_key' => 'growth_weight_fattening_report.primary_views',
                    'title' => 'ما العروض الرئيسية التي يجب أن يوفرها قسم تقارير النمو والأوزان والتسمين؟',
                    'help_text' => 'المطلوب تحليل الأوزان والنمو بعد الفطام ومسار التسمين اعتمادًا على السجلات الفعلية، دون إعادة تعريف مواعيد الوزن أو أهدافه أو قواعد جاهزية البيع الموجودة في Settings وWorkflow.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 1,
                    'report_category' => 'report_scope',
                    'target_entity' => 'growth_weight_fattening_report',
                    'options' => [
                        ['label' => 'تحليل سجل الأوزان ومؤشرات النمو', 'value' => 'weight_and_growth_analysis'],
                        ['label' => 'منحنى نمو الحيوان الفردي', 'value' => 'individual_growth_curve'],
                        ['label' => 'مقارنة أفراد البطن / الإخوة أثناء النمو', 'value' => 'litter_sibling_comparison'],
                        ['label' => 'تحليل مسار التسمين والتقدم نحو الجاهزية للبيع', 'value' => 'fattening_progress_and_sale_readiness'],
                    ],
                ],
                [
                    'seed_key' => 'growth_report.weight_source_model',
                    'title' => 'ما المصدر الذي يجب أن تعتمد عليه تقارير الأوزان والنمو للقيم الفعلية؟',
                    'help_text' => '4.3 هو المصدر Canonical لسجل الوزن. المطلوب منع وجود قيم وزن تقريرية مستقلة قد تختلف عن السجل التاريخي الفعلي.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 2,
                    'report_category' => 'source_rule',
                    'target_entity' => 'growth_report',
                    'options' => [
                        ['label' => 'الاعتماد حصريًا على Weight History في 4.3 مع سياق كل قياس', 'value' => 'canonical_weight_history_only'],
                        ['label' => 'الاعتماد على آخر وزن محفوظ في ملف الحيوان عند توفره، ثم الرجوع للسجل التاريخي', 'value' => 'current_weight_then_history'],
                        ['label' => 'السماح بإدخال وزن خاص بالتقرير مستقل عن Workflow', 'value' => 'report_specific_weight_values'],
                    ],
                ],
                [
                    'seed_key' => 'growth_report.core_metrics',
                    'title' => 'ما مؤشرات الوزن والنمو التي يجب أن يستطيع التقرير حسابها وعرضها؟',
                    'help_text' => 'المصدر يذكر وزن الفطام وأوزان مراحل النمو ومعدل الزيادة اليومية والزيادة بين المراحل. الأعمار مثل 45 و70 يومًا و3 أشهر أمثلة وليست مراحل ثابتة في الكود.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 3,
                    'report_category' => 'report_metric',
                    'target_entity' => 'growth_report',
                    'options' => [
                        ['label' => 'وزن الفطام', 'value' => 'weaning_weight'],
                        ['label' => 'آخر وزن مسجل', 'value' => 'latest_weight'],
                        ['label' => 'الوزن عند مراحل القياس / الفرز المعتمدة', 'value' => 'weight_at_configured_stages'],
                        ['label' => 'متوسط الزيادة اليومية', 'value' => 'average_daily_gain'],
                        ['label' => 'الزيادة بين قياسين متتاليين', 'value' => 'gain_between_measurements'],
                        ['label' => 'الزيادة بين مراحل النمو المعتمدة', 'value' => 'gain_between_configured_stages'],
                        ['label' => 'العمر عند كل قياس', 'value' => 'age_at_measurement'],
                        ['label' => 'مدة المتابعة من الفطام حتى آخر قياس', 'value' => 'growth_tracking_duration'],
                    ],
                ],
                [
                    'seed_key' => 'growth_report.stage_model',
                    'title' => 'كيف يجب التعامل في التقرير مع مراحل الوزن مثل 45 يومًا و70 يومًا و3 أشهر؟',
                    'help_text' => 'التصور يستخدم هذه الأعمار كأمثلة، بينما Workflow 4.9 ينص على أن مراحل الفرز وأعمارها قابلة للضبط. التقرير يجب أن يظل متوافقًا مع المراحل الفعلية المعتمدة لكل مزرعة.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 4,
                    'report_category' => 'report_model',
                    'target_entity' => 'growth_report',
                    'options' => [
                        ['label' => 'يعتمد التقرير مراحل الوزن / الفرز المعرفة في Settings ويعرض نتائج كل مرحلة', 'value' => 'use_configured_growth_stages'],
                        ['label' => 'يعرض مراحل ثابتة موحدة مثل 45 و70 يومًا و3 أشهر لكل المزارع', 'value' => 'fixed_standard_age_stages'],
                        ['label' => 'يعرض جميع القياسات زمنيًا فقط دون مفهوم مراحل تحليلية', 'value' => 'chronological_measurements_only'],
                    ],
                ],
                [
                    'seed_key' => 'growth_report.target_comparison',
                    'title' => 'كيف يجب أن يعرض التقرير مقارنة الوزن أو النمو بالأهداف التشغيلية؟',
                    'help_text' => 'الأوزان المستهدفة وحدود النمو لا تعرف داخل Reports؛ مصدرها Settings. التقرير يحدد فقط شكل المقارنة بالمستهدف المطبق.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 5,
                    'report_category' => 'presentation_rule',
                    'target_entity' => 'growth_report',
                    'options' => [
                        ['label' => 'عرض القيمة الفعلية فقط دون مقارنة بالمستهدف', 'value' => 'actual_values_only'],
                        ['label' => 'عرض الفعلي مقابل المستهدف مع مقدار / نسبة الانحراف', 'value' => 'actual_vs_target_with_variance'],
                        ['label' => 'عرض الفعلي مقابل المستهدف وتصنيف مشتق مثل أقل من المستهدف / ضمن المتوقع / أعلى من المستهدف', 'value' => 'actual_vs_target_with_derived_classification'],
                    ],
                ],
                [
                    'seed_key' => 'growth_report.curve_model',
                    'title' => 'كيف يجب أن يمثل النظام منحنى نمو الحيوان الفردي؟',
                    'help_text' => 'التصور يطلب عرض العمر مقابل الوزن من الفطام حتى آخر وزن لمعرفة اتجاه النمو. المطلوب حسم مستوى العرض دون إنشاء قياسات غير موجودة.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 6,
                    'report_category' => 'visualization_rule',
                    'target_entity' => 'individual_growth_curve',
                    'options' => [
                        ['label' => 'جدول زمني للقياسات فقط: العمر / التاريخ / الوزن', 'value' => 'chronological_measurement_table'],
                        ['label' => 'منحنى عمر → وزن مع جدول القياسات المصدر', 'value' => 'age_weight_curve_with_source_table'],
                        ['label' => 'منحنى عمر → وزن مع المستهدف / النطاق المتوقع عند توفره من Settings', 'value' => 'age_weight_curve_with_target_context'],
                    ],
                ],
                [
                    'seed_key' => 'growth_report.litter_comparison_metrics',
                    'title' => 'ما المعلومات التي يجب أن يدعمها تحليل مقارنة أفراد البطن / الإخوة أثناء النمو؟',
                    'help_text' => 'المصدر يذكر مقارنة أوزان الإخوة ومتوسط البطن وأعلى وأقل فرد ومعدل التجانس للمساعدة في التقييم. قرار الإحلال نفسه لا ينفذ داخل التقرير.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 7,
                    'report_category' => 'comparison_metric',
                    'target_entity' => 'litter_growth_comparison',
                    'options' => [
                        ['label' => 'أوزان الأفراد في نفس المرحلة / العمر المقارن', 'value' => 'sibling_weights'],
                        ['label' => 'متوسط وزن أفراد البطن', 'value' => 'average_litter_weight'],
                        ['label' => 'أعلى وزن داخل البطن', 'value' => 'highest_litter_weight'],
                        ['label' => 'أقل وزن داخل البطن', 'value' => 'lowest_litter_weight'],
                        ['label' => 'تشتت / تجانس الأوزان داخل البطن', 'value' => 'litter_weight_uniformity'],
                        ['label' => 'معدل النمو لكل فرد مقارنة بمتوسط إخوته', 'value' => 'individual_growth_vs_sibling_average'],
                    ],
                ],
                [
                    'seed_key' => 'growth_report.comparison_age_alignment',
                    'title' => 'عند مقارنة أوزان أفراد أو بطون، كيف يجب التعامل مع اختلاف العمر أو توقيت القياس؟',
                    'help_text' => 'مقارنة أوزان مأخوذة في أعمار مختلفة قد تكون مضللة. المصدر يعتمد على العمر ومراحل الوزن، لذلك يجب حسم أساس المقارنة دون اختراع وزن تقديري غير مسجل.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 8,
                    'report_category' => 'comparison_rule',
                    'target_entity' => 'growth_comparison',
                    'options' => [
                        ['label' => 'تقارن القياسات المسجلة في نفس المرحلة / نطاق العمر فقط', 'value' => 'compare_same_stage_or_age_window'],
                        ['label' => 'تقارن آخر الأوزان المسجلة مهما اختلفت الأعمار مع إظهار العمر بجانب كل وزن', 'value' => 'compare_latest_weights_with_age_context'],
                        ['label' => 'يدعم الطريقتين ويحدد التقرير بوضوح أساس المقارنة المستخدم', 'value' => 'support_stage_and_latest_comparisons_with_basis'],
                    ],
                ],
                [
                    'seed_key' => 'fattening_report.metrics',
                    'title' => 'ما المؤشرات التي يجب أن يتضمنها تقرير التسمين؟',
                    'help_text' => 'يعتمد التقرير على مسار التسمين في 4.12 وسجل الأوزان في 4.3. الجاهزية للبيع حالة مشتقة / مراجعة تشغيلية وليست عملية بيع فعلية.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 9,
                    'report_category' => 'report_metric',
                    'target_entity' => 'fattening_report',
                    'options' => [
                        ['label' => 'عدد الحيوانات تحت التسمين حاليًا', 'value' => 'active_fattening_animals'],
                        ['label' => 'متوسط عمر بداية التسمين', 'value' => 'average_age_at_fattening_start'],
                        ['label' => 'متوسط وزن بداية التسمين', 'value' => 'average_start_weight'],
                        ['label' => 'متوسط / توزيع الوزن الحالي', 'value' => 'current_weight_summary'],
                        ['label' => 'متوسط الزيادة اليومية أثناء التسمين', 'value' => 'average_daily_gain_during_fattening'],
                        ['label' => 'متوسط مدة التسمين', 'value' => 'average_fattening_duration'],
                        ['label' => 'عدد الجاهزين للبيع', 'value' => 'sale_ready_count'],
                        ['label' => 'عدد الأقل من الوزن / النمو المستهدف', 'value' => 'below_target_count'],
                        ['label' => 'عدد المتجاوزين للعمر أو مدة التسمين المستهدفة', 'value' => 'target_age_or_duration_exceeded_count'],
                    ],
                ],
                [
                    'seed_key' => 'fattening_report.readiness_source',
                    'title' => 'من أين يجب أن تأتي حالة «جاهز للبيع / غير جاهز» التي يعرضها تقرير التسمين؟',
                    'help_text' => '4.12 يحسم نموذج جاهزية البيع ويطبق قواعد Settings. التقرير يجب ألا ينشئ منطق جاهزية موازيًا قد ينتج حالة مختلفة.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 10,
                    'report_category' => 'derivation_rule',
                    'target_entity' => 'fattening_report',
                    'options' => [
                        ['label' => 'استخدام نتيجة الجاهزية نفسها المشتقة / المعتمدة في 4.12', 'value' => 'use_canonical_sale_readiness_result'],
                        ['label' => 'إعادة حساب الجاهزية داخل التقرير مباشرة من الوزن والعمر الحاليين', 'value' => 'recalculate_readiness_in_report'],
                        ['label' => 'يعرض التقرير البيانات فقط دون إظهار حالة جاهزية', 'value' => 'show_context_without_readiness_state'],
                    ],
                ],
                [
                    'seed_key' => 'fattening_report.individual_aggregation_model',
                    'title' => 'كيف يجب أن يتعامل التقرير مع التسمين الجماعي داخل قفص أو مجموعة مع الحفاظ على التتبع الفردي؟',
                    'help_text' => 'المصدر و4.12 يؤكدان أن التسمين الجماعي لا يلغي هوية الحيوان ووزنه وتاريخه الفردي. التجميع هنا للعرض والتحليل فقط.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 11,
                    'report_category' => 'aggregation_rule',
                    'target_entity' => 'fattening_report',
                    'options' => [
                        ['label' => 'يعرض ملخصات للمجموعة مع إمكانية النزول لكل حيوان وسجله الفردي', 'value' => 'aggregate_with_individual_drilldown'],
                        ['label' => 'يعرض الحيوانات الفردية فقط دون أي تجميع للمجموعة', 'value' => 'individual_rows_only'],
                        ['label' => 'يعرض بيانات القفص / المجموعة فقط دون تفاصيل الحيوانات', 'value' => 'group_aggregate_only'],
                    ],
                ],
                [
                    'seed_key' => 'growth_weight_fattening_report.drilldown_model',
                    'title' => 'إلى أي مستوى يجب أن يستطيع المستخدم النزول من مؤشرات النمو والأوزان والتسمين؟',
                    'help_text' => 'الهدف أن تكون المتوسطات والمنحنيات والتصنيفات قابلة للتفسير والرجوع إلى القياسات والأحداث Canonical الأصلية. الصفحة التحليلية الشاملة للحيوان ستراجع لاحقًا في 5.12.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 12,
                    'report_category' => 'interaction_rule',
                    'target_entity' => 'growth_weight_fattening_report',
                    'options' => [
                        ['label' => 'المؤشرات المجمعة فقط', 'value' => 'aggregate_only'],
                        ['label' => 'من المؤشر إلى قائمة الحيوانات / البطون الداخلة في الحساب', 'value' => 'drilldown_to_subjects'],
                        ['label' => 'من المؤشر إلى الحيوان ثم إلى سجل الأوزان ومرحلة التقييم / التسمين والحدث المصدر', 'value' => 'drilldown_to_subjects_weights_and_source_events'],
                    ],
                ],
            ];

            app(QuestionSeederSyncService::class)->sync(
                mainSectionName: 'التقارير والتحليلات والتنبيهات ومؤشرات الأداء',
                sectionName: 'تقارير النمو والأوزان والتسمين',
                questions: $questions,
                prune: true,
                preserveAnswers: true,
            );
        });
    }
}
