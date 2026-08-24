# Questionnaire Architecture Implementation Plan

> خطة التنفيذ الرسمية لإعادة هيكلة أقسام الاستبيان بعد اعتماد `QUESTIONNAIRE_SECTION_ARCHITECTURE_REVIEW.md`.
>
> مصدر القرار المعماري هو `QUESTIONNAIRE_SECTION_ARCHITECTURE_REVIEW.md`، والمرجع الأعلى للحالة الحالية هو `FARM_BLUEPRINT_PROJECT_CONTEXT.md`.
>
> الهدف من هذه الوثيقة: **ماذا سننفذ؟ بأي ترتيب؟ وما حالة كل خطوة؟**

---

## 1. حالات المتابعة

| Status | المعنى |
|---|---|
| `PENDING` | لم تبدأ الخطوة بعد |
| `IN_PROGRESS` | جاري التنفيذ |
| `DONE` | تم التنفيذ ولم تحصل بعد على مراجعة كافية |
| `VERIFIED` | تم التنفيذ ومراجعة الناتج المناسب للخطوة |
| `WAITING_LOCAL` | تغييرات GitHub جاهزة وتنتظر تشغيلًا أو تحققًا محليًا |

### قاعدة الحالة

- Commit ناجح وحده لا يعني أن التشغيل المحلي نجح.
- خطوات الكود يمكن اعتبارها `VERIFIED` بعد مراجعة الملفات والاستدعاءات على GitHub.
- `P8` و`P9` لا يصبحان `VERIFIED` قبل التشغيل على بيئة التطوير المحلية ومراجعة النتيجة.

---

## 2. الحالة العامة

**الحالة الحالية:** `WAITING_LOCAL_VERIFICATION`

| المرحلة | الحالة |
|---|---|
| P0 — Architecture Review | `VERIFIED` |
| P1 — إنشاء خطة التنفيذ | `VERIFIED` |
| P2 — فحص ما قبل التنفيذ | `VERIFIED` |
| P3 — تنفيذ Section Seeders | `VERIFIED` |
| P4 — تحديث ترتيب وتشغيل Main Sections | `VERIFIED` |
| P5 — تجهيز Question Orchestrators للأقسام 3–6 | `VERIFIED` |
| P6 — تحديث QuestionnaireQuestionsSeeder | `VERIFIED` |
| P7 — مزامنة الوثائق بعد تنفيذ GitHub | `VERIFIED` |
| P8 — تشغيل migrate:refresh --seed محليًا | `WAITING_LOCAL` |
| P9 — مراجعة الشجرة الناتجة | `WAITING_LOCAL` |
| P10 — إغلاق إعادة الهيكلة وبدء الأسئلة | `PENDING` |

---

# P0 — Architecture Review

**Status:** `VERIFIED`

الهيكل المعتمد:

```text
1. إدارة البيانات الأساسية
2. هيكل المزرعة
3. بيانات الحيوان وتكوين القطيع
4. الحركات ودورة التشغيل الفعلية
5. التقارير والتحليلات والتنبيهات ومؤشرات الأداء
6. الإعدادات وقواعد التشغيل
```

أعداد الـSubsections المستهدفة:

```text
Master Data = 15
Farm Structure = 4
Animal / Herd = 5
Workflow = 17
Reports = 15
Settings = 13
Total = 69
```

---

# P1 — إنشاء خطة التنفيذ

**Status:** `VERIFIED`

تم إنشاء هذه الوثيقة وربطها بـ:

- `FARM_BLUEPRINT_PROJECT_CONTEXT.md`
- `QUESTIONNAIRE_SECTION_ARCHITECTURE_REVIEW.md`

---

# P2 — فحص ما قبل التنفيذ

**Status:** `VERIFIED`

## Checklist

- [x] إعادة قراءة `FARM_BLUEPRINT_PROJECT_CONTEXT.md` قبل تعديل الكود.
- [x] إعادة قراءة `QUESTIONNAIRE_SECTION_ARCHITECTURE_REVIEW.md`.
- [x] مراجعة `QuestionnaireSectionSeeder.php`.
- [x] مراجعة Section Seeders الستة القديمة قبل الاستبدال.
- [x] التأكد أن `Questions/` لا يحتوي Question Seeders للأقسام 3–6.
- [x] التأكد من بقاء `migrate:refresh --seed` هو Workflow التطوير الحالي الموثق.
- [x] مراجعة `QuestionnaireSection` والتأكد أن إعادة الهيكلة تستخدم الحقول الحالية فقط.
- [x] التأكد أن إعادة الهيكلة لا تحتاج Migration أو Model Change جديدًا.

### النتيجة

لم يظهر Stop Condition. الوضع كان مطابقًا للـBaseline الموثق، ولذلك استمر التنفيذ.

**مهم:** هذا الفحص يعتمد على حالة المستودع والـProject Context. قاعدة البيانات المحلية نفسها لا يمكن فحصها عبر GitHub Connector؛ لذلك النجاح الفعلي للـFresh Seed ينتظر P8/P9.

---

# P3 — تنفيذ Section Seeders

**Status:** `VERIFIED`

## P3.1 — Master Data

**Status:** `VERIFIED`

لم يحتج تعديلًا وظيفيًا. ما زال:

`database/seeders/Sections/QuestionnaireMasterDataSectionSeeder.php`

ويبني 15 Subsection معتمدة، بدون إعادة `ملفات إعدادات التشغيل` إلى Master Data.

## P3.2 — Farm Structure

**Status:** `VERIFIED`

لم يحتج تعديلًا وظيفيًا. ما زال:

`database/seeders/Sections/QuestionnaireFarmStructureSectionSeeder.php`

ويبني:

```text
هيكل المزرعة
├── بيانات المزرعة
├── بيانات العنبر
├── بيانات البطارية
└── بيانات القفص / العين
```

السؤال المؤجل عن الأنواع الفيزيائية للبطاريات لم يحسم ضمن هذه المرحلة.

## P3.3 — Animal / Herd

**Status:** `VERIFIED`

تم إنشاء:

`database/seeders/Sections/QuestionnaireAnimalHerdSectionSeeder.php`

ويبني:

```text
بيانات الحيوان وتكوين القطيع
├── بيانات وهوية الحيوان
├── مصدر الحيوان وبداية السجل
├── النسب وشجرة العائلة
├── القطيع الافتتاحي وتهيئة نقطة البداية
└── تكوين القطيع الإنتاجي وتنظيم المجموعات
```

تم حذف Legacy file:

`QuestionnaireHerdSetupSectionSeeder.php`

## P3.4 — Workflow

**Status:** `VERIFIED`

تم تحديث:

`database/seeders/Sections/QuestionnaireWorkflowSectionSeeder.php`

ليبني 17 نطاقًا:

```text
استقبال الحيوان من الخارج وإعادة الإدخال
التسكين والنقل والإخلاء وإدارة الإشغال
الوزن والقياسات التشغيلية
التلقيح وإدارة المحاولات
فحص الحمل ومتابعة الحمل وتجهيز الولادة
تسجيل الولادة وإنشاء البطن
الرضاعة ومتابعة البطن وتداخل دورات الأم
الفطام والتحول إلى التتبع الفردي
النمو والفرز وإعادة التقييم
تحديد المصير والاستبعاد من المسار
الإحلال والاعتماد داخل القطيع الإنتاجي
التسمين والجاهزية للبيع
الصحة والعزل والتعافي والنفوق
الحالات الاستثنائية وإعادة بناء المسار
الخروج من المزرعة وإعادة الدخول
تشغيل وصيانة وتجهيز مواقع الإيواء
تنفيذ وإدارة المهام التشغيلية
```

## P3.5 — Reports

**Status:** `VERIFIED`

تم تحديث:

`database/seeders/Sections/QuestionnaireReportsSectionSeeder.php`

وتغيير Main Section إلى:

`التقارير والتحليلات والتنبيهات ومؤشرات الأداء`

ويبني 15 نطاقًا:

```text
لوحة التحكم ومتابعة التشغيل اليومي
تقارير القطيع والجاهزية
تقارير الخصوبة والتلقيح والحمل
تقارير الولادة والرضاعة والفطام
تقارير النمو والأوزان والتسمين
تقارير الصحة والنفوق والعزل
تحليل أداء الحيوانات الإنتاجية
تقارير النسب والإحلال
تقارير الإشغال والسعة والمواقع
الاتجاهات والمقارنات والتحليل عبر الزمن
الصفحات والسجلات التحليلية
التنبيهات والإنذار المبكر واكتشاف الحالات غير الطبيعية
جودة البيانات والاستثناءات الإدارية
المؤشرات الرئيسية للمزرعة KPIs
خصائص التقارير والتصفية والتصدير
```

لم تعد `مستويات التنبيه` أو `التقرير → التنبيه → القرار → الإجراء` Subsections مستقلة.

## P3.6 — Settings

**Status:** `VERIFIED`

تم إنشاء:

`database/seeders/Sections/QuestionnaireSettingsSectionSeeder.php`

ويبني:

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

تم حذف Legacy file:

`QuestionnaireOperationSettingsSectionSeeder.php`

### حدود محفوظة

- Code identity / Unique / Entity Status / Delete Policy لا تنتقل تلقائيًا إلى Settings.
- تنفيذ الأحداث يبقى Workflow.
- تعريف التقرير وKPI يبقى Reports؛ Targets وThresholds القابلة للضبط فقط تدخل Settings.
- لا يوجد افتراض جديد لـEnvironmental Control / Veterinary Treatment / Financial modules.

---

# P4 — تحديث ترتيب وتشغيل Main Sections

**Status:** `VERIFIED`

تم تحديث:

`database/seeders/QuestionnaireSectionSeeder.php`

الاستدعاء الحالي:

```text
QuestionnaireMasterDataSectionSeeder
QuestionnaireFarmStructureSectionSeeder
QuestionnaireAnimalHerdSectionSeeder
QuestionnaireWorkflowSectionSeeder
QuestionnaireReportsSectionSeeder
QuestionnaireSettingsSectionSeeder
```

والترتيب:

```text
إدارة البيانات الأساسية => 1
هيكل المزرعة => 2
بيانات الحيوان وتكوين القطيع => 3
الحركات ودورة التشغيل الفعلية => 4
التقارير والتحليلات والتنبيهات ومؤشرات الأداء => 5
الإعدادات وقواعد التشغيل => 6
```

---

# P5 — تجهيز Question Orchestrators للأقسام 3–6

**Status:** `VERIFIED`

تم إنشاء:

```text
database/seeders/QuestionnaireAnimalHerdQuestionsSeeder.php
database/seeders/QuestionnaireWorkflowQuestionsSeeder.php
database/seeders/QuestionnaireReportsQuestionsSeeder.php
database/seeders/QuestionnaireSettingsQuestionsSeeder.php
```

هذه Orchestrators فارغة وظيفيًا الآن ولا تنشئ أسئلة جديدة؛ سيتم إضافة Question Seeders داخلها عند بدء تصميم أسئلة كل قسم.

### مجلدات Questions

لم يتم إنشاء Empty Folders أو Placeholder files لأن Git لا يحتفظ بالمجلدات الفارغة. المجلدات التالية ستظهر مع أول Question Seeder حقيقي:

```text
Questions/AnimalHerd/
Questions/Workflow/
Questions/Reports/
Questions/Settings/
```

---

# P6 — تحديث QuestionnaireQuestionsSeeder

**Status:** `VERIFIED`

تم تحديث:

`database/seeders/QuestionnaireQuestionsSeeder.php`

والترتيب الحالي:

```text
QuestionnaireMasterDataQuestionsSeeder
QuestionnaireFarmStructureQuestionsSeeder
QuestionnaireAnimalHerdQuestionsSeeder
QuestionnaireWorkflowQuestionsSeeder
QuestionnaireReportsQuestionsSeeder
QuestionnaireSettingsQuestionsSeeder
```

لم يتم تعديل Question Seeders الحالية الخاصة بـMasterData أوFarmStructure.

---

# P7 — مزامنة الوثائق بعد تنفيذ GitHub

**Status:** `VERIFIED`

تم تحديث الوثائق لتفرق بوضوح بين:

```text
GitHub implementation
→ COMPLETE / STATICALLY REVIEWED

Local database execution
→ WAITING_LOCAL
```

الملفات المرجعية:

```text
docs/FARM_BLUEPRINT_PROJECT_CONTEXT.md
docs/QUESTIONNAIRE_SECTION_ARCHITECTURE_REVIEW.md
docs/QUESTIONNAIRE_ARCHITECTURE_IMPLEMENTATION_PLAN.md
```

`docs/QUESTIONNAIRE_IMPLEMENTATION_PLAN.md` يظل سجلًا تقنيًا تاريخيًا مستقلًا، ولا يتم استبداله بهذه الخطة. يمكن إضافة Summary له بعد نجاح P9 إذا احتجنا.

---

# P8 — تشغيل محلي بعد Pull

**Status:** `WAITING_LOCAL`

نفذ على بيئة التطوير المحلية:

```bash
git pull origin master
php artisan migrate:refresh --seed
```

لا يمكن اعتبار هذه الخطوة منفذة من GitHub Connector.

### إذا ظهر Error

لا ننتقل إلى P9 كـVerified. يتم إرسال الخطأ ومراجعته قبل الاستمرار.

---

# P9 — Verification بعد Seed

**Status:** `WAITING_LOCAL`

## Main Sections المطلوبة

يجب أن تظهر 6 فقط:

```text
1 إدارة البيانات الأساسية
2 هيكل المزرعة
3 بيانات الحيوان وتكوين القطيع
4 الحركات ودورة التشغيل الفعلية
5 التقارير والتحليلات والتنبيهات ومؤشرات الأداء
6 الإعدادات وقواعد التشغيل
```

## Legacy Main Sections التي يجب ألا تظهر

```text
إعدادات التشغيل ودورة الإنتاج
تكوين وإدخال القطيع
التقارير والإشعارات ومؤشرات الأداء
```

## Counts

```text
Master Data = 15
Farm Structure = 4
Animal / Herd = 5
Workflow = 17
Reports = 15
Settings = 13
Total = 69
```

## Existing Questions

يجب التأكد من:

- ظهور أسئلة MasterData الحالية.
- ظهور أسئلة FarmStructure الحالية.
- عدم حدوث Seeder Error بسبب Orchestrators الجديدة الفارغة.
- عدم ظهور سؤال تحت Parent قديم أو خاطئ.

## UI

- الترتيب مطابق.
- لا توجد أسماء Legacy.
- كل Subsection تحت Parent الصحيح.
- لا توجد أقسام مكررة.

---

# P10 — إغلاق إعادة الهيكلة وبدء مرحلة الأسئلة

**Status:** `PENDING`

لا يبدأ قبل نجاح P8/P9.

بعد التحقق المحلي:

1. تحويل P8 وP9 إلى `VERIFIED`.
2. تحديث الحالة العامة إلى `ARCHITECTURE_IMPLEMENTED_AND_VERIFIED`.
3. تحديث `FARM_BLUEPRINT_PROJECT_CONTEXT.md` بالحالة النهائية.
4. بدء تصميم الأسئلة بالترتيب:

```text
القسم 3 — بيانات الحيوان وتكوين القطيع
↓
القسم 4 — الحركات ودورة التشغيل الفعلية
↓
القسم 5 — التقارير والتحليلات والتنبيهات ومؤشرات الأداء
↓
القسم 6 — الإعدادات وقواعد التشغيل
```

5. الأقسام 1–2 لا يعاد بناؤها من الصفر؛ تراجع فقط النقاط الناقصة أو المؤجلة.
6. يعود السؤال المؤجل عن الأنواع الفيزيائية للبطاريات ضمن مراجعة/استكمال Farm Structure.

---

## 3. قواعد تنفيذ لا يجوز كسرها

1. لا يتم إنشاء أسئلة جديدة ضمن إعادة هيكلة Sections نفسها.
2. لا يتم اختراع Requirement وظيفي أثناء التنفيذ.
3. لا يتم تعديل `تصور_مشروع_الارانب.md` ضمن هذه الخطة.
4. Open Requirements تبقى أسئلة مستقبلية ولا تتحول إلى افتراضات.
5. Fresh Seed الحالي مسموح فقط خلال مرحلة التطوير الموثقة الحالية.
6. بعد بدء الاحتفاظ بالإجابات تستخدم القواعد:

```text
Stable Section Record
+ Stable section_id
+ Stable seed_key
+ Stable option value
+ preserveAnswers = true
```

7. أي تغيير مستقبلي خارج هذه الخطة يحتاج طلبًا صريحًا وفق قاعدة GitHub.

---

## 4. سجل التنفيذ الحالي

أهم تغييرات GitHub أثناء التنفيذ:

```text
b7631ce75598f8cd7add861f44c4a1ade629845e — إنشاء Animal/Herd Section Seeder
27039f4f373a04291c4859612b8d70f3a77df1a5 — إنشاء Settings Section Seeder
7ad4b1a1320cecde704a6e8c587de9932df44125 — إعادة بناء Workflow Sections
b6e046c697a302f553024856d405e7365596ff89 — إعادة بناء Reports Sections
06adbd9f0c1b888d6bbdf2f39e8817f5dd811b37 — تحديث QuestionnaireSectionSeeder
8355e1efcc561a28e1bcb2c1bbf32d03df8e0b5c — حذف HerdSetup legacy seeder
518219efa2ae4143d8aa47d8f36465ada319b575 — حذف OperationSettings legacy seeder
b61e2749270f2508565af79b7ac4e84ce86337e9 — Animal/Herd Questions orchestrator
2395304874800642514766b1d3df22208474ac4b — Workflow Questions orchestrator
88c1a1a5911df7a818e4b7cca00af03965cee36d — Reports Questions orchestrator
ddbb5a1062fbc42dfe0270e2015723d23a571170 — Settings Questions orchestrator
d44724ccf73d68d6457cc4d77234774f69ebff89 — تحديث QuestionnaireQuestionsSeeder
```

### ملاحظة التحقق

تمت مراجعة بنية الملفات والاستدعاءات من GitHub، لكن **لم يتم تشغيل PHP أو قاعدة البيانات محليًا بواسطة المساعد**. التحقق Runtime ينتظر P8/P9.

---

# 5. Current Next Action

**Next:** `P8 — تشغيل محلي`

```bash
git pull origin master
php artisan migrate:refresh --seed
```

بعد نجاح الأمر ننتقل مباشرة إلى P9 ومراجعة الشجرة الناتجة.
