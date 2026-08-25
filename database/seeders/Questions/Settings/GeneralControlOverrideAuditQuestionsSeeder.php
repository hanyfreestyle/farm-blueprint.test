<?php

namespace Database\Seeders\Questions\Settings;

use App\Enums\Questionnaire\QuestionType;
use App\Services\Questionnaire\QuestionSeederSyncService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GeneralControlOverrideAuditQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $questions = [
                [
                    'seed_key' => 'settings_control.enforcement_levels',
                    'title' => 'ما مستويات تطبيق القواعد التي يجب أن تدعمها منظومة الإعدادات؟',
                    'help_text' => 'المراجعة المعمارية تميز بين Information وWarning وBlock، مع احتمال وجود قيود صلبة لا تسمح بالتجاوز. المطلوب تحديد مستويات التحكم التي يمكن أن تستخدمها القواعد التشغيلية دون ربطها الآن بقاعدة بعينها.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 1,
                    'report_category' => 'enforcement_model',
                    'target_entity' => 'operational_rule',
                    'options' => [
                        ['label' => 'Information — معلومة أو تنبيه إرشادي دون منع التنفيذ', 'value' => 'information'],
                        ['label' => 'Warning — تحذير يسمح بالتنفيذ وفق سياسة التجاوز', 'value' => 'warning'],
                        ['label' => 'Block — منع التنفيذ عند عدم تحقق القاعدة', 'value' => 'block'],
                        ['label' => 'Hard Constraint — قيد صلب لا يقبل Override تشغيليًا', 'value' => 'hard_constraint'],
                    ],
                ],
                [
                    'seed_key' => 'settings_control.enforcement_assignment_model',
                    'title' => 'كيف يجب تحديد مستوى Information / Warning / Block لكل قاعدة تشغيلية؟',
                    'help_text' => 'بعض القواعد قد تكون تحذيرًا في مزرعة ومنعًا في مزرعة أخرى، بينما قيود أخرى قد تكون ثابتة بحكم سلامة البيانات أو البنية. المطلوب حسم ما إذا كان مستوى التطبيق قابلًا للضبط أم ثابتًا حسب تعريف القاعدة.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 2,
                    'report_category' => 'enforcement_rule',
                    'target_entity' => 'operational_rule_definition',
                    'options' => [
                        ['label' => 'المستوى ثابت داخل تعريف كل قاعدة ولا يغيره المستخدم', 'value' => 'fixed_by_rule_definition'],
                        ['label' => 'المستوى قابل للضبط حسب نطاق الإعدادات عندما تكون القاعدة قابلة لذلك', 'value' => 'configurable_by_settings_scope'],
                        ['label' => 'نموذج هجين: بعض القواعد ثابتة وبعضها يسمح بتغيير مستوى التطبيق', 'value' => 'hybrid_fixed_and_configurable'],
                    ],
                ],
                [
                    'seed_key' => 'settings_control.block_override_model',
                    'title' => 'أي أنواع المنع يجب أن تسمح بـOverride عند وجود سبب تشغيلي موثق؟',
                    'help_text' => 'المصدر يذكر أن بعض الحالات الواقعية قد تحتاج السماح بالتجاوز مع تسجيل السبب، بينما المراجعة المعمارية تؤكد إمكانية وجود Hard Constraints لا تسمح Override. المطلوب حسم الحد الفاصل بين المنع القابل للتجاوز والمنع النهائي.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 3,
                    'report_category' => 'override_policy',
                    'target_entity' => 'operational_rule_override',
                    'options' => [
                        ['label' => 'الـWarning فقط قابل للتجاوز، وأي Block يمنع التنفيذ نهائيًا', 'value' => 'warnings_only_overrideable'],
                        ['label' => 'بعض قواعد Block يمكن تعريفها كقابلة للـOverride، مع بقاء Hard Constraints غير قابلة للتجاوز', 'value' => 'selected_blocks_overrideable_hard_constraints_not'],
                        ['label' => 'كل قاعدة تحدد صراحة هل تسمح Override أم لا بغض النظر عن مستوى العرض', 'value' => 'overrideability_defined_per_rule'],
                    ],
                ],
                [
                    'seed_key' => 'settings_control.override_permission_model',
                    'title' => 'كيف يجب فصل صلاحية تنفيذ الإجراء عن صلاحية تجاوز القاعدة؟',
                    'help_text' => 'المراجعة المعمارية تنص صراحة على Action Permission ≠ Override Permission. المطلوب تحديد هل وجود صلاحية تنفيذ العملية يكفي للتجاوز أم أن الـOverride يحتاج صلاحية مستقلة.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 4,
                    'report_category' => 'permission_rule',
                    'target_entity' => 'operational_rule_override',
                    'options' => [
                        ['label' => 'صلاحية مستقلة للـOverride بالإضافة إلى صلاحية تنفيذ الإجراء نفسه', 'value' => 'separate_action_and_override_permissions'],
                        ['label' => 'صلاحية تنفيذ الإجراء تكفي لتجاوز أي قاعدة قابلة للتجاوز', 'value' => 'action_permission_includes_override'],
                        ['label' => 'يحدد لكل قاعدة هل تحتاج صلاحية Override مستقلة أم تكفي صلاحية الإجراء', 'value' => 'override_permission_requirement_by_rule'],
                    ],
                ],
                [
                    'seed_key' => 'settings_control.override_record_fields',
                    'title' => 'ما البيانات التي يجب الاحتفاظ بها عند تنفيذ Override لقاعدة تشغيلية؟',
                    'help_text' => 'المصدر يذكر بوضوح تسجيل السبب والمستخدم الذي وافق والتاريخ والقيمة التي تم تجاوزها. يضاف مرجع القاعدة والسجل أو العملية حتى يمكن تفسير القرار لاحقًا دون تعديل القاعدة الأصلية.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 5,
                    'report_category' => 'audit_field',
                    'target_entity' => 'operational_rule_override',
                    'options' => [
                        ['label' => 'القاعدة / الشرط الذي تم تجاوزه', 'value' => 'rule_reference'],
                        ['label' => 'القيمة الفعلية أو الحالة التي خالفت القاعدة', 'value' => 'triggering_value_or_context'],
                        ['label' => 'السبب المسجل للتجاوز', 'value' => 'override_reason'],
                        ['label' => 'المستخدم الذي نفذ أو طلب التجاوز', 'value' => 'requested_or_executed_by'],
                        ['label' => 'المستخدم الذي وافق على التجاوز عند وجود Approval', 'value' => 'approved_by'],
                        ['label' => 'تاريخ / وقت التجاوز', 'value' => 'overridden_at'],
                        ['label' => 'مرجع العملية / الحيوان / البطن / الموقع المتأثر', 'value' => 'subject_or_action_reference'],
                    ],
                ],
                [
                    'seed_key' => 'settings_control.override_approval_model',
                    'title' => 'متى يحتاج Override إلى موافقة إضافية قبل إكمال العملية؟',
                    'help_text' => 'المراجعة المعمارية تضع سبب وApproval التجاوز ضمن القرارات المفتوحة. المطلوب تحديد ما إذا كان المستخدم صاحب صلاحية الـOverride يستطيع الاعتماد مباشرة أم تحتاج بعض القواعد إلى موافقة أخرى.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 6,
                    'report_category' => 'approval_rule',
                    'target_entity' => 'operational_rule_override',
                    'options' => [
                        ['label' => 'المستخدم صاحب صلاحية الـOverride يعتمد التجاوز مباشرة دون موافقة ثانية', 'value' => 'authorized_user_self_approves'],
                        ['label' => 'كل Override يحتاج موافقة مستخدم آخر مخول قبل التنفيذ', 'value' => 'second_authorized_approval_required'],
                        ['label' => 'تحدد الحاجة للموافقة الإضافية حسب القاعدة أو مستوى خطورتها', 'value' => 'approval_requirement_by_rule'],
                    ],
                ],
                [
                    'seed_key' => 'settings_control.settings_change_approval_model',
                    'title' => 'كيف يجب اعتماد تغييرات الإعدادات التشغيلية نفسها قبل أن تصبح سارية؟',
                    'help_text' => '6.1 حسم Architecture السريان والـVersioning فقط، بينما صلاحيات واعتماد تغييرات Settings بقيت ضمن المتطلبات المفتوحة. هذا السؤال يحدد سياسة الاعتماد دون إعادة تعريف Effective Date أو Versioning.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 7,
                    'report_category' => 'approval_rule',
                    'target_entity' => 'operational_settings_change',
                    'options' => [
                        ['label' => 'المستخدم المخول بتعديل الإعداد يستطيع تفعيل التغيير مباشرة', 'value' => 'authorized_editor_can_activate_directly'],
                        ['label' => 'كل تغيير يحتاج موافقة منفصلة قبل التفعيل', 'value' => 'all_changes_require_approval'],
                        ['label' => 'التغييرات الحساسة فقط تحتاج Approval وفق تعريف الإعداد', 'value' => 'sensitive_changes_require_approval'],
                    ],
                ],
                [
                    'seed_key' => 'settings_control.sensitive_record_types',
                    'title' => 'ما أنواع التعديلات التي يجب اعتبارها حساسة وتحتاج سياسة تصحيح وتدقيق أقوى؟',
                    'help_text' => 'المصدر يذكر أمثلة مباشرة: تغيير القفص، تاريخ الميلاد، النسب، الجنس، السلالة، وسجل التلقيح. اختيارها هنا لا يغير مكان تسجيل الحدث الأصلي؛ هو يحدد شدة الحماية عند التصحيح بعد التسجيل.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 8,
                    'report_category' => 'sensitive_record_scope',
                    'target_entity' => 'sensitive_record_correction',
                    'options' => [
                        ['label' => 'الموقع / القفص المرتبط بالسجل', 'value' => 'housing_or_cage_reference'],
                        ['label' => 'تاريخ الميلاد', 'value' => 'birth_date'],
                        ['label' => 'النسب: الأب / الأم والعلاقات العائلية', 'value' => 'pedigree'],
                        ['label' => 'الجنس', 'value' => 'sex'],
                        ['label' => 'السلالة', 'value' => 'breed'],
                        ['label' => 'سجل تلقيح أو محاولة تلقيح مسجلة', 'value' => 'mating_record'],
                        ['label' => 'إعداد تشغيلي أو Rule Version ساري', 'value' => 'operational_setting_change'],
                    ],
                ],
                [
                    'seed_key' => 'settings_control.sensitive_record_correction_model',
                    'title' => 'كيف يجب تصحيح سجل حساس بعد اعتماده دون فقد التاريخ السابق؟',
                    'help_text' => 'المبدأ هو الاحتفاظ بالتاريخ وعدم محو ما كان مسجلًا وقتها. طريقة التصحيح قد تختلف حسب نوع السجل، لكن يجب أن تظل القيمة أو النسخة السابقة قابلة للمراجعة.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 9,
                    'report_category' => 'correction_rule',
                    'target_entity' => 'sensitive_record_correction',
                    'options' => [
                        ['label' => 'تعديل القيمة الحالية مع حفظ Before / After والسبب والمنفذ في Audit Trail', 'value' => 'edit_with_before_after_audit'],
                        ['label' => 'إنشاء Correction / Amendment Record جديد يحفظ القيمة القديمة دون تعديلها مباشرة', 'value' => 'append_correction_record'],
                        ['label' => 'تحدد آلية التصحيح حسب نوع السجل: تعديل مدقق أو Correction Event أو إلغاء وإعادة تسجيل', 'value' => 'correction_model_by_record_type'],
                    ],
                ],
                [
                    'seed_key' => 'settings_control.cancel_delete_policy',
                    'title' => 'كيف يجب التعامل مع السجلات التشغيلية التي ثبت أنها لم تعد صحيحة أو يجب إبطال أثرها؟',
                    'help_text' => 'التصور يطرح صراحة العمليات التي يجب إلغاؤها بدل حذفها، كما أن النظام يعتمد على Timeline تاريخي. المطلوب تحديد حدود الحذف مقابل الإلغاء أو الـVoid دون تحويل هذا السؤال إلى Delete Policy للكيانات المرجعية نفسها.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 10,
                    'report_category' => 'history_rule',
                    'target_entity' => 'operational_record',
                    'options' => [
                        ['label' => 'السجلات المعتمدة لا تحذف؛ تستخدم Cancellation / Void مع السبب والتاريخ', 'value' => 'no_delete_after_commit_use_cancel_or_void'],
                        ['label' => 'يسمح بالحذف فقط للمسودات غير المعتمدة، أما السجلات التشغيلية فتلغى أو تصحح مع الاحتفاظ بتاريخها', 'value' => 'drafts_deletable_committed_records_preserved'],
                        ['label' => 'تحدد سياسة الحذف أو الإلغاء لكل نوع سجل حسب حساسيته وتأثيره التاريخي', 'value' => 'delete_or_cancel_policy_by_record_type'],
                    ],
                ],
                [
                    'seed_key' => 'settings_control.minimum_audit_fields',
                    'title' => 'ما الحد الأدنى من البيانات التي يجب أن يسجلها Audit Trail لأي تغيير خاضع للتدقيق؟',
                    'help_text' => 'الـAudit يجب أن يسمح بفهم من قام بالتغيير ومتى وما الذي تغير ولماذا، مع الاحتفاظ بالسياق والمرجع المرتبط عند الحاجة.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 11,
                    'report_category' => 'audit_field',
                    'target_entity' => 'audit_log',
                    'options' => [
                        ['label' => 'نوع الكيان / السجل ومعرفه', 'value' => 'subject_reference'],
                        ['label' => 'نوع الإجراء: إنشاء / تعديل / تصحيح / إلغاء / Override / تغيير إعداد', 'value' => 'action_type'],
                        ['label' => 'المستخدم المنفذ', 'value' => 'performed_by'],
                        ['label' => 'التاريخ والوقت', 'value' => 'performed_at'],
                        ['label' => 'القيمة أو الحالة قبل التغيير', 'value' => 'before_value'],
                        ['label' => 'القيمة أو الحالة بعد التغيير', 'value' => 'after_value'],
                        ['label' => 'السبب أو الملاحظة عند الحاجة', 'value' => 'reason_or_note'],
                        ['label' => 'مرجع الموافقة عند وجود Approval', 'value' => 'approval_reference'],
                    ],
                ],
                [
                    'seed_key' => 'settings_control.audit_scope_model',
                    'title' => 'ما العمليات التي يجب أن تدخل ضمن Audit Trail الإلزامي؟',
                    'help_text' => 'المصدر يؤكد أن أي تغيير حساس يجب الاحتفاظ به تاريخيًا. المطلوب تحديد نطاق الـAudit الإلزامي حتى لا يقتصر على تعديل الحقول فقط ويتجاهل Overrides أو الإلغاءات أو تغييرات Settings.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 12,
                    'report_category' => 'audit_scope',
                    'target_entity' => 'audit_log',
                    'options' => [
                        ['label' => 'تعديل أو تصحيح السجلات الحساسة', 'value' => 'sensitive_record_corrections'],
                        ['label' => 'Overrides للقواعد التشغيلية', 'value' => 'rule_overrides'],
                        ['label' => 'Cancellation / Void للسجلات أو العمليات', 'value' => 'record_cancellations'],
                        ['label' => 'تغييرات الإعدادات والقواعد ونسخها', 'value' => 'settings_and_rule_changes'],
                        ['label' => 'الموافقات أو الرفض المرتبط بتغيير حساس أو Override', 'value' => 'approvals_and_rejections'],
                    ],
                ],
                [
                    'seed_key' => 'settings_control.configurable_code_generation_model',
                    'title' => 'إلى أي مدى يجب السماح بتهيئة طريقة توليد الأكواد دون تغيير قواعد الهوية والـUniqueness الثابتة للكيانات؟',
                    'help_text' => 'المراجعة المعمارية تفصل بين وجود الكود والهوية والـUnique Scope كجزء من تعريف الكيان، وبين الجزء القابل للتهيئة من صيغة التوليد عند الحاجة. هذا السؤال لا يعيد تعريف ما إذا كان لكل كيان كود أصلًا.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 13,
                    'report_category' => 'configuration_rule',
                    'target_entity' => 'code_generation_policy',
                    'options' => [
                        ['label' => 'لا توجد تهيئة عامة لصيغة الأكواد؛ تستخدم كل هوية قواعدها الثابتة', 'value' => 'fixed_code_generation_only'],
                        ['label' => 'يسمح بتهيئة الشكل غير المؤثر على الهوية مثل Prefix / Separator / Padding ضمن قيود الكيان', 'value' => 'configurable_format_with_fixed_identity_rules'],
                        ['label' => 'تحدد قابلية تهيئة صيغة الكود لكل نوع كيان على حدة مع الحفاظ على Unique Scope الخاص به', 'value' => 'entity_specific_code_format_configuration'],
                    ],
                ],
            ];

            app(QuestionSeederSyncService::class)->sync(
                mainSectionName: 'الإعدادات وقواعد التشغيل',
                sectionName: 'السياسات العامة والتحكم والتجاوز والتدقيق',
                questions: $questions,
                prune: true,
                preserveAnswers: true,
            );
        });
    }
}
