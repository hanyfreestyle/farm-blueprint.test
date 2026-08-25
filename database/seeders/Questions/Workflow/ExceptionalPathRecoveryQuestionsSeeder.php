<?php

namespace Database\Seeders\Questions\Workflow;

use App\Enums\Questionnaire\QuestionType;
use App\Services\Questionnaire\QuestionSeederSyncService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExceptionalPathRecoveryQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $questions = [
                [
                    'seed_key' => 'exception_handling.canonical_routing_model',
                    'title' => 'كيف يجب تمثيل الحالة الاستثنائية عندما يكون الحدث الفعلي له سجل Canonical موجود بالفعل في Workflow آخر؟',
                    'help_text' => 'أمثلة: نفوق الأم يسجل كنفوق في 4.13، ونقل المواليد يسجل في 4.7، والفطام الفعلي في 4.8. المطلوب منع إنشاء سجل استثنائي منافس لنفس الواقعة مع بقاء 4.14 مسؤولًا عن ربط الانحراف وإعادة بناء المسار.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 1,
                    'report_category' => 'integration_rule',
                    'target_entity' => 'exception_event',
                    'options' => [
                        ['label' => 'يستخدم الحدث Canonical في قسمه الأصلي، وتضيف 4.14 طبقة Orchestration / Reconstruction فقط عند الحاجة', 'value' => 'canonical_event_with_exception_orchestration'],
                        ['label' => 'ينشأ سجل Exception عام لكل حالة بالإضافة إلى الحدث الأصلي', 'value' => 'generic_exception_wrapper_plus_domain_event'],
                        ['label' => 'تعالج الحالات فقط داخل الأقسام الأصلية دون طبقة استثناءات أو إعادة بناء مستقلة', 'value' => 'domain_events_only_no_exception_layer'],
                    ],
                ],
                [
                    'seed_key' => 'exception_handling.unmodeled_case_policy',
                    'title' => 'إذا حدثت حالة استثنائية فعلية لا يوجد لها Event متخصص في الأقسام الحالية، كيف يجب تسجيلها؟',
                    'help_text' => 'المرجع يذكر أن الواقع قد يحتوي حالات إضافية غير محصورة مسبقًا. المطلوب دعمها دون اختراع Module كامل أو فقد ما حدث فعليًا، وبطريقة تسمح لاحقًا بتحويل الحالة المتكررة إلى Event متخصص إذا ثبتت الحاجة.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 2,
                    'report_category' => 'workflow_model',
                    'target_entity' => 'exception_event',
                    'options' => [
                        ['label' => 'سجل Exception منظم يحفظ الموضوع والتاريخ والسياق والوصف والسبب والمنفذ والإجراء التالي', 'value' => 'structured_generic_exception_event'],
                        ['label' => 'تسجل كملاحظة مرتبطة بالـTimeline فقط دون كيان Exception منظم', 'value' => 'timeline_note_only'],
                        ['label' => 'لا يسمح بالتسجيل حتى يضاف Event متخصص للحالة', 'value' => 'require_dedicated_event_before_recording'],
                    ],
                ],
                [
                    'seed_key' => 'pregnancy_exception.outcome_categories',
                    'title' => 'ما النتائج الاستثنائية للحمل التي يجب تمييزها عن الولادة الطبيعية وعن نتيجة فحص الحمل العادية؟',
                    'help_text' => 'المصدر يفرق بين الإجهاض المرصود، وفقد حمل مؤكد دون مشاهدة إجهاض واضح، والحمل الكاذب أو اكتشاف أن تشخيص الحمل السابق لم يعد صحيحًا. التصنيف الطبي الدقيق ما زال يحتاج مراجعة، لذلك نحسم فقط الفئات التشغيلية المطلوبة.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 3,
                    'report_category' => 'workflow_outcome',
                    'target_entity' => 'reproductive_cycle_exception',
                    'options' => [
                        ['label' => 'إجهاض مرصود فعليًا', 'value' => 'observed_abortion'],
                        ['label' => 'فقد حمل مؤكد دون تسجيل إجهاض واضح', 'value' => 'pregnancy_loss_without_observed_abortion'],
                        ['label' => 'حمل كاذب / تشخيص حمل سابق اتضح لاحقًا أنه غير صحيح', 'value' => 'false_pregnancy_or_misdiagnosis'],
                        ['label' => 'إنهاء الحمل لسبب صحي موثق دون ولادة', 'value' => 'health_related_pregnancy_termination'],
                        ['label' => 'نتيجة استثنائية أخرى موثقة للحمل', 'value' => 'other_documented_pregnancy_exception'],
                    ],
                ],
                [
                    'seed_key' => 'pregnancy_exception.record_fields',
                    'title' => 'ما البيانات التي يجب أن يحتفظ بها سجل الحالة الاستثنائية المرتبطة بالحمل؟',
                    'help_text' => 'السجل يجب أن يوضح ما اكتشف أو حدث فعليًا ويغلق مسار الحمل بالنتيجة الصحيحة دون تحويله إلى ولادة. عمر الحمل يحسب من البيانات المتاحة عند إمكان ذلك، والسبب الصحي يمكن ربطه بسجل 4.13 بدل نسخه.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 4,
                    'report_category' => 'field',
                    'target_entity' => 'reproductive_cycle_exception',
                    'options' => [
                        ['label' => 'الأنثى', 'value' => 'female'],
                        ['label' => 'دورة الإنتاج / الحمل المرتبط', 'value' => 'reproductive_cycle_or_pregnancy'],
                        ['label' => 'نوع / نتيجة الحالة الاستثنائية', 'value' => 'exception_outcome'],
                        ['label' => 'تاريخ / وقت الحدوث أو الاكتشاف', 'value' => 'occurred_or_discovered_at'],
                        ['label' => 'عمر / مرحلة الحمل عند إمكان تحديدها', 'value' => 'gestational_age_or_stage'],
                        ['label' => 'مرجع آخر فحص حمل ذي صلة', 'value' => 'last_pregnancy_check_reference'],
                        ['label' => 'سبب الحالة إذا كان معروفًا', 'value' => 'known_reason'],
                        ['label' => 'مرجع الحالة الصحية المرتبطة عند وجودها', 'value' => 'health_reference'],
                        ['label' => 'المستخدم / منفذ التسجيل', 'value' => 'recorded_by'],
                        ['label' => 'ملاحظات', 'value' => 'notes'],
                    ],
                ],
                [
                    'seed_key' => 'pregnancy_exception.history_integrity_model',
                    'title' => 'إذا كان هناك فحص حمل إيجابي مسجل ثم اتضح لاحقًا حمل كاذب أو فقد حمل، كيف يجب حماية التاريخ السابق؟',
                    'help_text' => 'المرجع يؤكد عدم تعديل نتيجة الجس القديمة لإخفاء ما كان مسجلًا وقتها. المطلوب تحديد هل نسجل حدثًا لاحقًا يصحح المسار فقط أم نعيد كتابة النتيجة السابقة.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 5,
                    'report_category' => 'history_rule',
                    'target_entity' => 'reproductive_cycle_exception',
                    'options' => [
                        ['label' => 'تبقى نتيجة الفحص السابقة كما سجلت، ويضاف Event لاحق يغير نتيجة ومسار الحمل من تاريخ اكتشافه', 'value' => 'preserve_prior_check_add_later_exception_event'],
                        ['label' => 'تعدل نتيجة الفحص السابقة إلى النتيجة النهائية مع Audit Trail للتعديل', 'value' => 'revise_prior_check_with_audit'],
                        ['label' => 'تحذف نتيجة الفحص السابقة وتستبدل بالسجل النهائي', 'value' => 'replace_prior_check_with_final_result'],
                    ],
                ],
                [
                    'seed_key' => 'workflow_reconstruction.context_detection_model',
                    'title' => 'عند وقوع حدث استثنائي، كيف يجب تحديد المسارات والعلاقات النشطة التي قد تتأثر به؟',
                    'help_text' => 'مثال: نفوق أنثى قد يجد حملًا نشطًا أو بطنًا تحت الرضاعة؛ الإجهاض يجد مهام ولادة مستقبلية؛ الحيوان المفقود قد يكون له تسكين ومسار نشط. المطلوب منع الاعتماد على Status واحد فقط.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 6,
                    'report_category' => 'derivation_rule',
                    'target_entity' => 'workflow_reconstruction',
                    'options' => [
                        ['label' => 'يكتشف النظام تلقائيًا كل العلاقات والمسارات والمهام المفتوحة من الـTimeline ثم يعرضها قبل إعادة البناء', 'value' => 'derive_all_active_context_from_timeline'],
                        ['label' => 'يكتشف النظام السياق تلقائيًا ويسمح للمستخدم بإضافة علاقات متأثرة لم يستنتجها النظام', 'value' => 'derive_context_with_manual_additions'],
                        ['label' => 'يحدد المستخدم يدويًا المسارات والعلاقات المتأثرة بكل حالة استثنائية', 'value' => 'manual_affected_context_selection'],
                    ],
                ],
                [
                    'seed_key' => 'workflow_reconstruction.action_plan_model',
                    'title' => 'كيف يجب تمثيل إعادة بناء المسار بعد الحدث الاستثنائي؟',
                    'help_text' => 'القاعدة الوظيفية هي تحديد ما لم يعد صالحًا من الخطوات القديمة وما الخطوة التالية الآن، مع عدم محو التاريخ. قواعد الاختيار التلقائي للخطوة التالية يمكن ضبطها في Settings، لكن 4.14 يحتاج نموذج تنفيذ واضح.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 7,
                    'report_category' => 'workflow_model',
                    'target_entity' => 'workflow_reconstruction',
                    'options' => [
                        ['label' => 'ينشئ النظام Reconstruction Plan موثقًا يحدد العناصر المتأثرة والإجراءات الملغاة/المغلقة والخطوات التالية ثم تنفذ أحداثها في أقسامها الأصلية', 'value' => 'documented_reconstruction_plan_with_domain_actions'],
                        ['label' => 'يطبق النظام التغييرات والانتقالات تلقائيًا مباشرة دون سجل Reconstruction مستقل', 'value' => 'automatic_direct_rebuild_without_plan_record'],
                        ['label' => 'يسجل الحدث الاستثنائي فقط ويترك إعادة بناء المسار بالكامل للمستخدم يدويًا', 'value' => 'exception_record_only_manual_rebuild'],
                    ],
                ],
                [
                    'seed_key' => 'workflow_reconstruction.task_integration_model',
                    'title' => 'كيف يجب التعامل مع المهام والمواعيد المستقبلية التي أصبحت غير صالحة بعد الحالة الاستثنائية؟',
                    'help_text' => '4.14 يحدد أثر الاستثناء على المسار، لكن إلغاء/إغلاق/تأجيل المهمة نفسها ينفذ في 4.17، وقواعد توليد المهمة وتوقيتها في Settings 6.12. المطلوب هنا حسم Integration فقط.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 8,
                    'report_category' => 'integration_rule',
                    'target_entity' => 'workflow_reconstruction',
                    'options' => [
                        ['label' => 'خطة إعادة البناء تنشئ إجراءات مرتبطة بالمهام المتأثرة ليتم إغلاقها/إلغاؤها في 4.17 مع سبب الاستثناء', 'value' => 'create_linked_task_lifecycle_actions'],
                        ['label' => 'تغلق المهام غير الصالحة تلقائيًا كجزء من Reconstruction مع Audit Link للحدث الاستثنائي', 'value' => 'auto_close_invalid_tasks_with_exception_link'],
                        ['label' => 'لا يؤثر الاستثناء تلقائيًا على المهام؛ يراجعها المستخدم واحدة واحدة', 'value' => 'manual_task_review_after_exception'],
                    ],
                ],
                [
                    'seed_key' => 'workflow_reconstruction.maternal_death_with_litter_model',
                    'title' => 'إذا نَفقت أم أثناء الرضاعة وكان لها بطن نشطة، كيف يجب إعادة بناء مسار المواليد دون تكرار سجل النفوق؟',
                    'help_text' => 'نفوق الأم نفسه Canonical في 4.13. المصدر يؤكد أن البطن لا تغلق تلقائيًا، بل يجب اكتشاف المواليد بدون أم وطلب إجراء مثل Foster Transfer أو توزيع أو رعاية بديلة أو فطام مبكر إذا سمحت القواعد.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 9,
                    'report_category' => 'integration_rule',
                    'target_entity' => 'workflow_reconstruction',
                    'options' => [
                        ['label' => 'ينشئ Reconstruction عاجل للبطن ويظل Litter مفتوحًا حتى تسجيل إجراء رعاية/نقل/فطام فعلي في القسم المختص', 'value' => 'urgent_orphan_litter_reconstruction_keep_litter_open'],
                        ['label' => 'ينقل النظام المواليد تلقائيًا إلى أم حاضنة مناسبة حسب Settings دون قرار تشغيلي منفصل', 'value' => 'automatic_foster_assignment_from_settings'],
                        ['label' => 'يغلق البطن تلقائيًا عند نفوق الأم ثم يعالج المواليد كسجلات منفصلة لاحقًا', 'value' => 'close_litter_on_maternal_death'],
                    ],
                ],
                [
                    'seed_key' => 'missing_animal.lifecycle_model',
                    'title' => 'كيف يجب تمثيل حالة الحيوان المفقود والعثور عليه لاحقًا؟',
                    'help_text' => 'المصدر يؤكد أن المفقود لا يعتبر نافقًا أو مباعًا تلقائيًا، وإذا وجد لاحقًا يستكمل نفس السجل. المطلوب حسم Lifecycle الحالة دون تحويلها إلى Exit أو إنشاء Animal Record جديد.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 10,
                    'report_category' => 'lifecycle_rule',
                    'target_entity' => 'missing_animal_event',
                    'options' => [
                        ['label' => 'حدث Missing مستقل يوقف الاعتماد على الموقع الحالي، ثم Found Event يعيد نفس Animal Record للمسار المناسب', 'value' => 'missing_event_then_found_event_same_animal_record'],
                        ['label' => 'تسجل حالة Missing مؤقتة على الحيوان وتزال عند العثور عليه دون أحداث تاريخية مستقلة', 'value' => 'temporary_missing_status_without_events'],
                        ['label' => 'يسجل Missing كخروج من المزرعة ثم Re-entry عند العثور عليه', 'value' => 'treat_missing_as_exit_then_reentry'],
                    ],
                ],
                [
                    'seed_key' => 'missing_animal.record_fields',
                    'title' => 'ما البيانات التي يجب أن يحتفظ بها حدث فقد الحيوان أو العثور عليه؟',
                    'help_text' => 'المرجع يذكر تاريخ الاكتشاف وآخر قفص وآخر حركة والملاحظات، وعند العثور عليه يجب الحفاظ على نفس الهوية وتوثيق متى وأين تم العثور عليه وما الإجراء التالي.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 11,
                    'report_category' => 'field',
                    'target_entity' => 'missing_animal_event',
                    'options' => [
                        ['label' => 'الحيوان', 'value' => 'animal'],
                        ['label' => 'تاريخ / وقت اكتشاف الفقد', 'value' => 'missing_discovered_at'],
                        ['label' => 'آخر موقع / قفص معروف', 'value' => 'last_known_housing_reference'],
                        ['label' => 'مرجع آخر حركة معروفة', 'value' => 'last_known_movement_reference'],
                        ['label' => 'المستخدم / مبلغ الحالة', 'value' => 'reported_by'],
                        ['label' => 'تاريخ / وقت العثور عليه عند حدوث ذلك', 'value' => 'found_at'],
                        ['label' => 'الموقع الذي عثر عليه فيه', 'value' => 'found_location'],
                        ['label' => 'مرجع الإجراء / التسكين التالي بعد العثور عليه', 'value' => 'recovery_action_reference'],
                        ['label' => 'ملاحظات', 'value' => 'notes'],
                    ],
                ],
                [
                    'seed_key' => 'sex_correction.conflict_model',
                    'title' => 'كيف يجب التعامل مع اكتشاف خطأ في جنس الحيوان بعد بدء تسجيل تاريخه التشغيلي؟',
                    'help_text' => 'المصدر يسمح بالتصحيح قبل الدخول في دورة إنتاجية مع تسجيل الأثر، لكنه يطلب Review إذا تعارض الجنس الجديد مع أحداث إنتاجية سابقة. صلاحية من يستطيع التصحيح وقواعد التدقيق العامة مكانها Settings 6.2.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 12,
                    'report_category' => 'correction_rule',
                    'target_entity' => 'animal_identity_correction',
                    'options' => [
                        ['label' => 'يسمح بتصحيح الجنس مع Audit قبل وجود تعارض تاريخي، وأي تعارض مع أحداث إنتاجية يفتح Exceptional Review بدل التعديل الصامت', 'value' => 'audit_correction_or_exception_review_on_conflict'],
                        ['label' => 'يسمح بتصحيح الجنس دائمًا مع Audit Trail حتى لو تعارض مع أحداث سابقة', 'value' => 'always_allow_correction_with_audit'],
                        ['label' => 'يمنع تعديل الجنس نهائيًا بعد إنشاء Animal Record', 'value' => 'never_allow_sex_correction'],
                    ],
                ],
                [
                    'seed_key' => 'event_correction.correction_model',
                    'title' => 'كيف يجب تصحيح أو إلغاء حدث تشغيلي حساس تم تسجيله بالخطأ دون محو ما حدث في النظام؟',
                    'help_text' => 'مثال المصدر: تسجيل نفوق للأرنب الخطأ. نحتاج ربط الحدث الأصلي بسبب التصحيح والمستخدم والتاريخ والحدث المصحح عند الحاجة، مع إعادة بناء أي آثار أو مهام نتجت عنه. صلاحيات التصحيح والموافقة تحدد في Settings 6.2.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 13,
                    'report_category' => 'correction_rule',
                    'target_entity' => 'event_correction',
                    'options' => [
                        ['label' => 'يبقى الحدث الأصلي محفوظًا ويضاف Correction / Cancellation Event مرتبط به وبالبديل وبـReconstruction للآثار الناتجة', 'value' => 'linked_correction_event_preserve_original_and_rebuild_effects'],
                        ['label' => 'يعدل الحدث الأصلي مباشرة مع Full Audit Trail ثم يعاد حساب الآثار التابعة', 'value' => 'edit_original_with_full_audit_and_recalculate_effects'],
                        ['label' => 'يحذف الحدث الخاطئ نهائيًا ثم يسجل الحدث الصحيح من جديد', 'value' => 'hard_delete_wrong_event_then_recreate'],
                    ],
                ],
            ];

            app(QuestionSeederSyncService::class)->sync(
                mainSectionName: 'الحركات ودورة التشغيل الفعلية',
                sectionName: 'الحالات الاستثنائية وإعادة بناء المسار',
                questions: $questions,
                prune: true,
                preserveAnswers: true,
            );
        });
    }
}
