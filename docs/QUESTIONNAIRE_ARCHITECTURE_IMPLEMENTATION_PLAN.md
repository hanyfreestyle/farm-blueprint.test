# Questionnaire Architecture Implementation Plan

> خطة التنفيذ الرسمية لإعادة هيكلة أقسام الاستبيان بعد اعتماد `QUESTIONNAIRE_SECTION_ARCHITECTURE_REVIEW.md`.
>
> مصدر القرار المعماري هو `QUESTIONNAIRE_SECTION_ARCHITECTURE_REVIEW.md`، والمرجع الأعلى للحالة الحالية هو `FARM_BLUEPRINT_PROJECT_CONTEXT.md`.
>
> الهدف من هذه الوثيقة هو تتبع ما تم تنفيذه، وترتيب التنفيذ، وحالة كل خطوة بصورة واضحة.

---

## 1. حالات المتابعة

| Status | المعنى |
|---|---|
| `PENDING` | لم تبدأ الخطوة بعد |
| `IN_PROGRESS` | جاري التنفيذ |
| `DONE` | تم التنفيذ ولم تحصل بعد على مراجعة كافية |
| `VERIFIED` | تم التنفيذ ومراجعة الناتج المناسب للخطوة |
| `WAITING_LOCAL` | تغييرات GitHub جاهزة وتنتظر تشغيلًا أو تحققًا محليًا |

---

## 2. الحالة العامة

**الحالة الحالية:** `ARCHITECTURE_IMPLEMENTED_AND_VERIFIED`

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
| P8 — تشغيل migrate:refresh --seed محليًا | `VERIFIED` |
| P9 — مراجعة الشجرة الناتجة | `VERIFIED` |
| P10 — إغلاق إعادة الهيكلة وفتح مرحلة إنشاء الأسئلة | `VERIFIED` |

---

# P0 — Architecture Review

**Status:** `VERIFIED`

الهيكل المعتمد والمنفذ:

```text
1. إدارة البيانات الأساسية
2. هيكل المزرعة
3. بيانات الحيوان وتكوين القطيع
4. الحركات ودورة التشغيل الفعلية
5. التقارير والتحليلات والتنبيهات ومؤشرات الأداء
6. الإعدادات وقواعد التشغيل
```

أعداد الـSubsections:

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

- `docs/FARM_BLUEPRINT_PROJECT_CONTEXT.md`
- `docs/QUESTIONNAIRE_SECTION_ARCHITECTURE_REVIEW.md`

---

# P2 — فحص ما قبل التنفيذ

**Status:** `VERIFIED`

تم التحقق قبل تعديل الكود من:

- قراءة `FARM_BLUEPRINT_PROJECT_CONTEXT.md`.
- قراءة `QUESTIONNAIRE_SECTION_ARCHITECTURE_REVIEW.md`.
- مراجعة `QuestionnaireSectionSeeder.php`.
- مراجعة Section Seeders الستة القديمة.
- عدم وجود Question Seeders للأقسام 3–6.
- بقاء `migrate:refresh --seed` هو Workflow التطوير الحالي المسموح به قبل بدء دورة الإجابات الجديدة.
- عدم الحاجة إلى Migration أو Model Change لإعادة هيكلة `QuestionnaireSection`.

لم يظهر Stop Condition.

---

# P3 — تنفيذ Section Seeders

**Status:** `VERIFIED`

## P3.1 — Master Data

**Status:** `VERIFIED`

تم الحفاظ على:

`database/seeders/Sections/QuestionnaireMasterDataSectionSeeder.php`

بعدد 15 Subsection، بدون إعادة `ملفات إعدادات التشغيل` إلى Master Data.

## P3.2 — Farm Structure

**Status:** `VERIFIED`

تم الحفاظ على:

`database/seeders/Sections/QuestionnaireFarmStructureSectionSeeder.php`

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

```text
بيانات الحيوان وتكوين القطيع
├── بيانات وهوية الحيوان
├── مصدر الحيوان وبداية السجل
├── النسب وشجرة العائلة
├── القطيع الافتتاحي وتهيئة نقطة البداية
└── تكوين القطيع الإنتاجي وتنظيم المجموعات
```

وتم حذف:

`QuestionnaireHerdSetupSectionSeeder.php`

## P3.4 — Workflow

**Status:** `VERIFIED`

تم تحديث:

`database/seeders/Sections/QuestionnaireWorkflowSectionSeeder.php`

ليبني 17 Subsection معتمدة.

## P3.5 — Reports

**Status:** `VERIFIED`

تم تحديث:

`database/seeders/Sections/QuestionnaireReportsSectionSeeder.php`

والاسم النهائي:

`التقارير والتحليلات والتنبيهات ومؤشرات الأداء`

بعدد 15 Subsection.

## P3.6 — Settings

**Status:** `VERIFIED`

تم إنشاء:

`database/seeders/Sections/QuestionnaireSettingsSectionSeeder.php`

والاسم النهائي:

`الإعدادات وقواعد التشغيل`

بعدد 13 Subsection.

وتم حذف:

`QuestionnaireOperationSettingsSectionSeeder.php`

### حدود محفوظة

- Code identity / Unique / Entity Status / Delete Policy لا تنتقل تلقائيًا إلى Settings.
- تنفيذ الأحداث يبقى Workflow.
- تعريف التقرير أو KPI يبقى Reports؛ Targets وThresholds القابلة للضبط فقط تدخل Settings.
- لم يتم افتراض Environmental Control / Veterinary Treatment / Financial modules غير مدعومة.

---

# P4 — تحديث ترتيب وتشغيل Main Sections

**Status:** `VERIFIED`

تم تحديث:

`database/seeders/QuestionnaireSectionSeeder.php`

ليستدعي:

```text
QuestionnaireMasterDataSectionSeeder
QuestionnaireFarmStructureSectionSeeder
QuestionnaireAnimalHerdSectionSeeder
QuestionnaireWorkflowSectionSeeder
QuestionnaireReportsSectionSeeder
QuestionnaireSettingsSectionSeeder
```

وبالترتيب النهائي 1 → 6 المعتمد.

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

هذه Orchestrators لا تنشئ أسئلة بعد. سيضاف لها Question Seeders عند بدء تصميم أسئلة كل قسم.

مجلدات `Questions/AnimalHerd`, `Questions/Workflow`, `Questions/Reports`, `Questions/Settings` ستظهر مع أول Seeder فعلي لأن Git لا يحتفظ بالمجلدات الفارغة.

---

# P6 — تحديث QuestionnaireQuestionsSeeder

**Status:** `VERIFIED`

تم تحديث:

`database/seeders/QuestionnaireQuestionsSeeder.php`

ليستدعي:

```text
QuestionnaireMasterDataQuestionsSeeder
QuestionnaireFarmStructureQuestionsSeeder
QuestionnaireAnimalHerdQuestionsSeeder
QuestionnaireWorkflowQuestionsSeeder
QuestionnaireReportsQuestionsSeeder
QuestionnaireSettingsQuestionsSeeder
```

لم يتم تعديل Question Seeders الحالية الخاصة بـMasterData أو FarmStructure ضمن إعادة الهيكلة.

---

# P7 — مزامنة الوثائق

**Status:** `VERIFIED`

تمت مزامنة:

```text
docs/FARM_BLUEPRINT_PROJECT_CONTEXT.md
docs/QUESTIONNAIRE_SECTION_ARCHITECTURE_REVIEW.md
docs/QUESTIONNAIRE_ARCHITECTURE_IMPLEMENTATION_PLAN.md
```

`docs/QUESTIONNAIRE_IMPLEMENTATION_PLAN.md` يظل سجلًا تقنيًا تاريخيًا مستقلًا.

---

# P8 — التشغيل المحلي

**Status:** `VERIFIED`

تم تنفيذ المسار المحلي:

```bash
git pull origin master
php artisan migrate:refresh --seed
```

وفي 2026-08-24 أكد المستخدم أن التشغيل **تم بنجاح بدون Error**.

---

# P9 — Verification بعد Seed

**Status:** `VERIFIED`

أساس التحقق:

1. Section Seeders والـOrchestrators تمت مراجعتها Static على GitHub قبل التشغيل.
2. قاعدة التطوير أعيد بناؤها Fresh باستخدام `migrate:refresh --seed`.
3. المستخدم أكد نجاح عملية الـSeed محليًا بدون Error.

الشجرة التي يبنيها الكود الآن:

```text
1 إدارة البيانات الأساسية — 15
2 هيكل المزرعة — 4
3 بيانات الحيوان وتكوين القطيع — 5
4 الحركات ودورة التشغيل الفعلية — 17
5 التقارير والتحليلات والتنبيهات ومؤشرات الأداء — 15
6 الإعدادات وقواعد التشغيل — 13

Total Subsections = 69
```

Legacy Main Sections التالية لم تعد Seeders فعالة ولا يتم إنشاؤها في Fresh Seed:

```text
إعدادات التشغيل ودورة الإنتاج
تكوين وإدخال القطيع
التقارير والإشعارات ومؤشرات الأداء
```

كما نجح تشغيل الـOrchestrators الجديدة الفارغة بدون Seeder Error.

> ملاحظة: لم يقدم المستخدم Screenshot منفصلًا للواجهة؛ اعتماد P9 قائم على Fresh Seed الناجح + مراجعة الـSeeders والـsort_order والاستدعاءات في GitHub.

---

# P10 — إغلاق إعادة الهيكلة وفتح مرحلة إنشاء الأسئلة

**Status:** `VERIFIED`

تم إغلاق مرحلة **Questionnaire Section Architecture Restructure**.

الحالة الجديدة للمشروع:

```text
Architecture
→ IMPLEMENTED & VERIFIED

Question creation for sections 3–6
→ READY TO START
```

ترتيب إنشاء الأسئلة المعتمد:

```text
القسم 3 — بيانات الحيوان وتكوين القطيع
↓
القسم 4 — الحركات ودورة التشغيل الفعلية
↓
القسم 5 — التقارير والتحليلات والتنبيهات ومؤشرات الأداء
↓
القسم 6 — الإعدادات وقواعد التشغيل
```

الأقسام 1–2 لا يعاد بناؤها من الصفر؛ تراجع فقط النقاط الناقصة أو المؤجلة عند الحاجة.

السؤال المؤجل عن **الأنواع الفيزيائية للبطاريات** يبقى ضمن مراجعة/استكمال Farm Structure ولا يدخل في إعادة الهيكلة المنتهية.

---

## 3. قواعد المرحلة التالية — إنشاء الأسئلة

1. قبل أي Question Seeder جديد، يقرأ `FARM_BLUEPRINT_PROJECT_CONTEXT.md` أولًا.
2. يراجع الجزء المقابل من `تصور_مشروع_الارانب.md`.
3. يراجع القسم المقابل في `QUESTIONNAIRE_SECTION_ARCHITECTURE_REVIEW.md`.
4. تراجع الأسئلة والقرارات الحالية لمنع التكرار.
5. كل سؤال يجب أن ينتج Decision فعلي.
6. لا تتحول كل نقطة معمارية إلى سؤال مستقل.
7. تستخدم Question Types وDependency Operators الموجودة فقط ما لم تتم مراجعة واعتماد غير ذلك.
8. تستخدم `seed_key` وقيم Options مستقرة.
9. `report_category` و`target_entity` يجب أن يتوافقا مع البنية الحالية.
10. Open Requirements تظل أسئلة ولا تتحول إلى افتراضات.
11. قبل بدء دورة إجابات نريد الحفاظ عليها، يجب الانتقال من Fresh destructive workflow إلى قواعد الحفاظ على الإجابات.

المبدأ عند بدء الحفاظ على الإجابات:

```text
Stable Section Record
+ Stable section_id
+ Stable seed_key
+ Stable option value
+ preserveAnswers = true
```

---

## 4. سجل التنفيذ

أهم Commits أثناء إعادة الهيكلة:

```text
b7631ce75598f8cd7add861f44c4a1ade629845e — إنشاء Animal/Herd Section Seeder
27039f4f373a04291c4859612b8d70f3a77df1a5 — إنشاء Settings Section Seeder
7ad4b1a1320cecde704a6e8c587de9932df44125 — إعادة بناء Workflow Sections
b6e046c697a302f553024856d405e7365596ff89 — إعادة بناء Reports Sections
06adbd9f0c1b888d6bbdf2f39e8817f5dd811b37 — تحديث QuestionnaireSectionSeeder
8355e1efcc561a28e1bcb2c1bbf32d03df8e0b5c — حذف HerdSetup Legacy Seeder
518219efa2ae4143d8aa47d8f36465ada319b575 — حذف OperationSettings Legacy Seeder
b61e2749270f2508565af79b7ac4e84ce86337e9 — Animal/Herd Questions Orchestrator
2395304874800642514766b1d3df22208474ac4b — Workflow Questions Orchestrator
88c1a1a5911df7a818e4b7cca00af03965cee36d — Reports Questions Orchestrator
ddbb5a1062fbc42dfe0270e2015723d23a571170 — Settings Questions Orchestrator
d44724ccf73d68d6457cc4d77234774f69ebff89 — تحديث QuestionnaireQuestionsSeeder
```

---

# 5. Current Next Action

إعادة الهيكلة **مكتملة ومقفلة**.

**Next:** بدء إنشاء أسئلة القسم الثالث:

`بيانات الحيوان وتكوين القطيع`

والبداية المنطقية:

`3.1 بيانات وهوية الحيوان`
