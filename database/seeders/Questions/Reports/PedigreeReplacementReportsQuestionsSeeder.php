<?php

namespace Database\Seeders\Questions\Reports;

use App\Enums\Questionnaire\QuestionType;
use App\Services\Questionnaire\QuestionSeederSyncService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PedigreeReplacementReportsQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $questions = [
                [
                    'seed_key' => 'pedigree_replacement_report.primary_views',
                    'title' => 'ما العروض الرئيسية التي يجب أن يوفرها قسم تقارير النسب والإحلال؟',
                    'help_text' => 'المطلوب تحديد نطاق هذا القسم التحليلي دون إعادة تعريف علاقات النسب في 3.3 أو قرارات الترشيح والاعتماد الفعلية في 4.11.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 1,
                    'report_category' => 'report_scope',
                    'target_entity' => 'pedigree_replacement_report',
                    'options' => [
                        ['label' => 'شجرة العائلة والعلاقات الوراثية للحيوان', 'value' => 'family_tree'],
                        ['label' => 'تحليل القرابة بين ذكر وأنثى', 'value' => 'kinship_analysis'],
                        ['label' => 'انتشار الآباء والأمهات والخطوط الوراثية داخل القطيع', 'value' => 'genetic_line_spread'],
                        ['label' => 'حالة ومسار مرشحي الإحلال', 'value' => 'replacement_pipeline'],
                        ['label' => 'نتائج الاعتماد والرفض في الإحلال', 'value' => 'replacement_outcomes'],
                    ],
                ],
                [
                    'seed_key' => 'pedigree_report.family_tree_relationships',
                    'title' => 'ما العلاقات التي يجب أن يستطيع عرض شجرة العائلة إظهارها عند توافر بياناتها؟',
                    'help_text' => 'شجرة العائلة تبنى من علاقات النسب المعتمدة في 3.3 ولا تنشئ علاقات جديدة داخل التقرير. المصدر يذكر الأب والأم والأجداد والإخوة والأبناء والأحفاد.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 2,
                    'report_category' => 'report_content',
                    'target_entity' => 'animal_family_tree_report',
                    'options' => [
                        ['label' => 'الأب', 'value' => 'father'],
                        ['label' => 'الأم', 'value' => 'mother'],
                        ['label' => 'الأجداد حسب البيانات المتاحة', 'value' => 'ancestors'],
                        ['label' => 'الإخوة', 'value' => 'siblings'],
                        ['label' => 'الأبناء', 'value' => 'offspring'],
                        ['label' => 'الأحفاد / الأجيال اللاحقة عند توافرها', 'value' => 'descendants'],
                    ],
                ],
                [
                    'seed_key' => 'pedigree_report.incomplete_data_presentation',
                    'title' => 'كيف يجب أن يتعامل عرض النسب مع الحيوانات التي تكون بيانات نسبها جزئية أو غير معروفة؟',
                    'help_text' => '3.3 يدعم نسبًا كاملًا أو جزئيًا أو غير معروف، ولا يجوز اختراع علاقات لإكمال الشجرة. هذا السؤال يحدد طريقة عرض النقص فقط.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 3,
                    'report_category' => 'presentation_rule',
                    'target_entity' => 'animal_family_tree_report',
                    'options' => [
                        ['label' => 'إظهار العلاقات المعروفة فقط مع تمييز الفروع أو العلاقات غير المعروفة بوضوح', 'value' => 'show_known_and_mark_unknown'],
                        ['label' => 'إظهار الشجرة المتاحة مع مستوى اكتمال النسب ومصدر توثيق العلاقات عند توفره', 'value' => 'show_tree_with_completeness_and_evidence'],
                        ['label' => 'إخفاء شجرة العائلة إذا لم يكن النسب كاملًا', 'value' => 'hide_tree_when_incomplete'],
                    ],
                ],
                [
                    'seed_key' => 'pedigree_report.kinship_analysis_model',
                    'title' => 'كيف يجب أن يعرض تقرير النسب نتيجة فحص القرابة بين ذكر وأنثى؟',
                    'help_text' => 'التقرير يحلل العلاقة الوراثية وفق البيانات وقواعد القرابة المعتمدة. قرار السماح أو التحذير أو المنع أثناء التلقيح يبقى في Workflow / Settings ولا ينفذه التقرير بنفسه.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 4,
                    'report_category' => 'report_model',
                    'target_entity' => 'kinship_report',
                    'options' => [
                        ['label' => 'عرض مستوى / نوع القرابة فقط', 'value' => 'relationship_level_only'],
                        ['label' => 'عرض مستوى القرابة مع المسار المشترك أو الأسلاف المشتركة التي تفسر النتيجة', 'value' => 'relationship_with_common_ancestry'],
                        ['label' => 'عرض مستوى القرابة والأسلاف المشتركة مع نتيجة القاعدة الحالية: مسموح / تحذير / غير مسموح', 'value' => 'relationship_ancestry_and_current_rule_result'],
                    ],
                ],
                [
                    'seed_key' => 'pedigree_report.genetic_spread_metrics',
                    'title' => 'ما المؤشرات التي يجب أن يتضمنها تقرير انتشار الآباء والأمهات والخطوط الوراثية؟',
                    'help_text' => 'المصدر يقترح معرفة مدى انتشار بعض الآباء والأمهات وعدد الأفراد الناتجين منهم ومدى تركّز القطيع حول عدد محدود من الأصول.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 5,
                    'report_category' => 'report_metric',
                    'target_entity' => 'genetic_spread_report',
                    'options' => [
                        ['label' => 'أكثر الذكور انتشارًا من حيث الأبناء الموجودين أو المحتفظ بهم', 'value' => 'most_represented_males'],
                        ['label' => 'أكثر الإناث التي لها أبناء محتفظ بهم', 'value' => 'females_with_most_retained_offspring'],
                        ['label' => 'عدد الأفراد الناتجين من كل أصل / خط عند استخدام مفهوم الخط الوراثي', 'value' => 'individuals_per_line'],
                        ['label' => 'نسبة تمثيل كل أب أو أم داخل القطيع الحالي', 'value' => 'parent_representation_share'],
                        ['label' => 'مدى تركّز القطيع حول عدد محدود من الآباء أو الأمهات', 'value' => 'parental_concentration'],
                    ],
                ],
                [
                    'seed_key' => 'pedigree_report.genetic_spread_population_scope',
                    'title' => 'ما النطاق السكاني الأساسي الذي يجب استخدامه عند حساب انتشار الأصول الوراثية؟',
                    'help_text' => 'نفس الأب قد يكون له أبناء تاريخيون كثيرون لكن الموجود حاليًا في القطيع أقل. المطلوب حسم النطاق الأساسي حتى لا تكون نسب الانتشار مضللة.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 6,
                    'report_category' => 'calculation_rule',
                    'target_entity' => 'genetic_spread_report',
                    'options' => [
                        ['label' => 'الحيوانات الموجودة حاليًا في المزرعة فقط', 'value' => 'current_present_animals'],
                        ['label' => 'أفراد القطيع الإنتاجي الحالي ومرشحو الإحلال فقط', 'value' => 'current_production_and_replacement_population'],
                        ['label' => 'إتاحة أكثر من نطاق: القطيع الحالي أو جميع الحيوانات التاريخية حسب التحليل', 'value' => 'selectable_current_or_historical_scope'],
                    ],
                ],
                [
                    'seed_key' => 'replacement_report.pipeline_metrics',
                    'title' => 'ما الأعداد والحالات التي يجب أن يتضمنها تقرير مسار الإحلال؟',
                    'help_text' => 'يعتمد التقرير على سجلات الترشيح والمتابعة والاعتماد أو عدم الاعتماد في 4.11، ولا يغير حالة المرشح من داخل التقرير.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 7,
                    'report_category' => 'report_metric',
                    'target_entity' => 'replacement_report',
                    'options' => [
                        ['label' => 'إجمالي مرشحي الإحلال', 'value' => 'candidate_count'],
                        ['label' => 'عدد الذكور المرشحة', 'value' => 'male_candidates'],
                        ['label' => 'عدد الإناث المرشحة', 'value' => 'female_candidates'],
                        ['label' => 'المرشحون تحت المتابعة', 'value' => 'candidates_under_followup'],
                        ['label' => 'المرشحون المعتمدون داخل القطيع الإنتاجي', 'value' => 'approved_candidates'],
                        ['label' => 'المرشحون الذين لم يتم اعتمادهم / رُفض ترشيحهم', 'value' => 'nonapproved_candidates'],
                    ],
                ],
                [
                    'seed_key' => 'replacement_report.outcome_reason_analysis',
                    'title' => 'كيف يجب تحليل أسباب عدم اعتماد أو رفض مرشحي الإحلال؟',
                    'help_text' => 'السبب يجب أن يأتي من القرار أو سجل الـWorkflow الذي أنهى أو غيّر مسار المرشح، لا من تصنيف يدخله المستخدم مرة أخرى داخل التقرير.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 8,
                    'report_category' => 'derivation_rule',
                    'target_entity' => 'replacement_outcome_report',
                    'options' => [
                        ['label' => 'عرض قائمة الحالات فقط دون تجميع الأسباب', 'value' => 'case_list_only'],
                        ['label' => 'تجميع الحالات حسب السبب / القرار المسجل في الـWorkflow مع إمكانية فتح التفاصيل', 'value' => 'group_by_recorded_workflow_reason'],
                        ['label' => 'عرض الأسباب المسجلة مع ربطها بالتقييمات والأوزان والسياق الذي سبق القرار', 'value' => 'reasons_with_decision_context'],
                    ],
                ],
                [
                    'seed_key' => 'replacement_report.approval_metrics',
                    'title' => 'ما مؤشرات الاعتماد التي يجب أن يتضمنها تقرير الإحلال للحيوانات التي دخلت القطيع الإنتاجي؟',
                    'help_text' => 'المصدر يذكر متوسط عمر الاعتماد ومتوسط وزن الاعتماد. الوزن يعتمد على سجل القياس Canonical المرتبط بحدث الاعتماد في 4.11.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 9,
                    'report_category' => 'report_metric',
                    'target_entity' => 'replacement_approval_report',
                    'options' => [
                        ['label' => 'عدد الاعتمادات خلال الفترة', 'value' => 'approval_count'],
                        ['label' => 'متوسط عمر الحيوان عند الاعتماد', 'value' => 'average_age_at_approval'],
                        ['label' => 'متوسط وزن الحيوان عند الاعتماد', 'value' => 'average_weight_at_approval'],
                        ['label' => 'التوزيع حسب الجنس / الدور الإنتاجي المعتمد', 'value' => 'approval_by_sex_or_role'],
                        ['label' => 'التوزيع حسب مصدر الإحلال: داخلي أو خارجي', 'value' => 'approval_by_replacement_source'],
                    ],
                ],
                [
                    'seed_key' => 'replacement_report.source_comparison',
                    'title' => 'هل يجب أن يستطيع تقرير الإحلال مقارنة نتائج الإحلال الداخلي بالحيوانات الخارجية المرشحة أو المعتمدة؟',
                    'help_text' => '4.11 يميز مصدر الإحلال إلى داخلي أو خارجي. هذا السؤال يحسم الحاجة إلى المقارنة التحليلية فقط، وليس اختلاف قواعد الاعتماد بين المصدرين.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 10,
                    'report_category' => 'comparison_scope',
                    'target_entity' => 'replacement_report',
                    'options' => [],
                ],
                [
                    'seed_key' => 'pedigree_replacement_report.retained_offspring_link',
                    'title' => 'هل يجب أن يربط تحليل النسب بين الآباء والأمهات وبين الأبناء الذين تم ترشيحهم أو اعتمادهم للإحلال؟',
                    'help_text' => 'المصدر يذكر أهمية معرفة أكثر الأمهات التي لها أبناء محتفظ بهم، كما أن تقييم الأبوين يستفيد من مصير الأبناء. المقصود هنا الربط التحليلي بين النسب ونتائج الإحلال دون تكرار تقييم الأداء الشامل في 5.7.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 11,
                    'report_category' => 'relationship_analysis',
                    'target_entity' => 'pedigree_replacement_report',
                    'options' => [],
                ],
                [
                    'seed_key' => 'pedigree_replacement_report.drilldown_model',
                    'title' => 'إلى أي مستوى يجب أن يستطيع المستخدم النزول من مؤشرات النسب والإحلال إلى البيانات الأصلية؟',
                    'help_text' => 'الهدف أن تكون شجرة النسب ونسب الانتشار وأعداد المرشحين والاعتمادات قابلة للتفسير والرجوع إلى الحيوانات والعلاقات والأحداث Canonical التي كونتها.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 12,
                    'report_category' => 'interaction_rule',
                    'target_entity' => 'pedigree_replacement_report',
                    'options' => [
                        ['label' => 'عرض النتائج الإجمالية فقط', 'value' => 'aggregate_only'],
                        ['label' => 'من المؤشر إلى قائمة الحيوانات / العلاقات الداخلة في الحساب', 'value' => 'drilldown_to_animals_and_relationships'],
                        ['label' => 'من المؤشر إلى الحيوانات والعلاقات ثم إلى سجلات الترشيح والاعتماد وقرارات الـWorkflow المفسرة للنتيجة', 'value' => 'drilldown_to_source_records_and_events'],
                    ],
                ],
            ];

            app(QuestionSeederSyncService::class)->sync(
                mainSectionName: 'التقارير والتحليلات والتنبيهات ومؤشرات الأداء',
                sectionName: 'تقارير النسب والإحلال',
                questions: $questions,
                prune: true,
                preserveAnswers: true,
            );
        });
    }
}
