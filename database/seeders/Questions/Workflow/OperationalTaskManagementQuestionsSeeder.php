<?php

namespace Database\Seeders\Questions\Workflow;

use App\Enums\Questionnaire\QuestionType;
use App\Services\Questionnaire\QuestionSeederSyncService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OperationalTaskManagementQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $questions = [
                [
                    'seed_key' => 'operational_task.record_fields',
                    'title' => 'ما البيانات التي يجب أن يحتفظ بها سجل المهمة التشغيلية بعد إنشائها؟',
                    'help_text' => 'المصدر يذكر نوع المهمة وموعد الاستحقاق والمزرعة والسياق المرتبط والأولوية والحالة، بينما هذا القسم يهتم أيضًا بمن نفذ المهمة ونتيجة التنفيذ. قواعد إنشاء المهمة وحساب الموعد والأولوية نفسها تبقى في Settings.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 1,
                    'report_category' => 'field',
                    'target_entity' => 'operational_task',
                    'options' => [
                        ['label' => 'نوع المهمة', 'value' => 'task_type'],
                        ['label' => 'المزرعة', 'value' => 'farm'],
                        ['label' => 'تاريخ / وقت الاستحقاق الأصلي', 'value' => 'original_due_at'],
                        ['label' => 'تاريخ / وقت الاستحقاق الحالي بعد أي تأجيل', 'value' => 'current_due_at'],
                        ['label' => 'مرجع الحيوان أو البطن أو الموقع أو السياق التشغيلي المرتبط', 'value' => 'subject_reference'],
                        ['label' => 'مرجع الحدث / العملية التي أدت إلى إنشاء المهمة عند وجودها', 'value' => 'source_event_reference'],
                        ['label' => 'الأولوية المحسوبة / المعتمدة', 'value' => 'priority'],
                        ['label' => 'الحالة الحالية للمهمة', 'value' => 'current_status'],
                        ['label' => 'المسؤول / المكلف الحالي عند وجوده', 'value' => 'assigned_to'],
                        ['label' => 'المستخدم الذي نفذ المهمة فعليًا', 'value' => 'performed_by'],
                        ['label' => 'تاريخ / وقت بدء التنفيذ عند استخدام حالة قيد التنفيذ', 'value' => 'started_at'],
                        ['label' => 'تاريخ / وقت الإكمال أو الإغلاق', 'value' => 'completed_or_closed_at'],
                        ['label' => 'مرجع العملية / الحدث الناتج من تنفيذ المهمة', 'value' => 'result_event_reference'],
                        ['label' => 'نتيجة أو ملاحظة التنفيذ عند الحاجة', 'value' => 'execution_result_or_notes'],
                    ],
                ],
                [
                    'seed_key' => 'operational_task.context_link_model',
                    'title' => 'كيف يجب ربط المهمة بالسياق التشغيلي الذي تخصه دون تكرار بيانات الكيانات الأصلية؟',
                    'help_text' => 'المهام قد تخص حيوانًا أو بطنًا أو موقع إيواء أو عملية تشغيلية. المطلوب الحفاظ على مرجع للسياق الأصلي حتى يمكن فتحه وتنفيذ الإجراء في قسمه Canonical بدل نسخ بياناته داخل المهمة.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 2,
                    'report_category' => 'relationship_rule',
                    'target_entity' => 'operational_task_context',
                    'options' => [
                        ['label' => 'مرجع Subject عام للمورد الأساسي مع مرجع اختياري للحدث / العملية المصدر', 'value' => 'primary_subject_with_optional_source_event'],
                        ['label' => 'عدة مراجع مباشرة يمكن أن تربط المهمة بأكثر من كيان أو حدث مرتبط', 'value' => 'multiple_direct_context_references'],
                        ['label' => 'تعتمد المهمة على وصف نصي للسياق دون علاقات مباشرة', 'value' => 'text_context_without_direct_relations'],
                    ],
                ],
                [
                    'seed_key' => 'operational_task.lifecycle_model',
                    'title' => 'كيف يجب إدارة Lifecycle المهمة عند التنفيذ أو التأجيل أو الإلغاء أو الإغلاق؟',
                    'help_text' => 'المصدر يطلب الحفاظ على تاريخ ما حدث للمهمة وعدم استبدال الماضي بمجرد آخر حالة، خصوصًا عند التأجيل. المطلوب حسم نموذج التاريخ التشغيلي للمهمة.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 3,
                    'report_category' => 'history_rule',
                    'target_entity' => 'operational_task_lifecycle',
                    'options' => [
                        ['label' => 'كل انتقال Lifecycle يسجل كحدث تاريخي مع الاحتفاظ بالحالة الحالية كمعلومة مشتقة / محدثة', 'value' => 'lifecycle_events_with_current_state'],
                        ['label' => 'يحفظ سجل المهمة الحالة الحالية فقط مع Audit Trail عام للتغييرات', 'value' => 'current_state_with_generic_audit'],
                        ['label' => 'يحفظ آخر حالة فقط دون تاريخ انتقالات مستقل', 'value' => 'current_state_only'],
                    ],
                ],
                [
                    'seed_key' => 'operational_task.schedule_state_model',
                    'title' => 'كيف يجب التعامل مع حالات «قادمة / مستحقة اليوم / متأخرة» بالنسبة لموعد المهمة؟',
                    'help_text' => 'المصدر ينص على أن المهمة التي يتجاوز موعدها دون تنفيذ تصبح متأخرة ولا تختفي. قواعد حساب الموعد والتنبيه في Settings؛ هنا نحسم هل هذه الحالات مشتقة من الموعد أم انتقالات تحفظ يدويًا.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 4,
                    'report_category' => 'derivation_rule',
                    'target_entity' => 'operational_task_lifecycle',
                    'options' => [
                        ['label' => 'قادمة / مستحقة / متأخرة حالات مشتقة تلقائيًا من الموعد والحالة التنفيذية ولا تعدل يدويًا', 'value' => 'derive_schedule_state_from_due_at_and_execution_state'],
                        ['label' => 'يسجل النظام انتقالًا تاريخيًا تلقائيًا عند تحول المهمة إلى مستحقة أو متأخرة', 'value' => 'automatic_persisted_schedule_state_transitions'],
                        ['label' => 'يغير المستخدم حالة المهمة يدويًا بين قادمة ومستحقة ومتأخرة', 'value' => 'manual_schedule_state_transitions'],
                    ],
                ],
                [
                    'seed_key' => 'operational_task.in_progress_model',
                    'title' => 'هل نحتاج حالة «قيد التنفيذ» مستقلة بين فتح المهمة وإكمال العملية المرتبطة بها؟',
                    'help_text' => 'المصدر يذكر «قيد التنفيذ إذا احتجناها». المطلوب حسم هل بدء العمل نفسه حدث Lifecycle مستقل يفيد في معرفة المهمة التي بدأ تنفيذها ولم تنته بعد.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 5,
                    'report_category' => 'lifecycle_rule',
                    'target_entity' => 'operational_task_lifecycle',
                    'options' => [
                        ['label' => 'نعم، بدء التنفيذ Action مستقل يسجل الوقت والمنفذ ثم تنتهي المهمة لاحقًا', 'value' => 'explicit_in_progress_transition'],
                        ['label' => 'لا، تنتقل المهمة مباشرة من مفتوحة / مستحقة إلى مكتملة أو ملغاة أو مؤجلة', 'value' => 'no_in_progress_state'],
                        ['label' => 'تستخدم حالة قيد التنفيذ فقط لأنواع مهام محددة حسب إعدادات المهمة', 'value' => 'in_progress_by_task_configuration'],
                    ],
                ],
                [
                    'seed_key' => 'operational_task.execution_routing_model',
                    'title' => 'عند ضغط المستخدم «تنفيذ المهمة»، كيف يجب الوصول إلى العملية التشغيلية الفعلية المرتبطة بها؟',
                    'help_text' => 'المصدر يفضل أن تقود المهمة مباشرة إلى شاشة العملية مثل فحص الحمل أو الوزن أو النقل، بدل مطالبة المستخدم بفتح المهمة ثم البحث عن العملية منفصلًا.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 6,
                    'report_category' => 'integration_rule',
                    'target_entity' => 'operational_task_execution',
                    'options' => [
                        ['label' => 'تفتح المهمة Action / Form الـWorkflow Canonical المناسب مع تمرير السياق المرتبط تلقائيًا', 'value' => 'route_to_canonical_workflow_action_with_context'],
                        ['label' => 'تفتح صفحة المهمة فقط ومنها يختار المستخدم العملية المطلوبة يدويًا', 'value' => 'open_task_then_choose_action'],
                        ['label' => 'تنفذ المهمة داخل نموذج عام مستقل دون فتح الـWorkflow الأصلي', 'value' => 'generic_task_execution_form'],
                    ],
                ],
                [
                    'seed_key' => 'operational_task.domain_completion_model',
                    'title' => 'عندما يسجل المستخدم العملية Canonical التي كانت المهمة تطلبها، كيف يجب إكمال المهمة المرتبطة؟',
                    'help_text' => 'المصدر يقرر مبدأ «المهمة تقود إلى العملية، والعملية هي التي تكمل المهمة». المطلوب حسم طريقة الربط التقني والتاريخي بين النتيجة والمهمة.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 7,
                    'report_category' => 'integration_rule',
                    'target_entity' => 'operational_task_execution',
                    'options' => [
                        ['label' => 'إكمال العملية Canonical يغلق المهمة تلقائيًا ويربطها بالحدث الناتج والمنفذ ووقت التنفيذ', 'value' => 'canonical_event_auto_completes_and_links_task'],
                        ['label' => 'إكمال العملية يضع المهمة بانتظار تأكيد إغلاقها يدويًا', 'value' => 'canonical_event_requires_manual_close_confirmation'],
                        ['label' => 'العملية والمهمة مستقلتان ويغلق المستخدم المهمة يدويًا دون علاقة مباشرة بالحدث', 'value' => 'independent_manual_task_close'],
                    ],
                ],
                [
                    'seed_key' => 'operational_task.manual_completion_model',
                    'title' => 'كيف يجب إكمال المهام التي لا ينتج عنها Event تشغيلي Canonical مستقل؟',
                    'help_text' => 'بعض المهام قد تكون مراجعة أو متابعة ولا تنشئ حركة مثل الوزن أو النقل. المطلوب دعم إكمالها دون اختراع Domain Event غير موجود، مع الاحتفاظ بنتيجة التنفيذ عند الحاجة.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 8,
                    'report_category' => 'workflow_rule',
                    'target_entity' => 'operational_task_execution',
                    'options' => [
                        ['label' => 'Action إكمال مباشر للمهمة مع نتيجة / ملاحظة اختيارية وسجل المنفذ والوقت', 'value' => 'direct_task_completion_with_optional_result'],
                        ['label' => 'Action إكمال مباشر مع نتيجة / ملاحظة إلزامية', 'value' => 'direct_task_completion_with_required_result'],
                        ['label' => 'لا يسمح بإكمال مهمة دون ربطها بحدث تشغيلي مستقل', 'value' => 'require_domain_event_for_completion'],
                    ],
                ],
                [
                    'seed_key' => 'operational_task.postponement_model',
                    'title' => 'كيف يجب تسجيل تأجيل المهمة مع الحفاظ على موعدها الأصلي؟',
                    'help_text' => 'المصدر ينص على ألا يتم تغيير التاريخ ببساطة؛ بل يسجل التأجيل والموعد الجديد وسببه مع بقاء الاستحقاق السابق في التاريخ.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 9,
                    'report_category' => 'history_rule',
                    'target_entity' => 'operational_task_postponement',
                    'options' => [
                        ['label' => 'كل تأجيل Event مستقل يحفظ الموعد السابق والجديد والسبب والمنفذ والتاريخ ويمكن تكراره أكثر من مرة', 'value' => 'append_only_postponement_events'],
                        ['label' => 'يحدث الموعد الحالي داخل المهمة مع الاحتفاظ بأول موعد أصلي وآخر سبب تأجيل فقط', 'value' => 'update_current_due_keep_original_and_latest_reason'],
                        ['label' => 'يستبدل موعد الاستحقاق القديم بالموعد الجديد دون سجل تأجيل مستقل', 'value' => 'replace_due_at_without_postponement_history'],
                    ],
                ],
                [
                    'seed_key' => 'operational_task.cancellation_model',
                    'title' => 'كيف يجب تسجيل إلغاء مهمة لم تعد صالحة أو لم يعد مطلوبًا تنفيذها؟',
                    'help_text' => 'الحالات الاستثنائية أو تغير المسار قد يجعل مهمة مستقبلية غير صالحة. 4.14 يحدد أن المهمة تأثرت، بينما 4.17 ينفذ Lifecycle الإلغاء نفسه مع الحفاظ على السبب والتاريخ.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 10,
                    'report_category' => 'history_rule',
                    'target_entity' => 'operational_task_cancellation',
                    'options' => [
                        ['label' => 'Cancellation Event يحفظ السبب والمنفذ والوقت ومرجع الحدث / القرار المسبب عند وجوده', 'value' => 'cancellation_event_with_reason_actor_time_and_source'],
                        ['label' => 'تغيير الحالة إلى ملغاة مع سبب اختياري دون سجل Lifecycle مستقل', 'value' => 'cancel_status_with_optional_reason'],
                        ['label' => 'حذف المهمة إذا لم تعد مطلوبة', 'value' => 'delete_obsolete_task'],
                    ],
                ],
                [
                    'seed_key' => 'operational_task.assignment_model',
                    'title' => 'كيف يجب إسناد المسؤولية الفعلية عن تنفيذ المهمة داخل التشغيل اليومي؟',
                    'help_text' => 'المصدر يضع توزيع المهام بين العامل والمدير كنقطة تحتاج مراجعة. قواعد الإسناد التلقائي إن وجدت يمكن أن تكون في Settings، أما هنا فنحسم نموذج المسؤولية الذي يستطيع Workflow تسجيله وتغييره.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 11,
                    'report_category' => 'responsibility_rule',
                    'target_entity' => 'operational_task_assignment',
                    'options' => [
                        ['label' => 'مستخدم واحد مسؤول عن المهمة ويمكن إعادة الإسناد مع حفظ تاريخ التغييرات', 'value' => 'single_user_assignment_with_history'],
                        ['label' => 'يمكن إسناد المهمة إلى مستخدم أو دور / فريق ثم يسجل المنفذ الفعلي عند التنفيذ', 'value' => 'user_or_role_assignment_with_actual_performer'],
                        ['label' => 'لا يوجد إسناد مسبق؛ يسجل فقط المستخدم الذي نفذ المهمة فعليًا', 'value' => 'no_preassignment_record_actual_performer_only'],
                    ],
                ],
                [
                    'seed_key' => 'operational_task.approval_model',
                    'title' => 'هل تحتاج بعض المهام إلى اعتماد بعد تنفيذها قبل اعتبارها مغلقة نهائيًا؟',
                    'help_text' => 'المرجع يضع اعتماد بعض المهام بعد التنفيذ كنقطة تحتاج مراجعة. هذا السؤال يحسم وجود مرحلة اعتماد داخل Lifecycle فقط؛ تحديد أي أنواع المهام تحتاجها ومن يملك الاعتماد يكون ضمن Settings والصلاحيات.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 12,
                    'report_category' => 'approval_rule',
                    'target_entity' => 'operational_task_lifecycle',
                    'options' => [
                        ['label' => 'لا توجد مرحلة اعتماد؛ التنفيذ الصحيح يغلق المهمة مباشرة', 'value' => 'no_post_execution_approval'],
                        ['label' => 'يدعم النظام مرحلة انتظار اعتماد لبعض أنواع المهام وفق الإعدادات', 'value' => 'optional_approval_by_task_configuration'],
                        ['label' => 'كل المهام تحتاج اعتماد مستخدم آخر بعد التنفيذ', 'value' => 'approval_required_for_all_tasks'],
                    ],
                ],
            ];

            app(QuestionSeederSyncService::class)->sync(
                mainSectionName: 'الحركات ودورة التشغيل الفعلية',
                sectionName: 'تنفيذ وإدارة المهام التشغيلية',
                questions: $questions,
                prune: true,
                preserveAnswers: true,
            );
        });
    }
}
