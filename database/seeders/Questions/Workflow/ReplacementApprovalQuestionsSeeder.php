<?php

namespace Database\Seeders\Questions\Workflow;

use App\Enums\Questionnaire\QuestionType;
use App\Services\Questionnaire\QuestionSeederSyncService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReplacementApprovalQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $questions = [
                [
                    'seed_key' => 'replacement.candidate_stage_policy',
                    'title' => 'كيف يجب تطبيق مرحلة «مرشح للقطيع» قبل الاعتماد النهائي في حالات الإحلال الداخلي والخارجي؟',
                    'help_text' => 'المصدر يفرق بوضوح بين المرشح وبين فرد القطيع الإنتاجي المعتمد. الإحلال قد يكون داخليًا من إنتاج المزرعة أو خارجيًا بعد مسار الاستقبال، والمطلوب حسم هل يمر الاثنان بنفس مرحلة المتابعة أم توجد إمكانية اعتماد مباشر للحيوان الخارجي المؤهل.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 1,
                    'report_category' => 'workflow_rule',
                    'target_entity' => 'replacement_candidate',
                    'options' => [
                        ['label' => 'كل إحلال داخلي أو خارجي يمر بمرحلة مرشح تحت المتابعة قبل الاعتماد', 'value' => 'all_replacements_use_candidate_stage'],
                        ['label' => 'الإحلال الداخلي يمر بمرحلة المرشح، بينما الحيوان الخارجي يمكن اعتماده مباشرة بعد الاستقبال إذا استوفى قواعد الاعتماد', 'value' => 'internal_candidate_external_direct_if_eligible'],
                        ['label' => 'مرحلة المرشح اختيارية للحالتين حسب القرار التشغيلي وقواعد المزرعة', 'value' => 'candidate_stage_optional_by_operational_decision'],
                    ],
                ],
                [
                    'seed_key' => 'replacement.candidate_record_fields',
                    'title' => 'ما البيانات التي يجب أن يحتفظ بها سجل ترشيح الحيوان للقطيع الإنتاجي؟',
                    'help_text' => 'الترشيح لا ينشئ حيوانًا جديدًا؛ هو مرحلة في نفس Timeline الحيوان. الوزن نفسه يبقى سجلًا Canonical في 4.3، وقرار المصير المصدر يمكن أن يأتي من 4.10، لذلك تحفظ المراجع بدل تكرار البيانات.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 2,
                    'report_category' => 'field',
                    'target_entity' => 'replacement_candidate',
                    'options' => [
                        ['label' => 'الحيوان', 'value' => 'animal'],
                        ['label' => 'تاريخ / وقت الترشيح', 'value' => 'nominated_at'],
                        ['label' => 'مصدر الإحلال: داخلي / خارجي', 'value' => 'replacement_source'],
                        ['label' => 'مرجع قرار المصير أو التقييم الذي أدى للترشيح عند وجوده', 'value' => 'source_decision_reference'],
                        ['label' => 'العمر عند الترشيح', 'value' => 'age_at_nomination'],
                        ['label' => 'مرجع الوزن المستخدم عند الترشيح', 'value' => 'weight_reference'],
                        ['label' => 'مرحلة / نتيجة الفرز المرتبطة عند وجودها', 'value' => 'sorting_context'],
                        ['label' => 'الهدف أو الدور الإنتاجي المرشح له الحيوان', 'value' => 'target_production_role'],
                        ['label' => 'سبب / مبرر الاختيار', 'value' => 'selection_reason'],
                        ['label' => 'المستخدم الذي اعتمد الترشيح', 'value' => 'nominated_by'],
                        ['label' => 'ملاحظات', 'value' => 'notes'],
                    ],
                ],
                [
                    'seed_key' => 'replacement.candidate_followup_context_sources',
                    'title' => 'ما المعلومات والسجلات التي يجب أن يستطيع المسؤول الرجوع إليها أثناء متابعة مرشح الإحلال قبل الاعتماد؟',
                    'help_text' => 'المصدر يذكر استمرار الوزن والنمو والصحة والنسب ومراجعة العمر والوزن. المطلوب هنا تحديد سياق المتابعة فقط؛ الأحداث الأصلية تظل في وحداتها المعتمدة ولا تنسخ داخل سجل المرشح.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 3,
                    'report_category' => 'workflow_context',
                    'target_entity' => 'replacement_candidate',
                    'options' => [
                        ['label' => 'سجل الأوزان', 'value' => 'weight_history'],
                        ['label' => 'مؤشرات النمو ونتائج الفرز وإعادة التقييم', 'value' => 'growth_and_sorting_history'],
                        ['label' => 'الحالة والسجل الصحي المؤثر', 'value' => 'health_context'],
                        ['label' => 'النسب والبطن الأصلية', 'value' => 'pedigree_and_litter_origin'],
                        ['label' => 'العمر الحالي المحسوب', 'value' => 'current_age'],
                        ['label' => 'الغرض / الدور المرشح له الحيوان', 'value' => 'target_production_role'],
                        ['label' => 'نتيجة فحص شروط الاعتماد الحالية', 'value' => 'approval_rule_evaluation'],
                        ['label' => 'الحيوان المراد إحلاله عند وجود ارتباط مباشر', 'value' => 'replaced_animal_context'],
                    ],
                ],
                [
                    'seed_key' => 'replacement.candidate_nonapproval_handoff_model',
                    'title' => 'إذا لم يُعتمد المرشح أو احتاج تغيير مساره أثناء المتابعة، كيف يجب إنهاء أو تحويل مسار الترشيح دون فقد التاريخ؟',
                    'help_text' => 'الترشيح ليس قرارًا نهائيًا. إذا كان المطلوب مجرد متابعة إضافية يمكن العودة لإعادة التقييم، أما رفض الترشيح أو تحويل الحيوان لمسار آخر فيجب ألا يتحول إلى تعديل Status صامت.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 4,
                    'report_category' => 'integration_rule',
                    'target_entity' => 'replacement_candidate',
                    'options' => [
                        ['label' => 'إعادة التقييم تعود إلى 4.9، أما رفض الترشيح أو تغيير المصير فيسجل عبر قرار مصير / استبعاد جديد في 4.10', 'value' => 'reevaluation_or_fate_decision_by_outcome'],
                        ['label' => 'كل حالة عدم اعتماد تنشئ قرار مصير جديدًا في 4.10 حتى لو كانت مجرد متابعة إضافية', 'value' => 'all_nonapproval_to_fate_decision'],
                        ['label' => 'يغلق سجل المرشح مباشرة مع كتابة المسار التالي دون إنشاء حدث مستقل', 'value' => 'close_candidate_with_inline_next_path'],
                    ],
                ],
                [
                    'seed_key' => 'replacement.approval_trigger_model',
                    'title' => 'كيف يجب تنفيذ الاعتماد النهائي لمرشح الإحلال كفرد في القطيع الإنتاجي؟',
                    'help_text' => 'شروط الاعتماد نفسها مثل العمر والوزن والصحة والنسب والفرز مكانها Settings. المطلوب هنا حسم هل الاعتماد حدث بشري صريح بعد فحص القواعد أم يحدث تلقائيًا بمجرد استيفائها.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 5,
                    'report_category' => 'workflow_rule',
                    'target_entity' => 'production_herd_approval',
                    'options' => [
                        ['label' => 'اعتماد صريح من المسؤول بعد أن يعرض النظام نتيجة فحص شروط الاعتماد', 'value' => 'explicit_approval_after_rule_check'],
                        ['label' => 'اعتماد تلقائي بمجرد استيفاء جميع الشروط', 'value' => 'automatic_approval_when_rules_pass'],
                        ['label' => 'اعتماد صريح من المسؤول دون اشتراط فحص آلي لشروط الاعتماد', 'value' => 'explicit_approval_without_automated_rule_check'],
                    ],
                ],
                [
                    'seed_key' => 'replacement.approval_record_fields',
                    'title' => 'ما البيانات التي يجب أن يحتفظ بها حدث اعتماد الحيوان داخل القطيع الإنتاجي؟',
                    'help_text' => 'المصدر يقترح تاريخ الاعتماد والعمر والوزن والغرض الإنتاجي والقفص والمجموعة وسبب الاعتماد والمنفذ. القفص الحالي ووزنه الحقيقي لا يتحولان إلى نسخ ثابتة؛ الأفضل حفظ المراجع اللازمة للتدقيق.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 6,
                    'report_category' => 'field',
                    'target_entity' => 'production_herd_approval',
                    'options' => [
                        ['label' => 'الحيوان', 'value' => 'animal'],
                        ['label' => 'مرجع سجل الترشيح عند وجوده', 'value' => 'candidate_reference'],
                        ['label' => 'تاريخ / وقت الاعتماد الفعلي', 'value' => 'approved_at'],
                        ['label' => 'العمر عند الاعتماد', 'value' => 'age_at_approval'],
                        ['label' => 'مرجع الوزن المستخدم عند الاعتماد', 'value' => 'weight_reference'],
                        ['label' => 'الدور الإنتاجي المعتمد', 'value' => 'approved_production_role'],
                        ['label' => 'الغرض الإنتاجي المرجعي', 'value' => 'production_purpose_reference'],
                        ['label' => 'مرجع الموقع / التسكين الحالي', 'value' => 'current_housing_reference'],
                        ['label' => 'المجموعة الإنتاجية عند تعيينها ضمن نفس العملية', 'value' => 'production_group_reference'],
                        ['label' => 'الحيوان الذي يحل محله عند وجود إحلال مباشر', 'value' => 'replaced_animal_reference'],
                        ['label' => 'سبب / مبرر الاعتماد', 'value' => 'approval_reason'],
                        ['label' => 'مرجع / لقطة نتيجة فحص شروط الاعتماد', 'value' => 'approval_rule_evaluation_reference'],
                        ['label' => 'المستخدم / صاحب قرار الاعتماد', 'value' => 'approved_by'],
                        ['label' => 'ملاحظات', 'value' => 'notes'],
                    ],
                ],
                [
                    'seed_key' => 'replacement.production_role_assignment_model',
                    'title' => 'كيف يجب تحديد الدور الإنتاجي للحيوان عند اعتماده داخل القطيع؟',
                    'help_text' => 'المصدر يستخدم هدف «ذكر إنتاج» أو «أنثى إنتاج». المطلوب حسم ما إذا كان الدور يسجل صراحة عند الاعتماد أم يشتق من الجنس أو من هدف الترشيح السابق، دون إنشاء هوية جديدة للحيوان.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 7,
                    'report_category' => 'state_transition_rule',
                    'target_entity' => 'production_herd_membership',
                    'options' => [
                        ['label' => 'يسجل الدور الإنتاجي صراحة عند الاعتماد مع التحقق من اتساقه مع بيانات الحيوان', 'value' => 'explicit_role_on_approval'],
                        ['label' => 'يشتق الدور تلقائيًا من هدف الترشيح المعتمد', 'value' => 'derive_role_from_candidate_target'],
                        ['label' => 'يشتق الدور من جنس الحيوان فقط دون حقل دور إنتاجي مستقل', 'value' => 'derive_role_from_sex_only'],
                    ],
                ],
                [
                    'seed_key' => 'replacement.production_purpose_reference',
                    'title' => 'هل يجب ربط حدث الاعتماد بالغرض الإنتاجي المرجعي من قسم «الأغراض الإنتاجية» بدل كتابة الغرض كنص حر؟',
                    'help_text' => 'الأغراض الإنتاجية معرفة بالفعل في Master Data. هذا السؤال لا يعيد تعريف قيمها، بل يحسم هل يستخدم الاعتماد مرجعًا للقيمة المعتمدة حتى يمكن الحفاظ على الاتساق والتحليل لاحقًا.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 8,
                    'report_category' => 'relationship_rule',
                    'target_entity' => 'production_herd_approval',
                    'options' => [],
                ],
                [
                    'seed_key' => 'replacement.group_assignment_integration_model',
                    'title' => 'كيف يجب دمج تعيين الحيوان في مجموعة إنتاجية عند اعتماده للقطيع دون تعديل عضوية المجموعة بصورة صامتة؟',
                    'help_text' => 'تعريف المجموعة وقواعد عضويتها موجود في 3.5. هنا نحسم فقط ما إذا كان الاعتماد يمكن أن ينشئ حركة عضوية تاريخية للمجموعة، أو يجب تنفيذ التعيين كخطوة مستقلة، مع بقاء التلقيح الفعلي منفصلًا في 4.4.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 9,
                    'report_category' => 'integration_rule',
                    'target_entity' => 'production_group_membership',
                    'options' => [
                        ['label' => 'يمكن تعيين المجموعة ضمن عملية الاعتماد، ويُسجل ذلك كتغيير عضوية تاريخي مستقل مرتبط بالاعتماد', 'value' => 'allow_group_assignment_with_membership_event'],
                        ['label' => 'إذا كانت سياسة 3.5 تشترط المجموعة، يجب استكمال عضوية المجموعة قبل إغلاق الاعتماد؛ وإلا يكون التعيين اختياريًا', 'value' => 'require_when_group_policy_requires_otherwise_optional'],
                        ['label' => 'لا يتم أي تعيين للمجموعة أثناء الاعتماد؛ تنفذ العضوية دائمًا كعملية مستقلة لاحقًا', 'value' => 'always_separate_group_assignment'],
                    ],
                ],
                [
                    'seed_key' => 'replacement.replaced_animal_link_model',
                    'title' => 'عندما يكون الإحلال بهدف استبدال حيوان محدد، كيف يجب ربط الحيوان الجديد بالحيوان الذي يحل محله؟',
                    'help_text' => 'المصدر يطرح حفظ الفرد الذي يتم إحلاله إذا كان هناك ارتباط مباشر، لما له من قيمة تحليلية. لا يعني هذا أن كل اعتماد يجب أن يستبدل حيوانًا بعينه؛ قد يكون الإحلال لزيادة العدد أو لتعويض احتياج عام.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 10,
                    'report_category' => 'relationship_rule',
                    'target_entity' => 'replacement_link',
                    'options' => [
                        ['label' => 'مرجع اختياري للحيوان المستبدل، ويستخدم فقط عندما يكون الإحلال مباشرًا واحدًا مقابل واحد', 'value' => 'optional_direct_replaced_animal_reference'],
                        ['label' => 'المرجع إلزامي كلما تم وصف العملية كإحلال مباشر لحيوان محدد', 'value' => 'required_for_direct_replacement'],
                        ['label' => 'لا يحفظ ارتباط مباشر؛ يكتفى بسبب الإحلال وتحليل أعداد القطيع', 'value' => 'no_direct_replaced_animal_link'],
                    ],
                ],
                [
                    'seed_key' => 'replacement.approval_vs_mating_readiness',
                    'title' => 'هل يجب فصل اعتماد الحيوان كعضو في القطيع الإنتاجي عن جاهزيته الفعلية للتلقيح؟',
                    'help_text' => 'المصدر يؤكد أن الأنثى أو الذكر قد يصبح عضوًا في القطيع قبل استيفاء شروط أول استخدام تناسلي. إذا كانت الإجابة نعم، تصبح عضوية القطيع نتيجة 4.11 بينما الجاهزية للتلقيح مشتقة من قواعد Settings وتُفحص عند Workflow التلقيح.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 11,
                    'report_category' => 'state_model_rule',
                    'target_entity' => 'production_herd_membership',
                    'options' => [],
                ],
            ];

            app(QuestionSeederSyncService::class)->sync(
                mainSectionName: 'الحركات ودورة التشغيل الفعلية',
                sectionName: 'الإحلال والاعتماد داخل القطيع الإنتاجي',
                questions: $questions,
                prune: true,
                preserveAnswers: true,
            );
        });
    }
}
