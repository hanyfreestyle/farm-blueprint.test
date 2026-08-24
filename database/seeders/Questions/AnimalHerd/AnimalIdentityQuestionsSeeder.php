<?php

namespace Database\Seeders\Questions\AnimalHerd;

use App\Enums\Questionnaire\QuestionDependencyOperator;
use App\Enums\Questionnaire\QuestionType;
use App\Models\QuestionnaireQuestion;
use App\Models\QuestionnaireSection;
use App\Services\Questionnaire\QuestionSeederSyncService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AnimalIdentityQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $questions = [
                [
                    'seed_key' => 'animal.identity_fields',
                    'title' => 'ما بيانات الهوية المباشرة التي يجب أن يدعمها ملف الحيوان؟',
                    'help_text' => 'حدد البيانات التي تمثل هوية الحيوان الأساسية والمستمرة معه. لا يشمل هذا السؤال الموقع الحالي أو الوزن الحالي أو الجاهزية أو الحالة الإنتاجية أو الصحية الحالية؛ فهذه معلومات تنتج من السجلات والحركات المرتبطة بالحيوان.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 1,
                    'report_category' => 'field',
                    'target_entity' => 'animal',
                    'options' => [
                        ['label' => 'الكود الداخلي', 'value' => 'internal_code'],
                        ['label' => 'المعرفات الخارجية مثل الرقم الخارجي / الحلقة / الوشم', 'value' => 'external_identifiers'],
                        ['label' => 'الجنس', 'value' => 'sex'],
                        ['label' => 'السلالة', 'value' => 'breed'],
                        ['label' => 'معلومات الميلاد', 'value' => 'birth_information'],
                        ['label' => 'صورة الحيوان', 'value' => 'photo'],
                        ['label' => 'علامات تعريفية أو ملاحظات مميزة', 'value' => 'distinguishing_marks'],
                    ],
                ],
                [
                    'seed_key' => 'animal.internal_code_strategy',
                    'title' => 'كيف يجب إنشاء الكود الداخلي للحيوان؟',
                    'help_text' => 'حدد من المسؤول عن إنشاء الكود الداخلي الذي يستخدم كهوية تشغيلية ثابتة للحيوان داخل النظام. شكل الكود نفسه ومكوناته لا تحسم في هذا السؤال.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 2,
                    'report_category' => 'identification_rule',
                    'target_entity' => 'animal',
                    'options' => [
                        ['label' => 'يقوم النظام بتوليده تلقائيًا', 'value' => 'automatic'],
                        ['label' => 'يدخله المستخدم يدويًا', 'value' => 'manual'],
                    ],
                ],
                [
                    'seed_key' => 'animal.internal_code_unique_scope',
                    'title' => 'ما نطاق عدم تكرار الكود الداخلي للحيوان؟',
                    'help_text' => 'حدد النطاق الذي يجب أن تكون داخله هوية الحيوان غير مكررة، مع مراعاة أن النظام قد يدعم أكثر من مزرعة.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 3,
                    'report_category' => 'validation_rule',
                    'target_entity' => 'animal',
                    'options' => [
                        ['label' => 'فريد داخل المزرعة', 'value' => 'unique_within_farm'],
                        ['label' => 'فريد على مستوى النظام بالكامل', 'value' => 'globally_unique'],
                    ],
                ],
                [
                    'seed_key' => 'animal.internal_code_lifetime',
                    'title' => 'هل يجب أن يظل الكود الداخلي ثابتًا مع الحيوان طوال حياته؟',
                    'help_text' => 'المقصود ألا يتغير الكود بسبب انتقال الحيوان بين الأقفاص أو تغير مرحلته أو مساره التشغيلي أو مصيره داخل المزرعة.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 4,
                    'report_category' => 'lifecycle_rule',
                    'target_entity' => 'animal',
                    'options' => [],
                ],
                [
                    'seed_key' => 'animal.external_identifier_cardinality',
                    'title' => 'هل يمكن للحيوان الاحتفاظ بأكثر من معرف خارجي في نفس الوقت؟',
                    'help_text' => 'مثل وجود رقم حلقة ووشم أو أكثر من وسيلة تعريف خارجية لنفس الحيوان، مع بقاء الكود الداخلي هو هوية النظام الخاصة به.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 5,
                    'report_category' => 'relationship_rule',
                    'target_entity' => 'animal_external_identifier',
                    'options' => [],
                ],
                [
                    'seed_key' => 'animal.external_identifier_types',
                    'title' => 'ما أنواع المعرفات الخارجية التي يجب أن يستطيع النظام تمييزها؟',
                    'help_text' => 'حدد أنواع وسائل التعريف التي قد تكون موجودة مسبقًا على الحيوان. هذا السؤال لا يعيد تعريف الكود الداخلي للحيوان ولا بيانات المصدر.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 6,
                    'report_category' => 'lookup_values',
                    'target_entity' => 'animal_external_identifier_type',
                    'options' => [
                        ['label' => 'رقم خارجي عام', 'value' => 'external_number'],
                        ['label' => 'رقم حلقة', 'value' => 'ring_number'],
                        ['label' => 'وشم', 'value' => 'tattoo'],
                        ['label' => 'نوع آخر', 'value' => 'other'],
                    ],
                ],
                [
                    'seed_key' => 'animal.temporary_unknown_sex',
                    'title' => 'هل يجب السماح بتسجيل جنس الحيوان كـ«غير محدد مؤقتًا» عندما لا يمكن تحديده بعد؟',
                    'help_text' => 'هذا القرار يخص إمكانية عدم حسم الجنس مؤقتًا، خصوصًا عند صغر العمر. توقيت وجوب حسم الجنس لاحقًا يعالج ضمن قواعد الفطام والنمو والتشغيل.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 7,
                    'report_category' => 'field_rule',
                    'target_entity' => 'animal',
                    'options' => [],
                ],
                [
                    'seed_key' => 'animal.breed_requirement',
                    'title' => 'متى يجب تحديد سلالة الحيوان؟',
                    'help_text' => 'السلالات معرفة كـMaster Data مستقلة. المطلوب هنا تحديد توقيت إلزام علاقة الحيوان بالسلالة دون إعادة تعريف أنواع السلالات أو بياناتها.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 8,
                    'report_category' => 'relationship_rule',
                    'target_entity' => 'animal_breed',
                    'options' => [
                        ['label' => 'إلزامية عند إنشاء سجل الحيوان', 'value' => 'required_on_create'],
                        ['label' => 'يمكن أن تكون غير معروفة مؤقتًا وتستكمل لاحقًا', 'value' => 'may_be_unknown_temporarily'],
                        ['label' => 'اختيارية', 'value' => 'optional'],
                    ],
                ],
                [
                    'seed_key' => 'animal.birth_information_methods',
                    'title' => 'ما طرق تسجيل معلومات الميلاد التي يجب أن يدعمها النظام عندما لا تكون البيانات كاملة؟',
                    'help_text' => 'العمر الحالي يحسبه النظام تلقائيًا ولا يخزن كقيمة يدوية ثابتة. المطلوب تحديد أشكال المعلومات التي يمكن تسجيلها عندما يكون تاريخ الميلاد مؤكدًا أو تقديريًا أو غير معروف.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 9,
                    'report_category' => 'field_rule',
                    'target_entity' => 'animal_birth_information',
                    'options' => [
                        ['label' => 'تاريخ ميلاد مؤكد', 'value' => 'confirmed_birth_date'],
                        ['label' => 'تاريخ ميلاد تقديري', 'value' => 'estimated_birth_date'],
                        ['label' => 'عمر تقديري عند بداية التسجيل', 'value' => 'estimated_age_at_registration'],
                        ['label' => 'السماح بأن تظل معلومات الميلاد غير معروفة مؤقتًا', 'value' => 'temporarily_unknown'],
                    ],
                ],
            ];

            app(QuestionSeederSyncService::class)->sync(
                mainSectionName: 'بيانات الحيوان وتكوين القطيع',
                sectionName: 'بيانات وهوية الحيوان',
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

        $identityFields = $questions->get('animal.identity_fields');

        if (! $identityFields) {
            return;
        }

        $dependencies = [
            'animal.internal_code_strategy' => 'internal_code',
            'animal.internal_code_unique_scope' => 'internal_code',
            'animal.internal_code_lifetime' => 'internal_code',
            'animal.external_identifier_cardinality' => 'external_identifiers',
            'animal.external_identifier_types' => 'external_identifiers',
            'animal.temporary_unknown_sex' => 'sex',
            'animal.breed_requirement' => 'breed',
            'animal.birth_information_methods' => 'birth_information',
        ];

        foreach ($dependencies as $dependentSeedKey => $dependencyValue) {
            $dependentQuestion = $questions->get($dependentSeedKey);

            if (! $dependentQuestion) {
                continue;
            }

            $dependentQuestion->forceFill([
                'depends_on_question_id' => $identityFields->id,
                'dependency_operator' => QuestionDependencyOperator::CONTAINS,
                'dependency_value' => $dependencyValue,
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
            ->where('name', 'بيانات وهوية الحيوان')
            ->first();
    }
}
