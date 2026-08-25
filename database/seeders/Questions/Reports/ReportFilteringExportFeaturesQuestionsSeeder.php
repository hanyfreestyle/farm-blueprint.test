<?php

namespace Database\Seeders\Questions\Reports;

use App\Enums\Questionnaire\QuestionType;
use App\Services\Questionnaire\QuestionSeederSyncService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReportFilteringExportFeaturesQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $questions = [
                [
                    'seed_key' => 'report_features.filter_dimensions',
                    'title' => 'ما أبعاد التصفية العامة التي يجب أن تكون متاحة للتقارير عند انطباقها على بيانات التقرير؟',
                    'help_text' => 'المصدر يذكر هذه الأبعاد كفلاتر مشتركة محتملة. لا يعني اختيارها أن كل تقرير يجب أن يعرضها جميعًا؛ التقرير يستخدم فقط الأبعاد الموجودة فعليًا في بياناته وسياقه.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 1,
                    'report_category' => 'filter_dimension',
                    'target_entity' => 'report_filtering',
                    'options' => [
                        ['label' => 'الفترة الزمنية', 'value' => 'period'],
                        ['label' => 'المزرعة', 'value' => 'farm'],
                        ['label' => 'العنبر', 'value' => 'barn'],
                        ['label' => 'البطارية', 'value' => 'battery'],
                        ['label' => 'السلالة', 'value' => 'breed'],
                        ['label' => 'الجنس', 'value' => 'sex'],
                        ['label' => 'المرحلة الإنتاجية', 'value' => 'production_stage'],
                        ['label' => 'الذكر المرتبط بالسياق الإنتاجي', 'value' => 'male'],
                        ['label' => 'الأنثى المرتبطة بالسياق الإنتاجي', 'value' => 'female'],
                        ['label' => 'القطيع / المجموعة الإنتاجية عند الحاجة', 'value' => 'production_group'],
                    ],
                ],
                [
                    'seed_key' => 'report_features.filter_applicability_model',
                    'title' => 'كيف يجب تحديد الفلاتر التي تظهر داخل كل تقرير؟',
                    'help_text' => 'المصدر ينص على التصفية «حسب البيانات المناسبة». المطلوب منع إظهار فلاتر لا علاقة لها بالتقرير أو لا يوجد لها بعد قابل للتصفية في البيانات الأصلية.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 2,
                    'report_category' => 'filter_rule',
                    'target_entity' => 'report_filtering',
                    'options' => [
                        ['label' => 'يظهر كل تقرير فقط الفلاتر القابلة للتطبيق على بياناته وسياقه', 'value' => 'show_only_applicable_filters'],
                        ['label' => 'تظهر قائمة الفلاتر العامة كاملة ويعطل غير القابل للتطبيق', 'value' => 'show_all_disable_not_applicable'],
                        ['label' => 'يحدد لكل تقرير صراحة أي فلاتر من القائمة العامة يسمح باستخدامها', 'value' => 'explicit_filter_configuration_per_report'],
                    ],
                ],
                [
                    'seed_key' => 'report_features.period_filter_model',
                    'title' => 'ما نموذج اختيار الفترة الزمنية المطلوب في التقارير التي تعتمد على نطاق زمني؟',
                    'help_text' => 'الفترة أحد الفلاتر الأساسية في المصدر. هذا السؤال يحدد طريقة اختيار النطاق فقط، ولا يحدد فترة حساب KPI الافتراضية أو الفترات القياسية الخاصة بالمقارنات في 5.11.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 3,
                    'report_category' => 'filter_rule',
                    'target_entity' => 'report_period_filter',
                    'options' => [
                        ['label' => 'من تاريخ إلى تاريخ يحددهما المستخدم', 'value' => 'custom_date_range'],
                        ['label' => 'فترات جاهزة يحددها النظام فقط', 'value' => 'predefined_periods_only'],
                        ['label' => 'فترات جاهزة مع إمكانية اختيار نطاق مخصص', 'value' => 'predefined_and_custom_range'],
                    ],
                ],
                [
                    'seed_key' => 'report_features.housing_filter_dependency_model',
                    'title' => 'كيف يجب أن تتصرف فلاتر المزرعة والعنبر والبطارية مع التسلسل الهيكلي للمواقع؟',
                    'help_text' => 'هيكل المواقع هو Farm → Barn → Battery → Cage. المطلوب منع اختيار تركيبات متعارضة مثل عنبر لا يتبع المزرعة المختارة أو بطارية لا تتبع العنبر المختار.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 4,
                    'report_category' => 'filter_rule',
                    'target_entity' => 'housing_report_filter',
                    'options' => [
                        ['label' => 'فلاتر مترابطة Cascading: اختيار المزرعة يقصر العنابر ثم اختيار العنبر يقصر البطاريات', 'value' => 'cascading_hierarchy_filters'],
                        ['label' => 'اختيار أي مستوى مباشرة ويستنتج النظام المستويات الأعلى تلقائيًا', 'value' => 'direct_level_selection_with_derived_parents'],
                        ['label' => 'دعم الأسلوبين مع منع أي تركيبة غير متسقة', 'value' => 'support_both_with_consistency_validation'],
                    ],
                ],
                [
                    'seed_key' => 'report_features.sex_vs_parent_filter_model',
                    'title' => 'كيف يجب التمييز بين فلتر «الجنس» وفلتر «الذكر / الأنثى» في التقارير الإنتاجية؟',
                    'help_text' => 'المصدر يذكر الجنس والذكر والأنثى كفلاتر مستقلة. الجنس يصف الحيوان محل التقرير، بينما الذكر أو الأنثى قد يكونان Parent / Reproductive Participant مرتبطين بحدث أو بطن أو نتيجة إنتاجية.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 5,
                    'report_category' => 'filter_semantics',
                    'target_entity' => 'animal_report_filter',
                    'options' => [
                        ['label' => 'الجنس يفلتر الحيوان محل التقرير، والذكر / الأنثى يفلتران المشارك التناسلي المرتبط بالسجل', 'value' => 'separate_subject_sex_from_reproductive_participants'],
                        ['label' => 'دمج الذكر / الأنثى داخل فلتر الجنس وعدم وجود فلاتر مستقلة للمشارك التناسلي', 'value' => 'use_sex_filter_only'],
                        ['label' => 'تختلف الدلالة حسب التقرير وتوضح تسمية الفلتر دوره داخل كل تقرير', 'value' => 'context_specific_role_labels'],
                    ],
                ],
                [
                    'seed_key' => 'report_features.production_group_time_context',
                    'title' => 'عند التصفية حسب القطيع / المجموعة الإنتاجية في تقرير تاريخي، أي عضوية يجب أن تستخدم؟',
                    'help_text' => 'المجموعة قد تتغير بمرور الوقت. استخدام العضوية الحالية على أحداث قديمة قد ينسب نتائج تاريخية إلى مجموعة لم يكن الحيوان عضوًا فيها وقت حدوثها.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 6,
                    'report_category' => 'filter_semantics',
                    'target_entity' => 'production_group_report_filter',
                    'options' => [
                        ['label' => 'العضوية التي كانت سارية وقت الحدث / السجل محل التحليل', 'value' => 'membership_at_event_time'],
                        ['label' => 'العضوية الحالية للحيوان وقت تشغيل التقرير', 'value' => 'current_membership'],
                        ['label' => 'دعم الخيارين مع توضيح أي منهما مستخدم في التقرير', 'value' => 'support_historical_and_current_membership'],
                    ],
                ],
                [
                    'seed_key' => 'report_features.filter_combination_model',
                    'title' => 'عند اختيار أكثر من فلتر في نفس التقرير، كيف يجب تطبيقها على النتائج؟',
                    'help_text' => 'مثل اختيار مزرعة + سلالة + مرحلة إنتاجية + فترة. المطلوب وجود سلوك موحد ومفهوم حتى لا تختلف دلالة نفس مجموعة الفلاتر من تقرير لآخر.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 7,
                    'report_category' => 'filter_rule',
                    'target_entity' => 'report_filtering',
                    'options' => [
                        ['label' => 'يجب أن يطابق السجل جميع الفلاتر المختارة معًا', 'value' => 'all_selected_filters_and_logic'],
                        ['label' => 'يسمح للمستخدم بالاختيار بين مطابقة جميع الفلاتر أو أي منها', 'value' => 'user_selectable_and_or_logic'],
                        ['label' => 'يحدد منطق الدمج داخل كل تقرير حسب طبيعته', 'value' => 'report_specific_filter_logic'],
                    ],
                ],
                [
                    'seed_key' => 'report_features.filtered_result_consistency',
                    'title' => 'هل يجب أن تعتمد كل الأرقام والجداول والتفاصيل داخل التقرير على نفس نطاق الفلاتر المطبق؟',
                    'help_text' => 'الهدف منع حالة يتغير فيها الجدول بالفلتر بينما تظل بطاقات الملخص أو المؤشرات محسوبة على نطاق مختلف، مما ينتج تقريرًا متناقضًا.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 8,
                    'report_category' => 'consistency_rule',
                    'target_entity' => 'report_result',
                    'options' => [],
                ],
                [
                    'seed_key' => 'report_features.export_formats',
                    'title' => 'ما خيارات إخراج / تصدير التقارير التي يجب دعمها عند الحاجة الفعلية؟',
                    'help_text' => 'التصور يذكر أن دعم الطباعة وPDF وExcel يمكن إضافته حسب الحاجة الفعلية. هذا السؤال يحسم أي الخيارات مطلوبة في النظام المستهدف، دون افتراض أن جميعها إلزامية من البداية.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 9,
                    'report_category' => 'export_feature',
                    'target_entity' => 'report_export',
                    'options' => [
                        ['label' => 'الطباعة', 'value' => 'print'],
                        ['label' => 'PDF', 'value' => 'pdf'],
                        ['label' => 'Excel', 'value' => 'excel'],
                    ],
                ],
                [
                    'seed_key' => 'report_features.export_filter_scope',
                    'title' => 'عند تصدير تقرير بعد تطبيق فلاتر، ما النطاق الذي يجب أن يحتويه الملف؟',
                    'help_text' => 'الملف يجب ألا يفاجئ المستخدم ببيانات خارج النطاق الذي كان يراجعه على الشاشة إلا إذا كان ذلك اختيارًا صريحًا.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 10,
                    'report_category' => 'export_rule',
                    'target_entity' => 'report_export',
                    'options' => [
                        ['label' => 'تصدير النتائج الحالية بعد تطبيق جميع الفلاتر فقط', 'value' => 'export_current_filtered_result'],
                        ['label' => 'تصدير التقرير كاملًا دون اعتبار الفلاتر الحالية', 'value' => 'export_full_report_dataset'],
                        ['label' => 'يختار المستخدم عند التصدير بين النتائج المفلترة وكامل نطاق التقرير المسموح', 'value' => 'user_selects_filtered_or_full_scope'],
                    ],
                ],
                [
                    'seed_key' => 'report_features.export_content_level',
                    'title' => 'ما مستوى التفاصيل الذي يجب أن يستطيع التصدير الاحتفاظ به؟',
                    'help_text' => 'بعض التقارير تحتوي ملخصات ومؤشرات مع إمكانية Drilldown إلى سجلات تفصيلية. المطلوب تحديد هل الملف يعكس العرض الملخص فقط أم يمكن أن يشمل التفاصيل التي تفسر النتائج.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 11,
                    'report_category' => 'export_rule',
                    'target_entity' => 'report_export',
                    'options' => [
                        ['label' => 'تصدير الملخص / العرض الحالي فقط', 'value' => 'current_summary_view_only'],
                        ['label' => 'تصدير البيانات التفصيلية الداخلة في النتائج فقط', 'value' => 'underlying_detail_rows_only'],
                        ['label' => 'دعم الملخص والتفاصيل كخيارين منفصلين حسب التقرير', 'value' => 'summary_and_detail_export_options'],
                    ],
                ],
                [
                    'seed_key' => 'report_features.export_context_metadata',
                    'title' => 'هل يجب أن يوضح ملف التقرير المطبوع أو المصدر سياق التقرير الذي أنتج الأرقام؟',
                    'help_text' => 'خصوصًا الفترة والمزرعة والفلاتر المستخدمة، حتى لا ينفصل الملف لاحقًا عن النطاق الذي حسبت عليه النتائج.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 12,
                    'report_category' => 'export_rule',
                    'target_entity' => 'report_export',
                    'options' => [],
                ],
            ];

            app(QuestionSeederSyncService::class)->sync(
                mainSectionName: 'التقارير والتحليلات والتنبيهات ومؤشرات الأداء',
                sectionName: 'خصائص التقارير والتصفية والتصدير',
                questions: $questions,
                prune: true,
                preserveAnswers: true,
            );
        });
    }
}
