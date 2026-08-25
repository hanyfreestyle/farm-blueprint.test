<?php

namespace Database\Seeders\Questions\Reports;

use App\Enums\Questionnaire\QuestionType;
use App\Services\Questionnaire\QuestionSeederSyncService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DailyOperationsDashboardQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $questions = [
                [
                    'seed_key' => 'daily_dashboard.primary_role',
                    'title' => 'ما الدور الأساسي الذي يجب أن تؤديه لوحة التحكم عند دخول المستخدم للنظام؟',
                    'help_text' => 'المرجع الوظيفي يعتبر شاشة اليوم نقطة البداية اليومية لمدير المزرعة. المطلوب حسم طبيعة الشاشة نفسها دون تحويلها إلى بديل عن الـWorkflow أو التقارير التفصيلية.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 1,
                    'report_category' => 'dashboard_scope',
                    'target_entity' => 'daily_dashboard',
                    'options' => [
                        ['label' => 'شاشة تشغيل يومية تركز على ما يحتاج تنفيذًا أو انتباهًا الآن', 'value' => 'daily_operational_home'],
                        ['label' => 'شاشة ملخص عام تعرض الوضع الحالي مع روابط للتفاصيل والتنفيذ', 'value' => 'summary_home_with_drilldown'],
                        ['label' => 'شاشة تحليلية رئيسية تركز على المؤشرات والاتجاهات أكثر من التشغيل اليومي', 'value' => 'analytical_home'],
                    ],
                ],
                [
                    'seed_key' => 'daily_dashboard.visible_blocks',
                    'title' => 'ما الكتل الرئيسية التي يجب أن تظهر في لوحة التحكم اليومية؟',
                    'help_text' => 'حدد المكونات التي يحتاج المستخدم رؤيتها في نقطة البداية. تفاصيل كل تقرير أو قاعدة أو Alert تبقى في القسم المتخصص الخاص بها.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 2,
                    'report_category' => 'dashboard_content',
                    'target_entity' => 'daily_dashboard',
                    'options' => [
                        ['label' => 'ملخص القطيع الحالي', 'value' => 'current_herd_summary'],
                        ['label' => 'الوضع الإنتاجي الحالي للإناث', 'value' => 'female_production_status'],
                        ['label' => 'مهام اليوم', 'value' => 'today_tasks'],
                        ['label' => 'المهام المتأخرة', 'value' => 'overdue_tasks'],
                        ['label' => 'الأحداث المهمة التي حدثت اليوم', 'value' => 'important_events_today'],
                        ['label' => 'التنبيهات الحالية التي تحتاج انتباهًا', 'value' => 'active_alerts'],
                    ],
                ],
                [
                    'seed_key' => 'daily_dashboard.herd_summary_items',
                    'title' => 'ما الأعداد التي يجب أن يتضمنها ملخص القطيع الحالي في لوحة التحكم؟',
                    'help_text' => 'هذه أعداد مشتقة من السجلات والحركات الحالية وليست حقولًا يكتبها المستخدم يدويًا. التقارير التفصيلية للقطيع والجاهزية توجد في 5.2.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 3,
                    'report_category' => 'dashboard_metric',
                    'target_entity' => 'daily_dashboard_herd_summary',
                    'options' => [
                        ['label' => 'إجمالي الحيوانات الموجودة حاليًا بالمزرعة', 'value' => 'total_animals_present'],
                        ['label' => 'عدد الإناث الإنتاجية', 'value' => 'productive_females'],
                        ['label' => 'عدد الذكور الإنتاجية', 'value' => 'productive_males'],
                        ['label' => 'عدد مرشحي الإحلال', 'value' => 'replacement_candidates'],
                        ['label' => 'عدد المفطومين / الحيوانات تحت النمو', 'value' => 'post_weaning_growth_animals'],
                        ['label' => 'عدد حيوانات التسمين', 'value' => 'fattening_animals'],
                        ['label' => 'عدد الحيوانات في العزل أو تحت المتابعة الصحية', 'value' => 'isolated_or_observed_animals'],
                        ['label' => 'عدد الحيوانات الجاهزة للبيع', 'value' => 'sale_ready_animals'],
                    ],
                ],
                [
                    'seed_key' => 'daily_dashboard.female_status_items',
                    'title' => 'ما الحالات الإنتاجية للإناث التي يجب تلخيصها في لوحة التحكم؟',
                    'help_text' => 'اللوحة تعرض الحالة المشتقة الحالية فقط. قواعد الجاهزية والتوقيت توجد في Settings، والأحداث التي نقلت الأنثى بين المراحل مسجلة في Workflow.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 4,
                    'report_category' => 'dashboard_metric',
                    'target_entity' => 'daily_dashboard_female_status',
                    'options' => [
                        ['label' => 'إناث جاهزة للتلقيح', 'value' => 'ready_for_mating'],
                        ['label' => 'إناث تم تلقيحها وتنتظر فحص الحمل', 'value' => 'awaiting_pregnancy_check'],
                        ['label' => 'إناث حوامل', 'value' => 'pregnant'],
                        ['label' => 'إناث قريبة من الولادة المتوقعة', 'value' => 'near_expected_birth'],
                        ['label' => 'إناث مرضعة', 'value' => 'lactating'],
                        ['label' => 'إناث مرضعة وحامل في نفس الوقت', 'value' => 'lactating_and_pregnant'],
                        ['label' => 'إناث في فترة راحة / غير جاهزة حاليًا', 'value' => 'resting_or_not_ready'],
                        ['label' => 'إناث متأخرة عن إجراء إنتاجي مطلوب', 'value' => 'overdue_production_action'],
                    ],
                ],
                [
                    'seed_key' => 'daily_dashboard.today_task_presentation',
                    'title' => 'كيف يجب عرض مهام اليوم في لوحة التحكم؟',
                    'help_text' => 'أنواع المهام معرفة كـMaster Data وسجل المهمة نفسه في Workflow. المطلوب هنا تحديد طريقة عرض المهام المستحقة اليوم داخل الـDashboard.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 5,
                    'report_category' => 'presentation_rule',
                    'target_entity' => 'daily_dashboard_tasks',
                    'options' => [
                        ['label' => 'تجميع حسب نوع المهمة مع إظهار عدد الحالات لكل نوع', 'value' => 'group_by_task_type_with_counts'],
                        ['label' => 'عرض قائمة تفصيلية بالمهام الفردية فقط', 'value' => 'detailed_task_list_only'],
                        ['label' => 'ملخص حسب النوع ثم قائمة تفصيلية قابلة للفتح', 'value' => 'summary_then_detailed_list'],
                    ],
                ],
                [
                    'seed_key' => 'daily_dashboard.overdue_task_presentation',
                    'title' => 'كيف يجب إظهار المهام المتأخرة مقارنة بمهام اليوم؟',
                    'help_text' => 'المصدر يطلب أن تظل المهمة المتأخرة ظاهرة ولا تختفي بمرور الموعد. هذا السؤال يحدد Presentation فقط؛ تعريف التأخير وحالة المهمة يأتيان من سجل المهمة وقواعدها.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 6,
                    'report_category' => 'presentation_rule',
                    'target_entity' => 'daily_dashboard_tasks',
                    'options' => [
                        ['label' => 'كتلة مستقلة وواضحة للمهام المتأخرة بعيدًا عن مهام اليوم', 'value' => 'separate_overdue_block'],
                        ['label' => 'تظهر مع مهام اليوم في قائمة واحدة مع تمييز المتأخر', 'value' => 'combined_list_with_overdue_marker'],
                        ['label' => 'يظهر عدد المتأخرات في الملخص وتفتح قائمة مستقلة عند الطلب', 'value' => 'overdue_summary_with_drilldown'],
                    ],
                ],
                [
                    'seed_key' => 'daily_dashboard.task_direct_execution',
                    'title' => 'هل يجب أن تسمح لوحة التحكم ببدء تنفيذ المهمة مباشرة من قائمة مهام اليوم أو المتأخرات؟',
                    'help_text' => 'إذا كانت الإجابة نعم، تستخدم اللوحة آلية تنفيذ المهمة المعتمدة في 4.17 للوصول إلى الـWorkflow Canonical المناسب؛ هذا السؤال لا يعيد حسم طريقة تنفيذ المهمة نفسها.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 7,
                    'report_category' => 'interaction_rule',
                    'target_entity' => 'daily_dashboard_tasks',
                    'options' => [],
                ],
                [
                    'seed_key' => 'daily_dashboard.task_visibility_scope',
                    'title' => 'ما نطاق المهام التي يجب أن تظهر للمستخدم في لوحة التحكم اليومية؟',
                    'help_text' => 'النطاق هنا يطبق فقط داخل البيانات التي يملك المستخدم صلاحية رؤيتها. صلاحيات الوصول نفسها لا تحسم في هذا السؤال.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 8,
                    'report_category' => 'dashboard_scope',
                    'target_entity' => 'daily_dashboard_tasks',
                    'options' => [
                        ['label' => 'المهام المسندة للمستخدم الحالي فقط', 'value' => 'current_user_assigned_tasks'],
                        ['label' => 'كل مهام المزرعة / النطاق المسموح للمستخدم رؤيته', 'value' => 'all_authorized_scope_tasks'],
                        ['label' => 'عرض مهامي افتراضيًا مع إمكانية التحويل إلى كل المهام المسموح بها', 'value' => 'my_tasks_default_with_all_authorized_view'],
                    ],
                ],
                [
                    'seed_key' => 'daily_dashboard.important_event_types',
                    'title' => 'ما أنواع الأحداث المهمة التي يجب تلخيصها ضمن «ما حدث اليوم»؟',
                    'help_text' => 'الأحداث نفسها تسجل في أقسام Workflow المناسبة. لوحة التحكم تعرض ملخصًا للأحداث الفعلية المهمة التي وقعت اليوم دون إنشاء سجل جديد لها.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 9,
                    'report_category' => 'dashboard_content',
                    'target_entity' => 'daily_dashboard_events',
                    'options' => [
                        ['label' => 'الولادات التي تمت اليوم', 'value' => 'births'],
                        ['label' => 'حالات النفوق', 'value' => 'mortality'],
                        ['label' => 'الإجهاضات / الحالات الاستثنائية المهمة', 'value' => 'abortions_or_exceptions'],
                        ['label' => 'حالات العزل', 'value' => 'isolation'],
                        ['label' => 'عمليات البيع / الخروج من المزرعة', 'value' => 'sales_or_exits'],
                        ['label' => 'حركات النقل المهمة بين المواقع أو المزارع', 'value' => 'housing_or_farm_transfers'],
                        ['label' => 'حالات الدخول الجديدة أو إعادة الدخول', 'value' => 'new_intakes_or_reentries'],
                    ],
                ],
                [
                    'seed_key' => 'daily_dashboard.active_alert_presentation',
                    'title' => 'كيف يجب أن تعرض لوحة التحكم التنبيهات الحالية التي تحتاج انتباهًا؟',
                    'help_text' => 'اكتشاف التنبيه وتاريخه وإغلاقه تتم دراستها في قسم التنبيهات، ومستويات الشدة والأولوية في Settings. هنا نحسم فقط مقدار ما يظهر منها على شاشة اليوم.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 10,
                    'report_category' => 'presentation_rule',
                    'target_entity' => 'daily_dashboard_alerts',
                    'options' => [
                        ['label' => 'عرض كل التنبيهات النشطة ضمن النطاق المسموح مع إمكانية فتح التفاصيل', 'value' => 'all_active_alerts_with_drilldown'],
                        ['label' => 'عرض ملخص الأعداد وأهم التنبيهات فقط مع رابط للقائمة الكاملة', 'value' => 'summary_and_top_alerts'],
                        ['label' => 'عرض عدد التنبيهات فقط والانتقال إلى شاشة التنبيهات للتفاصيل', 'value' => 'count_only_with_alerts_page'],
                    ],
                ],
                [
                    'seed_key' => 'daily_dashboard.summary_drilldown_model',
                    'title' => 'كيف يجب التعامل مع الأرقام والملخصات القابلة للتفصيل في لوحة التحكم؟',
                    'help_text' => 'مثل عدد الحوامل أو الحيوانات المعزولة أو المهام المتأخرة. المطلوب تحديد هل تكون أرقامًا للعرض فقط أم مداخل للوصول إلى السجلات التي كوّنت الرقم.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 11,
                    'report_category' => 'navigation_rule',
                    'target_entity' => 'daily_dashboard',
                    'options' => [
                        ['label' => 'كل ملخص قابل للنقر ويفتح قائمة مفلترة بالسجلات المكونة له', 'value' => 'all_summaries_drilldown'],
                        ['label' => 'الأرقام للعرض فقط والتفاصيل تفتح من الأقسام الرئيسية', 'value' => 'display_only'],
                        ['label' => 'تكون قابلية فتح التفاصيل حسب نوع الكتلة / المؤشر', 'value' => 'drilldown_by_block'],
                    ],
                ],
                [
                    'seed_key' => 'daily_dashboard.farm_scope_model',
                    'title' => 'كيف يجب أن تتعامل لوحة التحكم مع وجود أكثر من مزرعة ضمن نطاق المستخدم؟',
                    'help_text' => 'هذا السؤال يحدد طريقة العرض فقط داخل المزارع المسموح للمستخدم رؤيتها، ولا يحدد صلاحياته أو آلية ربط المستخدم بالمزرعة.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 12,
                    'report_category' => 'dashboard_scope',
                    'target_entity' => 'daily_dashboard',
                    'options' => [
                        ['label' => 'اختيار مزرعة واحدة وعرض لوحة التحكم الخاصة بها فقط', 'value' => 'single_selected_farm'],
                        ['label' => 'ملخص مجمع لكل المزارع المسموح بها مع إمكانية النزول لكل مزرعة', 'value' => 'aggregate_authorized_farms_with_drilldown'],
                        ['label' => 'دعم العرضين: ملخص عام أو مزرعة محددة', 'value' => 'global_and_selected_farm_views'],
                    ],
                ],
            ];

            app(QuestionSeederSyncService::class)->sync(
                mainSectionName: 'التقارير والتحليلات والتنبيهات ومؤشرات الأداء',
                sectionName: 'لوحة التحكم ومتابعة التشغيل اليومي',
                questions: $questions,
                prune: true,
                preserveAnswers: true,
            );
        });
    }
}
