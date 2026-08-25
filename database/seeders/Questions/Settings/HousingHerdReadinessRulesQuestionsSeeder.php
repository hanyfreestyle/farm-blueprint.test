<?php

namespace Database\Seeders\Questions\Settings;

use App\Enums\Questionnaire\QuestionDependencyOperator;
use App\Enums\Questionnaire\QuestionType;
use App\Models\QuestionnaireQuestion;
use App\Models\QuestionnaireSection;
use App\Services\Questionnaire\QuestionSeederSyncService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HousingHerdReadinessRulesQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $questions = [
                [
                    'seed_key' => 'housing_herd_rules.housing_eligibility_factors',
                    'title' => 'ما العوامل التي يجب أن تدخل في قرار هل موقع الإيواء مؤهل لاستقبال حيوان معين الآن؟',
                    'help_text' => '6.3 يحسم هل الموقع نفسه متاح تشغيليًا، بينما هذا السؤال يضيف توافق الحيوان مع الموقع. حركة التسكين الفعلية تظل في 4.2، ولا يخزن النظام قرار الإتاحة كقيمة يدوية مستقلة إذا كان يمكن اشتقاقه من القواعد والسجلات.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 1,
                    'report_category' => 'housing_rule',
                    'target_entity' => 'housing_eligibility',
                    'options' => [
                        ['label' => 'الموقع متاح تشغيليًا وفق قواعد 6.3', 'value' => 'site_operationally_available'],
                        ['label' => 'توجد سعة متاحة وفق السعة الفعالة للموقع', 'value' => 'capacity_available'],
                        ['label' => 'الاستخدام التشغيلي للموقع متوافق مع فئة / مرحلة الحيوان', 'value' => 'usage_compatible_with_animal'],
                        ['label' => 'قواعد الجمع أو الفصل تسمح بوضع الحيوان مع الشاغلين الحاليين', 'value' => 'cohousing_rules_allow_combination'],
                        ['label' => 'الحالة الصحية / العزل لا تمنع هذا النوع من التسكين', 'value' => 'health_and_isolation_allow_housing'],
                        ['label' => 'وضع الحيوان الحالي يسمح بالتسكين أو النقل المقترح', 'value' => 'animal_current_context_allows_housing'],
                    ],
                ],
                [
                    'seed_key' => 'housing_herd_rules.usage_compatibility_model',
                    'title' => 'كيف يجب تعريف توافق استخدام موقع الإيواء مع فئات الحيوانات والمراحل التشغيلية؟',
                    'help_text' => 'المصدر يذكر أن أنثى الإنتاج يجب أن تذهب إلى قفص مناسب، وأن أقفاص الفطام والتسمين لها استخدامات مختلفة. قائمة استخدامات الأقفاص نفسها موجودة في Master Data؛ المطلوب هنا فقط قاعدة التوافق.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 2,
                    'report_category' => 'compatibility_rule',
                    'target_entity' => 'housing_usage_compatibility',
                    'options' => [
                        ['label' => 'خريطة صريحة تحدد لكل استخدام تشغيلي فئات / مراحل الحيوانات المسموح بها', 'value' => 'explicit_usage_to_animal_category_matrix'],
                        ['label' => 'تحدد القاعدة حسب المرحلة الإنتاجية فقط، ويكون استخدام القفص عاملًا مساعدًا', 'value' => 'stage_based_compatibility'],
                        ['label' => 'الاستخدام وصفي فقط ولا يمنع أو يقيد التسكين', 'value' => 'usage_is_advisory_only'],
                    ],
                ],
                [
                    'seed_key' => 'housing_herd_rules.capacity_limit_policy',
                    'title' => 'كيف يجب التعامل مع السعة القصوى عند محاولة تسكين حيوان جديد؟',
                    'help_text' => 'الإشغال الحالي مشتق من حركات 4.2 والسعة من بنية الموقع وقواعدها. هذا السؤال يحدد طبيعة حد السعة نفسه؛ سياسة الـOverride العامة وتوثيق التجاوز موجودان في 6.2.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 3,
                    'report_category' => 'capacity_rule',
                    'target_entity' => 'housing_capacity_rule',
                    'options' => [
                        ['label' => 'Hard Constraint: لا يسمح مطلقًا بتجاوز السعة الفعالة', 'value' => 'hard_capacity_limit_no_override'],
                        ['label' => 'قاعدة تشغيلية يمكن تصنيفها Warning / Block وفق سياسة 6.2 مع Override موثق عند السماح', 'value' => 'controlled_capacity_rule_with_override_policy'],
                        ['label' => 'تختلف طبيعة حد السعة حسب استخدام / نوع الموقع وفق تعريف القاعدة', 'value' => 'capacity_policy_by_site_usage_or_type'],
                    ],
                ],
                [
                    'seed_key' => 'housing_herd_rules.cohousing_dimensions',
                    'title' => 'ما الخصائص التي يجب أن تدخل في قواعد السماح بجمع أكثر من حيوان داخل نفس موقع الإيواء؟',
                    'help_text' => 'المرجع يترك قواعد الجمع بين الأرانب كنقطة تحتاج حسمًا، خصوصًا في الفطام والتسمين. السعة وحدها لا تكفي إذا كانت هناك قواعد فصل أو توافق بين الحيوانات.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 4,
                    'report_category' => 'cohousing_rule',
                    'target_entity' => 'animal_cohousing',
                    'options' => [
                        ['label' => 'الجنس', 'value' => 'sex'],
                        ['label' => 'المرحلة الإنتاجية / التشغيلية', 'value' => 'production_stage'],
                        ['label' => 'العمر أو التقارب العمري عند الحاجة', 'value' => 'age_or_age_band'],
                        ['label' => 'الحجم / الوزن أو التقارب بين الأحجام عند الحاجة', 'value' => 'size_or_weight_band'],
                        ['label' => 'المسار أو الغرض التشغيلي مثل فطام / نمو / تسمين', 'value' => 'operational_path'],
                        ['label' => 'الحالة الصحية أو متطلبات العزل', 'value' => 'health_or_isolation_context'],
                    ],
                ],
                [
                    'seed_key' => 'housing_herd_rules.sex_separation_model',
                    'title' => 'بعد الوصول إلى نقطة الفصل بين الجنسين المعتمدة، كيف يجب أن تؤثر قاعدة الجنس على التسكين الجماعي؟',
                    'help_text' => 'موعد أو عمر بدء الفصل يحسم في 6.8 ضمن قواعد الفطام والانتقال للتتبع الفردي. هذا السؤال يحسم فقط أثر القاعدة على صلاحية الجمع بعد أن يصبح الفصل مطلوبًا.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 5,
                    'report_category' => 'cohousing_rule',
                    'target_entity' => 'sex_separation_housing_rule',
                    'options' => [
                        ['label' => 'يمنع الجمع المختلط بعد نقطة الفصل المعتمدة', 'value' => 'mixed_sex_housing_not_allowed_after_trigger'],
                        ['label' => 'يسمح فقط في حالات استثنائية تخضع لسياسة 6.2', 'value' => 'mixed_sex_housing_exception_only_after_trigger'],
                        ['label' => 'لا توجد قاعدة فصل حسب الجنس على مستوى التسكين', 'value' => 'no_sex_based_housing_restriction'],
                    ],
                ],
                [
                    'seed_key' => 'housing_herd_rules.batch_housing_validation',
                    'title' => 'عند تنفيذ تسكين أو نقل جماعي، هل يجب فحص أهلية كل حيوان والسعة الناتجة للموقع قبل تنفيذ العملية؟',
                    'help_text' => '4.2 يدعم العملية الجماعية مع سجل فردي لكل حيوان. فحص القواعد قبل التنفيذ يمنع نجاح جزء من العملية وترك الباقي في حالة غير متسقة دون قرار واضح.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 6,
                    'report_category' => 'validation_rule',
                    'target_entity' => 'housing_batch_operation',
                    'options' => [],
                ],
                [
                    'seed_key' => 'housing_herd_rules.no_eligible_site_handling',
                    'title' => 'إذا لم يوجد موقع إيواء مؤهل أو سعة مناسبة للحيوان المطلوب تسكينه، كيف يجب أن يتصرف النظام؟',
                    'help_text' => 'المصدر يذكر صراحة حالة عدم وجود قفص مناسب وعجز سعة أقفاص الفطام أو التسمين. المطلوب منع إنشاء تسكين غير منطقي، مع ترك آلية التنبيه نفسها لقواعد 6.12.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 7,
                    'report_category' => 'exception_rule',
                    'target_entity' => 'housing_eligibility',
                    'options' => [
                        ['label' => 'يمنع تنفيذ التسكين حتى يتوفر موقع مؤهل', 'value' => 'block_until_eligible_site_exists'],
                        ['label' => 'يسجل احتياج / طلب تسكين معلق دون تغيير الموقع الحالي حتى يتوفر مكان', 'value' => 'create_pending_housing_need'],
                        ['label' => 'يمكن تجاوز بعض قواعد الأهلية فقط وفق Override Policy في 6.2، مع بقاء القيود الصلبة غير قابلة للتجاوز', 'value' => 'allow_governed_override_for_soft_rules_only'],
                    ],
                ],
                [
                    'seed_key' => 'production_group_rules.target_female_count_enabled',
                    'title' => 'هل تحتاج المزرعة إلى عدد مستهدف من الإناث لكل ذكر ضمن تنظيم المجموعات الإنتاجية؟',
                    'help_text' => 'المصدر يذكر أن النسبة مثل 1:3 ليست رقمًا ثابتًا في النظام، وأن العدد المستهدف للإناث لكل ذكر يجب أن يكون قابلًا للضبط عند استخدام هذا النموذج.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 8,
                    'report_category' => 'group_rule',
                    'target_entity' => 'production_group_rule',
                    'options' => [],
                ],
                [
                    'seed_key' => 'production_group_rules.target_females_per_male',
                    'title' => 'ما العدد المستهدف من الإناث لكل ذكر في المجموعة الإنتاجية؟',
                    'help_text' => 'هذه قيمة تنظيمية وليست قاعدة تثبت أن الذكر الأساسي هو المستخدم في كل تلقيح. التلقيح الفعلي يسجل الذكر المستخدم في 4.4.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 9,
                    'report_category' => 'group_target',
                    'target_entity' => 'production_group_rule',
                    'options' => [],
                ],
                [
                    'seed_key' => 'production_group_rules.maximum_female_count_enabled',
                    'title' => 'هل تحتاج المزرعة إلى حد أقصى لعدد الإناث المرتبطة بالذكر ضمن تنظيم المجموعات؟',
                    'help_text' => 'الحد الأقصى هنا يخص العلاقة التنظيمية للمجموعة، ولا يمثل حد عدد التلقيحات أو مرات استخدام الذكر؛ قواعد الاستخدام التناسلي تراجع في 6.5.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 10,
                    'report_category' => 'group_rule',
                    'target_entity' => 'production_group_rule',
                    'options' => [],
                ],
                [
                    'seed_key' => 'production_group_rules.maximum_females_per_male',
                    'title' => 'ما الحد الأقصى للإناث المرتبطة بالذكر في المجموعة الإنتاجية؟',
                    'help_text' => 'إذا تم اعتماد حد أقصى، يستخدم لتنظيم تكوين المجموعة ولا يغير سجل التلقيحات الفعلية أو الأبوة.',
                    'type' => QuestionType::NUMBER,
                    'is_required' => true,
                    'sort_order' => 11,
                    'report_category' => 'group_limit',
                    'target_entity' => 'production_group_rule',
                    'options' => [],
                ],
                [
                    'seed_key' => 'production_group_rules.balance_conditions',
                    'title' => 'ما حالات عدم التوازن التنظيمي التي يجب أن يستطيع النظام تقييمها في المجموعات الإنتاجية؟',
                    'help_text' => 'هذه الشروط تعرف متى توجد حالة تستحق المتابعة؛ إنشاء Alert فعلي ومستواه وأولويته يعالج في 6.12، وعرض الحالة وتحليلها يكون في التقارير.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 12,
                    'report_category' => 'group_rule',
                    'target_entity' => 'production_group_rule',
                    'options' => [
                        ['label' => 'عدد الإناث المرتبطات بالذكر أقل من الهدف عند استخدام هدف', 'value' => 'female_count_below_target'],
                        ['label' => 'عدد الإناث المرتبطات بالذكر أعلى من الهدف عند استخدام هدف', 'value' => 'female_count_above_target'],
                        ['label' => 'عدد الإناث يتجاوز الحد الأقصى المعتمد', 'value' => 'female_count_above_maximum'],
                        ['label' => 'ذكر إنتاجي بلا إناث مرتبطة عند استخدام نموذج المجموعات', 'value' => 'production_male_without_females'],
                        ['label' => 'أنثى إنتاجية بلا مجموعة عندما تكون العضوية مطلوبة وفق قرار 3.5', 'value' => 'required_female_without_group'],
                    ],
                ],
                [
                    'seed_key' => 'production_group_rules.kinship_check_at_assignment',
                    'title' => 'كيف يجب استخدام فحص القرابة عند إضافة أنثى إلى مجموعة ذكر؟',
                    'help_text' => 'المصدر يقترح فحص القرابة عند تكوين المجموعة ثم إعادة التحقق عند التلقيح الفعلي لأن المستخدم قد يختار ذكرًا آخر. قواعد القرابة وحدودها التفصيلية تراجع مع قواعد التلقيح في 6.5؛ هذا السؤال يحسم التكامل مع تنظيم المجموعة.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 13,
                    'report_category' => 'integration_rule',
                    'target_entity' => 'production_group_membership_rule',
                    'options' => [
                        ['label' => 'لا يفحص النظام القرابة عند تكوين المجموعة ويكتفي بالفحص وقت التلقيح', 'value' => 'kinship_check_at_mating_only'],
                        ['label' => 'يفحص القرابة عند تكوين المجموعة باستخدام قواعد 6.5 ويعاد الفحص وقت التلقيح', 'value' => 'check_at_group_assignment_and_recheck_at_mating'],
                        ['label' => 'يستخدم فحص المجموعة كمعلومة تنظيمية فقط، بينما الحكم النهائي دائمًا وقت التلقيح', 'value' => 'group_check_informational_mating_is_final'],
                    ],
                ],
                [
                    'seed_key' => 'production_herd_rules.general_approval_factors',
                    'title' => 'ما الشروط العامة التي يجب أن تدخل في فحص اعتماد الحيوان داخل القطيع الإنتاجي قبل تنفيذ حدث الاعتماد في 4.11؟',
                    'help_text' => 'العمر والوزن ومراحل الفرز ومعايير الإحلال التفصيلية تراجع في 6.9. هنا نحسم الشروط العامة العابرة للمسارات حتى لا يصبح مجرد وجود الحيوان بالمزرعة كافيًا لاعتماده إنتاجيًا.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 14,
                    'report_category' => 'readiness_rule',
                    'target_entity' => 'production_herd_approval_rule',
                    'options' => [
                        ['label' => 'الحيوان موجود حاليًا بالمزرعة ولم يخرج منها', 'value' => 'currently_present_in_farm'],
                        ['label' => 'أكمل مسار الاستقبال / الاعتماد الأولي إذا كان حيوانًا خارجيًا', 'value' => 'external_intake_requirements_completed'],
                        ['label' => 'الحالة الصحية والعزل لا يمنعان الاعتماد', 'value' => 'health_and_isolation_allow_approval'],
                        ['label' => 'ليس مستبعدًا أو محولًا لمسار يتعارض مع القطيع الإنتاجي', 'value' => 'not_excluded_from_production_path'],
                        ['label' => 'لديه تسكين صالح أو مسار تسكين معتمد يسمح بدخوله القطيع', 'value' => 'valid_housing_context'],
                        ['label' => 'استوفى قواعد الترشيح / الإحلال في 6.9 عندما يكون الاعتماد ناتجًا عن إحلال', 'value' => 'replacement_rules_pass_when_applicable'],
                    ],
                ],
                [
                    'seed_key' => 'production_herd_rules.operational_availability_model',
                    'title' => 'كيف يجب تمثيل الجاهزية / الإتاحة التشغيلية الحالية لحيوان معتمد داخل القطيع الإنتاجي؟',
                    'help_text' => 'المصدر يفرق بين وجود الحيوان واعتماده وبين كونه جاهزًا للاستخدام الآن، ويقترح ألا تكون الجاهزية Status يدويًا. الجاهزية التناسلية التفصيلية للذكر والأنثى تراجع في 6.5.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 15,
                    'report_category' => 'derivation_rule',
                    'target_entity' => 'production_herd_operational_availability',
                    'options' => [
                        ['label' => 'قيمة مشتقة تلقائيًا من الاعتماد والسجلات الحالية والقواعد الفعالة، مع إظهار أسباب عدم الجاهزية', 'value' => 'derived_from_current_context_and_rules'],
                        ['label' => 'Status يدوي يحدده المستخدم للحيوان', 'value' => 'manual_readiness_status'],
                        ['label' => 'قيمة مشتقة مع إمكانية Override مؤقت موثق وفق 6.2 عندما تسمح القاعدة بذلك', 'value' => 'derived_with_governed_temporary_override'],
                    ],
                ],
            ];

            app(QuestionSeederSyncService::class)->sync(
                mainSectionName: 'الإعدادات وقواعد التشغيل',
                sectionName: 'قواعد التسكين وتنظيم القطيع والجاهزية',
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

        $targetEnabled = $questions->get('production_group_rules.target_female_count_enabled');
        $targetValue = $questions->get('production_group_rules.target_females_per_male');

        if ($targetEnabled && $targetValue) {
            $targetValue->forceFill([
                'depends_on_question_id' => $targetEnabled->id,
                'dependency_operator' => QuestionDependencyOperator::EQUALS,
                'dependency_value' => '1',
            ])->save();
        }

        $maximumEnabled = $questions->get('production_group_rules.maximum_female_count_enabled');
        $maximumValue = $questions->get('production_group_rules.maximum_females_per_male');

        if ($maximumEnabled && $maximumValue) {
            $maximumValue->forceFill([
                'depends_on_question_id' => $maximumEnabled->id,
                'dependency_operator' => QuestionDependencyOperator::EQUALS,
                'dependency_value' => '1',
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
            ->where('name', 'قواعد التسكين وتنظيم القطيع والجاهزية')
            ->first();
    }
}
