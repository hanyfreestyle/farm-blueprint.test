<?php

namespace Database\Seeders\Questions\Reports;

use App\Enums\Questionnaire\QuestionType;
use App\Services\Questionnaire\QuestionSeederSyncService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HousingOccupancyCapacityOperationsReportsQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $questions = [
                [
                    'seed_key' => 'housing_report.primary_views',
                    'title' => 'ما العروض الرئيسية التي يجب أن يوفرها قسم تقارير الإشغال والسعة وتشغيل مواقع الإيواء؟',
                    'help_text' => 'المطلوب تحليل استخدام مواقع الإيواء وحالتها التشغيلية اعتمادًا على الهيكل والحركات وأحداث الصيانة والتطهير، دون تنفيذ حركة أو تغيير حالة موقع من داخل التقرير.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 1,
                    'report_category' => 'report_scope',
                    'target_entity' => 'housing_report',
                    'options' => [
                        ['label' => 'تقرير السعة والإشغال الحالي', 'value' => 'current_capacity_and_occupancy'],
                        ['label' => 'تقرير حالة الأقفاص والمواقع المتاحة وغير المتاحة', 'value' => 'site_availability_status'],
                        ['label' => 'تقرير الصيانة والتوقف وفترات عدم الإتاحة', 'value' => 'maintenance_and_downtime'],
                        ['label' => 'تقرير التطهير والتجهيز والعودة للخدمة', 'value' => 'sanitation_and_return_to_service'],
                        ['label' => 'تقرير العجز أو الاحتياج المتوقع في السعة', 'value' => 'projected_capacity_shortage'],
                    ],
                ],
                [
                    'seed_key' => 'housing_report.hierarchy_levels',
                    'title' => 'على أي مستويات من هيكل المزرعة يجب أن يدعم التقرير تجميع السعة والإشغال؟',
                    'help_text' => 'المرجع يعرض التسلسل Farm → Barn → Battery → Cage، والقيم في المستويات الأعلى يجب أن تنتج من المواقع التابعة بدل إدخال أرقام مستقلة.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 2,
                    'report_category' => 'aggregation_scope',
                    'target_entity' => 'housing_capacity_report',
                    'options' => [
                        ['label' => 'المزرعة', 'value' => 'farm'],
                        ['label' => 'العنبر', 'value' => 'barn'],
                        ['label' => 'البطارية', 'value' => 'battery'],
                        ['label' => 'القفص / العين', 'value' => 'cage'],
                    ],
                ],
                [
                    'seed_key' => 'housing_report.capacity_metrics',
                    'title' => 'ما مؤشرات السعة والإشغال التي يجب عرضها لكل مستوى مناسب؟',
                    'help_text' => 'السعة والإشغال الحالي والسعة المتاحة ونسبة الإشغال مذكورة صراحة في المرجع. التقرير يحسبها من الهيكل والإشغال والحالة التشغيلية ولا يسمح بإدخالها يدويًا.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 3,
                    'report_category' => 'report_metric',
                    'target_entity' => 'housing_capacity_report',
                    'options' => [
                        ['label' => 'السعة الإجمالية المعرفة', 'value' => 'total_defined_capacity'],
                        ['label' => 'الإشغال الحالي', 'value' => 'current_occupancy'],
                        ['label' => 'السعة المتاحة حاليًا', 'value' => 'currently_available_capacity'],
                        ['label' => 'نسبة الإشغال', 'value' => 'occupancy_rate'],
                        ['label' => 'عدد المواقع غير المتاحة تشغيليًا', 'value' => 'operationally_unavailable_sites'],
                    ],
                ],
                [
                    'seed_key' => 'housing_report.available_capacity_denominator',
                    'title' => 'كيف يجب احتساب السعة المتاحة ونسبة الإشغال عند وجود أقفاص أو مواقع غير متاحة بسبب الصيانة أو التعطيل أو انتظار التطهير؟',
                    'help_text' => 'المرجع يفرق بين السعة الفيزيائية وبين حالة القفص التشغيلية. المطلوب حسم هل تعرض التقارير السعة الفيزيائية والسعة التشغيلية المتاحة كمفهومين منفصلين لتجنب تفسير مضلل لنسبة الإشغال.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 4,
                    'report_category' => 'calculation_rule',
                    'target_entity' => 'housing_capacity_report',
                    'options' => [
                        ['label' => 'عرض السعة الفيزيائية والسعة التشغيلية المتاحة كلٌ على حدة، مع احتساب نسبة إشغال تشغيلية على السعة القابلة للاستخدام', 'value' => 'separate_physical_and_operational_capacity'],
                        ['label' => 'احتساب نسبة الإشغال دائمًا على إجمالي السعة الفيزيائية حتى لو كانت بعض المواقع غير متاحة', 'value' => 'occupancy_against_total_physical_capacity'],
                        ['label' => 'عرض الأعداد فقط دون نسبة إشغال مشتقة عند وجود مواقع غير متاحة', 'value' => 'counts_only_when_unavailable_sites_exist'],
                    ],
                ],
                [
                    'seed_key' => 'housing_report.site_status_breakdown',
                    'title' => 'ما حالات مواقع الإيواء التي يجب أن يستطيع التقرير فصلها عند عرض التوزيع الحالي؟',
                    'help_text' => 'المصدر يذكر حالات مثل مشغول ومتاح وبانتظار تنظيف/تعقيم ومعطل وتحت الصيانة وعزل وغير متاح لسبب آخر. الحالة النهائية المعروضة يجب أن تعتمد على بيانات الموقع والإشغال وأحداث التشغيل المطبقة.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 5,
                    'report_category' => 'status_breakdown',
                    'target_entity' => 'housing_site_report',
                    'options' => [
                        ['label' => 'مشغول', 'value' => 'occupied'],
                        ['label' => 'متاح', 'value' => 'available'],
                        ['label' => 'بانتظار تنظيف / تعقيم أو تجهيز', 'value' => 'awaiting_sanitation'],
                        ['label' => 'تحت الصيانة', 'value' => 'under_maintenance'],
                        ['label' => 'معطل', 'value' => 'faulted'],
                        ['label' => 'خارج الخدمة / غير متاح تشغيليًا', 'value' => 'out_of_service'],
                        ['label' => 'مستخدم للعزل عند انطباقه', 'value' => 'isolation_use'],
                        ['label' => 'غير متاح لسبب آخر', 'value' => 'other_unavailable'],
                    ],
                ],
                [
                    'seed_key' => 'housing_report.usage_breakdown',
                    'title' => 'هل يجب أن يدعم تقرير السعة والإشغال توزيع النتائج حسب استخدام موقع الإيواء الحالي؟',
                    'help_text' => 'المرجع يفرق بين الاستخدامات مثل أنثى إنتاج وذكر وفطام وتسمين وعزل. هذا يسمح بمعرفة السعة المتاحة الفعلية للغرض المطلوب بدل الاكتفاء بإجمالي سعة المزرعة.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 6,
                    'report_category' => 'aggregation_scope',
                    'target_entity' => 'housing_capacity_report',
                    'options' => [],
                ],
                [
                    'seed_key' => 'housing_report.maintenance_metrics',
                    'title' => 'ما المؤشرات التي يجب أن يتضمنها تحليل صيانة مواقع الإيواء؟',
                    'help_text' => '4.16 يحتفظ بتاريخ الصيانة وبدايتها واكتمالها على القفص أو البطارية أو العنبر. التقرير يحلل هذه السجلات ولا يعيد تعريف دورة الصيانة أو قواعد بدءها.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 7,
                    'report_category' => 'report_metric',
                    'target_entity' => 'housing_maintenance_report',
                    'options' => [
                        ['label' => 'عدد عمليات الصيانة', 'value' => 'maintenance_count'],
                        ['label' => 'عدد المواقع الموجودة حاليًا تحت الصيانة', 'value' => 'sites_currently_under_maintenance'],
                        ['label' => 'متوسط مدة الصيانة المكتملة', 'value' => 'average_completed_maintenance_duration'],
                        ['label' => 'المواقع ذات فترات الصيانة الأطول', 'value' => 'longest_maintenance_sites'],
                        ['label' => 'تكرار الصيانة لنفس الموقع', 'value' => 'repeat_maintenance_by_site'],
                        ['label' => 'إجمالي مدة عدم الإتاحة الناتجة عن الصيانة', 'value' => 'maintenance_unavailability_duration'],
                    ],
                ],
                [
                    'seed_key' => 'housing_report.sanitation_metrics',
                    'title' => 'ما المؤشرات التي يجب أن يتضمنها تحليل التطهير والتجهيز والعودة للخدمة؟',
                    'help_text' => 'المصدر و4.16 يميزان بين إخلاء الموقع وانتظار التطهير واكتمال التجهيز والعودة الفعلية إلى الخدمة. التقرير يجب أن يحافظ على هذه المراحل بدل اختزالها في متاح / غير متاح فقط.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 8,
                    'report_category' => 'report_metric',
                    'target_entity' => 'housing_sanitation_report',
                    'options' => [
                        ['label' => 'عدد المواقع المنتظرة للتنظيف / التعقيم', 'value' => 'sites_awaiting_sanitation'],
                        ['label' => 'عدد عمليات التطهير / التجهيز المكتملة', 'value' => 'completed_sanitation_count'],
                        ['label' => 'متوسط الزمن من الإخلاء حتى اكتمال التجهيز', 'value' => 'average_vacate_to_ready_duration'],
                        ['label' => 'متوسط الزمن من اكتمال الصيانة حتى العودة للخدمة عند انطباقه', 'value' => 'average_maintenance_completion_to_service_return'],
                        ['label' => 'المواقع التي اكتمل تجهيزها وما زالت غير متاحة بسبب متطلبات أخرى', 'value' => 'prepared_but_not_available_sites'],
                    ],
                ],
                [
                    'seed_key' => 'housing_report.parent_site_unavailability_effect',
                    'title' => 'هل يجب أن يوضح التقرير أثر عدم إتاحة عنبر أو بطارية على السعة التشغيلية للمواقع التابعة دون اعتبار كل قفص حدث صيانة مستقل؟',
                    'help_text' => '4.16 يسمح بأن يكون الحدث على الموقع الأب ويشتق أثره على المواقع التابعة. التقرير يحتاج إظهار أثر ذلك على السعة الفعلية مع الحفاظ على مصدر عدم الإتاحة الصحيح.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 9,
                    'report_category' => 'derivation_rule',
                    'target_entity' => 'housing_site_availability_report',
                    'options' => [],
                ],
                [
                    'seed_key' => 'housing_report.projected_capacity_model',
                    'title' => 'كيف يجب أن يعرض النظام العجز أو الاحتياج المتوقع في السعة؟',
                    'help_text' => 'المرجع يعطي مثالًا بمقارنة عدد الحيوانات المتوقع فطامها خلال فترة قادمة بالسعة المتاحة لأقفاص الفطام. التقرير يجب أن يقارن احتياجًا متوقعًا قابلًا للاشتقاق بسعة مناسبة للغرض، دون أن يضع قواعد النقل أو الفطام بنفسه.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 10,
                    'report_category' => 'forecast_model',
                    'target_entity' => 'housing_capacity_forecast',
                    'options' => [
                        ['label' => 'مقارنة الاحتياج المتوقع بالسعة التشغيلية المناسبة وعرض الفائض أو العجز المتوقع', 'value' => 'compare_projected_demand_with_operational_capacity'],
                        ['label' => 'عرض الاحتياج المتوقع والسعة المتاحة كقيم منفصلة دون حساب عجز أو فائض', 'value' => 'show_demand_and_capacity_separately'],
                        ['label' => 'عدم دعم توقع السعة؛ يقتصر التقرير على الوضع الحالي', 'value' => 'current_state_only'],
                    ],
                ],
                [
                    'seed_key' => 'housing_report.projected_capacity_context',
                    'title' => 'ما المعلومات التي يجب أن تظهر مع توقع العجز في السعة حتى يكون قابلًا للتصرف والمراجعة؟',
                    'help_text' => 'التوقع يجب أن يوضح مصدر الاحتياج والفترة ونوع الموقع المطلوب والسعة التي استخدمت في المقارنة، مع إمكانية الرجوع إلى السجلات التي كوّنت التوقع.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 11,
                    'report_category' => 'forecast_context',
                    'target_entity' => 'housing_capacity_forecast',
                    'options' => [
                        ['label' => 'الفترة الزمنية التي يغطيها التوقع', 'value' => 'forecast_period'],
                        ['label' => 'نوع / استخدام موقع الإيواء المطلوب', 'value' => 'required_housing_usage'],
                        ['label' => 'عدد الأماكن المتوقع الاحتياج إليها', 'value' => 'projected_required_capacity'],
                        ['label' => 'السعة التشغيلية المتاحة المناسبة', 'value' => 'matching_operational_capacity'],
                        ['label' => 'قيمة العجز أو الفائض المتوقع', 'value' => 'projected_shortage_or_surplus'],
                        ['label' => 'الأحداث أو السجلات التي أدت إلى الاحتياج المتوقع', 'value' => 'source_events_or_records'],
                    ],
                ],
                [
                    'seed_key' => 'housing_report.drilldown_model',
                    'title' => 'إلى أي مستوى يجب أن يستطيع المستخدم النزول من مؤشرات الإشغال والسعة وتشغيل المواقع؟',
                    'help_text' => 'الهدف أن تكون الأرقام قابلة للمراجعة، من إجمالي المزرعة حتى المواقع والحيوانات والحركات وأحداث الصيانة أو التطهير التي تفسر الحالة الحالية.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 12,
                    'report_category' => 'interaction_rule',
                    'target_entity' => 'housing_report',
                    'options' => [
                        ['label' => 'عرض الأرقام الإجمالية فقط', 'value' => 'aggregate_only'],
                        ['label' => 'من المزرعة إلى العنبر ثم البطارية ثم القفص / العين', 'value' => 'drilldown_through_housing_hierarchy'],
                        ['label' => 'من الهيكل إلى القفص ثم إلى الحيوانات / الإشغال والحركات وأحداث التشغيل Canonical المفسرة للحالة', 'value' => 'drilldown_to_occupancy_movements_and_operations'],
                    ],
                ],
            ];

            app(QuestionSeederSyncService::class)->sync(
                mainSectionName: 'التقارير والتحليلات والتنبيهات ومؤشرات الأداء',
                sectionName: 'تقارير الإشغال والسعة وتشغيل مواقع الإيواء',
                questions: $questions,
                prune: true,
                preserveAnswers: true,
            );
        });
    }
}
