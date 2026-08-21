<?php

namespace Database\Seeders\Questions\FarmStructure;

use App\Enums\Questionnaire\QuestionType;
use App\Services\Questionnaire\QuestionSeederSyncService;
use Illuminate\Database\Seeder;

class ProductionPurposesQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        $questions = [
            [
                'seed_key' => 'production_purpose.representation',
                'title' => 'كيف يجب تمثيل الأغراض الإنتاجية داخل النظام؟',
                'help_text' => 'يحسم هذا السؤال هل الأغراض الإنتاجية تكون قيمًا ثابتة داخل الكود / Enum أم Master Data مستقلة يمكن إدارتها من لوحة التحكم. هذا القسم يعرّف الغرض نفسه فقط، أما طريقة ربط الأغراض بالسلالات فتُحسم لاحقًا في قسم بيانات السلالات.',
                'type' => QuestionType::SINGLE_CHOICE,
                'is_required' => true,
                'sort_order' => 1,
                'report_category' => 'value_management',
                'target_entity' => 'production_purpose',
                'options' => [
                    ['label' => 'قيم ثابتة داخل الكود / Enum ولا يمكن إضافة أغراض جديدة من لوحة التحكم', 'value' => 'fixed_enum'],
                    ['label' => 'Master Data مستقلة قابلة للإضافة والتعديل من لوحة التحكم', 'value' => 'managed_master_data'],
                ],
            ],
            [
                'seed_key' => 'production_purpose.fields',
                'title' => 'ما البيانات التي يجب أن يحتوي عليها سجل الغرض الإنتاجي؟',
                'help_text' => 'حدد البيانات التي تصف الغرض الإنتاجي نفسه باعتباره قيمة مرجعية مستقلة. الاسم والوصف القابلان للعرض يستخدمان قاعدة الترجمة المعتمدة في المشروع.',
                'type' => QuestionType::MULTI_CHOICE,
                'is_required' => true,
                'sort_order' => 2,
                'report_category' => 'field',
                'target_entity' => 'production_purpose',
                'options' => [
                    ['label' => 'اسم الغرض بالعربية والإنجليزية', 'value' => 'name'],
                    ['label' => 'وصف الغرض بالعربية والإنجليزية', 'value' => 'description'],
                    ['label' => 'الحالة: نشط / غير نشط', 'value' => 'is_active'],
                    ['label' => 'ترتيب الظهور', 'value' => 'sort_order'],
                    ['label' => 'ملاحظات', 'value' => 'notes'],
                ],
            ],
            [
                'seed_key' => 'production_purpose.required_fields',
                'title' => 'أي من بيانات الغرض الإنتاجي يجب أن تكون إلزامية عند إنشائه؟',
                'help_text' => 'حدد الحد الأدنى من البيانات التي لا يسمح النظام بإنشاء غرض إنتاجي بدونها.',
                'type' => QuestionType::MULTI_CHOICE,
                'is_required' => true,
                'sort_order' => 3,
                'report_category' => 'rule',
                'target_entity' => 'production_purpose',
                'options' => [
                    ['label' => 'اسم الغرض بالعربية والإنجليزية', 'value' => 'name'],
                    ['label' => 'وصف الغرض بالعربية والإنجليزية', 'value' => 'description'],
                    ['label' => 'الحالة', 'value' => 'is_active'],
                    ['label' => 'ترتيب الظهور', 'value' => 'sort_order'],
                    ['label' => 'ملاحظات', 'value' => 'notes'],
                ],
            ],
            [
                'seed_key' => 'production_purpose.unique_name',
                'title' => 'هل يجب منع تكرار اسم الغرض الإنتاجي؟',
                'help_text' => 'حدد هل يجب منع إنشاء أكثر من غرض إنتاجي يحمل نفس الاسم حتى تظل القائمة المرجعية واضحة وغير مكررة.',
                'type' => QuestionType::YES_NO,
                'is_required' => true,
                'sort_order' => 4,
                'report_category' => 'rule',
                'target_entity' => 'production_purpose',
                'options' => [],
            ],
            [
                'seed_key' => 'production_purpose.retirement_policy',
                'title' => 'كيف يجب التعامل مع غرض إنتاجي لم نعد نريد استخدامه؟',
                'help_text' => 'حدد سياسة التعامل مع الغرض إذا كان قد استخدم تاريخيًا في علاقات أو سجلات، بحيث لا يؤدي إيقاف استخدامه إلى كسر التاريخ.',
                'type' => QuestionType::SINGLE_CHOICE,
                'is_required' => true,
                'sort_order' => 5,
                'report_category' => 'lifecycle_rule',
                'target_entity' => 'production_purpose',
                'options' => [
                    ['label' => 'يتم تحويله إلى غير نشط ولا يسمح بحذفه', 'value' => 'disable_only'],
                    ['label' => 'يسمح بحذفه إذا لم يستخدم سابقًا، وإلا يتم تحويله إلى غير نشط', 'value' => 'delete_if_unused_otherwise_disable'],
                    ['label' => 'يسمح بالحذف المنطقي Soft Delete مع الاحتفاظ بالسجل التاريخي', 'value' => 'soft_delete'],
                ],
            ],
            [
                'seed_key' => 'production_purpose.initial_values',
                'title' => 'ما الأغراض الإنتاجية المبدئية التي يجب أن يوفرها النظام؟',
                'help_text' => 'حدد القيم المبدئية التي يبدأ بها النظام. القائمة هنا تعرف Master Data فقط؛ لا تحدد هل السلالة ترتبط بغرض واحد أو أكثر، ولا تحسم بعد ما إذا كانت قيمة «متعدد الأغراض» ستظل مطلوبة بعد تحديد Cardinality في قسم بيانات السلالات.',
                'type' => QuestionType::MULTI_CHOICE,
                'is_required' => true,
                'sort_order' => 6,
                'report_category' => 'lookup_values',
                'target_entity' => 'production_purpose',
                'options' => [
                    ['label' => 'إنتاج لحم', 'value' => 'meat'],
                    ['label' => 'أمهات / إنتاج', 'value' => 'breeding_production'],
                    ['label' => 'تحسين وراثي', 'value' => 'genetic_improvement'],
                    ['label' => 'متعدد الأغراض', 'value' => 'multi_purpose'],
                ],
            ],
            [
                'seed_key' => 'production_purpose.additional_requirements',
                'title' => 'هل توجد ملاحظات تحتاج إلى تحويلها لاحقًا إلى أسئلة محددة تخص الأغراض الإنتاجية؟',
                'help_text' => 'هذا سؤال دراسة مفتوح مؤقت لتدوين نقاط تحتاج مراجعة. أي متطلب حقيقي يظهر هنا يجب تحويله إلى سؤال مستقل قبل تجهيز Final Requirements Input.',
                'type' => QuestionType::TEXTAREA,
                'is_required' => false,
                'sort_order' => 7,
                'report_category' => 'manual_review',
                'target_entity' => 'production_purpose',
                'options' => [],
            ],
        ];

        app(QuestionSeederSyncService::class)->sync(
            mainSectionName: 'إدارة البيانات الأساسية',
            sectionName: 'الأغراض الإنتاجية',
            questions: $questions,
            prune: true,
            preserveAnswers: true,
        );
    }
}
