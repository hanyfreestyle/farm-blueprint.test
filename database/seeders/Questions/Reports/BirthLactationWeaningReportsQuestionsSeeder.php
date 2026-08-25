<?php

namespace Database\Seeders\Questions\Reports;

use App\Enums\Questionnaire\QuestionType;
use App\Services\Questionnaire\QuestionSeederSyncService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BirthLactationWeaningReportsQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $questions = [
                [
                    'seed_key' => 'birth_lactation_weaning_report.primary_views',
                    'title' => 'ما العروض الرئيسية التي يجب أن يوفرها قسم تقارير الولادة والرضاعة والفطام؟',
                    'help_text' => 'المطلوب تحليل رحلة البطن من الولادة حتى الفطام كوحدة مترابطة، دون تكرار تحليل الخصوبة والحمل في 5.3 أو النمو بعد الفطام في 5.5.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 1,
                    'report_category' => 'report_scope',
                    'target_entity' => 'litter_lifecycle_report',
                    'options' => [
                        ['label' => 'تقرير نتائج الولادات', 'value' => 'birth_outcomes'],
                        ['label' => 'تقرير الرضاعة والبقاء قبل الفطام', 'value' => 'lactation_and_preweaning_survival'],
                        ['label' => 'تقرير نقل المواليد بين الأمهات / الحضانة البديلة عند استخدامها', 'value' => 'foster_transfer_analysis'],
                        ['label' => 'تقرير نتائج الفطام', 'value' => 'weaning_outcomes'],
                        ['label' => 'عرض مترابط لمسار البطن من الولادة حتى الفطام', 'value' => 'integrated_litter_lifecycle'],
                    ],
                ],
                [
                    'seed_key' => 'birth_report.metrics',
                    'title' => 'ما المؤشرات التي يجب أن يتضمنها تقرير الولادات خلال فترة؟',
                    'help_text' => 'يعتمد التقرير على أحداث الولادة الفعلية وأعداد المواليد المسجلة في 4.6. لا يعيد تعريف طريقة تسجيل الولادة أو أعدادها.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 2,
                    'report_category' => 'report_metric',
                    'target_entity' => 'birth_report',
                    'options' => [
                        ['label' => 'عدد الولادات', 'value' => 'birth_count'],
                        ['label' => 'إجمالي المواليد', 'value' => 'total_born'],
                        ['label' => 'الأحياء عند الولادة', 'value' => 'live_born'],
                        ['label' => 'النافقون عند الولادة', 'value' => 'stillborn_at_birth'],
                        ['label' => 'متوسط حجم البطن عند الولادة', 'value' => 'average_litter_size_at_birth'],
                        ['label' => 'الولادات بدون مواليد أحياء', 'value' => 'births_without_live_offspring'],
                        ['label' => 'الولادات المبكرة مقارنة بالفترة المتوقعة', 'value' => 'early_births'],
                        ['label' => 'الولادات المتأخرة مقارنة بالفترة المتوقعة', 'value' => 'late_births'],
                        ['label' => 'الولادات المرتبطة بحالة استثنائية مسجلة', 'value' => 'exceptional_births'],
                    ],
                ],
                [
                    'seed_key' => 'birth_report.timing_classification_source',
                    'title' => 'من أين يجب أن يأتي تصنيف الولادة إلى مبكرة / داخل الفترة المتوقعة / متأخرة؟',
                    'help_text' => 'فترة الولادة المتوقعة ناتجة من الحمل والإعدادات المطبقة على الدورة، بينما التقرير يعرض المقارنة فقط ولا يحدد عدد أيام الحمل أو الحدود بنفسه.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 3,
                    'report_category' => 'derivation_rule',
                    'target_entity' => 'birth_report',
                    'options' => [
                        ['label' => 'يشتق تلقائيًا من تاريخ الولادة الفعلي مقارنة بفترة الولادة المتوقعة المحفوظة / المطبقة على الحمل', 'value' => 'derive_from_actual_birth_vs_expected_window'],
                        ['label' => 'يعرض التاريخ الفعلي والمتوقع فقط دون إنشاء تصنيف مبكر / متأخر', 'value' => 'show_dates_without_timing_classification'],
                        ['label' => 'يعتمد على تصنيف مسجل بالفعل مع حدث الولادة إذا كان الـWorkflow يحفظه', 'value' => 'use_recorded_workflow_timing_classification'],
                    ],
                ],
                [
                    'seed_key' => 'lactation_report.metrics',
                    'title' => 'ما المؤشرات التي يجب أن يتضمنها تقرير الرضاعة قبل الفطام؟',
                    'help_text' => 'العدد الحالي للمواليد ينتج من الأحياء عند الولادة ثم أحداث النفوق والنقل والاستقبال. التقرير يحلل هذه النتائج ولا يسمح بتعديل العدد من خلاله.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 4,
                    'report_category' => 'report_metric',
                    'target_entity' => 'lactation_report',
                    'options' => [
                        ['label' => 'عدد البطون تحت الرضاعة', 'value' => 'active_lactating_litters'],
                        ['label' => 'عدد المواليد الأحياء الحالي داخل البطون', 'value' => 'current_alive_offspring'],
                        ['label' => 'عدد حالات النفوق أثناء الرضاعة', 'value' => 'lactation_mortality_count'],
                        ['label' => 'نسبة النفوق قبل الفطام', 'value' => 'preweaning_mortality_rate'],
                        ['label' => 'عدد البطون التي فقدت جميع مواليدها', 'value' => 'complete_litter_loss_count'],
                        ['label' => 'متوسط مدة الرضاعة للبطون المكتملة', 'value' => 'average_lactation_duration'],
                        ['label' => 'عدد البطون التي حدث بها نقل / استقبال مواليد', 'value' => 'litters_with_foster_transfer'],
                    ],
                ],
                [
                    'seed_key' => 'lactation_report.mortality_boundary',
                    'title' => 'كيف يجب الفصل في التقارير بين النافق عند الولادة والنفوق الذي حدث لاحقًا أثناء الرضاعة؟',
                    'help_text' => 'النافق عند الولادة جزء من نتيجة حدث الولادة في 4.6، بينما النفوق بعد الولادة حدث مستقل في 4.13 مرتبط بالبطن. المطلوب منع العد المزدوج والحفاظ على دلالة كل مرحلة.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 5,
                    'report_category' => 'calculation_rule',
                    'target_entity' => 'litter_mortality_report',
                    'options' => [
                        ['label' => 'يعرضهما كمؤشرين منفصلين دائمًا: نافق عند الولادة + نفوق بعد الولادة قبل الفطام', 'value' => 'always_separate_birth_and_postbirth_mortality'],
                        ['label' => 'يعرضهما منفصلين مع إمكانية إظهار إجمالي الفقد من الولادة حتى الفطام كمؤشر إضافي', 'value' => 'separate_with_optional_total_loss'],
                        ['label' => 'يدمجهما في إجمالي نفوق واحد قبل الفطام', 'value' => 'combine_as_single_preweaning_mortality_total'],
                    ],
                ],
                [
                    'seed_key' => 'lactation_report.foster_transfer_analysis',
                    'title' => 'إلى أي مستوى يجب أن يحلل التقرير نقل المواليد بين الأمهات أثناء الرضاعة؟',
                    'help_text' => '4.7 يحافظ على البطن الأصلية والأم البيولوجية وعلاقة الأم الحاضنة. التقرير يجب ألا يغيّر النسب أو ينسب المولود للحاضنة كأصل بيولوجي.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 6,
                    'report_category' => 'report_model',
                    'target_entity' => 'foster_transfer_report',
                    'options' => [
                        ['label' => 'إظهار عدد عمليات النقل وعدد المواليد المنقولة فقط', 'value' => 'transfer_counts_only'],
                        ['label' => 'إظهار المصدر والمستقبل والأعداد لكل حركة مع ملخص إجمالي', 'value' => 'transfer_details_with_summary'],
                        ['label' => 'إظهار الحركات مع تتبع نتائج المواليد المنقولة حتى الفطام مع الحفاظ على الأصل البيولوجي', 'value' => 'transfer_history_with_outcomes_to_weaning'],
                    ],
                ],
                [
                    'seed_key' => 'weaning_report.metrics',
                    'title' => 'ما المؤشرات التي يجب أن يتضمنها تقرير الفطام؟',
                    'help_text' => 'يعتمد التقرير على أحداث الفطام الفعلية في 4.8 وسجلات الوزن Canonical عند الحاجة، ولا يعيد تعريف شروط العمر أو الوزن المطلوبة للفطام.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 7,
                    'report_category' => 'report_metric',
                    'target_entity' => 'weaning_report',
                    'options' => [
                        ['label' => 'عدد البطون التي تم فطامها', 'value' => 'weaned_litter_count'],
                        ['label' => 'إجمالي عدد المفطومين', 'value' => 'total_weaned_offspring'],
                        ['label' => 'متوسط عدد المفطومين لكل بطن', 'value' => 'average_weaned_per_litter'],
                        ['label' => 'متوسط عمر الفطام', 'value' => 'average_weaning_age'],
                        ['label' => 'متوسط وزن الفطام', 'value' => 'average_weaning_weight'],
                        ['label' => 'أقل وزن عند الفطام', 'value' => 'minimum_weaning_weight'],
                        ['label' => 'أعلى وزن عند الفطام', 'value' => 'maximum_weaning_weight'],
                        ['label' => 'حالات الفطام المبكر مقارنة بالقواعد المطبقة', 'value' => 'early_weaning_cases'],
                        ['label' => 'حالات الفطام المتأخر مقارنة بالقواعد المطبقة', 'value' => 'late_weaning_cases'],
                    ],
                ],
                [
                    'seed_key' => 'weaning_report.partial_weaning_handling',
                    'title' => 'إذا كان النظام يدعم الفطام الجزئي، كيف يجب احتسابه داخل تقارير الفطام؟',
                    'help_text' => '4.8 يحسم أصلًا هل الفطام الجزئي مدعوم. إذا كان موجودًا، يجب منع عد البطن عدة مرات بصورة مضللة مع الحفاظ على أحداث الفطام الفعلية.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 8,
                    'report_category' => 'calculation_rule',
                    'target_entity' => 'weaning_report',
                    'options' => [
                        ['label' => 'يحسب كل حدث فطام كعملية، ويحسب البطن كمكتملة مرة واحدة فقط عند فطام آخر فرد', 'value' => 'count_events_and_finalize_litter_once'],
                        ['label' => 'يعتمد التقرير فقط على لحظة اكتمال فطام البطن ويتجاهل الأحداث الجزئية كعمليات مستقلة', 'value' => 'report_only_final_litter_completion'],
                        ['label' => 'يعرض مؤشرين منفصلين: عمليات الفطام الجزئي + البطون المكتملة الفطام', 'value' => 'separate_partial_events_and_completed_litters'],
                    ],
                ],
                [
                    'seed_key' => 'weaning_report.weight_source',
                    'title' => 'من أين يجب أن تأتي أوزان الفطام المستخدمة في التقرير؟',
                    'help_text' => 'الوزن الفعلي له سجل Canonical في 4.3. المطلوب منع إنشاء قيمة وزن تقريرية مستقلة قد تختلف عن سجل القياس الأصلي.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 9,
                    'report_category' => 'data_source_rule',
                    'target_entity' => 'weaning_report',
                    'options' => [
                        ['label' => 'من سجلات الوزن Canonical المرتبطة مباشرة بعملية / سياق الفطام', 'value' => 'canonical_weights_linked_to_weaning'],
                        ['label' => 'من أقرب وزن مسجل لتاريخ الفطام وفق قاعدة زمنية تحدد لاحقًا', 'value' => 'nearest_weight_to_weaning_by_rule'],
                        ['label' => 'من قيمة وزن مستقلة تحفظ داخل حدث الفطام حتى لو وُجد سجل وزن عام', 'value' => 'independent_weight_inside_weaning_event'],
                    ],
                ],
                [
                    'seed_key' => 'litter_report.survival_to_weaning_model',
                    'title' => 'كيف يجب احتساب وإسناد معدل البقاء حتى الفطام عند وجود نقل مواليد بين الأمهات؟',
                    'help_text' => 'التصور يعرّف المؤشر أساسًا بعدد المفطومين ÷ عدد الأحياء عند الولادة × 100. نقل المواليد بين الأمهات يجعل من المهم تحديد هل الأداء ينسب للبطن البيولوجية أم لمجموعة الرضاعة الفعلية.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 10,
                    'report_category' => 'calculation_rule',
                    'target_entity' => 'litter_survival_report',
                    'options' => [
                        ['label' => 'يحسب حسب البطن البيولوجية: مفطومو نفس الأصل ÷ الأحياء عند ولادتهم، مع تتبع النقل', 'value' => 'biological_litter_survival'],
                        ['label' => 'يحسب حسب مجموعة الرضاعة / الأم الحاضنة الفعلية التي وصل منها المولود إلى الفطام', 'value' => 'foster_lactation_group_survival'],
                        ['label' => 'يعرض المؤشرين معًا: بقاء حسب الأصل البيولوجي + نتيجة الرعاية لدى الأم الحاضنة', 'value' => 'both_biological_and_foster_survival_views'],
                    ],
                ],
                [
                    'seed_key' => 'litter_report.integrated_lifecycle_view',
                    'title' => 'كيف يجب عرض النتيجة المترابطة للبطن من الولادة حتى الفطام؟',
                    'help_text' => 'الهدف رؤية مسار واحد يفسر كيف انتقل البطن من أعداد الولادة إلى العدد المفطوم، مع إظهار النفوق والنقل والاستقبال دون استبدال السجلات التاريخية الأصلية.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 11,
                    'report_category' => 'presentation_rule',
                    'target_entity' => 'litter_lifecycle_report',
                    'options' => [
                        ['label' => 'ملخص أرقام نهائية للولادة والرضاعة والفطام فقط', 'value' => 'stage_summaries_only'],
                        ['label' => 'مسار عددي مترابط: أحياء عند الولادة → نفوق / نقل / استقبال → مفطومون', 'value' => 'linked_quantity_funnel'],
                        ['label' => 'المسار العددي مع Timeline للأحداث المهمة وإمكانية فتح مصدر كل تغير', 'value' => 'linked_funnel_with_event_timeline'],
                    ],
                ],
                [
                    'seed_key' => 'litter_report.drilldown_model',
                    'title' => 'إلى أي مستوى يجب أن يستطيع المستخدم النزول من مؤشرات الولادة والرضاعة والفطام؟',
                    'help_text' => 'الأرقام يجب أن تكون قابلة للمراجعة إلى البطون والأحداث الأصلية. الصفحة التحليلية الكاملة للبطن ككيان ستراجع لاحقًا في 5.12.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 12,
                    'report_category' => 'interaction_rule',
                    'target_entity' => 'litter_lifecycle_report',
                    'options' => [
                        ['label' => 'الأرقام والنسب الإجمالية فقط', 'value' => 'aggregate_only'],
                        ['label' => 'من المؤشر إلى قائمة البطون الداخلة في الحساب', 'value' => 'drilldown_to_litters'],
                        ['label' => 'من المؤشر إلى البطن ثم إلى أحداث الولادة والنفوق والنقل والفطام Canonical المفسرة للنتيجة', 'value' => 'drilldown_to_litters_and_source_events'],
                    ],
                ],
            ];

            app(QuestionSeederSyncService::class)->sync(
                mainSectionName: 'التقارير والتحليلات والتنبيهات ومؤشرات الأداء',
                sectionName: 'تقارير الولادة والرضاعة والفطام',
                questions: $questions,
                prune: true,
                preserveAnswers: true,
            );
        });
    }
}
