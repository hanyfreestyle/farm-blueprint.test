<?php

namespace Database\Seeders\Questions\Settings;

use App\Enums\Questionnaire\QuestionDependencyOperator;
use App\Enums\Questionnaire\QuestionType;
use App\Models\QuestionnaireQuestion;
use App\Models\QuestionnaireSection;
use App\Services\Questionnaire\QuestionSeederSyncService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskAlertSchedulingPriorityRulesQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $questions = [
                [
                    'seed_key' => 'task_rules.automation_rule_fields',
                    'title' => 'ما المكونات التي يجب أن يحتوي عليها تعريف قاعدة توليد المهمة التشغيلية؟',
                    'help_text' => 'نوع المهمة نفسه Master Data، وتنفيذها الفعلي في 4.17. المطلوب هنا تحديد ما تحتاجه قاعدة الأتمتة حتى تحول حدثًا أو موعدًا أو شرطًا إلى مهمة قابلة للحساب والإسناد.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 1,
                    'report_category' => 'task_automation_rule',
                    'target_entity' => 'task_generation_rule',
                    'options' => [
                        ['label' => 'نوع المهمة المرجعي', 'value' => 'task_type'],
                        ['label' => 'الحدث / الشرط / الجدول الذي يولد المهمة', 'value' => 'trigger_source'],
                        ['label' => 'نطاق تطبيق القاعدة', 'value' => 'scope'],
                        ['label' => 'قاعدة حساب موعد الاستحقاق', 'value' => 'due_calculation'],
                        ['label' => 'نموذج الموعد: وقت دقيق / تاريخ / فترة تنفيذ', 'value' => 'due_model'],
                        ['label' => 'الأولوية الافتراضية أو قاعدة حسابها', 'value' => 'priority_rule'],
                        ['label' => 'قاعدة الإسناد الافتراضي', 'value' => 'assignment_rule'],
                        ['label' => 'إعداد التكرار عند انطباقه', 'value' => 'recurrence_rule'],
                        ['label' => 'إعداد التأجيل عند انطباقه', 'value' => 'postponement_rule'],
                        ['label' => 'اشتراط اعتماد بعد التنفيذ عند انطباقه', 'value' => 'approval_rule'],
                        ['label' => 'الحالة: نشطة / غير نشطة', 'value' => 'is_active'],
                    ],
                ],
                [
                    'seed_key' => 'task_rules.trigger_models',
                    'title' => 'ما مصادر التوليد التي يجب أن تدعمها قواعد المهام؟',
                    'help_text' => 'المصدر يضع النموذج الأساسي: حدث → إعدادات → موعد → مهمة. توجد أيضًا مهام دورية أو مراجعات قد تنتج من شروط تشغيلية. اختر مصادر التوليد التي يحتاجها النظام دون تحويل المهمة نفسها إلى Event تشغيلي بديل.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 2,
                    'report_category' => 'task_generation_rule',
                    'target_entity' => 'task_generation_rule',
                    'options' => [
                        ['label' => 'حدث Workflow فعلي مثل تلقيح أو ولادة أو فطام', 'value' => 'workflow_event'],
                        ['label' => 'نتيجة أو حالة مشتقة من البيانات والقواعد', 'value' => 'derived_condition'],
                        ['label' => 'جدول دوري مستقل لمهمة متكررة', 'value' => 'recurring_schedule'],
                        ['label' => 'قرار / مراجعة تشغيلية تنشئ متابعة لاحقة', 'value' => 'operational_review_outcome'],
                        ['label' => 'إنشاء يدوي استثنائي عند الحاجة', 'value' => 'manual_exceptional_creation'],
                    ],
                ],
                [
                    'seed_key' => 'task_rules.manual_creation_policy',
                    'title' => 'كيف يجب التعامل مع إنشاء المهام يدويًا خارج قواعد التوليد التلقائي؟',
                    'help_text' => 'المصدر يفضل أن معظم المهام الأساسية تنتج تلقائيًا، لكنه لا يمنع وجود حاجة تشغيلية لمهمة غير ناتجة عن حدث معروف. المطلوب تحديد حدود الإنشاء اليدوي.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 3,
                    'report_category' => 'task_creation_rule',
                    'target_entity' => 'operational_task',
                    'options' => [
                        ['label' => 'يسمح بإنشاء مهمة يدوية من أنواع المهام النشطة مع تسجيل المنشئ والسياق', 'value' => 'allow_manual_from_active_task_types'],
                        ['label' => 'يسمح بالإنشاء اليدوي فقط لأنواع مهام معرفة بأنها تقبل ذلك', 'value' => 'manual_creation_by_task_configuration'],
                        ['label' => 'لا يسمح بإنشاء مهام يدوية؛ كل مهمة يجب أن تنتج من قاعدة أو حدث موثق', 'value' => 'automation_only_no_manual_tasks'],
                    ],
                ],
                [
                    'seed_key' => 'task_rules.duplicate_generation_policy',
                    'title' => 'إذا أعيد تقييم نفس الحدث أو الشرط، كيف يجب منع إنشاء نفس المهمة المفتوحة أكثر من مرة؟',
                    'help_text' => 'المهمة يجب أن تبقى مرتبطة بمصدرها الحقيقي. المطلوب تحديد قاعدة Idempotency تمنع تكرار العمل المطلوب عند إعادة الحساب أو إعادة بناء المسار، مع السماح بمهمة جديدة عندما تكون دورة أو استحقاقًا جديدًا فعلًا.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 4,
                    'report_category' => 'task_deduplication_rule',
                    'target_entity' => 'task_generation_rule',
                    'options' => [
                        ['label' => 'مهمة واحدة لكل قاعدة + مصدر + موضوع + استحقاق، ويعاد استخدام المفتوحة عند إعادة التقييم', 'value' => 'deduplicate_by_rule_source_subject_due'],
                        ['label' => 'مهمة واحدة مفتوحة لكل نوع مهمة وموضوع بغض النظر عن مصدر الاستحقاق', 'value' => 'single_open_task_per_type_and_subject'],
                        ['label' => 'ينشأ سجل مهمة جديد في كل مرة تتحقق فيها القاعدة', 'value' => 'new_task_on_every_rule_evaluation'],
                    ],
                ],
                [
                    'seed_key' => 'task_rules.due_model',
                    'title' => 'ما نماذج موعد الاستحقاق التي يجب أن تدعمها المهمة حسب نوع العمل؟',
                    'help_text' => 'المصدر يضع سؤالًا مفتوحًا: أي المهام مرتبطة بموعد دقيق وأيها بفترة زمنية. المطلوب أن تكون قاعدة الموعد قابلة للاختلاف حسب المهمة بدل إجبار كل المهام على نفس الشكل.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 5,
                    'report_category' => 'task_schedule_rule',
                    'target_entity' => 'task_schedule',
                    'options' => [
                        ['label' => 'تاريخ ووقت استحقاق دقيق', 'value' => 'exact_datetime'],
                        ['label' => 'تاريخ استحقاق دون وقت إلزامي', 'value' => 'due_date'],
                        ['label' => 'فترة تنفيذ تبدأ وتنتهي في نطاق زمني محدد', 'value' => 'execution_window'],
                    ],
                ],
                [
                    'seed_key' => 'task_rules.recurrence_policy',
                    'title' => 'كيف يجب دعم المهام المتكررة التي لا تعتمد على حدث واحد فقط؟',
                    'help_text' => 'المصدر يضع المهام التي يجب أن تتكرر كنقطة تحتاج مراجعة. هذا السؤال يحسم نموذج القاعدة دون تحديد أنواع المهام نفسها هنا لأنها موجودة في Master Data.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 6,
                    'report_category' => 'task_recurrence_rule',
                    'target_entity' => 'task_generation_rule',
                    'options' => [
                        ['label' => 'لا توجد مهام دورية مستقلة؛ كل مهمة تنشأ من حدث أو نتيجة تشغيلية', 'value' => 'no_independent_recurring_tasks'],
                        ['label' => 'تدعم قواعد دورية قابلة للضبط حسب نوع المهمة', 'value' => 'configured_recurring_task_rules'],
                    ],
                ],
                [
                    'seed_key' => 'task_rules.recurrence_anchor_model',
                    'title' => 'عند استخدام مهمة دورية، من أي نقطة يجب حساب الاستحقاق التالي؟',
                    'help_text' => 'هذا القرار يمنع انزلاق الجدول الزمني دون قصد عند التأخير. اختر هل التكرار ثابت على الجدول الأصلي أم يتحرك بناءً على آخر تنفيذ فعلي.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 7,
                    'report_category' => 'task_recurrence_rule',
                    'target_entity' => 'task_schedule',
                    'options' => [
                        ['label' => 'من الموعد المجدول السابق حتى يظل الجدول ثابتًا', 'value' => 'from_previous_scheduled_due'],
                        ['label' => 'من تاريخ / وقت الإكمال الفعلي للمهمة السابقة', 'value' => 'from_previous_completion'],
                        ['label' => 'يحدد الأساس داخل كل قاعدة مهمة دورية', 'value' => 'anchor_by_recurring_rule'],
                    ],
                ],
                [
                    'seed_key' => 'task_rules.postponement_scope_model',
                    'title' => 'كيف يجب تحديد المهام التي يسمح بتأجيلها؟',
                    'help_text' => '4.17 يحفظ Event التأجيل والموعد السابق والجديد والسبب. هنا نحسم سياسة السماح بالتأجيل نفسها، لأن بعض الأعمال قد تكون قابلة للتأجيل وبعضها قد يحتاج تنفيذًا أو إلغاءً واضحًا.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 8,
                    'report_category' => 'task_postponement_rule',
                    'target_entity' => 'task_generation_rule',
                    'options' => [
                        ['label' => 'كل المهام يمكن تأجيلها ما لم توجد قاعدة تمنع ذلك', 'value' => 'postponement_allowed_by_default'],
                        ['label' => 'السماح بالتأجيل يحدد داخل إعداد كل نوع / قاعدة مهمة', 'value' => 'postponement_by_task_configuration'],
                        ['label' => 'لا يسمح بالتأجيل؛ يتم التنفيذ أو الإلغاء / إعادة بناء المسار', 'value' => 'postponement_not_supported'],
                    ],
                ],
                [
                    'seed_key' => 'task_rules.postponement_limit_enabled',
                    'title' => 'هل يجب تحديد عدد أقصى لتأجيل نفس المهمة قبل أن تحتاج مراجعة إدارية؟',
                    'help_text' => 'المصدر يضع قواعد التأجيل كنقطة مفتوحة. إذا تم اعتماد حد، فهو لا يحذف سجل التأجيلات السابقة؛ 4.17 يحتفظ بها تاريخيًا.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 9,
                    'report_category' => 'task_postponement_rule',
                    'target_entity' => 'task_postponement_policy',
                    'options' => [],
                ],
                [
                    'seed_key' => 'task_rules.max_postponements_before_review',
                    'title' => 'بعد كم مرة تأجيل يجب أن تصبح المهمة بحاجة إلى مراجعة إدارية؟',
                    'help_text' => 'هذه القيمة تطبق فقط إذا تم اعتماد حد لعدد مرات التأجيل، ولا تعتبر المهمة مكتملة أو ملغاة تلقائيًا عند بلوغه.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 10,
                    'report_category' => 'task_postponement_threshold',
                    'target_entity' => 'task_postponement_policy',
                    'options' => [],
                ],
                [
                    'seed_key' => 'task_rules.priority_levels',
                    'title' => 'ما مستويات أولوية المهام التي يجب أن يدعمها النظام؟',
                    'help_text' => 'المصدر يقترح عادية ومهمة وعاجلة. اختر المستويات المطلوبة فعليًا؛ لا تخلط بينها وبين Information / Warning / Block الخاصة بتطبيق القواعد في 6.2 ولا مع Severity التنبيه.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 11,
                    'report_category' => 'task_priority_rule',
                    'target_entity' => 'task_priority',
                    'options' => [
                        ['label' => 'عادية', 'value' => 'normal'],
                        ['label' => 'مهمة', 'value' => 'important'],
                        ['label' => 'عاجلة', 'value' => 'urgent'],
                    ],
                ],
                [
                    'seed_key' => 'task_rules.priority_calculation_model',
                    'title' => 'كيف يجب تحديد أولوية المهمة عند إنشائها؟',
                    'help_text' => 'المصدر يفضل أن يحدد النظام الأولوية تلقائيًا قدر الإمكان. المطلوب تحديد هل تأتي من نوع المهمة وحده أم من قواعد تراعي السياق والتأخير والحالة.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 12,
                    'report_category' => 'task_priority_rule',
                    'target_entity' => 'task_priority',
                    'options' => [
                        ['label' => 'أولوية افتراضية ثابتة لكل نوع / قاعدة مهمة', 'value' => 'default_priority_per_task_rule'],
                        ['label' => 'أولوية مشتقة من نوع المهمة والسياق والموعد مع إمكانية التصعيد بالقواعد', 'value' => 'rule_based_contextual_priority'],
                        ['label' => 'يحدد المستخدم الأولوية يدويًا عند إنشاء / إسناد المهمة', 'value' => 'manual_priority'],
                    ],
                ],
                [
                    'seed_key' => 'task_rules.overdue_escalation_model',
                    'title' => 'كيف يجب أن تؤثر حالة التأخير على أولوية المهمة؟',
                    'help_text' => '4.17 يحدد أن «متأخرة» حالة مشتقة من الموعد. هنا نحسم هل التأخير يغير الأولوية تلقائيًا أم يظل مجرد حالة عرض منفصلة.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 13,
                    'report_category' => 'task_priority_rule',
                    'target_entity' => 'task_priority',
                    'options' => [
                        ['label' => 'لا يغير التأخير الأولوية؛ تظهر حالة متأخرة فقط', 'value' => 'overdue_does_not_change_priority'],
                        ['label' => 'تتصاعد الأولوية تلقائيًا وفق مدة التأخير وقاعدة المهمة', 'value' => 'priority_escalates_by_overdue_duration_and_rule'],
                        ['label' => 'تحدد كل قاعدة مهمة ما إذا كان التأخير يصعد الأولوية وكيف', 'value' => 'overdue_escalation_by_task_rule'],
                    ],
                ],
                [
                    'seed_key' => 'task_rules.assignment_model',
                    'title' => 'كيف يجب تحديد المسؤول الافتراضي عن المهمة عند توليدها تلقائيًا؟',
                    'help_text' => '4.17 يحفظ الإسناد الفعلي وتغييره. المصدر يضع توزيع المهام بين العامل والمدير كنقطة تحتاج مراجعة؛ هنا نحسم قاعدة الإسناد الابتدائي فقط.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 14,
                    'report_category' => 'task_assignment_rule',
                    'target_entity' => 'task_assignment',
                    'options' => [
                        ['label' => 'لا يوجد إسناد تلقائي؛ تبقى المهمة غير مسندة حتى يختارها أو يسندها مستخدم', 'value' => 'no_automatic_assignment'],
                        ['label' => 'يحدد مستخدم / دور / فريق افتراضي داخل كل قاعدة مهمة', 'value' => 'default_assignee_per_task_rule'],
                        ['label' => 'يحسب الإسناد من نوع المهمة والمزرعة / الموقع وسياق التشغيل', 'value' => 'rule_based_assignment_from_task_and_scope'],
                    ],
                ],
                [
                    'seed_key' => 'task_rules.approval_configuration_model',
                    'title' => 'إذا كان Lifecycle في 4.17 يدعم اعتمادًا اختياريًا بعد التنفيذ، كيف يجب تحديد المهام التي تحتاج هذا الاعتماد؟',
                    'help_text' => 'هذا السؤال لا يعيد تعريف مرحلة الاعتماد نفسها؛ يحدد فقط أين تحفظ قاعدة أن مهمة معينة تحتاج اعتمادًا قبل الإغلاق النهائي.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 15,
                    'report_category' => 'task_approval_rule',
                    'target_entity' => 'task_generation_rule',
                    'options' => [
                        ['label' => 'تحدد داخل كل قاعدة / نوع مهمة', 'value' => 'approval_requirement_per_task_rule'],
                        ['label' => 'تحدد حسب تصنيف المهمة أو مستوى أولويتها', 'value' => 'approval_requirement_by_category_or_priority'],
                        ['label' => 'لا توجد قاعدة مسبقة؛ يطلب الاعتماد يدويًا عند الحاجة لكل حالة', 'value' => 'case_by_case_manual_approval_request'],
                    ],
                ],
                [
                    'seed_key' => 'task_rules.invalidated_task_rebuild_model',
                    'title' => 'عندما يتغير المسار ويصبح موعد أو مهمة مستقبلية غير صالحين، كيف يجب إعادة بناء المهام؟',
                    'help_text' => 'المصدر يعطي مثال حمل → إجهاض: تلغى مهام الولادة غير الصالحة وتنشأ متابعة جديدة. 4.14 يحدد العناصر المتأثرة و4.17 ينفذ الإلغاء؛ هنا نحسم قاعدة الأتمتة التي تعيد حساب العمل المطلوب.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 16,
                    'report_category' => 'task_rebuild_rule',
                    'target_entity' => 'task_generation_rule',
                    'options' => [
                        ['label' => 'إلغاء / تحديث المهام غير الصالحة تلقائيًا وإنشاء المهام الجديدة من القواعد الفعالة مع حفظ روابط السبب', 'value' => 'automatic_rebuild_from_current_workflow_and_rules'],
                        ['label' => 'اقتراح خطة تحديث للمهام ويعتمدها المستخدم قبل تطبيق الإلغاء والإنشاء', 'value' => 'proposed_rebuild_requires_confirmation'],
                        ['label' => 'لا يعاد بناء المهام تلقائيًا؛ يتعامل المستخدم معها يدويًا بعد تغير المسار', 'value' => 'manual_task_rebuild'],
                    ],
                ],
                [
                    'seed_key' => 'early_warning.lead_time_model',
                    'title' => 'كيف يجب ضبط مدة التنبيه المبكر قبل المواعيد التشغيلية المدعومة في 5.13؟',
                    'help_text' => 'المصدر يذكر تنبيهًا قبل الجس والولادة والفطام والفرز بعدد أيام قابل للضبط. هذا القرار يحدد هل توجد مدة مشتركة أم مدة مستقلة لكل نوع، مع بقاء Alert Record نفسه في 5.13.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 17,
                    'report_category' => 'early_warning_rule',
                    'target_entity' => 'early_warning_alert',
                    'options' => [
                        ['label' => 'مدة واحدة مشتركة لكل التنبيهات المبكرة المدعومة', 'value' => 'shared_lead_time'],
                        ['label' => 'مدة مستقلة لكل نوع موعد / حدث', 'value' => 'separate_lead_time_by_event'],
                        ['label' => 'إعادة استخدام مهلة الاستعداد المعرفة في إعدادات المجال عندما توجد، دون قيمة Early Warning مستقلة', 'value' => 'reuse_domain_preparation_lead_time'],
                        ['label' => 'لا توجد مدة Early Warning مستقلة؛ يكفي عرض المهمة القادمة وفق نموذج 5.13', 'value' => 'no_dedicated_early_warning_lead_time'],
                    ],
                ],
                [
                    'seed_key' => 'early_warning.shared_lead_days',
                    'title' => 'كم يومًا قبل الموعد يجب أن يظهر التنبيه المبكر المشترك؟',
                    'help_text' => 'تستخدم هذه القيمة فقط عند اعتماد مدة واحدة مشتركة لكل التنبيهات المبكرة.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 18,
                    'report_category' => 'early_warning_threshold',
                    'target_entity' => 'early_warning_alert',
                    'options' => [],
                ],
                [
                    'seed_key' => 'early_warning.pregnancy_check_lead_days',
                    'title' => 'كم يومًا قبل موعد فحص الحمل / الجس يجب أن يبدأ التنبيه المبكر؟',
                    'help_text' => 'تستخدم القيمة عند اعتماد مدد مستقلة حسب الحدث. موعد الجس نفسه يحسب من قواعد 6.6؛ هنا فقط مهلة إظهار التنبيه المبكر قبله.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => false,
                    'sort_order' => 19,
                    'report_category' => 'early_warning_threshold',
                    'target_entity' => 'early_warning_alert',
                    'options' => [],
                ],
                [
                    'seed_key' => 'early_warning.expected_birth_lead_days',
                    'title' => 'كم يومًا قبل موعد / نافذة الولادة المتوقعة يجب أن يبدأ التنبيه المبكر؟',
                    'help_text' => 'هذه ليست مدة الحمل ولا موعد تجهيز بيت الولادة؛ هي فقط مهلة Alert قبل الموعد المتوقع.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => false,
                    'sort_order' => 20,
                    'report_category' => 'early_warning_threshold',
                    'target_entity' => 'early_warning_alert',
                    'options' => [],
                ],
                [
                    'seed_key' => 'early_warning.weaning_lead_days',
                    'title' => 'كم يومًا قبل موعد الفطام المتوقع يجب أن يبدأ التنبيه المبكر؟',
                    'help_text' => 'موعد الفطام وقواعد استحقاقه في 6.8؛ هذه القيمة تحدد فقط متى يبدأ Alert المبكر إذا كان له توقيت مستقل.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => false,
                    'sort_order' => 21,
                    'report_category' => 'early_warning_threshold',
                    'target_entity' => 'early_warning_alert',
                    'options' => [],
                ],
                [
                    'seed_key' => 'early_warning.sorting_lead_days',
                    'title' => 'كم يومًا قبل موعد الفرز / التقييم القادم يجب أن يبدأ التنبيه المبكر؟',
                    'help_text' => 'مراحل الفرز ومواعيدها في 6.9؛ هنا فقط مهلة Early Warning قبل الاستحقاق.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => false,
                    'sort_order' => 22,
                    'report_category' => 'early_warning_threshold',
                    'target_entity' => 'early_warning_alert',
                    'options' => [],
                ],
                [
                    'seed_key' => 'alert_rules.threshold_reference_model',
                    'title' => 'كيف يجب أن تحصل قاعدة التنبيه على الـThreshold أو الحد الذي تقارن به البيانات؟',
                    'help_text' => 'الحدود الخاصة بالمجالات مثل النمو والنفوق والعزل قد تكون معرفة أصلًا في Settings 6.9–6.11. المطلوب منع تكرار نفس Threshold في أكثر من مكان مع السماح بتنبيه يحتاج حدًا خاصًا عند الضرورة.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 23,
                    'report_category' => 'alert_threshold_rule',
                    'target_entity' => 'alert_detection_rule',
                    'options' => [
                        ['label' => 'تستخدم قاعدة التنبيه Threshold المجال الأصلي دائمًا ولا تحفظ قيمة مستقلة', 'value' => 'reuse_domain_threshold_only'],
                        ['label' => 'يمكن لقاعدة التنبيه أن تشير إلى Threshold المجال أو تعرف حدًا خاصًا بها عند الحاجة', 'value' => 'domain_threshold_or_alert_specific_threshold'],
                        ['label' => 'كل Alert Rule يحتفظ بThreshold مستقل حتى لو كان مشابهًا لقاعدة المجال', 'value' => 'independent_threshold_per_alert_rule'],
                    ],
                ],
                [
                    'seed_key' => 'alert_rules.severity_levels',
                    'title' => 'ما مستويات شدة التنبيه التي يجب أن يدعمها النظام؟',
                    'help_text' => 'المصدر يقترح معلومة وتحذير وعاجل حتى لا تكون كل التنبيهات بنفس الأهمية. هذه الشدة تخص Alert وليست Task Priority ولا Information / Warning / Block الخاصة بإنفاذ قواعد 6.2.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 24,
                    'report_category' => 'alert_severity_rule',
                    'target_entity' => 'alert_severity',
                    'options' => [
                        ['label' => 'معلومة', 'value' => 'info'],
                        ['label' => 'تحذير', 'value' => 'warning'],
                        ['label' => 'عاجل', 'value' => 'urgent'],
                    ],
                ],
                [
                    'seed_key' => 'alert_rules.severity_assignment_model',
                    'title' => 'كيف يجب تحديد شدة التنبيه عند اكتشاف الحالة؟',
                    'help_text' => 'الهدف أن تكون الشدة ناتجة من قاعدة واضحة، لا اختيارًا عشوائيًا بعد ظهور التنبيه. يمكن أن تكون ثابتة حسب نوع التنبيه أو مشتقة من مقدار التجاوز ومدة استمرار الحالة.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 25,
                    'report_category' => 'alert_severity_rule',
                    'target_entity' => 'alert_detection_rule',
                    'options' => [
                        ['label' => 'شدة افتراضية ثابتة لكل Alert Rule', 'value' => 'fixed_severity_per_alert_rule'],
                        ['label' => 'الشدة مشتقة من مقدار تجاوز الحد أو مدة استمرار الحالة وفق القاعدة', 'value' => 'severity_derived_from_deviation_or_duration'],
                        ['label' => 'يدعم النوعان ويحدد كل Alert Rule طريقة حساب الشدة', 'value' => 'severity_model_by_alert_rule'],
                    ],
                ],
                [
                    'seed_key' => 'alert_rules.immediate_surface_levels',
                    'title' => 'أي مستويات التنبيه يجب أن تظهر فورًا للمستخدم خارج العرض العادي لسجل التنبيهات؟',
                    'help_text' => 'المصدر يضع «التنبيهات التي تستحق الظهور فورًا» كنقطة تحتاج مراجعة. المقصود أولوية الظهور داخل النظام، وليس اختيار قناة خارجية مثل SMS أو WhatsApp التي لم يعتمدها المصدر.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 26,
                    'report_category' => 'alert_visibility_rule',
                    'target_entity' => 'alert_severity',
                    'options' => [
                        ['label' => 'معلومة', 'value' => 'info'],
                        ['label' => 'تحذير', 'value' => 'warning'],
                        ['label' => 'عاجل', 'value' => 'urgent'],
                    ],
                ],
                [
                    'seed_key' => 'alert_rules.recheck_triggers',
                    'title' => 'متى يجب إعادة تقييم التنبيه النشط لمعرفة هل ما زال الشرط قائمًا؟',
                    'help_text' => '5.13 يحتفظ بـlast_evaluated_at ويفصل بين المعالجة وزوال الشرط. هنا نحدد ما الذي يعيد تشغيل قاعدة الكشف على Alert قائم.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 27,
                    'report_category' => 'alert_recheck_rule',
                    'target_entity' => 'alert_detection_rule',
                    'options' => [
                        ['label' => 'عند تغير البيانات / حدوث Event قد يؤثر على الشرط', 'value' => 'recheck_on_relevant_data_change'],
                        ['label' => 'إعادة تقييم دورية بفاصل زمني', 'value' => 'periodic_recheck'],
                        ['label' => 'إعادة تقييم عند فتح / مراجعة التنبيه يدويًا', 'value' => 'recheck_on_manual_review'],
                    ],
                ],
                [
                    'seed_key' => 'alert_rules.periodic_recheck_interval_hours',
                    'title' => 'كل كم ساعة يجب إعادة تقييم التنبيهات النشطة عند اعتماد المراجعة الدورية؟',
                    'help_text' => 'هذه القيمة لا تنشئ Alert جديدًا في كل مرة؛ 5.13 يحسم Deduplication ويحتفظ بسجل التنبيه النشط.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 28,
                    'report_category' => 'alert_recheck_interval',
                    'target_entity' => 'alert_detection_rule',
                    'options' => [],
                ],
                [
                    'seed_key' => 'alert_rules.persistent_condition_escalation_model',
                    'title' => 'إذا استمرت الحالة غير الطبيعية أو ازدادت سوءًا بينما Alert نفسه ما زال نشطًا، كيف يجب التعامل مع الشدة؟',
                    'help_text' => '5.13 يمنع التكرار غير الضروري للتنبيه النشط. هنا نحسم هل استمرار الشرط يحدث القيمة فقط أم يمكن أن يصعد Severity وفق قاعدة واضحة.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 29,
                    'report_category' => 'alert_escalation_rule',
                    'target_entity' => 'alert_detection_rule',
                    'options' => [
                        ['label' => 'تظل الشدة الأصلية ثابتة ويحدث فقط السياق / القيمة الحالية', 'value' => 'keep_original_severity'],
                        ['label' => 'يمكن تصعيد الشدة تلقائيًا حسب مدة الاستمرار أو مقدار التجاوز', 'value' => 'automatic_severity_escalation'],
                        ['label' => 'تحدد كل Alert Rule سياسة التصعيد الخاصة بها', 'value' => 'escalation_policy_by_alert_rule'],
                    ],
                ],
                [
                    'seed_key' => 'alert_rules.task_creation_enabled',
                    'title' => 'هل يجب أن تسمح بعض قواعد التنبيه بإنشاء مهمة تشغيلية تلقائيًا للمراجعة أو الإجراء؟',
                    'help_text' => 'Alert لا يساوي Task. المصدر يضع المسار: تنبيه → مراجعة → إجراء، و5.13 يسمح بربط مهمة عند الحاجة. هذا السؤال يحسم هل يوجد تحويل آلي من بعض Alerts إلى Tasks مع بقاء السجلين منفصلين ومترابطين.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 30,
                    'report_category' => 'alert_task_integration_rule',
                    'target_entity' => 'alert_detection_rule',
                    'options' => [],
                ],
                [
                    'seed_key' => 'alert_rules.task_creation_scope_model',
                    'title' => 'عند السماح بتحويل التنبيه إلى مهمة تلقائيًا، ما نطاق القواعد التي تستخدم ذلك؟',
                    'help_text' => 'المطلوب ألا تتحول كل معلومة إلى مهمة تلقائيًا فتتكرر الضوضاء. يمكن تحديد التحويل فقط للقواعد التي تحتاج إجراءً واضحًا.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 31,
                    'report_category' => 'alert_task_integration_rule',
                    'target_entity' => 'alert_detection_rule',
                    'options' => [
                        ['label' => 'فقط Alert Rules المحددة صراحة بأنها تنشئ مهمة', 'value' => 'selected_alert_rules_only'],
                        ['label' => 'كل Alert مصنف بأنه يحتاج إجراءً ينشئ مهمة تلقائيًا', 'value' => 'all_actionable_alerts'],
                    ],
                ],
                [
                    'seed_key' => 'alert_rules.task_mapping_model',
                    'title' => 'كيف يجب تحديد نوع المهمة التي ينشئها Alert Rule؟',
                    'help_text' => 'نوع المهمة يجب أن يأتي من Master Data، والتنفيذ في 4.17. المطلوب هنا فقط ربط قاعدة التنبيه بالمهمة المناسبة دون إنشاء نوع مهمة جديد داخل Alert.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 32,
                    'report_category' => 'alert_task_integration_rule',
                    'target_entity' => 'alert_detection_rule',
                    'options' => [
                        ['label' => 'كل Alert Rule يحدد نوع المهمة وقاعدة موعدها عند إنشاء المهمة', 'value' => 'task_type_and_due_rule_per_alert_rule'],
                        ['label' => 'استخدام نوع مهمة عام «مراجعة تنبيه» لكل التنبيهات ثم يحدد الإجراء بعد المراجعة', 'value' => 'generic_alert_review_task'],
                        ['label' => 'يحدد النوع حسب فئة التنبيه من إعدادات مشتركة', 'value' => 'task_type_by_alert_category'],
                    ],
                ],
                [
                    'seed_key' => 'alert_rules.task_priority_source_model',
                    'title' => 'إذا أنشأ التنبيه مهمة تلقائيًا، كيف يجب تحديد أولوية المهمة الناتجة؟',
                    'help_text' => 'Alert Severity وTask Priority مفهومان منفصلان. هذا السؤال يحسم هل المهمة تستخدم أولوية قاعدة المهمة العادية أم توجد علاقة تحويل من شدة التنبيه.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 33,
                    'report_category' => 'alert_task_integration_rule',
                    'target_entity' => 'task_priority',
                    'options' => [
                        ['label' => 'تستخدم أولوية Task Rule المرتبطة كما هي', 'value' => 'use_task_rule_priority'],
                        ['label' => 'تشتق أولوية المهمة من Severity التنبيه وفق Mapping قابل للضبط', 'value' => 'map_alert_severity_to_task_priority'],
                        ['label' => 'تستخدم Task Rule كأساس ويمكن رفع الأولوية إذا كانت شدة التنبيه أعلى', 'value' => 'task_priority_with_alert_severity_escalation'],
                    ],
                ],
            ];

            app(QuestionSeederSyncService::class)->sync(
                mainSectionName: 'الإعدادات وقواعد التشغيل',
                sectionName: 'قواعد المهام والتنبيهات والمواعيد والأولويات',
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
            ['task_rules.recurrence_policy', 'task_rules.recurrence_anchor_model', QuestionDependencyOperator::EQUALS, 'configured_recurring_task_rules'],
            ['task_rules.postponement_limit_enabled', 'task_rules.max_postponements_before_review', QuestionDependencyOperator::EQUALS, '1'],
            ['early_warning.lead_time_model', 'early_warning.shared_lead_days', QuestionDependencyOperator::EQUALS, 'shared_lead_time'],
            ['early_warning.lead_time_model', 'early_warning.pregnancy_check_lead_days', QuestionDependencyOperator::EQUALS, 'separate_lead_time_by_event'],
            ['early_warning.lead_time_model', 'early_warning.expected_birth_lead_days', QuestionDependencyOperator::EQUALS, 'separate_lead_time_by_event'],
            ['early_warning.lead_time_model', 'early_warning.weaning_lead_days', QuestionDependencyOperator::EQUALS, 'separate_lead_time_by_event'],
            ['early_warning.lead_time_model', 'early_warning.sorting_lead_days', QuestionDependencyOperator::EQUALS, 'separate_lead_time_by_event'],
            ['alert_rules.recheck_triggers', 'alert_rules.periodic_recheck_interval_hours', QuestionDependencyOperator::CONTAINS, 'periodic_recheck'],
            ['alert_rules.task_creation_enabled', 'alert_rules.task_creation_scope_model', QuestionDependencyOperator::EQUALS, '1'],
            ['alert_rules.task_creation_enabled', 'alert_rules.task_mapping_model', QuestionDependencyOperator::EQUALS, '1'],
            ['alert_rules.task_creation_enabled', 'alert_rules.task_priority_source_model', QuestionDependencyOperator::EQUALS, '1'],
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
            ->where('name', 'قواعد المهام والتنبيهات والمواعيد والأولويات')
            ->first();
    }
}
