<?php

namespace Database\Seeders\Questions\Settings;

use App\Enums\Questionnaire\QuestionDependencyOperator;
use App\Enums\Questionnaire\QuestionType;
use App\Models\QuestionnaireQuestion;
use App\Models\QuestionnaireSection;
use App\Services\Questionnaire\QuestionSeederSyncService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FatteningSaleReadinessRulesQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $questions = [
                [
                    'seed_key' => 'fattening_rules.program_parameters',
                    'title' => 'ما القيم والأهداف التي يجب أن تكون قابلة للضبط داخل برنامج التسمين؟',
                    'help_text' => 'المصدر يذكر العمر المستهدف، وزن البيع المستهدف، الحد الأدنى المقبول للوزن، الحد الأقصى لمدة التسمين، معدل النمو المستهدف، حد انخفاض النمو، وموعد بدء الاستعداد للبيع. اختر فقط ما يستخدم فعليًا؛ لا يفرض النظام أرقامًا ثابتة.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 1,
                    'report_category' => 'fattening_program_rule',
                    'target_entity' => 'fattening_program',
                    'options' => [
                        ['label' => 'العمر المستهدف لنهاية التسمين', 'value' => 'target_end_age'],
                        ['label' => 'الوزن المستهدف للبيع', 'value' => 'target_sale_weight'],
                        ['label' => 'الحد الأدنى المقبول لوزن البيع', 'value' => 'minimum_sale_weight'],
                        ['label' => 'الحد الأقصى لمدة البقاء في التسمين', 'value' => 'maximum_fattening_duration'],
                        ['label' => 'معدل النمو المستهدف', 'value' => 'target_growth_rate'],
                        ['label' => 'حد انخفاض معدل النمو الذي يستوجب المراجعة', 'value' => 'low_growth_threshold'],
                        ['label' => 'عدد الأيام قبل الموعد المتوقع للبيع لبدء الاستعداد / التنبيه', 'value' => 'sale_preparation_lead_time'],
                    ],
                ],
                [
                    'seed_key' => 'fattening_rules.target_end_age_days',
                    'title' => 'ما العمر المستهدف لنهاية التسمين، بالأيام؟',
                    'help_text' => 'هذه قيمة مستهدفة وليست حدث خروج تلقائيًا. الوصول للعمر لا يخرج الحيوان من المزرعة ولا يغلق مسار التسمين تلقائيًا.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 2,
                    'report_category' => 'fattening_age_target',
                    'target_entity' => 'fattening_program',
                    'options' => [],
                ],
                [
                    'seed_key' => 'fattening_rules.target_sale_weight',
                    'title' => 'ما الوزن المستهدف للبيع؟',
                    'help_text' => 'استخدم نفس وحدة الوزن القياسية المعتمدة في Weight History. هذه قيمة مقارنة وتشغيل وليست قياس وزن فعلي.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 3,
                    'report_category' => 'sale_weight_target',
                    'target_entity' => 'sale_readiness',
                    'options' => [],
                ],
                [
                    'seed_key' => 'fattening_rules.minimum_sale_weight',
                    'title' => 'ما الحد الأدنى المقبول لوزن البيع؟',
                    'help_text' => 'المصدر يفرق بين الوزن المستهدف والحد الأدنى المقبول. هذه القيمة تستخدم للحكم على الحالات التي لم تصل للهدف الكامل ولا تمثل وزن خروج فعليًا.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 4,
                    'report_category' => 'sale_weight_threshold',
                    'target_entity' => 'sale_readiness',
                    'options' => [],
                ],
                [
                    'seed_key' => 'fattening_rules.maximum_duration_days',
                    'title' => 'ما الحد الأقصى لمدة البقاء في مسار التسمين، بالأيام؟',
                    'help_text' => 'تجاوز المدة يستوجب مراجعة حسب القاعدة، لكنه لا ينشئ بيعًا أو خروجًا تلقائيًا.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 5,
                    'report_category' => 'fattening_duration_threshold',
                    'target_entity' => 'fattening_program',
                    'options' => [],
                ],
                [
                    'seed_key' => 'fattening_rules.weight_schedule_source_model',
                    'title' => 'كيف يجب تحديد دورية الوزن أثناء التسمين دون إنشاء برنامج وزن متعارض مع 6.9؟',
                    'help_text' => 'الوزن المنفذ فعليًا يبقى دائمًا في 4.3. المطلوب هنا حسم هل التسمين يستمر على برنامج الوزن العام بعد الفطام أم يستخدم دورية مستقلة أثناء فترة التسمين.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 6,
                    'report_category' => 'fattening_weight_schedule_rule',
                    'target_entity' => 'fattening_weight_program',
                    'options' => [
                        ['label' => 'يستمر استخدام برنامج الوزن العام المعتمد في 6.9', 'value' => 'use_general_growth_weight_schedule'],
                        ['label' => 'يستخدم التسمين دورية وزن مستقلة قابلة للضبط', 'value' => 'dedicated_fattening_periodic_schedule'],
                        ['label' => 'يستخدم برنامج 6.9 مع إمكانية إضافة دورية خاصة بالتسمين عند الحاجة', 'value' => 'general_schedule_with_fattening_extension'],
                        ['label' => 'لا توجد دورية ثابتة أثناء التسمين؛ الوزن عند الحاجة فقط', 'value' => 'weight_when_needed_only'],
                    ],
                ],
                [
                    'seed_key' => 'fattening_rules.weight_interval_days',
                    'title' => 'كل كم يوم يستحق وزن الحيوان أثناء التسمين عند استخدام دورية مستقلة؟',
                    'help_text' => 'القيمة تحدد موعد الاستحقاق فقط. كل وزن فعلي يسجل في Weight History الموحد.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 7,
                    'report_category' => 'fattening_weight_interval',
                    'target_entity' => 'fattening_weight_program',
                    'options' => [],
                ],
                [
                    'seed_key' => 'fattening_rules.growth_rate_metric_model',
                    'title' => 'أي مؤشر مشتق من سجل الأوزان يجب اعتباره «معدل النمو» عند تطبيق هدف النمو في التسمين؟',
                    'help_text' => 'المصدر يستخدم مصطلح معدل النمو لكنه لا يحدد معادلته. المطلوب ربط الهدف بمؤشر واضح مشتق من Weight History بدل إدخال معدل يدوي مستقل.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 8,
                    'report_category' => 'growth_metric_rule',
                    'target_entity' => 'fattening_growth_rule',
                    'options' => [
                        ['label' => 'متوسط الزيادة اليومية المحسوب من قياسين موثقين', 'value' => 'average_daily_gain'],
                        ['label' => 'الزيادة منذ آخر وزن خلال الفترة الأخيرة', 'value' => 'gain_since_previous_weight'],
                        ['label' => 'نسبة / معدل النمو بين قياسين خلال الفترة', 'value' => 'relative_growth_rate_between_measurements'],
                        ['label' => 'استخدام نفس مؤشر النمو الأساسي المعتمد في 6.9', 'value' => 'use_primary_growth_metric_from_6_9'],
                    ],
                ],
                [
                    'seed_key' => 'fattening_rules.target_growth_rate',
                    'title' => 'ما معدل النمو المستهدف أثناء التسمين وفق المؤشر المختار؟',
                    'help_text' => 'الوحدة وطريقة قراءة القيمة تتبع مؤشر معدل النمو المختار. هذه Target للمقارنة وليست قيمة نمو تسجل يدويًا للحيوان.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 9,
                    'report_category' => 'growth_target',
                    'target_entity' => 'fattening_growth_rule',
                    'options' => [],
                ],
                [
                    'seed_key' => 'fattening_rules.low_growth_threshold_model',
                    'title' => 'كيف يجب تعريف حد انخفاض النمو الذي يجعل الحيوان بحاجة إلى مراجعة؟',
                    'help_text' => 'المصدر يذكر حد انخفاض معدل النمو لكنه لا يحدد طريقة التعبير عنه. إنشاء Alert فعلي ومستواه وأولويته يظل في 6.12.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 10,
                    'report_category' => 'growth_threshold_rule',
                    'target_entity' => 'fattening_growth_rule',
                    'options' => [
                        ['label' => 'لا يستخدم Threshold منفصل لانخفاض النمو', 'value' => 'no_low_growth_threshold'],
                        ['label' => 'حد أدنى مطلق لمؤشر معدل النمو', 'value' => 'absolute_minimum_growth_rate'],
                        ['label' => 'نسبة انخفاض مسموحة عن معدل النمو المستهدف', 'value' => 'allowed_percentage_below_target'],
                    ],
                ],
                [
                    'seed_key' => 'fattening_rules.minimum_growth_rate',
                    'title' => 'ما الحد الأدنى المطلق لمعدل النمو الذي إذا انخفض عنه الحيوان يحتاج إلى مراجعة؟',
                    'help_text' => 'تظهر هذه القيمة فقط عند استخدام حد مطلق لمعدل النمو.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 11,
                    'report_category' => 'growth_threshold',
                    'target_entity' => 'fattening_growth_rule',
                    'options' => [],
                ],
                [
                    'seed_key' => 'fattening_rules.allowed_growth_shortfall_percentage',
                    'title' => 'ما نسبة الانخفاض المسموحة عن معدل النمو المستهدف قبل اعتبار الحالة بحاجة إلى مراجعة؟',
                    'help_text' => 'تستخدم هذه القيمة فقط إذا كان حد الانخفاض معبرًا عنه كنسبة من الهدف.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 12,
                    'report_category' => 'growth_threshold',
                    'target_entity' => 'fattening_growth_rule',
                    'options' => [],
                ],
                [
                    'seed_key' => 'fattening_rules.sale_preparation_lead_days',
                    'title' => 'قبل الموعد المتوقع للبيع بكم يوم يبدأ الاستعداد / التنبيه؟',
                    'help_text' => 'هذه Lead Time للتشغيل. إنشاء المهمة أو التنبيه الناتج عنها وتحديد أولويته يعالج في 6.12.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 13,
                    'report_category' => 'sale_preparation_lead_time',
                    'target_entity' => 'sale_readiness',
                    'options' => [],
                ],
                [
                    'seed_key' => 'fattening_rules.readiness_required_factors',
                    'title' => 'ما العوامل التي يجب أن تدخل في تقييم جاهزية الحيوان للبيع؟',
                    'help_text' => 'المصدر ينص على أن الجاهزية لا تعتمد على العمر وحده، ويذكر العمر والوزن ومعدل النمو والحالة الصحية ومدة التسمين. الجاهزية الناتجة لا تنفذ البيع تلقائيًا.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 14,
                    'report_category' => 'sale_readiness_rule',
                    'target_entity' => 'sale_readiness',
                    'options' => [
                        ['label' => 'العمر الحالي مقابل العمر المستهدف', 'value' => 'age_vs_target'],
                        ['label' => 'الوزن الحالي مقابل الوزن المستهدف', 'value' => 'weight_vs_target'],
                        ['label' => 'الوزن الحالي مقابل الحد الأدنى المقبول للبيع', 'value' => 'weight_vs_minimum_sale_weight'],
                        ['label' => 'معدل النمو الحالي مقابل الهدف / الحد المعتمد', 'value' => 'growth_rate_context'],
                        ['label' => 'الحالة الصحية الحالية تسمح بالبيع / الخروج', 'value' => 'health_allows_sale'],
                        ['label' => 'مدة البقاء في التسمين مقابل الحد أو المدة المستهدفة', 'value' => 'fattening_duration_context'],
                    ],
                ],
                [
                    'seed_key' => 'fattening_rules.primary_goal_model',
                    'title' => 'ما الأساس الرئيسي للوصول إلى هدف التسمين عندما تستخدم المزرعة العمر والوزن معًا؟',
                    'help_text' => 'المصدر يضع صراحة سؤالًا مفتوحًا: هل الهدف الأساسي عمر معين أم وزن معين أم الاثنين؟ المطلوب حسم منطق التقييم وليس تثبيت رقم.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 15,
                    'report_category' => 'sale_readiness_rule',
                    'target_entity' => 'sale_readiness',
                    'options' => [
                        ['label' => 'الوزن هو الهدف الأساسي، والعمر / المدة سياق للمراجعة', 'value' => 'weight_primary_age_context'],
                        ['label' => 'العمر هو الهدف الأساسي، والوزن يستخدم كشرط أو سياق إضافي', 'value' => 'age_primary_weight_context'],
                        ['label' => 'يجب استيفاء العمر والوزن معًا للوصول إلى الهدف العادي', 'value' => 'require_age_and_weight_together'],
                        ['label' => 'التقييم متعدد العوامل ويعتمد على جميع شروط الجاهزية المختارة لا على عمر أو وزن منفرد', 'value' => 'multi_factor_readiness_model'],
                    ],
                ],
                [
                    'seed_key' => 'fattening_rules.minimum_vs_target_weight_role',
                    'title' => 'كيف يجب استخدام الحد الأدنى المقبول لوزن البيع مقارنة بالوزن المستهدف؟',
                    'help_text' => 'المصدر يذكر القيمتين معًا، لذلك نحتاج منع الغموض بين «الهدف المثالي» و«أقل وزن يمكن قبوله عند اتخاذ قرار بيع بالوضع الحالي».',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 16,
                    'report_category' => 'sale_weight_rule',
                    'target_entity' => 'sale_readiness',
                    'options' => [
                        ['label' => 'الوزن المستهدف يحقق الجاهزية العادية، والحد الأدنى يسمح بقرار بيع استثنائي / مراجعة موثقة فقط', 'value' => 'target_for_normal_readiness_minimum_for_reviewed_sale'],
                        ['label' => 'الوصول للحد الأدنى يكفي لاعتبار الحيوان جاهزًا، والوزن المستهدف هدف أداء فقط', 'value' => 'minimum_defines_readiness_target_is_performance_goal'],
                        ['label' => 'يجب الوصول للوزن المستهدف دائمًا؛ الحد الأدنى يستخدم للمعلومات والتحليل فقط', 'value' => 'target_required_minimum_is_advisory'],
                    ],
                ],
                [
                    'seed_key' => 'fattening_rules.target_miss_review_triggers',
                    'title' => 'ما الحالات التي يجب أن تجعل الحيوان بحاجة إلى مراجعة تشغيلية داخل مسار التسمين؟',
                    'help_text' => 'المصدر يذكر تأخر الوصول للوزن، انخفاض معدل النمو، وتجاوز العمر أو مدة التسمين. المراجعة الفعلية ونتيجتها محفوظتان في 4.12.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 17,
                    'report_category' => 'fattening_review_rule',
                    'target_entity' => 'fattening_review',
                    'options' => [
                        ['label' => 'بلوغ العمر المستهدف دون الوصول للوزن المطلوب', 'value' => 'target_age_reached_weight_not_met'],
                        ['label' => 'تجاوز الحد الأقصى لمدة التسمين', 'value' => 'maximum_duration_exceeded'],
                        ['label' => 'انخفاض معدل النمو عن الحد المعتمد', 'value' => 'growth_below_threshold'],
                        ['label' => 'وجود حالة صحية تؤثر على استمرار التسمين أو الجاهزية للبيع', 'value' => 'health_condition_affects_path'],
                    ],
                ],
                [
                    'seed_key' => 'fattening_rules.target_miss_handling_model',
                    'title' => 'عند عدم الوصول للهدف أو تجاوز المدة، كيف يجب أن تتعامل القاعدة قبل أي قرار بيع أو استبعاد؟',
                    'help_text' => 'المصدر يؤكد أن الحيوان لا يخرج تلقائيًا؛ يستمر تسجيله حتى يراجع المسؤول حالته ويختار الإجراء المناسب. هذا السؤال يحدد درجة إلزام المراجعة فقط، بينما نتيجة المراجعة نفسها تسجل في 4.12.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 18,
                    'report_category' => 'fattening_review_rule',
                    'target_entity' => 'fattening_review',
                    'options' => [
                        ['label' => 'تتحول الحالة إلى «تحتاج مراجعة» ويجب تسجيل مراجعة قبل اعتماد المسار التالي', 'value' => 'require_review_before_next_decision'],
                        ['label' => 'تعرض Warning ويستطيع المسؤول مباشرة تنفيذ المسار التالي وفق صلاحياته', 'value' => 'warning_then_allow_authorized_next_action'],
                        ['label' => 'تطبق طبيعة Information / Warning / Block العامة من 6.2 حسب نوع الانحراف', 'value' => 'use_general_enforcement_policy_by_deviation_type'],
                    ],
                ],
                [
                    'seed_key' => 'fattening_rules.below_minimum_weight_sale_policy',
                    'title' => 'إذا قرر المسؤول البيع بالوزن الحالي وكان الوزن أقل من الحد الأدنى المقبول، كيف يجب أن تتصرف القاعدة؟',
                    'help_text' => 'المصدر يسمح بأن تكون نتيجة المراجعة «البيع بالوزن الحالي»، وفي الوقت نفسه يقترح حدًا أدنى مقبولًا لوزن البيع. المطلوب حسم العلاقة بين القرارين دون تنفيذ البيع داخل Settings.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 19,
                    'report_category' => 'sale_weight_enforcement_rule',
                    'target_entity' => 'sale_readiness',
                    'options' => [
                        ['label' => 'Hard Constraint: لا يسمح باعتماد توجه البيع تحت الحد الأدنى', 'value' => 'block_below_minimum_no_override'],
                        ['label' => 'يمكن كاستثناء محكوم وفق 6.2 مع سبب وOverride موثق', 'value' => 'governed_override_below_minimum'],
                        ['label' => 'الحد الأدنى إرشادي فقط ويعرض Warning دون منع', 'value' => 'minimum_is_advisory_warning_only'],
                    ],
                ],
                [
                    'seed_key' => 'fattening_rules.maximum_duration_exceeded_policy',
                    'title' => 'إذا تجاوز الحيوان الحد الأقصى لمدة التسمين وما زال داخل المزرعة، هل يجب أن يبقى مسار التسمين مفتوحًا حتى وقوع قرار أو حدث فعلي؟',
                    'help_text' => 'تجاوز المدة لا يعني بيعًا ولا خروجًا. 4.12 يحتفظ بالمسار حتى حدث نهائي فعلي أو انتقال معتمد لمسار آخر؛ هذا السؤال يحسم ما إذا كانت القاعدة تسمح بالاستمرار بعد المراجعة أو تعتبر الحد Hard Constraint.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 20,
                    'report_category' => 'fattening_duration_rule',
                    'target_entity' => 'fattening_program',
                    'options' => [
                        ['label' => 'يسمح بالاستمرار بعد مراجعة موثقة حتى اتخاذ قرار فعلي', 'value' => 'allow_continuation_after_documented_review'],
                        ['label' => 'التجاوز مسموح وفق Warning / Override Policy في 6.2 مع توثيق السبب', 'value' => 'continuation_requires_governed_exception'],
                        ['label' => 'Hard Constraint: يجب إنهاء أو تحويل المسار عند بلوغ الحد ولا يسمح باستمرار التسمين بعده', 'value' => 'hard_maximum_duration_no_continuation'],
                    ],
                ],
            ];

            app(QuestionSeederSyncService::class)->sync(
                mainSectionName: 'الإعدادات وقواعد التشغيل',
                sectionName: 'قواعد التسمين والجاهزية للبيع',
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
            ['fattening_rules.program_parameters', 'fattening_rules.target_end_age_days', QuestionDependencyOperator::CONTAINS, 'target_end_age'],
            ['fattening_rules.program_parameters', 'fattening_rules.target_sale_weight', QuestionDependencyOperator::CONTAINS, 'target_sale_weight'],
            ['fattening_rules.program_parameters', 'fattening_rules.minimum_sale_weight', QuestionDependencyOperator::CONTAINS, 'minimum_sale_weight'],
            ['fattening_rules.program_parameters', 'fattening_rules.maximum_duration_days', QuestionDependencyOperator::CONTAINS, 'maximum_fattening_duration'],
            ['fattening_rules.weight_schedule_source_model', 'fattening_rules.weight_interval_days', QuestionDependencyOperator::EQUALS, 'dedicated_fattening_periodic_schedule'],
            ['fattening_rules.program_parameters', 'fattening_rules.target_growth_rate', QuestionDependencyOperator::CONTAINS, 'target_growth_rate'],
            ['fattening_rules.low_growth_threshold_model', 'fattening_rules.minimum_growth_rate', QuestionDependencyOperator::EQUALS, 'absolute_minimum_growth_rate'],
            ['fattening_rules.low_growth_threshold_model', 'fattening_rules.allowed_growth_shortfall_percentage', QuestionDependencyOperator::EQUALS, 'allowed_percentage_below_target'],
            ['fattening_rules.program_parameters', 'fattening_rules.sale_preparation_lead_days', QuestionDependencyOperator::CONTAINS, 'sale_preparation_lead_time'],
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
            ->where('name', 'قواعد التسمين والجاهزية للبيع')
            ->first();
    }
}
