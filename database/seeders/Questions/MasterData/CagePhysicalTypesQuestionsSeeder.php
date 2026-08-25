<?php

namespace Database\Seeders\Questions\MasterData;

use App\Enums\Questionnaire\QuestionType;
use App\Services\Questionnaire\QuestionSeederSyncService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CagePhysicalTypesQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $questions = [
                [
                    'seed_key' => 'cage_physical_type.fields',
                    'title' => 'ما البيانات التي يجب أن يحتوي عليها تعريف النوع الفيزيائي للقفص؟',
                    'help_text' => 'النوع الفيزيائي يصف تصميم القفص ومقاسه وتجهيزاته، وهو منفصل عن الاستخدام التشغيلي الحالي مثل أنثى إنتاج أو فطام أو تسمين أو عزل.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 1,
                    'report_category' => 'field',
                    'target_entity' => 'cage_physical_type',
                    'options' => [
                        ['label' => 'اسم النوع بالعربية والإنجليزية', 'value' => 'name'],
                        ['label' => 'وصف النوع بالعربية والإنجليزية', 'value' => 'description'],
                        ['label' => 'الأبعاد / المقاسات', 'value' => 'dimensions'],
                        ['label' => 'وصف التجهيزات أو الخصائص الفيزيائية', 'value' => 'physical_features'],
                        ['label' => 'السعة الافتراضية إن كانت مرتبطة بالتصميم', 'value' => 'default_capacity'],
                        ['label' => 'الحالة: نشط / غير نشط', 'value' => 'is_active'],
                        ['label' => 'ترتيب الظهور', 'value' => 'sort_order'],
                        ['label' => 'ملاحظات', 'value' => 'notes'],
                    ],
                ],
                [
                    'seed_key' => 'cage_physical_type.required_fields',
                    'title' => 'أي من بيانات النوع الفيزيائي للقفص يجب أن تكون إلزامية عند إنشائه؟',
                    'help_text' => 'حدد الحد الأدنى من البيانات اللازمة لإنشاء النوع الفيزيائي كقيمة Master Data مستقلة.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 2,
                    'report_category' => 'validation_rule',
                    'target_entity' => 'cage_physical_type',
                    'options' => [
                        ['label' => 'اسم النوع بالعربية والإنجليزية', 'value' => 'name'],
                        ['label' => 'وصف النوع بالعربية والإنجليزية', 'value' => 'description'],
                        ['label' => 'الأبعاد / المقاسات', 'value' => 'dimensions'],
                        ['label' => 'وصف التجهيزات أو الخصائص الفيزيائية', 'value' => 'physical_features'],
                        ['label' => 'السعة الافتراضية', 'value' => 'default_capacity'],
                        ['label' => 'الحالة', 'value' => 'is_active'],
                        ['label' => 'ترتيب الظهور', 'value' => 'sort_order'],
                        ['label' => 'ملاحظات', 'value' => 'notes'],
                    ],
                ],
                [
                    'seed_key' => 'cage_physical_type.unique_name',
                    'title' => 'هل يجب منع تكرار اسم النوع الفيزيائي للقفص؟',
                    'help_text' => 'يحدد هذا القرار قاعدة عدم التكرار داخل قائمة الأنواع الفيزيائية للأقفاص.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 3,
                    'report_category' => 'validation_rule',
                    'target_entity' => 'cage_physical_type',
                    'options' => [],
                ],
                [
                    'seed_key' => 'cage_physical_type.attribute_model',
                    'title' => 'كيف يجب تمثيل المواصفات الفيزيائية التي تختلف بين أنواع الأقفاص؟',
                    'help_text' => 'المصدر يطرح المقاسات والتجهيزات كخصائص محتملة تستحق التسجيل. المطلوب تحديد مستوى المرونة دون افتراض قائمة مواصفات غير موجودة في المرجع.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 4,
                    'report_category' => 'architecture_rule',
                    'target_entity' => 'cage_physical_type',
                    'options' => [
                        ['label' => 'حقول أساسية ثابتة مثل الأبعاد والسعة مع وصف إضافي للتجهيزات', 'value' => 'fixed_core_fields_with_features_description'],
                        ['label' => 'مواصفات مرنة Key/Value لكل نوع بجانب الاسم والوصف', 'value' => 'flexible_key_value_attributes'],
                        ['label' => 'اسم ووصف فقط، وتبقى المواصفات التفصيلية داخل تكوين البطارية أو ملاحظات القفص', 'value' => 'name_description_only'],
                    ],
                ],
                [
                    'seed_key' => 'cage_physical_type.retirement_policy',
                    'title' => 'كيف يجب التعامل مع نوع فيزيائي للقفص لم نعد نريد استخدامه؟',
                    'help_text' => 'يجب ألا يؤدي إيقاف نوع مستخدم تاريخيًا إلى كسر سجلات الأقفاص السابقة.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 5,
                    'report_category' => 'lifecycle_rule',
                    'target_entity' => 'cage_physical_type',
                    'options' => [
                        ['label' => 'يتم تحويله إلى غير نشط ولا يسمح بحذفه', 'value' => 'disable_only'],
                        ['label' => 'يسمح بحذفه فقط إذا لم يستخدم سابقًا، وإلا يتم تعطيله', 'value' => 'delete_if_unused_otherwise_disable'],
                        ['label' => 'يسمح بالحذف المنطقي Soft Delete مع الحفاظ على التاريخ', 'value' => 'soft_delete'],
                    ],
                ],
            ];

            app(QuestionSeederSyncService::class)->sync(
                mainSectionName: 'إدارة البيانات الأساسية',
                sectionName: 'أنواع الأقفاص الفيزيائية',
                questions: $questions,
                prune: true,
                preserveAnswers: true,
            );
        });
    }
}
