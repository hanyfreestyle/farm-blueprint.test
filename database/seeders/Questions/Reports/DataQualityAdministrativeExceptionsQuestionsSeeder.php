<?php

namespace Database\Seeders\Questions\Reports;

use App\Enums\Questionnaire\QuestionType;
use App\Services\Questionnaire\QuestionSeederSyncService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataQualityAdministrativeExceptionsQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $questions = [
                [
                    'seed_key' => 'data_quality_report.primary_views',
                    'title' => 'ما العروض الرئيسية التي يجب أن يوفرها قسم جودة البيانات والاستثناءات الإدارية؟',
                    'help_text' => 'هذا القسم يكشف البيانات الناقصة أو المتعارضة والتسلسلات غير المنطقية التي قد تجعل التقارير أو الحالة الحالية مضللة. لا يسجل الحدث التشغيلي بدل Workflow، ولا يحدد Thresholds أو قواعد التنبيه بدل Settings.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 1,
                    'report_category' => 'report_scope',
                    'target_entity' => 'data_quality_report',
                    'options' => [
                        ['label' => 'بيانات حيوان أساسية ناقصة', 'value' => 'missing_animal_data'],
                        ['label' => 'تعارضات في الأعداد أو العلاقات', 'value' => 'count_or_relationship_inconsistencies'],
                        ['label' => 'تسلسلات Workflow غير منطقية أو غير مكتملة', 'value' => 'workflow_sequence_exceptions'],
                        ['label' => 'تعارضات الإشغال والسعة والموقع', 'value' => 'housing_and_capacity_exceptions'],
                        ['label' => 'استثناءات إدارية مرتبطة بعمل مطلوب لم يكتمل', 'value' => 'administrative_execution_exceptions'],
                    ],
                ],
                [
                    'seed_key' => 'data_quality_report.animal_missing_data_checks',
                    'title' => 'ما حالات نقص بيانات الحيوان التي يجب أن يستطيع النظام اكتشافها؟',
                    'help_text' => 'المصدر يذكر هذه الحالات صراحة باعتبارها أمثلة تؤثر على موثوقية التقارير. العمر أو المدة التي تجعل نقص الجنس أو الوزن مشكلة لا تحدد هنا؛ إذا كانت قابلة للضبط فهي Settings.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 2,
                    'report_category' => 'data_quality_check',
                    'target_entity' => 'animal_data_quality',
                    'options' => [
                        ['label' => 'حيوان موجود بالمزرعة بدون موقع حالي معروف', 'value' => 'animal_without_current_location'],
                        ['label' => 'حيوان بدون جنس بعد العمر / المرحلة التي يفترض عندها تحديد الجنس', 'value' => 'sex_missing_after_expected_stage'],
                        ['label' => 'حيوان بدون وزن حديث بعد تجاوز المدة المعتمدة', 'value' => 'stale_or_missing_recent_weight'],
                        ['label' => 'أنثى إنتاجية تنقصها بيانات أساسية لازمة للتشغيل أو التحليل', 'value' => 'productive_female_missing_required_core_data'],
                    ],
                ],
                [
                    'seed_key' => 'data_quality_report.litter_consistency_checks',
                    'title' => 'كيف يجب أن يتعامل النظام مع عدم تطابق أعداد البطن بين الولادة والرضاعة والفطام والأحداث المسجلة؟',
                    'help_text' => 'المصدر يذكر «بطن أعدادها غير متطابقة». المطلوب حسم مستوى الكشف، مع بقاء الولادة والرضاعة والنفوق والنقل والفطام أحداثًا Canonical في أقسام Workflow الخاصة بها.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 3,
                    'report_category' => 'consistency_rule',
                    'target_entity' => 'litter_data_quality',
                    'options' => [
                        ['label' => 'كشف عدم التطابق فقط وإظهار البطن المتأثرة', 'value' => 'detect_and_list_inconsistent_litters'],
                        ['label' => 'كشف عدم التطابق مع إظهار المعادلة والأحداث التي تفسر العدد المتوقع والحالي', 'value' => 'detect_with_count_reconciliation_detail'],
                        ['label' => 'لا يعد عدم التطابق مشكلة إلا إذا نتج عنه قيمة نهائية غير ممكنة بعد احتساب جميع الأحداث المسجلة', 'value' => 'flag_only_unreconciled_final_inconsistency'],
                    ],
                ],
                [
                    'seed_key' => 'data_quality_report.reproductive_sequence_checks',
                    'title' => 'ما التناقضات الأساسية في تسلسل التكاثر التي يجب أن يكشفها تقرير جودة البيانات؟',
                    'help_text' => 'المصدر يذكر الحمل بدون محاولة تلقيح معروفة كمثال واضح. الكشف يجب أن يعتمد على السجلات المتاحة دون اختراع تاريخ غير موجود للحيوانات الخارجية أو القطيع الافتتاحي.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 4,
                    'report_category' => 'sequence_check',
                    'target_entity' => 'reproductive_data_quality',
                    'options' => [
                        ['label' => 'حمل / متابعة حمل دون محاولة تلقيح معروفة في الفترة التي يفترض أن تفسره', 'value' => 'pregnancy_without_known_mating_attempt'],
                        ['label' => 'استثناء التسلسل عندما تكون بداية سجل الحيوان موثقة من مرحلة لاحقة ولا يوجد تاريخ سابق معروف', 'value' => 'respect_partial_history_start_boundary'],
                    ],
                ],
                [
                    'seed_key' => 'data_quality_report.multiple_location_check',
                    'title' => 'كيف يجب أن يعرض النظام حالة حيوان يظهر له أكثر من إشغال نشط في نفس الوقت؟',
                    'help_text' => '4.2 يفترض وجود موقع حالي واضح مشتق من حركات الإشغال. وجود الحيوان في أكثر من موقع في اللحظة نفسها يمثل تعارضًا يجب كشفه دون إنشاء حركة تصحيحية تلقائيًا من التقرير.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 5,
                    'report_category' => 'integrity_check',
                    'target_entity' => 'animal_occupancy_data_quality',
                    'options' => [
                        ['label' => 'عرض الحيوان والمواقع المتعارضة فقط', 'value' => 'show_conflicting_active_locations'],
                        ['label' => 'عرض الحيوان والمواقع مع آخر حركات التسكين / النقل التي أدت للتعارض', 'value' => 'show_conflict_with_source_movements'],
                        ['label' => 'عرض التعارض مع توجيه المستخدم إلى سجلات 4.2 لمراجعته وتصحيحه', 'value' => 'show_conflict_and_route_to_canonical_housing_records'],
                    ],
                ],
                [
                    'seed_key' => 'data_quality_report.overcapacity_check',
                    'title' => 'كيف يجب اكتشاف وعرض حالة قفص / عين تتجاوز إشغالها السعة المسموح بها؟',
                    'help_text' => 'المصدر يذكر القفص فوق السعة ضمن استثناءات الجودة. الإشغال مشتق من الحركات والسعة من بنية الموقع وقواعدها؛ التقرير يكشف التعارض ولا يعدل الإشغال أو السعة بنفسه.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 6,
                    'report_category' => 'integrity_check',
                    'target_entity' => 'housing_capacity_data_quality',
                    'options' => [
                        ['label' => 'إظهار المواقع التي تجاوزت السعة مع الإشغال والسعة الحالية', 'value' => 'show_overcapacity_sites'],
                        ['label' => 'إظهار التجاوز مع الحيوانات / الحركات التي كونت الإشغال الحالي', 'value' => 'show_overcapacity_with_occupancy_sources'],
                        ['label' => 'إظهار التجاوز فقط عندما لا يوجد Override تشغيلي موثق يفسر الحالة', 'value' => 'flag_unexplained_overcapacity_only'],
                    ],
                ],
                [
                    'seed_key' => 'data_quality_report.unexecuted_movement_decision_check',
                    'title' => 'كيف يجب أن يظهر التقرير وجود قرار / حاجة نقل لم تتحول إلى حركة نقل فعلية؟',
                    'help_text' => 'المصدر يذكر «قرار نقل لم ينفذ». القرار أو السياق الذي يطلب النقل لا يغير الموقع الحالي؛ الموقع لا يتغير إلا بحركة فعلية في 4.2. التقرير يوضح الفجوة بين القرار والتنفيذ.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 7,
                    'report_category' => 'workflow_exception',
                    'target_entity' => 'unexecuted_movement_exception',
                    'options' => [
                        ['label' => 'إظهار القرارات المفتوحة التي لا يوجد لها تنفيذ مرتبط', 'value' => 'show_open_unexecuted_movement_decisions'],
                        ['label' => 'إظهار القرار مع المهمة المرتبطة وحالة تنفيذها عند وجودها', 'value' => 'show_decision_with_related_task_status'],
                        ['label' => 'إظهار القرار مع إمكانية فتح عملية النقل Canonical في 4.2 دون تنفيذها داخل التقرير', 'value' => 'show_and_route_to_canonical_transfer_action'],
                    ],
                ],
                [
                    'seed_key' => 'data_quality_report.overdue_critical_task_check',
                    'title' => 'كيف يجب تمثيل «مهمة أساسية / حرجة متأخرة» داخل جودة البيانات والاستثناءات الإدارية دون تكرار تقرير أداء المهام؟',
                    'help_text' => '5.10 يحلل أداء المهام بصورة عامة، بينما المصدر يذكر المهمة الأساسية المتأخرة كاستثناء إداري قد يؤثر على موثوقية المسار أو البيانات. تعريف المهمة الحرجة وحدود التأخير مكانها Settings.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 8,
                    'report_category' => 'administrative_exception',
                    'target_entity' => 'critical_task_exception',
                    'options' => [
                        ['label' => 'عرض المهام الحرجة المتأخرة فقط كاستثناءات إدارية', 'value' => 'show_overdue_critical_tasks_only'],
                        ['label' => 'عرضها فقط عندما يؤدي التأخير إلى فجوة أو عدم موثوقية في بيانات المسار', 'value' => 'show_when_delay_affects_data_integrity'],
                        ['label' => 'عدم تكرارها هنا والاكتفاء بتقرير 5.10 والتنبيهات في 5.13', 'value' => 'delegate_fully_to_task_report_and_alerts'],
                    ],
                ],
                [
                    'seed_key' => 'data_quality_report.rule_driven_checks_model',
                    'title' => 'كيف يجب التعامل مع فحوص جودة البيانات التي تعتمد على قيمة قابلة للضبط مثل مدة غياب الوزن أو العمر المتوقع لتحديد الجنس؟',
                    'help_text' => 'القسم يحدد وجود الفحص ومخرجه، بينما القيمة العددية أو المرحلة التي تجعل الحالة استثناءً قد تختلف حسب التشغيل ويجب ألا تكون Hardcoded داخل التقرير.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 9,
                    'report_category' => 'integration_rule',
                    'target_entity' => 'data_quality_rule_evaluation',
                    'options' => [
                        ['label' => 'يقيّم التقرير الحالة باستخدام القاعدة / القيمة المعتمدة من Settings', 'value' => 'evaluate_using_settings_rule'],
                        ['label' => 'يستخدم التقرير قيمة ثابتة واحدة على مستوى النظام', 'value' => 'use_global_fixed_value'],
                        ['label' => 'يعرض البيانات الخام فقط ويترك الحكم للمستخدم دون قاعدة آلية', 'value' => 'show_raw_data_without_automatic_judgement'],
                    ],
                ],
                [
                    'seed_key' => 'data_quality_report.alert_relationship_model',
                    'title' => 'ما العلاقة بين مشكلة جودة البيانات المكتشفة وبين التنبيهات في 5.13؟',
                    'help_text' => 'ليس كل نقص أو تعارض بيانات بالضرورة Alert تشغيليًا بنفس الشدة. المطلوب منع إنشاء تنبيه تلقائي لكل مشكلة دون قرار، مع السماح بتحويل الحالات المهمة إلى تنبيه وفق القواعد المعتمدة.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 10,
                    'report_category' => 'integration_rule',
                    'target_entity' => 'data_quality_alert_integration',
                    'options' => [
                        ['label' => 'كل مشكلة جودة بيانات تنشئ Alert مستقلًا', 'value' => 'every_issue_creates_alert'],
                        ['label' => 'تظهر مشاكل الجودة في تقريرها فقط ولا تنشئ Alerts', 'value' => 'quality_report_only'],
                        ['label' => 'تنشئ Alert فقط للحالات التي تقرر Settings أنها تستحق تنبيهًا', 'value' => 'alert_only_when_configured_rule_requires'],
                    ],
                ],
                [
                    'seed_key' => 'data_quality_report.resolution_model',
                    'title' => 'كيف يجب أن تعتبر مشكلة جودة البيانات «محلولة» بعد تصحيح مصدرها؟',
                    'help_text' => 'التقرير ليس مصدرًا بديلًا للبيانات. التصحيح يجب أن يحدث في السجل أو الـWorkflow Canonical المناسب، ثم يعاد تقييم المشكلة بناءً على الحالة الجديدة مع الحفاظ على ما يلزم من Audit Trail حسب سياسات النظام.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 11,
                    'report_category' => 'resolution_rule',
                    'target_entity' => 'data_quality_issue',
                    'options' => [
                        ['label' => 'تختفي من القائمة النشطة تلقائيًا بعد أن يصبح المصدر متسقًا عند إعادة التقييم', 'value' => 'auto_resolve_when_source_becomes_consistent'],
                        ['label' => 'تحتاج مراجعة / إغلاقًا إداريًا صريحًا حتى بعد تصحيح المصدر', 'value' => 'manual_review_close_after_source_fix'],
                        ['label' => 'تدعم الحالتين حسب نوع الاستثناء: حل تلقائي أو مراجعة إدارية', 'value' => 'resolution_model_by_exception_type'],
                    ],
                ],
                [
                    'seed_key' => 'data_quality_report.history_model',
                    'title' => 'هل يجب الاحتفاظ بتاريخ الاستثناءات الإدارية ومشكلات جودة البيانات بعد زوالها من الحالة النشطة؟',
                    'help_text' => 'الاحتفاظ بالتاريخ يفيد في معرفة المشاكل المتكررة ومراجعة ما تم تصحيحه، بينما القيم والسجلات الأصلية تظل في مصادرها Canonical ولا تنسخ داخل تقرير الجودة.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 12,
                    'report_category' => 'history_rule',
                    'target_entity' => 'data_quality_issue',
                    'options' => [
                        ['label' => 'الاحتفاظ بسجل تاريخي للمشكلة واكتشافها وحلها', 'value' => 'retain_issue_history'],
                        ['label' => 'عرض المشاكل الحالية فقط دون سجل تاريخي مستقل', 'value' => 'current_issues_only'],
                        ['label' => 'الاحتفاظ بالتاريخ فقط لأنواع استثناءات محددة حسب أهميتها', 'value' => 'retain_history_for_selected_issue_types'],
                    ],
                ],
                [
                    'seed_key' => 'data_quality_report.drilldown_model',
                    'title' => 'إلى أي مستوى يجب أن يستطيع المستخدم النزول من مشكلة جودة البيانات إلى السجلات التي تفسرها؟',
                    'help_text' => 'الهدف أن يستطيع المستخدم فهم سبب ظهور المشكلة والرجوع إلى الحيوان أو البطن أو الموقع أو المهمة والأحداث Canonical ذات الصلة دون تصحيح صامت من داخل التقرير.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 13,
                    'report_category' => 'interaction_rule',
                    'target_entity' => 'data_quality_report',
                    'options' => [
                        ['label' => 'عرض نوع المشكلة والكيان المتأثر فقط', 'value' => 'issue_and_subject_only'],
                        ['label' => 'فتح السجلات المباشرة التي أدت إلى المشكلة', 'value' => 'drilldown_to_source_records'],
                        ['label' => 'فتح السجلات والأحداث Canonical مع توجيه المستخدم إلى مكان المراجعة / التصحيح المناسب', 'value' => 'drilldown_and_route_to_canonical_review'],
                    ],
                ],
            ];

            app(QuestionSeederSyncService::class)->sync(
                mainSectionName: 'التقارير والتحليلات والتنبيهات ومؤشرات الأداء',
                sectionName: 'جودة البيانات والاستثناءات الإدارية',
                questions: $questions,
                prune: true,
                preserveAnswers: true,
            );
        });
    }
}
