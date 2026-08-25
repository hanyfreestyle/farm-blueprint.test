<?php

namespace Database\Seeders\Questions\Reports;

use App\Enums\Questionnaire\QuestionType;
use App\Services\Questionnaire\QuestionSeederSyncService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FertilityMatingPregnancyReportsQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $questions = [
                [
                    'seed_key' => 'fertility_report.primary_views',
                    'title' => 'ما العروض الرئيسية التي يجب أن يوفرها قسم تقارير الخصوبة والتلقيح والحمل؟',
                    'help_text' => 'حدد نطاق التقرير التحليلي لهذا الجزء من الدورة الإنتاجية. التلقيح وفحص الحمل والحمل أحداث مسجلة في Workflow، بينما التقرير يجمعها ويحلل نتائجها دون تنفيذ العملية أو تغيير حالتها.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 1,
                    'report_category' => 'report_scope',
                    'target_entity' => 'fertility_report',
                    'options' => [
                        ['label' => 'نشاط التلقيح ومحاولات التلقيح', 'value' => 'mating_activity'],
                        ['label' => 'نتائج فحوص الحمل', 'value' => 'pregnancy_check_results'],
                        ['label' => 'الحمل المؤكد وحالته الحالية', 'value' => 'confirmed_pregnancy_status'],
                        ['label' => 'النجاح والفشل عبر محاولات التلقيح', 'value' => 'attempt_success_failure'],
                        ['label' => 'الإجهاض وفقد الحمل والحالات الاستثنائية المرتبطة بالدورة', 'value' => 'pregnancy_exceptions'],
                    ],
                ],
                [
                    'seed_key' => 'fertility_report.mating_counting_granularity',
                    'title' => 'كيف يجب الفصل في التقارير بين عملية التلقيح الواحدة ومحاولة التلقيح ودورة الإنتاج؟',
                    'help_text' => 'الـWorkflow يميز بين Mating Event وMating Attempt وReproductive Cycle. التقرير يجب ألا يخلط هذه الوحدات في رقم واحد لأن كل مستوى يجيب عن سؤال تحليلي مختلف.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 2,
                    'report_category' => 'calculation_model',
                    'target_entity' => 'mating_report',
                    'options' => [
                        ['label' => 'إظهار مقاييس مستقلة لعمليات التلقيح والمحاولات ودورات الإنتاج', 'value' => 'separate_events_attempts_cycles'],
                        ['label' => 'اعتبار محاولة التلقيح الوحدة الرئيسية مع إمكانية فتح عمليات التلقيح التابعة لها', 'value' => 'attempt_primary_with_event_drilldown'],
                        ['label' => 'الاعتماد على عمليات التلقيح الفعلية فقط دون تحليل مستقل للمحاولات والدورات', 'value' => 'mating_events_only'],
                    ],
                ],
                [
                    'seed_key' => 'fertility_report.core_metrics',
                    'title' => 'ما المؤشرات الأساسية التي يجب أن يعرضها تقرير الخصوبة والتلقيح والحمل؟',
                    'help_text' => 'المرجع الوظيفي يذكر عدد عمليات ومحاولات التلقيح ونتائج فحص الحمل ونسبة النجاح ومتوسط المحاولات والحالات التي يتكرر فيها الفشل. Targets وحدود التنبيه ليست جزءًا من هذا السؤال.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 3,
                    'report_category' => 'report_metric',
                    'target_entity' => 'fertility_report',
                    'options' => [
                        ['label' => 'عدد عمليات التلقيح الفعلية', 'value' => 'mating_events_count'],
                        ['label' => 'عدد محاولات التلقيح', 'value' => 'mating_attempts_count'],
                        ['label' => 'عدد فحوص الحمل المنفذة', 'value' => 'pregnancy_checks_count'],
                        ['label' => 'عدد نتائج فحص الحمل الإيجابية', 'value' => 'positive_checks_count'],
                        ['label' => 'عدد نتائج فحص الحمل السلبية', 'value' => 'negative_checks_count'],
                        ['label' => 'عدد نتائج فحص الحمل غير المؤكدة', 'value' => 'uncertain_checks_count'],
                        ['label' => 'عدد حالات الحمل المؤكدة', 'value' => 'confirmed_pregnancies_count'],
                        ['label' => 'نسبة نجاح الوصول إلى حمل مؤكد', 'value' => 'pregnancy_success_rate'],
                        ['label' => 'متوسط عدد المحاولات اللازمة للوصول إلى حمل', 'value' => 'average_attempts_to_pregnancy'],
                        ['label' => 'عدد الإناث التي احتاجت أكثر من محاولة للوصول إلى حمل', 'value' => 'females_requiring_multiple_attempts'],
                        ['label' => 'عدد حالات الإجهاض أو فقد الحمل', 'value' => 'pregnancy_losses_count'],
                    ],
                ],
                [
                    'seed_key' => 'pregnancy_check_report.aggregation_model',
                    'title' => 'كيف يجب تحليل أكثر من فحص حمل داخل نفس محاولة التلقيح دون تضخيم النتائج؟',
                    'help_text' => 'قد يكون للمحاولة فحص أول غير مؤكد ثم فحص لاحق إيجابي أو سلبي. نحتاج الفصل بين عدد الفحوص المنفذة وبين النتيجة النهائية للمحاولة حتى لا تعتبر المحاولة الواحدة أكثر من نجاح أو فشل.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 4,
                    'report_category' => 'aggregation_rule',
                    'target_entity' => 'pregnancy_check_report',
                    'options' => [
                        ['label' => 'عرض كل الفحوص كسجل تنفيذ مستقل مع احتساب نتيجة نهائية واحدة فقط لكل محاولة عند تحليل النجاح والفشل', 'value' => 'all_checks_plus_single_final_attempt_outcome'],
                        ['label' => 'استخدام آخر فحص فقط في كل التقارير وإخفاء الفحوص السابقة من التجميع', 'value' => 'latest_check_only'],
                        ['label' => 'احتساب كل نتيجة فحص مستقلة ضمن نسب النجاح والفشل', 'value' => 'count_every_check_as_outcome'],
                    ],
                ],
                [
                    'seed_key' => 'fertility_report.success_rate_model',
                    'title' => 'على أي وحدة يجب أن تعتمد نسبة نجاح الحمل في التقرير؟',
                    'help_text' => 'عبارة «نسبة نجاح الحمل» قد تصبح مضللة إذا لم نحدد المقام بوضوح. المطلوب اختيار النموذج التحليلي، مع استخدام النتائج النهائية الموثقة وعدم اعتبار الفحص غير المؤكد نجاحًا أو فشلًا نهائيًا.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 5,
                    'report_category' => 'calculation_rule',
                    'target_entity' => 'fertility_report',
                    'options' => [
                        ['label' => 'الحمل المؤكد ÷ محاولات التلقيح ذات النتيجة النهائية المعروفة', 'value' => 'pregnancies_over_attempts_with_final_outcome'],
                        ['label' => 'الحمل المؤكد ÷ جميع محاولات التلقيح التي بدأت خلال الفترة', 'value' => 'pregnancies_over_all_started_attempts'],
                        ['label' => 'دعم أكثر من معدل مسمى بوضوح بدل الاعتماد على نسبة واحدة فقط', 'value' => 'multiple_clearly_named_success_rates'],
                    ],
                ],
                [
                    'seed_key' => 'fertility_report.repeated_failure_view',
                    'title' => 'كيف يجب أن يعرض التقرير حالات تكرار فشل الحمل أو الحاجة إلى محاولات متعددة؟',
                    'help_text' => 'المرجع يعتبر تكرار الفشل معلومة مهمة للتحليل والتنبيه. التقرير يعرض الحالات والعدد والسجل؛ أما الحد الذي يحول التكرار إلى Alert أو قرار تشغيلي فينتمي إلى Settings والتنبيهات.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 6,
                    'report_category' => 'analysis_view',
                    'target_entity' => 'fertility_failure_analysis',
                    'options' => [
                        ['label' => 'عرض العدد الإجمالي فقط للحالات التي تجاوزت أكثر من محاولة', 'value' => 'aggregate_count_only'],
                        ['label' => 'تجميع الحالات حسب عدد المحاولات مع قائمة الإناث في كل مجموعة', 'value' => 'group_by_attempt_count_with_females'],
                        ['label' => 'عرض سجل كل أنثى ذات تكرار فشل مع المحاولات والفحوص والنتائج المرتبطة', 'value' => 'detailed_repeated_failure_history'],
                    ],
                ],
                [
                    'seed_key' => 'fertility_report.female_reproductive_metrics',
                    'title' => 'ما مؤشرات الخصوبة الخاصة بالأنثى التي يجب أن يستطيع هذا التقرير عرضها؟',
                    'help_text' => 'هذا الجزء يعرض المؤشرات التناسلية فقط. التقييم الشامل للأنثى الذي يجمع الخصوبة والولادة والبطن والبقاء والنمو سيبقى في 5.7 تحليل أداء الحيوانات الإنتاجية.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 7,
                    'report_category' => 'subject_metric',
                    'target_entity' => 'female_reproductive_analysis',
                    'options' => [
                        ['label' => 'عدد دورات الإنتاج التي بدأت', 'value' => 'reproductive_cycles_count'],
                        ['label' => 'عدد محاولات التلقيح', 'value' => 'mating_attempts_count'],
                        ['label' => 'عدد حالات الحمل المؤكدة', 'value' => 'confirmed_pregnancies_count'],
                        ['label' => 'نسبة نجاح الحمل', 'value' => 'pregnancy_success_rate'],
                        ['label' => 'متوسط عدد المحاولات للوصول إلى حمل', 'value' => 'average_attempts_to_pregnancy'],
                        ['label' => 'عدد حالات الإجهاض أو فقد الحمل', 'value' => 'pregnancy_losses_count'],
                        ['label' => 'عدد الدورات التي انتهت بولادة مسجلة', 'value' => 'cycles_ending_in_birth'],
                    ],
                ],
                [
                    'seed_key' => 'fertility_report.male_reproductive_metrics',
                    'title' => 'ما مؤشرات الخصوبة الخاصة بالذكر التي يجب أن يستطيع هذا التقرير عرضها؟',
                    'help_text' => 'المرجع يطلب ربط نتائج الحمل بالذكر المستخدم فعليًا. هنا نركز على الخصوبة فقط، بينما تقييم جودة الأبناء والنتائج الإنتاجية الأوسع للذكر يظل ضمن 5.7.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 8,
                    'report_category' => 'subject_metric',
                    'target_entity' => 'male_reproductive_analysis',
                    'options' => [
                        ['label' => 'عدد الإناث التي تم تلقيحها باستخدام الذكر', 'value' => 'females_mated_count'],
                        ['label' => 'عدد عمليات التلقيح الفعلية المرتبطة بالذكر', 'value' => 'mating_events_count'],
                        ['label' => 'عدد محاولات التلقيح المرتبطة بالذكر', 'value' => 'mating_attempts_count'],
                        ['label' => 'عدد حالات الحمل المؤكدة الناتجة', 'value' => 'confirmed_pregnancies_count'],
                        ['label' => 'عدد النتائج النهائية السلبية المرتبطة بمحاولاته', 'value' => 'negative_attempt_outcomes_count'],
                        ['label' => 'نسبة نجاح الحمل المرتبطة باستخدامه', 'value' => 'pregnancy_success_rate'],
                    ],
                ],
                [
                    'seed_key' => 'pregnancy_report.current_state_views',
                    'title' => 'ما حالات الحمل الحالية التي يجب أن يستطيع التقرير إظهار أعدادها وقوائمها؟',
                    'help_text' => 'الحالة الحالية للحمل مشتقة أو محفوظة من Workflow وفق القرار المعتمد هناك. التقرير يعرضها فقط، ولا يغير الحمل أو ينشئ ولادة تلقائيًا عند الوصول للتاريخ المتوقع.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 9,
                    'report_category' => 'report_state',
                    'target_entity' => 'pregnancy_report',
                    'options' => [
                        ['label' => 'حمل مؤكد ونشط', 'value' => 'confirmed_active'],
                        ['label' => 'قريبة من فترة الولادة المتوقعة', 'value' => 'approaching_expected_birth'],
                        ['label' => 'داخل فترة الولادة المتوقعة', 'value' => 'within_expected_birth_window'],
                        ['label' => 'تجاوزت فترة الولادة المتوقعة دون حدث نهائي', 'value' => 'overdue_without_terminal_event'],
                        ['label' => 'حمل انتهى بولادة فعلية', 'value' => 'ended_by_birth'],
                        ['label' => 'حمل انتهى بحالة استثنائية مسجلة', 'value' => 'ended_by_exception'],
                    ],
                ],
                [
                    'seed_key' => 'pregnancy_report.exception_outcomes',
                    'title' => 'ما النتائج الاستثنائية المرتبطة بالحمل التي يجب تضمينها في تحليل هذا التقرير؟',
                    'help_text' => 'الحالات نفسها تسجل كأحداث Canonical في Workflow المناسب. التقرير يجمع أثرها على الخصوبة والحمل مع الاحتفاظ بالتمييز بينها وعدم تحويلها إلى مجرد حالة حالية للأنثى.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 10,
                    'report_category' => 'report_outcome',
                    'target_entity' => 'pregnancy_report',
                    'options' => [
                        ['label' => 'إجهاض', 'value' => 'abortion'],
                        ['label' => 'فقد حمل / انتهاء الحمل دون ولادة طبيعية', 'value' => 'pregnancy_loss'],
                        ['label' => 'حمل كاذب عند تسجيله كحالة استثنائية', 'value' => 'false_pregnancy'],
                        ['label' => 'نفوق الأنثى أثناء الحمل', 'value' => 'maternal_death_during_pregnancy'],
                        ['label' => 'إلغاء / إنهاء دورة الإنتاج لسبب صحي أو استثنائي', 'value' => 'exceptional_cycle_termination'],
                    ],
                ],
                [
                    'seed_key' => 'pregnancy_report.time_metrics',
                    'title' => 'ما المقاييس الزمنية التي يجب أن يدعمها تحليل التلقيح والحمل؟',
                    'help_text' => 'التواريخ والأحداث الفعلية موجودة في Workflow، بينما القيم المستهدفة والمواعيد المعيارية تأتي من Settings. التقرير يستطيع حساب الفترات الفعلية دون تحويلها إلى قواعد تشغيل.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 11,
                    'report_category' => 'time_metric',
                    'target_entity' => 'fertility_report',
                    'options' => [
                        ['label' => 'المدة من التلقيح المرجعي إلى فحص الحمل الفعلي', 'value' => 'mating_to_check_duration'],
                        ['label' => 'المدة من بدء المحاولة إلى تأكيد الحمل', 'value' => 'attempt_to_pregnancy_confirmation_duration'],
                        ['label' => 'المدة من الولادة السابقة إلى أول تلقيح تالٍ عند توفر السياق', 'value' => 'previous_birth_to_next_mating_interval'],
                        ['label' => 'الفترة بين ولادتين متتاليتين عند اكتمال الدورتين', 'value' => 'inter_birth_interval'],
                        ['label' => 'مدة الحمل الفعلية عند انتهاء الحمل بولادة مسجلة', 'value' => 'actual_gestation_duration'],
                    ],
                ],
                [
                    'seed_key' => 'fertility_report.drilldown_model',
                    'title' => 'إلى أي مستوى يجب أن يستطيع المستخدم النزول من مؤشرات الخصوبة والتلقيح والحمل؟',
                    'help_text' => 'الهدف أن تكون النسب والأعداد قابلة للتفسير والرجوع إلى مصدرها بدل عرض أرقام مجمعة بلا أثر تاريخي. الصفحات التحليلية الشاملة للكيانات نفسها ستراجع لاحقًا في 5.12.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 12,
                    'report_category' => 'interaction_rule',
                    'target_entity' => 'fertility_report',
                    'options' => [
                        ['label' => 'الأرقام والنسب الإجمالية فقط', 'value' => 'aggregate_only'],
                        ['label' => 'من المؤشر إلى قائمة الإناث أو الذكور أو دورات الإنتاج الداخلة في الحساب', 'value' => 'drilldown_to_subjects_and_cycles'],
                        ['label' => 'من المؤشر إلى الحيوان / الدورة ثم إلى المحاولة وفحوص الحمل والأحداث Canonical المفسرة للنتيجة', 'value' => 'drilldown_to_attempts_checks_and_source_events'],
                    ],
                ],
            ];

            app(QuestionSeederSyncService::class)->sync(
                mainSectionName: 'التقارير والتحليلات والتنبيهات ومؤشرات الأداء',
                sectionName: 'تقارير الخصوبة والتلقيح والحمل',
                questions: $questions,
                prune: true,
                preserveAnswers: true,
            );
        });
    }
}
