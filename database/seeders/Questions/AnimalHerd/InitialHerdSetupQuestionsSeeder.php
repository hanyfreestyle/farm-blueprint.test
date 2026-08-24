<?php

namespace Database\Seeders\Questions\AnimalHerd;

use App\Enums\Questionnaire\QuestionDependencyOperator;
use App\Enums\Questionnaire\QuestionType;
use App\Models\QuestionnaireQuestion;
use App\Models\QuestionnaireSection;
use App\Services\Questionnaire\QuestionSeederSyncService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InitialHerdSetupQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $questions = [
                [
                    'seed_key' => 'animal.opening_snapshot_operational_fields',
                    'title' => 'ما المعلومات التشغيلية الحالية التي يجب أن يستطيع تسجيل القطيع الافتتاحي إثباتها كنقطة بداية للحيوان؟',
                    'help_text' => 'بيانات الهوية والسلالة والنسب معرفة في أقسامها السابقة. المطلوب هنا تحديد المعلومات الحالية التي تصف وضع الحيوان لحظة بدء استخدام النظام دون اعتبارها حقولًا ثابتة تعدل لاحقًا يدويًا.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 1,
                    'report_category' => 'field',
                    'target_entity' => 'opening_herd_snapshot',
                    'options' => [
                        ['label' => 'الوزن الحالي إن كان معروفًا', 'value' => 'current_weight'],
                        ['label' => 'موقع / قفص التسكين الحالي', 'value' => 'current_housing'],
                        ['label' => 'الحالة الصحية الحالية', 'value' => 'current_health_state'],
                        ['label' => 'المسار أو المرحلة التشغيلية الحالية', 'value' => 'current_operational_stage'],
                        ['label' => 'السياق التناسلي الحالي للأنثى عند انطباقه', 'value' => 'current_reproductive_context'],
                    ],
                ],
                [
                    'seed_key' => 'animal.opening_missing_data_policy',
                    'title' => 'كيف يجب التعامل مع البيانات غير المتاحة عند إدخال حيوان ضمن القطيع الافتتاحي؟',
                    'help_text' => 'المزرعة القائمة قد لا تملك كل البيانات التاريخية أو الحالية لكل حيوان. المطلوب تحديد مستوى السماح بالنقص دون اختراع معلومات غير معروفة.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 2,
                    'report_category' => 'data_quality_rule',
                    'target_entity' => 'opening_herd_setup',
                    'options' => [
                        ['label' => 'يسمح بإدخال الحيوان مع تمييز البيانات الناقصة، وتستكمل قبل العملية التي تحتاجها', 'value' => 'allow_incomplete_flag_and_require_when_needed'],
                        ['label' => 'يسمح بالنقص غير الحرج فقط، ويجب استكمال حد أدنى قبل اعتماد القطيع الافتتاحي', 'value' => 'minimum_required_before_activation'],
                        ['label' => 'لا يعتمد الحيوان ضمن القطيع الافتتاحي حتى تكتمل كل المعلومات التشغيلية المختارة', 'value' => 'require_all_selected_before_activation'],
                    ],
                ],
                [
                    'seed_key' => 'animal.opening_starting_contexts',
                    'title' => 'ما الأوضاع التي يجب أن يستطيع الحيوان أن يبدأ منها عند تسجيل القطيع الافتتاحي؟',
                    'help_text' => 'الهدف ألا يجبر النظام المزرعة القائمة على بدء كل الحيوانات من أول دورة جديدة. هذه أوضاع بداية يجب تمثيلها، وليست Status واحدة ثابتة للحيوان.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 3,
                    'report_category' => 'state_model_rule',
                    'target_entity' => 'opening_herd_snapshot',
                    'options' => [
                        ['label' => 'ذكر ضمن القطيع الإنتاجي', 'value' => 'production_male'],
                        ['label' => 'أنثى إنتاج ليست داخل دورة تناسلية نشطة حاليًا', 'value' => 'production_female_no_active_cycle'],
                        ['label' => 'أنثى داخل دورة تناسلية نشطة بالفعل', 'value' => 'female_active_reproductive_cycle'],
                        ['label' => 'مرشح / مرشحة للإحلال', 'value' => 'replacement_candidate'],
                        ['label' => 'حيوان في مرحلة النمو', 'value' => 'growing'],
                        ['label' => 'حيوان في مسار التسمين', 'value' => 'fattening'],
                        ['label' => 'حيوان تحت الملاحظة الصحية أو العزل', 'value' => 'health_observation_or_isolation'],
                    ],
                ],
                [
                    'seed_key' => 'animal.opening_reproductive_contexts',
                    'title' => 'ما نقاط البداية التناسلية التي يجب دعمها للأنثى الموجودة بالفعل داخل دورة إنتاج عند بدء النظام؟',
                    'help_text' => 'يمكن أن تبدأ الأنثى في منتصف دورة قائمة. المطلوب تحديد الأوضاع التي يجب تمثيلها دون إجبارها على إعادة دورة التلقيح من البداية.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 4,
                    'report_category' => 'state_model_rule',
                    'target_entity' => 'opening_reproductive_context',
                    'options' => [
                        ['label' => 'تم تلقيحها وتنتظر فحص الحمل', 'value' => 'mated_awaiting_pregnancy_check'],
                        ['label' => 'حمل مؤكد', 'value' => 'confirmed_pregnant'],
                        ['label' => 'حامل وقريبة من الولادة', 'value' => 'pregnant_near_kindling'],
                        ['label' => 'مرضعة حاليًا', 'value' => 'lactating'],
                        ['label' => 'مرضعة وتم تلقيحها مرة أخرى', 'value' => 'lactating_and_remated'],
                    ],
                ],
                [
                    'seed_key' => 'animal.opening_trusted_prior_history_strategy',
                    'title' => 'كيف يجب التعامل مع معلومات تاريخية موثوقة ترجع إلى ما قبل تاريخ بدء استخدام النظام؟',
                    'help_text' => 'لا يتم اختراع أحداث مفقودة. المطلوب تحديد هل يكتفي النظام بلقطة الوضع الحالي، أم يسمح بإضافة الحقائق السابقة المعروفة مع تمييزها بوضوح كبيانات سابقة لبدء النظام.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 5,
                    'report_category' => 'history_rule',
                    'target_entity' => 'animal_history',
                    'options' => [
                        ['label' => 'تسجيل الوضع الحالي فقط دون إنشاء تاريخ سابق', 'value' => 'current_snapshot_only'],
                        ['label' => 'تسجيل الوضع الحالي مع السماح بإضافة الحقائق السابقة الموثوقة وتمييزها كبيانات قبل بدء النظام', 'value' => 'snapshot_plus_trusted_prior_facts'],
                    ],
                ],
                [
                    'seed_key' => 'animal.opening_trusted_prior_fact_types',
                    'title' => 'ما أنواع الحقائق السابقة الموثوقة التي يجب أن يسمح النظام بإضافتها عند توافرها؟',
                    'help_text' => 'هذا لا يعني أن هذه البيانات مطلوبة لكل حيوان؛ بل يحدد ما الذي يمكن الاحتفاظ به إذا كانت المزرعة تعرفه بصورة موثوقة عند التهيئة.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 6,
                    'report_category' => 'history_scope',
                    'target_entity' => 'animal_history',
                    'options' => [
                        ['label' => 'تواريخ أو أحداث تناسلية سابقة معروفة مثل آخر تلقيح', 'value' => 'known_reproductive_dates_or_events'],
                        ['label' => 'عدد الولادات السابقة المعروف', 'value' => 'known_previous_birth_count'],
                        ['label' => 'بيانات الأبناء السابقين المعروفة', 'value' => 'known_previous_offspring'],
                        ['label' => 'أوزان تاريخية موثوقة', 'value' => 'known_historical_weights'],
                    ],
                ],
                [
                    'seed_key' => 'animal.opening_history_completeness_tracking',
                    'title' => 'كيف يجب إظهار أن تاريخ الحيوان قبل بدء النظام غير مكتمل؟',
                    'help_text' => 'الهدف منع تفسير غياب البيانات القديمة على أنه عدم حدوثها، والحفاظ على الفرق بين «غير معروف» و«لم يحدث».',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 7,
                    'report_category' => 'data_quality_rule',
                    'target_entity' => 'animal_history',
                    'options' => [
                        ['label' => 'علامة عامة على سجل الحيوان توضح أن التاريخ قبل بدء النظام غير مكتمل', 'value' => 'record_level_incomplete_before_start'],
                        ['label' => 'تتبع حالة المعرفة / المصدر لكل معلومة تاريخية عند الحاجة', 'value' => 'per_fact_completeness_and_provenance'],
                        ['label' => 'استخدام العلامة العامة مع تتبع التفاصيل لكل معلومة عند توفرها', 'value' => 'both_levels'],
                    ],
                ],
                [
                    'seed_key' => 'animal.opening_pre_activation_checks',
                    'title' => 'ما المراجعات التي يجب تنفيذها قبل اعتماد القطيع الافتتاحي وبدء التشغيل الفعلي؟',
                    'help_text' => 'هذه المراجعات تستخدم قواعد الهيكل والتسكين والتنظيم المعتمدة في أقسامها ولا تعيد تعريف حدود السعة أو الجاهزية هنا. الهدف تحديد ما الذي يجب فحصه عند نقطة التهيئة.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 8,
                    'report_category' => 'validation_rule',
                    'target_entity' => 'opening_herd_setup',
                    'options' => [
                        ['label' => 'كل حيوان لديه موقع تسكين صالح عند اشتراط ذلك', 'value' => 'animal_has_valid_housing'],
                        ['label' => 'عدم وجود الحيوان في أكثر من موقع في الوقت نفسه', 'value' => 'no_multiple_current_locations'],
                        ['label' => 'عدم تجاوز سعة مواقع الإيواء', 'value' => 'housing_capacity_not_exceeded'],
                        ['label' => 'عدم التسكين في موقع متوقف أو غير متاح تشغيليًا', 'value' => 'housing_operationally_available'],
                        ['label' => 'توافق استخدام موقع الإيواء مع الحيوان وفق القواعد المعتمدة', 'value' => 'housing_usage_compatible'],
                        ['label' => 'الحيوانات المعزولة أو تحت الملاحظة في المواقع المناسبة لها', 'value' => 'isolation_housing_valid'],
                        ['label' => 'اكتمال تنظيم المجموعات والتخصيصات المطلوبة قبل التشغيل', 'value' => 'required_grouping_complete'],
                        ['label' => 'عدم وجود نقص بيانات حرج يمنع العمليات الأولى المطلوبة', 'value' => 'no_critical_missing_data'],
                    ],
                ],
                [
                    'seed_key' => 'animal.opening_activation_model',
                    'title' => 'كيف تنتقل عملية تسجيل القطيع الافتتاحي من الإدخال إلى بدء التشغيل الفعلي؟',
                    'help_text' => 'وجود مرحلة مراجعة يسمح بتصحيح تعارضات التسكين والبيانات قبل اعتبار القطيع نقطة البداية الرسمية للمزرعة.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 9,
                    'report_category' => 'initialization_rule',
                    'target_entity' => 'opening_herd_setup',
                    'options' => [
                        ['label' => 'إدخال كمسودة ثم مراجعة ثم اعتماد / تفعيل نقطة البداية', 'value' => 'draft_review_activate'],
                        ['label' => 'يصبح كل سجل فعالًا فور إدخاله دون مرحلة اعتماد افتتاحية مستقلة', 'value' => 'immediate_activation'],
                    ],
                ],
                [
                    'seed_key' => 'animal.opening_baseline_snapshot',
                    'title' => 'هل يجب حفظ رصيد القطيع عند اعتماد التهيئة كخط أساس تاريخي يمكن الرجوع إليه لاحقًا؟',
                    'help_text' => 'المقصود الاحتفاظ بصورة نقطة البداية التشغيلية، مثل أعداد الذكور والإناث والحوامل والمرضعات والفئات الأخرى، دون تحويل الأعداد إلى حقول يدوية ثابتة؛ فهي تحسب من السجلات عند الاعتماد.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 10,
                    'report_category' => 'reporting_baseline_rule',
                    'target_entity' => 'opening_herd_baseline',
                    'options' => [],
                ],
                [
                    'seed_key' => 'animal.opening_task_evaluation_after_activation',
                    'title' => 'بعد اعتماد القطيع الافتتاحي، هل يجب تشغيل تقييم المهام تلقائيًا اعتمادًا على الوضع الحالي المسجل؟',
                    'help_text' => 'مثال: أنثى تم تلقيحها سابقًا قد تحتاج مهمة فحص حمل، أو أنثى قريبة من الولادة قد تحتاج مهمة تجهيز. قواعد التوقيت والأولوية نفسها تحسم لاحقًا في Settings؛ هذا السؤال يحدد فقط هل يبدأ التقييم من نقطة التهيئة.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 11,
                    'report_category' => 'integration_rule',
                    'target_entity' => 'task_engine',
                    'options' => [],
                ],
            ];

            app(QuestionSeederSyncService::class)->sync(
                mainSectionName: 'بيانات الحيوان وتكوين القطيع',
                sectionName: 'القطيع الافتتاحي وتهيئة نقطة البداية',
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

        $startingContexts = $questions->get('animal.opening_starting_contexts');
        $reproductiveContexts = $questions->get('animal.opening_reproductive_contexts');

        if ($startingContexts && $reproductiveContexts) {
            $reproductiveContexts->forceFill([
                'depends_on_question_id' => $startingContexts->id,
                'dependency_operator' => QuestionDependencyOperator::CONTAINS,
                'dependency_value' => 'female_active_reproductive_cycle',
            ])->save();
        }

        $priorHistoryStrategy = $questions->get('animal.opening_trusted_prior_history_strategy');
        $priorFactTypes = $questions->get('animal.opening_trusted_prior_fact_types');

        if ($priorHistoryStrategy && $priorFactTypes) {
            $priorFactTypes->forceFill([
                'depends_on_question_id' => $priorHistoryStrategy->id,
                'dependency_operator' => QuestionDependencyOperator::EQUALS,
                'dependency_value' => 'snapshot_plus_trusted_prior_facts',
            ])->save();
        }
    }

    private function resolveSection(): ?QuestionnaireSection
    {
        $mainSection = QuestionnaireSection::query()
            ->whereNull('parent_id')
            ->where('name', 'بيانات الحيوان وتكوين القطيع')
            ->first();

        if (! $mainSection) {
            return null;
        }

        return QuestionnaireSection::query()
            ->where('parent_id', $mainSection->id)
            ->where('name', 'القطيع الافتتاحي وتهيئة نقطة البداية')
            ->first();
    }
}
