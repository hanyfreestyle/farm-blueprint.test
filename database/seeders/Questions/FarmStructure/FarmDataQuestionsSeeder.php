<?php

namespace Database\Seeders\Questions\FarmStructure;

use App\Enums\Questionnaire\QuestionType;
use App\Models\QuestionnaireQuestion;
use App\Models\QuestionnaireSection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class FarmDataQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $mainSection = QuestionnaireSection::query()
                ->whereNull('parent_id')
                ->where('name', 'إدارة البيانات الأساسية')
                ->first();

            if (! $mainSection) {
                throw new RuntimeException(
                    'Main section "إدارة البيانات الأساسية" was not found. Run QuestionnaireSectionSeeder first.'
                );
            }

            $section = QuestionnaireSection::query()
                ->where('parent_id', $mainSection->id)
                ->where('name', 'بيانات المزرعة')
                ->first();

            if (! $section) {
                throw new RuntimeException(
                    'Subsection "بيانات المزرعة" was not found. Run QuestionnaireSectionSeeder first.'
                );
            }

            $questions = [
                [
                    'title' => 'ما البيانات التي يجب أن يحتوي عليها ملف المزرعة؟',
                    'help_text' => 'حدد البيانات التي يجب أن يدعمها ملف المزرعة. بعض الاختيارات قد تتحول لاحقًا إلى علاقات مستقلة وليست أعمدة مباشرة داخل جدول المزارع.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 1,
                    'report_category' => 'field',
                    'target_entity' => 'farm',
                    'options' => [
                        ['label' => 'اسم المزرعة', 'value' => 'name'],
                        ['label' => 'كود المزرعة', 'value' => 'code'],
                        ['label' => 'أنشطة المزرعة', 'value' => 'activities'],
                        ['label' => 'أرقام الهاتف', 'value' => 'phones'],
                        ['label' => 'أرقام واتساب', 'value' => 'whatsapps'],
                        ['label' => 'المحافظة', 'value' => 'governorate'],
                        ['label' => 'المدينة', 'value' => 'city'],
                        ['label' => 'العنوان', 'value' => 'address'],
                        ['label' => 'الموقع على الخريطة', 'value' => 'map_location'],
                        ['label' => 'تاريخ بدء التشغيل', 'value' => 'started_at'],
                        ['label' => 'الحالة التشغيلية', 'value' => 'status'],
                        ['label' => 'ملاحظات عامة', 'value' => 'notes'],
                    ],
                ],
                [
                    'title' => 'أي من بيانات ملف المزرعة يجب أن تكون إلزامية عند إنشاء مزرعة جديدة؟',
                    'help_text' => 'حدد الحد الأدنى من البيانات التي لا يسمح النظام بإنشاء المزرعة بدونها.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 2,
                    'report_category' => 'rule',
                    'target_entity' => 'farm',
                    'options' => [
                        ['label' => 'اسم المزرعة', 'value' => 'name'],
                        ['label' => 'كود المزرعة', 'value' => 'code'],
                        ['label' => 'نشاط واحد على الأقل', 'value' => 'activities'],
                        ['label' => 'رقم هاتف واحد على الأقل', 'value' => 'phones'],
                        ['label' => 'رقم واتساب واحد على الأقل', 'value' => 'whatsapps'],
                        ['label' => 'المحافظة', 'value' => 'governorate'],
                        ['label' => 'المدينة', 'value' => 'city'],
                        ['label' => 'العنوان', 'value' => 'address'],
                        ['label' => 'الموقع على الخريطة', 'value' => 'map_location'],
                        ['label' => 'تاريخ بدء التشغيل', 'value' => 'started_at'],
                        ['label' => 'الحالة التشغيلية', 'value' => 'status'],
                    ],
                ],
                [
                    'title' => 'كيف يجب التعامل مع كود المزرعة؟',
                    'help_text' => 'حدد طريقة إنشاء الكود واستخدامه كمعرف تشغيلي للمزرعة بجانب الـID الداخلي.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 3,
                    'report_category' => 'rule',
                    'target_entity' => 'farm',
                    'options' => [
                        ['label' => 'المستخدم يدخله يدويًا', 'value' => 'manual'],
                        ['label' => 'النظام يولده تلقائيًا', 'value' => 'automatic'],
                        ['label' => 'النظام يولده تلقائيًا مع إمكانية تعديله', 'value' => 'automatic_editable'],
                        ['label' => 'لا نحتاج كودًا للمزرعة', 'value' => 'not_required'],
                    ],
                ],
                [
                    'title' => 'هل يمكن ربط المزرعة بأكثر من نشاط في نفس الوقت؟',
                    'help_text' => 'أنشطة المزرعة أصبحت بيانات مرجعية مستقلة، وهذا السؤال يحدد هل علاقة المزرعة بالأنشطة تسمح بنشاط واحد أم أكثر.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 4,
                    'report_category' => 'relationship',
                    'target_entity' => 'farm',
                    'options' => [],
                ],
                [
                    'title' => 'ما قاعدة عدم التكرار المطلوبة لبيانات المزرعة؟',
                    'help_text' => 'حدد البيانات التي يجب اعتبارها فريدة بين المزارع حتى نحدد قيود Unique المناسبة في قاعدة البيانات.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 5,
                    'report_category' => 'rule',
                    'target_entity' => 'farm',
                    'options' => [
                        ['label' => 'كود المزرعة فقط يجب ألا يتكرر', 'value' => 'code_only'],
                        ['label' => 'اسم المزرعة فقط يجب ألا يتكرر', 'value' => 'name_only'],
                        ['label' => 'اسم المزرعة وكودها يجب ألا يتكررا', 'value' => 'name_and_code'],
                        ['label' => 'لا نحتاج شرط Unique إضافيًا بخلاف الـID', 'value' => 'none'],
                    ],
                ],
                [
                    'title' => 'ما الحالات التشغيلية التي نحتاجها للمزرعة؟',
                    'help_text' => 'التصور المرجعي يتضمن حالتي نشطة ومتوقفة. حدد الحالات التي يجب أن يمثلها النظام.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 6,
                    'report_category' => 'field',
                    'target_entity' => 'farm',
                    'options' => [
                        ['label' => 'نشطة', 'value' => 'active'],
                        ['label' => 'متوقفة', 'value' => 'stopped'],
                        ['label' => 'أخرى', 'value' => 'other'],
                    ],
                ],
                [
                    'title' => 'عندما تكون المزرعة متوقفة، ما التأثير المطلوب على التشغيل؟',
                    'help_text' => 'حدد المعنى التشغيلي لحالة المزرعة المتوقفة حتى لا تظل الحالة مجرد وصف بدون Business Rule واضح.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 7,
                    'report_category' => 'rule',
                    'target_entity' => 'farm',
                    'options' => [
                        ['label' => 'الحالة للمعلومة فقط ولا تمنع العمليات', 'value' => 'informational_only'],
                        ['label' => 'منع إنشاء عمليات وحركات تشغيلية جديدة مع استمرار العرض والتقارير', 'value' => 'block_new_operations'],
                        ['label' => 'منع التعديلات التشغيلية بالكامل باستثناء إعادة التفعيل والإجراءات الإدارية', 'value' => 'freeze_operations'],
                    ],
                ],
                [
                    'title' => 'عند توقف المزرعة، كيف يتم التعامل مع العنابر والبطاريات والأقفاص التابعة لها؟',
                    'help_text' => 'حدد هل توقف المزرعة يغير حالات العناصر التابعة فعليًا أم يؤثر فقط على إتاحتها التشغيلية.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 8,
                    'report_category' => 'rule',
                    'target_entity' => 'farm',
                    'options' => [
                        ['label' => 'تظل حالاتها الأصلية كما هي ولكن تعتبر غير متاحة تشغيليًا بسبب توقف المزرعة', 'value' => 'preserve_status_block_usage'],
                        ['label' => 'يتم تحويل العناصر التابعة تلقائيًا إلى حالة متوقفة', 'value' => 'cascade_stopped_status'],
                        ['label' => 'لا يوجد تأثير تلقائي ويتم التعامل مع كل عنصر بصورة مستقلة', 'value' => 'independent_status'],
                    ],
                ],
                [
                    'title' => 'كيف يجب تطبيق إعدادات التشغيل والإنتاج على المزارع؟',
                    'help_text' => 'هذا القرار سيحدد طريقة بناء قسم إعدادات التشغيل القادم وعلاقة الإعدادات بكل مزرعة.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 9,
                    'report_category' => 'rule',
                    'target_entity' => 'farm',
                    'options' => [
                        ['label' => 'إعدادات مستقلة بالكامل لكل مزرعة', 'value' => 'per_farm'],
                        ['label' => 'إعدادات عامة مشتركة بين جميع المزارع', 'value' => 'global'],
                        ['label' => 'إعدادات عامة افتراضية مع إمكانية تخصيصها لكل مزرعة', 'value' => 'global_with_farm_overrides'],
                    ],
                ],
                [
                    'title' => 'متى يجب أن يكون دعم تشغيل أكثر من مزرعة متاحًا؟',
                    'help_text' => 'التصور يسمح بتعدد المزارع. المطلوب هنا تحديد نطاق الإصدار الأول مع الحفاظ على قابلية التوسع.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 10,
                    'report_category' => 'relationship',
                    'target_entity' => 'farm',
                    'options' => [
                        ['label' => 'من الإصدار الأول', 'value' => 'from_first_release'],
                        ['label' => 'مزرعة واحدة حاليًا مع بناء قاعدة البيانات والبنية من البداية لدعم أكثر من مزرعة مستقبلًا', 'value' => 'single_now_multi_ready'],
                    ],
                ],
                [
                    'title' => 'كيف يجب التعامل مع حذف مزرعة سبق أن ارتبطت ببيانات أو حركات تشغيلية؟',
                    'help_text' => 'حدد سياسة الحذف المطلوبة بما يحافظ على سلامة العلاقات والسجل التاريخي.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 11,
                    'report_category' => 'rule',
                    'target_entity' => 'farm',
                    'options' => [
                        ['label' => 'لا يسمح بحذفها نهائيًا ويتم إيقافها فقط', 'value' => 'disable_only'],
                        ['label' => 'يسمح بالحذف المنطقي Soft Delete مع الاحتفاظ بالبيانات', 'value' => 'soft_delete'],
                        ['label' => 'يسمح بالحذف فقط إذا لم توجد بيانات تشغيلية مرتبطة بها', 'value' => 'delete_if_unused'],
                    ],
                ],
                [
                    'title' => 'هل توجد بيانات أو قواعد أخرى خاصة بملف المزرعة ترى ضرورة إضافتها؟',
                    'help_text' => 'اكتب أي نقطة مهمة لم تغطها الأسئلة السابقة حتى تتم مراجعتها قبل اعتماد Blueprint بيانات المزرعة.',
                    'type' => QuestionType::TEXTAREA,
                    'is_required' => false,
                    'sort_order' => 12,
                    'report_category' => 'general',
                    'target_entity' => 'farm',
                    'options' => [],
                ],
            ];

            $desiredBySortOrder = collect($questions)->keyBy('sort_order');
            $existingQuestions = QuestionnaireQuestion::query()
                ->with(['options', 'answer'])
                ->where('section_id', $section->id)
                ->get()
                ->keyBy('sort_order');

            /*
             * Safety preflight:
             * Never reinterpret an existing answer as an answer to a newly
             * redesigned question. If an answered question would change in
             * title, type, option values, or disappear, abort the whole Seeder.
             */
            foreach ($existingQuestions as $sortOrder => $existingQuestion) {
                if (! $existingQuestion->answer) {
                    continue;
                }

                $desiredQuestion = $desiredBySortOrder->get($sortOrder);

                if (! $desiredQuestion) {
                    throw new RuntimeException(
                        "Farm questions redesign stopped safely: answered question #{$sortOrder} would be removed. Preserve/export the existing answers before replacing the question set."
                    );
                }

                $existingOptionValues = $existingQuestion->options
                    ->pluck('value')
                    ->map(fn ($value): string => (string) $value)
                    ->sort()
                    ->values()
                    ->all();

                $desiredOptionValues = collect($desiredQuestion['options'] ?? [])
                    ->pluck('value')
                    ->map(fn ($value): string => (string) $value)
                    ->sort()
                    ->values()
                    ->all();

                if (
                    $existingQuestion->title !== $desiredQuestion['title']
                    || $existingQuestion->type !== $desiredQuestion['type']
                    || $existingOptionValues !== $desiredOptionValues
                ) {
                    throw new RuntimeException(
                        "Farm questions redesign stopped safely: answered question #{$sortOrder} would change meaning or available values. Existing answers were not modified."
                    );
                }
            }

            $desiredSortOrders = collect($questions)->pluck('sort_order')->all();

            // Remove obsolete draft questions only when they have no answers.
            $obsoleteQuestions = QuestionnaireQuestion::query()
                ->with('answer')
                ->where('section_id', $section->id)
                ->whereNotIn('sort_order', $desiredSortOrders)
                ->get();

            foreach ($obsoleteQuestions as $obsoleteQuestion) {
                if ($obsoleteQuestion->answer) {
                    throw new RuntimeException(
                        "Farm questions redesign stopped safely: answered question #{$obsoleteQuestion->sort_order} cannot be removed automatically."
                    );
                }

                $obsoleteQuestion->options()->delete();
                $obsoleteQuestion->delete();
            }

            foreach ($questions as $questionData) {
                $options = $questionData['options'] ?? [];
                unset($questionData['options']);

                $questionData['depends_on_question_id'] = null;
                $questionData['dependency_operator'] = null;
                $questionData['dependency_value'] = null;

                $question = QuestionnaireQuestion::query()->updateOrCreate(
                    [
                        'section_id' => $section->id,
                        'sort_order' => $questionData['sort_order'],
                    ],
                    $questionData,
                );

                $desiredOptionValues = collect($options)->pluck('value')->all();

                if (! $question->answer()->exists()) {
                    $obsoleteOptionsQuery = $question->options();

                    if ($desiredOptionValues !== []) {
                        $obsoleteOptionsQuery->whereNotIn('value', $desiredOptionValues);
                    }

                    $obsoleteOptionsQuery->delete();
                }

                foreach ($options as $index => $option) {
                    $question->options()->updateOrCreate(
                        ['value' => $option['value']],
                        [
                            'label' => $option['label'],
                            'sort_order' => $index + 1,
                        ],
                    );
                }
            }
        });
    }
}
