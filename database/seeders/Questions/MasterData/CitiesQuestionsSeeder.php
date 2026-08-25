<?php

namespace Database\Seeders\Questions\MasterData;

use App\Enums\Questionnaire\QuestionType;
use App\Services\Questionnaire\QuestionSeederSyncService;
use Illuminate\Database\Seeder;

class CitiesQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        $questions = [
            [
                'seed_key' => 'city.fields',
                'title' => 'ما البيانات التي يجب أن يحتوي عليها سجل المدينة؟',
                'help_text' => 'حدد البيانات الأساسية التي يجب أن يدعمها سجل المدينة باعتبارها Master Data تابعة للمحافظات وتستخدم لاحقًا في بيانات الموقع.',
                'type' => QuestionType::MULTI_CHOICE,
                'is_required' => true,
                'sort_order' => 1,
                'report_category' => 'field',
                'target_entity' => 'city',
                'options' => [
                    ['label' => 'اسم المدينة بالعربية والإنجليزية', 'value' => 'name'],
                    ['label' => 'المحافظة التابعة لها', 'value' => 'governorate_id'],
                    ['label' => 'وصف المدينة بالعربية والإنجليزية', 'value' => 'description'],
                    ['label' => 'الحالة: نشطة / غير نشطة', 'value' => 'is_active'],
                    ['label' => 'ترتيب الظهور', 'value' => 'sort_order'],
                    ['label' => 'ملاحظات', 'value' => 'notes'],
                ],
            ],
            [
                'seed_key' => 'city.required_fields',
                'title' => 'أي من بيانات المدينة يجب أن تكون إلزامية عند إنشائها؟',
                'help_text' => 'حدد الحد الأدنى من البيانات التي لا يسمح النظام بإنشاء مدينة بدونها، بما في ذلك علاقتها بالمحافظة.',
                'type' => QuestionType::MULTI_CHOICE,
                'is_required' => true,
                'sort_order' => 2,
                'report_category' => 'rule',
                'target_entity' => 'city',
                'options' => [
                    ['label' => 'اسم المدينة بالعربية والإنجليزية', 'value' => 'name'],
                    ['label' => 'المحافظة التابعة لها', 'value' => 'governorate_id'],
                    ['label' => 'وصف المدينة بالعربية والإنجليزية', 'value' => 'description'],
                    ['label' => 'الحالة', 'value' => 'is_active'],
                    ['label' => 'ترتيب الظهور', 'value' => 'sort_order'],
                    ['label' => 'ملاحظات', 'value' => 'notes'],
                ],
            ],
            [
                'seed_key' => 'city.governorate_relation',
                'title' => 'هل يجب أن ترتبط كل مدينة بمحافظة واحدة؟',
                'help_text' => 'يثبت هذا السؤال العلاقة الأساسية بين المحافظات والمدن ويحدد ما إذا كانت كل مدينة يجب أن تحمل مرجعًا لمحافظة واحدة.',
                'type' => QuestionType::YES_NO,
                'is_required' => true,
                'sort_order' => 3,
                'report_category' => 'relationship',
                'target_entity' => 'city',
                'options' => [],
            ],
            [
                'seed_key' => 'city.management',
                'title' => 'كيف يجب إدارة المدن داخل النظام؟',
                'help_text' => 'حدد هل المدن تكون قيمًا ثابتة داخل النظام أم Master Data قابلة للإضافة والتعديل من لوحة التحكم.',
                'type' => QuestionType::SINGLE_CHOICE,
                'is_required' => true,
                'sort_order' => 4,
                'report_category' => 'value_management',
                'target_entity' => 'city',
                'options' => [
                    ['label' => 'قيم ثابتة داخل النظام ولا يمكن إضافة مدن جديدة', 'value' => 'fixed'],
                    ['label' => 'قابلة للإضافة والتعديل من لوحة التحكم', 'value' => 'managed'],
                ],
            ],
            [
                'seed_key' => 'city.unique_scope',
                'title' => 'ما قاعدة عدم التكرار المطلوبة لاسم المدينة؟',
                'help_text' => 'حدد نطاق التحقق من عدم تكرار أسماء المدن، مع مراعاة أن نفس الاسم قد يظهر في محافظات مختلفة.',
                'type' => QuestionType::SINGLE_CHOICE,
                'is_required' => true,
                'sort_order' => 5,
                'report_category' => 'rule',
                'target_entity' => 'city',
                'options' => [
                    ['label' => 'يمنع تكرار اسم المدينة على مستوى النظام بالكامل', 'value' => 'global_unique'],
                    ['label' => 'يسمح بتكرار الاسم في محافظات مختلفة، ويمنع تكراره داخل نفس المحافظة', 'value' => 'unique_within_governorate'],
                ],
            ],
            [
                'seed_key' => 'city.retirement_policy',
                'title' => 'كيف يجب التعامل مع مدينة لم نعد نريد استخدامها؟',
                'help_text' => 'حدد سياسة التعامل مع مدينة قد تكون مستخدمة تاريخيًا في بيانات الموقع، بحيث لا يؤدي إيقاف استخدامها إلى كسر السجلات السابقة.',
                'type' => QuestionType::SINGLE_CHOICE,
                'is_required' => true,
                'sort_order' => 6,
                'report_category' => 'lifecycle_rule',
                'target_entity' => 'city',
                'options' => [
                    ['label' => 'يتم تحويلها إلى غير نشطة ولا يسمح بحذفها', 'value' => 'disable_only'],
                    ['label' => 'يسمح بحذفها فقط إذا لم يتم استخدامها سابقًا، وإلا يتم تعطيلها', 'value' => 'delete_if_unused_otherwise_disable'],
                    ['label' => 'يسمح بالحذف المنطقي Soft Delete مع الاحتفاظ بالسجل التاريخي', 'value' => 'soft_delete'],
                ],
            ],
            [
                'seed_key' => 'city.initial_data_source',
                'title' => 'كيف يجب توفير البيانات المبدئية للمدن؟',
                'help_text' => 'المرجع الوظيفي يثبت الحاجة إلى المدن في بيانات الموقع لكنه لا يحتوي Dataset كاملة للمدن؛ حدد طريقة تهيئة هذه البيانات في النظام.',
                'type' => QuestionType::SINGLE_CHOICE,
                'is_required' => true,
                'sort_order' => 7,
                'report_category' => 'data_source',
                'target_entity' => 'city',
                'options' => [
                    ['label' => 'إدخال المدن يدويًا من لوحة التحكم', 'value' => 'manual'],
                    ['label' => 'توفير Dataset مبدئية جاهزة مع النظام', 'value' => 'seeded_dataset'],
                    ['label' => 'استيراد المدن من ملف بيانات منظم', 'value' => 'import_file'],
                ],
            ],
        ];

        app(QuestionSeederSyncService::class)->sync(
            mainSectionName: 'إدارة البيانات الأساسية',
            sectionName: 'المدن',
            questions: $questions,
            prune: true,
            preserveAnswers: true,
        );
    }
}
