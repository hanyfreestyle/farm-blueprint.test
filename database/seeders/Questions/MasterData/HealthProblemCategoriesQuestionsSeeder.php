<?php

namespace Database\Seeders\Questions\MasterData;

use App\Enums\Questionnaire\QuestionType;
use App\Services\Questionnaire\QuestionSeederSyncService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HealthProblemCategoriesQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $questions = [
                [
                    'seed_key' => 'health_problem_category.fields',
                    'title' => 'ما البيانات التي يجب أن يحتوي عليها تعريف تصنيف المشكلة أو الملاحظة الصحية؟',
                    'help_text' => 'الهدف إنشاء قائمة مرجعية بسيطة تساعد في توحيد التسجيل والتحليل دون تحويل الـMVP إلى نظام بيطري كامل. درجة الخطورة والتفاصيل الفعلية تبقى في سجل الحالة الصحية نفسه.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 1,
                    'report_category' => 'field',
                    'target_entity' => 'health_problem_category',
                    'options' => [
                        ['label' => 'اسم التصنيف بالعربية والإنجليزية', 'value' => 'name'],
                        ['label' => 'وصف التصنيف بالعربية والإنجليزية', 'value' => 'description'],
                        ['label' => 'الحالة: نشط / غير نشط', 'value' => 'is_active'],
                        ['label' => 'ترتيب الظهور', 'value' => 'sort_order'],
                        ['label' => 'ملاحظات', 'value' => 'notes'],
                    ],
                ],
                [
                    'seed_key' => 'health_problem_category.required_fields',
                    'title' => 'أي من بيانات تصنيف المشكلة أو الملاحظة الصحية يجب أن تكون إلزامية عند إنشائه؟',
                    'help_text' => 'حدد الحد الأدنى من البيانات اللازمة لإنشاء تصنيف صحي مرجعي قابل للاستخدام في المتابعة الصحية والتقارير.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 2,
                    'report_category' => 'validation_rule',
                    'target_entity' => 'health_problem_category',
                    'options' => [
                        ['label' => 'اسم التصنيف بالعربية والإنجليزية', 'value' => 'name'],
                        ['label' => 'وصف التصنيف بالعربية والإنجليزية', 'value' => 'description'],
                        ['label' => 'الحالة', 'value' => 'is_active'],
                        ['label' => 'ترتيب الظهور', 'value' => 'sort_order'],
                        ['label' => 'ملاحظات', 'value' => 'notes'],
                    ],
                ],
                [
                    'seed_key' => 'health_problem_category.granularity_model',
                    'title' => 'ما مستوى التفصيل المطلوب في تصنيفات المشاكل والملاحظات الصحية داخل الـMVP؟',
                    'help_text' => 'المرجع ينص على الاحتفاظ بالحد الأدنى الذي يؤثر على دورة الإنتاج وعدم بناء نظام إدارة بيطرية كامل، لذلك يجب تحديد مستوى التصنيف المناسب للتشغيل الحالي.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 3,
                    'report_category' => 'architecture_rule',
                    'target_entity' => 'health_problem_category',
                    'options' => [
                        ['label' => 'تصنيفات تشغيلية عامة فقط مع تفاصيل نصية داخل سجل الحالة عند الحاجة', 'value' => 'broad_operational_categories'],
                        ['label' => 'تصنيفات عامة مع إمكانية إضافة تصنيفات أكثر تحديدًا من لوحة التحكم', 'value' => 'broad_with_managed_specific_categories'],
                        ['label' => 'تصنيف تفصيلي متعدد المستويات من البداية', 'value' => 'detailed_hierarchical_classification'],
                    ],
                ],
                [
                    'seed_key' => 'health_problem_category.recording_model',
                    'title' => 'كيف يجب الجمع بين تصنيف المشكلة الصحية والوصف النصي عند تسجيل الحالة الفعلية؟',
                    'help_text' => 'التصور يطلب تسجيل نوع المشكلة أو الملاحظة مع ملاحظات، لذلك نحتاج توحيد القيم القابلة للتحليل مع عدم فقد التفاصيل الخاصة بكل حالة.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 4,
                    'report_category' => 'integration_rule',
                    'target_entity' => 'health_problem_category_usage',
                    'options' => [
                        ['label' => 'اختيار تصنيف واحد إلزامي مع تفاصيل / ملاحظات نصية اختيارية', 'value' => 'required_category_optional_details'],
                        ['label' => 'اختيار تصنيف واحد أو أكثر مع تفاصيل / ملاحظات نصية اختيارية', 'value' => 'multiple_categories_optional_details'],
                        ['label' => 'التصنيف اختياري ويمكن الاعتماد على وصف نصي حر عند عدم وجود تصنيف مناسب', 'value' => 'optional_category_with_free_text_fallback'],
                    ],
                ],
                [
                    'seed_key' => 'health_problem_category.initial_values',
                    'title' => 'ما تصنيفات المشاكل أو الملاحظات الصحية الفعلية التي يجب أن تبدأ بها القائمة؟',
                    'help_text' => 'المرجع يطلب تسجيل نوع المشكلة أو الملاحظة لكنه لا يقدم تصنيفًا طبيًا معتمدًا. اكتب التصنيفات التشغيلية التي تريد اعتمادها مبدئيًا دون اختراع قائمة طبية غير موثقة.',
                    'type' => QuestionType::TEXTAREA,
                    'is_required' => true,
                    'sort_order' => 5,
                    'report_category' => 'lookup_values',
                    'target_entity' => 'health_problem_category',
                    'options' => [],
                ],
                [
                    'seed_key' => 'health_problem_category.unique_name',
                    'title' => 'هل يجب منع تكرار اسم تصنيف المشكلة أو الملاحظة الصحية؟',
                    'help_text' => 'يحدد هذا القرار قاعدة عدم التكرار حتى لا تتجزأ التقارير بين تسميات متكررة لنفس التصنيف.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 6,
                    'report_category' => 'validation_rule',
                    'target_entity' => 'health_problem_category',
                    'options' => [],
                ],
                [
                    'seed_key' => 'health_problem_category.retirement_policy',
                    'title' => 'كيف يجب التعامل مع تصنيف صحي لم نعد نريد استخدامه؟',
                    'help_text' => 'إيقاف التصنيف يجب أن يمنع اختياره مستقبلًا مع بقاء الحالات والتقارير التاريخية مفهومة.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 7,
                    'report_category' => 'lifecycle_rule',
                    'target_entity' => 'health_problem_category',
                    'options' => [
                        ['label' => 'يتم تحويله إلى غير نشط ولا يسمح بحذفه', 'value' => 'disable_only'],
                        ['label' => 'يسمح بحذفه فقط إذا لم يستخدم سابقًا، وإلا يتم تعطيله', 'value' => 'delete_if_unused_otherwise_disable'],
                        ['label' => 'يسمح بالحذف المنطقي Soft Delete مع الحفاظ على التاريخ', 'value' => 'soft_delete'],
                    ],
                ],
            ];

            app(QuestionSeederSyncService::class)->sync(
                mainSectionName: 'إدارة البيانات الأساسية',
                sectionName: 'تصنيفات المشاكل / الملاحظات الصحية',
                questions: $questions,
                prune: true,
                preserveAnswers: true,
            );
        });
    }
}
