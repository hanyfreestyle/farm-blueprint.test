# Questionnaire Implementation Plan

## 1. Project Goal

هذا المشروع عبارة عن تطبيق مخصص لمراجعة وتحليل تصور نظام إدارة مزرعة الأرانب مع مختص Domain Expert.

الهدف ليس إنشاء Questionnaire Platform عامة.

المسار الأساسي للمشروع:

Domain Review
→ Questions
→ Specialist Answers
→ Optional Notes
→ Needs Review
→ Technical Specification

المختص لا يقوم بتصميم Laravel Database أو Migrations مباشرة.

المختص يجيب عن أسئلة تشغيلية وفنية تخص الواقع الفعلي للمزرعة.

بعد اكتمال الإجابات، يقوم النظام بإنتاج Technical Specification منظم يمكن إعطاؤه لاحقًا لمطور أو AI Developer لبناء النظام الحقيقي.

---

# 2. Core Development Reference

المرجع الأساسي للتعامل مع Laravel Core هو:

AGENTS.md

الموجود في جذر المشروع.

قبل تنفيذ أي Phase يجب:

1. قراءة AGENTS.md بالكامل.
2. الالتزام بإصدارات Laravel / Filament والحزم الموجودة فعليًا.
3. اتباع conventions الموجودة في المشروع.
4. استخدام نفس أسلوب بناء Filament Resources المستخدم في الـCore.
5. عدم افتراض APIs أو namespaces غير موجودة في النسخة المثبتة.

هذا الملف:

docs/QUESTIONNAIRE_IMPLEMENTATION_PLAN.md

خاص فقط بخطة تنفيذ مشروع الاستبيان.

AGENTS.md
خاص بقواعد الـCore والمشروع ككل.

---

# 3. Explicit Non-Goals

هذا المشروع ليس:

- Generic Questionnaire Builder
- Survey SaaS
- Multi Project Platform
- Multi Tenant Platform
- Blueprint Engine
- Questionnaire Versioning System
- Questionnaire Sessions System
- Source Mapping Engine
- Generic Decision Engine
- Rules DSL
- Generic Workflow Builder

لا يتم إضافة أي abstraction عامة إلا إذا ظهرت لها حاجة فعلية في المشروع الحالي وتم اعتمادها صراحة.

---

# 4. Simplified Architecture

البنية الأساسية:

Section
→ Subsection
→ Questions
→ Question Options
→ Answers
→ Technical Report

لدينا استبيان واحد فقط.

لا توجد Questionnaire Sessions.

كل Question لها Answer حالية واحدة فقط.

عند تعديل الإجابة يتم تحديث نفس Answer.

هيكل الأقسام المعتمد الآن:

Main Section
→ Subsection
→ Questions

ولا يتم اعتماد شجرة عميقة عامة في هذا المشروع.

---

# 5. Sections

الأقسام هي المراحل الرئيسية للدراسة.

وهي المصدر الذي يتم منه بناء القائمة الجانبية في واجهة المستخدم.

يتم استخدام جدول واحد ذاتي الربط لدعم مستويين منطقيين فقط:

- Main Section
- Subsection

الحقول المبدئية:

- id
- parent_id nullable
- name
- description
- sort_order
- timestamps

description:

يحتوي على Markdown.

الترتيب:

sort_order

يحدد ترتيب القسم في:

- Filament
- Frontend Sidebar
- Technical Report

معنى parent_id:

parent_id = null
→ Main Section

parent_id != null
→ Subsection

العلاقات:

- Section belongsTo Parent
- Section hasMany Children
- Section hasMany Questions

قاعدة البنية:

- يدعم المشروع مستويين منطقيين فقط للأقسام
- الأسئلة تنتمي عادة إلى Subsections
- لا يتم بناء deep tree عام

سلوك الحذف الآمن:

- حذف Main Section مع وجود Subsections تحته يجب أن يكون مرفوضاً
- حذف أي Section ما دام مرتبطاً به Questions يجب أن يكون مرفوضاً

---

# 6. Questions

كل Question تتبع Section واحدة.

الحقول المبدئية:

- id
- section_id
- title
- help_text
- type
- is_required
- sort_order
- depends_on_question_id nullable
- dependency_operator nullable
- dependency_value nullable
- report_category nullable
- target_entity nullable
- timestamps

لا يتم اعتماد الحقول النهائية قبل تنفيذ Phase 1 ومراجعة المشروع فعليًا.

---

# 7. Question Types

يتم استخدام PHP Backed Enum باسم مناسب مثل:

QuestionType

القيم:

text
textarea
number
date
yes_no
single_choice
multi_choice
select

معانيها:

text
= نص قصير

textarea
= نص طويل

number
= رقم

date
= تاريخ

yes_no
= نعم / لا

single_choice
= اختيار واحد

multi_choice
= أكثر من اختيار

select
= قائمة منسدلة

---

# 8. Question Options

تستخدم فقط مع:

single_choice
multi_choice
select

الحقول المبدئية:

- id
- question_id
- label
- value
- sort_order
- timestamps

في Filament يتم إدارتها بواسطة Repeater داخل Question Resource.

عند اختيار Question Type لا يحتاج Options:

- text
- textarea
- number
- date
- yes_no

لا يظهر Repeater.

عند اختيار:

- single_choice
- multi_choice
- select

يظهر Repeater تلقائيًا.

---

# 9. Simple Conditional Questions

النظام يدعم شرط ظهور بسيط فقط.

السؤال الحالي يمكن أن يعتمد على إجابة سؤال سابق.

المنطق:

Parent Question
+
Operator
+
Expected Value

الـOperators المطلوبة مبدئيًا:

equals
contains

مثال:

Question A:
هل التصور مناسب؟

Answer:
needs_changes

Question B:
ما التعديل المطلوب؟

Question B يظهر عندما:

Question A
equals
needs_changes

لا يتم بناء:

- DSL
- Nested Rule Engine
- Complex Boolean Expressions

في هذه المرحلة.

---

# 10. Answers

لدينا نتيجة واحدة فقط للاستبيان.

كل Question لها Answer واحدة كحد أقصى.

الحقول المبدئية:

- id
- question_id
- value
- notes nullable
- needs_review boolean
- review_status
- reviewed_at nullable
- timestamps

يجب وجود Unique Constraint على:

question_id

بحيث لا يتم إنشاء عدة Answers لنفس السؤال.

عند تعديل الإجابة يتم استخدام:

updateOrCreate

أو المنطق المكافئ.

---

# 11. Answer Storage

القيم الفردية مثل:

text
textarea
number
date
yes_no
single_choice
select

يتم تخزين قيمة واحدة.

multi_choice:

يتم تخزين Array باستخدام JSON/Cast مناسب.

التفاصيل النهائية يتم اعتمادها في Phase 1.

---

# 12. Notes

كل سؤال في Frontend يحتوي على:

ملاحظات اختيارية

المختص يستطيع كتابة أي ملاحظة إضافية تخص السؤال.

مثال:

السؤال:
ما الحقول المطلوبة لبيانات المزرعة؟

المختص يختار:

- الاسم
- الهاتف
- الحالة

ثم يكتب في Notes:

"نحتاج أيضًا رقم السجل التجاري."

---

# 13. Needs Review

إذا كانت:

notes

غير فارغة:

needs_review = true

تلقائيًا.

لا يحدد المستخدم needs_review بنفسه.

المسار:

Specialist writes note
→ Answer becomes Needs Review
→ Admin reviews note
→ Admin may update existing Question
or
→ Add new Question
or
→ Add new Option
→ Mark note Reviewed

---

# 14. Review Status

استخدم Enum بسيط إذا كان مناسبًا:

pending
reviewed

لا يتم بناء Workflow أكبر من ذلك.

المطلوب فقط معرفة:

هل تمت مراجعة ملاحظة المختص أم لا؟

---

# 15. Business Questions vs Technical Decisions

المختص لا يتم سؤاله بلغة تقنية.

مثال غير صحيح:

"هل تريد Farm Status كـ Enum أم Table؟"

السؤال الصحيح:

"هل حالات المزرعة قيم ثابتة لا تتغير، أم يجب أن يستطيع مدير النظام إضافة وتعديل الحالات؟"

إذا اختار:

قيم ثابتة

يمكن أن ينتج التقرير:

Recommendation:
Enum

إذا اختار:

يمكن إضافتها وتعديلها

يمكن أن ينتج التقرير:

Recommendation:
Managed Lookup Table

مثل:

farm_statuses

---

# 16. Report Categories

يمكن أن تحتوي Question على:

report_category

الأنواع المبدئية:

field
lookup
relationship
workflow
rule
alert
report
general

الهدف هو مساعدة Technical Report Generator.

لا يعتبر هذا Generic Rule Engine.

---

# 17. Target Entity

يمكن أن تحتوي Question على:

target_entity

مثال:

farm
barn
battery
cage
rabbit
mating
birth

الهدف:

ربط الإجابة بالجزء الذي ستظهر تحته في التقرير التقني.

مثال:

target_entity:
farm

report_category:
field

---

# 18. Technical Specification Goal

بعد انتهاء الإجابات، لا نريد تقريرًا من نوع:

Question:
ما الحقول المطلوبة؟

Answer:
الاسم، الهاتف، الحالة.

بل نريد Technical Specification منظم.

مثال:

# Farm

## Fields

name
- type: string

phone
- type: string

status
- source: Farm Status Requirement

## Managed Lists

Farm Status

If editable:
Recommend:
farm_statuses table

If fixed:
Recommend:
Enum

---

# 19. Technical Specification Sections

التقرير النهائي يمكن أن يحتوي على:

# Entities

# Fields

# Fixed Values / Enums

# Managed Lookup Tables

# Relationships

# Workflow Requirements

# Business Rules

# Alerts

# Reports

# Needs Review

لا يتم اعتماد شكل التقرير النهائي بالكامل قبل Phase 4.

---

# 20. Needs Review in Technical Report

أي Answer تحتوي:

needs_review = true

ولا تزال:

review_status = pending

تظهر في التقرير تحت:

# Needs Review

مع:

- Section
- Question
- Current Answer
- Specialist Note

ولا يتم تحويل الملاحظة غير المعتمدة تلقائيًا إلى Migration Requirement.

---

# 21. Filament Administration Goal

Filament Admin يكون بسيطًا.

القائمة المطلوبة مبدئيًا:

Questionnaire

- Sections
- Questions
- Answers

Question Options لا تحتاج Resource مستقلة بالضرورة.

يفضل إدارتها داخل Question Resource باستخدام Repeater.

---

# 22. Section Resource

المطلوب:

List
Create
Edit
Delete
Ordering

Form:

Name

Description
Markdown Editor أو textarea مناسب حسب Core conventions.

Sort Order

---

# 23. Question Resource

Form مبدئي:

Section

Question Title

Help Text

Question Type

Required

Sort Order

Options Repeater
يظهر فقط عند الحاجة.

Conditional Question Settings
تظهر فقط عند تفعيل dependency.

Report Category

Target Entity

Metadata فقط إذا ظهرت لها حاجة فعلية.

---

# 24. Dynamic Question Form

Filament form يجب أن يتغير حسب:

QuestionType

TEXT:
لا Options.

TEXTAREA:
لا Options.

NUMBER:
يمكن لاحقًا إضافة:
min
max
step
إذا ظهرت حاجة فعلية.

DATE:
لا Options.

YES_NO:
القيم معروفة داخليًا:
yes / no

SINGLE_CHOICE:
Options Repeater.

MULTI_CHOICE:
Options Repeater.

SELECT:
Options Repeater.

---

# 25. Answers Administration

Filament Answers page تعرض الإجابات مجمعة حسب Section.

يجب أن يرى Admin:

Question

Readable Answer

Notes

Needs Review

Review Status

Edit

لا يتم عرض:

Raw JSON

Internal Value

IDs

إلا عند الحاجة التقنية.

---

# 26. Frontend Goal

واجهة المستخدم يجب أن تكون بسيطة جدًا.

Approved stack:

Bootstrap 5
Bootstrap RTL
Font Awesome 6
Tajawal
Blade
Lightweight JavaScript

لا يتم استخدام Filament UI في واجهة المختص.

---

# 27. Frontend Layout

Desktop:

Sidebar
+
Questions Area

Sidebar يتم توليدها من:

Sections

بالترتيب:

sort_order

يستطيع المستخدم التنقل بحرية بين الأقسام.

لا يوجد Session Flow معقد.

---

# 28. Question Rendering

Frontend يقرأ QuestionType ويولد Control مناسب.

text:
input text

textarea:
textarea

number:
input number

date:
input date

yes_no:
Yes / No controls

single_choice:
radio/cards

multi_choice:
checkbox/cards

select:
select

---

# 29. Optional Notes on Every Question

تحت كل سؤال يوجد:

ملاحظات إضافية
اختياري

Textarea صغيرة.

لا تكون بارزة أكثر من الإجابة الأساسية.

إذا كتب المستخدم بها:

needs_review = true

---

# 30. Answer Persistence

الإجابات تحفظ فعليًا في Database.

لا تعتمد على Browser Session.

إذا أغلق المستخدم:

Browser
Computer

ثم عاد لاحقًا:

الإجابات تظل محفوظة.

لا يوجد QuestionnaireSession Model.

لدينا Answer واحدة لكل Question.

---

# 31. Technical Report Frontend

Route لاحقًا مثل:

/technical-report

تعرض Technical Specification النهائي.

تحتوي على:

Print

ويستخدم Browser Print:

Save as PDF

لا نضيف PDF Package في البداية.

---

# 32. Repository Encoding Standard

هذه القواعد ثابتة للمشروع.

Encoding:

UTF-8 without BOM

Line Endings:

LF

Final Newline:

Yes

يجب الحفاظ على اللغة العربية بشكل صحيح.

---

# 33. File Editing Rules

قبل تعديل أي ملف:

Read it first.

استخدم targeted edits.

لا تعيد كتابة ملف كامل إذا كان تعديل صغير كافيًا.

إذا فشل Patch:

- لا تكرر نفس المحاولة
- افحص المحتوى الحالي
- افحص encoding
- افحص line endings
- ثم عدل

لا تعمل normalization لملفات غير مرتبطة بالمهمة.

لا تنشئ noisy diffs بدون داعٍ.

---

# 34. Filament Compatibility

يجب قراءة:

AGENTS.md

قبل تنفيذ أي Filament Resource.

ويجب التحقق من:

actual installed Filament version

من المشروع.

لا يتم افتراض namespace أو API من الذاكرة.

Existing working resources in the Laravel Core are the preferred implementation reference.

---

# 35. Testing Rules

لا يتم بناء Browser Screenshot Testing.

المستخدم يقوم بالمراجعة البصرية.

Codex مسؤول عن:

Automated Tests

للسلوك المهم.

---

# 36. Implementation Phases

## Phase 0 — Project Audit & Repository Hygiene

Goal:

فهم Laravel Core قبل تنفيذ المشروع الجديد.

Tasks:

- [x] Read AGENTS.md completely
- [x] Verify Laravel version
- [x] Verify PHP version
- [x] Verify Filament version
- [x] Verify database
- [x] Verify installed packages
- [x] Verify localization conventions
- [x] Verify Filament Resource conventions
- [x] Verify frontend conventions
- [x] Verify UTF-8
- [x] Verify BOM
- [x] Verify line endings
- [x] Review .editorconfig
- [x] Review .gitattributes
- [x] Verify text hygiene tooling if present
- [x] Document findings
- [x] Confirm Phase 1 architecture

Verified Core Findings:

- AGENTS.md matches the current Core stack in the audited areas and did not require correction during Phase 0.
- PHP CLI version verified: 8.2.12.
- Laravel version verified: 12.66.0.
- Filament version verified from composer.lock: 4.12.6.
- Database configuration supports multiple drivers, with MySQL defined and the default connection remaining environment-driven through `DB_CONNECTION`.
- The questionnaire plan file is valid UTF-8 without BOM and uses LF line endings; Arabic text rendering issues observed in terminal output are display-side mojibake, not a repository encoding defect.
- Audited directories `app/`, `resources/`, `lang/`, `database/`, and `docs/` currently contain no UTF-8 BOM files and no CRLF files.
- `.editorconfig` already enforces `charset = utf-8`, `end_of_line = lf`, and `insert_final_newline = true`.
- `.gitattributes` already enforces `* text=auto eol=lf`.
- No dedicated repository-wide text hygiene script or test exists at the moment; Phase 0 did not introduce one because no encoding or line-ending defect was found.
- Existing Filament resources confirm the current Core conventions: separate Resource / Schemas / Tables / Pages classes, translation-backed labels, session-persisted table state, modal filters with four columns when filters exist, `->iconButton()` record actions, and full-width admin pages.
- Localization conventions are aligned with AGENTS.md: admin content locales come from `config/core.php`, frontend localized routes come from `mcamara/laravel-localization`, and translatable content uses Spatie Translatable.
- Frontend baseline currently uses Blade + Vite + Tailwind CSS v4 in the starter project; the questionnaire-specific Bootstrap 5 / RTL / Font Awesome 6 / Tajawal stack remains a future approved direction for questionnaire frontend work rather than an existing Core-wide frontend implementation.

Status:
Completed

---

## Phase 1 — Core Data Model

Goal:

إنشاء قاعدة البيانات الأساسية فقط.

Expected scope:

- Sections Migration
- Questions Migration
- Question Options Migration
- Answers Migration
- Enums
- Models
- Relationships
- Casts
- Constraints
- Indexes
- Tests

Tasks:

- [x] Finalize table structure
- [x] Create migrations
- [x] Create QuestionType enum
- [x] Create AnswerReviewStatus enum if approved
- [x] Create models
- [x] Add relationships
- [x] Add casts
- [x] Add constraints
- [x] Add indexes
- [x] Add database tests
- [x] Run migrations
- [x] Run tests
- [x] Update plan

Status:
Completed

---

## Phase 2 — Filament Administration

Goal:

إدارة محتوى الاستبيان والإجابات من لوحة التحكم.

Tasks:

- [ ] Section Resource
- [ ] Question Resource
- [ ] Dynamic Question Type form
- [ ] Options Repeater
- [ ] Conditional Question settings
- [ ] Report Category
- [ ] Target Entity
- [ ] Answers administration
- [ ] Needs Review indicators
- [ ] Review action
- [ ] Filament permissions according to Core
- [ ] Automated tests
- [ ] Update plan

Status:
Pending

---

## Phase 3 — Questionnaire Frontend

Goal:

واجهة بسيطة للمختص مشابهة في بساطتها للنموذج المعتمد.

Tasks:

- [ ] Questionnaire route
- [ ] Bootstrap layout
- [ ] RTL
- [ ] Tajawal
- [ ] Font Awesome
- [ ] Sidebar from Sections
- [ ] Section description rendering
- [ ] Question renderer
- [ ] Answer save/update
- [ ] Notes save/update
- [ ] Needs Review auto flag
- [ ] Simple conditional questions
- [ ] Restore existing answers
- [ ] Responsive structure
- [ ] Automated behavior tests
- [ ] Update plan

Status:
Pending

---

## Phase 4 — Technical Specification Generator

Goal:

تحويل الإجابات إلى Technical Specification واضح.

Tasks:

- [ ] Define structured report format
- [ ] Group by Target Entity
- [ ] Fields section
- [ ] Enum recommendations
- [ ] Managed Lookup recommendations
- [ ] Relationships
- [ ] Workflow requirements
- [ ] Business rules
- [ ] Alerts
- [ ] Reports
- [ ] Needs Review
- [ ] Technical report page
- [ ] Print stylesheet
- [ ] Automated report tests
- [ ] Update plan

Status:
Pending

---

## Phase 5 — Final Review & Polish

Goal:

مراجعة التجربة الكاملة.

Tasks:

- [ ] Review Admin UX
- [ ] Review questionnaire UX
- [ ] Review report output
- [ ] Fix confirmed usability problems
- [ ] Validate answer persistence
- [ ] Validate Needs Review process
- [ ] Validate Print output
- [ ] Final automated test run
- [ ] Update documentation

Status:
Pending

---

# 37. Architectural Decisions

## AD-001

One questionnaire only.

Status:
Approved

## AD-002

No Questionnaire Sessions.

Status:
Approved

## AD-003

One Answer per Question.

Status:
Approved

## AD-004

Question Options are managed in Question Resource through Repeater.

Status:
Approved

## AD-005

Every Question supports Optional Notes.

Status:
Approved

## AD-006

Notes automatically trigger Needs Review.

Status:
Approved

## AD-007

Technical decisions are derived from business answers.

Status:
Approved

## AD-008

Specialist is not asked Laravel/database terminology.

Status:
Approved

## AD-009

Frontend uses Bootstrap 5 + Bootstrap RTL + Font Awesome 6 + Tajawal.

Status:
Approved

## AD-010

Technical report initially uses browser Print instead of PDF library.

Status:
Approved

## AD-011

Questionnaire Sections use a two-level self-referencing hierarchy:

Main Section
→ Subsection

Status:
Approved

---

# 38. Deferred Decisions

The following remain deferred until the relevant Phase:

- Exact report metadata fields
- Exact Technical Specification transformation rules
- Whether number questions require min/max/step
- Whether Question metadata needs JSON
- Whether Answer edit history is required
- Whether report should later support direct PDF generation

---

# 39. Development Rule

Any feature that does not directly support:

Question
→ Answer
→ Optional Note
→ Needs Review
→ Technical Specification

must not be added without explicit approval.

---

# 40. Execution Log

Append entries only.

Do not delete previous execution history.

Format:

## YYYY-MM-DD — Phase X

### Planned

### Implemented

### Files Created

### Files Modified

### Tests

### Findings

### Decisions

### Issues

### Next Step

## 2026-08-15 - Phase 0

### Planned

- Audit the current Laravel Core before any questionnaire implementation.
- Verify AGENTS.md against the actual repository state.
- Confirm repository text hygiene and file editing safety conditions.
- Stop after documenting Phase 0 findings and Phase 1 recommendations.

### Implemented

- Read AGENTS.md and the questionnaire implementation plan completely.
- Verified PHP, Laravel, Filament, Node, npm, database configuration, and key installed package versions from the repository.
- Inspected current Filament resources, localization helpers, admin panel provider, user model, routes, and frontend package configuration to capture actual project conventions.
- Audited UTF-8 validity, BOM presence, and LF / CRLF status across `app/`, `resources/`, `lang/`, `database/`, and `docs/`.
- Reviewed `.editorconfig` and `.gitattributes`.
- Confirmed Phase 1 should retain the approved simplified questionnaire architecture with no questionnaire session layer.

### Files Created

- None.

### Files Modified

- `docs/QUESTIONNAIRE_IMPLEMENTATION_PLAN.md`

### Tests

- `php -v`
- `php artisan --version`
- Read-only repository inspection of composer metadata, configuration, Filament resources, and localization helpers
- Read-only UTF-8 / BOM / line-ending audit across the target directories

### Findings

- AGENTS.md is materially aligned with the actual Core and required no factual correction in Phase 0.
- Verified versions: PHP 8.2.12, Laravel 12.66.0, Filament 4.12.6, Node 22.14.0, npm 10.9.2.
- MySQL remains part of the intended Core stack and is fully configured, while the active default connection stays environment-driven.
- Existing resource patterns are suitable as the direct reference for future questionnaire Resources.
- The repository is already stable on the target text format standard in the audited directories.
- No existing automated text hygiene tool was found.

### Decisions

- Phase 0 remains documentation and audit only; no questionnaire application code was created.
- The plan continues to use the approved simplified architecture: one questionnaire, no questionnaire sessions, one current answer per question, optional notes triggering review, and a later technical specification output.

### Issues

- `composer show` and `git status` are blocked in this execution environment by Git safe-directory ownership checks, so package verification relied on `composer.lock`, `composer.json`, and installed project code instead.

### Next Step

- Await approval for Phase 1 - Core Data Model.

## 2026-08-15 - Phase 1

### Planned

- Build the minimal questionnaire domain foundation only.
- Add the approved two-level section hierarchy using one self-referencing table.
- Create migrations, enums, models, relationships, casts, constraints, and focused database tests.
- Stop before any Filament, frontend, or report-generation work.

### Implemented

- Created questionnaire migrations for sections, questions, question options, and answers.
- Added `QuestionType`, `QuestionDependencyOperator`, and `AnswerReviewStatus` enums.
- Added questionnaire models with relationships, defaults, casts, and safe domain rules.
- Implemented automatic `notes` → `needs_review` synchronization and `review_status` → `reviewed_at` synchronization in the answer model.
- Added focused feature tests covering hierarchy, deletion safety, relationships, uniqueness constraints, JSON answer storage, notes review behavior, and reviewed-state timestamps.
- Updated the implementation plan to reflect the approved main-section / subsection hierarchy and Phase 1 completion.

### Files Created

- `app/Enums/Questionnaire/QuestionType.php`
- `app/Enums/Questionnaire/QuestionDependencyOperator.php`
- `app/Enums/Questionnaire/AnswerReviewStatus.php`
- `lang/en/enums/questionnaire.php`
- `lang/ar/enums/questionnaire.php`
- `tests/Feature/QuestionnaireCoreDataModelTest.php`

### Files Modified

- `database/migrations/2026_08_15_144817_create_questionnaire_sections_table.php`
- `database/migrations/2026_08_15_144818_create_questionnaire_questions_table.php`
- `database/migrations/2026_08_15_144819_create_questionnaire_question_options_table.php`
- `database/migrations/2026_08_15_144820_create_questionnaire_answers_table.php`
- `app/Models/QuestionnaireSection.php`
- `app/Models/QuestionnaireQuestion.php`
- `app/Models/QuestionnaireQuestionOption.php`
- `app/Models/QuestionnaireAnswer.php`
- `docs/QUESTIONNAIRE_IMPLEMENTATION_PLAN.md`

### Tests

- Questionnaire core data model feature tests
- Migration execution against SQLite in-memory configuration

### Findings

- The two-level section hierarchy fits cleanly in a single self-referencing table without needing a tree package.
- Safe deletion is handled by FK restrictions for parent sections and section-owned questions.
- A single JSON answer value column can support scalar and array answers while keeping the model API simple.

### Decisions

- `report_category` and `target_entity` remain nullable strings in Phase 1.
- `metadata` remains deferred and was not added.
- Dependency parent deletion clears only `depends_on_question_id`; dependent questions remain intact.

### Issues

- None beyond the previously known terminal mojibake display issue for Arabic text in this environment.

### Next Step

- Await approval for Phase 2 - Filament Administration.

---

# 41. Current Phase

Current Phase:

Phase 1 — Core Data Model

Status:

Completed

Next Action:

Await approval for Phase 2 - Filament Administration.
