<?php

namespace Database\Seeders\Questions\Workflow;

use App\Enums\Questionnaire\QuestionDependencyOperator;
use App\Enums\Questionnaire\QuestionType;
use App\Models\QuestionnaireQuestion;
use App\Models\QuestionnaireSection;
use App\Services\Questionnaire\QuestionSeederSyncService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LactationOverlapQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $questions = [
                [
                    'seed_key' => 'lactation.start_model',
                    'title' => 'كيف يجب أن تبدأ مرحلة الرضاعة للبطن بعد تسجيل الولادة؟',
                    'help_text' => 'المرجع يربط الولادة بإنشاء البطن ثم بدء الرضاعة عندما توجد مواليد أحياء. المطلوب حسم هل يبدأ المسار تلقائيًا من حدث الولادة/إنشاء البطن أم يحتاج إجراء تشغيل مستقل.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 1,
                    'report_category' => 'workflow_rule',
                    'target_entity' => 'litter',
                    'options' => [
                        ['label' => 'تبدأ الرضاعة تلقائيًا عند إنشاء بطن بها مواليد أحياء', 'value' => 'auto_start_for_litter_with_live_offspring'],
                        ['label' => 'ينشأ البطن أولًا ثم يبدأ المستخدم مرحلة الرضاعة بإجراء مستقل', 'value' => 'explicit_lactation_start_action'],
                    ],
                ],
                [
                    'seed_key' => 'lactation.current_alive_count_model',
                    'title' => 'كيف يجب تحديد العدد الحالي للمواليد الأحياء داخل البطن أثناء الرضاعة؟',
                    'help_text' => 'المرجع يفضل عدم تعديل العدد الحالي يدويًا، بل اشتقاقه من الأحياء عند الولادة ثم أحداث النفوق والنقل إلى أم أخرى والاستقبال من أم أخرى، مع بقاء أرقام الولادة الأصلية ثابتة تاريخيًا.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 2,
                    'report_category' => 'derived_state_rule',
                    'target_entity' => 'litter',
                    'options' => [
                        ['label' => 'يحسب تلقائيًا من أحداث الولادة والنفوق والنقل والاستقبال', 'value' => 'derive_from_litter_events'],
                        ['label' => 'يخزن كقيمة حالية لكن لا يتغير إلا عبر أحداث مسجلة', 'value' => 'stored_count_changed_by_events'],
                        ['label' => 'يمكن للمستخدم تعديل العدد الحالي مباشرة', 'value' => 'direct_manual_current_count'],
                    ],
                ],
                [
                    'seed_key' => 'lactation.followup_event_types',
                    'title' => 'ما أنواع المتابعة الفعلية التي يجب أن يستطيع النظام تسجيلها أو الربط بها أثناء الرضاعة؟',
                    'help_text' => 'المرجع يذكر مراجعة حالة البطن والأم، ملاحظات النمو، وجود مواليد ضعيفة، والوزن عند الحاجة. الوزن نفسه يبقى سجلًا موحدًا في 4.3 ويشار إليه من سياق الرضاعة بدل تكرار القيمة.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 3,
                    'report_category' => 'workflow_model',
                    'target_entity' => 'lactation_followup',
                    'options' => [
                        ['label' => 'مراجعة الحالة العامة للبطن', 'value' => 'litter_condition_review'],
                        ['label' => 'مراجعة حالة الأم مع المواليد', 'value' => 'mother_with_litter_review'],
                        ['label' => 'تسجيل وجود مواليد ضعيفة أو تحتاج متابعة', 'value' => 'weak_offspring_observation'],
                        ['label' => 'ملاحظات النمو والتطور', 'value' => 'growth_observation'],
                        ['label' => 'ربط قياس وزن مسجل في 4.3 بسياق الرضاعة', 'value' => 'weight_measurement_reference'],
                        ['label' => 'متابعة أخرى يحددها التشغيل', 'value' => 'other_followup'],
                    ],
                ],
                [
                    'seed_key' => 'lactation.mortality_recording_model',
                    'title' => 'كيف يجب تسجيل نفوق المواليد أثناء الرضاعة وربطه بالبطن؟',
                    'help_text' => 'النافق عند الولادة جزء من نتيجة 4.6، أما النفوق بعد الولادة فهو حدث جديد. لتجنب تكرار سجل النفوق، يمكن أن يكون الحدث الأساسي في 4.13 ويرتبط بالبطن وسياق الرضاعة، ثم يؤثر في العدد الحالي دون تعديل live_born التاريخي.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 4,
                    'report_category' => 'integration_rule',
                    'target_entity' => 'litter_mortality',
                    'options' => [
                        ['label' => 'يستخدم سجل النفوق العام في 4.13 مع ربطه بالبطن وسياق الرضاعة، ويشتق منه العدد الحالي', 'value' => 'use_canonical_mortality_event_linked_to_litter'],
                        ['label' => 'ينشأ حدث نفوق خاص بالرضاعة منفصل عن سجل النفوق العام', 'value' => 'dedicated_lactation_mortality_event'],
                        ['label' => 'يخفض العدد الحالي فقط دون الاحتفاظ بحدث نفوق مستقل', 'value' => 'adjust_count_without_mortality_event'],
                    ],
                ],
                [
                    'seed_key' => 'lactation.foster_transfer_model',
                    'title' => 'كيف يجب دعم نقل المواليد بين الأمهات أثناء الرضاعة؟',
                    'help_text' => 'المرجع يعتبر نقل مواليد من بطن إلى أم/بطن أخرى حركة فعلية مهمة يجب أن تحفظ المصدر والمستقبل والتاريخ، مع عدم تغيير الأم البيولوجية. هذا السؤال يحسم هل تكون الحركة Structured Workflow أم معالجة أبسط.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 5,
                    'report_category' => 'workflow_model',
                    'target_entity' => 'foster_transfer',
                    'options' => [
                        ['label' => 'حركة منظمة مستقلة لنقل/استقبال المواليد بين البطون والأمهات', 'value' => 'structured_foster_transfer'],
                        ['label' => 'تسجل كحالة استثنائية عامة مع ملاحظات دون نموذج نقل مخصص', 'value' => 'generic_exception_with_notes'],
                        ['label' => 'لا يدعم النظام نقل المواليد بين الأمهات', 'value' => 'not_supported'],
                    ],
                ],
                [
                    'seed_key' => 'lactation.foster_transfer_scopes',
                    'title' => 'ما نطاقات نقل المواليد التي يجب أن تدعمها حركة الحضانة البديلة؟',
                    'help_text' => 'المصدر يدعم نقل مولود أو عدة مواليد، وقد يلزم توزيع المواليد على أكثر من أم. المطلوب تحديد النطاقات المطلوبة دون وضع حدود عددية؛ حدود السعة وشروط الأم الحاضنة مكانها Settings.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 6,
                    'report_category' => 'workflow_scope',
                    'target_entity' => 'foster_transfer',
                    'options' => [
                        ['label' => 'نقل جزء من البطن إلى بطن/أم أخرى', 'value' => 'partial_transfer'],
                        ['label' => 'نقل جميع المواليد الأحياء من البطن', 'value' => 'full_transfer'],
                        ['label' => 'توزيع مواليد بطن واحدة على أكثر من أم حاضنة', 'value' => 'multi_destination_distribution'],
                        ['label' => 'استقبال مواليد من بطن أخرى داخل البطن الحالية', 'value' => 'receive_from_other_litter'],
                    ],
                ],
                [
                    'seed_key' => 'lactation.foster_transfer_record_fields',
                    'title' => 'ما البيانات التي يجب أن يحتفظ بها سجل نقل المواليد بين الأمهات؟',
                    'help_text' => 'السجل يجب أن يوضح ما حدث فعليًا ويتيح إعادة بناء العدد الحالي والأصل التاريخي للمواليد. العمر يمكن حسابه من تاريخ الولادة عندما يكون الأصل معروفًا.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 7,
                    'report_category' => 'field',
                    'target_entity' => 'foster_transfer',
                    'options' => [
                        ['label' => 'البطن المصدر', 'value' => 'source_litter'],
                        ['label' => 'البطن / الأم الحاضنة المستقبلة', 'value' => 'destination_litter_or_foster_mother'],
                        ['label' => 'عدد المواليد المنقولة', 'value' => 'offspring_count'],
                        ['label' => 'تاريخ / وقت النقل', 'value' => 'occurred_at'],
                        ['label' => 'العمر وقت النقل', 'value' => 'age_at_transfer'],
                        ['label' => 'سبب النقل', 'value' => 'reason'],
                        ['label' => 'المستخدم / منفذ العملية', 'value' => 'performed_by'],
                        ['label' => 'ملاحظات', 'value' => 'notes'],
                    ],
                ],
                [
                    'seed_key' => 'lactation.transferred_offspring_tracking_model',
                    'title' => 'كيف يجب تتبع المواليد المنقولين قبل الفطام وقبل حصولهم على الكود الفردي النهائي؟',
                    'help_text' => 'هذه نقطة تصميمية مفتوحة في المرجع: إذا نقلنا بعض المواليد فقط، يجب عند الفطام معرفة أي أفراد أصلهم من أي بطن. المطلوب اختيار طريقة تحقق ذلك دون تقديم الترقيم الفردي النهائي قبل 4.8.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 8,
                    'report_category' => 'identity_rule',
                    'target_entity' => 'preweaning_offspring',
                    'options' => [
                        ['label' => 'معرف داخلي مؤقت لكل مولود منقول حتى يتحول إلى هوية فردية عند الفطام', 'value' => 'temporary_identifier_per_transferred_offspring'],
                        ['label' => 'مجموعة/دفعة نقل مؤقتة تحفظ الأصل والعدد ثم توزع على الأفراد عند الفطام', 'value' => 'temporary_transfer_batch_resolved_at_weaning'],
                        ['label' => 'تتبع بالعدد فقط ثم يحدد الأصل يدويًا عند الفطام', 'value' => 'quantity_only_manual_origin_at_weaning'],
                    ],
                ],
                [
                    'seed_key' => 'lactation.foster_relationship_model',
                    'title' => 'كيف يجب أن يؤثر نقل المواليد لأم حاضنة على علاقات الأصل والرعاية؟',
                    'help_text' => 'المرجع يؤكد أن الأم البيولوجية لا تتغير عند نقل المولود للرضاعة عند أم أخرى. المطلوب حسم تمثيل علاقة الرعاية الحالية مع الحفاظ على البطن الأصلية والنسب.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 9,
                    'report_category' => 'relationship_rule',
                    'target_entity' => 'foster_transfer',
                    'options' => [
                        ['label' => 'يبقى الأصل البيولوجي والبطن الأصلية ثابتين وتضاف علاقة أم حاضنة/مجموعة رضاعة حالية منفصلة', 'value' => 'preserve_biological_origin_add_foster_relation'],
                        ['label' => 'ينتقل المولود إلى البطن الجديدة وتصبح هي الأصل المسجل له', 'value' => 'replace_origin_with_destination_litter'],
                        ['label' => 'لا تحفظ علاقة منظمة ويكتفى بالملاحظات', 'value' => 'notes_only_foster_relation'],
                    ],
                ],
                [
                    'seed_key' => 'lactation.maternal_problem_outcomes',
                    'title' => 'ما الإجراءات التشغيلية التي يجب أن يستطيع النظام تسجيلها عند وجود مشكلة رضاعة أو أمومة تؤثر على البطن؟',
                    'help_text' => 'إذا كان السبب مرضيًا أو صحيًا يسجل أصله في المسار الصحي 4.13، بينما هذا السؤال يحدد الإجراءات التي حدثت للبطن نتيجة المشكلة. شروط السماح بالفطام المبكر أو اختيار الأم الحاضنة تبقى في Settings.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 10,
                    'report_category' => 'workflow_outcome',
                    'target_entity' => 'lactation_management_action',
                    'options' => [
                        ['label' => 'استمرار المتابعة دون نقل', 'value' => 'continue_monitoring'],
                        ['label' => 'نقل المواليد إلى أم حاضنة', 'value' => 'transfer_to_foster_mother'],
                        ['label' => 'توزيع المواليد على أكثر من أم', 'value' => 'distribute_across_foster_mothers'],
                        ['label' => 'تسجيل رعاية بديلة', 'value' => 'alternative_care'],
                        ['label' => 'الانتقال إلى فطام مبكر إذا سمحت القواعد', 'value' => 'early_weaning_if_allowed'],
                        ['label' => 'إجراء آخر موثق', 'value' => 'other_action'],
                    ],
                ],
                [
                    'seed_key' => 'lactation.concurrent_cycle_link_model',
                    'title' => 'عند بدء دورة تناسلية جديدة للأم أثناء استمرار رضاعة بطن نشطة، كيف يجب ربط المسارين تاريخيًا؟',
                    'help_text' => 'المعمارية المعتمدة تسمح بتداخل الدورات، والـMating نفسه يستخدم Workflow 4.4. المطلوب هنا فقط حسم هل تحفظ علاقة صريحة بين الدورة الجديدة والبطن التي كانت الأم ترضعها عند بدايتها أم يكتفى بالاستنتاج من التواريخ.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 11,
                    'report_category' => 'relationship_rule',
                    'target_entity' => 'reproductive_cycle',
                    'options' => [
                        ['label' => 'يحفظ مرجع صريح للبطن النشطة / سياق الرضاعة عند بدء الدورة الجديدة', 'value' => 'explicit_active_litter_context_link'],
                        ['label' => 'تستنتج العلاقة من الأم وتداخل التواريخ دون مرجع صريح', 'value' => 'derive_overlap_from_mother_and_dates'],
                        ['label' => 'لا يحتاج النظام لربط أو استنتاج العلاقة بين المسارين', 'value' => 'no_overlap_relation_needed'],
                    ],
                ],
                [
                    'seed_key' => 'lactation.end_model',
                    'title' => 'كيف يجب تحديد انتهاء مرحلة الرضاعة للبطن؟',
                    'help_text' => 'الفطام الفعلي هو الحد الطبيعي للمرحلة في 4.8، لكن قد توجد حالات ينتهي فيها وجود مواليد تحت رعاية البطن قبل الفطام مثل فقد جميع المواليد أو نقلهم جميعًا. المطلوب منع إغلاق يدوي غامض والحفاظ على سبب النهاية كحدث.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 12,
                    'report_category' => 'lifecycle_rule',
                    'target_entity' => 'litter',
                    'options' => [
                        ['label' => 'تنتهي طبيعيًا بحدث الفطام، أو بحدث موثق آخر ينهي وجود مواليد تحت الرعاية قبل الفطام', 'value' => 'end_by_weaning_or_documented_terminal_event'],
                        ['label' => 'لا تنتهي إلا بحدث الفطام حتى لو لم يعد هناك مواليد تحت الرعاية', 'value' => 'end_only_by_weaning'],
                        ['label' => 'يمكن للمستخدم إغلاق مرحلة الرضاعة يدويًا دون حدث محدد', 'value' => 'manual_lactation_close'],
                    ],
                ],
            ];

            app(QuestionSeederSyncService::class)->sync(
                mainSectionName: 'الحركات ودورة التشغيل الفعلية',
                sectionName: 'الرضاعة ومتابعة البطن وتداخل دورات الأم',
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

        $transferModel = $questions->get('lactation.foster_transfer_model');

        if (! $transferModel) {
            return;
        }

        foreach ([
            'lactation.foster_transfer_scopes',
            'lactation.foster_transfer_record_fields',
            'lactation.transferred_offspring_tracking_model',
            'lactation.foster_relationship_model',
        ] as $dependentSeedKey) {
            $dependentQuestion = $questions->get($dependentSeedKey);

            if (! $dependentQuestion) {
                continue;
            }

            $dependentQuestion->forceFill([
                'depends_on_question_id' => $transferModel->id,
                'dependency_operator' => QuestionDependencyOperator::EQUALS,
                'dependency_value' => 'structured_foster_transfer',
            ])->save();
        }
    }

    private function resolveSection(): ?QuestionnaireSection
    {
        $mainSection = QuestionnaireSection::query()
            ->whereNull('parent_id')
            ->where('name', 'الحركات ودورة التشغيل الفعلية')
            ->first();

        if (! $mainSection) {
            return null;
        }

        return QuestionnaireSection::query()
            ->where('parent_id', $mainSection->id)
            ->where('name', 'الرضاعة ومتابعة البطن وتداخل دورات الأم')
            ->first();
    }
}
