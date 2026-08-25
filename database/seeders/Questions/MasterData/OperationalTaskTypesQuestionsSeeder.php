<?php

namespace Database\Seeders\Questions\MasterData;

use App\Enums\Questionnaire\QuestionType;
use App\Services\Questionnaire\QuestionSeederSyncService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OperationalTaskTypesQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $questions = [
                [
                    'seed_key' => 'operational_task_type.fields',
                    'title' => 'ما البيانات التي يجب أن يحتوي عليها تعريف نوع المهمة التشغيلية؟',
                    'help_text' => 'نوع المهمة يعرّف المهمة المرجعية نفسها، بينما قواعد توليدها وموعدها وأولويتها وتنبيهاتها تبقى في Settings، وتنفيذ المهمة الفعلي يبقى في Workflow 4.17.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 1,
                    'report_category' => 'field',
                    'target_entity' => 'operational_task_type',
                    'options' => [
                        ['label' => 'اسم نوع المهمة بالعربية والإنجليزية', 'value' => 'name'],
                        ['label' => 'وصف نوع المهمة بالعربية والإنجليزية', 'value' => 'description'],
                        ['label' => 'تصنيف / مجموعة المهمة', 'value' => 'category'],
                        ['label' => 'الحالة: نشط / غير نشط', 'value' => 'is_active'],
                        ['label' => 'ترتيب الظهور', 'value' => 'sort_order'],
                        ['label' => 'ملاحظات', 'value' => 'notes'],
                    ],
                ],
                [
                    'seed_key' => 'operational_task_type.required_fields',
                    'title' => 'أي من بيانات نوع المهمة التشغيلية يجب أن تكون إلزامية عند إنشائه؟',
                    'help_text' => 'حدد الحد الأدنى من البيانات اللازمة لإنشاء نوع المهمة كقيمة Master Data مستقلة.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 2,
                    'report_category' => 'validation_rule',
                    'target_entity' => 'operational_task_type',
                    'options' => [
                        ['label' => 'اسم نوع المهمة بالعربية والإنجليزية', 'value' => 'name'],
                        ['label' => 'وصف نوع المهمة بالعربية والإنجليزية', 'value' => 'description'],
                        ['label' => 'تصنيف / مجموعة المهمة', 'value' => 'category'],
                        ['label' => 'الحالة', 'value' => 'is_active'],
                        ['label' => 'ترتيب الظهور', 'value' => 'sort_order'],
                        ['label' => 'ملاحظات', 'value' => 'notes'],
                    ],
                ],
                [
                    'seed_key' => 'operational_task_type.categories',
                    'title' => 'ما التصنيفات الرئيسية التي يجب استخدامها لتنظيم أنواع المهام التشغيلية؟',
                    'help_text' => 'التصور الوظيفي يقسم المهام إلى مجموعات بحسب سياقها التشغيلي لتسهيل الإدارة والعرض، دون أن ينقل قواعد التنفيذ من Workflow أو التوقيت من Settings.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 3,
                    'report_category' => 'lookup_values',
                    'target_entity' => 'operational_task_category',
                    'options' => [
                        ['label' => 'مهام القطيع', 'value' => 'herd'],
                        ['label' => 'مهام التلقيح', 'value' => 'mating'],
                        ['label' => 'مهام الحمل', 'value' => 'pregnancy'],
                        ['label' => 'مهام الرضاعة', 'value' => 'lactation'],
                        ['label' => 'مهام الفطام', 'value' => 'weaning'],
                        ['label' => 'مهام النمو والفرز', 'value' => 'growth_sorting'],
                        ['label' => 'مهام التسمين', 'value' => 'fattening'],
                        ['label' => 'مهام المواقع', 'value' => 'housing_sites'],
                        ['label' => 'مهام صحية', 'value' => 'health'],
                    ],
                ],
                [
                    'seed_key' => 'operational_task_type.initial_values',
                    'title' => 'ما أنواع المهام التشغيلية المبدئية التي يجب أن يوفرها النظام؟',
                    'help_text' => 'القائمة مبنية فقط على المهام المذكورة في التصور الحالي. مواعيد مثل أوزان أو تقييمات أعمار محددة لا تثبت هنا؛ التوقيت الفعلي يحدد لاحقًا في Settings.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 4,
                    'report_category' => 'lookup_values',
                    'target_entity' => 'operational_task_type',
                    'options' => [
                        ['label' => 'وزن دوري', 'value' => 'periodic_weight'],
                        ['label' => 'مراجعة الحالة الصحية', 'value' => 'health_status_review'],
                        ['label' => 'مراجعة الحيوانات غير الجاهزة للإنتاج', 'value' => 'not_ready_for_production_review'],
                        ['label' => 'مراجعة المرشحين للقطيع', 'value' => 'replacement_candidate_review'],
                        ['label' => 'تلقيح أنثى مستحقة', 'value' => 'female_due_for_mating'],
                        ['label' => 'إعادة التلقيح', 'value' => 'remating'],
                        ['label' => 'التلقيح الثاني عند اعتماد هذا النظام', 'value' => 'second_mating_if_enabled'],
                        ['label' => 'فحص الحمل / الجس', 'value' => 'pregnancy_check'],
                        ['label' => 'إعادة فحص الحمل', 'value' => 'pregnancy_recheck'],
                        ['label' => 'متابعة الحمل', 'value' => 'pregnancy_followup'],
                        ['label' => 'تجهيز بيت الولادة', 'value' => 'nest_box_preparation'],
                        ['label' => 'متابعة الولادة المتوقعة', 'value' => 'expected_birth_followup'],
                        ['label' => 'متابعة البطن أثناء الرضاعة', 'value' => 'litter_followup'],
                        ['label' => 'وزن المواليد عند اعتماد القياس', 'value' => 'offspring_weight'],
                        ['label' => 'إعادة تلقيح الأم أثناء الرضاعة عند اعتماد النظام', 'value' => 'lactating_mother_remating'],
                        ['label' => 'متابعة حالة رضاعة خاصة', 'value' => 'special_lactation_followup'],
                        ['label' => 'الاستعداد للفطام', 'value' => 'weaning_preparation'],
                        ['label' => 'وزن ما قبل / عند الفطام', 'value' => 'weaning_weight'],
                        ['label' => 'تحديد الجنس', 'value' => 'sex_determination'],
                        ['label' => 'إنشاء أرقام / هويات الأرانب عند الفطام', 'value' => 'animal_identity_creation'],
                        ['label' => 'نقل المفطومين', 'value' => 'weaned_animals_transfer'],
                        ['label' => 'وزن دوري أثناء النمو', 'value' => 'growth_periodic_weight'],
                        ['label' => 'فرز أول', 'value' => 'first_sorting'],
                        ['label' => 'فرز ثان', 'value' => 'second_sorting'],
                        ['label' => 'تقييم مرحلي للنمو', 'value' => 'growth_stage_evaluation'],
                        ['label' => 'إعادة تقييم حالة مؤجلة', 'value' => 'deferred_case_reevaluation'],
                        ['label' => 'مراجعة معدل النمو في التسمين', 'value' => 'fattening_growth_rate_review'],
                        ['label' => 'مراجعة الجاهزية للبيع', 'value' => 'sale_readiness_review'],
                        ['label' => 'نقل أرنب', 'value' => 'animal_transfer'],
                        ['label' => 'إخلاء قفص', 'value' => 'cage_vacating'],
                        ['label' => 'تنظيف / تعقيم قفص', 'value' => 'cage_cleaning_sanitation'],
                        ['label' => 'مراجعة قفص معطل أو تحت الصيانة', 'value' => 'cage_maintenance_review'],
                        ['label' => 'متابعة أرنب تحت الملاحظة', 'value' => 'animal_under_observation_followup'],
                        ['label' => 'مراجعة حالة العزل', 'value' => 'isolation_review'],
                        ['label' => 'إعادة تقييم الحيوان قبل عودته للإنتاج', 'value' => 'pre_return_to_production_reevaluation'],
                    ],
                ],
                [
                    'seed_key' => 'operational_task_type.unique_name',
                    'title' => 'هل يجب منع تكرار اسم نوع المهمة التشغيلية؟',
                    'help_text' => 'يحدد هذا القرار قاعدة عدم التكرار داخل قائمة أنواع المهام.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 5,
                    'report_category' => 'validation_rule',
                    'target_entity' => 'operational_task_type',
                    'options' => [],
                ],
                [
                    'seed_key' => 'operational_task_type.retirement_policy',
                    'title' => 'كيف يجب التعامل مع نوع مهمة لم نعد نريد استخدامه؟',
                    'help_text' => 'إيقاف نوع المهمة يجب أن يمنع استخدامه مستقبلًا دون كسر المهام والسجلات التاريخية التي سبق إنشاؤها منه.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 6,
                    'report_category' => 'lifecycle_rule',
                    'target_entity' => 'operational_task_type',
                    'options' => [
                        ['label' => 'يتم تحويله إلى غير نشط ولا يسمح بحذفه', 'value' => 'disable_only'],
                        ['label' => 'يسمح بحذفه فقط إذا لم يستخدم سابقًا، وإلا يتم تعطيله', 'value' => 'delete_if_unused_otherwise_disable'],
                        ['label' => 'يسمح بالحذف المنطقي Soft Delete مع الحفاظ على التاريخ', 'value' => 'soft_delete'],
                    ],
                ],
            ];

            app(QuestionSeederSyncService::class)->sync(
                mainSectionName: 'إدارة البيانات الأساسية',
                sectionName: 'أنواع المهام التشغيلية',
                questions: $questions,
                prune: true,
                preserveAnswers: true,
            );
        });
    }
}
