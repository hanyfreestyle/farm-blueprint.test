<?php

namespace Database\Seeders\Questions\Concerns;

use App\Enums\Questionnaire\QuestionType;
use App\Services\Questionnaire\QuestionSeederSyncService;
use Illuminate\Database\Seeder;

abstract class ReferenceMasterDataQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        $singular = $this->singularLabel();
        $plural = $this->pluralLabel();
        $prefix = $this->seedKeyPrefix();
        $targetEntity = $this->targetEntity();

        $questions = [
            [
                'seed_key' => "{$prefix}.fields",
                'title' => "ما البيانات التي يجب أن يحتوي عليها سجل {$singular}؟",
                'help_text' => "حدد البيانات الأساسية التي يجب أن يدعمها {$singular} باعتباره Master Data مرجعية تستخدم لاحقًا في بيانات العنبر.",
                'type' => QuestionType::MULTI_CHOICE,
                'is_required' => true,
                'sort_order' => 1,
                'report_category' => 'field',
                'target_entity' => $targetEntity,
                'options' => [
                    ['label' => "اسم {$singular} بالعربية والإنجليزية", 'value' => 'name'],
                    ['label' => "وصف {$singular} بالعربية والإنجليزية", 'value' => 'description'],
                    ['label' => 'الحالة: نشط / غير نشط', 'value' => 'is_active'],
                    ['label' => 'ترتيب الظهور', 'value' => 'sort_order'],
                    ['label' => 'ملاحظات', 'value' => 'notes'],
                ],
            ],
            [
                'seed_key' => "{$prefix}.required_fields",
                'title' => "أي من بيانات {$singular} يجب أن تكون إلزامية عند إنشائه؟",
                'help_text' => "حدد الحد الأدنى من البيانات التي لا يسمح النظام بإنشاء {$singular} بدونها.",
                'type' => QuestionType::MULTI_CHOICE,
                'is_required' => true,
                'sort_order' => 2,
                'report_category' => 'rule',
                'target_entity' => $targetEntity,
                'options' => [
                    ['label' => "اسم {$singular} بالعربية والإنجليزية", 'value' => 'name'],
                    ['label' => "وصف {$singular} بالعربية والإنجليزية", 'value' => 'description'],
                    ['label' => 'الحالة', 'value' => 'is_active'],
                    ['label' => 'ترتيب الظهور', 'value' => 'sort_order'],
                    ['label' => 'ملاحظات', 'value' => 'notes'],
                ],
            ],
            [
                'seed_key' => "{$prefix}.management",
                'title' => "كيف يجب إدارة {$plural} داخل النظام؟",
                'help_text' => "حدد هل {$plural} تكون قيمًا ثابتة داخل النظام أم Master Data قابلة للإضافة والتعديل من لوحة التحكم.",
                'type' => QuestionType::SINGLE_CHOICE,
                'is_required' => true,
                'sort_order' => 3,
                'report_category' => 'value_management',
                'target_entity' => $targetEntity,
                'options' => [
                    ['label' => 'قيم ثابتة داخل النظام ولا يمكن إضافة قيم جديدة', 'value' => 'fixed'],
                    ['label' => 'قابلة للإضافة والتعديل من لوحة التحكم', 'value' => 'managed'],
                ],
            ],
            [
                'seed_key' => "{$prefix}.unique_name",
                'title' => "هل يجب منع تكرار اسم {$singular}؟",
                'help_text' => "حدد هل يجب منع إنشاء أكثر من قيمة داخل {$plural} تحمل نفس الاسم.",
                'type' => QuestionType::YES_NO,
                'is_required' => true,
                'sort_order' => 4,
                'report_category' => 'rule',
                'target_entity' => $targetEntity,
                'options' => [],
            ],
            [
                'seed_key' => "{$prefix}.retirement_policy",
                'title' => "كيف يجب التعامل مع {$singular} لم نعد نريد استخدامه؟",
                'help_text' => 'حدد سياسة التعامل مع قيمة Master Data قد تكون مستخدمة تاريخيًا، بحيث لا يؤدي إيقاف استخدامها إلى كسر السجلات السابقة.',
                'type' => QuestionType::SINGLE_CHOICE,
                'is_required' => true,
                'sort_order' => 5,
                'report_category' => 'lifecycle_rule',
                'target_entity' => $targetEntity,
                'options' => [
                    ['label' => 'يتم تحويله إلى غير نشط ولا يسمح بحذفه', 'value' => 'disable_only'],
                    ['label' => 'يسمح بحذفه فقط إذا لم يتم استخدامه سابقًا، وإلا يتم تعطيله', 'value' => 'delete_if_unused_otherwise_disable'],
                    ['label' => 'يسمح بالحذف المنطقي Soft Delete مع الاحتفاظ بالسجل التاريخي', 'value' => 'soft_delete'],
                ],
            ],
            [
                'seed_key' => "{$prefix}.initial_values",
                'title' => "ما {$plural} المبدئية التي يجب أن يوفرها النظام؟",
                'help_text' => "حدد القيم المبدئية التي يتم توفيرها عند تهيئة النظام. هذه قيم بداية قابلة للمراجعة والإضافة والتعديل من لوحة التحكم حسب القرار المعتمد.",
                'type' => QuestionType::MULTI_CHOICE,
                'is_required' => true,
                'sort_order' => 6,
                'report_category' => 'lookup_values',
                'target_entity' => $targetEntity,
                'options' => $this->initialValues(),
            ],
        ];

        app(QuestionSeederSyncService::class)->sync(
            mainSectionName: 'إدارة البيانات الأساسية',
            sectionName: $this->sectionName(),
            questions: $questions,
            prune: true,
            preserveAnswers: false,
        );
    }

    abstract protected function sectionName(): string;

    abstract protected function singularLabel(): string;

    abstract protected function pluralLabel(): string;

    abstract protected function seedKeyPrefix(): string;

    abstract protected function targetEntity(): string;

    /**
     * @return array<int, array{label: string, value: string}>
     */
    abstract protected function initialValues(): array;
}
