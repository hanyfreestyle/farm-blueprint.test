<?php

namespace Database\Seeders\Questions\Workflow;

use App\Enums\Questionnaire\QuestionType;
use App\Services\Questionnaire\QuestionSeederSyncService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FatteningSaleReadinessQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $questions = [
                [
                    'seed_key' => 'fattening.entry_boundary_model',
                    'title' => 'متى يجب اعتبار الحيوان قد دخل فعليًا في مسار التسمين؟',
                    'help_text' => 'المصدر يضع التسلسل: قرار التسمين ثم التسكين ثم المتابعة. المطلوب حسم نقطة البداية التاريخية لمسار التسمين دون تكرار قرار المصير في 4.10 أو حركة التسكين في 4.2.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 1,
                    'report_category' => 'workflow_boundary',
                    'target_entity' => 'fattening_period',
                    'options' => [
                        ['label' => 'يبدأ مسار التسمين بمجرد اعتماد قرار التحويل للتسمين، حتى لو كان النقل إلى موقع التسمين ما زال معلقًا', 'value' => 'start_on_fattening_fate_decision'],
                        ['label' => 'يبدأ بعد تنفيذ حركة التسكين الفعلية في موقع صالح للتسمين', 'value' => 'start_on_fattening_housing_completion'],
                        ['label' => 'يبدأ بحدث تشغيل مستقل لتفعيل التسمين بعد قرار المصير، مع ربط حركة التسكين به عند الحاجة', 'value' => 'explicit_fattening_start_event'],
                    ],
                ],
                [
                    'seed_key' => 'fattening.start_record_fields',
                    'title' => 'ما البيانات التي يجب أن يحتفظ بها سجل بداية مسار التسمين؟',
                    'help_text' => 'المصدر يذكر تاريخ بدء التسمين والعمر والوزن وسبب التحويل. الوزن الفعلي يبقى سجلًا Canonical في 4.3، لذلك يفضل حفظ مرجعه بدل نسخ قيمة مستقلة عند اعتماد هذا النموذج.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 2,
                    'report_category' => 'field',
                    'target_entity' => 'fattening_period',
                    'options' => [
                        ['label' => 'الحيوان', 'value' => 'animal'],
                        ['label' => 'تاريخ / وقت بداية التسمين', 'value' => 'started_at'],
                        ['label' => 'العمر عند البداية', 'value' => 'age_at_start'],
                        ['label' => 'مرجع الوزن عند بداية التسمين', 'value' => 'start_weight_reference'],
                        ['label' => 'مرجع قرار المصير الذي أدى إلى التسمين', 'value' => 'source_fate_decision_reference'],
                        ['label' => 'سبب / مبرر التحويل للتسمين', 'value' => 'fattening_reason'],
                        ['label' => 'مرجع موقع التسكين عند بداية التسمين', 'value' => 'housing_reference'],
                        ['label' => 'المستخدم / منفذ بدء المسار', 'value' => 'performed_by'],
                        ['label' => 'ملاحظات', 'value' => 'notes'],
                    ],
                ],
                [
                    'seed_key' => 'fattening.individual_tracking_model',
                    'title' => 'كيف يجب الحفاظ على تتبع الحيوان داخل مسار التسمين عند تسكين عدة أرانب معًا في قفص واحد؟',
                    'help_text' => 'المصدر يؤكد أن التسمين الجماعي لا يلغي التتبع الفردي؛ لكل حيوان رقم ووزن وعمر ونسب وتاريخ مستقل. هذا السؤال يحسم هل مسار التسمين نفسه يبقى سجلًا فرديًا لكل حيوان أم يسمح بسجل جماعي بديل.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 3,
                    'report_category' => 'integrity_rule',
                    'target_entity' => 'fattening_period',
                    'options' => [
                        ['label' => 'لكل حيوان سجل تسمين مستقل دائمًا، ويمكن فقط تجميع العمليات في Session مشتركة للتسهيل', 'value' => 'individual_fattening_record_with_optional_batch_sessions'],
                        ['label' => 'يوجد سجل تسمين جماعي للقفص مع الاحتفاظ فقط بسجلات الوزن الفردية للحيوانات', 'value' => 'group_fattening_record_with_individual_weights'],
                        ['label' => 'يسمح بسجل جماعي كامل للتسمين دون Timeline مستقل لكل حيوان', 'value' => 'group_only_fattening_tracking'],
                    ],
                ],
                [
                    'seed_key' => 'fattening.progress_context_sources',
                    'title' => 'ما المعلومات التي يجب أن يعتمد عليها عرض ومراجعة تقدم الحيوان أثناء التسمين؟',
                    'help_text' => 'المرجع يعتمد على العمر والوزن ومعدل النمو والحالة الصحية ومدة التسمين. القيم المستهدفة والحدود نفسها تبقى Settings، بينما الأوزان الفعلية تأتي من 4.3.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 4,
                    'report_category' => 'workflow_context',
                    'target_entity' => 'fattening_period',
                    'options' => [
                        ['label' => 'العمر الحالي المحسوب', 'value' => 'current_age'],
                        ['label' => 'وزن بداية التسمين', 'value' => 'start_weight'],
                        ['label' => 'آخر وزن مسجل', 'value' => 'latest_weight'],
                        ['label' => 'سجل الأوزان أثناء التسمين', 'value' => 'fattening_weight_history'],
                        ['label' => 'مؤشرات النمو المحسوبة من سجل الأوزان', 'value' => 'derived_growth_metrics'],
                        ['label' => 'مدة البقاء في مسار التسمين', 'value' => 'fattening_duration'],
                        ['label' => 'الحالة / السياق الصحي المؤثر على الجاهزية', 'value' => 'health_context'],
                        ['label' => 'نتيجة آخر مراجعة أو قرار متعلق بالتسمين', 'value' => 'latest_fattening_review'],
                    ],
                ],
                [
                    'seed_key' => 'fattening.readiness_evaluation_model',
                    'title' => 'كيف يجب تحديد حالة جاهزية الحيوان للبيع داخل مسار التسمين؟',
                    'help_text' => 'المرجع يعتبر الجاهزية توصية أو حالة تشغيلية تعتمد على العمر والوزن والنمو والصحة والمدة، وليس حدث بيع. شروط الجاهزية نفسها تعرف في Settings 6.10.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 5,
                    'report_category' => 'state_model_rule',
                    'target_entity' => 'sale_readiness',
                    'options' => [
                        ['label' => 'تشتق الجاهزية تلقائيًا من البيانات الحالية وقواعد Settings دون Status يدوي', 'value' => 'derived_automatically_from_rules_and_records'],
                        ['label' => 'يحسب النظام نتيجة الجاهزية ثم تحتاج إلى مراجعة / تأكيد تشغيلي مسجل قبل اعتبار الحيوان جاهزًا للبيع', 'value' => 'derived_then_explicit_readiness_review'],
                        ['label' => 'تحدد الجاهزية يدويًا من المسؤول مع عرض البيانات المساعدة فقط', 'value' => 'manual_readiness_decision_with_context'],
                    ],
                ],
                [
                    'seed_key' => 'fattening.readiness_outcome_categories',
                    'title' => 'ما النتائج التشغيلية التي يجب أن يستطيع تقييم جاهزية البيع تمييزها؟',
                    'help_text' => 'المصدر يطرح حالات مثل غير جاهز، يقترب من الهدف، جاهز، تجاوز المدة أو العمر، ونمو أقل من المتوقع. هذه نتائج تقييم وليست بيعًا فعليًا أو خروجًا من المزرعة.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 6,
                    'report_category' => 'workflow_outcome',
                    'target_entity' => 'sale_readiness',
                    'options' => [
                        ['label' => 'غير جاهز للبيع', 'value' => 'not_ready'],
                        ['label' => 'يقترب من الهدف / يحتاج متابعة قصيرة', 'value' => 'approaching_target'],
                        ['label' => 'جاهز للبيع', 'value' => 'ready_for_sale'],
                        ['label' => 'تجاوز العمر أو مدة التسمين المستهدفة ويحتاج قرارًا', 'value' => 'target_age_or_duration_exceeded'],
                        ['label' => 'النمو أو الوزن أقل من المتوقع ويحتاج مراجعة', 'value' => 'growth_or_weight_below_target'],
                    ],
                ],
                [
                    'seed_key' => 'fattening.target_miss_review_outcomes',
                    'title' => 'إذا لم يصل الحيوان للهدف المتوقع أو تجاوز مدة التسمين، ما النتائج التي يجب أن تدعمها المراجعة التشغيلية؟',
                    'help_text' => 'المصدر يؤكد أن الحيوان لا يخرج تلقائيًا عند عدم الوصول للوزن المستهدف. المسؤول قد يقرر الاستمرار أو البيع بالوضع الحالي أو المتابعة الصحية أو الاستبعاد أو قرارًا آخر. التنفيذ الفعلي لكل مسار يبقى في القسم المختص.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 7,
                    'report_category' => 'workflow_outcome',
                    'target_entity' => 'fattening_review',
                    'options' => [
                        ['label' => 'استمرار التسمين والمتابعة', 'value' => 'continue_fattening'],
                        ['label' => 'اعتماد التوجه للبيع بالوضع / الوزن الحالي', 'value' => 'plan_sale_at_current_state'],
                        ['label' => 'تحويل لمتابعة صحية أو مراجعة صحية', 'value' => 'health_review'],
                        ['label' => 'إنشاء قرار استبعاد / تغيير مصير عبر 4.10', 'value' => 'exclusion_or_fate_change'],
                        ['label' => 'إعادة تقييم في موعد لاحق', 'value' => 'reevaluate_later'],
                        ['label' => 'قرار تشغيلي آخر موثق', 'value' => 'other_documented_decision'],
                    ],
                ],
                [
                    'seed_key' => 'fattening.review_record_fields',
                    'title' => 'ما البيانات التي يجب أن يحتفظ بها سجل مراجعة التسمين عند الحاجة إلى قرار بسبب الجاهزية أو عدم الوصول للهدف؟',
                    'help_text' => 'السجل يحفظ القرار الفعلي في لحظة المراجعة دون نسخ القيم الأصلية كمصادر ثانية؛ يمكن حفظ مراجع الأوزان ولقطة المؤشرات المستخدمة حتى يبقى سبب القرار قابلًا للتدقيق.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 8,
                    'report_category' => 'field',
                    'target_entity' => 'fattening_review',
                    'options' => [
                        ['label' => 'الحيوان / مسار التسمين', 'value' => 'fattening_period'],
                        ['label' => 'تاريخ / وقت المراجعة', 'value' => 'reviewed_at'],
                        ['label' => 'سبب استحقاق المراجعة', 'value' => 'review_trigger'],
                        ['label' => 'مرجع آخر وزن مستخدم', 'value' => 'weight_reference'],
                        ['label' => 'مرجع / لقطة مؤشرات النمو والمدة المستخدمة', 'value' => 'evaluation_context_snapshot'],
                        ['label' => 'نتيجة المراجعة', 'value' => 'review_outcome'],
                        ['label' => 'المستخدم / صاحب القرار', 'value' => 'reviewed_by'],
                        ['label' => 'ملاحظات', 'value' => 'notes'],
                    ],
                ],
                [
                    'seed_key' => 'fattening.sale_handoff_model',
                    'title' => 'عندما يصبح الحيوان جاهزًا للبيع أو يعتمد المسؤول البيع بالوضع الحالي، كيف يجب الانتقال إلى مسار البيع والخروج دون تسجيل خروج داخل 4.12؟',
                    'help_text' => 'المصدر يفرق صراحة بين الجاهزية للبيع وبين البيع الفعلي. 4.12 يحدد فقط تسليم الحالة إلى 4.15؛ تاريخ البيع ووزن الخروج وإخلاء الموقع وتغيير الوجود بالمزرعة تحدث عند Event الخروج الفعلي.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 9,
                    'report_category' => 'integration_rule',
                    'target_entity' => 'sale_transition',
                    'options' => [
                        ['label' => 'تنشئ الجاهزية / قرار البيع انتقالًا معلقًا مرتبطًا بـ4.15، ولا يخرج الحيوان حتى تنفيذ Event البيع والخروج', 'value' => 'create_pending_exit_transition'],
                        ['label' => 'تسجل الجاهزية أو قرار البيع فقط، ويبدأ المستخدم عملية الخروج في 4.15 يدويًا لاحقًا', 'value' => 'record_readiness_manual_exit_start'],
                        ['label' => 'يبدأ النظام سياق عملية البيع في 4.15 تلقائيًا مع بقاء Event الخروج الفعلي منفصلًا', 'value' => 'auto_start_exit_context_without_exit_event'],
                    ],
                ],
                [
                    'seed_key' => 'fattening.path_closure_model',
                    'title' => 'متى يجب إغلاق مسار التسمين التاريخي للحيوان؟',
                    'help_text' => 'الجاهزية أو قرار البيع لا يعنيان أن الحيوان خرج فعليًا. المطلوب تحديد Boundary إغلاق مسار التسمين مع الحفاظ على تاريخ الحالات التي ينتهي فيها المسار بسبب خروج أو تغيير مصير أو نفوق.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 10,
                    'report_category' => 'lifecycle_rule',
                    'target_entity' => 'fattening_period',
                    'options' => [
                        ['label' => 'يبقى المسار مفتوحًا حتى حدث نهائي فعلي: خروج/بيع، انتقال معتمد لمسار آخر، أو نفوق', 'value' => 'close_on_terminal_actual_event'],
                        ['label' => 'يغلق بمجرد اعتماد الجاهزية للبيع أو قرار البيع حتى لو ظل الحيوان داخل المزرعة', 'value' => 'close_on_sale_readiness_or_sale_decision'],
                        ['label' => 'يغلق عند إنشاء انتقال معلق للـWorkflow التالي قبل تنفيذ الحدث النهائي', 'value' => 'close_on_pending_transition_creation'],
                    ],
                ],
            ];

            app(QuestionSeederSyncService::class)->sync(
                mainSectionName: 'الحركات ودورة التشغيل الفعلية',
                sectionName: 'التسمين والجاهزية للبيع',
                questions: $questions,
                prune: true,
                preserveAnswers: true,
            );
        });
    }
}
