# Questionnaire Implementation Plan

> هذا الملف هو **خطة التنفيذ التقنية وسجل تطور أداة الـQuestionnaire / Blueprint**.
>
> المرجع الأساسي الحالي لقواعد المشروع والقرارات المعتمدة هو:
>
> `docs/FARM_BLUEPRINT_PROJECT_CONTEXT.md`
>
> إذا تعارض وصف تاريخي قديم داخل هذا الملف مع السياق الحالي، تكون الأولوية دائمًا لـ:
>
> **أحدث قرار معتمد → FARM_BLUEPRINT_PROJECT_CONTEXT.md → الكود الحالي → السجل التاريخي القديم.**

---

## 1. هدف المشروع

المشروع عبارة عن تطبيق مخصص لبناء ومراجعة Blueprint تفصيلي لنظام إدارة مزرعة الأرانب قبل تنفيذ النظام النهائي.

هذا المشروع **ليس نظام إدارة المزرعة النهائي**، وليس منصة استبيانات عامة.

المسار الأساسي:

```text
المرجع الوظيفي
    ↓
الأقسام
    ↓
الأسئلة
    ↓
الإجابات
    ↓
المراجعة
    ↓
Software Requirements / Business Rules
    ↓
Technical / Implementation Outputs
    ↓
Blueprint قابل للتنفيذ
```

المختص يجيب عن أسئلة وظيفية وتشغيلية واضحة، ولا يُطلب منه اتخاذ قرارات Laravel أو Database مباشرة.

---

## 2. المراجع الأساسية

### المرجع الأول لقواعد المشروع الحالية

`docs/FARM_BLUEPRINT_PROJECT_CONTEXT.md`

يجب قراءته قبل أي تعديل في المستودع.

### المرجع الوظيفي عند إنشاء أو مراجعة الأسئلة

`تصور_مشروع_الارانب.md`

يستخدم أثناء بناء الدراسة والأسئلة فقط، مع الحفاظ على المصطلحات والمنطق الموجودين فيه وعدم اختراع متطلبات غير مدعومة.

### قواعد Laravel Core

`AGENTS.md`

يستخدم عند تنفيذ أو مراجعة الكود وFilament والـconventions التقنية.

---

## 3. نطاق المشروع

المشروع يدعم حاليًا:

- Main Sections.
- Subsections.
- Questions.
- Question Options.
- Answers.
- Optional Notes.
- Needs Review.
- Simple Dependencies.
- Questionnaire Guides.
- Final Requirements Input.
- تقارير ومخرجات تقنية مشتقة من القرارات المعتمدة.

المشروع ليس:

- Generic Questionnaire Builder.
- Survey SaaS.
- Multi Tenant Questionnaire Platform.
- Generic Workflow Builder.
- Generic Rules DSL.
- Generic Decision Engine.

أي abstraction جديدة لا تضاف إلا لحاجة فعلية معتمدة.

---

## 4. البنية المنطقية الحالية للأقسام

الأقسام الرئيسية المعتمدة حاليًا:

```text
1. إدارة البيانات الأساسية — Master / Reference Data
2. هيكل المزرعة — Farm Structure
3. إعدادات التشغيل ودورة الإنتاج — Operation Settings
4. تكوين وإدخال القطيع — Herd Setup
5. الحركات ودورة التشغيل الفعلية — Workflow
6. التقارير والإشعارات ومؤشرات الأداء — Reports
```

### 4.1 إدارة البيانات الأساسية

```text
إدارة البيانات الأساسية
├── الأنشطة التشغيلية
├── الأغراض الإنتاجية
├── مؤشرات السلالات
├── بيانات السلالات
├── المستخدمون وفريق التشغيل
├── أسباب النقل
├── أسباب النفوق
├── أسباب الاستبعاد
├── أسباب الخروج
├── أسباب تغيير الذكر
├── المحافظات
├── المدن
├── أنظمة التهوية
├── أنظمة التبريد
└── أنظمة التدفئة
```

> **ملفات إعدادات التشغيل لم تعد Subsection داخل Master Data.**
>
> سيتم التعامل معها لاحقًا داخل القسم الرئيسي المستقل:
>
> `إعدادات التشغيل ودورة الإنتاج`.

### 4.2 هيكل المزرعة

```text
هيكل المزرعة
├── بيانات المزرعة
├── بيانات العنبر
├── بيانات البطارية
└── بيانات القفص / العين
```

العلاقة الهيكلية:

```text
Farm
  ↓
Barn
  ↓
Battery
  ↓
Cage / Cell
```

Farm / Barn / Battery / Cage كيانات هيكلية فعلية وليست Lookup Lists.

---

## 5. شجرة Seeders — قاعدة تنظيمية معتمدة

يجب أن تعكس شجرة ملفات Question Seeders شجرة الأقسام نفسها حتى لا توجد أسماء تاريخية مضللة.

الشكل المعتمد:

```text
database/seeders/Questions/
├── Concerns/
│
├── MasterData/
│   ├── OperationalActivitiesQuestionsSeeder.php
│   ├── ProductionPurposesQuestionsSeeder.php
│   ├── BreedMetricsQuestionsSeeder.php
│   ├── BreedDataQuestionsSeeder.php
│   ├── TransferReasonsQuestionsSeeder.php
│   ├── MortalityReasonsQuestionsSeeder.php
│   ├── ExclusionReasonsQuestionsSeeder.php
│   ├── ExitReasonsQuestionsSeeder.php
│   ├── MaleChangeReasonsQuestionsSeeder.php
│   ├── GovernoratesQuestionsSeeder.php
│   ├── CitiesQuestionsSeeder.php
│   ├── VentilationSystemsQuestionsSeeder.php
│   ├── CoolingSystemsQuestionsSeeder.php
│   └── HeatingSystemsQuestionsSeeder.php
│
├── FarmStructure/
│   ├── FarmDataQuestionsSeeder.php
│   ├── BarnDataQuestionsSeeder.php
│   ├── BatteryDataQuestionsSeeder.php
│   └── CageDataQuestionsSeeder.php
│
├── OperationSettings/     ← عند بدء أسئلة هذا القسم
├── HerdSetup/             ← عند بدء أسئلة هذا القسم
├── Workflow/              ← عند بدء أسئلة هذا القسم
└── Reports/               ← عند بدء أسئلة هذا القسم
```

لا يجوز وضع Master Data Seeder داخل `FarmStructure` أو العكس لمجرد أن الملف قديم أو كان موجودًا سابقًا بهذا الشكل.

---

## 6. Orchestrators الحالية

### كل الأسئلة الحالية

`database/seeders/QuestionnaireQuestionsSeeder.php`

هو الـorchestrator العام الحالي.

يستدعي:

```text
QuestionnaireMasterDataQuestionsSeeder
QuestionnaireFarmStructureQuestionsSeeder
```

ومستقبلًا يضاف إليه orchestrator كل Main Section عند بدء أسئلته فعليًا.

### Master Data

`database/seeders/QuestionnaireMasterDataQuestionsSeeder.php`

يستدعي فقط Seeders الموجودة تحت:

`Questions/MasterData/`

### Farm Structure

`database/seeders/QuestionnaireFarmStructureQuestionsSeeder.php`

يستدعي:

- `FarmDataQuestionsSeeder`
- `BarnDataQuestionsSeeder`
- `BatteryDataQuestionsSeeder`
- `CageDataQuestionsSeeder`

### DatabaseSeeder

`DatabaseSeeder` يجب أن يستدعي:

1. Seeders الأساسية للنظام.
2. `QuestionnaireSectionSeeder`.
3. `QuestionnaireQuestionsSeeder`.

وبذلك يجب أن يؤدي Seed كامل إلى بناء الحالة الحالية من الصفر بدون الاعتماد على سجلات قديمة.

---

## 7. طريقة التشغيل الحالية أثناء إعادة البناء

المستخدم يعمل حاليًا محليًا باستخدام:

```bash
php artisan migrate:refresh --seed
```

هذه الطريقة **تدمر بيانات قاعدة البيانات الحالية وتعيد بناءها بالكامل**.

تم اعتمادها مؤقتًا في هذه المرحلة لأن الإجابات القديمة تم الاستغناء عنها ويجري الآن تنظيف الهيكل والـSeeders قبل بدء دورة إجابات جديدة.

### النتيجة المطلوبة من كل تشغيل حالي

بعد `migrate:refresh --seed` يجب أن:

- تُنشأ الأقسام الرئيسية بالترتيب الصحيح.
- تُنشأ Subsections في الـParent الصحيح.
- تُزرع كل الأسئلة المعتمدة حاليًا.
- لا تعتمد أي أسئلة على بيانات متبقية من تشغيل سابق.
- لا تختفي مجموعة أسئلة بسبب عدم إضافتها إلى orchestrator.

---

## 8. الانتقال لاحقًا إلى الحفاظ على الإجابات

بمجرد بدء دورة إجابات نريد الاحتفاظ بها، لا يكون `migrate:refresh --seed` أسلوب التشغيل العادي على قاعدة البيانات التي تحتوي هذه الإجابات.

يصبح المبدأ:

```text
Stable Section Record
+ Stable section_id
+ Stable seed_key
+ Stable option value
+ preserveAnswers = true
```

### قاعدة مهمة

هوية السؤال:

```text
section_id + seed_key
```

هوية الاختيار:

```text
question_id + option.value
```

لا يجب تغيير `seed_key` لمجرد تعديل صياغة عنوان السؤال إذا ظل نفس القرار الوظيفي.

لا يجب تغيير `option.value` لمجرد تعديل Label إذا ظل نفس المعنى.

---

## 9. نقل الأقسام بعد وجود إجابات

إذا احتجنا لاحقًا إلى إعادة تصنيف Subsection مع وجود إجابات:

المسموح:

```text
نفس Section Record
→ تعديل parent_id
→ الحفاظ على Section ID
```

غير المسموح:

```text
Delete old subsection
→ Create new subsection
→ Recreate questions
```

لأن ذلك يكسر الهوية وقد يؤدي إلى فقد الإجابات والعلاقات.

---

## 10. Question Seeder Sync

الخدمة الموحدة:

`app/Services/Questionnaire/QuestionSeederSyncService.php`

تستخدم بدل كتابة منطق مزامنة مختلف داخل كل Seeder.

الشكل العام:

```php
app(QuestionSeederSyncService::class)->sync(
    mainSectionName: 'هيكل المزرعة',
    sectionName: 'بيانات القفص / العين',
    questions: $questions,
    prune: true,
    preserveAnswers: true,
);
```

`prune` يزيل الأسئلة التي لم تعد موجودة في تعريف Seeder إذا كان ذلك آمنًا وفق قواعد الخدمة.

`preserveAnswers` يجب استخدامه عند التعامل مع إجابات يجب الاحتفاظ بها، وأي تغيير تدميري غير آمن يجب أن يوقف المزامنة برسالة واضحة بدل حذف البيانات بصمت.

---

## 11. Questions

كل Question تتبع Subsection واحدًا.

الحقول الأساسية الحالية تشمل:

- `id`
- `section_id`
- `seed_key`
- `title`
- `help_text`
- `type`
- `is_required`
- `sort_order`
- `depends_on_question_id`
- `dependency_operator`
- `dependency_value`
- `report_category`
- `target_entity`

كل سؤال يجب أن يؤدي إلى قرار قابل للتحويل إلى Requirement أو Rule أو Relationship أو Validation أو Workflow Decision.

---

## 12. Question Types الحالية

الـEnum الحالي:

`App\Enums\Questionnaire\QuestionType`

يدعم:

```text
text
textarea
number
date
yes_no
single_choice
multi_choice
select
```

لا يتم افتراض Question Type جديد قبل مراجعة الـEnum والكود الحالي واعتماد الحاجة إليه.

---

## 13. Question Options

تستخدم مع:

- `single_choice`
- `multi_choice`
- `select`

كل Option لها `value` ثابت يستخدم كهوية منطقية للاختيار، و`label` قابل لتحسين الصياغة دون تغيير المعنى.

عند وجود إجابات مهمة، تغيير `value` يعتبر تغييرًا تدميريًا محتملًا ويجب التعامل معه بحذر.

---

## 14. Dependencies

الدعم الحالي يعتمد على:

```text
EQUALS
CONTAINS
```

ويتم تمثيله بواسطة:

`QuestionDependencyOperator`

لا يتم اختراع Operators جديدة دون مراجعة الـEnum والـCondition Evaluator الحاليين.

Dependency تستخدم فقط عندما يكون ظهور السؤال مرتبطًا فعليًا بإجابة سؤال آخر.

السؤال المعتمد الذي يجب أن يظهر دائمًا لا يتم إخفاؤه بشرط مصطنع.

---

## 15. Answers

النموذج الحالي يعتمد على:

- Answer واحدة حالية لكل Question.
- تخزين القيمة بما يناسب النوع.
- `multi_choice` كقيمة Array/JSON.
- Notes اختيارية.
- Needs Review.
- Review Status.

الإجابة تعدل نفس Answer الحالية بدل إنشاء نسخ مستقلة لنفس Question.

لا يوجد حاليًا Questionnaire Sessions كطبقة مستقلة.

---

## 16. Notes وNeeds Review

كل سؤال يمكن أن يحتوي على Notes اختيارية.

الملاحظات تستخدم لتسجيل نقطة تحتاج مراجعة أو قرار إضافي.

المبدأ التاريخي الحالي:

```text
Specialist adds note
→ Answer may require review
→ Review occurs
→ Existing Question / Option may be corrected
   or a new explicit Question is added
→ Review closes
```

الملاحظات المفتوحة لا تتحول تلقائيًا إلى Requirements نهائية بدون مراجعة.

---

## 17. report_category وtarget_entity

`report_category` يساعد على تفسير نوع القرار الناتج من السؤال.

أمثلة مستخدمة في المشروع:

- `field`
- `field_rule`
- `rule`
- `business_rule`
- `relationship`
- `relationship_rule`
- `workflow_rule`
- `lifecycle_rule`
- `lookup_values`
- `value_management`
- `calculation_rule`
- `ui_requirement`
- `manual_review`

`target_entity` يحدد الكيان أو الجزء الذي يتأثر بالقرار، مثل:

```text
farm
barn
battery
cage
breed
city
...
```

لا يتم تحويل هذه الحقول إلى DSL أو Rule Engine عام.

---

## 18. فلسفة تصميم الأسئلة

السؤال الجيد ينتج قرارًا واضحًا مثل:

- Field.
- Required / Nullable.
- Relationship.
- Validation Rule.
- Lifecycle Rule.
- Workflow Rule.
- State / Status Decision.
- Audit / History Rule.
- Configuration Rule.
- UI Requirement.

عند إنشاء أسئلة جديدة يجب:

1. قراءة الجزء المقابل من `تصور_مشروع_الارانب.md`.
2. مراجعة الأسئلة الموجودة حاليًا.
3. منع التكرار.
4. تحويل النقاط غير المحسومة إلى أسئلة قرار.
5. استخدام `seed_key` ثابت.
6. استخدام Question Types الحالية فقط.
7. مراجعة Dependencies الحالية قبل إضافة شرط.
8. تحديد `report_category` و`target_entity`.
9. التفكير من البداية في الحفاظ على الإجابات المستقبلية.

---

## 19. Farm Structure — الاتجاه الحالي

تم فصل Farm Structure عن Master Data.

### Farm

`بيانات المزرعة` جزء من `هيكل المزرعة`.

### Barn

العنبر Child من المزرعة ويحتوي قواعده الهيكلية والتشغيلية الخاصة.

### Battery

البطارية Child من العنبر، وتحتوي البنية التي تستخدم لتوليد الأقفاص.

### Cage

القفص Child مولد من البطارية وليس Record ينشأ يدويًا بصورة مستقلة.

الاتجاه الحالي للقفص:

```text
Battery Structure
→ Generate Cages
→ Fixed Cage Identity
→ Review / Activation
→ Operational Cage
→ Actions + History
```

القفص بعد التفعيل لا يعامل كـEdit Form عادي لتغيير هويته الأساسية.

التغييرات التشغيلية يتم تمثيلها عبر Actions / Events مع History/Audit بدل تعديل قيم الحالة مباشرة بدون أثر تاريخي.

QR Code جزء من هوية الوصول الميداني للقفص ويرتبط بهويته/كوده.

---

## 20. Frontend

واجهة المختص الحالية مبنية على:

- Blade.
- Bootstrap 5.
- Bootstrap RTL.
- Tajawal.
- Font Awesome 6.
- JavaScript خفيف.

الواجهة عربية RTL.

التنقل الحالي يعتمد على:

```text
Home
→ Main Section
→ Subsection
→ One Question per Step
→ Save & Continue
→ Completion
```

الإجابات تحفظ في قاعدة البيانات وليست معتمدة على Browser Session.

---

## 21. Filament Administration

Filament يستخدم لإدارة:

- Sections.
- Questions.
- Answers.
- Question Options داخل Question Resource عند الحاجة.

المطلوب الحفاظ على توافق التنفيذ مع Laravel 12 / PHP 8.2 / Filament 4 والكود الموجود فعليًا في المشروع.

---

## 22. Questionnaire Guide

الـGuides تشرح معنى الأسئلة وحدود تفسيرها ولا تعيد تخزين الإجابات كنسخة ثانية من الحقيقة.

المسار:

`docs/questionnaire-guide/`

كل Guide يجب أن يستخدم `Question Key / seed_key` للربط بين السؤال وبين المتطلبات الناتجة عنه.

---

## 23. Final Requirements Input

المدخل النهائي لوكيل كتابة Requirements يجب أن يعتمد على:

- الأسئلة ذات `seed_key` معروف.
- الإجابات المعتمدة.
- عدم وجود مراجعة مفتوحة مؤثرة.
- استبعاد `manual_review` من التحويل المباشر إلى Requirement.
- استبعاد `*.additional_requirements` كقرار نهائي مباشر حتى يتم تحويل محتواه إلى سؤال واضح عند الحاجة.

---

## 24. مصادر Requirements Agent

بعد انتهاء مرحلة الأسئلة والإجابات، يعتمد Requirements Agent على:

1. `docs/FARM_BLUEPRINT_PROJECT_CONTEXT.md`
2. Questionnaire Guide الخاص بالقسم.
3. Final Requirements Input.
4. `docs/questionnaire-guide/REQUIREMENTS_CONFLICTS.md`

ولا يرجع إلى `تصور_مشروع_الارانب.md` ليغيّر قرارًا نهائيًا سبق اعتماده.

---

## 25. التعامل مع التعارضات

إذا ظهر تعارض بين إجابات أو قرارات:

- لا يحسم الوكيل التعارض من نفسه.
- لا يخترع حلًا وسطًا.
- لا يستخدم المرجع القديم لتجاوز أحدث قرار.
- يسجل التعارض في `REQUIREMENTS_CONFLICTS.md`.
- يحدد Question Keys المتأثرة.
- ينتظر قرار المستخدم.

---

## 26. Technical Outputs

المخرجات التقنية يجب أن تكون مشتقة من قرارات واضحة، وليس مجرد إعادة عرض سؤال/إجابة.

أمثلة:

```text
Questions + Answers
        ↓
Entities
Fields
Relationships
Enums / Fixed Values
Managed Lookup Tables
Business Rules
Workflow Rules
UI Requirements
Needs Review
```

التحويل يجب أن يكون قابلًا للمراجعة والتتبع إلى `seed_key` الأصلي.

---

## 27. قواعد الحذف والتعديل الآمن

### Sections

لا يحذف Main Section إذا كان تحته Subsections مستخدمة.

لا يحذف Subsection عليها Questions/Answers مهمة لمجرد إعادة التنظيم.

### Questions

عند وجود إجابات مهمة:

- لا يحذف Question ثم يعاد إنشاؤه تحت `seed_key` جديد لنفس القرار.
- لا تغير Option Values بدون مراجعة أثر الإجابات.
- لا يستخدم `prune` تدميريًا بدون `preserveAnswers` عندما نكون في وضع الحفاظ على الإجابات.

---

## 28. Repository Encoding Standard

كل ملفات النصوص في المستودع يجب أن تكون:

```text
Encoding: UTF-8 without BOM
Line Endings: LF
Final Newline: Yes
```

العربية يجب أن تظهر كنص عربي حقيقي، وليس Mojibake من نوع:

```text
Ù‡Ø°Ø§
Ø§Ù„Ù…Ø´Ø±ÙˆØ¹
```

إذا ظهر هذا الشكل، يعتبر الملف بحاجة إلى إصلاح فعلي قبل الاعتماد عليه.

---

## 29. قواعد تعديل الملفات

قبل تعديل أي ملف:

1. اقرأ المحتوى الحالي.
2. راجع `FARM_BLUEPRINT_PROJECT_CONTEXT.md`.
3. استخدم تعديلًا واضحًا ومقصودًا.
4. لا تنشئ أسماء بديلة مضللة لنفس المسؤولية.
5. لا تترك Compatibility Wrapper قديمًا إذا أصبح اسمه يسبب التباسًا معماريًا، إلا إذا كانت هناك ضرورة فعلية ومعلنة.
6. لا تعدل `تصور_مشروع_الارانب.md` بدون طلب صريح.

---

## 30. GitHub Workflow

المستودع Read Only افتراضيًا.

لا يتم تنفيذ كتابة على GitHub إلا بطلب صريح من المستخدم.

عند وجود طلب صريح:

```text
Assistant modifies GitHub master
→ Assistant verifies repository state
→ User pulls locally
→ User runs local tests / seed
```

بعد اكتمال التعديلات:

```bash
git pull origin master
```

وفي مرحلة إعادة البناء الحالية:

```bash
php artisan migrate:refresh --seed
```

---

## 31. الوضع الحالي — 2026-08-24

### مكتمل تقنيًا

- Core questionnaire data model.
- Question Types.
- Simple Dependencies.
- Filament administration.
- Respondent frontend.
- Answer persistence.
- Needs Review model.
- Technical report foundations.
- `QuestionSeederSyncService`.
- Stable `seed_key` approach.
- فصل `Farm Structure` عن `Master Data`.
- فصل ملفات Question Seeders حسب شجرة الأقسام.
- إعادة بناء أسئلة `Cage` وفق النموذج الهيكلي/التشغيلي الجديد.

### مرحلة العمل الحالية

المشروع في مرحلة:

**تنظيف البنية والأسئلة قبل بدء دورة إجابات جديدة مستقرة.**

الإجابات القديمة تم حذفها عمدًا بإعادة بناء قاعدة البيانات.

### طريقة التشغيل الحالية

```bash
php artisan migrate:refresh --seed
```

### الهدف قبل بدء الإجابات الجديدة

- تثبيت شجرة الأقسام.
- تثبيت شجرة Seeders.
- إزالة الأقسام الموجودة في المكان الخطأ.
- مراجعة الأسئلة الأساسية.
- التأكد أن كل Seeder مستدعى من orchestrator الصحيح.
- التأكد أن Fresh Seed يعيد بناء الحالة الكاملة.
- بعدها تبدأ الإجابات الجديدة.

---

## 32. المرحلة التالية بعد تثبيت الهيكل

بعد التأكد من سلامة الـSeeders محليًا:

1. مراجعة الأقسام واحدًا واحدًا.
2. مراجعة الأسئلة قبل الإجابة.
3. الإجابة على الأسئلة المعتمدة.
4. مراجعة Notes / Needs Review.
5. تثبيت Questionnaire Guides.
6. إنشاء Final Requirements Input.
7. توليد Requirements / Business Rules.
8. مراجعة التعارضات.
9. بناء الـBlueprint النهائي.

---

## 33. ملخص تاريخ التنفيذ

هذا القسم **تاريخي** ولا يعلو على الوضع الحالي الموثق أعلاه.

### Phase 0 — Project Audit & Repository Hygiene

تمت مراجعة Laravel Core والإصدارات والـconventions وبنية المشروع قبل التنفيذ.

**Status:** Completed.

### Phase 1 — Core Data Model

تم إنشاء:

- Questionnaire Sections.
- Questions.
- Question Options.
- Answers.
- Enums.
- Models.
- Relationships.
- Constraints.
- Tests.

**Status:** Completed.

### Phase 2 — Filament Administration

تم إنشاء إدارة الأقسام والأسئلة والإجابات وOptions وDependencies والمراجعات داخل Filament.

**Status:** Completed.

### Phase 3A — Questionnaire Frontend Pilot

تم بناء واجهة المختص وحفظ الإجابات والتنقل والـprogress والـconditional questions.

**Status:** Completed.

### Phase 3A-R1 وما بعدها

تمت عدة مراجعات للـUX والتنقل والـHome/Main Section hierarchy والـvisibility والـstepper.

هذه التعديلات تعتبر سجلًا تاريخيًا للتطور وليست بنية مستقلة جديدة.

### Phase 3B — Master Technical Specification Pilot

تم بناء أساس تقرير تقني رئيسي مشتق من بيانات الاستبيان واختبار التحويل على Pilot مبكر.

**Status:** Completed as Pilot.

> ملاحظة: بعض افتراضات الـPilot القديمة تم تجاوزها لاحقًا أثناء إعادة تنظيم الأقسام والأسئلة، لذلك أي تنفيذ جديد يعتمد على السياق الحالي وQuestionnaire Guides وFinal Requirements Input.

---

## 34. Architectural Decisions المستمرة

### AD-001

يوجد Questionnaire واحد للدراسة الحالية.

**Status:** Approved.

### AD-002

لا توجد Questionnaire Sessions كطبقة مستقلة حاليًا.

**Status:** Approved.

### AD-003

لكل Question إجابة حالية واحدة.

**Status:** Approved.

### AD-004

Question Options مرتبطة بالسؤال وتدار ضمن Question Resource عند الحاجة.

**Status:** Approved.

### AD-005

كل Question يدعم Notes اختيارية.

**Status:** Approved.

### AD-006

الملاحظات تدخل مسار Needs Review ولا تتحول مباشرة إلى Requirements نهائية.

**Status:** Approved.

### AD-007

القرارات التقنية تستنتج من الإجابات الوظيفية المعتمدة.

**Status:** Approved.

### AD-008

لا يطلب من المختص اتخاذ قرارات Laravel / Database بصياغة تقنية مباشرة.

**Status:** Approved.

### AD-009

واجهة المختص عربية RTL وتعتمد Bootstrap 5 + Tajawal + Font Awesome 6.

**Status:** Approved.

### AD-010

Sections تستخدم مستويين منطقيين رئيسيين:

```text
Main Section
→ Subsection
```

**Status:** Approved.

### AD-011

Question identity تعتمد على `section_id + seed_key`.

**Status:** Approved.

### AD-012

Option identity تعتمد على `question_id + value`.

**Status:** Approved.

### AD-013

عند بدء الحفاظ على الإجابات، أي تغيير تدميري يجب أن يرفض أو يطلب مراجعة بدل حذف الإجابات بصمت.

**Status:** Approved.

### AD-014

شجرة ملفات Question Seeders يجب أن تعكس شجرة Main Sections/Subsections منطقيًا لمنع الأسماء المضللة.

**Status:** Approved.

### AD-015

Farm / Barn / Battery / Cage تقع تحت Main Section مستقل باسم `هيكل المزرعة`.

**Status:** Approved.

### AD-016

`ملفات إعدادات التشغيل` ليست Master Data Subsection حاليًا، وسيعاد بناؤها لاحقًا داخل Main Section الإعدادات.

**Status:** Approved.

---

## 35. قرارات مؤجلة

تظل التفاصيل التالية مؤجلة إلى القسم أو المرحلة المناسبة:

- التصميم النهائي الكامل لقسم Operation Settings.
- الشكل النهائي لتكوين وإدخال القطيع.
- كل Workflow operations التفصيلية التي لم تتحول إلى أسئلة بعد.
- التقارير والتنبيهات التفصيلية غير المعتمدة بعد.
- أي PDF direct generation إذا لم يعد Browser/Markdown كافيًا.
- أي Answer edit history أوسع من النموذج الحالي إذا أثبتت الحاجة إليه.

---

## 36. قاعدة التنفيذ الحالية

أي عمل جديد يجب أن يخدم المسار التالي مباشرة:

```text
Reference / Domain Decision
→ Explicit Question
→ Stable Seed Definition
→ Answer
→ Review
→ Requirement
→ Blueprint
```

ولا تتم إضافة طبقات عامة أو تعقيد معماري لا يخدم هذا المسار بصورة مباشرة.

---

## 37. Execution Log — من الآن فصاعدًا

أي إضافة جديدة لهذا السجل تكون Append Only بصيغة:

```text
## YYYY-MM-DD — Task / Phase

### Planned

### Implemented

### Files Created

### Files Modified

### Tests

### Findings

### Decisions

### Issues

### Next Step
```

لا يستخدم الـExecution Log لتعريف الوضع الحالي إذا كان هناك قسم Current State أحدث في أعلى الملف.

---

## 2026-08-24 — Plan Repair & Current Workflow Alignment

### Planned

- إصلاح مشكلة ترميز `QUESTIONNAIRE_IMPLEMENTATION_PLAN.md`.
- إزالة الـMojibake وBOM.
- إعادة تعريف دور الملف بوضوح.
- جعل الخطة متوافقة مع طريقة العمل الحالية.
- الحفاظ على خلاصة تاريخ التنفيذ والقرارات المعمارية المهمة.

### Implemented

- إعادة كتابة الملف كنص UTF-8 عربي واضح.
- تعريف `FARM_BLUEPRINT_PROJECT_CONTEXT.md` كمرجع الوضع الحالي الأعلى أولوية.
- تحديث شجرة الأقسام الحالية.
- إزالة `ملفات إعدادات التشغيل` من Master Data في الخطة الحالية.
- توثيق فصل `MasterData` و`FarmStructure` في شجرة Question Seeders.
- توثيق `QuestionnaireQuestionsSeeder` كـorchestrator عام.
- توثيق طريقة التطوير الحالية `php artisan migrate:refresh --seed`.
- توثيق الانتقال المستقبلي إلى `preserveAnswers = true` والحفاظ على `seed_key` وOption Values.
- توثيق Farm Structure واتجاه Cage الحالي.
- الاحتفاظ بتاريخ المراحل السابقة كملخص تاريخي بدل اعتباره Current Architecture.

### Files Modified

- `docs/QUESTIONNAIRE_IMPLEMENTATION_PLAN.md`

### Decisions

- هذا الملف أصبح Technical Implementation Plan + Historical Execution Record.
- Current Project Rules تبقى في `FARM_BLUEPRINT_PROJECT_CONTEXT.md`.
- أي تعارض مع سجل قديم يحسم بأحدث قرار معتمد.

### Next Step

- استكمال تنظيف السياق والـSeeders ثم تشغيل Fresh Seed محليًا والتحقق قبل بدء دورة الإجابات الجديدة.
