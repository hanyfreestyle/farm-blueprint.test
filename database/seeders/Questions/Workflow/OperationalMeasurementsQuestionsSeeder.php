<?php

namespace Database\Seeders\Questions\Workflow;

use App\Enums\Questionnaire\QuestionDependencyOperator;
use App\Enums\Questionnaire\QuestionType;
use App\Models\QuestionnaireQuestion;
use App\Models\QuestionnaireSection;
use App\Services\Questionnaire\QuestionSeederSyncService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OperationalMeasurementsQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $questions = [
                [
                    'seed_key' => 'operational_measurement.subject_types',
                    'title' => 'ما وحدات التتبع التي يجب أن يستطيع النظام تسجيل وزن فعلي لها؟',
                    'help_text' => 'المرجع يستخدم الوزن للحيوان الفردي، كما يطرح وزن المواليد أثناء الرضاعة قبل اكتمال التتبع الفردي. لا يفترض هذا السؤال وجود قياسات أخرى غير موثقة في المصدر الحالي.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 1,
                    'report_category' => 'workflow_model',
                    'target_entity' => 'operational_measurement',
                    'options' => [
                        ['label' => 'الحيوان الفردي بعد وجود سجل مستقل له', 'value' => 'individual_animal'],
                        ['label' => 'البطن / مجموعة المواليد قبل التحول الكامل للتتبع الفردي', 'value' => 'preweaning_litter'],
                    ],
                ],
                [
                    'seed_key' => 'operational_measurement.record_fields',
                    'title' => 'ما البيانات التي يجب أن يحتفظ بها سجل الوزن الفعلي؟',
                    'help_text' => 'الوزن نفسه يسجل كواقعة تاريخية مستقلة، ولا يستبدل قيمة وزن ثابتة في ملف الحيوان. يمكن ربط السجل بالسياق التشغيلي الذي تم القياس خلاله عند الحاجة.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 2,
                    'report_category' => 'field',
                    'target_entity' => 'weight_measurement',
                    'options' => [
                        ['label' => 'الحيوان أو البطن موضوع القياس', 'value' => 'subject'],
                        ['label' => 'قيمة الوزن', 'value' => 'weight_value'],
                        ['label' => 'وحدة الوزن', 'value' => 'weight_unit'],
                        ['label' => 'تاريخ / وقت القياس الفعلي', 'value' => 'measured_at'],
                        ['label' => 'السياق / المرحلة التشغيلية للقياس', 'value' => 'measurement_context'],
                        ['label' => 'العمر وقت القياس عند إمكان تحديده', 'value' => 'age_at_measurement'],
                        ['label' => 'مرجع الحدث أو الدورة أو البطن المرتبط بالقياس عند انطباقه', 'value' => 'related_workflow_reference'],
                        ['label' => 'المستخدم / المنفذ', 'value' => 'performed_by'],
                        ['label' => 'ملاحظات', 'value' => 'notes'],
                    ],
                ],
                [
                    'seed_key' => 'operational_measurement.weight_unit_policy',
                    'title' => 'كيف يجب التعامل مع وحدة الوزن عند التسجيل والعرض؟',
                    'help_text' => 'الهدف منع وجود أوزان لا يمكن مقارنتها بسبب اختلاف الوحدة. هذا السؤال يحسم نموذج التعامل مع الوحدة فقط، ولا يحدد أهداف الوزن أو حدوده التشغيلية.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 3,
                    'report_category' => 'measurement_rule',
                    'target_entity' => 'weight_measurement',
                    'options' => [
                        ['label' => 'استخدام وحدة وزن واحدة ثابتة في النظام كله', 'value' => 'single_fixed_unit'],
                        ['label' => 'تحدد المزرعة وحدة الوزن المفضلة ويقوم النظام بالتوحيد عند التخزين والمقارنة', 'value' => 'farm_preferred_unit_with_normalization'],
                        ['label' => 'السماح بالتسجيل بأكثر من وحدة مع تحويل القيم إلى وحدة داخلية موحدة', 'value' => 'multi_unit_input_with_normalization'],
                    ],
                ],
                [
                    'seed_key' => 'operational_measurement.context_types',
                    'title' => 'ما السياقات التشغيلية التي يجب أن يستطيع سجل الوزن تمييزها؟',
                    'help_text' => 'السياق يشرح لماذا تم الوزن دون إنشاء نسخ منفصلة من نفس القيمة. مواعيد الوزن وإلزامه في كل مرحلة تبقى Rules داخل Settings، وليست جزءًا من هذا السؤال.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 4,
                    'report_category' => 'workflow_context',
                    'target_entity' => 'weight_measurement',
                    'options' => [
                        ['label' => 'وزن دخول / استقبال', 'value' => 'intake'],
                        ['label' => 'متابعة الأنثى أثناء الحمل', 'value' => 'pregnancy'],
                        ['label' => 'متابعة البطن / المواليد أثناء الرضاعة', 'value' => 'lactation'],
                        ['label' => 'وزن الفطام', 'value' => 'weaning'],
                        ['label' => 'وزن دوري أثناء النمو', 'value' => 'growth_periodic'],
                        ['label' => 'وزن مرتبط بالفرز أو إعادة التقييم', 'value' => 'sorting_evaluation'],
                        ['label' => 'متابعة مرشح الإحلال', 'value' => 'replacement_followup'],
                        ['label' => 'متابعة التسمين', 'value' => 'fattening'],
                        ['label' => 'سياق تشغيلي آخر عند الحاجة', 'value' => 'other'],
                    ],
                ],
                [
                    'seed_key' => 'operational_measurement.single_source_linking',
                    'title' => 'عندما يسجل الوزن من داخل Workflow آخر، هل يجب إنشاء سجل وزن واحد وربطه بالحدث الأصلي بدل حفظ نسختين مستقلتين للقيمة؟',
                    'help_text' => 'مثال: وزن الدخول أو وزن الأنثى أثناء الحمل يجب أن يظهر في سجل الأوزان العام، مع بقاء علاقته بحدث الاستقبال أو دورة الحمل. الهدف أن تكون قيمة القياس لها مصدر تاريخي واحد يمكن الاعتماد عليه.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 5,
                    'report_category' => 'integrity_rule',
                    'target_entity' => 'weight_measurement',
                    'options' => [],
                ],
                [
                    'seed_key' => 'operational_measurement.age_handling',
                    'title' => 'كيف يجب تحديد العمر وقت القياس؟',
                    'help_text' => 'العمر قد يكون قابلًا للحساب من تاريخ ميلاد مؤكد أو تقديري، وقد يكون غير معروف في بعض الحيوانات الخارجية. المطلوب حسم مصدر العمر المرتبط بالقياس دون تحويل العمر الحالي إلى حقل ثابت.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 6,
                    'report_category' => 'derivation_rule',
                    'target_entity' => 'weight_measurement',
                    'options' => [
                        ['label' => 'يحسب تلقائيًا من بيانات الميلاد عند توافرها، ويظل غير معروف عند غيابها', 'value' => 'derive_from_birth_or_unknown'],
                        ['label' => 'يحسب تلقائيًا عند توافر بيانات الميلاد، ويسمح بعمر تقديري عند غيابها', 'value' => 'derive_or_allow_estimated_age'],
                        ['label' => 'يسجل العمر يدويًا مع كل قياس', 'value' => 'manual_age_per_measurement'],
                    ],
                ],
                [
                    'seed_key' => 'operational_measurement.preweaning_weight_model',
                    'title' => 'إذا تم وزن المواليد قبل الفطام، كيف يجب تمثيل هذا الوزن قبل اكتمال التتبع الفردي؟',
                    'help_text' => 'المرجع يضع هذه النقطة صراحة ضمن ما يحتاج مراجعة. لا يفترض السؤال وجود هوية فردية كاملة لكل مولود قبل الفطام؛ الخيار الفردي يطبق فقط إذا كان المولود قابلًا للتعريف فعليًا.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 7,
                    'report_category' => 'workflow_model',
                    'target_entity' => 'litter_weight_measurement',
                    'options' => [
                        ['label' => 'لا نسجل وزنًا للمواليد قبل الفطام', 'value' => 'no_preweaning_weight'],
                        ['label' => 'نسجل الوزن الإجمالي للبطن فقط', 'value' => 'litter_total_weight'],
                        ['label' => 'نسجل الوزن الإجمالي ويحسب النظام متوسط وزن المولود من العدد الحالي', 'value' => 'litter_total_with_derived_average'],
                        ['label' => 'نسجل أوزانًا فردية فقط عندما يكون كل مولود قابلًا للتعريف قبل الفطام', 'value' => 'individual_when_identifiable'],
                        ['label' => 'ندعم وزن البطن ككل والأوزان الفردية عندما تكون الهوية متاحة', 'value' => 'litter_and_individual_when_identifiable'],
                    ],
                ],
                [
                    'seed_key' => 'operational_measurement.batch_entry_support',
                    'title' => 'هل يجب أن يدعم النظام جلسة وزن واحدة لإدخال أوزان عدة حيوانات بصورة جماعية؟',
                    'help_text' => 'هذا يفيد في الأوزان الدورية أثناء النمو أو التسمين. المقصود تسهيل الإدخال فقط، وليس تحويل وزن المجموعة إلى قيمة واحدة مشتركة لكل الحيوانات.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 8,
                    'report_category' => 'workflow_model',
                    'target_entity' => 'measurement_session',
                    'options' => [],
                ],
                [
                    'seed_key' => 'operational_measurement.batch_individual_records',
                    'title' => 'عند استخدام جلسة وزن جماعية، هل يجب إنشاء سجل وزن مستقل لكل حيوان وربطه بالجلسة المشتركة؟',
                    'help_text' => 'الهدف الحفاظ على Timeline مستقل لكل حيوان مع إمكانية تنفيذ عملية الإدخال بصورة أسرع على مستوى مجموعة.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 9,
                    'report_category' => 'history_rule',
                    'target_entity' => 'weight_measurement',
                    'options' => [],
                ],
            ];

            app(QuestionSeederSyncService::class)->sync(
                mainSectionName: 'الحركات ودورة التشغيل الفعلية',
                sectionName: 'الوزن والقياسات التشغيلية',
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

        $subjectTypes = $questions->get('operational_measurement.subject_types');
        $preweaningWeightModel = $questions->get('operational_measurement.preweaning_weight_model');

        if ($subjectTypes && $preweaningWeightModel) {
            $preweaningWeightModel->forceFill([
                'depends_on_question_id' => $subjectTypes->id,
                'dependency_operator' => QuestionDependencyOperator::CONTAINS,
                'dependency_value' => 'preweaning_litter',
            ])->save();
        }

        $batchEntrySupport = $questions->get('operational_measurement.batch_entry_support');
        $batchIndividualRecords = $questions->get('operational_measurement.batch_individual_records');

        if ($batchEntrySupport && $batchIndividualRecords) {
            $batchIndividualRecords->forceFill([
                'depends_on_question_id' => $batchEntrySupport->id,
                'dependency_operator' => QuestionDependencyOperator::EQUALS,
                'dependency_value' => '1',
            ])->save();
        }
    }

    private function resolveSection(): ?QuestionnaireSection
    {
        $mainSection = QuestionnaireSection::query()
            ->whereNull('parent_id')
            ->where('name', 'الحركات ودورة التشغيل الفعلية')
            ->first();

        if (! $mainSection) {
            return null;
        }

        return QuestionnaireSection::query()
            ->where('parent_id', $mainSection->id)
            ->where('name', 'الوزن والقياسات التشغيلية')
            ->first();
    }
}
