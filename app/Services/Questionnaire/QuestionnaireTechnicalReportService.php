<?php

namespace App\Services\Questionnaire;

use App\Models\QuestionnaireQuestion;
use App\Models\QuestionnaireQuestionOption;
use App\Models\QuestionnaireSection;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class QuestionnaireTechnicalReportService
{
    public const REPORT_FILENAME = 'rabbit-farm-technical-specification.md';

    public function __construct(
        private readonly QuestionnaireFrontendService $frontendService,
    ) {
    }

    public function buildReport(): string
    {
        return $this->renderMarkdown($this->buildReportData());
    }

    public function buildReportData(): array
    {
        $mainSections = $this->loadMainSections();

        $totals = [
            'main_sections' => $mainSections->count(),
            'subsections' => 0,
            'questions' => 0,
            'applicable_questions' => 0,
            'answered_questions' => 0,
            'unanswered_questions' => 0,
            'questions_requiring_review' => 0,
        ];

        $global = $this->emptyGlobalAggregation();
        $sectionEntries = [];

        foreach ($mainSections as $mainIndex => $mainSection) {
            $subsectionEntries = [];
            $totals['subsections'] += $mainSection->children->count();

            foreach ($mainSection->children as $subIndex => $subsection) {
                $subsectionData = $this->buildSubsectionData($mainSection, $subsection, $mainIndex + 1, $subIndex + 1);
                $subsectionEntries[] = $subsectionData;

                $totals['questions'] += $subsectionData['question_count'];
                $totals['applicable_questions'] += $subsectionData['applicable_question_count'];
                $totals['answered_questions'] += $subsectionData['answered_count'];
                $totals['unanswered_questions'] += count($subsectionData['unanswered_questions']);
                $totals['questions_requiring_review'] += count($subsectionData['needs_review_items']);

                $global = $this->mergeGlobalAggregation($global, $subsectionData['global']);
            }

            $sectionEntries[] = [
                'number' => (string) ($mainIndex + 1),
                'name' => $mainSection->name,
                'description' => $mainSection->description,
                'subsections' => $subsectionEntries,
            ];
        }

        $totals['completion_percentage'] = $totals['applicable_questions'] > 0
            ? (int) round(($totals['answered_questions'] / $totals['applicable_questions']) * 100)
            : 0;

        return [
            'title' => 'المواصفات الفنية لنظام إدارة مزرعة الأرانب',
            'generated_at' => Carbon::now(),
            'filename' => self::REPORT_FILENAME,
            'stats' => $totals,
            'main_sections' => $sectionEntries,
            'global' => $this->normalizeGlobalAggregation($global),
        ];
    }

    public function renderMarkdown(array $reportData): string
    {
        $lines = [
            '# ' . $reportData['title'],
            '',
            '## معلومات التقرير',
            '',
            '| البند | القيمة |',
            '|---|---|',
            '| اسم الملف | `' . $reportData['filename'] . '` |',
            '| وقت التوليد | ' . $reportData['generated_at']->format('Y-m-d H:i:s') . ' |',
            '',
            '## الملخص التنفيذي',
            '',
            'هذا المستند يمثل المواصفات الفنية الرئيسية الحالية لنظام إدارة مزرعة الأرانب، ويعكس حالة الاستبيان كما هي في قاعدة البيانات وقت التوليد دون أي استنتاجات غير معتمدة.',
            '',
            '## حالة الدراسة',
            '',
            '| المؤشر | القيمة |',
            '|---|---|',
            '| إجمالي الأقسام الرئيسية | ' . $reportData['stats']['main_sections'] . ' |',
            '| إجمالي الأقسام الفرعية | ' . $reportData['stats']['subsections'] . ' |',
            '| إجمالي الأسئلة | ' . $reportData['stats']['questions'] . ' |',
            '| الأسئلة المجاب عنها | ' . $reportData['stats']['answered_questions'] . ' |',
            '| الأسئلة غير المحسومة | ' . $reportData['stats']['unanswered_questions'] . ' |',
            '| الأسئلة التي تحتاج مراجعة | ' . $reportData['stats']['questions_requiring_review'] . ' |',
            '| نسبة الإنجاز الحالية | ' . $reportData['stats']['completion_percentage'] . '% |',
            '',
        ];

        foreach ($reportData['main_sections'] as $mainSection) {
            $lines[] = '# ' . $mainSection['number'] . '. ' . $mainSection['name'];
            $lines[] = '';

            if (filled($mainSection['description'])) {
                $lines[] = '**وصف القسم**';
                $lines[] = '';
                $lines[] = trim((string) $mainSection['description']);
                $lines[] = '';
            }

            foreach ($mainSection['subsections'] as $subsection) {
                $lines[] = '## ' . $subsection['number'] . ' ' . $subsection['name'];
                $lines[] = '';

                if (filled($subsection['description'])) {
                    $lines[] = '**وصف القسم**';
                    $lines[] = '';
                    $lines[] = trim((string) $subsection['description']);
                    $lines[] = '';
                }

                $lines[] = '**الحالة الحالية**';
                $lines[] = '';
                $lines[] = '- الحالة: ' . $subsection['status_label'];
                $lines[] = '- عدد الأسئلة: ' . $subsection['question_count'];
                $lines[] = '- عدد الأسئلة القابلة حاليًا: ' . $subsection['applicable_question_count'];
                $lines[] = '- المجاب عنه: ' . $subsection['answered_count'];
                $lines[] = '';

                if ($subsection['question_count'] === 0) {
                    $lines[] = 'لم تتم إضافة أسئلة المراجعة لهذا القسم بعد.';
                    $lines[] = '';
                    continue;
                }

                $lines = [...$lines, ...$this->renderDecisionEntries($subsection['approved_decisions'])];
                $lines = [...$lines, ...$this->renderFieldEntries($subsection['fields'])];
                $lines = [...$lines, ...$this->renderEnumEntries($subsection['enums'])];
                $lines = [...$lines, ...$this->renderLookupEntries($subsection['lookups'])];
                $lines = [...$lines, ...$this->renderSimpleEntries('### العلاقات', $subsection['relationships'])];
                $lines = [...$lines, ...$this->renderSimpleEntries('### قواعد العمل', $subsection['business_rules'])];
                $lines = [...$lines, ...$this->renderSimpleEntries('### متطلبات الواجهة', $subsection['ui_requirements'])];
                $lines = [...$lines, ...$this->renderNeedsReviewEntries($subsection['needs_review_items'])];
                $lines = [...$lines, ...$this->renderUnansweredEntries($subsection['unanswered_questions'])];
            }
        }

        $global = $reportData['global'];

        $lines = [...$lines, ...$this->renderGlobalEntities($global['entities'])];
        $lines = [...$lines, ...$this->renderGlobalFields($global['fields'])];
        $lines = [...$lines, ...$this->renderGlobalEnums($global['enums'])];
        $lines = [...$lines, ...$this->renderGlobalLookups($global['lookups'])];
        $lines = [...$lines, ...$this->renderSimpleEntries('# العلاقات', $global['relationships'])];
        $lines = [...$lines, ...$this->renderSimpleEntries('# قواعد العمل', $global['business_rules'])];
        $lines = [...$lines, ...$this->renderSimpleEntries('# متطلبات الواجهة', $global['ui_requirements'])];
        $lines = [...$lines, ...$this->renderSimpleEntries('# التنبيهات', $global['alerts'], 'لا توجد تنبيهات معتمدة حاليًا.')];
        $lines = [...$lines, ...$this->renderSimpleEntries('# التقارير', $global['reports'], 'لم يتم اعتماد تقارير تنفيذية تفصيلية بعد.')];
        $lines = [...$lines, ...$this->renderNeedsReviewEntries($global['needs_review'], '# نقاط تحتاج مراجعة')];
        $lines = [...$lines, ...$this->renderUnansweredEntries($global['unanswered'], '# الأسئلة غير المحسومة')];
        $lines = [...$lines, ...$this->renderDecisionLog($global['decision_log'])];

        return implode("\n", $lines) . "\n";
    }

    /**
     * @return Collection<int, QuestionnaireSection>
     */
    private function loadMainSections(): Collection
    {
        return QuestionnaireSection::query()
            ->mainSections()
            ->select(['id', 'parent_id', 'name', 'description', 'sort_order'])
            ->with([
                'children' => fn ($query) => $query
                    ->select(['id', 'parent_id', 'name', 'description', 'sort_order'])
                    ->with([
                        'questions' => fn ($questionQuery) => $questionQuery
                            ->select([
                                'id',
                                'section_id',
                                'title',
                                'help_text',
                                'type',
                                'is_required',
                                'sort_order',
                                'depends_on_question_id',
                                'dependency_operator',
                                'dependency_value',
                                'report_category',
                                'target_entity',
                            ])
                            ->with([
                                'options' => fn ($optionQuery) => $optionQuery
                                    ->select(['id', 'question_id', 'label', 'value', 'sort_order'])
                                    ->orderBy('sort_order')
                                    ->orderBy('id'),
                                'answer:id,question_id,value,notes,needs_review,review_status,reviewed_at',
                            ])
                            ->orderBy('sort_order')
                            ->orderBy('id'),
                    ])
                    ->orderBy('sort_order')
                    ->orderBy('id'),
            ])
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }

    private function buildSubsectionData(
        QuestionnaireSection $mainSection,
        QuestionnaireSection $subsection,
        int $mainNumber,
        int $subNumber,
    ): array {
        $questions = $subsection->questions->values();
        $applicableQuestions = $this->frontendService->getApplicableQuestions($subsection);
        $answeredQuestions = $applicableQuestions
            ->filter(fn (QuestionnaireQuestion $question): bool => $this->frontendService->hasMeaningfulAnswer($question, $question->answer))
            ->values();
        $unansweredQuestions = $applicableQuestions
            ->reject(fn (QuestionnaireQuestion $question): bool => $this->frontendService->hasMeaningfulAnswer($question, $question->answer))
            ->values();

        $needsReviewItems = $this->buildQuestionNeedsReviewItems($subsection, $applicableQuestions);

        $data = [
            'number' => $mainNumber . '.' . $subNumber,
            'name' => $subsection->name,
            'description' => $subsection->description,
            'question_count' => $questions->count(),
            'applicable_question_count' => $applicableQuestions->count(),
            'answered_count' => $answeredQuestions->count(),
            'status_label' => $this->resolveSubsectionStatusLabel(
                $questions->count(),
                $answeredQuestions->count(),
                $applicableQuestions->count(),
                ! empty($needsReviewItems),
            ),
            'approved_decisions' => [],
            'fields' => [],
            'enums' => [],
            'lookups' => [],
            'relationships' => [],
            'business_rules' => [],
            'ui_requirements' => [],
            'needs_review_items' => $needsReviewItems,
            'unanswered_questions' => $this->buildUnansweredQuestionItems($subsection, $unansweredQuestions),
            'global' => $this->emptyGlobalAggregation(),
        ];

        if ($this->isFarmDataPilotSubsection($mainSection, $subsection)) {
            $interpreted = $this->interpretFarmDataSubsection($subsection, $applicableQuestions);

            $data['approved_decisions'] = $interpreted['approved_decisions'];
            $data['fields'] = $interpreted['fields'];
            $data['enums'] = $interpreted['enums'];
            $data['lookups'] = $interpreted['lookups'];
            $data['relationships'] = $interpreted['relationships'];
            $data['business_rules'] = $interpreted['business_rules'];
            $data['ui_requirements'] = $interpreted['ui_requirements'];
            $data['needs_review_items'] = array_values(array_merge($data['needs_review_items'], $interpreted['needs_review_items']));
            $data['global'] = $interpreted['global'];
        }

        $data['global']['needs_review'] = array_values(array_merge($data['global']['needs_review'], $data['needs_review_items']));
        $data['global']['unanswered'] = array_values(array_merge($data['global']['unanswered'], $data['unanswered_questions']));

        return $data;
    }

    private function isFarmDataPilotSubsection(QuestionnaireSection $mainSection, QuestionnaireSection $subsection): bool
    {
        return $mainSection->sort_order === 1 && $subsection->sort_order === 1;
    }

    private function interpretFarmDataSubsection(QuestionnaireSection $subsection, Collection $applicableQuestions): array
    {
        $questionsByOrder = $applicableQuestions->keyBy('sort_order');
        $selectedFarmFields = $this->answerArrayValues($questionsByOrder->get(1));
        $farmNameRequired = $this->answerBoolValue($questionsByOrder->get(2));
        $farmCodeBehavior = $this->answerScalarValue($questionsByOrder->get(3));
        $selectedActivities = $this->answerArrayValues($questionsByOrder->get(4));
        $activityManagement = $this->answerScalarValue($questionsByOrder->get(5));
        $ownerManagerMode = $this->answerScalarValue($questionsByOrder->get(6));
        $selectedContacts = $this->answerArrayValues($questionsByOrder->get(7));
        $selectedLocationFields = $this->answerArrayValues($questionsByOrder->get(8));
        $geolocationMethod = $this->answerScalarValue($questionsByOrder->get(9));
        $locationManagement = $this->answerScalarValue($questionsByOrder->get(10));
        $selectedFarmStatuses = $this->answerArrayValues($questionsByOrder->get(11));
        $farmStatusManagement = $this->answerScalarValue($questionsByOrder->get(12));
        $multiFarmSupport = $this->answerScalarValue($questionsByOrder->get(13));
        $farmSpecificSettings = $this->answerBoolValue($questionsByOrder->get(14));
        $additionalRequirements = $this->answerTextValue($questionsByOrder->get(15));

        $approvedDecisions = [];
        $fields = [];
        $enums = [];
        $lookups = [];
        $relationships = [];
        $businessRules = [];
        $uiRequirements = [];
        $needsReviewItems = [];
        $global = $this->emptyGlobalAggregation();

        $global['entities']['farm'] = [
            'name' => 'Farm',
            'purpose' => 'الكيان الرئيسي الذي يمثل المزرعة كوحدة تشغيلية مستقلة داخل النظام.',
            'source' => $subsection->name,
        ];

        $approvedDecisions[] = $this->decisionEntry(
            'بيانات ملف المزرعة',
            'الحقول المعتمدة في ملف المزرعة حاليًا هي: ' . $this->labelsFromQuestion($questionsByOrder->get(1), $selectedFarmFields),
            'توليد حقول الكيان `Farm` بناءً على الاختيارات المعتمدة فقط.',
            $subsection->name . ' — ' . $questionsByOrder->get(1)?->title,
        );

        if (in_array('name', $selectedFarmFields, true)) {
            $fields['farm.name'] = $this->fieldEntry('Farm', 'name', 'string', $farmNameRequired ? 'نعم' : 'لا', $subsection->name, 'اسم المزرعة');
        }

        if (in_array('code', $selectedFarmFields, true) && in_array($farmCodeBehavior, ['manual', 'automatic'], true)) {
            $fields['farm.code'] = $this->fieldEntry(
                'Farm',
                'code',
                'string',
                'غير محسوم',
                $subsection->name,
                $farmCodeBehavior === 'automatic' ? 'يتولد تلقائيًا بواسطة النظام.' : 'يُدخل يدويًا بواسطة المستخدم.'
            );
        }

        if (in_array('phone', $selectedFarmFields, true) && ! in_array('multiple_phones', $selectedContacts, true)) {
            $fields['farm.phone'] = $this->fieldEntry('Farm', 'phone', 'string', 'غير محسوم', $subsection->name, 'رقم هاتف رئيسي واحد للمزرعة.');
        }

        if (in_array('governorate', $selectedFarmFields, true)) {
            $fields['farm.governorate'] = $this->fieldEntry('Farm', 'governorate', $locationManagement === 'text' ? 'string' : 'reference', 'غير محسوم', $subsection->name);
        }

        if (in_array('area', $selectedFarmFields, true)) {
            $fields['farm.area'] = $this->fieldEntry('Farm', 'area', $locationManagement === 'text' ? 'string' : 'reference', 'غير محسوم', $subsection->name);
        }

        if (in_array('address', $selectedFarmFields, true)) {
            $fields['farm.address'] = $this->fieldEntry('Farm', 'address', 'text', 'غير محسوم', $subsection->name);
        }

        if (in_array('started_at', $selectedFarmFields, true)) {
            $fields['farm.started_at'] = $this->fieldEntry('Farm', 'started_at', 'date', 'غير محسوم', $subsection->name);
        }

        if (in_array('notes', $selectedFarmFields, true)) {
            $fields['farm.notes'] = $this->fieldEntry('Farm', 'notes', 'text', 'لا', $subsection->name, 'ملاحظات عامة على ملف المزرعة.');
        }

        if (in_array('geolocation', $selectedLocationFields, true)) {
            $fields['farm.latitude'] = $this->fieldEntry('Farm', 'latitude', 'decimal', 'غير محسوم', $subsection->name, 'يستخدم عند اعتماد الموقع الجغرافي.');
            $fields['farm.longitude'] = $this->fieldEntry('Farm', 'longitude', 'decimal', 'غير محسوم', $subsection->name, 'يستخدم عند اعتماد الموقع الجغرافي.');
        }

        if (in_array('status', $selectedFarmFields, true)) {
            if ($farmStatusManagement === 'fixed') {
                $fields['farm.status'] = $this->fieldEntry('Farm', 'status', 'enum:FarmStatus', 'غير محسوم', $subsection->name);
            }

            if ($farmStatusManagement === 'managed') {
                $fields['farm.status_id'] = $this->fieldEntry('Farm', 'status_id', 'foreignId', 'غير محسوم', $subsection->name);
            }
        }

        $approvedDecisions[] = $this->decisionEntry(
            'اسم المزرعة',
            $farmNameRequired ? 'اسم المزرعة مطلوب عند إنشاء السجل.' : 'اسم المزرعة غير إلزامي حاليًا.',
            $farmNameRequired ? 'الحقل `Farm.name` يجب أن يكون غير فارغ.' : 'الحقل `Farm.name` يمكن أن يكون nullable.',
            $subsection->name . ' — ' . $questionsByOrder->get(2)?->title,
        );

        $approvedDecisions[] = $this->decisionEntry(
            'كود المزرعة',
            $this->readableAnswer($questionsByOrder->get(3)),
            match ($farmCodeBehavior) {
                'not_required' => 'لا يتم فرض حقل كود مستقل إلا إذا تمت مراجعته لاحقًا.',
                'manual' => 'يوصى بحقل `Farm.code` نصي مع إدخال يدوي.',
                'automatic' => 'يوصى بحقل `Farm.code` نصي مع توليد تلقائي بواسطة التطبيق.',
                default => 'يحتاج قرار كود المزرعة إلى مراجعة قبل اعتماد التنفيذ.',
            },
            $subsection->name . ' — ' . $questionsByOrder->get(3)?->title,
        );

        $activityValues = array_values(array_filter($selectedActivities, fn (string $value): bool => $value !== 'multiple'));
        $multipleActivitiesEnabled = in_array('multiple', $selectedActivities, true);

        $approvedDecisions[] = $this->decisionEntry(
            'أنشطة المزرعة',
            $this->labelsFromQuestion($questionsByOrder->get(4), $selectedActivities),
            $multipleActivitiesEnabled
                ? 'المزرعة قد تعمل بأكثر من نشاط، لذلك يجب أن يدعم التصميم ربط المزرعة بعدة أنشطة.'
                : 'المزرعة تعمل ضمن الأنشطة المحددة فقط حسب اختيار المختص.',
            $subsection->name . ' — ' . $questionsByOrder->get(4)?->title,
        );

        if ($activityManagement === 'fixed' && ! empty($activityValues)) {
            $enums['FarmActivity'] = [
                'name' => 'FarmActivity',
                'values' => $this->enumValuesFromQuestion($questionsByOrder->get(4), $activityValues),
                'source' => $subsection->name,
            ];
        }

        if ($activityManagement === 'managed') {
            $lookups['farm_activities'] = [
                'table' => 'farm_activities',
                'purpose' => 'إدارة أنشطة المزرعة من خلال قائمة قابلة للإضافة والتعديل.',
                'fields' => ['id', 'name', 'is_active', 'sort_order', 'timestamps'],
                'relationship' => $multipleActivitiesEnabled ? 'Farm يرتبط بعدة FarmActivity' : 'Farm يرتبط بـ FarmActivity حسب قرار الإدارة.',
                'source' => $subsection->name,
            ];

            $global['entities']['farm_activity'] = [
                'name' => 'FarmActivity',
                'purpose' => 'كيان لإدارة أنشطة المزرعة عندما تكون قابلة للإضافة والتعديل.',
                'source' => $subsection->name,
            ];
        }

        if ($multipleActivitiesEnabled) {
            $relationships[] = 'المزرعة قد ترتبط بأكثر من نشاط تشغيلي واحد.';
        }

        if ($ownerManagerMode === 'single_person') {
            $relationships[] = 'يكفي تسجيل شخص مسؤول واحد مرتبط بالمزرعة.';
        } elseif ($ownerManagerMode === 'separate_owner_manager') {
            $relationships[] = 'يجب الفصل بين دور مالك المزرعة ودور المسؤول التشغيلي.';
        } elseif ($ownerManagerMode === 'multiple_managers') {
            $relationships[] = 'المزرعة قد ترتبط بأكثر من مسؤول تشغيلي.';
        }

        $approvedDecisions[] = $this->decisionEntry(
            'المالك والمسؤول التشغيلي',
            $this->readableAnswer($questionsByOrder->get(6)),
            Arr::last($relationships) ?? 'يحدد هذا القرار شكل العلاقة بين المزرعة والمسؤولين عنها.',
            $subsection->name . ' — ' . $questionsByOrder->get(6)?->title,
        );

        if (in_array('multiple_phones', $selectedContacts, true)) {
            $businessRules[] = 'المزرعة تحتاج إلى أكثر من رقم هاتف، لذلك لا يكفي الاعتماد على عمود هاتف واحد فقط.';
            $uiRequirements[] = 'واجهة المزرعة يجب أن تدعم إدخال أكثر من رقم هاتف بصورة تكرارية.';
        } elseif (in_array('phone', $selectedContacts, true)) {
            $fields['farm.phone'] ??= $this->fieldEntry('Farm', 'phone', 'string', 'غير محسوم', $subsection->name, 'رقم هاتف رئيسي واحد للمزرعة.');
        }

        if (in_array('email', $selectedContacts, true)) {
            $fields['farm.email'] = $this->fieldEntry('Farm', 'email', 'string', 'غير محسوم', $subsection->name);
        }

        if (in_array('whatsapp', $selectedContacts, true)) {
            $fields['farm.whatsapp'] = $this->fieldEntry('Farm', 'whatsapp', 'string', 'غير محسوم', $subsection->name);
        }

        $approvedDecisions[] = $this->decisionEntry(
            'بيانات الاتصال',
            $this->labelsFromQuestion($questionsByOrder->get(7), $selectedContacts),
            in_array('multiple_phones', $selectedContacts, true)
                ? 'يلزم دعم بيانات اتصال قابلة للتكرار للمزرعة.'
                : 'يتم تسجيل بيانات الاتصال المختارة فقط دون افتراض حقول إضافية.',
            $subsection->name . ' — ' . $questionsByOrder->get(7)?->title,
        );

        if (in_array('governorate', $selectedLocationFields, true) && $locationManagement !== 'text') {
            $relationships[] = 'المزرعة ترتبط بالمحافظة المختارة عند اعتماد إدارة مواقع منظمة.';
        }

        if (in_array('city', $selectedLocationFields, true) && $locationManagement !== 'text') {
            $fields['farm.city'] = $this->fieldEntry('Farm', 'city', 'reference', 'غير محسوم', $subsection->name);
            $relationships[] = 'المزرعة ترتبط بالمدينة أو المركز المختار عند اعتماد إدارة مواقع منظمة.';
        }

        if ($locationManagement === 'managed') {
            if (in_array('governorate', $selectedLocationFields, true)) {
                $lookups['governorates'] = [
                    'table' => 'governorates',
                    'purpose' => 'إدارة المحافظات المتاحة للاختيار داخل بيانات المزرعة.',
                    'fields' => ['id', 'name', 'is_active', 'sort_order', 'timestamps'],
                    'relationship' => 'Farm يرتبط بمحافظة واحدة.',
                    'source' => $subsection->name,
                ];
                $global['entities']['governorate'] = [
                    'name' => 'Governorate',
                    'purpose' => 'كيان لإدارة المحافظات في النظام.',
                    'source' => $subsection->name,
                ];
            }

            if (in_array('city', $selectedLocationFields, true)) {
                $lookups['cities'] = [
                    'table' => 'cities',
                    'purpose' => 'إدارة المدن أو المراكز المتاحة للاختيار داخل بيانات المزرعة.',
                    'fields' => ['id', 'governorate_id', 'name', 'is_active', 'sort_order', 'timestamps'],
                    'relationship' => 'Farm يرتبط بمدينة أو مركز واحد.',
                    'source' => $subsection->name,
                ];
                $global['entities']['city'] = [
                    'name' => 'City',
                    'purpose' => 'كيان لإدارة المدن أو المراكز.',
                    'source' => $subsection->name,
                ];
            }

            if (in_array('area', $selectedLocationFields, true)) {
                $lookups['areas'] = [
                    'table' => 'areas',
                    'purpose' => 'إدارة المناطق أو القرى المتاحة للاختيار داخل بيانات المزرعة.',
                    'fields' => ['id', 'city_id', 'name', 'is_active', 'sort_order', 'timestamps'],
                    'relationship' => 'Farm يرتبط بمنطقة أو قرية واحدة.',
                    'source' => $subsection->name,
                ];
                $global['entities']['area'] = [
                    'name' => 'Area',
                    'purpose' => 'كيان لإدارة المناطق أو القرى.',
                    'source' => $subsection->name,
                ];
            }
        }

        if ($locationManagement === 'fixed') {
            $businessRules[] = 'بيانات المحافظات والمناطق تعتمد على قوائم ثابتة معدة مسبقًا.';
        }

        if ($locationManagement === 'text') {
            $businessRules[] = 'بيانات الموقع الجغرافي الوصفية تعتمد على إدخال نصي مباشر دون قوائم إدارية.';
        }

        if (in_array('geolocation', $selectedLocationFields, true)) {
            $uiRequirements[] = match ($geolocationMethod) {
                'map' => 'واجهة المزرعة يجب أن توفر اختيار الموقع عبر الخريطة.',
                'coordinates' => 'واجهة المزرعة يجب أن توفر إدخال Latitude و Longitude مباشرة.',
                'both' => 'واجهة المزرعة يجب أن تدعم اختيار الموقع على الخريطة وإدخال الإحداثيات يدويًا.',
                default => 'متطلبات إدخال الموقع الجغرافي تحتاج مراجعة.',
            };
        }

        $approvedDecisions[] = $this->decisionEntry(
            'تفاصيل الموقع',
            $this->labelsFromQuestion($questionsByOrder->get(8), $selectedLocationFields),
            $locationManagement === 'managed'
                ? 'المستويات المختارة فقط هي التي ستدخل ضمن قوائم إدارية قابلة للإدارة.'
                : ($locationManagement === 'text'
                    ? 'المستويات المختارة ستدخل كنصوص مباشرة دون قوائم إدارية.'
                    : 'المستويات المختارة ستعتمد على قوائم ثابتة.')
            ,
            $subsection->name . ' — ' . $questionsByOrder->get(10)?->title,
        );

        if ($farmStatusManagement === 'fixed' && ! empty($selectedFarmStatuses)) {
            $enums['FarmStatus'] = [
                'name' => 'FarmStatus',
                'values' => $this->enumValuesFromQuestion($questionsByOrder->get(11), $selectedFarmStatuses),
                'source' => $subsection->name,
            ];
        }

        if ($farmStatusManagement === 'managed') {
            $lookups['farm_statuses'] = [
                'table' => 'farm_statuses',
                'purpose' => 'إدارة حالات المزرعة من خلال قائمة قابلة للإضافة والتعديل.',
                'fields' => ['id', 'name', 'is_active', 'sort_order', 'timestamps'],
                'relationship' => 'Farm belongsTo FarmStatus',
                'source' => $subsection->name,
            ];

            $global['entities']['farm_status'] = [
                'name' => 'FarmStatus',
                'purpose' => 'كيان لإدارة حالات المزرعة عندما تكون قابلة للإضافة والتعديل.',
                'source' => $subsection->name,
            ];
        }

        $approvedDecisions[] = $this->decisionEntry(
            'حالات المزرعة',
            $this->labelsFromQuestion($questionsByOrder->get(11), $selectedFarmStatuses),
            $farmStatusManagement === 'fixed'
                ? 'يوصى باستخدام Enum ثابت لقيم حالة المزرعة.'
                : 'يوصى باستخدام قائمة إدارية قابلة للتعديل لحالات المزرعة.',
            $subsection->name . ' — ' . $questionsByOrder->get(12)?->title,
        );

        if ($multiFarmSupport === 'single_farm') {
            $businessRules[] = 'النشر الحالي يدعم مزرعة واحدة فقط.';
        } elseif ($multiFarmSupport === 'multi_farm') {
            $businessRules[] = 'التصميم يجب أن يدعم إدارة أكثر من مزرعة من البداية.';
        } elseif ($multiFarmSupport === 'future_multi_farm') {
            $businessRules[] = 'العلاقات الأساسية يجب أن تبقى Farm-aware لدعم التوسع المستقبلي دون اعتبارها Multi-Tenant.';
        }

        if ($farmSpecificSettings === true) {
            $businessRules[] = 'إعدادات التشغيل والإنتاج يجب أن تدعم التخصيص على مستوى كل مزرعة.';
        }

        if ($farmSpecificSettings === false) {
            $businessRules[] = 'الإعدادات يمكن أن تبقى عامة ما لم تظهر متطلبات لاحقة تعكس خلاف ذلك.';
        }

        $approvedDecisions[] = $this->decisionEntry(
            'دعم تعدد المزارع',
            $this->readableAnswer($questionsByOrder->get(13)),
            Arr::last($businessRules) ?? 'هذا القرار يؤثر على شكل العلاقات العامة داخل النظام.',
            $subsection->name . ' — ' . $questionsByOrder->get(13)?->title,
        );

        if (filled($additionalRequirements)) {
            $needsReviewItems[] = [
                'section' => $subsection->name,
                'question' => $questionsByOrder->get(15)?->title,
                'answer' => $this->readableAnswer($questionsByOrder->get(15)),
                'note' => 'متطلب إضافي من المختص يحتاج مراجعة إدارية قبل تحويله إلى قرار تقني.',
            ];
        }

        if (! in_array('code', $selectedFarmFields, true) && in_array($farmCodeBehavior, ['manual', 'automatic'], true)) {
            $needsReviewItems[] = $this->consistencyReviewItem(
                $subsection->name,
                'تم تحديد سلوك لكود المزرعة رغم أن حقول ملف المزرعة لا تتضمن كود المزرعة.'
            );
        }

        if (! in_array('status', $selectedFarmFields, true) && (! empty($selectedFarmStatuses) || filled($farmStatusManagement))) {
            $needsReviewItems[] = $this->consistencyReviewItem(
                $subsection->name,
                'تم تحديد حالات للمزرعة أو أسلوب إدارتها رغم أن حقل حالة المزرعة غير مختار ضمن بيانات الملف.'
            );
        }

        if (in_array('none', $selectedContacts, true) && count(array_diff($selectedContacts, ['none'])) > 0) {
            $needsReviewItems[] = $this->consistencyReviewItem(
                $subsection->name,
                'هناك تعارض بين اختيار "لا نحتاج بيانات اتصال مستقلة" واختيار أنواع اتصال أخرى في نفس الوقت.'
            );
        }

        if (! in_array('geolocation', $selectedLocationFields, true) && $questionsByOrder->get(9) instanceof QuestionnaireQuestion) {
            $needsReviewItems[] = $this->consistencyReviewItem(
                $subsection->name,
                'سؤال أسلوب إدخال الموقع الجغرافي لا ينبغي أن يؤثر ما دام الموقع الجغرافي غير مختار ضمن بيانات الموقع.'
            );
        }

        if (in_array('other', $selectedFarmStatuses, true) && blank($questionsByOrder->get(11)?->answer?->notes)) {
            $needsReviewItems[] = $this->consistencyReviewItem(
                $subsection->name,
                'تم اختيار حالة إضافية للمزرعة دون توضيح معناها.'
            );
        }

        $global['fields'] = array_values($fields);
        $global['enums'] = array_values($enums);
        $global['lookups'] = array_values($lookups);
        $global['relationships'] = array_values($relationships);
        $global['business_rules'] = array_values($businessRules);
        $global['ui_requirements'] = array_values($uiRequirements);
        $global['needs_review'] = array_values($needsReviewItems);
        $global['decision_log'] = array_values($approvedDecisions);

        return [
            'approved_decisions' => array_values($approvedDecisions),
            'fields' => array_values($fields),
            'enums' => array_values($enums),
            'lookups' => array_values($lookups),
            'relationships' => array_values($relationships),
            'business_rules' => array_values($businessRules),
            'ui_requirements' => array_values($uiRequirements),
            'needs_review_items' => array_values($needsReviewItems),
            'global' => $global,
        ];
    }

    private function buildQuestionNeedsReviewItems(QuestionnaireSection $subsection, Collection $applicableQuestions): array
    {
        return $applicableQuestions
            ->filter(fn (QuestionnaireQuestion $question): bool => filled($question->answer?->notes) || $question->answer?->needs_review === true)
            ->map(fn (QuestionnaireQuestion $question): array => [
                'section' => $subsection->name,
                'question' => $question->title,
                'answer' => $this->readableAnswer($question),
                'note' => filled($question->answer?->notes)
                    ? (string) $question->answer?->notes
                    : 'الإجابة الحالية معلمة كمحتاجة مراجعة.',
            ])
            ->values()
            ->all();
    }

    private function buildUnansweredQuestionItems(QuestionnaireSection $subsection, Collection $unansweredQuestions): array
    {
        return $unansweredQuestions
            ->map(fn (QuestionnaireQuestion $question): array => [
                'section' => $subsection->name,
                'question' => $question->title,
                'help_text' => $question->help_text,
            ])
            ->values()
            ->all();
    }

    private function resolveSubsectionStatusLabel(
        int $questionCount,
        int $answeredCount,
        int $applicableCount,
        bool $hasNeedsReview,
    ): string {
        if ($hasNeedsReview) {
            return 'يحتاج مراجعة';
        }

        if ($questionCount === 0) {
            return 'غير مُجهز بعد';
        }

        if ($answeredCount === 0) {
            return 'لم يبدأ';
        }

        if ($applicableCount > 0 && $answeredCount >= $applicableCount) {
            return 'مكتمل';
        }

        return 'قيد التنفيذ';
    }

    private function emptyGlobalAggregation(): array
    {
        return [
            'entities' => [],
            'fields' => [],
            'enums' => [],
            'lookups' => [],
            'relationships' => [],
            'business_rules' => [],
            'ui_requirements' => [],
            'alerts' => [],
            'reports' => [],
            'needs_review' => [],
            'unanswered' => [],
            'decision_log' => [],
        ];
    }

    private function mergeGlobalAggregation(array $current, array $incoming): array
    {
        foreach (['entities', 'fields', 'enums', 'lookups'] as $group) {
            foreach ($incoming[$group] as $entry) {
                $key = $this->entryKey($group, $entry);
                $current[$group][$key] = $entry;
            }
        }

        foreach (['relationships', 'business_rules', 'ui_requirements', 'alerts', 'reports'] as $group) {
            foreach ($incoming[$group] as $entry) {
                $current[$group][$entry] = $entry;
            }
        }

        foreach (['needs_review', 'unanswered', 'decision_log'] as $group) {
            foreach ($incoming[$group] as $entry) {
                $key = md5(json_encode($entry, JSON_UNESCAPED_UNICODE));
                $current[$group][$key] = $entry;
            }
        }

        return $current;
    }

    private function normalizeGlobalAggregation(array $aggregation): array
    {
        foreach ($aggregation as $key => $entries) {
            $aggregation[$key] = array_values($entries);
        }

        return $aggregation;
    }

    private function entryKey(string $group, array $entry): string
    {
        return match ($group) {
            'entities' => Str::lower((string) ($entry['name'] ?? 'entity')),
            'fields' => Str::lower((string) ($entry['entity'] ?? 'entity') . '.' . ($entry['field'] ?? 'field')),
            'enums' => Str::lower((string) ($entry['name'] ?? 'enum')),
            'lookups' => Str::lower((string) ($entry['table'] ?? 'lookup')),
            default => md5(json_encode($entry, JSON_UNESCAPED_UNICODE)),
        };
    }

    private function answerArrayValues(?QuestionnaireQuestion $question): array
    {
        $value = $question?->answer?->value;

        if (! is_array($value)) {
            return [];
        }

        return array_values(array_map('strval', $value));
    }

    private function answerScalarValue(?QuestionnaireQuestion $question): ?string
    {
        $value = $question?->answer?->value;

        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        if ($value === null || is_array($value)) {
            return null;
        }

        return (string) $value;
    }

    private function answerBoolValue(?QuestionnaireQuestion $question): ?bool
    {
        $value = $question?->answer?->value;

        return is_bool($value) ? $value : null;
    }

    private function answerTextValue(?QuestionnaireQuestion $question): ?string
    {
        $value = $question?->answer?->value;

        if (! is_string($value)) {
            return null;
        }

        $trimmed = trim($value);

        return $trimmed === '' ? null : $trimmed;
    }

    private function readableAnswer(?QuestionnaireQuestion $question): string
    {
        if (! $question instanceof QuestionnaireQuestion) {
            return 'غير محسوم';
        }

        return $question->formatAnswerValue($question->answer?->value);
    }

    private function labelsFromQuestion(?QuestionnaireQuestion $question, array $values): string
    {
        if (! $question instanceof QuestionnaireQuestion || empty($values)) {
            return 'غير محسوم';
        }

        $labels = $question->options
            ->mapWithKeys(fn (QuestionnaireQuestionOption $option): array => [$option->value => $option->label]);

        return collect($values)
            ->map(fn (string $value): string => $labels->get($value, $value))
            ->implode('، ');
    }

    private function enumValuesFromQuestion(?QuestionnaireQuestion $question, array $values): array
    {
        if (! $question instanceof QuestionnaireQuestion) {
            return [];
        }

        $labels = $question->options
            ->mapWithKeys(fn (QuestionnaireQuestionOption $option): array => [$option->value => $option->label]);

        return collect($values)
            ->map(fn (string $value): array => [
                'value' => $value,
                'label' => $labels->get($value, $value),
            ])
            ->values()
            ->all();
    }

    private function fieldEntry(
        string $entity,
        string $field,
        string $typeRecommendation,
        string $required,
        string $source,
        ?string $notes = null,
    ): array {
        return [
            'entity' => $entity,
            'field' => $field,
            'type' => $typeRecommendation,
            'required' => $required,
            'source' => $source,
            'notes' => $notes,
        ];
    }

    private function decisionEntry(string $title, string $specialistDecision, string $technicalRecommendation, string $source): array
    {
        return [
            'title' => $title,
            'specialist_decision' => $specialistDecision,
            'technical_recommendation' => $technicalRecommendation,
            'source' => $source,
        ];
    }

    private function consistencyReviewItem(string $sectionName, string $note): array
    {
        return [
            'section' => $sectionName,
            'question' => 'فحص الاتساق',
            'answer' => 'يحتاج مراجعة',
            'note' => $note,
        ];
    }

    private function renderDecisionEntries(array $entries): array
    {
        $lines = ['### القرارات المعتمدة', ''];

        if (empty($entries)) {
            $lines[] = 'لا توجد قرارات تقنية معتمدة لهذا القسم حتى الآن.';
            $lines[] = '';

            return $lines;
        }

        foreach ($entries as $entry) {
            $lines[] = '#### ' . $entry['title'];
            $lines[] = '';
            $lines[] = '**قرار المختص:**  ';
            $lines[] = $entry['specialist_decision'];
            $lines[] = '';
            $lines[] = '**التوصية الفنية:**  ';
            $lines[] = $entry['technical_recommendation'];
            $lines[] = '';
            $lines[] = '**المصدر:**  ';
            $lines[] = $entry['source'];
            $lines[] = '';
        }

        return $lines;
    }

    private function renderFieldEntries(array $entries): array
    {
        $lines = ['### الحقول', ''];

        if (empty($entries)) {
            $lines[] = 'لا توجد حقول معتمدة إضافية في هذا القسم حتى الآن.';
            $lines[] = '';

            return $lines;
        }

        $lines[] = '| الكيان | الحقل | التوصية النوعية | الإلزام | المصدر | ملاحظات |';
        $lines[] = '|---|---|---|---|---|---|';

        foreach ($entries as $entry) {
            $lines[] = '| ' . $entry['entity'] . ' | `' . $entry['field'] . '` | ' . $entry['type'] . ' | ' . $entry['required'] . ' | ' . $entry['source'] . ' | ' . ($entry['notes'] ?? '-') . ' |';
        }

        $lines[] = '';

        return $lines;
    }

    private function renderEnumEntries(array $entries): array
    {
        $lines = ['### القيم الثابتة / Enums', ''];

        if (empty($entries)) {
            $lines[] = 'لا توجد قيم ثابتة معتمدة في هذا القسم حاليًا.';
            $lines[] = '';

            return $lines;
        }

        foreach ($entries as $entry) {
            $lines[] = '#### ' . $entry['name'];
            $lines[] = '';
            $lines[] = 'القيم:';
            $lines[] = '';

            foreach ($entry['values'] as $value) {
                $lines[] = '- `' . $value['value'] . '` — ' . $value['label'];
            }

            $lines[] = '';
        }

        return $lines;
    }

    private function renderLookupEntries(array $entries): array
    {
        $lines = ['### القوائم القابلة للإدارة', ''];

        if (empty($entries)) {
            $lines[] = 'لا توجد قوائم إدارية مقترحة في هذا القسم حاليًا.';
            $lines[] = '';

            return $lines;
        }

        foreach ($entries as $entry) {
            $lines[] = '#### `' . $entry['table'] . '`';
            $lines[] = '';
            $lines[] = '**الغرض:**  ';
            $lines[] = $entry['purpose'];
            $lines[] = '';
            $lines[] = '**الحقول الأساسية المقترحة:**';
            $lines[] = '';

            foreach ($entry['fields'] as $field) {
                $lines[] = '- `' . $field . '`';
            }

            $lines[] = '';
            $lines[] = '**العلاقة:**  ';
            $lines[] = $entry['relationship'];
            $lines[] = '';
        }

        return $lines;
    }

    private function renderSimpleEntries(string $heading, array $entries, ?string $emptyText = null): array
    {
        $lines = [$heading, ''];

        if (empty($entries)) {
            $lines[] = $emptyText ?? 'لا توجد عناصر معتمدة حاليًا.';
            $lines[] = '';

            return $lines;
        }

        foreach ($entries as $entry) {
            $lines[] = '- ' . $entry;
        }

        $lines[] = '';

        return $lines;
    }

    private function renderNeedsReviewEntries(array $entries, string $heading = '### نقاط تحتاج مراجعة'): array
    {
        $lines = [$heading, ''];

        if (empty($entries)) {
            $lines[] = 'لا توجد نقاط مفتوحة للمراجعة في هذا القسم حاليًا.';
            $lines[] = '';

            return $lines;
        }

        foreach ($entries as $entry) {
            $lines[] = '- **القسم:** ' . $entry['section'];
            $lines[] = '  **السؤال:** ' . $entry['question'];
            $lines[] = '  **الإجابة الحالية:** ' . $entry['answer'];
            $lines[] = '  **ملاحظة المختص:** ' . $entry['note'];
        }

        $lines[] = '';

        return $lines;
    }

    private function renderUnansweredEntries(array $entries, string $heading = '### أسئلة غير محسومة'): array
    {
        $lines = [$heading, ''];

        if (empty($entries)) {
            $lines[] = 'لا توجد أسئلة قابلة حالياً بدون إجابة في هذا القسم.';
            $lines[] = '';

            return $lines;
        }

        foreach ($entries as $entry) {
            $lines[] = '- **القسم:** ' . $entry['section'];
            $lines[] = '  **السؤال:** ' . $entry['question'];

            if (filled($entry['help_text'] ?? null)) {
                $lines[] = '  **التوضيح:** ' . $entry['help_text'];
            }
        }

        $lines[] = '';

        return $lines;
    }

    private function renderGlobalEntities(array $entries): array
    {
        $lines = ['# الكيانات المقترحة', ''];

        if (empty($entries)) {
            $lines[] = 'لا توجد كيانات مقترحة معتمدة بعد.';
            $lines[] = '';

            return $lines;
        }

        foreach ($entries as $entry) {
            $lines[] = '## ' . $entry['name'];
            $lines[] = '';
            $lines[] = $entry['purpose'];
            $lines[] = '';
            $lines[] = '**المصدر:** ' . $entry['source'];
            $lines[] = '';
        }

        return $lines;
    }

    private function renderGlobalFields(array $entries): array
    {
        $lines = ['# الحقول المقترحة', ''];

        if (empty($entries)) {
            $lines[] = 'لا توجد حقول مقترحة مجمعة حتى الآن.';
            $lines[] = '';

            return $lines;
        }

        $grouped = collect($entries)->groupBy('entity');

        foreach ($grouped as $entity => $fields) {
            $lines[] = '## ' . $entity;
            $lines[] = '';
            $lines[] = '| Field | Type Recommendation | Required | Source |';
            $lines[] = '|---|---|---|---|';

            foreach ($fields as $field) {
                $lines[] = '| `' . $field['field'] . '` | ' . $field['type'] . ' | ' . $field['required'] . ' | ' . $field['source'] . ' |';
            }

            $lines[] = '';
        }

        return $lines;
    }

    private function renderGlobalEnums(array $entries): array
    {
        $lines = ['# Enums المقترحة', ''];

        if (empty($entries)) {
            $lines[] = 'لا توجد Enums معتمدة حاليًا.';
            $lines[] = '';

            return $lines;
        }

        foreach ($entries as $entry) {
            $lines[] = '## ' . $entry['name'];
            $lines[] = '';
            $lines[] = 'Values:';
            $lines[] = '';

            foreach ($entry['values'] as $value) {
                $lines[] = '- `' . $value['value'] . '` — ' . $value['label'];
            }

            $lines[] = '';
        }

        return $lines;
    }

    private function renderGlobalLookups(array $entries): array
    {
        $lines = ['# Lookup Tables المقترحة', ''];

        if (empty($entries)) {
            $lines[] = 'لا توجد Lookup Tables معتمدة حاليًا.';
            $lines[] = '';

            return $lines;
        }

        foreach ($entries as $entry) {
            $lines[] = '## `' . $entry['table'] . '`';
            $lines[] = '';
            $lines[] = 'Purpose:';
            $lines[] = $entry['purpose'];
            $lines[] = '';
            $lines[] = 'Suggested baseline fields:';
            $lines[] = '';

            foreach ($entry['fields'] as $field) {
                $lines[] = '- `' . $field . '`';
            }

            $lines[] = '';
            $lines[] = 'Relationship:';
            $lines[] = $entry['relationship'];
            $lines[] = '';
        }

        return $lines;
    }

    private function renderDecisionLog(array $entries): array
    {
        $lines = ['# سجل القرارات', ''];

        if (empty($entries)) {
            $lines[] = 'لا توجد قرارات مجمعة بعد.';
            $lines[] = '';

            return $lines;
        }

        foreach ($entries as $entry) {
            $lines[] = '- **' . $entry['title'] . '** — ' . $entry['specialist_decision'] . ' — ' . $entry['technical_recommendation'];
        }

        $lines[] = '';

        return $lines;
    }
}
