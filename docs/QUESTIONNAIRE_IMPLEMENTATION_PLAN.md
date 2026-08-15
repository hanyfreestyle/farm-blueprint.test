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
→ Questions
→ Question Options
→ Answers
→ Technical Report

لدينا استبيان واحد فقط.

لا توجد Questionnaire Sessions.

كل Question لها Answer حالية واحدة فقط.

عند تعديل الإجابة يتم تحديث نفس Answer.

---

# 5. Sections

الأقسام هي المراحل الرئيسية للدراسة.

وهي المصدر الذي يتم منه بناء القائمة الجانبية في واجهة المستخدم.

الحقول المبدئية:

- id
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
- metadata nullable
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

- [ ] Read AGENTS.md completely
- [ ] Verify Laravel version
- [ ] Verify PHP version
- [ ] Verify Filament version
- [ ] Verify database
- [ ] Verify installed packages
- [ ] Verify localization conventions
- [ ] Verify Filament Resource conventions
- [ ] Verify frontend conventions
- [ ] Verify UTF-8
- [ ] Verify BOM
- [ ] Verify line endings
- [ ] Review .editorconfig
- [ ] Review .gitattributes
- [ ] Verify text hygiene tooling if present
- [ ] Document findings
- [ ] Confirm Phase 1 architecture

Status:
Pending

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

- [ ] Finalize table structure
- [ ] Create migrations
- [ ] Create QuestionType enum
- [ ] Create AnswerReviewStatus enum if approved
- [ ] Create models
- [ ] Add relationships
- [ ] Add casts
- [ ] Add constraints
- [ ] Add indexes
- [ ] Add database tests
- [ ] Run migrations
- [ ] Run tests
- [ ] Update plan

Status:
Pending

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

---

# 41. Current Phase

Current Phase:

Phase 0 — Project Audit & Repository Hygiene

Status:

Pending

Next Action:

Read AGENTS.md and inspect the existing Laravel Core before any questionnaire implementation.