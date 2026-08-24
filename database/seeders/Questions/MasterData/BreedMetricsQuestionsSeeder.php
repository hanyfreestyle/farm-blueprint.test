<?php

namespace Database\Seeders\Questions\MasterData;

use App\Enums\Questionnaire\QuestionType;
use App\Services\Questionnaire\QuestionSeederSyncService;
use Illuminate\Database\Seeder;

class BreedMetricsQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        $questions = [
            [
                'seed_key' => 'breed_metric.representation',
                'title' => 'كيف يجب تمثيل مؤشرات السلالات داخل النظام؟',
                'help_text' => 'يحسم هذا السؤال هل تعريفات المؤشرات تكون قيمًا ثابتة داخل الكود / Enum أم Master Data مستقلة يمكن إدارتها من لوحة التحكم. المقصود هنا تعريف المؤشر نفسه، وليس القيمة المرجعية الخاصة بكل سلالة.',
                'type' => QuestionType::SINGLE_CHOICE,
                'is_required' => true,
                'sort_order' => 1,
                'report_category' => 'value_management',
                'target_entity' => 'breed_metric',
                'options' => [
                    ['label' => 'قيم ثابتة داخل الكود / Enum ولا يمكن إضافة مؤشرات جديدة من لوحة التحكم', 'value' => 'fixed_enum'],
                    ['label' => 'Master Data مستقلة قابلة للإضافة والتعديل من لوحة التحكم', 'value' => 'managed_master_data'],
                ],
            ],
            [
                'seed_key' => 'breed_metric.fields',
                'title' => 'ما البيانات التي يجب أن يحتوي عليها تعريف مؤشر السلالة؟',
                'help_text' => 'حدد البيانات التي تصف المؤشر نفسه. وحدة القياس وطريقة القياس واتجاه المقارنة تساعد لاحقًا على فهم القيمة المرجعية ومقارنتها بالأداء الفعلي دون أن تكون القيمة الخاصة بسلالة معينة جزءًا من هذا السجل.',
                'type' => QuestionType::MULTI_CHOICE,
                'is_required' => true,
                'sort_order' => 2,
                'report_category' => 'field',
                'target_entity' => 'breed_metric',
                'options' => [
                    ['label' => 'اسم المؤشر بالعربية والإنجليزية', 'value' => 'name'],
                    ['label' => 'وصف المؤشر بالعربية والإنجليزية', 'value' => 'description'],
                    ['label' => 'وحدة القياس', 'value' => 'measurement_unit'],
                    ['label' => 'تعريف طريقة القياس / الحساب', 'value' => 'measurement_definition'],
                    ['label' => 'اتجاه التقييم والمقارنة', 'value' => 'comparison_direction'],
                    ['label' => 'الحالة: نشط / غير نشط', 'value' => 'is_active'],
                    ['label' => 'ترتيب الظهور', 'value' => 'sort_order'],
                    ['label' => 'ملاحظات', 'value' => 'notes'],
                ],
            ],
            [
                'seed_key' => 'breed_metric.required_fields',
                'title' => 'أي من بيانات تعريف مؤشر السلالة يجب أن تكون إلزامية عند إنشائه؟',
                'help_text' => 'حدد الحد الأدنى من بيانات تعريف المؤشر التي لا يسمح النظام بإنشاء مؤشر بدونها.',
                'type' => QuestionType::MULTI_CHOICE,
                'is_required' => true,
                'sort_order' => 3,
                'report_category' => 'rule',
                'target_entity' => 'breed_metric',
                'options' => [
                    ['label' => 'اسم المؤشر بالعربية والإنجليزية', 'value' => 'name'],
                    ['label' => 'وصف المؤشر بالعربية والإنجليزية', 'value' => 'description'],
                    ['label' => 'وحدة القياس', 'value' => 'measurement_unit'],
                    ['label' => 'تعريف طريقة القياس / الحساب', 'value' => 'measurement_definition'],
                    ['label' => 'اتجاه التقييم والمقارنة', 'value' => 'comparison_direction'],
                    ['label' => 'الحالة', 'value' => 'is_active'],
                    ['label' => 'ترتيب الظهور', 'value' => 'sort_order'],
                    ['label' => 'ملاحظات', 'value' => 'notes'],
                ],
            ],
            [
                'seed_key' => 'breed_metric.unique_name',
                'title' => 'هل يجب منع تكرار اسم مؤشر السلالة؟',
                'help_text' => 'حدد هل يجب منع إنشاء أكثر من تعريف لمؤشر يحمل نفس الاسم حتى لا تظهر مقاييس مرجعية مكررة أو متعارضة.',
                'type' => QuestionType::YES_NO,
                'is_required' => true,
                'sort_order' => 4,
                'report_category' => 'rule',
                'target_entity' => 'breed_metric',
                'options' => [],
            ],
            [
                'seed_key' => 'breed_metric.comparison_direction_required',
                'title' => 'هل يجب أن يحدد كل مؤشر طريقة تفسير الأفضلية عند المقارنة؟',
                'help_text' => 'إذا كانت الإجابة نعم، يحتوي تعريف المؤشر على قاعدة توضح هل القيمة الأعلى أفضل، أو الأقل أفضل، أو أن الأفضل هو الاقتراب من نطاق أو قيمة مستهدفة. هذا لا يحدد القيمة المرجعية لأي سلالة؛ بل يحدد كيفية تفسير المؤشر نفسه.',
                'type' => QuestionType::YES_NO,
                'is_required' => true,
                'sort_order' => 5,
                'report_category' => 'comparison_rule',
                'target_entity' => 'breed_metric',
                'options' => [],
            ],
            [
                'seed_key' => 'breed_metric.retirement_policy',
                'title' => 'كيف يجب التعامل مع مؤشر سلالة لم نعد نريد استخدامه؟',
                'help_text' => 'حدد سياسة التعامل مع مؤشر قد تكون له قيم مرجعية أو سجلات تاريخية مرتبطة به، بحيث لا يؤدي إيقاف استخدامه إلى فقدان معنى البيانات السابقة.',
                'type' => QuestionType::SINGLE_CHOICE,
                'is_required' => true,
                'sort_order' => 6,
                'report_category' => 'lifecycle_rule',
                'target_entity' => 'breed_metric',
                'options' => [
                    ['label' => 'يتم تحويله إلى غير نشط ولا يسمح بحذفه', 'value' => 'disable_only'],
                    ['label' => 'يسمح بحذفه إذا لم يستخدم سابقًا، وإلا يتم تحويله إلى غير نشط', 'value' => 'delete_if_unused_otherwise_disable'],
                    ['label' => 'يسمح بالحذف المنطقي Soft Delete مع الاحتفاظ بالسجل التاريخي', 'value' => 'soft_delete'],
                ],
            ],
            [
                'seed_key' => 'breed_metric.initial_values',
                'title' => 'ما مؤشرات السلالات المبدئية التي يجب أن يوفرها النظام؟',
                'help_text' => 'اختر المقاييس الرقمية المبدئية التي نحتاج تعريفها لمقارنة السلالات. هذه القائمة تعرف أسماء المقاييس فقط؛ القيم المرجعية لكل سلالة وشكل هذه القيم يتم حسمهما لاحقًا في قسم بيانات السلالات، كما أن المعادلات العلمية والحدود الطبيعية لا يتم افتراضها هنا إذا لم تكن قد حُسمت بأسئلة مستقلة.',
                'type' => QuestionType::MULTI_CHOICE,
                'is_required' => true,
                'sort_order' => 7,
                'report_category' => 'lookup_values',
                'target_entity' => 'breed_metric',
                'options' => [
                    ['label' => 'نسبة نجاح الحمل', 'value' => 'pregnancy_success_rate'],
                    ['label' => 'متوسط عدد المواليد لكل ولادة', 'value' => 'average_births_per_litter'],
                    ['label' => 'متوسط عدد الأحياء عند الولادة', 'value' => 'average_live_born_per_litter'],
                    ['label' => 'متوسط عدد المفطومين لكل بطن', 'value' => 'average_weaned_per_litter'],
                    ['label' => 'نسبة البقاء حتى الفطام', 'value' => 'survival_to_weaning_rate'],
                    ['label' => 'متوسط وزن الفطام', 'value' => 'average_weaning_weight'],
                    ['label' => 'متوسط معدل النمو اليومي', 'value' => 'average_daily_gain'],
                    ['label' => 'نسبة الوصول إلى الوزن المستهدف', 'value' => 'target_weight_reach_rate'],
                    ['label' => 'نسبة النفوق', 'value' => 'mortality_rate'],
                ],
            ],
            [
                'seed_key' => 'breed_metric.additional_requirements',
                'title' => 'هل توجد ملاحظات تحتاج إلى تحويلها لاحقًا إلى أسئلة محددة تخص مؤشرات السلالات؟',
                'help_text' => 'هذا سؤال دراسة مفتوح مؤقت لتدوين نقاط تحتاج مراجعة، مثل مؤشر غير موجود أو تعريف يحتاج توضيحًا. أي متطلب حقيقي يظهر هنا يجب تحويله إلى سؤال مستقل قبل تجهيز Final Requirements Input.',
                'type' => QuestionType::TEXTAREA,
                'is_required' => false,
                'sort_order' => 8,
                'report_category' => 'manual_review',
                'target_entity' => 'breed_metric',
                'options' => [],
            ],
        ];

        app(QuestionSeederSyncService::class)->sync(
            mainSectionName: 'إدارة البيانات الأساسية',
            sectionName: 'مؤشرات السلالات',
            questions: $questions,
            prune: true,
            preserveAnswers: true,
        );
    }
}
