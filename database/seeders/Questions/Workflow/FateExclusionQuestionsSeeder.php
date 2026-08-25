<?php

namespace Database\Seeders\Questions\Workflow;

use App\Enums\Questionnaire\QuestionType;
use App\Services\Questionnaire\QuestionSeederSyncService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FateExclusionQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $questions = [
                [
                    'seed_key' => 'fate_decision.record_fields',
                    'title' => 'ما البيانات التي يجب أن يحتفظ بها سجل قرار المصير الفعلي للحيوان؟',
                    'help_text' => 'قرار المصير يأتي بعد تقييم أو مراجعة ويجب أن يحفظ ما تم اعتماده فعليًا دون حذف نتائج الفرز السابقة. الوزن نفسه يبقى سجلًا Canonical في 4.3، لذلك يمكن حفظ مرجعه بدل نسخ قيمة مستقلة.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 1,
                    'report_category' => 'field',
                    'target_entity' => 'fate_decision',
                    'options' => [
                        ['label' => 'الحيوان', 'value' => 'animal'],
                        ['label' => 'تاريخ / وقت اتخاذ القرار', 'value' => 'decided_at'],
                        ['label' => 'العمر وقت القرار', 'value' => 'age_at_decision'],
                        ['label' => 'مرجع الوزن المستخدم وقت القرار', 'value' => 'weight_reference'],
                        ['label' => 'مرحلة / سياق القرار', 'value' => 'decision_context'],
                        ['label' => 'مرجع التقييم أو السجل الذي أدى إلى القرار عند وجوده', 'value' => 'source_reference'],
                        ['label' => 'نوع قرار المصير', 'value' => 'decision_type'],
                        ['label' => 'مبرر / ملاحظات القرار', 'value' => 'decision_notes'],
                        ['label' => 'المستخدم / صاحب القرار', 'value' => 'decided_by'],
                    ],
                ],
                [
                    'seed_key' => 'fate_decision.source_reference_model',
                    'title' => 'كيف يجب ربط قرار المصير بالمراجعة أو التقييم الذي أدى إليه عند وجود مصدر واضح للقرار؟',
                    'help_text' => 'قد ينتج القرار من فرز في 4.9 أو مراجعة لاحقة لمرشح إحلال أو أداء حيوان إنتاجي أو سياق تشغيلي آخر. المطلوب تحديد هل نحفظ مرجعًا صريحًا للمصدر أم نكتفي باستنتاجه من الخط الزمني.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 2,
                    'report_category' => 'relationship_rule',
                    'target_entity' => 'fate_decision',
                    'options' => [
                        ['label' => 'يحفظ مرجع اختياري صريح للسجل / التقييم الذي أدى إلى القرار', 'value' => 'explicit_optional_source_reference'],
                        ['label' => 'يستنتج مصدر القرار من الخط الزمني والسياق دون مرجع مباشر', 'value' => 'derive_source_from_timeline'],
                        ['label' => 'لا يحتاج قرار المصير إلى ربط بمصدر سابق', 'value' => 'no_source_reference'],
                    ],
                ],
                [
                    'seed_key' => 'fate_decision.history_model',
                    'title' => 'كيف يجب حفظ تاريخ قرارات المصير المتعاقبة لنفس الحيوان؟',
                    'help_text' => 'المصدر يؤكد أن تغير القرار لاحقًا لا يمسح نتائج أو قرارات المراحل السابقة. المطلوب حسم النموذج الذي يحافظ على تاريخ القرار ويمكنه توضيح أن قرارًا أحدث حل محل قرار سابق.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 3,
                    'report_category' => 'history_rule',
                    'target_entity' => 'fate_decision',
                    'options' => [
                        ['label' => 'كل قرار سجل مستقل ويمكن للقرار الجديد الإشارة إلى القرار الذي عدله أو تجاوزه', 'value' => 'independent_decisions_with_supersession_link'],
                        ['label' => 'سجل قرار حالي واحد مع Audit Trail كامل لكل تغيير', 'value' => 'single_current_decision_with_full_audit'],
                    ],
                ],
                [
                    'seed_key' => 'fate_decision.outcome_categories',
                    'title' => 'ما أنواع قرار المصير الفعلي التي يجب أن يدعمها هذا القسم قبل الانتقال إلى المسارات التنفيذية التالية؟',
                    'help_text' => 'هذه قرارات معتمدة وليست مجرد توصيات فرز. تنفيذ الإحلال الفعلي ينتمي إلى 4.11، والتسمين إلى 4.12، والخروج الفعلي إلى 4.15، بينما إعادة التقييم تعود إلى 4.9.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 4,
                    'report_category' => 'workflow_outcome',
                    'target_entity' => 'fate_decision',
                    'options' => [
                        ['label' => 'اعتماد المسار كمرشح إحلال / قطيع تحت المتابعة', 'value' => 'replacement_candidate_path'],
                        ['label' => 'اعتماد التحويل إلى مسار التسمين', 'value' => 'fattening_path'],
                        ['label' => 'استبعاد الحيوان من المسار الحالي', 'value' => 'exclude_from_current_path'],
                        ['label' => 'استمرار المتابعة / إعادة التقييم قبل تغيير المسار', 'value' => 'continue_monitoring_or_reevaluation'],
                        ['label' => 'مسار آخر موثق يحدده التشغيل', 'value' => 'other_documented_path'],
                    ],
                ],
                [
                    'seed_key' => 'fate_decision.downstream_transition_model',
                    'title' => 'بعد اعتماد قرار المصير، كيف يجب تسليم الحيوان إلى الـWorkflow التنفيذي التالي دون تكرار نفس الحدث؟',
                    'help_text' => 'قرار المصير لا يجب أن يكرر أحداث الإحلال أو التسمين أو الخروج أو النقل. المطلوب تحديد هل ينشئ القرار انتقالًا معلقًا للمسار التالي أم يبدأ سياقه مباشرة أم يترك بدءه للمستخدم لاحقًا.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 5,
                    'report_category' => 'integration_rule',
                    'target_entity' => 'operational_path_transition',
                    'options' => [
                        ['label' => 'ينشئ قرار المصير انتقالًا / إجراءً معلقًا مرتبطًا بالـWorkflow التالي، والتنفيذ الفعلي يتم هناك', 'value' => 'create_pending_downstream_transition'],
                        ['label' => 'يبدأ النظام سياق الـWorkflow التالي تلقائيًا مع بقاء أحداثه الفعلية مستقلة', 'value' => 'auto_start_downstream_context'],
                        ['label' => 'يسجل القرار فقط ويبدأ المستخدم الـWorkflow التالي يدويًا لاحقًا', 'value' => 'record_decision_manual_downstream_start'],
                    ],
                ],
                [
                    'seed_key' => 'exclusion.scope_categories',
                    'title' => 'من أي أنواع المسارات يجب أن يستطيع قرار الاستبعاد إخراج الحيوان دون اعتباره خارج المزرعة تلقائيًا؟',
                    'help_text' => 'الاستبعاد يعني إنهاء أهلية أو استمرار الحيوان في مسار محدد، وليس نهاية هويته أو وجوده بالمزرعة. المصدر يدعم استبعاد مرشح الإحلال وكذلك حيوان من برنامج التربية أو القطيع الإنتاجي مع إمكانية انتقاله لمسار آخر.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 6,
                    'report_category' => 'workflow_scope',
                    'target_entity' => 'exclusion_decision',
                    'options' => [
                        ['label' => 'مسار النمو / الفرز الحالي', 'value' => 'growth_sorting_path'],
                        ['label' => 'مسار الترشيح أو الإحلال', 'value' => 'replacement_candidate_path'],
                        ['label' => 'برنامج التربية / القطيع الإنتاجي', 'value' => 'production_breeding_path'],
                        ['label' => 'مسار تشغيلي آخر محدد بوضوح', 'value' => 'other_operational_path'],
                    ],
                ],
                [
                    'seed_key' => 'exclusion.reason_reference_requirement',
                    'title' => 'عند تسجيل قرار استبعاد، ما سياسة استخدام سبب الاستبعاد المرجعي من Master Data؟',
                    'help_text' => 'قائمة أسباب الاستبعاد معرفة بالفعل في Master Data ولا تعاد هنا. هذا السؤال يحسم هل يجب أن يشير كل قرار استبعاد إلى سبب مرجعي معتمد أم يمكن ترك المرجع اختياريًا مع توثيق المبرر.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 7,
                    'report_category' => 'validation_rule',
                    'target_entity' => 'exclusion_decision',
                    'options' => [
                        ['label' => 'سبب استبعاد مرجعي معتمد إلزامي لكل قرار استبعاد', 'value' => 'master_data_reason_required'],
                        ['label' => 'السبب المرجعي مدعوم لكنه اختياري إذا وُجد تبرير نصي موثق', 'value' => 'master_data_reason_optional_with_documented_justification'],
                    ],
                ],
                [
                    'seed_key' => 'exclusion.other_reason_detail_policy',
                    'title' => 'إذا اختير سبب الاستبعاد المرجعي «سبب آخر»، كيف يجب توثيق السبب الفعلي للحالة؟',
                    'help_text' => 'وجود قيمة «سبب آخر» في Master Data لا يعني تلقائيًا وجود حقل نصي أو كونه إلزاميًا. المطلوب حسم هذا السلوك صراحة داخل واقعة الاستبعاد.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 8,
                    'report_category' => 'validation_rule',
                    'target_entity' => 'exclusion_decision',
                    'options' => [
                        ['label' => 'يجب إدخال وصف نصي للسبب الفعلي عند اختيار «سبب آخر»', 'value' => 'require_other_reason_detail'],
                        ['label' => 'يسمح بوصف نصي اختياري عند اختيار «سبب آخر»', 'value' => 'optional_other_reason_detail'],
                        ['label' => 'لا يضاف وصف خاص؛ «سبب آخر» يكفي كتصنيف مرجعي', 'value' => 'no_other_reason_detail'],
                    ],
                ],
                [
                    'seed_key' => 'exclusion.next_destination_model',
                    'title' => 'كيف يجب تمثيل المصير التالي للحيوان بعد استبعاده من مساره الحالي؟',
                    'help_text' => 'المصدر يرفض استخدام Excluded كحالة نهائية غامضة؛ بعد الاستبعاد يجب معرفة ما سيحدث بعده. هذا السؤال يحسم هل يحفظ المصير التالي داخل قرار الاستبعاد أم كسجل انتقال مستقل مرتبط به.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 9,
                    'report_category' => 'relationship_rule',
                    'target_entity' => 'exclusion_decision',
                    'options' => [
                        ['label' => 'يحفظ نوع المصير التالي داخل قرار الاستبعاد ويستخدم كمرجع لبدء الـWorkflow التالي', 'value' => 'store_next_destination_on_exclusion'],
                        ['label' => 'ينشأ سجل Fate / Transition مستقل مرتبط بقرار الاستبعاد', 'value' => 'separate_linked_next_destination_record'],
                        ['label' => 'يستنتج المصير التالي من أول Workflow لاحق دون تخزينه صراحة في قرار الاستبعاد', 'value' => 'derive_from_next_workflow_event'],
                    ],
                ],
                [
                    'seed_key' => 'exclusion.next_destination_categories',
                    'title' => 'ما المصائر التالية التي يجب أن يستطيع النظام تسجيلها بعد قرار الاستبعاد؟',
                    'help_text' => 'هذه خيارات للمصير التالي وليست الأحداث التنفيذية نفسها. بدء التسمين يتم في 4.12، والبيع أو الخروج الفعلي في 4.15، والمتابعة المؤقتة تعود لمسار تقييم أو متابعة مناسب.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 10,
                    'report_category' => 'workflow_outcome',
                    'target_entity' => 'exclusion_decision',
                    'options' => [
                        ['label' => 'التحويل إلى التسمين', 'value' => 'fattening'],
                        ['label' => 'بيع مخطط له / الانتقال إلى مسار الخروج', 'value' => 'planned_sale_or_exit'],
                        ['label' => 'متابعة مؤقتة قبل قرار جديد', 'value' => 'temporary_followup'],
                        ['label' => 'خروج نهائي مخطط له', 'value' => 'planned_final_exit'],
                        ['label' => 'مصير آخر موثق', 'value' => 'other_documented_destination'],
                    ],
                ],
                [
                    'seed_key' => 'exclusion.source_path_closure_model',
                    'title' => 'متى يعتبر المسار الذي تم استبعاد الحيوان منه منتهيًا بالنسبة لهذا الحيوان؟',
                    'help_text' => 'قد يسجل قرار الاستبعاد قبل تنفيذ النقل أو بدء التسمين أو الخروج. المطلوب حسم العلاقة بين انتهاء المسار القديم وبين تنفيذ المصير التالي مع الحفاظ على أن القرار والتنفيذ الميداني حدثان قابلان للتتبع.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 11,
                    'report_category' => 'lifecycle_rule',
                    'target_entity' => 'operational_path',
                    'options' => [
                        ['label' => 'ينتهي المسار القديم عند اعتماد قرار الاستبعاد، ويظل تنفيذ المصير التالي معلقًا بصورة مستقلة', 'value' => 'close_source_path_on_exclusion_decision'],
                        ['label' => 'يبقى المسار القديم بحالة انتظار تنفيذ حتى يبدأ المسار التالي فعليًا', 'value' => 'keep_source_path_pending_until_next_starts'],
                        ['label' => 'ينتهي المسار القديم فقط بعد اكتمال كل الإجراءات الميدانية المطلوبة للمصير التالي', 'value' => 'close_after_downstream_execution_complete'],
                    ],
                ],
            ];

            app(QuestionSeederSyncService::class)->sync(
                mainSectionName: 'الحركات ودورة التشغيل الفعلية',
                sectionName: 'تحديد المصير والاستبعاد من المسار',
                questions: $questions,
                prune: true,
                preserveAnswers: true,
            );
        });
    }
}
