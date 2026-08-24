<?php

namespace Database\Seeders\Questions\MasterData;

use App\Enums\Questionnaire\QuestionType;
use App\Services\Questionnaire\QuestionSeederSyncService;
use Illuminate\Database\Seeder;

class OperationalActivitiesQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        $questions = [
            [
                'seed_key' => 'operational_activity.fields',
                'title' => 'ما البيانات التي يجب أن يحتوي عليها سجل النشاط التشغيلي؟',
                'help_text' => 'حدد البيانات الأساسية التي يجب أن يدعمها سجل النشاط التشغيلي باعتباره Master Data مستقلة تستخدم لاحقًا في أجزاء أخرى من النظام.',
                'type' => QuestionType::MULTI_CHOICE,
                'is_required' => true,
                'sort_order' => 1,
                'report_category' => 'field',
                'target_entity' => 'operational_activity',
                'options' => [
                    ['label' => 'اسم النشاط بالعربية والإنجليزية', 'value' => 'name'],
                    ['label' => 'وصف النشاط بالعربية والإنجليزية', 'value' => 'description'],
                    ['label' => 'حالة النشاط: نشط / غير نشط', 'value' => 'is_active'],
                    ['label' => 'ترتيب الظهور', 'value' => 'sort_order'],
                    ['label' => 'ملاحظات', 'value' => 'notes'],
                ],
            ],
            [
                'seed_key' => 'operational_activity.required_fields',
                'title' => 'أي من بيانات النشاط التشغيلي يجب أن تكون إلزامية عند إنشائه؟',
                'help_text' => 'حدد الحد الأدنى من البيانات التي لا يسمح النظام بإنشاء نشاط تشغيلي بدونها.',
                'type' => QuestionType::MULTI_CHOICE,
                'is_required' => true,
                'sort_order' => 2,
                'report_category' => 'rule',
                'target_entity' => 'operational_activity',
                'options' => [
                    ['label' => 'اسم النشاط بالعربية والإنجليزية', 'value' => 'name'],
                    ['label' => 'وصف النشاط بالعربية والإنجليزية', 'value' => 'description'],
                    ['label' => 'حالة النشاط', 'value' => 'is_active'],
                    ['label' => 'ترتيب الظهور', 'value' => 'sort_order'],
                    ['label' => 'ملاحظات', 'value' => 'notes'],
                ],
            ],
            [
                'seed_key' => 'operational_activity.management',
                'title' => 'كيف يجب إدارة الأنشطة التشغيلية داخل النظام؟',
                'help_text' => 'حدد هل قائمة الأنشطة تكون ثابتة في الكود أم Master Data قابلة للإضافة والتعديل من لوحة التحكم.',
                'type' => QuestionType::SINGLE_CHOICE,
                'is_required' => true,
                'sort_order' => 3,
                'report_category' => 'value_management',
                'target_entity' => 'operational_activity',
                'options' => [
                    ['label' => 'قيم ثابتة داخل النظام ولا يمكن إضافة أنشطة جديدة', 'value' => 'fixed'],
                    ['label' => 'قابلة للإضافة والتعديل من لوحة التحكم', 'value' => 'managed'],
                ],
            ],
            [
                'seed_key' => 'operational_activity.unique_name',
                'title' => 'هل يجب منع تكرار اسم النشاط التشغيلي؟',
                'help_text' => 'حدد هل يجب منع إنشاء أكثر من نشاط يحمل نفس الاسم حتى تظل القائمة المرجعية واضحة وغير مكررة.',
                'type' => QuestionType::YES_NO,
                'is_required' => true,
                'sort_order' => 4,
                'report_category' => 'rule',
                'target_entity' => 'operational_activity',
                'options' => [],
            ],
            [
                'seed_key' => 'operational_activity.retirement_policy',
                'title' => 'كيف يجب التعامل مع نشاط تشغيلي لم نعد نريد استخدامه؟',
                'help_text' => 'حدد سياسة التعامل مع قيمة Master Data قد تكون مستخدمة في سجلات أو علاقات أخرى لاحقًا، مع الحفاظ على السجل التاريخي.',
                'type' => QuestionType::SINGLE_CHOICE,
                'is_required' => true,
                'sort_order' => 5,
                'report_category' => 'lifecycle_rule',
                'target_entity' => 'operational_activity',
                'options' => [
                    ['label' => 'يتم تحويله إلى غير نشط ولا يسمح بحذفه', 'value' => 'disable_only'],
                    ['label' => 'يسمح بحذفه فقط إذا لم يتم استخدامه سابقًا، وإلا يتم تعطيله', 'value' => 'delete_if_unused_otherwise_disable'],
                    ['label' => 'يسمح بالحذف المنطقي Soft Delete مع الاحتفاظ بالسجل التاريخي', 'value' => 'soft_delete'],
                ],
            ],
            [
                'seed_key' => 'operational_activity.initial_values',
                'title' => 'ما الأنشطة التشغيلية المبدئية التي يجب أن يوفرها النظام؟',
                'help_text' => 'حدد القيم المبدئية التي يجب إضافتها عند تهيئة النظام. هذه القائمة تخص Master Data نفسها فقط، أما طريقة استخدامها وربطها بالعنابر فتُحسم في قسم بيانات العنبر.',
                'type' => QuestionType::MULTI_CHOICE,
                'is_required' => true,
                'sort_order' => 6,
                'report_category' => 'lookup_values',
                'target_entity' => 'operational_activity',
                'options' => [
                    ['label' => 'إنتاج', 'value' => 'production'],
                    ['label' => 'فطام', 'value' => 'weaning'],
                    ['label' => 'تسمين', 'value' => 'fattening'],
                    ['label' => 'حجر / عزل', 'value' => 'quarantine_isolation'],
                    ['label' => 'متعدد الاستخدام', 'value' => 'multi_use'],
                ],
            ],
        ];

        app(QuestionSeederSyncService::class)->sync(
            mainSectionName: 'إدارة البيانات الأساسية',
            sectionName: 'الأنشطة التشغيلية',
            questions: $questions,
            prune: true,
            preserveAnswers: false,
        );
    }
}
