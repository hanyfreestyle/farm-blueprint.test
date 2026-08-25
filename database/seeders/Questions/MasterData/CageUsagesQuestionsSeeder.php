<?php

namespace Database\Seeders\Questions\MasterData;

use App\Enums\Questionnaire\QuestionType;
use App\Services\Questionnaire\QuestionSeederSyncService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CageUsagesQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $questions = [
                [
                    'seed_key' => 'cage_usage.fields',
                    'title' => 'ما البيانات التي يجب أن يحتوي عليها تعريف استخدام القفص؟',
                    'help_text' => 'الاستخدام التشغيلي يصف الغرض الحالي للقفص مثل أنثى إنتاج أو ذكر أو فطام أو تسمين أو حجر/عزل، وهو منفصل عن النوع الفيزيائي للقفص.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 1,
                    'report_category' => 'field',
                    'target_entity' => 'cage_usage',
                    'options' => [
                        ['label' => 'اسم الاستخدام بالعربية والإنجليزية', 'value' => 'name'],
                        ['label' => 'وصف الاستخدام بالعربية والإنجليزية', 'value' => 'description'],
                        ['label' => 'الحالة: نشط / غير نشط', 'value' => 'is_active'],
                        ['label' => 'ترتيب الظهور', 'value' => 'sort_order'],
                        ['label' => 'ملاحظات', 'value' => 'notes'],
                    ],
                ],
                [
                    'seed_key' => 'cage_usage.required_fields',
                    'title' => 'أي من بيانات استخدام القفص يجب أن تكون إلزامية عند إنشائه؟',
                    'help_text' => 'حدد الحد الأدنى من البيانات اللازمة لإنشاء استخدام القفص كقيمة Master Data مستقلة.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 2,
                    'report_category' => 'validation_rule',
                    'target_entity' => 'cage_usage',
                    'options' => [
                        ['label' => 'اسم الاستخدام بالعربية والإنجليزية', 'value' => 'name'],
                        ['label' => 'وصف الاستخدام بالعربية والإنجليزية', 'value' => 'description'],
                        ['label' => 'الحالة', 'value' => 'is_active'],
                        ['label' => 'ترتيب الظهور', 'value' => 'sort_order'],
                        ['label' => 'ملاحظات', 'value' => 'notes'],
                    ],
                ],
                [
                    'seed_key' => 'cage_usage.initial_values',
                    'title' => 'ما استخدامات الأقفاص المبدئية التي يجب أن يوفرها النظام؟',
                    'help_text' => 'القيم مأخوذة من التصور الوظيفي الحالي، وتظل Master Data قابلة للمراجعة والإضافة والتعديل من لوحة التحكم.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 3,
                    'report_category' => 'lookup_values',
                    'target_entity' => 'cage_usage',
                    'options' => [
                        ['label' => 'أنثى إنتاج', 'value' => 'production_female'],
                        ['label' => 'ذكر', 'value' => 'male'],
                        ['label' => 'فطام', 'value' => 'weaning'],
                        ['label' => 'تسمين', 'value' => 'fattening'],
                        ['label' => 'حجر / عزل', 'value' => 'quarantine_isolation'],
                        ['label' => 'استخدام آخر', 'value' => 'other'],
                    ],
                ],
                [
                    'seed_key' => 'cage_usage.unique_name',
                    'title' => 'هل يجب منع تكرار اسم استخدام القفص؟',
                    'help_text' => 'يحدد هذا القرار قاعدة عدم التكرار داخل قائمة استخدامات الأقفاص.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 4,
                    'report_category' => 'validation_rule',
                    'target_entity' => 'cage_usage',
                    'options' => [],
                ],
                [
                    'seed_key' => 'cage_usage.operational_activity_relation',
                    'title' => 'ما العلاقة المطلوبة بين استخدام القفص والأنشطة التشغيلية الموجودة بالفعل في Master Data؟',
                    'help_text' => 'الأنشطة التشغيلية تستخدم على مستويات أعلى مثل العنبر والبطارية، بينما استخدام القفص أكثر تحديدًا. المطلوب منع تكرار مفهوم واحد دون داعٍ مع السماح بفصل الاستخدام عند الحاجة.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 5,
                    'report_category' => 'relationship_rule',
                    'target_entity' => 'cage_usage',
                    'options' => [
                        ['label' => 'استخدام القفص قائمة مستقلة تمامًا عن النشاط التشغيلي', 'value' => 'independent_from_operational_activity'],
                        ['label' => 'كل استخدام قفص يرتبط بنشاط تشغيلي واحد مناسب', 'value' => 'linked_to_one_operational_activity'],
                        ['label' => 'الربط بالنشاط التشغيلي اختياري حسب الاستخدام', 'value' => 'optional_operational_activity_link'],
                    ],
                ],
                [
                    'seed_key' => 'cage_usage.retirement_policy',
                    'title' => 'كيف يجب التعامل مع استخدام قفص لم نعد نريد استعماله؟',
                    'help_text' => 'يجب ألا يؤدي إيقاف استخدام سبق ربطه بأقفاص أو سجلات تاريخية إلى كسر التاريخ السابق.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 6,
                    'report_category' => 'lifecycle_rule',
                    'target_entity' => 'cage_usage',
                    'options' => [
                        ['label' => 'يتم تحويله إلى غير نشط ولا يسمح بحذفه', 'value' => 'disable_only'],
                        ['label' => 'يسمح بحذفه فقط إذا لم يستخدم سابقًا، وإلا يتم تعطيله', 'value' => 'delete_if_unused_otherwise_disable'],
                        ['label' => 'يسمح بالحذف المنطقي Soft Delete مع الحفاظ على التاريخ', 'value' => 'soft_delete'],
                    ],
                ],
            ];

            app(QuestionSeederSyncService::class)->sync(
                mainSectionName: 'إدارة البيانات الأساسية',
                sectionName: 'استخدامات الأقفاص',
                questions: $questions,
                prune: true,
                preserveAnswers: true,
            );
        });
    }
}
