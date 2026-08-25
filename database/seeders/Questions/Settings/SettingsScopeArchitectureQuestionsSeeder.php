<?php

namespace Database\Seeders\Questions\Settings;

use App\Enums\Questionnaire\QuestionType;
use App\Services\Questionnaire\QuestionSeederSyncService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsScopeArchitectureQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $questions = [
                [
                    'seed_key' => 'settings_architecture.supported_scopes',
                    'title' => 'على أي نطاقات يجب أن يستطيع النظام تطبيق إعدادات التشغيل؟',
                    'help_text' => 'التصور يقرر أن لكل مزرعة إعدادات تشغيل وإنتاج خاصة بها، بينما المراجعة المعمارية تترك System / Farm / Barn / Profile Scope أسئلة مفتوحة. المطلوب تحديد النطاقات التي تحتاجها المنظومة دون افتراض أن كل إعداد يجب أن يعمل على كل نطاق.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 1,
                    'report_category' => 'settings_scope',
                    'target_entity' => 'operational_settings',
                    'options' => [
                        ['label' => 'إعدادات افتراضية عامة على مستوى النظام', 'value' => 'system'],
                        ['label' => 'إعدادات خاصة بكل مزرعة', 'value' => 'farm'],
                        ['label' => 'إعدادات خاصة بعنبر عند الحاجة', 'value' => 'barn'],
                        ['label' => 'Operational Settings Profile قابل للإسناد إلى نطاق تشغيل', 'value' => 'operational_settings_profile'],
                    ],
                ],
                [
                    'seed_key' => 'settings_architecture.configuration_model',
                    'title' => 'ما النموذج الأساسي الأنسب لتنظيم الإعدادات بين القيم المباشرة والـOperational Settings Profiles؟',
                    'help_text' => 'الهدف حسم Architecture التخزين والتطبيق نفسها. الـProfile هنا مجموعة إعدادات تشغيل قابلة للإسناد أو إعادة الاستخدام، وليس كيانًا إنتاجيًا جديدًا.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 2,
                    'report_category' => 'architecture_rule',
                    'target_entity' => 'operational_settings',
                    'options' => [
                        ['label' => 'القيم تحفظ مباشرة على النطاق فقط دون Profiles قابلة لإعادة الاستخدام', 'value' => 'direct_scoped_settings_only'],
                        ['label' => 'الإعدادات تنظم أساسًا داخل Operational Settings Profiles ثم تسند للنطاقات', 'value' => 'profile_based_settings'],
                        ['label' => 'نموذج هجين: Profiles لإعادة الاستخدام مع إمكانية قيم مباشرة أو Overrides على النطاق', 'value' => 'hybrid_profiles_and_scoped_values'],
                    ],
                ],
                [
                    'seed_key' => 'settings_architecture.profile_reuse_model',
                    'title' => 'إذا استخدمنا Operational Settings Profiles، كيف يجب أن تعمل إعادة استخدامها؟',
                    'help_text' => 'المراجعة المعمارية تضع Reusable Profiles كقرار مفتوح. المطلوب تحديد هل الـProfile مرجع مشترك فعلًا أم مجرد قالب يبدأ منه كل نطاق ثم يستقل بقيمه.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 3,
                    'report_category' => 'profile_rule',
                    'target_entity' => 'operational_settings_profile',
                    'options' => [
                        ['label' => 'Profile مشترك يمكن إسناده لأكثر من مزرعة / عنبر وتظل النطاقات مرتبطة به', 'value' => 'shared_reusable_profile'],
                        ['label' => 'Profile يستخدم كقالب للنسخ فقط ثم تصبح نسخة كل نطاق مستقلة', 'value' => 'copy_template_then_independent'],
                        ['label' => 'دعم النموذجين: مرجع مشترك أو نسخة مستقلة حسب طريقة الإسناد', 'value' => 'shared_or_copied_profile_assignment'],
                    ],
                ],
                [
                    'seed_key' => 'settings_architecture.active_profile_model',
                    'title' => 'كم Operational Settings Profile يمكن أن يكون فعالًا على نفس النطاق في نفس الوقت؟',
                    'help_text' => 'وجود أكثر من Profile فعال قد يفيد إذا قسمت الإعدادات إلى مجالات، لكنه يحتاج قاعدة واضحة لحل التعارض. المطلوب حسم النموذج قبل بناء آلية التطبيق.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 4,
                    'report_category' => 'profile_rule',
                    'target_entity' => 'operational_settings_profile_assignment',
                    'options' => [
                        ['label' => 'Profile واحد فعال يغطي إعدادات النطاق كلها', 'value' => 'single_active_profile'],
                        ['label' => 'يمكن وجود Profile مختلف لكل مجال إعدادات دون تداخل بين المجالات', 'value' => 'one_profile_per_settings_domain'],
                        ['label' => 'يمكن وجود عدة Profiles متراكبة مع قاعدة أولوية واضحة عند التعارض', 'value' => 'multiple_layered_profiles'],
                    ],
                ],
                [
                    'seed_key' => 'settings_architecture.inheritance_model',
                    'title' => 'كيف يجب أن تعمل القيم الافتراضية والـInheritance بين مستويات الإعدادات؟',
                    'help_text' => 'السؤال يحسم هل يحتاج النطاق الأدنى إلى إدخال كل قيمة بنفسه أم يمكنه وراثة قيمة من مستوى أعلى. لا يحدد هنا من يملك صلاحية تعديل القيمة أو تجاوزها؛ ذلك يعالج في 6.2.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 5,
                    'report_category' => 'inheritance_rule',
                    'target_entity' => 'operational_settings_resolution',
                    'options' => [
                        ['label' => 'لا يوجد Inheritance؛ كل نطاق يحفظ القيم المطلوبة صراحة', 'value' => 'no_inheritance_explicit_values'],
                        ['label' => 'Inheritance هرمي من المستوى الأعلى إلى الأدنى عند عدم وجود قيمة محلية', 'value' => 'hierarchical_inheritance'],
                        ['label' => 'Inheritance يستخدم فقط للإعدادات التي تسمح به حسب تعريف الإعداد', 'value' => 'inheritance_by_setting_definition'],
                    ],
                ],
                [
                    'seed_key' => 'settings_architecture.override_model',
                    'title' => 'عند وجود قيمة موروثة أو آتية من Profile، هل يمكن للنطاق الأدنى عمل Override لها؟',
                    'help_text' => 'هذا السؤال يحسم وجود Override كقدرة معمارية فقط. من يملك صلاحية الـOverride، وهل يحتاج سببًا أو اعتمادًا أو Audit، كلها ضمن 6.2.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 6,
                    'report_category' => 'override_rule',
                    'target_entity' => 'operational_settings_resolution',
                    'options' => [
                        ['label' => 'نعم، يمكن Override لأي إعداد قابل للتخصيص على النطاق الأدنى', 'value' => 'allow_scoped_overrides'],
                        ['label' => 'يسمح بالـOverride فقط للإعدادات التي يحدد تعريفها أنها قابلة لذلك', 'value' => 'allow_overrides_by_setting_definition'],
                        ['label' => 'لا يسمح بالـOverride؛ يجب تغيير المصدر أو الـProfile نفسه', 'value' => 'no_scoped_overrides'],
                    ],
                ],
                [
                    'seed_key' => 'settings_architecture.value_resolution_model',
                    'title' => 'إذا وجدت أكثر من قيمة قابلة للتطبيق لنفس الإعداد، كيف يجب تحديد القيمة الفعالة؟',
                    'help_text' => 'مثل وجود Default عام وProfile وقيمة خاصة بالمزرعة أو العنبر. يجب أن تكون هناك قاعدة Deterministic حتى لا يختلف تفسير نفس الحالة من شاشة لأخرى.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 7,
                    'report_category' => 'resolution_rule',
                    'target_entity' => 'operational_settings_resolution',
                    'options' => [
                        ['label' => 'الأكثر تحديدًا في النطاق يفوز بالقيمة الفعالة بعد تطبيق الـInheritance / Override', 'value' => 'most_specific_scope_wins'],
                        ['label' => 'الـProfile المسند هو المصدر الأساسي والقيمة المحلية تستخدم فقط كOverride صريح', 'value' => 'assigned_profile_then_explicit_override'],
                        ['label' => 'يكون ترتيب الأولوية نفسه قابلًا للتكوين ضمن Architecture الإعدادات', 'value' => 'configurable_source_precedence'],
                    ],
                ],
                [
                    'seed_key' => 'settings_architecture.effective_date_model',
                    'title' => 'كيف يجب التعامل مع تاريخ سريان تغييرات الإعدادات؟',
                    'help_text' => 'Effective Date أحد القرارات المفتوحة المعتمدة للمراجعة. المطلوب تحديد هل التغيير يبدأ فور حفظه أم يمكن تجهيز قيمة لتبدأ في تاريخ لاحق مع الحفاظ على القيمة الحالية حتى ذلك الوقت.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 8,
                    'report_category' => 'effective_date_rule',
                    'target_entity' => 'operational_settings_version',
                    'options' => [
                        ['label' => 'كل تغيير يسري فور تفعيله فقط', 'value' => 'immediate_effect_only'],
                        ['label' => 'يمكن تحديد Effective Date مستقبلي لكل تغيير', 'value' => 'scheduled_effective_date'],
                        ['label' => 'دعم السريان الفوري أو المجدول حسب التغيير', 'value' => 'immediate_or_scheduled_effect'],
                    ],
                ],
                [
                    'seed_key' => 'settings_architecture.versioning_model',
                    'title' => 'كيف يجب حفظ تاريخ تغييرات الإعدادات بمرور الوقت؟',
                    'help_text' => 'المبدأ التاريخي المعتمد يمنع تفسير الماضي بقيمة حالية مختلفة عن السياق الذي وقع فيه. هذا السؤال يحسم نموذج Versioning نفسه، بينما تفاصيل من عدّل وماذا سجّل في الـAudit تعالج في 6.2.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 9,
                    'report_category' => 'versioning_rule',
                    'target_entity' => 'operational_settings_version',
                    'options' => [
                        ['label' => 'كل تغيير ينشئ Version جديدًا غير قابل لإعادة كتابة النسخة التاريخية', 'value' => 'immutable_setting_versions'],
                        ['label' => 'تحفظ القيمة الحالية مع سجل تاريخي لتغييراتها يمكن الرجوع إليه', 'value' => 'current_value_with_change_history'],
                        ['label' => 'Versioning على مستوى Profile / مجموعة الإعدادات، مع تاريخ مستقل للقيم المحلية أو Overrides', 'value' => 'profile_versioning_with_scoped_history'],
                    ],
                ],
                [
                    'seed_key' => 'settings_architecture.historical_reference_model',
                    'title' => 'كيف يجب أن يعرف النظام أي قيم إعدادات كانت سارية عند تفسير حدث أو نتيجة تاريخية؟',
                    'help_text' => 'تغيير إعداد اليوم لا يجوز أن يغير تفسير حدث قديم. المطلوب حسم العلاقة بين السجل التاريخي والإعداد الذي كان ساريًا وقت حدوثه دون نسخ غير منضبط للقيم.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 10,
                    'report_category' => 'history_rule',
                    'target_entity' => 'historical_settings_reference',
                    'options' => [
                        ['label' => 'السجل يحتفظ بمرجع مباشر إلى Settings Version / Profile Version الذي حكمه', 'value' => 'reference_effective_settings_version'],
                        ['label' => 'السجل يحتفظ Snapshot بالقيم المؤثرة نفسها وقت الحدث', 'value' => 'snapshot_effective_setting_values'],
                        ['label' => 'يستنتج النظام القيم التاريخية من Effective Date وVersion History دون مرجع محفوظ على كل حدث', 'value' => 'resolve_from_effective_date_history'],
                    ],
                ],
                [
                    'seed_key' => 'settings_architecture.active_process_change_model',
                    'title' => 'إذا تغير إعداد أثناء دورة أو مسار تشغيلي بدأ بالفعل، كيف يجب تحديد القاعدة التي تحكم الخطوات المتبقية؟',
                    'help_text' => 'المراجعة المعمارية تضع أثر تعديل Settings على العمليات الجارية كقرار مفتوح. المطلوب منع أن تتغير نتيجة المسار بصمت لمجرد تعديل إعداد عام بعد بدايته.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 11,
                    'report_category' => 'change_effect_rule',
                    'target_entity' => 'active_workflow_settings_context',
                    'options' => [
                        ['label' => 'المسار الجاري يستمر بالقيم التي بدأ بها حتى نهايته', 'value' => 'freeze_settings_for_active_process'],
                        ['label' => 'الخطوات المستقبلية في المسار تستخدم القيم الجديدة من تاريخ سريانها', 'value' => 'future_steps_use_new_settings'],
                        ['label' => 'يحدد السلوك حسب نوع الإعداد: ثابت للمسار أو يتبع أحدث قيمة سارية', 'value' => 'change_behavior_by_setting_definition'],
                    ],
                ],
                [
                    'seed_key' => 'settings_architecture.existing_tasks_change_model',
                    'title' => 'إذا تغير إعداد كان قد استخدم لتوليد مهام أو مواعيد مستقبلية قائمة بالفعل، ماذا يحدث لهذه المهام؟',
                    'help_text' => 'المهام الفعلية وتاريخها في 4.17، وقواعد توليدها في Settings. المطلوب هنا تحديد أثر تغيير القاعدة على المهام الموجودة دون حذف تاريخها أو إعادة بناء الماضي.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 12,
                    'report_category' => 'change_effect_rule',
                    'target_entity' => 'generated_task_settings_context',
                    'options' => [
                        ['label' => 'المهام الموجودة تحتفظ بالموعد / القاعدة التي ولدت بها، والتغيير يؤثر على المهام الجديدة فقط', 'value' => 'existing_tasks_keep_original_schedule'],
                        ['label' => 'تعاد حساب المهام المفتوحة تلقائيًا من تاريخ سريان الإعداد الجديد مع حفظ تاريخ التغيير', 'value' => 'recalculate_open_tasks_on_setting_change'],
                        ['label' => 'يعرض النظام المهام المتأثرة للمراجعة وإعادة البناء وفق نوع الإعداد بدل تعديلها تلقائيًا دائمًا', 'value' => 'review_and_rebuild_affected_tasks'],
                    ],
                ],
                [
                    'seed_key' => 'settings_architecture.shared_profile_update_model',
                    'title' => 'إذا تم تعديل Profile مشترك مرتبط بعدة نطاقات، كيف يجب أن يصل التغيير إلى النطاقات المرتبطة؟',
                    'help_text' => 'هذا القرار مهم فقط عند اعتماد Reusable Shared Profiles، لكنه يحدد هل تعديل المصدر ينتشر تلقائيًا أم يحتاج Version جديد وتفعيلًا واضحًا لكل نطاق حتى لا تتغير عدة مزارع أو عنابر دون قصد.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 13,
                    'report_category' => 'profile_versioning_rule',
                    'target_entity' => 'operational_settings_profile_assignment',
                    'options' => [
                        ['label' => 'كل نطاق مرتبط بالـProfile ينتقل تلقائيًا إلى النسخة الجديدة عند سريانها', 'value' => 'linked_scopes_follow_profile_updates'],
                        ['label' => 'التعديل ينشئ Version جديدًا ويحتاج تفعيل / ترقية صريحة على كل نطاق مرتبط', 'value' => 'explicit_profile_version_upgrade_per_scope'],
                        ['label' => 'يحدد عند الإسناد هل النطاق يتبع تحديثات الـProfile تلقائيًا أم يثبت على Version محدد', 'value' => 'assignment_can_follow_or_pin_version'],
                    ],
                ],
            ];

            app(QuestionSeederSyncService::class)->sync(
                mainSectionName: 'الإعدادات وقواعد التشغيل',
                sectionName: 'نموذج الإعدادات ونطاق التطبيق',
                questions: $questions,
                prune: true,
                preserveAnswers: true,
            );
        });
    }
}
