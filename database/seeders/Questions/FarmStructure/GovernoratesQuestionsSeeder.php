<?php

namespace Database\Seeders\Questions\FarmStructure;

use App\Enums\Questionnaire\QuestionType;
use App\Services\Questionnaire\QuestionSeederSyncService;
use Illuminate\Database\Seeder;

class GovernoratesQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        $questions = [
            [
                'seed_key' => 'governorate.fields',
                'title' => 'ما البيانات التي يجب أن يحتوي عليها سجل المحافظة؟',
                'help_text' => 'حدد البيانات الأساسية التي يجب أن يدعمها سجل المحافظة باعتبارها Master Data مستقلة تستخدم لاحقًا في بيانات الموقع.',
                'type' => QuestionType::MULTI_CHOICE,
                'is_required' => true,
                'sort_order' => 1,
                'report_category' => 'field',
                'target_entity' => 'governorate',
                'options' => [
                    ['label' => 'اسم المحافظة بالعربية والإنجليزية', 'value' => 'name'],
                    ['label' => 'وصف المحافظة بالعربية والإنجليزية', 'value' => 'description'],
                    ['label' => 'الحالة: نشطة / غير نشطة', 'value' => 'is_active'],
                    ['label' => 'ترتيب الظهور', 'value' => 'sort_order'],
                    ['label' => 'ملاحظات', 'value' => 'notes'],
                ],
            ],
            [
                'seed_key' => 'governorate.required_fields',
                'title' => 'أي من بيانات المحافظة يجب أن تكون إلزامية عند إنشائها؟',
                'help_text' => 'حدد الحد الأدنى من البيانات التي لا يسمح النظام بإنشاء محافظة بدونها.',
                'type' => QuestionType::MULTI_CHOICE,
                'is_required' => true,
                'sort_order' => 2,
                'report_category' => 'rule',
                'target_entity' => 'governorate',
                'options' => [
                    ['label' => 'اسم المحافظة بالعربية والإنجليزية', 'value' => 'name'],
                    ['label' => 'وصف المحافظة بالعربية والإنجليزية', 'value' => 'description'],
                    ['label' => 'الحالة', 'value' => 'is_active'],
                    ['label' => 'ترتيب الظهور', 'value' => 'sort_order'],
                    ['label' => 'ملاحظات', 'value' => 'notes'],
                ],
            ],
            [
                'seed_key' => 'governorate.management',
                'title' => 'كيف يجب إدارة المحافظات داخل النظام؟',
                'help_text' => 'حدد هل المحافظات تكون قيمًا ثابتة داخل النظام أم Master Data قابلة للإضافة والتعديل من لوحة التحكم.',
                'type' => QuestionType::SINGLE_CHOICE,
                'is_required' => true,
                'sort_order' => 3,
                'report_category' => 'value_management',
                'target_entity' => 'governorate',
                'options' => [
                    ['label' => 'قيم ثابتة داخل النظام ولا يمكن إضافة محافظات جديدة', 'value' => 'fixed'],
                    ['label' => 'قابلة للإضافة والتعديل من لوحة التحكم', 'value' => 'managed'],
                ],
            ],
            [
                'seed_key' => 'governorate.unique_name',
                'title' => 'هل يجب منع تكرار اسم المحافظة؟',
                'help_text' => 'حدد هل يجب منع إنشاء أكثر من محافظة تحمل نفس الاسم داخل قائمة المحافظات.',
                'type' => QuestionType::YES_NO,
                'is_required' => true,
                'sort_order' => 4,
                'report_category' => 'rule',
                'target_entity' => 'governorate',
                'options' => [],
            ],
            [
                'seed_key' => 'governorate.retirement_policy',
                'title' => 'كيف يجب التعامل مع محافظة لم نعد نريد استخدامها؟',
                'help_text' => 'حدد سياسة التعامل مع محافظة قد تكون مستخدمة تاريخيًا في بيانات الموقع، بحيث لا يؤدي إيقاف استخدامها إلى كسر السجلات السابقة.',
                'type' => QuestionType::SINGLE_CHOICE,
                'is_required' => true,
                'sort_order' => 5,
                'report_category' => 'lifecycle_rule',
                'target_entity' => 'governorate',
                'options' => [
                    ['label' => 'يتم تحويلها إلى غير نشطة ولا يسمح بحذفها', 'value' => 'disable_only'],
                    ['label' => 'يسمح بحذفها فقط إذا لم يتم استخدامها سابقًا، وإلا يتم تعطيلها', 'value' => 'delete_if_unused_otherwise_disable'],
                    ['label' => 'يسمح بالحذف المنطقي Soft Delete مع الاحتفاظ بالسجل التاريخي', 'value' => 'soft_delete'],
                ],
            ],
            [
                'seed_key' => 'governorate.initial_data_source',
                'title' => 'كيف يجب توفير البيانات المبدئية للمحافظات؟',
                'help_text' => 'المرجع الوظيفي يثبت الحاجة إلى المحافظة في بيانات الموقع لكنه لا يحتوي Dataset كاملة للمحافظات؛ حدد طريقة تهيئة هذه البيانات في النظام.',
                'type' => QuestionType::SINGLE_CHOICE,
                'is_required' => true,
                'sort_order' => 6,
                'report_category' => 'data_source',
                'target_entity' => 'governorate',
                'options' => [
                    ['label' => 'إدخال المحافظات يدويًا من لوحة التحكم', 'value' => 'manual'],
                    ['label' => 'توفير Dataset مبدئية جاهزة مع النظام', 'value' => 'seeded_dataset'],
                    ['label' => 'استيراد المحافظات من ملف بيانات منظم', 'value' => 'import_file'],
                ],
            ],
        ];

        app(QuestionSeederSyncService::class)->sync(
            mainSectionName: 'إدارة البيانات الأساسية',
            sectionName: 'المحافظات',
            questions: $questions,
            prune: true,
            preserveAnswers: false,
        );
    }
}
