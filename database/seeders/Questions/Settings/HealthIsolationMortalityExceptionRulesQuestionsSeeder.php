<?php

namespace Database\Seeders\Questions\Settings;

use App\Enums\Questionnaire\QuestionDependencyOperator;
use App\Enums\Questionnaire\QuestionType;
use App\Models\QuestionnaireQuestion;
use App\Models\QuestionnaireSection;
use App\Services\Questionnaire\QuestionSeederSyncService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HealthIsolationMortalityExceptionRulesQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $questions = [
                [
                    'seed_key' => 'health_rules.operational_restriction_scope',
                    'title' => 'ما العمليات التي يجب أن تستطيع الحالة الصحية تقييد أهلية الحيوان لها؟',
                    'help_text' => 'المصدر يؤكد أن الحالة الصحية ليست معلومة فقط، بل قد تمنع التلقيح أو استخدام الذكر أو بعض الحركات وتؤثر على الجاهزية التشغيلية. هذا السؤال يحدد نطاق القواعد، بينما الواقعة الصحية نفسها تسجل في 4.13.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 1,
                    'report_category' => 'health_restriction_rule',
                    'target_entity' => 'health_operational_restriction',
                    'options' => [
                        ['label' => 'تلقيح الأنثى / بدء محاولة تلقيح جديدة', 'value' => 'female_mating'],
                        ['label' => 'استخدام الذكر في التلقيح', 'value' => 'male_mating_use'],
                        ['label' => 'النقل أو التسكين العادي مع استثناء النقل الضروري للعزل أو الطوارئ', 'value' => 'routine_housing_movement'],
                        ['label' => 'الاعتماد أو الاستمرار داخل القطيع الإنتاجي', 'value' => 'production_herd_eligibility'],
                        ['label' => 'الجاهزية للبيع عند اشتراط حالة صحية مناسبة', 'value' => 'sale_readiness'],
                        ['label' => 'عمليات تشغيلية أخرى تحددها قواعد المزرعة', 'value' => 'other_configured_operations'],
                    ],
                ],
                [
                    'seed_key' => 'health_rules.restriction_trigger_factors',
                    'title' => 'ما المعلومات التي يجب أن تدخل في تحديد القيود التشغيلية الناتجة عن الحالة الصحية؟',
                    'help_text' => 'لا يطلب هذا السؤال إنشاء تشخيص بيطري جديد؛ هو يحدد أي بيانات موجودة يمكن للقواعد استخدامها للحكم على أهلية الحيوان للتشغيل.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 2,
                    'report_category' => 'health_restriction_rule',
                    'target_entity' => 'health_operational_restriction',
                    'options' => [
                        ['label' => 'الحالة الصحية الحالية المشتقة من السجل الصحي', 'value' => 'current_health_state'],
                        ['label' => 'تصنيف المشكلة / الملاحظة الصحية من Master Data', 'value' => 'health_problem_category'],
                        ['label' => 'درجة الخطورة المسجلة في المتابعة الصحية', 'value' => 'severity'],
                        ['label' => 'وجود فترة عزل نشطة', 'value' => 'active_isolation'],
                        ['label' => 'قرار صحي موثق خاص بالحالة يفرض قيدًا مؤقتًا', 'value' => 'documented_case_restriction'],
                        ['label' => 'سياق الحيوان الحالي مثل حمل أو رضاعة أو تسمين عند الحاجة', 'value' => 'current_operational_context'],
                    ],
                ],
                [
                    'seed_key' => 'health_rules.followup_schedule_model',
                    'title' => 'كيف يجب تحديد موعد مراجعة الحالة الصحية عندما تحتاج الحالة إلى متابعة؟',
                    'help_text' => 'المصدر يحتاج متابعة صحية ومواعيد مراجعة دون بناء Treatment Module كامل. تنفيذ المراجعة يسجل في 4.13، وتوليد المهمة وأولويتها يحدد في 6.12.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 3,
                    'report_category' => 'health_followup_rule',
                    'target_entity' => 'health_followup_rule',
                    'options' => [
                        ['label' => 'لا توجد دورية عامة؛ يحدد موعد المراجعة لكل حالة عند تسجيلها', 'value' => 'case_specific_review_date'],
                        ['label' => 'توجد دورية مراجعة افتراضية ثابتة لكل الحالات التي تحتاج متابعة', 'value' => 'fixed_default_review_interval'],
                        ['label' => 'تختلف دورية المراجعة حسب تصنيف المشكلة أو درجة الخطورة', 'value' => 'review_interval_by_problem_or_severity'],
                    ],
                ],
                [
                    'seed_key' => 'health_rules.followup_interval_days',
                    'title' => 'كل كم يوم تستحق المراجعة الصحية الافتراضية عند استخدام دورية ثابتة؟',
                    'help_text' => 'هذه القيمة تحدد موعد الاستحقاق فقط، ولا تنشئ سجل متابعة قبل تنفيذ المراجعة الفعلية.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 4,
                    'report_category' => 'health_followup_interval',
                    'target_entity' => 'health_followup_rule',
                    'options' => [],
                ],
                [
                    'seed_key' => 'health_rules.return_to_operation_criteria',
                    'title' => 'ما الشروط التي يجب مراجعتها قبل رفع القيود الصحية وعودة الحيوان للتشغيل؟',
                    'help_text' => 'المصدر يضع التسلسل: انتهاء الحالة الصحية أو التعافي → إعادة تقييم → العودة للتشغيل. هذا السؤال يحدد شروط القاعدة، بينما حدث التعافي أو إنهاء العزل نفسه يبقى في 4.13.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 5,
                    'report_category' => 'health_return_rule',
                    'target_entity' => 'health_recovery',
                    'options' => [
                        ['label' => 'وجود حدث تعافٍ / مراجعة صحية يسمح بالعودة', 'value' => 'recovery_or_clearance_recorded'],
                        ['label' => 'انتهاء العزل فعليًا إذا كان الحيوان معزولًا', 'value' => 'isolation_released_if_applicable'],
                        ['label' => 'اجتياز إعادة تقييم صحي قبل رفع القيود', 'value' => 'health_reevaluation_passed'],
                        ['label' => 'إعادة تطبيق قواعد الجاهزية الخاصة بالعملية المطلوبة بدل استعادة كل الصلاحيات تلقائيًا', 'value' => 'reapply_domain_readiness_rules'],
                        ['label' => 'وجود موقع إيواء مؤهل عند الحاجة للنقل بعد العزل', 'value' => 'eligible_housing_available_if_needed'],
                    ],
                ],
                [
                    'seed_key' => 'isolation_rules.trigger_model',
                    'title' => 'كيف يجب أن يحدد النظام أن الحالة الصحية تستوجب العزل؟',
                    'help_text' => 'المصدر يضع «متى يستوجب الحيوان العزل» كنقطة مراجعة. قائمة أسباب العزل نفسها موجودة في Master Data، وقرار/حدث العزل الفعلي يسجل في 4.13.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 6,
                    'report_category' => 'isolation_rule',
                    'target_entity' => 'health_isolation',
                    'options' => [
                        ['label' => 'يعرض النظام السياق ويترك قرار العزل للمستخدم في كل حالة', 'value' => 'manual_isolation_decision_with_context'],
                        ['label' => 'تقترح القواعد العزل عند تحقق الشروط ويحتاج المستخدم إلى تأكيد القرار', 'value' => 'rule_recommendation_requires_confirmation'],
                        ['label' => 'توجد حالات معرفة كعزل إلزامي عند تحقق قواعدها مع تطبيق سياسة التجاوز العامة في 6.2 إن سمحت', 'value' => 'rule_required_isolation_when_criteria_match'],
                    ],
                ],
                [
                    'seed_key' => 'isolation_rules.trigger_factors',
                    'title' => 'ما العوامل التي يجب أن تستطيع قواعد العزل الاعتماد عليها؟',
                    'help_text' => 'المطلوب الاعتماد على معلومات صحية وتشغيلية موجودة، دون اختراع تشخيصات أو بروتوكولات علاج غير موجودة في المرجع.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 7,
                    'report_category' => 'isolation_rule',
                    'target_entity' => 'health_isolation',
                    'options' => [
                        ['label' => 'تصنيف المشكلة / الملاحظة الصحية', 'value' => 'health_problem_category'],
                        ['label' => 'درجة الخطورة', 'value' => 'severity'],
                        ['label' => 'الحالة الصحية الحالية', 'value' => 'current_health_state'],
                        ['label' => 'قرار صحي صريح من المسؤول', 'value' => 'explicit_health_review_decision'],
                        ['label' => 'السياق التشغيلي الحالي إذا كان يؤثر على قرار العزل', 'value' => 'current_operational_context'],
                    ],
                ],
                [
                    'seed_key' => 'isolation_rules.review_schedule_model',
                    'title' => 'كيف يجب تحديد موعد مراجعة الحيوان أثناء فترة العزل؟',
                    'help_text' => 'المصدر ينص على تحديد موعد مراجعة للحالة أثناء العزل، بينما تنفيذ المراجعة وتسجيل نتيجتها في 4.13.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 8,
                    'report_category' => 'isolation_review_rule',
                    'target_entity' => 'health_isolation',
                    'options' => [
                        ['label' => 'موعد مراجعة يحدد لكل حالة عند بدء العزل', 'value' => 'case_specific_review_date'],
                        ['label' => 'مراجعة دورية بفاصل أيام افتراضي ثابت', 'value' => 'fixed_periodic_review_interval'],
                        ['label' => 'تختلف دورية المراجعة حسب سبب العزل أو درجة الخطورة', 'value' => 'review_interval_by_reason_or_severity'],
                    ],
                ],
                [
                    'seed_key' => 'isolation_rules.review_interval_days',
                    'title' => 'كل كم يوم تستحق مراجعة الحيوان أثناء العزل عند استخدام دورية ثابتة؟',
                    'help_text' => 'القيمة للجدولة فقط؛ المهمة والتنبيه الناتجان عن الموعد يعالجان في 6.12.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 9,
                    'report_category' => 'isolation_review_interval',
                    'target_entity' => 'health_isolation',
                    'options' => [],
                ],
                [
                    'seed_key' => 'isolation_rules.expected_duration_model',
                    'title' => 'هل تحتاج المزرعة إلى مدة متوقعة أو حد زمني لمتابعة طول فترة العزل؟',
                    'help_text' => 'التقارير والتنبيهات تحتاج معرفة متى تصبح فترة العزل أطول من المتوقع. تجاوز المدة لا ينهي العزل تلقائيًا؛ هو حالة تحتاج مراجعة.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 10,
                    'report_category' => 'isolation_duration_rule',
                    'target_entity' => 'health_isolation',
                    'options' => [
                        ['label' => 'لا توجد مدة متوقعة عامة؛ يعتمد انتهاء العزل على المراجعة فقط', 'value' => 'no_default_expected_duration'],
                        ['label' => 'توجد مدة متوقعة افتراضية واحدة للعزل تستخدم لاكتشاف الحالات المتأخرة', 'value' => 'fixed_default_expected_duration'],
                        ['label' => 'تختلف المدة المتوقعة حسب سبب العزل أو درجة الخطورة', 'value' => 'expected_duration_by_reason_or_severity'],
                    ],
                ],
                [
                    'seed_key' => 'isolation_rules.default_expected_duration_days',
                    'title' => 'ما المدة المتوقعة الافتراضية للعزل، بالأيام؟',
                    'help_text' => 'تستخدم لاكتشاف تجاوز المدة المتوقعة فقط، ولا تغلق العزل تلقائيًا عند انتهائها.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 11,
                    'report_category' => 'isolation_duration_threshold',
                    'target_entity' => 'health_isolation',
                    'options' => [],
                ],
                [
                    'seed_key' => 'isolation_rules.release_criteria',
                    'title' => 'ما الشروط التي يجب مراجعتها قبل إنهاء العزل؟',
                    'help_text' => 'إنهاء العزل لا يعني تلقائيًا العودة لكل العمليات؛ بعده تطبق قواعد العودة للتشغيل والجاهزية الحالية.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 12,
                    'report_category' => 'isolation_release_rule',
                    'target_entity' => 'health_isolation',
                    'options' => [
                        ['label' => 'نتيجة مراجعة صحية تسمح بإنهاء العزل', 'value' => 'health_review_allows_release'],
                        ['label' => 'زوال سبب العزل أو عدم بقائه نشطًا', 'value' => 'isolation_reason_no_longer_active'],
                        ['label' => 'تسجيل نتيجة نهاية العزل صراحة', 'value' => 'release_outcome_recorded'],
                        ['label' => 'وجود موقع مؤهل للعودة أو النقل عند الحاجة', 'value' => 'eligible_destination_available'],
                        ['label' => 'إعادة تقييم القيود التشغيلية قبل استئناف النشاط', 'value' => 'operational_restrictions_reevaluated'],
                    ],
                ],
                [
                    'seed_key' => 'mortality_rules.rate_threshold_scopes',
                    'title' => 'في أي مراحل تحتاج المزرعة إلى نسبة نفوق قابلة للضبط لاكتشاف الارتفاع غير الطبيعي؟',
                    'help_text' => 'المصدر يذكر نسب النفوق في البطن والرضاعة والمفطومين والتسمين. هذه Thresholds لا تنشئ Alert بذاتها؛ قواعد إنشاء التنبيه ومستواه تعالج في 6.12.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 13,
                    'report_category' => 'mortality_threshold_rule',
                    'target_entity' => 'mortality_detection_rule',
                    'options' => [
                        ['label' => 'نفوق البطن / المواليد قبل الفطام', 'value' => 'litter_mortality_rate'],
                        ['label' => 'النفوق خلال فترة الرضاعة', 'value' => 'lactation_mortality_rate'],
                        ['label' => 'النفوق بعد الفطام', 'value' => 'postweaning_mortality_rate'],
                        ['label' => 'النفوق أثناء التسمين', 'value' => 'fattening_mortality_rate'],
                    ],
                ],
                [
                    'seed_key' => 'mortality_rules.litter_rate_threshold_percent',
                    'title' => 'ما نسبة نفوق البطن / المواليد قبل الفطام التي تعتبر أعلى من الحد المقبول؟',
                    'help_text' => 'أدخل النسبة المئوية المستخدمة كThreshold عند اعتماد هذا النطاق. طريقة عرض المؤشر وحسابه التقريرى تبقى في Reports.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 14,
                    'report_category' => 'mortality_threshold',
                    'target_entity' => 'mortality_detection_rule',
                    'options' => [],
                ],
                [
                    'seed_key' => 'mortality_rules.lactation_rate_threshold_percent',
                    'title' => 'ما نسبة النفوق خلال الرضاعة التي تعتبر أعلى من الحد المقبول؟',
                    'help_text' => 'القيمة Threshold للمراجعة أو التنبيه عند اعتمادها، وليست سبب نفوق أو نتيجة صحية بحد ذاتها.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 15,
                    'report_category' => 'mortality_threshold',
                    'target_entity' => 'mortality_detection_rule',
                    'options' => [],
                ],
                [
                    'seed_key' => 'mortality_rules.postweaning_rate_threshold_percent',
                    'title' => 'ما نسبة النفوق بعد الفطام التي تعتبر أعلى من الحد المقبول؟',
                    'help_text' => 'يستخدم النظام السجلات الفعلية في 4.13 والحيوانات المعرضة للخطر في الفترة المناسبة لحساب المؤشر؛ هذا السؤال يحدد الحد فقط.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 16,
                    'report_category' => 'mortality_threshold',
                    'target_entity' => 'mortality_detection_rule',
                    'options' => [],
                ],
                [
                    'seed_key' => 'mortality_rules.fattening_rate_threshold_percent',
                    'title' => 'ما نسبة النفوق أثناء التسمين التي تعتبر أعلى من الحد المقبول؟',
                    'help_text' => 'القيمة تستخدم للحكم على مسار التسمين، بينما سجل النفوق نفسه يبقى Canonical في 4.13.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 17,
                    'report_category' => 'mortality_threshold',
                    'target_entity' => 'mortality_detection_rule',
                    'options' => [],
                ],
                [
                    'seed_key' => 'mortality_rules.rolling_count_detection_enabled',
                    'title' => 'هل يجب اكتشاف ارتفاع النفوق أيضًا من عدد الحالات خلال فترة زمنية محددة، بصرف النظر عن النسبة؟',
                    'help_text' => 'المصدر يذكر صراحة عدد حالات النفوق خلال فترة زمنية معينة كشرط محتمل للتنبيه الإداري.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 18,
                    'report_category' => 'mortality_count_rule',
                    'target_entity' => 'mortality_detection_rule',
                    'options' => [],
                ],
                [
                    'seed_key' => 'mortality_rules.rolling_count_threshold',
                    'title' => 'كم حالة نفوق داخل فترة القياس تعتبر تجاوزًا يستوجب المراجعة؟',
                    'help_text' => 'هذه قيمة الكشف فقط؛ إنشاء Alert أو Task عند تجاوزها يحدد في 6.12.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 19,
                    'report_category' => 'mortality_count_threshold',
                    'target_entity' => 'mortality_detection_rule',
                    'options' => [],
                ],
                [
                    'seed_key' => 'mortality_rules.rolling_count_window_days',
                    'title' => 'ما طول الفترة الزمنية المستخدمة لعد حالات النفوق المتقاربة، بالأيام؟',
                    'help_text' => 'المصدر يترك الفترة المستخدمة لاكتشاف ارتفاع النفوق نقطة تحتاج مراجعة، لذلك لا نفرض عدد أيام ثابتًا.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 20,
                    'report_category' => 'mortality_count_window',
                    'target_entity' => 'mortality_detection_rule',
                    'options' => [],
                ],
                [
                    'seed_key' => 'mortality_rules.cluster_detection_enabled',
                    'title' => 'هل يجب أن يبحث النظام عن تجمعات غير طبيعية لحالات النفوق بدل تقييم كل حالة منفردة فقط؟',
                    'help_text' => 'المصدر يذكر تجمع الحالات في عنبر أو بطارية أو قفص وارتفاع النفوق في سلالة معينة كإشارات مهمة.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 21,
                    'report_category' => 'mortality_cluster_rule',
                    'target_entity' => 'mortality_cluster_detection',
                    'options' => [],
                ],
                [
                    'seed_key' => 'mortality_rules.cluster_dimensions',
                    'title' => 'على أي أبعاد يجب اكتشاف تجمع حالات النفوق؟',
                    'help_text' => 'اختر الأبعاد المدعومة من المرجع والتي تساعد على كشف نمط مكاني أو مرتبط بالسلالة.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 22,
                    'report_category' => 'mortality_cluster_rule',
                    'target_entity' => 'mortality_cluster_detection',
                    'options' => [
                        ['label' => 'العنبر', 'value' => 'barn'],
                        ['label' => 'البطارية', 'value' => 'battery'],
                        ['label' => 'القفص / العين', 'value' => 'cage'],
                        ['label' => 'السلالة', 'value' => 'breed'],
                    ],
                ],
                [
                    'seed_key' => 'mortality_rules.cluster_case_threshold',
                    'title' => 'ما الحد الأدنى لعدد حالات النفوق المتجمعة في نفس البعد حتى تعتبر الحالة غير طبيعية؟',
                    'help_text' => 'لا نحول أمثلة المصدر مثل 4 أو 5 حالات إلى قيم ثابتة؛ المزرعة تحدد الحد الذي يناسبها.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 23,
                    'report_category' => 'mortality_cluster_threshold',
                    'target_entity' => 'mortality_cluster_detection',
                    'options' => [],
                ],
                [
                    'seed_key' => 'mortality_rules.cluster_window_days',
                    'title' => 'خلال كم يوم يجب أن تقع الحالات حتى تعتبر تجمعًا واحدًا غير طبيعي؟',
                    'help_text' => 'الفترة الزمنية قابلة للضبط ولا تفترض رقمًا ثابتًا. التنبيه الناتج عن اكتشاف التجمع يعالج في 6.12.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 24,
                    'report_category' => 'mortality_cluster_window',
                    'target_entity' => 'mortality_cluster_detection',
                    'options' => [],
                ],
                [
                    'seed_key' => 'exception_rules.configurable_categories',
                    'title' => 'ما الحالات الاستثنائية التي تحتاج قواعد مسبقة لتحديد أثرها على المسار التشغيلي؟',
                    'help_text' => '4.14 يسجل الواقعة ويعيد بناء المسار. هنا نحدد الحالات التي تحتاج قواعد تشغيل قابلة للضبط بدل ترك أثر كل حالة للاجتهاد اليدوي الكامل.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 25,
                    'report_category' => 'exception_rule_scope',
                    'target_entity' => 'workflow_exception_rule',
                    'options' => [
                        ['label' => 'الإجهاض أو فقد الحمل أو الحمل الكاذب / التشخيص غير الصحيح', 'value' => 'pregnancy_exception'],
                        ['label' => 'نفوق الأم أثناء الحمل', 'value' => 'maternal_death_during_pregnancy'],
                        ['label' => 'نفوق الأم أثناء الرضاعة ووجود مواليد أحياء', 'value' => 'maternal_death_during_lactation'],
                        ['label' => 'فقد البطن بالكامل أثناء الرضاعة', 'value' => 'complete_litter_loss'],
                        ['label' => 'مشكلة صحية للأم تؤثر على قدرتها على الرضاعة', 'value' => 'maternal_health_interrupts_lactation'],
                        ['label' => 'الفطام المبكر أو المتأخر كحالة خروج عن المسار المستهدف', 'value' => 'early_or_late_weaning'],
                        ['label' => 'مرض أو عزل أثناء دورة التلقيح / الحمل', 'value' => 'health_or_isolation_during_reproductive_cycle'],
                        ['label' => 'الحيوان المفقود ثم العثور عليه لاحقًا', 'value' => 'animal_missing_and_found'],
                    ],
                ],
                [
                    'seed_key' => 'exception_rules.application_model',
                    'title' => 'عندما توجد قاعدة معتمدة لحالة استثنائية، كيف يجب تطبيقها على الخطوة التالية؟',
                    'help_text' => 'الهدف تحديد درجة الأتمتة في القرار دون تكرار تنفيذ الأحداث في 4.14. إلغاء/توليد المهام الفعلية يخضع لتكامل 4.17 وقواعد 6.12.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 26,
                    'report_category' => 'exception_rule_model',
                    'target_entity' => 'workflow_exception_rule',
                    'options' => [
                        ['label' => 'تحدد القواعد الإجراءات المقترحة ويؤكد المستخدم خطة إعادة البناء قبل التنفيذ', 'value' => 'rules_recommend_actions_user_confirms'],
                        ['label' => 'تطبق الانتقالات الحتمية فقط تلقائيًا، بينما القرارات التي لها أكثر من مسار تحتاج تأكيد المستخدم', 'value' => 'auto_apply_deterministic_require_confirmation_for_choices'],
                        ['label' => 'تعرض القواعد السياق فقط ويحدد المستخدم كل خطوة تالية يدويًا', 'value' => 'rules_provide_context_manual_next_step'],
                    ],
                ],
                [
                    'seed_key' => 'abortion_rules.post_event_requirements',
                    'title' => 'ما المتطلبات التي يجب تطبيقها بعد الإجهاض أو فقد الحمل قبل عودة الأنثى لمسار التلقيح؟',
                    'help_text' => 'المصدر يذكر المتابعة الصحية، إعادة تقييم الأنثى، وفترة راحة محتملة بعد الإجهاض. الحدث نفسه يسجل في 4.14 ولا يعتبر ولادة.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 27,
                    'report_category' => 'post_abortion_rule',
                    'target_entity' => 'reproductive_cycle_exception',
                    'options' => [
                        ['label' => 'مراجعة / متابعة صحية بعد الحالة', 'value' => 'health_followup_required'],
                        ['label' => 'فترة راحة قبل السماح بمحاولة تلقيح جديدة', 'value' => 'rest_period_before_remating'],
                        ['label' => 'إعادة تقييم جاهزية الأنثى قبل العودة للتلقيح', 'value' => 'readiness_reevaluation_required'],
                        ['label' => 'قرار مراجعة بشري إذا تكررت الحالة قبل بدء دورة جديدة', 'value' => 'management_review_on_recurrence'],
                    ],
                ],
                [
                    'seed_key' => 'abortion_rules.rest_policy',
                    'title' => 'كيف يجب تحديد فترة الراحة بعد الإجهاض أو فقد الحمل عند اعتمادها؟',
                    'help_text' => 'المصدر يضع مدة الراحة المطلوبة بعد الإجهاض كنقطة تحتاج مراجعة ولا يفرض رقمًا ثابتًا.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 28,
                    'report_category' => 'post_abortion_rest_rule',
                    'target_entity' => 'reproductive_cycle_exception',
                    'options' => [
                        ['label' => 'لا توجد مدة ثابتة؛ العودة تعتمد على إعادة تقييم الحالة والجاهزية', 'value' => 'condition_based_no_fixed_rest'],
                        ['label' => 'توجد مدة راحة ثابتة قابلة للضبط ثم تعاد مراجعة الجاهزية', 'value' => 'fixed_rest_days_then_reassess'],
                        ['label' => 'تختلف مدة الراحة حسب نوع الحالة أو تقييمها الصحي', 'value' => 'rest_duration_by_exception_or_health_review'],
                    ],
                ],
                [
                    'seed_key' => 'abortion_rules.rest_days',
                    'title' => 'كم يومًا تستمر فترة الراحة الثابتة بعد الإجهاض أو فقد الحمل؟',
                    'help_text' => 'تستخدم فقط عند اعتماد مدة ثابتة، ولا تعيد الأنثى للتلقيح تلقائيًا دون تطبيق شروط الجاهزية الحالية.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 29,
                    'report_category' => 'post_abortion_rest_threshold',
                    'target_entity' => 'reproductive_cycle_exception',
                    'options' => [],
                ],
                [
                    'seed_key' => 'abortion_rules.recurrence_threshold_enabled',
                    'title' => 'هل يجب تحديد عدد حالات إجهاض / فقد حمل متكرر يستوجب مراجعة إدارية خاصة؟',
                    'help_text' => 'المصدر يذكر تنبيه تكرار الإجهاض عند الوصول لحد محدد، مع التأكيد أن ذلك لا يؤدي إلى الاستبعاد تلقائيًا.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 30,
                    'report_category' => 'abortion_recurrence_rule',
                    'target_entity' => 'reproductive_cycle_exception',
                    'options' => [],
                ],
                [
                    'seed_key' => 'abortion_rules.recurrence_count',
                    'title' => 'بعد كم حالة إجهاض / فقد حمل متكرر تصبح الأنثى مستحقة للمراجعة الإدارية الخاصة؟',
                    'help_text' => 'هذه قيمة كشف ومراجعة فقط؛ لا تنشئ قرار استبعاد آليًا.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 31,
                    'report_category' => 'abortion_recurrence_threshold',
                    'target_entity' => 'reproductive_cycle_exception',
                    'options' => [],
                ],
                [
                    'seed_key' => 'exception_rules.orphaned_litter_handling_requirement',
                    'title' => 'عند نفوق الأم أثناء الرضاعة مع وجود مواليد أحياء، ما الحد الأدنى المطلوب قبل اعتبار الحالة الاستثنائية معالجة؟',
                    'help_text' => 'المصدر يؤكد أن البطن لا تغلق تلقائيًا وأنه يجب تسجيل الإجراء التالي للمواليد، مثل أم حاضنة أو توزيع أو رعاية بديلة أو فطام مبكر وفق القواعد المناسبة.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 32,
                    'report_category' => 'orphaned_litter_rule',
                    'target_entity' => 'workflow_exception_rule',
                    'options' => [
                        ['label' => 'يجب اختيار وتسجيل إجراء رعاية تالٍ قبل إغلاق معالجة الاستثناء', 'value' => 'require_followup_care_action_before_resolution'],
                        ['label' => 'يمكن إبقاء الحالة مفتوحة كاستثناء غير محلول حتى يحدد المسؤول الإجراء التالي', 'value' => 'allow_open_exception_until_care_decision'],
                        ['label' => 'يغلق البطن تلقائيًا عند نفوق الأم دون انتظار قرار للمواليد', 'value' => 'auto_close_litter_on_maternal_death'],
                    ],
                ],
                [
                    'seed_key' => 'exception_rules.full_litter_loss_maternal_followup_model',
                    'title' => 'بعد فقد البطن بالكامل أثناء الرضاعة، كيف يجب تحديد المتابعة التالية للأم؟',
                    'help_text' => 'المصدر ينص على إغلاق الرضاعة وإلغاء الفطام ثم إعادة تقييم وضع الأم وإنشاء المتابعة المناسبة، دون افتراض أنها جاهزة مباشرة لدورة جديدة.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 33,
                    'report_category' => 'litter_loss_rule',
                    'target_entity' => 'maternal_recovery_rule',
                    'options' => [
                        ['label' => 'إعادة تقييم حالة الأم إلزامية قبل تحديد المسار التالي', 'value' => 'require_maternal_reevaluation'],
                        ['label' => 'تطبق قواعد ما بعد انتهاء الرضاعة والجاهزية الحالية مباشرة مع إظهار سبب النهاية', 'value' => 'apply_post_lactation_and_readiness_rules'],
                        ['label' => 'يحدد المسؤول المسار التالي يدويًا دون قاعدة افتراضية', 'value' => 'manual_next_path_decision'],
                    ],
                ],
                [
                    'seed_key' => 'exception_rules.early_weaning_allowed_reasons',
                    'title' => 'ما الحالات الاستثنائية التي يمكن أن تبرر دراسة الفطام المبكر قبل العمر المستهدف؟',
                    'help_text' => 'الفطام المبكر لا يصبح تلقائيًا لمجرد وجود هذه الأسباب؛ يجب أن يظل خاضعًا لحدود الفطام في 6.8 وسياسة التحكم والتجاوز في 6.2 عند الحاجة.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 34,
                    'report_category' => 'early_weaning_exception_rule',
                    'target_entity' => 'weaning_exception_rule',
                    'options' => [
                        ['label' => 'نفوق الأم المرضعة', 'value' => 'maternal_death'],
                        ['label' => 'مشكلة صحية أو عزل يمنع الأم من الاستمرار في الرعاية', 'value' => 'maternal_health_or_isolation'],
                        ['label' => 'مشكلة في الرضاعة / الأمومة تجعل استمرار البطن على الوضع الحالي غير مناسب', 'value' => 'lactation_or_maternal_care_problem'],
                        ['label' => 'عدم توفر أم حاضنة مناسبة بعد الحاجة لنقل المواليد', 'value' => 'no_eligible_foster_available'],
                        ['label' => 'سبب استثنائي آخر موثق يخضع للمراجعة', 'value' => 'other_documented_exception'],
                    ],
                ],
                [
                    'seed_key' => 'exception_rules.health_during_reproductive_cycle_model',
                    'title' => 'إذا حدث مرض أو عزل أثناء دورة التلقيح أو الحمل، كيف يجب أن تحدد القواعد أثر الحالة على استمرار الدورة؟',
                    'help_text' => 'المصدر يؤكد عدم حذف الدورة السابقة. المطلوب فقط تحديد كيفية الحكم على الاستمرار أو الإيقاف أو الإنهاء الصحي مع بقاء القرار والحدث التاريخي في 4.13 و4.14.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 35,
                    'report_category' => 'reproductive_health_exception_rule',
                    'target_entity' => 'workflow_exception_rule',
                    'options' => [
                        ['label' => 'تستخدم قيود الصحة وقواعد الجاهزية لتحديد ما يمكن استمراره، وتحتاج القرارات غير الحتمية لمراجعة المستخدم', 'value' => 'derive_allowed_actions_from_health_rules_with_review'],
                        ['label' => 'أي مرض أو عزل أثناء الدورة يوقف المسار تلقائيًا حتى قرار صريح باستئنافه أو إنهائه', 'value' => 'pause_cycle_until_explicit_decision'],
                        ['label' => 'تستمر الدورة افتراضيًا، ولا تتغير إلا إذا سجل المستخدم قرارًا صحيًا خاصًا بالحالة', 'value' => 'continue_unless_explicit_case_restriction'],
                    ],
                ],
            ];

            app(QuestionSeederSyncService::class)->sync(
                mainSectionName: 'الإعدادات وقواعد التشغيل',
                sectionName: 'قواعد الصحة والعزل والنفوق والحالات الاستثنائية',
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

        $dependencies = [
            ['health_rules.followup_schedule_model', 'health_rules.followup_interval_days', QuestionDependencyOperator::EQUALS, 'fixed_default_review_interval'],
            ['isolation_rules.review_schedule_model', 'isolation_rules.review_interval_days', QuestionDependencyOperator::EQUALS, 'fixed_periodic_review_interval'],
            ['isolation_rules.expected_duration_model', 'isolation_rules.default_expected_duration_days', QuestionDependencyOperator::EQUALS, 'fixed_default_expected_duration'],
            ['mortality_rules.rate_threshold_scopes', 'mortality_rules.litter_rate_threshold_percent', QuestionDependencyOperator::CONTAINS, 'litter_mortality_rate'],
            ['mortality_rules.rate_threshold_scopes', 'mortality_rules.lactation_rate_threshold_percent', QuestionDependencyOperator::CONTAINS, 'lactation_mortality_rate'],
            ['mortality_rules.rate_threshold_scopes', 'mortality_rules.postweaning_rate_threshold_percent', QuestionDependencyOperator::CONTAINS, 'postweaning_mortality_rate'],
            ['mortality_rules.rate_threshold_scopes', 'mortality_rules.fattening_rate_threshold_percent', QuestionDependencyOperator::CONTAINS, 'fattening_mortality_rate'],
            ['mortality_rules.rolling_count_detection_enabled', 'mortality_rules.rolling_count_threshold', QuestionDependencyOperator::EQUALS, '1'],
            ['mortality_rules.rolling_count_detection_enabled', 'mortality_rules.rolling_count_window_days', QuestionDependencyOperator::EQUALS, '1'],
            ['mortality_rules.cluster_detection_enabled', 'mortality_rules.cluster_dimensions', QuestionDependencyOperator::EQUALS, '1'],
            ['mortality_rules.cluster_detection_enabled', 'mortality_rules.cluster_case_threshold', QuestionDependencyOperator::EQUALS, '1'],
            ['mortality_rules.cluster_detection_enabled', 'mortality_rules.cluster_window_days', QuestionDependencyOperator::EQUALS, '1'],
            ['abortion_rules.rest_policy', 'abortion_rules.rest_days', QuestionDependencyOperator::EQUALS, 'fixed_rest_days_then_reassess'],
            ['abortion_rules.recurrence_threshold_enabled', 'abortion_rules.recurrence_count', QuestionDependencyOperator::EQUALS, '1'],
        ];

        foreach ($dependencies as [$parentSeedKey, $childSeedKey, $operator, $value]) {
            $parent = $questions->get($parentSeedKey);
            $child = $questions->get($childSeedKey);

            if (! $parent || ! $child) {
                continue;
            }

            $child->forceFill([
                'depends_on_question_id' => $parent->id,
                'dependency_operator' => $operator,
                'dependency_value' => $value,
            ])->save();
        }
    }

    private function resolveSection(): ?QuestionnaireSection
    {
        $mainSection = QuestionnaireSection::query()
            ->whereNull('parent_id')
            ->where('name', 'الإعدادات وقواعد التشغيل')
            ->first();

        if (! $mainSection) {
            return null;
        }

        return QuestionnaireSection::query()
            ->where('parent_id', $mainSection->id)
            ->where('name', 'قواعد الصحة والعزل والنفوق والحالات الاستثنائية')
            ->first();
    }
}
