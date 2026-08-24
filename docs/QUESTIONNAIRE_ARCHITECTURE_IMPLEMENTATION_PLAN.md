# Questionnaire Architecture Implementation Plan

> خطة التنفيذ الرسمية لإعادة هيكلة أقسام الاستبيان بعد اكتمال واعتماد `QUESTIONNAIRE_SECTION_ARCHITECTURE_REVIEW.md`.
>
> هذه الوثيقة **ليست مصدر القرارات الوظيفية**؛ مصدر القرارات المعمارية هو `QUESTIONNAIRE_SECTION_ARCHITECTURE_REVIEW.md`، والمرجع الأعلى للحالة الحالية هو `FARM_BLUEPRINT_PROJECT_CONTEXT.md`.
>
> الهدف من هذه الوثيقة هو الإجابة عن: **ماذا سننفذ؟ بأي ترتيب؟ وما حالة كل خطوة؟**

---

## 1. حالات المتابعة

تستخدم الخطة الحالات التالية:

| Status | المعنى |
|---|---|
| `PENDING` | لم تبدأ الخطوة بعد |
| `IN_PROGRESS` | جاري تنفيذ الخطوة |
| `DONE` | تم تنفيذ الخطوة، لكنها لم تحصل بعد على مراجعة نهائية مستقلة |
| `VERIFIED` | تم التنفيذ ومراجعة النتيجة والتأكد منها |
| `WAITING_LOCAL` | التعديل في GitHub جاهز وينتظر تشغيل/تحقق محلي لا يمكن تنفيذه من GitHub Connector |

### قاعدة تحديث الحالة

- لا تتحول خطوة إلى `DONE` قبل تنفيذها فعليًا في المستودع.
- لا تتحول إلى `VERIFIED` لمجرد نجاح Commit؛ يجب مراجعة الناتج المناسب لها.
- عند بدء أي خطوة، يتم تحديث هذه الوثيقة إلى `IN_PROGRESS` عند الحاجة، ثم `DONE` أو `VERIFIED` بعد التنفيذ.
- لا يتم تخطي خطوة معلقة إذا كانت الخطوة التالية تعتمد عليها.

---

## 2. الحالة العامة للخطة

**الحالة الحالية:** `READY FOR IMPLEMENTATION`

| المرحلة | الحالة |
|---|---|
| P0 — Architecture Review | `VERIFIED` |
| P1 — إنشاء خطة التنفيذ وربطها بالحالة الحالية | `DONE` |
| P2 — فحص ما قبل التنفيذ | `PENDING` |
| P3 — تنفيذ Section Seeders الجديدة | `PENDING` |
| P4 — تحديث ترتيب وتشغيل Main Sections | `PENDING` |
| P5 — تجهيز Question Orchestrators للأقسام 3–6 | `PENDING` |
| P6 — تحديث QuestionnaireQuestionsSeeder | `PENDING` |
| P7 — مزامنة الوثائق بعد التنفيذ | `PENDING` |
| P8 — تشغيل migrate:refresh --seed محليًا | `PENDING` |
| P9 — مراجعة الشجرة الناتجة | `PENDING` |
| P10 — إغلاق إعادة الهيكلة وبدء مرحلة إنشاء الأسئلة | `PENDING` |

---

# P0 — Architecture Review

**Status:** `VERIFIED`

تم اعتماد الهيكل المعماري النهائي في:

`docs/QUESTIONNAIRE_SECTION_ARCHITECTURE_REVIEW.md`

الهيكل المستهدف:

```text
1. إدارة البيانات الأساسية
2. هيكل المزرعة
3. بيانات الحيوان وتكوين القطيع
4. الحركات ودورة التشغيل الفعلية
5. التقارير والتحليلات والتنبيهات ومؤشرات الأداء
6. الإعدادات وقواعد التشغيل
```

### أعداد الـSubsections المستهدفة

| Main Section | العدد |
|---|---:|
| إدارة البيانات الأساسية | 15 |
| هيكل المزرعة | 4 |
| بيانات الحيوان وتكوين القطيع | 5 |
| الحركات ودورة التشغيل الفعلية | 17 |
| التقارير والتحليلات والتنبيهات ومؤشرات الأداء | 15 |
| الإعدادات وقواعد التشغيل | 13 |
| **الإجمالي** | **69** |

---

# P1 — إنشاء خطة التنفيذ وربطها بالحالة الحالية

**Status:** `DONE`

## Baseline تم التحقق منه عند إنشاء الخطة

### Section Seeders الحالية

المجلد:

`database/seeders/Sections/`

يحتوي حاليًا على:

```text
QuestionnaireMasterDataSectionSeeder.php
QuestionnaireFarmStructureSectionSeeder.php
QuestionnaireOperationSettingsSectionSeeder.php
QuestionnaireHerdSetupSectionSeeder.php
QuestionnaireWorkflowSectionSeeder.php
QuestionnaireReportsSectionSeeder.php
```

### الترتيب الحالي قبل التنفيذ

`database/seeders/QuestionnaireSectionSeeder.php` ما زال يشغل:

```text
1. إدارة البيانات الأساسية
2. هيكل المزرعة
3. إعدادات التشغيل ودورة الإنتاج
4. تكوين وإدخال القطيع
5. الحركات ودورة التشغيل الفعلية
6. التقارير والإشعارات ومؤشرات الأداء
```

### Question Seeders الحالية

`database/seeders/Questions/` يحتوي حاليًا فقط على:

```text
Concerns/
MasterData/
FarmStructure/
```

ولا توجد Question Seeder folders للأقسام 3–6 الجديدة حتى الآن.

### Orchestrator الحالي

`database/seeders/QuestionnaireQuestionsSeeder.php` يستدعي حاليًا:

```text
QuestionnaireMasterDataQuestionsSeeder
QuestionnaireFarmStructureQuestionsSeeder
```

### DatabaseSeeder

`database/seeders/DatabaseSeeder.php` يشغّل الترتيب الصحيح على مستوى الطبقات:

```text
QuestionnaireSectionSeeder
↓
QuestionnaireQuestionsSeeder
```

ولا يحتاج هذا الجزء إلى تغيير لمجرد إعادة هيكلة الأقسام ما لم يظهر سبب جديد أثناء التنفيذ.

---

# P2 — فحص ما قبل التنفيذ

**Status:** `PENDING`

الهدف منع تعديل الشجرة اعتمادًا على افتراض قديم.

## Checklist

- [ ] إعادة قراءة `FARM_BLUEPRINT_PROJECT_CONTEXT.md` قبل أول تعديل كود.
- [ ] إعادة قراءة `QUESTIONNAIRE_SECTION_ARCHITECTURE_REVIEW.md`.
- [ ] مراجعة آخر نسخة من `QuestionnaireSectionSeeder.php`.
- [ ] مراجعة Section Seeders الستة الموجودة وقت التنفيذ.
- [ ] التأكد أن Question Seeders للأقسام 3–6 لم تظهر منذ إعداد هذه الخطة.
- [ ] التأكد أن مرحلة التطوير الحالية ما زالت تسمح بـ`php artisan migrate:refresh --seed` وأن الإجابات القديمة غير مطلوب الحفاظ عليها.
- [ ] التأكد أن إعادة هيكلة Sections وحدها لا تحتاج Migration أو Model change جديد.
- [ ] تسجيل أي اختلاف عن Baseline داخل هذه الخطة قبل الاستمرار.

### شرط إيقاف

إذا ظهرت إجابات جديدة يجب الحفاظ عليها أو Question Seeders جديدة للأقسام 3–6 قبل التنفيذ، **لا يستمر التنفيذ بنفس خطة الـrefresh** قبل مراجعة أثرها على Stable Sections / seed keys / answers.

---

# P3 — تنفيذ Section Seeders الجديدة

**Status:** `PENDING`

## P3.1 — Master Data

**Status:** `PENDING`

الملف:

`database/seeders/Sections/QuestionnaireMasterDataSectionSeeder.php`

المطلوب:

- الحفاظ على Main Section الحالي `إدارة البيانات الأساسية`.
- الحفاظ على الـ15 Subsection المعتمدة حاليًا.
- عدم إعادة `ملفات إعدادات التشغيل` إلى Master Data.
- إجراء تعديل فقط إذا كشف P2 اختلافًا عن الحالة الموثقة.

**Expected result:** لا تغيير وظيفي متوقع حاليًا.

---

## P3.2 — Farm Structure

**Status:** `PENDING`

الملف:

`database/seeders/Sections/QuestionnaireFarmStructureSectionSeeder.php`

المطلوب الحفاظ على:

```text
هيكل المزرعة
├── بيانات المزرعة
├── بيانات العنبر
├── بيانات البطارية
└── بيانات القفص / العين
```

**Expected result:** لا تغيير وظيفي متوقع حاليًا.

السؤال المؤجل:

`كيف يجب إدارة الأنواع الفيزيائية للبطاريات؟`

لا يحسم ضمن إعادة هيكلة الـSections؛ يعود له العمل بعد تثبيت الشجرة وقبل/أثناء استكمال أسئلة Farm Structure.

---

## P3.3 — بيانات الحيوان وتكوين القطيع

**Status:** `PENDING`

### Legacy

`database/seeders/Sections/QuestionnaireHerdSetupSectionSeeder.php`

### Target

يفضل أن يصبح اسم الملف/الكلاس مطابقًا للمسؤولية الجديدة، مثل:

`database/seeders/Sections/QuestionnaireAnimalHerdSectionSeeder.php`

الـMain Section المستهدف:

`بيانات الحيوان وتكوين القطيع`

الشجرة:

```text
بيانات الحيوان وتكوين القطيع
├── بيانات وهوية الحيوان
├── مصدر الحيوان وبداية السجل
├── النسب وشجرة العائلة
├── القطيع الافتتاحي وتهيئة نقطة البداية
└── تكوين القطيع الإنتاجي وتنظيم المجموعات
```

### Mapping وظيفي من القديم

- `بيانات ملف الأرنب الأساسية` → `بيانات وهوية الحيوان`.
- `مصادر تكوين القطيع` → `مصدر الحيوان وبداية السجل`.
- `النسب وشجرة العائلة` → يبقى.
- إضافة `القطيع الافتتاحي وتهيئة نقطة البداية` كنطاق معتمد.
- `تنظيم الذكور والإناث داخل القطيع` → يتطور إلى `تكوين القطيع الإنتاجي وتنظيم المجموعات`.
- `الدخول الأول للمزرعة والتقييم الأولي` → لا يبقى هنا؛ نطاقه انتقل إلى Workflow.
- `تسكين القطيع وإدارة الإشغال` → لا يبقى هنا؛ انتقل إلى Workflow.
- `تحويل إنتاج المزرعة إلى القطيع والإحلال الداخلي` → لا يبقى هنا؛ التنفيذ التشغيلي انتقل إلى Workflow.
- `جاهزية القطيع لبدء دورة التشغيل والإنتاج` → لا تبقى ككتلة واحدة؛ القواعد Settings والتنفيذ Workflow والعرض Reports.

---

## P3.4 — Workflow

**Status:** `PENDING`

الملف:

`database/seeders/Sections/QuestionnaireWorkflowSectionSeeder.php`

الـMain Section يبقى بالاسم:

`الحركات ودورة التشغيل الفعلية`

ويتحول من الشجرة القديمة إلى الشجرة المعتمدة ذات 17 نطاقًا:

```text
الحركات ودورة التشغيل الفعلية
├── استقبال الحيوان من الخارج وإعادة الإدخال
├── التسكين والنقل والإخلاء وإدارة الإشغال
├── الوزن والقياسات التشغيلية
├── التلقيح وإدارة المحاولات
├── فحص الحمل ومتابعة الحمل وتجهيز الولادة
├── تسجيل الولادة وإنشاء البطن
├── الرضاعة ومتابعة البطن وتداخل دورات الأم
├── الفطام والتحول إلى التتبع الفردي
├── النمو والفرز وإعادة التقييم
├── تحديد المصير والاستبعاد من المسار
├── الإحلال والاعتماد داخل القطيع الإنتاجي
├── التسمين والجاهزية للبيع
├── الصحة والعزل والتعافي والنفوق
├── الحالات الاستثنائية وإعادة بناء المسار
├── الخروج من المزرعة وإعادة الدخول
├── تشغيل وصيانة وتجهيز مواقع الإيواء
└── تنفيذ وإدارة المهام التشغيلية
```

### قواعد تنفيذ إلزامية

- لا يعاد إدخال Rules الخاصة بالجاهزية هنا؛ Workflow يسجل القرار/الحدث الفعلي.
- لا تدخل تحليلات وتقارير هنا.
- لا تنشأ Main Sections مستقلة للمواقع أو المهام.

---

## P3.5 — Reports

**Status:** `PENDING`

الملف:

`database/seeders/Sections/QuestionnaireReportsSectionSeeder.php`

تغيير اسم Main Section من:

`التقارير والإشعارات ومؤشرات الأداء`

إلى:

`التقارير والتحليلات والتنبيهات ومؤشرات الأداء`

الشجرة المستهدفة ذات 15 نطاقًا:

```text
التقارير والتحليلات والتنبيهات ومؤشرات الأداء
├── لوحة التحكم ومتابعة التشغيل اليومي
├── تقارير القطيع والجاهزية
├── تقارير الخصوبة والتلقيح والحمل
├── تقارير الولادة والرضاعة والفطام
├── تقارير النمو والأوزان والتسمين
├── تقارير الصحة والنفوق والعزل
├── تحليل أداء الحيوانات الإنتاجية
├── تقارير النسب والإحلال
├── تقارير الإشغال والسعة والمواقع
├── الاتجاهات والمقارنات والتحليل عبر الزمن
├── الصفحات والسجلات التحليلية
├── التنبيهات والإنذار المبكر واكتشاف الحالات غير الطبيعية
├── جودة البيانات والاستثناءات الإدارية
├── المؤشرات الرئيسية للمزرعة KPIs
└── خصائص التقارير والتصفية والتصدير
```

### نقاط لا تنشأ كـSubsections مستقلة

- `مستويات التنبيه` → Settings.
- `التقرير → التنبيه → القرار → الإجراء` → مبدأ معماري وليس Subsection.
- صفحات الحيوان والبطن المنفصلة → مدمجة تحت `الصفحات والسجلات التحليلية`.
- المقارنات الزمنية والمكانية → مدمجة في نطاق واحد.

---

## P3.6 — Settings

**Status:** `PENDING`

### Legacy

`database/seeders/Sections/QuestionnaireOperationSettingsSectionSeeder.php`

### Target

يفضل اسم ملف/كلاس يعكس القسم النهائي، مثل:

`database/seeders/Sections/QuestionnaireSettingsSectionSeeder.php`

Main Section:

`الإعدادات وقواعد التشغيل`

الشجرة المعتمدة:

```text
الإعدادات وقواعد التشغيل
├── نموذج الإعدادات ونطاق التطبيق
├── السياسات العامة والتحكم والتجاوز والتدقيق
├── قواعد تشغيل هيكل المزرعة ومواقع الإيواء
├── قواعد التسكين وتنظيم القطيع والجاهزية
├── قواعد التلقيح والخصوبة والجاهزية التناسلية
├── قواعد فحص الحمل والحمل وتجهيز الولادة
├── قواعد الولادة والرضاعة وإعادة التلقيح
├── قواعد الفطام والانتقال للتتبع الفردي
├── قواعد النمو والوزن والفرز والإحلال
├── قواعد التسمين والجاهزية للبيع
├── قواعد الصحة والعزل والنفوق والحالات الاستثنائية
├── قواعد المهام والتنبيهات والمواعيد والأولويات
└── إعدادات التقارير وKPIs والأهداف
```

### قواعد الحدود

- لا تنقل Code identity / Unique / Entity statuses / Delete policies من الأقسام الأصلية إلى Settings.
- 6.1 يحدد نموذج Settings وScope ولا يفترض Profile/Inheritance/Override/Versioning قبل الأسئلة.
- 6.2 يركز على Enforcement / Override / Correction / Audit.
- تنفيذ الأحداث يظل Workflow.
- التقرير أو الـKPI نفسه يظل Reports؛ Targets / Thresholds القابلة للضبط فقط تدخل Settings.
- لا يتم افتراض Environmental Control Setpoints أو Treatment Module أو Financial Module من مجرد وجود بيانات مرتبطة بها.

---

# P4 — تحديث ترتيب وتشغيل Main Sections

**Status:** `PENDING`

الملف:

`database/seeders/QuestionnaireSectionSeeder.php`

## المطلوب

1. تحديث Imports إذا تم تغيير أسماء Classes الخاصة بـHerd/Settings.
2. تعديل ترتيب `$this->call()` إلى:

```text
QuestionnaireMasterDataSectionSeeder
QuestionnaireFarmStructureSectionSeeder
QuestionnaireAnimalHerdSectionSeeder
QuestionnaireWorkflowSectionSeeder
QuestionnaireReportsSectionSeeder
QuestionnaireSettingsSectionSeeder
```

3. تعديل `$mainSectionOrder` إلى:

```text
إدارة البيانات الأساسية => 1
هيكل المزرعة => 2
بيانات الحيوان وتكوين القطيع => 3
الحركات ودورة التشغيل الفعلية => 4
التقارير والتحليلات والتنبيهات ومؤشرات الأداء => 5
الإعدادات وقواعد التشغيل => 6
```

4. التأكد أن أسماء الـMain Sections المستخدمة في الـSeeders و`mainSectionOrder` متطابقة حرفيًا.

---

# P5 — تجهيز Question Orchestrators للأقسام 3–6

**Status:** `PENDING`

## الهدف

تجهيز بنية استدعاء الأسئلة من الآن حتى لا تنشأ Question Seeders لاحقًا بدون Orchestrator واضح.

الملفات المقترحة:

```text
database/seeders/QuestionnaireAnimalHerdQuestionsSeeder.php
database/seeders/QuestionnaireWorkflowQuestionsSeeder.php
database/seeders/QuestionnaireReportsQuestionsSeeder.php
database/seeders/QuestionnaireSettingsQuestionsSeeder.php
```

في هذه المرحلة يمكن أن تكون Orchestrators بدون Question Seeders فعلية، إلى أن يبدأ بناء الأسئلة قسمًا قسمًا.

## Target Question folder tree

```text
database/seeders/Questions/
├── Concerns/
├── MasterData/
├── FarmStructure/
├── AnimalHerd/
├── Workflow/
├── Reports/
└── Settings/
```

### ملاحظة Git

Git لا يحتفظ بالمجلدات الفارغة. لذلك لا تنشأ ملفات Placeholder لمجرد إظهار المجلد؛ يظهر كل مجلد عند إنشاء أول Question Seeder حقيقي له، إلا إذا ظهر سبب عملي مختلف.

---

# P6 — تحديث QuestionnaireQuestionsSeeder

**Status:** `PENDING`

الملف:

`database/seeders/QuestionnaireQuestionsSeeder.php`

الترتيب المستهدف:

```text
QuestionnaireMasterDataQuestionsSeeder
QuestionnaireFarmStructureQuestionsSeeder
QuestionnaireAnimalHerdQuestionsSeeder
QuestionnaireWorkflowQuestionsSeeder
QuestionnaireReportsQuestionsSeeder
QuestionnaireSettingsQuestionsSeeder
```

### شرط

يجب ألا يؤدي إدخال Orchestrators الجديدة إلى تغيير أو حذف أسئلة MasterData / FarmStructure الحالية.

---

# P7 — مزامنة الوثائق بعد تنفيذ الكود

**Status:** `PENDING`

بعد تنفيذ P3–P6 يجب تحديث الوثائق، وليس قبل معرفة النتيجة الفعلية.

## الملفات

### `docs/FARM_BLUEPRINT_PROJECT_CONTEXT.md`

- تحويل وصف الهيكل من `Target / not implemented` إلى الهيكل المنفذ فعليًا بعد التحقق.
- تحديث أسماء ملفات Section Seeders الجديدة.
- تحديث Question Orchestrators الجديدة.
- الحفاظ على Open Requirements الخاصة بالأسئلة كما هي؛ تنفيذ الشجرة لا يعني حسمها.

### `docs/QUESTIONNAIRE_SECTION_ARCHITECTURE_REVIEW.md`

- الحفاظ عليه كسجل قرارات.
- إضافة ملاحظة قصيرة أن التنفيذ يتم تتبعه في:
  `QUESTIONNAIRE_ARCHITECTURE_IMPLEMENTATION_PLAN.md`.
- لا يحول إلى Checklist تنفيذية.

### `docs/QUESTIONNAIRE_IMPLEMENTATION_PLAN.md`

- لا يستبدل بهذه الخطة.
- بعد نجاح إعادة الهيكلة يمكن إضافة Summary تاريخي/مرجع للخطة الجديدة إذا كان ذلك مفيدًا، بدون خلط التاريخ التقني القديم بخطوات التنفيذ الحالية.

---

# P8 — تشغيل محلي بعد Pull

**Status:** `PENDING`

هذه الخطوة تنفذ على بيئة التطوير المحلية بعد انتهاء تغييرات GitHub.

```bash
git pull origin master
php artisan migrate:refresh --seed
```

بسبب طبيعة الأمر التدميرية يجب استخدامه فقط طالما ما زالت مرحلة التطوير الحالية تسمح بالتخلص من الإجابات القديمة كما هو موثق في Project Context.

بعد بدء دورة إجابات نريد الاحتفاظ بها، لا يستخدم هذا الأسلوب كروتين عادي.

---

# P9 — Verification بعد Seed

**Status:** `PENDING`

## 9.1 Main Sections

يجب أن تظهر **6 فقط** وبالترتيب:

```text
1 إدارة البيانات الأساسية
2 هيكل المزرعة
3 بيانات الحيوان وتكوين القطيع
4 الحركات ودورة التشغيل الفعلية
5 التقارير والتحليلات والتنبيهات ومؤشرات الأداء
6 الإعدادات وقواعد التشغيل
```

## 9.2 Legacy Main Sections

يجب ألا تظهر كـMain Sections منفصلة:

```text
إعدادات التشغيل ودورة الإنتاج
تكوين وإدخال القطيع
التقارير والإشعارات ومؤشرات الأداء
```

## 9.3 Subsection counts

يجب التحقق من:

```text
Master Data = 15
Farm Structure = 4
Animal / Herd = 5
Workflow = 17
Reports = 15
Settings = 13
Total = 69
```

## 9.4 Existing Questions

يجب التأكد أن:

- أسئلة MasterData ما زالت تظهر.
- أسئلة FarmStructure ما زالت تظهر.
- لا يوجد Seeder Error بسبب Orchestrators الجديدة الفارغة.
- لا تظهر أسئلة في Section قديم أو Parent غير صحيح.

## 9.5 UI review

مراجعة الـSidebar / Navigation للتأكد من:

- الترتيب مطابق للشجرة.
- لا توجد أسماء Legacy.
- كل Subsection تحت Parent الصحيح.
- لا توجد أقسام مكررة.

### نتيجة P9

لا تنتقل الخطة إلى `VERIFIED` قبل مراجعة هذه النقاط بعد تشغيل Seed محليًا.

---

# P10 — إغلاق إعادة الهيكلة وفتح مرحلة الأسئلة

**Status:** `PENDING`

بعد نجاح P9:

1. تحويل P3–P9 إلى `VERIFIED` حسب نتيجة المراجعة.
2. تحديث الحالة العامة لهذه الخطة إلى `ARCHITECTURE IMPLEMENTED & VERIFIED`.
3. تحديث `FARM_BLUEPRINT_PROJECT_CONTEXT.md` بالحالة النهائية.
4. بدء إنشاء الأسئلة بالترتيب:

```text
القسم 3 — بيانات الحيوان وتكوين القطيع
↓
القسم 4 — الحركات ودورة التشغيل الفعلية
↓
القسم 5 — التقارير والتحليلات والتنبيهات ومؤشرات الأداء
↓
القسم 6 — الإعدادات وقواعد التشغيل
```

5. لا يعاد إنشاء أسئلة الأقسام 1–2 من الصفر؛ تراجع فقط النقاط الناقصة أو المؤجلة عند الحاجة، مع الحفاظ على الأسئلة والإجابات المعتمدة.
6. يعود السؤال المؤجل عن **الأنواع الفيزيائية للبطاريات** في مرحلة مراجعة/استكمال Farm Structure، وليس ضمن تنفيذ شجرة الأقسام نفسها.

---

# 3. قواعد تنفيذ لا يجوز كسرها

1. لا يتم إنشاء أسئلة جديدة أثناء تنفيذ إعادة هيكلة Sections إلا بطلب منفصل واضح.
2. لا يتم اختراع Requirement وظيفي أثناء التنفيذ؛ إذا ظهر نقص يرجع إلى Architecture Review والمرجع الوظيفي أو يسجل كـOpen Requirement.
3. لا يتم تعديل `تصور_مشروع_الارانب.md` ضمن هذه الخطة.
4. لا يتم استخدام Rename أو Delete بطريقة تكسر إجابات محفوظة عندما تبدأ مرحلة الحفاظ على البيانات.
5. في مرحلة التطوير الحالية يعتمد التنفيذ على Fresh Seed بعد `migrate:refresh` فقط ما دام ذلك مسموحًا حسب Project Context.
6. بعد بدء الإجابات المستقرة تطبق قواعد:

```text
Stable Section Record
+ Stable seed_key
+ Stable option value
+ preserveAnswers = true
```

7. لا تعتبر الخطة نفسها إذنًا مفتوحًا لأي تعديل مستقبلي خارج خطواتها؛ تنفيذ كل مرحلة على GitHub يتم عند الطلب الصريح وفق قاعدة المستودع.

---

# 4. Current Next Action

**Next:** `P2 — فحص ما قبل التنفيذ`

بعد نجاحه تنتقل الخطة إلى تنفيذ `P3 — Section Seeders`.
