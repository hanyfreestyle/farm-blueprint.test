<?php

namespace Database\Seeders\Questions\Reports;

use App\Enums\Questionnaire\QuestionType;
use App\Services\Questionnaire\QuestionSeederSyncService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OperationalTaskPerformanceReportsQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $questions = [
                [
                    'seed_key' => 'operational_task_performance_report.primary_views',
                    'title' => 'ما العروض الرئيسية التي يجب أن يوفرها تقرير الأداء التشغيلي وتنفيذ المهام؟',
                    'help_text' => 'هذا القسم يقيس أداء تنفيذ العمل بعد تراكم سجل المهام، ولا يعيد شاشة «مهام اليوم» في 5.1 ولا قواعد توليد المهام ومواعيدها وأولوياتها الموجودة في Settings.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 1,
                    'report_category' => 'report_scope',
                    'target_entity' => 'operational_task_performance_report',
                    'options' => [
                        ['label' => 'ملخص تنفيذ المهام وحالات الإغلاق', 'value' => 'execution_summary'],
                        ['label' => 'تحليل الالتزام بالمواعيد والتأخير', 'value' => 'timeliness_analysis'],
                        ['label' => 'تحليل التأجيل والإلغاء', 'value' => 'postponement_and_cancellation'],
                        ['label' => 'تحليل الأداء حسب نوع المهمة', 'value' => 'performance_by_task_type'],
                        ['label' => 'تحليل الأداء حسب المسؤول / المنفذ', 'value' => 'performance_by_responsible_user'],
                        ['label' => 'اكتشاف أنواع المهام أو السياقات التي تتكرر فيها مشكلات التنفيذ', 'value' => 'repeated_execution_problem_patterns'],
                    ],
                ],
                [
                    'seed_key' => 'operational_task_performance_report.core_metrics',
                    'title' => 'ما المؤشرات الأساسية التي يجب أن يعرضها تقرير تنفيذ المهام؟',
                    'help_text' => 'المصدر يطلب معرفة المهام المنفذة في موعدها والمتأخرة ومن نفذها ومتوسط التأخير. يضاف هنا الفصل بين نتائج الـLifecycle المسجلة في 4.17 حتى لا تختلط المهمة المكتملة بالمتأخرة أو المؤجلة أو الملغاة.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 2,
                    'report_category' => 'report_metric',
                    'target_entity' => 'operational_task_performance_report',
                    'options' => [
                        ['label' => 'إجمالي المهام التي استحقت خلال نطاق التقرير', 'value' => 'due_task_count'],
                        ['label' => 'عدد المهام المكتملة', 'value' => 'completed_task_count'],
                        ['label' => 'عدد المهام المنفذة في موعدها', 'value' => 'on_time_completed_count'],
                        ['label' => 'عدد المهام التي اكتملت بعد موعدها', 'value' => 'late_completed_count'],
                        ['label' => 'عدد المهام المتأخرة حاليًا ولم تكتمل', 'value' => 'currently_overdue_count'],
                        ['label' => 'عدد المهام التي تعرضت للتأجيل', 'value' => 'postponed_task_count'],
                        ['label' => 'عدد المهام الملغاة', 'value' => 'cancelled_task_count'],
                        ['label' => 'نسبة إكمال المهام', 'value' => 'completion_rate'],
                        ['label' => 'نسبة التنفيذ في الموعد', 'value' => 'on_time_completion_rate'],
                        ['label' => 'متوسط مدة التأخير', 'value' => 'average_delay_duration'],
                    ],
                ],
                [
                    'seed_key' => 'operational_task_performance_report.overdue_state_boundary',
                    'title' => 'كيف يجب أن يفرق التقرير بين «مهمة متأخرة حاليًا» و«مهمة اكتملت متأخرة»؟',
                    'help_text' => 'مرور الموعد دون تنفيذ يجعل المهمة متأخرة ولا يجعلها تختفي. إذا نفذت لاحقًا فهي لم تعد مفتوحة، لكن يجب أن يظل معروفًا أنها اكتملت بعد الموعد حتى لا يفقد التقرير حقيقة التأخير التاريخي.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 3,
                    'report_category' => 'calculation_rule',
                    'target_entity' => 'operational_task_timeliness',
                    'options' => [
                        ['label' => 'مؤشران منفصلان دائمًا: متأخرة مفتوحة حاليًا + مكتملة بعد الموعد', 'value' => 'separate_open_overdue_and_late_completed'],
                        ['label' => 'مؤشر إجمالي للتأخير مع إمكانية فصل المفتوح عن المكتمل عند النزول للتفاصيل', 'value' => 'combined_delay_with_detail_split'],
                        ['label' => 'تعد المهمة متأخرة فقط أثناء بقائها مفتوحة ولا يحتفظ التقرير بتصنيف التأخير بعد إكمالها', 'value' => 'overdue_only_while_open'],
                    ],
                ],
                [
                    'seed_key' => 'operational_task_performance_report.timeliness_reference_model',
                    'title' => 'عند وجود تأجيل غيّر موعد الاستحقاق، أي موعد يجب أن يستخدمه التقرير للحكم على التنفيذ في الموعد؟',
                    'help_text' => '4.17 يحتفظ بموعد الاستحقاق الأصلي والموعد الحالي بعد التأجيل. الاعتماد على الموعد الحالي فقط قد يخفي أثر تكرار التأجيل، بينما الموعد الأصلي وحده قد يعتبر التأجيل المعتمد تأخيرًا حتى بعد الموافقة عليه.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 4,
                    'report_category' => 'calculation_rule',
                    'target_entity' => 'operational_task_timeliness',
                    'options' => [
                        ['label' => 'الحكم الأساسي مقابل موعد الاستحقاق الحالي بعد التأجيل', 'value' => 'evaluate_against_current_due_at'],
                        ['label' => 'الحكم الأساسي مقابل موعد الاستحقاق الأصلي قبل أي تأجيل', 'value' => 'evaluate_against_original_due_at'],
                        ['label' => 'عرض القياسين معًا: الالتزام بالموعد الحالي + أثر الانحراف عن الموعد الأصلي', 'value' => 'show_current_and_original_due_metrics'],
                    ],
                ],
                [
                    'seed_key' => 'operational_task_performance_report.delay_duration_model',
                    'title' => 'كيف يجب حساب مدة التأخير للمهام المتأخرة أو المكتملة بعد الموعد؟',
                    'help_text' => 'مدة التأخير يجب أن تعتمد على التوقيت الفعلي للمهمة، لا على قيمة يدخلها المستخدم. المهمة المكتملة يمكن قياس تأخيرها حتى وقت الإكمال، أما المفتوحة فيقاس تأخيرها حتى لحظة التقرير.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 5,
                    'report_category' => 'calculation_rule',
                    'target_entity' => 'operational_task_timeliness',
                    'options' => [
                        ['label' => 'المكتملة: من موعد الاستحقاق إلى وقت الإكمال، والمفتوحة: من موعد الاستحقاق إلى وقت التقرير', 'value' => 'completed_to_completion_open_to_report_time'],
                        ['label' => 'يحسب التأخير للمهام المكتملة فقط ولا يحسب مدة للمهام المفتوحة', 'value' => 'completed_tasks_only'],
                        ['label' => 'يعرض عدد المهام المتأخرة دون حساب مدة التأخير', 'value' => 'count_only_without_delay_duration'],
                    ],
                ],
                [
                    'seed_key' => 'operational_task_performance_report.denominator_model',
                    'title' => 'كيف يجب التعامل مع المهام الملغاة عند حساب نسب الإكمال والتنفيذ في الموعد؟',
                    'help_text' => 'الإلغاء نتيجة Lifecycle مختلفة عن عدم التنفيذ. إدخال المهام الملغاة في نفس المقام دون تمييز قد يجعل معدل الالتزام يبدو أسوأ حتى عندما كان الإلغاء قرارًا تشغيليًا صحيحًا وموثقًا.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 6,
                    'report_category' => 'calculation_rule',
                    'target_entity' => 'operational_task_performance_report',
                    'options' => [
                        ['label' => 'تستبعد المهام الملغاة من مقام نسبة الإكمال والتنفيذ في الموعد وتعرض كمؤشر مستقل', 'value' => 'exclude_cancelled_from_completion_denominator'],
                        ['label' => 'تدخل المهام الملغاة في المقام وتعتبر غير مكتملة', 'value' => 'include_cancelled_as_not_completed'],
                        ['label' => 'يعرض النظام النسبتين: مع استبعاد الملغى ومع احتسابه', 'value' => 'show_rates_with_and_without_cancelled'],
                    ],
                ],
                [
                    'seed_key' => 'operational_task_performance_report.postponement_analysis',
                    'title' => 'ما الذي يجب أن يظهره تحليل تأجيل المهام؟',
                    'help_text' => 'التأجيل في 4.17 يحافظ على الموعد الأصلي والحالي وتاريخ الـLifecycle. هذا التقرير يحلل أثر التأجيل ولا يحدد من يحق له التأجيل أو المدة المسموحة؛ تلك قواعد تشغيلية خارج التقرير.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 7,
                    'report_category' => 'report_metric',
                    'target_entity' => 'operational_task_postponement_report',
                    'options' => [
                        ['label' => 'عدد المهام التي تم تأجيلها مرة واحدة على الأقل', 'value' => 'tasks_postponed_at_least_once'],
                        ['label' => 'إجمالي مرات التأجيل', 'value' => 'total_postponement_events'],
                        ['label' => 'متوسط عدد مرات التأجيل للمهمة المؤجلة', 'value' => 'average_postponements_per_postponed_task'],
                        ['label' => 'إجمالي / متوسط الزمن المضاف بسبب التأجيل', 'value' => 'added_time_due_to_postponement'],
                        ['label' => 'أسباب التأجيل عند كونها مسجلة بصورة منظمة', 'value' => 'postponement_reasons'],
                        ['label' => 'المهام التي تكرر تأجيلها أكثر من مرة', 'value' => 'repeatedly_postponed_tasks'],
                    ],
                ],
                [
                    'seed_key' => 'operational_task_performance_report.cancellation_analysis',
                    'title' => 'ما الذي يجب أن يظهره تحليل إلغاء المهام؟',
                    'help_text' => 'المهمة قد تغلق بالإلغاء بسبب واضح بدل بقائها متأخرة. التقرير يجب أن يحافظ على الإلغاء كنتيجة مستقلة قابلة للتحليل دون اعتباره تنفيذًا للعمل المطلوب.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 8,
                    'report_category' => 'report_metric',
                    'target_entity' => 'operational_task_cancellation_report',
                    'options' => [
                        ['label' => 'عدد المهام الملغاة', 'value' => 'cancelled_task_count'],
                        ['label' => 'نسبة الإلغاء من المهام ذات الصلة', 'value' => 'cancellation_rate'],
                        ['label' => 'أسباب الإلغاء عند تسجيلها بصورة منظمة', 'value' => 'cancellation_reasons'],
                        ['label' => 'أنواع المهام التي يتكرر إلغاؤها', 'value' => 'task_types_with_repeated_cancellation'],
                        ['label' => 'المسؤول / المستخدم الذي سجل قرار الإلغاء عند توفره', 'value' => 'cancelled_by_user'],
                    ],
                ],
                [
                    'seed_key' => 'operational_task_performance_report.task_type_comparison',
                    'title' => 'ما المؤشرات التي يجب أن يمكن مقارنتها حسب نوع المهمة التشغيلية؟',
                    'help_text' => 'أنواع المهام معرفة في Master Data، بينما التقرير يستخدمها كبعد تحليلي لمعرفة أين يتكرر التأخير أو التأجيل أو الإلغاء. المقارنة عبر الفترات الزمنية نفسها مكانها 5.11.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 9,
                    'report_category' => 'comparison_scope',
                    'target_entity' => 'operational_task_type_performance',
                    'options' => [
                        ['label' => 'حجم / عدد المهام', 'value' => 'task_volume'],
                        ['label' => 'نسبة الإكمال', 'value' => 'completion_rate'],
                        ['label' => 'نسبة التنفيذ في الموعد', 'value' => 'on_time_completion_rate'],
                        ['label' => 'متوسط مدة التأخير', 'value' => 'average_delay_duration'],
                        ['label' => 'معدل / تكرار التأجيل', 'value' => 'postponement_frequency'],
                        ['label' => 'معدل / تكرار الإلغاء', 'value' => 'cancellation_frequency'],
                    ],
                ],
                [
                    'seed_key' => 'operational_task_performance_report.user_performance_dimension',
                    'title' => 'عند تحليل الأداء حسب المستخدم، كيف يجب التمييز بين الشخص المكلف بالمهمة والشخص الذي نفذها فعليًا؟',
                    'help_text' => '4.17 يمكن أن يحتفظ بـassigned_to وperformed_by بصورة منفصلة. دمجهما في مستخدم واحد قد يعطي تقييمًا غير دقيق إذا أعيد توزيع المهمة أو نفذها شخص غير المكلف الأصلي.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 10,
                    'report_category' => 'analysis_dimension',
                    'target_entity' => 'operational_task_user_performance',
                    'options' => [
                        ['label' => 'تحليل حسب المنفذ الفعلي فقط', 'value' => 'performed_by_only'],
                        ['label' => 'تحليل حسب المكلف الحالي / الأخير فقط', 'value' => 'assigned_to_only'],
                        ['label' => 'الاحتفاظ بالبعدَين منفصلين: المسؤول عن المهمة + المنفذ الفعلي', 'value' => 'separate_assignee_and_performer_dimensions'],
                    ],
                ],
                [
                    'seed_key' => 'operational_task_performance_report.execution_duration_analysis',
                    'title' => 'إذا كان Workflow يستخدم حالة «قيد التنفيذ» ويحفظ وقت البدء، هل يجب أن يحلل التقرير مدة التنفيذ الفعلية من بدء المهمة حتى إكمالها؟',
                    'help_text' => 'هذا المؤشر لا يعمل إلا عندما يكون started_at موجودًا بصورة موثوقة في 4.17. مدة التنفيذ تختلف عن التأخير: قد تبدأ المهمة في موعدها لكنها تستغرق وقتًا طويلًا قبل الإكمال.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 11,
                    'report_category' => 'report_metric',
                    'target_entity' => 'operational_task_execution_duration',
                    'options' => [],
                ],
                [
                    'seed_key' => 'operational_task_performance_report.repeated_problem_patterns',
                    'title' => 'ما أنماط مشكلات التنفيذ المتكررة التي يجب أن يستطيع التقرير اكتشافها؟',
                    'help_text' => 'المصدر يطلب معرفة المهام التي تتكرر فيها المشكلات. المطلوب هنا تحديد الأنماط القابلة للاكتشاف من سجل المهام نفسه دون تحويل هذا القسم إلى نظام تنبيهات؛ إنشاء التنبيه ومعايير شدته يعالج لاحقًا في 5.13 وSettings.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 12,
                    'report_category' => 'analysis_pattern',
                    'target_entity' => 'operational_task_problem_pattern',
                    'options' => [
                        ['label' => 'نوع مهمة يتكرر تنفيذه بعد الموعد', 'value' => 'repeated_late_completion_by_task_type'],
                        ['label' => 'نوع مهمة يتكرر بقاؤه متأخرًا دون تنفيذ', 'value' => 'repeated_open_overdue_by_task_type'],
                        ['label' => 'نوع مهمة يتكرر تأجيله', 'value' => 'repeated_postponement_by_task_type'],
                        ['label' => 'نوع مهمة يتكرر إلغاؤه', 'value' => 'repeated_cancellation_by_task_type'],
                        ['label' => 'سياق / حيوان / موقع يرتبط بتكرار مهام متعثرة', 'value' => 'repeated_problem_by_subject_context'],
                        ['label' => 'مستخدم / مسؤول يرتبط بتراكم مهام متأخرة بصورة متكررة', 'value' => 'repeated_overdue_by_responsible_user'],
                    ],
                ],
                [
                    'seed_key' => 'operational_task_performance_report.drilldown_model',
                    'title' => 'إلى أي مستوى يجب أن يستطيع المستخدم النزول من مؤشرات أداء المهام إلى البيانات الأصلية؟',
                    'help_text' => 'الأرقام يجب أن تظل قابلة للمراجعة من المؤشر إلى المهام التي كونته، ثم إلى Lifecycle وسياق المهمة والحدث أو العملية Canonical المرتبطة عند وجودها.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 13,
                    'report_category' => 'interaction_rule',
                    'target_entity' => 'operational_task_performance_report',
                    'options' => [
                        ['label' => 'عرض المؤشرات الإجمالية فقط', 'value' => 'aggregate_only'],
                        ['label' => 'من المؤشر إلى قائمة المهام الداخلة في الحساب', 'value' => 'drilldown_to_tasks'],
                        ['label' => 'من المؤشر إلى المهمة ثم Lifecycle والسياق والحدث / العملية Canonical المرتبطة', 'value' => 'drilldown_to_task_lifecycle_and_source_events'],
                    ],
                ],
            ];

            app(QuestionSeederSyncService::class)->sync(
                mainSectionName: 'التقارير والتحليلات والتنبيهات ومؤشرات الأداء',
                sectionName: 'تقارير الأداء التشغيلي وتنفيذ المهام',
                questions: $questions,
                prune: true,
                preserveAnswers: true,
            );
        });
    }
}
