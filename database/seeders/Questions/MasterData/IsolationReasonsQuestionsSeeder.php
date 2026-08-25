<?php

namespace Database\Seeders\Questions\MasterData;

use App\Enums\Questionnaire\QuestionType;
use App\Services\Questionnaire\QuestionSeederSyncService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IsolationReasonsQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $questions = [
                [
                    'seed_key' => 'isolation_reason.fields',
                    'title' => 'ما البيانات التي يجب أن يحتوي عليها سجل سبب العزل؟',
                    'help_text' => 'سبب العزل قيمة مرجعية تستخدم عند تسجيل العزل الفعلي في Workflow، مع بقاء تفاصيل الحالة الصحية أو الاستقبال في سجلاتها الأصلية.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 1,
                    'report_category' => 'field',
                    'target_entity' => 'isolation_reason',
                    'options' => [
                        ['label' => 'اسم السبب بالعربية والإنجليزية', 'value' => 'name'],
                        ['label' => 'وصف السبب بالعربية والإنجليزية', 'value' => 'description'],
                        ['label' => 'نطاق / سياق استخدام السبب', 'value' => 'scope'],
                        ['label' => 'الحالة: نشط / غير نشط', 'value' => 'is_active'],
                        ['label' => 'ترتيب الظهور', 'value' => 'sort_order'],
                        ['label' => 'ملاحظات', 'value' => 'notes'],
                    ],
                ],
                [
                    'seed_key' => 'isolation_reason.required_fields',
                    'title' => 'أي من بيانات سبب العزل يجب أن تكون إلزامية عند إنشائه؟',
                    'help_text' => 'حدد الحد الأدنى من البيانات اللازمة لإنشاء سبب عزل مرجعي قابل للاستخدام في السجلات التشغيلية.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 2,
                    'report_category' => 'validation_rule',
                    'target_entity' => 'isolation_reason',
                    'options' => [
                        ['label' => 'اسم السبب بالعربية والإنجليزية', 'value' => 'name'],
                        ['label' => 'وصف السبب بالعربية والإنجليزية', 'value' => 'description'],
                        ['label' => 'نطاق / سياق استخدام السبب', 'value' => 'scope'],
                        ['label' => 'الحالة', 'value' => 'is_active'],
                        ['label' => 'ترتيب الظهور', 'value' => 'sort_order'],
                        ['label' => 'ملاحظات', 'value' => 'notes'],
                    ],
                ],
                [
                    'seed_key' => 'isolation_reason.scopes',
                    'title' => 'في أي سياقات يجب أن يستطيع النظام استخدام أسباب العزل؟',
                    'help_text' => 'التصور الحالي يدعم العزل نتيجة حالة صحية، كما يدعم مرحلة حجر / ملاحظة للحيوانات الواردة عند الحاجة. المطلوب تمييز السياق دون إنشاء واقعة عزل داخل Master Data.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 3,
                    'report_category' => 'lookup_values',
                    'target_entity' => 'isolation_reason_scope',
                    'options' => [
                        ['label' => 'عزل مرتبط بحالة أو ملاحظة صحية', 'value' => 'health_isolation'],
                        ['label' => 'حجر / ملاحظة أثناء استقبال حيوان من خارج المزرعة', 'value' => 'intake_quarantine_observation'],
                        ['label' => 'سبب عزل آخر موثق', 'value' => 'other'],
                    ],
                ],
                [
                    'seed_key' => 'isolation_reason.health_problem_relation',
                    'title' => 'عند استخدام سبب عزل صحي، ما العلاقة المطلوبة مع تصنيف المشكلة أو الملاحظة الصحية؟',
                    'help_text' => 'نوع المشكلة الصحية وسبب العزل مفهومان مختلفان؛ قد تكون المشكلة هي ما تمت ملاحظته بينما سبب العزل يشرح لماذا تقرر فصل الحيوان. المطلوب تحديد هل نحتاج ربطًا بين القائمتين.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 4,
                    'report_category' => 'relationship_rule',
                    'target_entity' => 'isolation_reason',
                    'options' => [
                        ['label' => 'يبقى سبب العزل مستقلًا عن تصنيف المشكلة الصحية', 'value' => 'independent'],
                        ['label' => 'يمكن ربط سبب العزل اختياريًا بتصنيف أو أكثر من المشاكل الصحية', 'value' => 'optional_health_problem_links'],
                        ['label' => 'سبب العزل الصحي يجب أن يرتبط بتصنيف مشكلة صحية واحد على الأقل', 'value' => 'health_reason_requires_problem_category'],
                    ],
                ],
                [
                    'seed_key' => 'isolation_reason.initial_values',
                    'title' => 'ما أسباب العزل الفعلية التي يجب أن تبدأ بها القائمة؟',
                    'help_text' => 'المرجع يثبت ضرورة تسجيل سبب العزل لكنه لا يحدد قائمة أسباب معتمدة. اكتب الأسباب المستخدمة فعليًا أو التي تريد اعتمادها مبدئيًا دون افتراض تصنيف طبي غير موثق.',
                    'type' => QuestionType::TEXTAREA,
                    'is_required' => true,
                    'sort_order' => 5,
                    'report_category' => 'lookup_values',
                    'target_entity' => 'isolation_reason',
                    'options' => [],
                ],
                [
                    'seed_key' => 'isolation_reason.unique_name',
                    'title' => 'هل يجب منع تكرار اسم سبب العزل؟',
                    'help_text' => 'يحدد هذا القرار قاعدة عدم التكرار داخل قائمة أسباب العزل.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 6,
                    'report_category' => 'validation_rule',
                    'target_entity' => 'isolation_reason',
                    'options' => [],
                ],
                [
                    'seed_key' => 'isolation_reason.retirement_policy',
                    'title' => 'كيف يجب التعامل مع سبب عزل لم نعد نريد استخدامه؟',
                    'help_text' => 'إيقاف السبب يجب أن يمنع اختياره مستقبلًا مع الحفاظ على معنى سجلات العزل السابقة.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 7,
                    'report_category' => 'lifecycle_rule',
                    'target_entity' => 'isolation_reason',
                    'options' => [
                        ['label' => 'يتم تحويله إلى غير نشط ولا يسمح بحذفه', 'value' => 'disable_only'],
                        ['label' => 'يسمح بحذفه فقط إذا لم يستخدم سابقًا، وإلا يتم تعطيله', 'value' => 'delete_if_unused_otherwise_disable'],
                        ['label' => 'يسمح بالحذف المنطقي Soft Delete مع الحفاظ على التاريخ', 'value' => 'soft_delete'],
                    ],
                ],
            ];

            app(QuestionSeederSyncService::class)->sync(
                mainSectionName: 'إدارة البيانات الأساسية',
                sectionName: 'أسباب العزل',
                questions: $questions,
                prune: true,
                preserveAnswers: true,
            );
        });
    }
}
