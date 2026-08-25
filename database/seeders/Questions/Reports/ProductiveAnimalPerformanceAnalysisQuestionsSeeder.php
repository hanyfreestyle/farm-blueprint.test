<?php

namespace Database\Seeders\Questions\Reports;

use App\Enums\Questionnaire\QuestionType;
use App\Services\Questionnaire\QuestionSeederSyncService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductiveAnimalPerformanceAnalysisQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $questions = [
                [
                    'seed_key' => 'productive_animal_performance.primary_views',
                    'title' => 'ما العروض الرئيسية التي يجب أن يوفرها تحليل أداء الحيوانات الإنتاجية؟',
                    'help_text' => 'المرجع يعتبر هذا التقرير تجميعًا لعدة مؤشرات تساعد على تقييم الحيوان دون الحكم عليه من رقم واحد. التقارير المتخصصة للخصوبة والولادة والنمو تظل مصادر تحليل تفصيلية، بينما هذا القسم يجمع نتيجتها على مستوى الحيوان الإنتاجي.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 1,
                    'report_category' => 'report_scope',
                    'target_entity' => 'productive_animal_performance',
                    'options' => [
                        ['label' => 'تقييم أداء الإناث الإنتاجية', 'value' => 'female_performance'],
                        ['label' => 'تقييم أداء الذكور الإنتاجية', 'value' => 'male_performance'],
                        ['label' => 'مقارنة الحيوانات الإنتاجية ببعضها', 'value' => 'peer_comparison'],
                        ['label' => 'تصنيف مستوى الأداء عند اعتماد نموذج تصنيف واضح', 'value' => 'performance_classification'],
                    ],
                ],
                [
                    'seed_key' => 'female_performance.metrics',
                    'title' => 'ما المؤشرات التي يجب أن تدخل في تقييم أداء الأنثى الإنتاجية؟',
                    'help_text' => 'المؤشرات مأخوذة مباشرة من التصور الوظيفي. كل قيمة يجب أن تشتق من سجلات التلقيح والحمل والولادة والبطن والفطام والأوزان والحالات الاستثنائية، لا من قيم ملخصة يدخلها المستخدم يدويًا.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 2,
                    'report_category' => 'performance_metric',
                    'target_entity' => 'female_performance',
                    'options' => [
                        ['label' => 'نسبة الحمل', 'value' => 'pregnancy_rate'],
                        ['label' => 'عدد الولادات', 'value' => 'birth_count'],
                        ['label' => 'متوسط عدد المواليد', 'value' => 'average_total_born'],
                        ['label' => 'متوسط الأحياء عند الولادة', 'value' => 'average_live_born'],
                        ['label' => 'متوسط عدد المفطومين', 'value' => 'average_weaned_count'],
                        ['label' => 'نسبة النفوق في البطون', 'value' => 'litter_mortality_rate'],
                        ['label' => 'متوسط وزن المفطومين', 'value' => 'average_weaning_weight'],
                        ['label' => 'عدد حالات الإجهاض', 'value' => 'abortion_count'],
                        ['label' => 'عدد محاولات التلقيح اللازمة للوصول إلى الحمل', 'value' => 'attempts_to_pregnancy'],
                        ['label' => 'انتظام الدورة الإنتاجية', 'value' => 'reproductive_cycle_regularity'],
                    ],
                ],
                [
                    'seed_key' => 'male_performance.metrics',
                    'title' => 'ما المؤشرات التي يجب أن تدخل في تقييم أداء الذكر الإنتاجي؟',
                    'help_text' => 'المرجع يؤكد أن تقييم الذكر لا يعتمد فقط على حدوث الحمل، بل يمتد إلى نتائج الأبناء عند توافر علاقة أبوة موثوقة.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 3,
                    'report_category' => 'performance_metric',
                    'target_entity' => 'male_performance',
                    'options' => [
                        ['label' => 'عدد الإناث التي تم استخدام الذكر معها', 'value' => 'females_used_with'],
                        ['label' => 'نسبة الحمل الناتجة عن استخدامه', 'value' => 'pregnancy_rate'],
                        ['label' => 'عدد البطون الناتجة', 'value' => 'resulting_litter_count'],
                        ['label' => 'عدد الأبناء', 'value' => 'offspring_count'],
                        ['label' => 'نسبة بقاء الأبناء حتى الفطام', 'value' => 'offspring_survival_to_weaning_rate'],
                        ['label' => 'متوسط نمو الأبناء', 'value' => 'offspring_average_growth'],
                        ['label' => 'عدد الأبناء الذين تم اختيارهم أو اعتمادهم للإحلال', 'value' => 'offspring_selected_for_replacement'],
                    ],
                ],
                [
                    'seed_key' => 'productive_animal_performance.metric_source_model',
                    'title' => 'كيف يجب تكوين مؤشرات أداء الحيوان من البيانات التاريخية المسجلة؟',
                    'help_text' => 'المؤشرات تجمع نتائج من عدة Workflows وتقارير متخصصة. المطلوب تحديد مصدر الحقيقة ومنع إنشاء قيم أداء يدوية تنافس الأحداث والسجلات الأصلية.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 4,
                    'report_category' => 'derivation_rule',
                    'target_entity' => 'productive_animal_performance',
                    'options' => [
                        ['label' => 'تحسب المؤشرات من السجلات والأحداث Canonical عند عرض التقرير', 'value' => 'derive_from_canonical_history_on_report'],
                        ['label' => 'تحسب من السجلات Canonical مع إمكانية حفظ Aggregates / Snapshots محسوبة لتحسين الأداء دون أن تصبح مصدر الحقيقة', 'value' => 'derive_with_refreshable_aggregates'],
                        ['label' => 'تخزن قيم أداء ملخصة مستقلة ويتم تحديثها يدويًا', 'value' => 'manual_independent_performance_values'],
                    ],
                ],
                [
                    'seed_key' => 'productive_animal_performance.comparison_model',
                    'title' => 'كيف يجب دعم مقارنة أداء الحيوانات الإنتاجية ببعضها؟',
                    'help_text' => 'التصور يذكر صراحة إمكانية مقارنة الإناث ببعضها، وينطبق نفس مبدأ تعدد المؤشرات على الذكور. هذا السؤال يحدد شكل المقارنة دون إعادة تعريف مؤشرات كل مجال.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 5,
                    'report_category' => 'presentation_rule',
                    'target_entity' => 'productive_animal_performance',
                    'options' => [
                        ['label' => 'صفحة أداء مستقلة لكل حيوان فقط', 'value' => 'individual_profile_only'],
                        ['label' => 'جدول مقارنة بين عدة حيوانات على نفس مجموعة المؤشرات', 'value' => 'multi_animal_comparison_table'],
                        ['label' => 'صفحة فردية + مقارنة متعددة الحيوانات', 'value' => 'individual_and_comparison_views'],
                    ],
                ],
                [
                    'seed_key' => 'productive_animal_performance.foster_attribution_model',
                    'title' => 'عند وجود نقل مواليد إلى أم حاضنة، كيف يجب إسناد نتائج ما قبل الفطام في تقييم أداء الأمهات؟',
                    'help_text' => 'المشروع يحافظ على الأم البيولوجية منفصلة عن الأم الحاضنة. المطلوب ألا يؤدي التحضين البديل إلى فقد الأصل أو إسناد كل النتائج إلى علاقة واحدة بصورة مضللة.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 6,
                    'report_category' => 'attribution_rule',
                    'target_entity' => 'female_performance',
                    'options' => [
                        ['label' => 'تفصل النتائج: الأصل والنتائج الوراثية للأم البيولوجية، ونتائج فترة الرعاية للأم الحاضنة عند الحاجة', 'value' => 'separate_biological_and_foster_attribution'],
                        ['label' => 'تنسب كل نتائج البطن إلى الأم البيولوجية فقط، وتظهر الحضانة كمعلومة سياقية', 'value' => 'attribute_all_to_biological_mother'],
                        ['label' => 'تنسب نتائج الفترة بعد النقل إلى الأم الحاضنة مع بقاء النسب البيولوجي محفوظًا', 'value' => 'attribute_post_transfer_outcomes_to_foster_mother'],
                    ],
                ],
                [
                    'seed_key' => 'productive_animal_performance.classification_model',
                    'title' => 'هل يحتاج التقرير إلى تصنيف مستوى أداء الحيوان، وكيف يجب أن يبنى هذا التصنيف؟',
                    'help_text' => 'التصور يقترح مستقبلًا مستويات مثل أداء مرتفع وطبيعي ويحتاج متابعة ومنخفض، لكنه يؤكد أن التصنيف الأفضل يعتمد على مؤشرات واضحة وليس حكمًا يدويًا مجردًا.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 7,
                    'report_category' => 'classification_rule',
                    'target_entity' => 'productive_animal_performance',
                    'options' => [
                        ['label' => 'لا نستخدم تصنيفًا إجماليًا؛ نعرض المؤشرات فقط', 'value' => 'no_overall_classification'],
                        ['label' => 'تصنيف إلى مستويات أداء وفق قواعد واضحة تعتمد على عدة مؤشرات', 'value' => 'multi_indicator_rule_based_classification'],
                        ['label' => 'درجة مركبة محسوبة من مؤشرات وأوزان واضحة مع إظهار مكوناتها', 'value' => 'transparent_weighted_composite_score'],
                        ['label' => 'تصنيف يدوي يحدده المسؤول بعد مراجعة المؤشرات', 'value' => 'manual_classification_after_review'],
                    ],
                ],
                [
                    'seed_key' => 'productive_animal_performance.missing_data_handling',
                    'title' => 'كيف يجب التعامل مع مؤشر لا تتوفر للحيوان بيانات كافية لحسابه بصورة موثوقة؟',
                    'help_text' => 'المشروع لا يفترض تاريخًا غير موجود، خصوصًا للحيوانات الواردة من خارج المزرعة أو الحديثة في القطيع. المطلوب منع تحويل غياب البيانات إلى صفر أو نتيجة أداء مضللة.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 8,
                    'report_category' => 'data_quality_rule',
                    'target_entity' => 'productive_animal_performance',
                    'options' => [
                        ['label' => 'يظهر المؤشر كغير متاح / بيانات غير كافية ولا يدخل في الحكم إلا عند توفر مصدره', 'value' => 'mark_unavailable_when_insufficient'],
                        ['label' => 'يحسب المؤشر من البيانات المتاحة فقط مع إظهار حجم العينة / عدد الوقائع المستخدمة', 'value' => 'calculate_from_available_with_sample_context'],
                        ['label' => 'يعامل غياب البيانات كقيمة صفرية', 'value' => 'treat_missing_as_zero'],
                    ],
                ],
                [
                    'seed_key' => 'productive_animal_performance.historical_subject_scope',
                    'title' => 'ما نطاق الحيوانات التي يجب أن تكون متاحة في تحليل الأداء بعد تغير وضعها التشغيلي؟',
                    'help_text' => 'المرجع يؤكد أن الأحداث التاريخية لا تمحى عند تغير الحالة الحالية. لذلك يجب حسم هل يبقى أداء الحيوان قابلًا للمراجعة بعد الاستبعاد أو الخروج أو التوقف عن الإنتاج.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 9,
                    'report_category' => 'report_scope',
                    'target_entity' => 'productive_animal_performance',
                    'options' => [
                        ['label' => 'الحيوانات الإنتاجية النشطة حاليًا فقط', 'value' => 'active_productive_animals_only'],
                        ['label' => 'النشطة افتراضيًا مع إمكانية إظهار المستبعدة أو الخارجة أو المتوقفة تاريخيًا', 'value' => 'active_default_with_historical_filter'],
                        ['label' => 'كل الحيوانات التي لها تاريخ إنتاجي ضمن نفس التقرير مع تمييز وضعها الحالي', 'value' => 'all_animals_with_productive_history'],
                    ],
                ],
                [
                    'seed_key' => 'productive_animal_performance.decision_support_role',
                    'title' => 'ما الدور الذي يجب أن يؤديه تحليل الأداء بالنسبة لقرار متابعة الحيوان أو استبعاده؟',
                    'help_text' => 'التصور يجعل قرار الاستبعاد بشريًا في البداية، بينما يوفر النظام المعلومات والتنبيهات. التقرير لا يجب أن يحول انخفاض مؤشر إلى استبعاد تلقائي دون Workflow قرار مسجل.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 10,
                    'report_category' => 'decision_support_rule',
                    'target_entity' => 'productive_animal_performance',
                    'options' => [
                        ['label' => 'عرض المعلومات فقط ويبدأ المستخدم أي قرار من Workflow منفصل', 'value' => 'information_only'],
                        ['label' => 'عرض المعلومات مع تمييز الحيوانات التي تحتاج مراجعة وفق القواعد، دون تنفيذ قرار', 'value' => 'information_with_review_flags'],
                        ['label' => 'عرض المعلومات والتنبيهات مع إمكانية فتح Workflow قرار الاستبعاد / المصير من التقرير مع بقاء القرار بشريًا ومسجلًا', 'value' => 'decision_support_with_workflow_entry'],
                    ],
                ],
                [
                    'seed_key' => 'productive_animal_performance.drilldown_model',
                    'title' => 'إلى أي مستوى يجب أن يستطيع المستخدم تتبع كل مؤشر أداء إلى البيانات التي كوّنته؟',
                    'help_text' => 'لأن أداء الحيوان يجمع بيانات من مجالات متعددة، يجب أن تظل النتيجة قابلة للمراجعة والرجوع إلى الدورات والبطون والأبناء والأحداث الأصلية التي دخلت في الحساب.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 11,
                    'report_category' => 'interaction_rule',
                    'target_entity' => 'productive_animal_performance',
                    'options' => [
                        ['label' => 'عرض المؤشرات النهائية فقط', 'value' => 'metrics_only'],
                        ['label' => 'من المؤشر إلى الدورات / البطون / الأبناء الداخلة في الحساب', 'value' => 'drilldown_to_cycles_litters_offspring'],
                        ['label' => 'من المؤشر إلى الدورات / البطون / الأبناء ثم إلى أحداث وسجلات Workflow Canonical المفسرة للنتيجة', 'value' => 'drilldown_to_source_events'],
                    ],
                ],
            ];

            app(QuestionSeederSyncService::class)->sync(
                mainSectionName: 'التقارير والتحليلات والتنبيهات ومؤشرات الأداء',
                sectionName: 'تحليل أداء الحيوانات الإنتاجية',
                questions: $questions,
                prune: true,
                preserveAnswers: true,
            );
        });
    }
}
