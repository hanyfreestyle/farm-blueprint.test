<?php

namespace Database\Seeders\Questions\MasterData;

use App\Enums\Questionnaire\QuestionType;
use App\Services\Questionnaire\QuestionSeederSyncService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BatteryPhysicalTypesQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $questions = [
                [
                    'seed_key' => 'battery_type.fields',
                    'title' => 'ما البيانات التي يجب أن يحتوي عليها تعريف النوع الفيزيائي للبطارية؟',
                    'help_text' => 'النوع الفيزيائي يصف تصميم البطارية كمرجع قابل لإعادة الاستخدام، بينما عدد الجوانب والمستويات والأقفاص والتكوين الفعلي لكل بطارية يظل ضمن بيانات وتكوين البطارية نفسها.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 1,
                    'report_category' => 'field',
                    'target_entity' => 'battery_type',
                    'options' => [
                        ['label' => 'اسم النوع بالعربية والإنجليزية', 'value' => 'name'],
                        ['label' => 'وصف النوع بالعربية والإنجليزية', 'value' => 'description'],
                        ['label' => 'وصف الخصائص / التصميم الفيزيائي العام', 'value' => 'physical_design_description'],
                        ['label' => 'الحالة: نشط / غير نشط', 'value' => 'is_active'],
                        ['label' => 'ترتيب الظهور', 'value' => 'sort_order'],
                        ['label' => 'ملاحظات', 'value' => 'notes'],
                    ],
                ],
                [
                    'seed_key' => 'battery_type.required_fields',
                    'title' => 'أي من بيانات النوع الفيزيائي للبطارية يجب أن تكون إلزامية عند إنشائه؟',
                    'help_text' => 'حدد الحد الأدنى من البيانات اللازمة لإنشاء النوع الفيزيائي كقيمة Master Data مستقلة دون نقل التكوين الهيكلي الفعلي من ملف البطارية إلى هذا التعريف.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 2,
                    'report_category' => 'validation_rule',
                    'target_entity' => 'battery_type',
                    'options' => [
                        ['label' => 'اسم النوع بالعربية والإنجليزية', 'value' => 'name'],
                        ['label' => 'وصف النوع بالعربية والإنجليزية', 'value' => 'description'],
                        ['label' => 'وصف الخصائص / التصميم الفيزيائي العام', 'value' => 'physical_design_description'],
                        ['label' => 'الحالة', 'value' => 'is_active'],
                        ['label' => 'ترتيب الظهور', 'value' => 'sort_order'],
                        ['label' => 'ملاحظات', 'value' => 'notes'],
                    ],
                ],
                [
                    'seed_key' => 'battery_type.unique_name',
                    'title' => 'هل يجب منع تكرار اسم النوع الفيزيائي للبطارية؟',
                    'help_text' => 'يحدد هذا القرار قاعدة عدم التكرار داخل قائمة الأنواع الفيزيائية للبطاريات.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 3,
                    'report_category' => 'validation_rule',
                    'target_entity' => 'battery_type',
                    'options' => [],
                ],
                [
                    'seed_key' => 'battery_type.operational_activity_relation',
                    'title' => 'هل يجب أن يرتبط النوع الفيزيائي للبطارية بالاستخدامات أو الأنشطة التشغيلية المناسبة له؟',
                    'help_text' => 'المرجع يطرح هل يختلف تصميم البطارية حسب الذكور أو الإناث أو التسمين. النشاط الفعلي للبطارية يظل قرارًا مستقلًا، وهذا السؤال يحسم فقط هل النوع الفيزيائي يحمل توصية أو قيدًا مرجعيًا للاستخدام.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 4,
                    'report_category' => 'relationship_rule',
                    'target_entity' => 'battery_type',
                    'options' => [
                        ['label' => 'النوع الفيزيائي مستقل تمامًا عن النشاط التشغيلي', 'value' => 'independent_from_operational_activity'],
                        ['label' => 'يمكن ربط النوع بأنشطة تشغيلية موصى بها دون منع الأنشطة الأخرى', 'value' => 'recommended_operational_activities'],
                        ['label' => 'يرتبط النوع بأنشطة تشغيلية مسموح بها ويستخدم الربط كقيد عند اختيار النشاط', 'value' => 'allowed_operational_activities_constraint'],
                    ],
                ],
                [
                    'seed_key' => 'battery_type.initial_values',
                    'title' => 'ما الأنواع الفيزيائية للبطاريات المستخدمة فعليًا والتي يجب أن تبدأ بها القائمة؟',
                    'help_text' => 'المرجع يسأل صراحة عن أنواع البطاريات المستخدمة فعليًا ولا يحدد قائمة جاهزة. اكتب الأنواع المعروفة لديك حاليًا، ويمكن إضافة أنواع أخرى لاحقًا من لوحة التحكم.',
                    'type' => QuestionType::TEXTAREA,
                    'is_required' => true,
                    'sort_order' => 5,
                    'report_category' => 'lookup_values',
                    'target_entity' => 'battery_type',
                    'options' => [],
                ],
                [
                    'seed_key' => 'battery_type.retirement_policy',
                    'title' => 'كيف يجب التعامل مع نوع فيزيائي للبطارية لم نعد نريد استخدامه؟',
                    'help_text' => 'يجب ألا يؤدي إيقاف نوع سبق ربطه ببطاريات إلى كسر السجلات أو تغيير معنى التاريخ السابق.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 6,
                    'report_category' => 'lifecycle_rule',
                    'target_entity' => 'battery_type',
                    'options' => [
                        ['label' => 'يتم تحويله إلى غير نشط ولا يسمح بحذفه', 'value' => 'disable_only'],
                        ['label' => 'يسمح بحذفه فقط إذا لم يستخدم سابقًا، وإلا يتم تعطيله', 'value' => 'delete_if_unused_otherwise_disable'],
                        ['label' => 'يسمح بالحذف المنطقي Soft Delete مع الحفاظ على التاريخ', 'value' => 'soft_delete'],
                    ],
                ],
            ];

            app(QuestionSeederSyncService::class)->sync(
                mainSectionName: 'إدارة البيانات الأساسية',
                sectionName: 'الأنواع الفيزيائية للبطاريات',
                questions: $questions,
                prune: true,
                preserveAnswers: true,
            );
        });
    }
}
