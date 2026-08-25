<?php

namespace Database\Seeders\Questions\Reports;

use App\Enums\Questionnaire\QuestionType;
use App\Services\Questionnaire\QuestionSeederSyncService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HerdReadinessMovementReportsQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $questions = [
                [
                    'seed_key' => 'herd_report.primary_views',
                    'title' => 'ما العروض الرئيسية التي يجب أن يوفرها قسم تقارير القطيع والجاهزية وحركة القطيع؟',
                    'help_text' => 'المطلوب تحديد مكونات هذا النطاق التحليلي دون تكرار ملخص لوحة التحكم اليومية أو التقارير المتخصصة للخصوبة والصحة والنمو.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 1,
                    'report_category' => 'report_scope',
                    'target_entity' => 'herd_report',
                    'options' => [
                        ['label' => 'تكوين القطيع الحالي وتوزيعه', 'value' => 'current_herd_composition'],
                        ['label' => 'الجاهزية الحالية وأسباب عدم الجاهزية', 'value' => 'current_readiness'],
                        ['label' => 'ميزان حركة القطيع خلال فترة', 'value' => 'herd_movement_balance'],
                        ['label' => 'قائمة الأحداث التي أدت إلى حركة الأعداد خلال الفترة', 'value' => 'herd_movement_events'],
                    ],
                ],
                [
                    'seed_key' => 'herd_report.composition_breakdowns',
                    'title' => 'بأي أبعاد يجب أن يستطيع تقرير تكوين القطيع الحالي تقسيم الأعداد؟',
                    'help_text' => 'المرجع الوظيفي يطلب فهم توزيع القطيع حسب الجنس والعمر والسلالة والمرحلة الإنتاجية. هذه تقسيمات تحليلية مشتقة من البيانات الحالية وليست حقولًا جديدة على الحيوان.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 2,
                    'report_category' => 'report_dimension',
                    'target_entity' => 'herd_composition_report',
                    'options' => [
                        ['label' => 'الجنس', 'value' => 'sex'],
                        ['label' => 'العمر / الفئة العمرية', 'value' => 'age_group'],
                        ['label' => 'السلالة', 'value' => 'breed'],
                        ['label' => 'المرحلة / المسار التشغيلي الحالي', 'value' => 'operational_stage'],
                        ['label' => 'المجموعة / القطيع الإنتاجي عند استخدامه', 'value' => 'production_group'],
                    ],
                ],
                [
                    'seed_key' => 'herd_report.operational_categories',
                    'title' => 'ما الفئات التشغيلية التي يجب إظهار أعدادها بوضوح داخل تقرير القطيع الحالي؟',
                    'help_text' => 'هذه الفئات مذكورة في التصور كأمثلة لازمة لفهم تكوين القطيع. التقرير يعرض العدد والتفاصيل، بينما مصدر الحالة أو المرحلة يبقى في البيانات والـWorkflow المناسب.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 3,
                    'report_category' => 'report_metric',
                    'target_entity' => 'herd_composition_report',
                    'options' => [
                        ['label' => 'الإناث الإنتاجية', 'value' => 'productive_females'],
                        ['label' => 'الذكور الإنتاجية', 'value' => 'productive_males'],
                        ['label' => 'مرشحو الإحلال', 'value' => 'replacement_candidates'],
                        ['label' => 'الحيوانات تحت النمو بعد الفطام', 'value' => 'growth_animals'],
                        ['label' => 'حيوانات التسمين', 'value' => 'fattening_animals'],
                        ['label' => 'الحيوانات المعزولة أو تحت المتابعة الصحية', 'value' => 'isolated_or_observed_animals'],
                        ['label' => 'الحيوانات غير الجاهزة للإنتاج', 'value' => 'not_ready_for_production'],
                    ],
                ],
                [
                    'seed_key' => 'herd_report.readiness_domains',
                    'title' => 'ما أنواع الجاهزية الحالية التي يجب أن يستطيع التقرير عرضها؟',
                    'help_text' => 'قواعد تحديد الجاهزية نفسها توجد في Settings، بينما التقرير يعرض النتيجة الحالية والأسباب المشتقة منها. لا ينفذ التقرير قرارًا تشغيليًا.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 4,
                    'report_category' => 'readiness_scope',
                    'target_entity' => 'herd_readiness_report',
                    'options' => [
                        ['label' => 'جاهزية الإناث للتلقيح / بدء دورة إنتاجية', 'value' => 'female_mating_readiness'],
                        ['label' => 'إتاحة / جاهزية الذكور للاستخدام في التلقيح', 'value' => 'male_mating_availability'],
                        ['label' => 'جاهزية مرشحي الإحلال للاعتماد داخل القطيع الإنتاجي', 'value' => 'replacement_readiness'],
                        ['label' => 'جاهزية حيوانات التسمين للبيع', 'value' => 'sale_readiness'],
                    ],
                ],
                [
                    'seed_key' => 'herd_report.readiness_presentation',
                    'title' => 'كيف يجب عرض نتيجة الجاهزية وأسباب عدم الجاهزية؟',
                    'help_text' => 'الهدف أن يستطيع المستخدم معرفة العدد ثم فهم لماذا بعض الحيوانات غير جاهزة، مع عدم إعادة تعريف قواعد الجاهزية داخل التقرير.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 5,
                    'report_category' => 'presentation_rule',
                    'target_entity' => 'herd_readiness_report',
                    'options' => [
                        ['label' => 'أعداد جاهز / غير جاهز فقط', 'value' => 'counts_only'],
                        ['label' => 'أعداد جاهز / غير جاهز مع تجميع أسباب عدم الجاهزية', 'value' => 'counts_with_reason_breakdown'],
                        ['label' => 'أعداد + أسباب عدم الجاهزية + قائمة الحيوانات القابلة للفتح', 'value' => 'counts_reasons_and_drilldown'],
                    ],
                ],
                [
                    'seed_key' => 'herd_report.readiness_reason_source',
                    'title' => 'من أين يجب أن تأتي أسباب عدم الجاهزية المعروضة في التقرير؟',
                    'help_text' => 'الحالة الصحية والتسكين والمرحلة وقواعد التشغيل قد تمنع الجاهزية. المطلوب منع ظهور سبب تقريري منفصل عن المنطق الفعلي الذي اتخذ النظام بناءً عليه قرار الجاهزية.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 6,
                    'report_category' => 'derivation_rule',
                    'target_entity' => 'herd_readiness_report',
                    'options' => [
                        ['label' => 'تشتق الأسباب فقط من القواعد والحالات الفعلية التي جعلت الحيوان غير جاهز', 'value' => 'derive_from_applied_rules_and_state'],
                        ['label' => 'سبب يدخله المستخدم يدويًا عند كل حالة عدم جاهزية', 'value' => 'manual_reason_only'],
                        ['label' => 'أسباب مشتقة تلقائيًا مع ملاحظة تفسيرية اختيارية من المستخدم', 'value' => 'derived_reasons_with_optional_note'],
                    ],
                ],
                [
                    'seed_key' => 'herd_movement.balance_model',
                    'title' => 'كيف يجب تمثيل تقرير حركة أعداد القطيع خلال فترة؟',
                    'help_text' => 'التصور الوظيفي يقترح ميزانًا يبدأ برصيد أول المدة ثم يضيف الدخول والمواليد ويطرح النفوق والبيع والنقل للخارج والخروج للوصول إلى رصيد آخر المدة.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 7,
                    'report_category' => 'report_model',
                    'target_entity' => 'herd_movement_report',
                    'options' => [
                        ['label' => 'ميزان رصيد أول المدة + الإضافات - الاستبعادات = رصيد آخر المدة', 'value' => 'opening_plus_inflows_minus_outflows_equals_closing'],
                        ['label' => 'قائمة أحداث الحركة فقط دون ميزان عددي', 'value' => 'movement_event_list_only'],
                        ['label' => 'الميزان العددي مع إمكانية فتح قائمة الأحداث المكونة لكل بند', 'value' => 'balance_with_event_drilldown'],
                    ],
                ],
                [
                    'seed_key' => 'herd_movement.inflow_types',
                    'title' => 'ما أنواع الأحداث التي يجب أن تظهر كإضافات إلى رصيد القطيع في تقرير الحركة؟',
                    'help_text' => 'لا ينشئ التقرير أحداثًا جديدة؛ بل يجمع الأحداث Canonical المسجلة في Workflow أو سجلات الولادة حسب مدلول كل حدث.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 8,
                    'report_category' => 'movement_classification',
                    'target_entity' => 'herd_movement_report',
                    'options' => [
                        ['label' => 'المواليد الأحياء الناتجون داخل المزرعة', 'value' => 'live_births'],
                        ['label' => 'دخول حيوانات من خارج المزرعة', 'value' => 'external_intake'],
                        ['label' => 'إعادة دخول حيوان سبق خروجه', 'value' => 'reentry'],
                        ['label' => 'نقل حيوان إلى المزرعة من مزرعة أخرى داخل النظام عند وجود أكثر من مزرعة', 'value' => 'interfarm_transfer_in'],
                    ],
                ],
                [
                    'seed_key' => 'herd_movement.outflow_types',
                    'title' => 'ما أنواع الأحداث التي يجب أن تظهر كاستبعادات من رصيد القطيع في تقرير الحركة؟',
                    'help_text' => 'القائمة تجمع أثر الأحداث الفعلية على وجود الحيوان داخل المزرعة، مع الحفاظ على الفرق بين النفوق والبيع والنقل والخروج العام في مصادرها الأصلية.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 9,
                    'report_category' => 'movement_classification',
                    'target_entity' => 'herd_movement_report',
                    'options' => [
                        ['label' => 'النفوق بعد الولادة / للحيوانات الموجودة في الرصيد', 'value' => 'mortality'],
                        ['label' => 'البيع / الخروج بسبب البيع', 'value' => 'sale_exit'],
                        ['label' => 'النقل إلى مزرعة أخرى داخل النظام', 'value' => 'interfarm_transfer_out'],
                        ['label' => 'الخروج من المزرعة لأسباب أخرى', 'value' => 'other_farm_exit'],
                    ],
                ],
                [
                    'seed_key' => 'herd_movement.internal_housing_movements',
                    'title' => 'كيف يجب التعامل مع النقل بين العنابر أو البطاريات أو الأقفاص داخل نفس المزرعة في تقرير حركة القطيع؟',
                    'help_text' => 'هذه الحركات تغير الموقع لكنها لا تغير عدد الحيوانات الموجودة بالمزرعة. المطلوب منع خلط حركة الإشغال الداخلية مع دخول أو خروج من رصيد القطيع.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 10,
                    'report_category' => 'movement_rule',
                    'target_entity' => 'herd_movement_report',
                    'options' => [
                        ['label' => 'لا تدخل مطلقًا في ميزان رصيد القطيع ويمكن تحليلها في تقارير المواقع فقط', 'value' => 'exclude_from_herd_balance'],
                        ['label' => 'لا تؤثر على الرصيد لكن يظهر عدد الحركات الداخلية كبند معلوماتي مستقل', 'value' => 'exclude_from_balance_show_internal_count'],
                        ['label' => 'تظهر داخل نفس جدول الحركة مع تمييز أن أثرها الصافي على الرصيد يساوي صفرًا', 'value' => 'show_as_zero_net_movement'],
                    ],
                ],
                [
                    'seed_key' => 'herd_movement.preweaning_counting_model',
                    'title' => 'كيف يجب احتساب المواليد قبل الفطام داخل إجمالي القطيع وميزان الحركة قبل إنشاء سجلاتهم الفردية؟',
                    'help_text' => 'قبل الفطام تدار المواليد ضمن البطن وقد لا يكون لكل مولود Animal Record فردي بعد. ومع ذلك يحتاج تقرير القطيع إلى عدم فقد أثر الولادة والنفوق قبل الفطام من الأعداد.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 11,
                    'report_category' => 'counting_rule',
                    'target_entity' => 'herd_movement_report',
                    'options' => [
                        ['label' => 'تدخل المواليد الأحياء في رصيد القطيع من الولادة باستخدام أعداد البطن حتى إنشاء السجلات الفردية عند الفطام', 'value' => 'count_from_birth_using_litter_quantities'],
                        ['label' => 'لا تدخل في رصيد الحيوانات إلا بعد إنشاء السجلات الفردية عند الفطام', 'value' => 'count_only_after_individual_tracking'],
                        ['label' => 'يعرض النظام رصيدين منفصلين: مواليد قبل الفطام + حيوانات ذات تتبع فردي', 'value' => 'separate_preweaning_and_individual_balances'],
                    ],
                ],
                [
                    'seed_key' => 'herd_movement.historical_balance_source',
                    'title' => 'كيف يجب تحديد رصيد أول وآخر المدة عند طلب تقرير حركة عن فترة تاريخية؟',
                    'help_text' => 'العدد الحالي وحده لا يكفي لبناء تقرير تاريخي. المطلوب حسم مصدر الرصيد التاريخي مع الحفاظ على Canonical Event History وعدم الاعتماد على قيمة حالية قابلة للتغير.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 12,
                    'report_category' => 'historical_calculation_rule',
                    'target_entity' => 'herd_movement_report',
                    'options' => [
                        ['label' => 'إعادة بناء الرصيد من سجل الأحداث وحالة الوجود عند حدود الفترة', 'value' => 'reconstruct_from_event_history'],
                        ['label' => 'الاعتماد على Snapshots دورية محفوظة للأرصدة', 'value' => 'periodic_balance_snapshots'],
                        ['label' => 'استخدام Snapshots عند توفرها مع إعادة البناء من الأحداث للتحقق أو استكمال الفترات', 'value' => 'hybrid_snapshots_and_event_reconstruction'],
                    ],
                ],
                [
                    'seed_key' => 'herd_report.drilldown_model',
                    'title' => 'إلى أي مستوى يجب أن يستطيع المستخدم النزول من الأعداد الإجمالية في تقارير القطيع والجاهزية والحركة؟',
                    'help_text' => 'الهدف أن تكون الأرقام قابلة للتفسير والمراجعة بدل عرض مجموعات غير قابلة للتتبع إلى البيانات الأصلية.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 13,
                    'report_category' => 'interaction_rule',
                    'target_entity' => 'herd_report',
                    'options' => [
                        ['label' => 'الأرقام الإجمالية فقط دون Drill-down', 'value' => 'aggregate_only'],
                        ['label' => 'من الإجمالي إلى قائمة الحيوانات / البطون الداخلة في الرقم', 'value' => 'drilldown_to_subjects'],
                        ['label' => 'من الإجمالي إلى الحيوانات / البطون ثم إلى الحدث أو السجل Canonical الذي يفسر الحالة أو الحركة', 'value' => 'drilldown_to_subjects_and_source_events'],
                    ],
                ],
            ];

            app(QuestionSeederSyncService::class)->sync(
                mainSectionName: 'التقارير والتحليلات والتنبيهات ومؤشرات الأداء',
                sectionName: 'تقارير القطيع والجاهزية وحركة القطيع',
                questions: $questions,
                prune: true,
                preserveAnswers: true,
            );
        });
    }
}
