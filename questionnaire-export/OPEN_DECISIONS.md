# Farm Blueprint — Open Decisions Register

> **الغرض:** سجل مركزي لكل التعارضات، الفجوات، نقاط التكامل، والقرارات المؤجلة التي ظهرت أثناء تفسير الإجابات وكتابة الـGuides.  
> **طبيعة الملف:** ملف تحليلي يدوي، وليس ملفًا مولدًا من قاعدة البيانات.  
> **قاعدة ثابتة:** لا تُحذف النقطة بعد حسمها؛ تتحول حالتها من `Open` إلى `Resolved` مع تسجيل القرار النهائي ومصدره.  
> **مصدر الحقيقة للقرار الأصلي:** ملفات `answers/` ثم ملفات `guides/` للتفسير، وفق أولوية المراجع المعتمدة في `docs/FARM_BLUEPRINT_PROJECT_CONTEXT.md`.

---

## 1. حالات السجل

```text
Open
= لم يُحسم بعد

Resolved
= تم اتخاذ قرار صريح وحفظه

Deferred
= معروف لكنه مؤجل لمرحلة/قسم لاحق

Not Required
= اتضح لاحقًا أنه لا يحتاج قرارًا مستقلًا
```

## 2. أنواع النقاط

```text
Blocking Conflict
= قرارات متعارضة لا يمكن تحويلها إلى Requirement نهائي واحد

Cross-section Conflict
= تعارض بين قسمين أو أكثر

Semantic Overlap
= مفهومان معتمدان قد يؤديان إلى معنى مكرر أو غامض

Requirement Gap
= معلومة لازمة للـRequirements لم تُحسم بعد

Integration Gap
= العلاقة بين كيانين/مسارين غير محسومة

Scope Decision
= نطاق الاستخدام أو التغطية يحتاج قرارًا لاحقًا

Implementation Boundary
= المعنى الوظيفي محسوم لكن تفصيل التنفيذ النهائي غير محسوم
```

---

## 3. تقدم المراجعة

| القسم الرئيسي | الحالة |
|---|---|
| 1. إدارة البيانات الأساسية | Reviewed |
| 2. هيكل المزرعة | Reviewed |
| 3. بيانات الحيوان وتكوين القطيع | Reviewed |
| 4. الحركات ودورة التشغيل الفعلية | Reviewed |
| 5. التقارير والتحليلات والتنبيهات ومؤشرات الأداء | Reviewed |
| 6. الإعدادات وقواعد التشغيل | Pending Review |

> تمت مراجعة الأقسام 1 و2 و3 و4 و5. في القسم الخامس لا توجد Guides حالية تحت `questionnaire-export/guides/05-reports/`، لذلك تمت المراجعة من ملفات الإجابات الحالية الـ16 المصدرة في `answers/05-reports/` مع الرجوع إلى `FARM_BLUEPRINT_PROJECT_CONTEXT.md` والـWorkflow Guides والجزء المقابل من `تصور_مشروع_الارانب.md`. يتم دمج النقاط المتكررة، ولا تُحوّل حدود التفسير العامة إلى Open Decision إلا إذا كانت تؤثر على Requirement نهائي أو تمنع ربط الأقسام ببعضها بصورة واضحة. النقاط التي مكان حسمها الطبيعي Settings تظل Deferred ولا يتم اختراع قيمها من Reports أو Workflow.

---

# القسم الأول — إدارة البيانات الأساسية

## OD-001 — مكان مواصفات النوع الفيزيائي للقفص ودور `CagePhysicalType`

**الحالة:** Open  
**النوع:** Blocking Conflict / Cross-section Conflict  
**الأولوية:** Blocking before Final Requirements  
**الأقسام المتأثرة:** 1.16 أنواع الأقفاص الفيزيائية + 2.4 بيانات القفص / العين

**Question Keys:**

```text
cage_physical_type.fields
cage_physical_type.required_fields
cage_physical_type.attribute_model
cage.physical_profile_strategy
cage.capacity_strategy
```

**القرارات الحالية:**

```text
1.16:
fields → dimensions + physical_features + default_capacity
required_fields → dimensions + default_capacity
attribute_model = name_description_only

2.4:
cage.physical_profile_strategy = per_cage_physical_specs
cage.capacity_strategy = from_physical_profile_with_pre_activation_override
```

**المشكلة:** لا يمكن تثبيت المصدر النهائي للمواصفات والسعة، ولا تحديد هل `CagePhysicalType` مرجع فعلي للقفص أم أن المواصفات تعيش مباشرة على القفص/تكوين البطارية.

**المطلوب حسمه:**

```text
A) CagePhysicalType مرجع فعلي ويحمل/يساهم في المواصفات والسعة
أو
B) المواصفات مباشرة على Cage / Battery Structure ويعاد تقييم دور CagePhysicalType
```

**مصادر التفاصيل:**

```text
questionnaire-export/guides/01-master-data/16-cage-physical-types.md
questionnaire-export/guides/02-farm-structure/04-cages.md
```

**القرار النهائي:** لم يحسم بعد.

---

## OD-002 — `متعدد الأغراض` مقابل ربط السلالة بأكثر من غرض إنتاجي

**الحالة:** Open  
**النوع:** Semantic Overlap  
**الأولوية:** Medium  
**الأقسام المتأثرة:** 1.2 الأغراض الإنتاجية + 1.4 بيانات السلالات

**Question Keys:**

```text
production_purpose.initial_values
breed.multiple_production_purposes
```

**المشكلة:** يمكن تمثيل السلالة متعددة الأغراض بطريقتين متداخلتين:

```text
Breed → [meat, breeding_production]
أو
Breed → [multi_purpose]
```

**المطلوب حسمه:** هل `multi_purpose` غرض مستقل بمعنى وظيفي خاص، أم أن تعدد العلاقات يغني عنه، أم يسمح بالنموذجين مع تعريف الفرق.

**مصادر التفاصيل:**

```text
questionnaire-export/guides/01-master-data/02-production-purposes.md
questionnaire-export/guides/01-master-data/04-breeds.md
```

**القرار النهائي:** لم يحسم بعد.

---

## OD-003 — حالة السلالة `inactive` غير مضمونة رغم سياسة التعطيل

**الحالة:** Open  
**النوع:** Requirement Gap  
**الأولوية:** High  
**القسم:** 1.4 بيانات السلالات

**Question Keys:**

```text
breed.statuses
breed.status_management
breed.retirement_policy
```

**الوضع الحالي:** `breed.statuses` يحتوي `active` فقط، بينما `retirement_policy = disable_only` يفترض وجود معنى واضح لـ`inactive`.

**المطلوب حسمه:** هل `inactive` حالة System-required، أم قيمة مبدئية في القائمة Managed، أم أن التعطيل يمثل بآلية منفصلة عن Status.

---

## OD-004 — طريقة إدارة قاموس الأنواع الفيزيائية للبطاريات

**الحالة:** Resolved  
**النوع:** Requirement Gap تم حسمه عابرًا بين الأقسام  
**الأقسام المتأثرة:** 1.18 + 2.3

**مصدر الحسم:**

```text
battery.physical_type_management = managed
```

**القرار النهائي:** `BatteryType` هي Managed Master Data قابلة للإدارة من لوحة التحكم.

**ملاحظة:** Initial Dataset ما زالت مفتوحة في `OD-005`.

---

## OD-005 — لا توجد Initial Dataset معتمدة للأنواع الفيزيائية للبطاريات

**الحالة:** Open  
**النوع:** Requirement Gap  
**الأولوية:** Medium  
**القسم:** 1.18

**Question Key:** `battery_type.initial_values`

**الوضع الحالي:** لا توجد قيم مبدئية محددة.

**المطلوب حسمه:** اعتماد قيم البداية أو اعتماد أن القاموس يبدأ فارغًا ويُملأ إداريًا.

---

## OD-006 — طريقة إدارة قاموس أنواع المهام التشغيلية

**الحالة:** Open  
**النوع:** Requirement Gap  
**الأولوية:** Medium  
**القسم:** 1.19

**الوضع الحالي:** توجد 35 قيمة مبدئية، لكن لا يوجد Question Key يحسم هل `OperationalTaskType` Fixed أم Managed.

**المطلوب حسمه:** Management Mode النهائي للقاموس.

---

## OD-007 — Mapping أنواع المهام إلى التصنيفات غير محسوم

**الحالة:** Open  
**النوع:** Requirement Gap  
**الأولوية:** Medium  
**القسم:** 1.19

**Question Keys:**

```text
operational_task_type.fields
operational_task_type.categories
operational_task_type.initial_values
```

**الوضع الحالي:** تم اعتماد 9 تصنيفات و35 نوع مهمة، لكن لا يوجد Mapping معتمد بينها، كما لم تُحسم Cardinality التصنيف.

**المطلوب حسمه:** تحديد Category لكل نوع مبدئي وهل النوع يتبع تصنيفًا واحدًا أم أكثر.

---

## OD-008 — طريقة إدارة قاموس أسباب العزل

**الحالة:** Open  
**النوع:** Requirement Gap  
**الأولوية:** Medium  
**القسم:** 1.20

**الوضع الحالي:** `IsolationReason` قاموس مرجعي لكن Fixed vs Managed غير محسوم.

**المطلوب حسمه:** Management Mode النهائي.

---

## OD-009 — لا توجد Initial Dataset معتمدة لأسباب العزل

**الحالة:** Open  
**النوع:** Requirement Gap  
**الأولوية:** High  
**القسم:** 1.20

**Question Key:** `isolation_reason.initial_values`

**المطلوب حسمه:** اعتماد أسباب العزل الصحي المبدئية أو اعتماد أن القاموس يبدأ فارغًا.

---

## OD-010 — لا توجد Initial Dataset معتمدة لتصنيفات المشاكل الصحية

**الحالة:** Open  
**النوع:** Requirement Gap  
**الأولوية:** High  
**القسم:** 1.21

**Question Key:** `health_problem_category.initial_values`

**المطلوب حسمه:** اعتماد قائمة أولية للتصنيفات الصحية دون توسيع المشروع إلى نظام بيطري غير مطلوب.

---

## OD-011 — أسباب العزل لا تغطي حجر/ملاحظة الاستقبال حاليًا

**الحالة:** Deferred  
**النوع:** Scope Decision / Integration Gap  
**الأولوية:** Medium  
**الأقسام المتأثرة:** 1.20 + Workflow 4.1

**Question Key:** `isolation_reason.scopes`

**القرار الحالي:** `health_isolation` فقط.

**المطلوب حسمه لاحقًا:** هل حجر الاستقبال يستخدم نفس القاموس بعد توسيع Scope، أم مصدرًا مرجعيًا آخر، أم لا يحتاج قاموسًا مرجعيًا.

---

## OD-012 — تطبيق فريدة الاسم على الحقول متعددة اللغات

**الحالة:** Deferred  
**النوع:** Implementation Boundary  
**الأولوية:** Low / Before schema freeze

**الأقسام المتأثرة:** قواميس Master Data التي تعتمد `unique_name`.

**القرار الوظيفي:** منع تكرار الاسم.

**غير المحسوم:** قواعد Uniqueness لكل لغة، التطبيع النصي، Case Handling، والتعامل مع اختلاف العربية والإنجليزية.

**المطلوب حسمه:** قاعدة تنفيذ موحدة قبل تثبيت Constraints النهائية.

---

# القسم الثاني — هيكل المزرعة

## OD-013 — إلزام علاقات العنبر عند الإنشاء يتعارض مع القرارات المتخصصة

**الحالة:** Open  
**النوع:** Blocking Conflict  
**الأولوية:** Blocking before Final Requirements  
**القسم:** 2.2 بيانات العنبر

**Question Keys:**

```text
barn.required_fields
barn.settings_profile_requirement
barn.ventilation_relation
barn.cooling_relation
barn.heating_relation
```

**التعارض:**

```text
barn.required_fields
→ settings_profile + ventilation + cooling + heating مطلوبة عند Create

لكن:
settings_profile = required_before_operation
ventilation/cooling/heating = optional_multiple
```

**المطلوب حسمه:** هل تُعدّل `required_fields` لتتوافق مع القرارات المتخصصة، أم تصبح العلاقات المتخصصة Required on Create.

---

## OD-014 — تعدد ملفات إعدادات التشغيل النشطة للعنبر دون قاعدة دمج أو أولوية

**الحالة:** Deferred  
**النوع:** Integration Gap  
**الأولوية:** High / Before Settings model freeze  
**الأقسام المتأثرة:** 2.2 + Settings

**Question Keys:**

```text
barn.multiple_active_settings_profiles
barn.settings_profile_requirement
```

**المشكلة:** يسمح بأكثر من Settings Profile فعال، لكن لا توجد قاعدة Precedence/Scope/Merge/Effective Date تمنع تعارض القيم.

**المطلوب حسمه:** نموذج Resolution واضح في Settings.

---

## OD-015 — آلية تقاعد البطارية غير ممثلة صراحة ضمن الحالات الحالية

**الحالة:** Open  
**النوع:** Requirement Gap  
**الأولوية:** High  
**القسم:** 2.3

**Question Keys:**

```text
battery.statuses
battery.status_management
battery.physical_reconfiguration_after_history
battery.delete_policy
```

**الوضع الحالي:** الحالات `active/stopped/maintenance`، بينما إعادة التكوين بعد التاريخ وDelete Policy تعتمد مفهوم `Retire old Battery`.

**المطلوب حسمه:** هل `retired` Status مستقلة، أم Action/Lifecycle منفصل، أم تمثيل آخر يحافظ على الفرق بين التوقف المؤقت والتقاعد النهائي.

---

## OD-016 — الحالات الإضافية للبطارية Managed لكن دلالتها التشغيلية غير معرفة

**الحالة:** Deferred  
**النوع:** Integration Gap  
**الأولوية:** Medium  
**الأقسام المتأثرة:** 2.3 + Settings/Lifecycle Rules

**Question Keys:**

```text
battery.statuses
battery.status_management
battery.status_change_requires_empty
battery.status_change_requires_inactive_cages
battery.non_operational_child_effect
```

**المشكلة:** إضافة Status جديدة ممكنة إداريًا، لكن Semantics الخاصة بالتسكين والإخلاء وتأثير الأب على الأقفاص والانتقالات غير معرفة.

**المطلوب حسمه:** إما حالات System-defined ذات دلالة ثابتة، أو نموذج Rules يحدد معنى كل Status Managed.

---

## OD-017 — نطاق السماح بتعديل كود المزرعة بعد بدء التاريخ التشغيلي

**الحالة:** Deferred  
**النوع:** Implementation Boundary  
**الأولوية:** Medium  
**القسم:** 2.1

**Question Key:** `farm.code_strategy`

**القرار الحالي:** `automatic_editable`.

**غير المحسوم:** هل التعديل فقط قبل أول عملية، أم لاحقًا مع Audit، أم دائمًا مع الحفاظ على Uniqueness.

**المطلوب حسمه:** Lifecycle واضح لقابلية تعديل Farm Code بعد وجود تاريخ تشغيلي.

---

# القسم الثالث — بيانات الحيوان وتكوين القطيع

## OD-018 — Requiredness حقول هوية الحيوان عند إنشاء السجل غير مكتمل

**الحالة:** Open  
**النوع:** Requirement Gap  
**الأولوية:** High / Before Animal creation contract freeze  
**القسم:** 3.1 بيانات وهوية الحيوان

**Question Keys ذات الصلة:**

```text
animal.identity_fields
animal.temporary_unknown_sex
animal.breed_requirement
animal.birth_information_methods
animal.external_identifier_cardinality
animal.external_identifier_types
```

**القرارات الحالية:** تم حسم الحقول التي يدعمها Animal Record، وتم السماح بأن يكون الجنس والسلالة ومعلومات الميلاد غير محسومة مؤقتًا في حالات محددة.

**المشكلة:** لا يوجد قرار يغلق بدقة Minimum Creation Contract لكل Animal Record، خصوصًا Requiredness المعرف الخارجي والصورة والعلامات المميزة والحقول التي تسمح بـUnknown.

**المطلوب حسمه:** تحديد الحد الأدنى المطلوب للإنشاء مع الحفاظ على السماح بالقيم غير المعروفة حيث تم اعتماده.

---

## OD-019 — Lifecycle رقم الحلقة / المعرف الخارجي غير محسوم

**الحالة:** Open  
**النوع:** Requirement Gap / Identity Integrity  
**الأولوية:** High  
**القسم:** 3.1

**Question Keys:**

```text
animal.external_identifier_cardinality
animal.external_identifier_types
```

**القرار الحالي:** معرف خارجي واحد كحد أقصى في نفس الوقت، والنوع المدعوم حاليًا `ring_number`.

**غير المحسوم:** Requiredness، Uniqueness، الاستبدال/التصحيح، حفظ التاريخ، وإعادة استخدام رقم حلقة قديم.

**المطلوب حسمه:** سياسة Lifecycle كاملة للمعرف الخارجي.

---

## OD-020 — توقيت حسم وتصحيح الجنس والسلالة ومعلومات الميلاد بعد وجود تاريخ

**الحالة:** Deferred  
**النوع:** Integration Gap / Data Correction Boundary  
**الأولوية:** High / Before Workflow validation freeze  
**الأقسام المتأثرة:** 3.1 + Workflow + Settings

**Question Keys:**

```text
animal.temporary_unknown_sex
animal.breed_requirement
animal.birth_information_methods
```

**غير المحسوم:** متى تتحول البيانات Unknown إلى Required، وكيف يتم تصحيح Sex/Breed/Birth Date بعد وجود تاريخ تشغيلي دون إعادة كتابة الماضي بصمت.

**المطلوب حسمه:** Boundaries تشغيلية وسياسة Audit/Correction مناسبة.

---

## OD-021 — استكمال النسب يدويًا مقابل تصحيح علاقة نسب مشتقة من النظام

**الحالة:** Open  
**النوع:** Requirement Gap / Data Integrity  
**الأولوية:** High  
**القسم:** 3.3 النسب وشجرة العائلة

**Question Key:** `animal.internal_pedigree_derivation`

**القرار الحالي:** `automatic_when_available_manual_completion`.

**المشكلة:** لم تحسم الحدود بين استكمال Parentage مفقودة وبين Override لعلاقة مشتقة من Birth/Reproduction Canonical Records.

**المطلوب حسمه:** قواعد التصحيح والـAudit وآثار تعديل النسب على Birth/Litter/Reproductive History.

---

## OD-022 — `Genetic Line` مطلوب كمفهوم لكن نموذجه غير معرف

**الحالة:** Open  
**النوع:** Requirement Gap  
**الأولوية:** High / Before Final Data Model  
**القسم:** 3.3

**Question Key:** `animal.genetic_line_usage = true`

**غير المحسوم:** نوع الكيان، الحقول، Lifecycle، Cardinality مع Animal، العلاقة مع Breed، طريقة التحديد/التوريث، والإدارة.

**المطلوب حسمه:** تعريف نموذج `GeneticLine` وحدوده وعلاقاته.

---

## OD-023 — كيف تتحول Opening Snapshot إلى المصادر التشغيلية Canonical بعد التفعيل

**الحالة:** Deferred  
**النوع:** Integration Gap  
**الأولوية:** High / Before Opening Herd implementation  
**الأقسام المتأثرة:** 3.4 + Workflow 4.2/4.3/4.13 وغيرها

**Question Keys:**

```text
animal.opening_snapshot_operational_fields
animal.opening_activation_model
animal.opening_baseline_snapshot
```

**القرار الحالي:** Opening Herd يسجل Snapshot للوضع الحالي ثم تعتمد الحالة المستقبلية على المصادر التشغيلية Canonical.

**المشكلة:** Workflow 4.2/4.3/4.13 يحدد مصادر الحقيقة بعد التشغيل، لكنه لا يحسم آلية تهيئة أول Current Occupancy/Weight/Health/Reproductive Context من Opening Baseline دون تحويلها إلى أحداث ماضية مختلقة أو إبقاء مصدرين للحقيقة.

**المطلوب حسمه:** نموذج `Opening Baseline → Canonical Current State` لكل مجال.

---

## OD-024 — الحد الأدنى للبيانات الزمنية في السياقات التناسلية الافتتاحية غير محدد

**الحالة:** Deferred  
**النوع:** Requirement Gap / Task Engine Integration  
**الأولوية:** High  
**الأقسام المتأثرة:** 3.4 + Workflow التناسلي + Task Rules

**Question Keys:**

```text
animal.opening_reproductive_contexts
animal.opening_missing_data_policy
animal.opening_task_evaluation_after_activation
```

**المشكلة:** يمكن بدء أنثى من منتصف دورة قائمة، لكن بعض المهام والحسابات تحتاج Temporal Anchor غير محدد لكل Starting Context.

**المطلوب حسمه:** الحد الأدنى من البيانات الزمنية اللازمة لاعتماد كل Context ولتوليد أول Due Dates دون اختراع تاريخ.

---

## OD-025 — الإدارة الفردية تتعارض مع إلزام كل أنثى إنتاجية بمجموعة نشطة

**الحالة:** Open  
**النوع:** Blocking Conflict  
**الأولوية:** Blocking before Production Herd Requirements  
**القسم:** 3.5 تكوين القطيع الإنتاجي وتنظيم المجموعات

**Question Keys:**

```text
production_herd.organization_methods
production_group.female_membership_model
```

**التعارض:** `individual_management` موصوف بأنه دون اشتراط مجموعة، بينما كل أنثى إنتاجية مطلوبة في مجموعة نشطة واحدة بالضبط.

**المطلوب حسمه:** نطاق `individual_management` وعلاقته بإلزام عضوية الأنثى الإنتاجية.

---

## OD-026 — التاريخ الكامل للمجموعة معتمد لكن Canonical Change Events غير محددة بالكامل

**الحالة:** Deferred  
**النوع:** Integration Gap  
**الأولوية:** High / Before group-change workflow freeze  
**الأقسام المتأثرة:** 3.5 + Workflow 4.11 وما بعده

**Question Keys:**

```text
production_group.history_policy
production_group.primary_male_model
production_group.alternate_male_model
production_group.female_membership_model
production_group.statuses
```

**القرار الحالي:** `full_effective_history` مطلوب.

**ما أضافته مراجعة Workflow:** 4.11 يؤكد أن Group Assignment عند اعتماد الإحلال يجب أن ينشئ **Membership Event تاريخي مستقل**، لكنه لا يغطي كل التغييرات العامة للمجموعة.

**المتبقي غير المحسوم:** Canonical Events لإضافة/إزالة/نقل الإناث، تغيير Primary/Alternate Male، وإيقاف/حل/إعادة تفعيل المجموعة عند انطباقه.

**المطلوب حسمه:** مصدر الحقيقة التاريخي الكامل لتغييرات Production Group دون تعديل العلاقات الحالية بصمت.

---

# القسم الرابع — الحركات ودورة التشغيل الفعلية

## OD-027 — رفض النقل الجماعي العام مقابل دعم إخلاء هيكل مشغول جماعيًا

**الحالة:** Open  
**النوع:** Cross-section / Internal Workflow Conflict  
**الأولوية:** High  
**القسم:** 4.2 التسكين والنقل والإخلاء وإدارة الإشغال

**Question Keys:**

```text
housing_movement.batch_transfer_support
housing_movement.occupied_structure_relocation_support
```

**القرارات الحالية:**

```text
batch_transfer_support = false
→ لا Generic Batch Transfer

occupied_structure_relocation_support = true
→ توجد عملية نقل جماعي مرتبطة بإخلاء موقع إيواء مشغول
```

**المشكلة:** لم يحسم هل إخلاء الهيكل المشغول **استثناء متخصص مقصود** من رفض النقل الجماعي العام، أم أن رفض Batch Transfer كان مقصودًا به منع أي عملية جماعية.

**المطلوب حسمه:**

```text
A) لا Batch Tool عام، لكن Specialized Structural Relocation مسموحة
أو
B) لا توجد أي حركة جماعية، ويجب تعديل قرار occupied structure relocation
أو
C) اعتماد Batch Transfer عام بقواعد واضحة
```

**مصدر التفاصيل:** `questionnaire-export/guides/04-workflow/02-housing-movements.md`

---

## OD-028 — مصدر أسباب `initial_housing` و`explicit_vacate` غير محدد

**الحالة:** Open  
**النوع:** Requirement Gap  
**الأولوية:** Medium  
**القسم:** 4.2

**Question Keys:**

```text
housing_movement.reason_requirement_scope
housing_movement.transfer_reason_reference
```

**القرار الحالي:** السبب مطلوب في `initial_housing` و`transfer` و`explicit_vacate`، لكن `TransferReason` حُسم فقط كمرجع لسبب النقل.

**غير المحسوم:** هل Initial Housing وExplicit Vacate يستخدمان `TransferReason` أيضًا أم مصدر/قاموس مختلف أم تمثيلًا آخر.

**المطلوب حسمه:** مصدر السبب Canonical لكل Movement Type دون إعادة استخدام قاموس في Scope لم يعتمد له.

---

## OD-029 — وحدة الوزن ثابتة لكن القيمة الفعلية للوحدة غير محددة

**الحالة:** Open  
**النوع:** Requirement Gap  
**الأولوية:** Medium / Before measurement contract freeze  
**القسم:** 4.3 الوزن والقياسات التشغيلية

**Question Key:** `operational_measurement.weight_unit_policy`

**القرار الحالي:** `single_fixed_unit` على مستوى النظام.

**غير المحسوم:** هل الوحدة `g` أو `kg` أو غير ذلك، وأين يثبت هذا القرار إن كان قابلًا للضبط.

**المطلوب حسمه:** الوحدة الفعلية الواحدة التي سيستخدمها Canonical Weight History أو مصدر ضبطها المعتمد.

---

## OD-030 — وزن المواليد قبل الفطام غير قابل للتمثيل بصورة محسومة

**الحالة:** Open  
**النوع:** Cross-section Conflict / Requirement Gap  
**الأولوية:** High  
**الأقسام المتأثرة:** 4.3 الوزن + 4.7 الرضاعة

**Question Keys:**

```text
operational_measurement.subject_types
operational_measurement.preweaning_weight_model
lactation.followup_event_types
```

**القرارات الحالية:**

```text
4.3 subject_types = individual_animal فقط
preweaning_litter غير مختار
→ سؤال preweaning_weight_model غير مطبق

4.7 يدعم weight_measurement_reference داخل متابعة الرضاعة
```

**المشكلة:** إذا كان مرجع الوزن في الرضاعة يخص البطن أو مواليد لم تتحول بعد إلى Animal Records دائمة، فلا يوجد Canonical Subject Model معتمد لوزنها.

**المطلوب حسمه:** هل وزن ما قبل الفطام غير مدعوم، أم يضاف Litter Subject، أم يستخدم Temporary Identified Offspring، وما النموذج Canonical للقيمة والمتوسط إن وجد.

**مصادر التفاصيل:**

```text
questionnaire-export/guides/04-workflow/03-operational-measurements.md
questionnaire-export/guides/04-workflow/07-lactations.md
```

---

## OD-031 — نقطة بدء Reproductive Cycle عند رفض/عدم اكتمال التلقيح غير محسومة

**الحالة:** Open  
**النوع:** Workflow Boundary Gap  
**الأولوية:** High  
**القسم:** 4.4 التلقيح وإدارة المحاولات

**Question Keys:**

```text
mating.reproductive_cycle_start_model
mating.event_result_categories
```

**القرارات الحالية:**

```text
reproductive_cycle_start_model = auto_on_first_mating
results = mating_performed / female_refused / not_completed_other_reason
```

**المشكلة:** لم يحسم هل `auto_on_first_mating` يعني أول Record لمحاولة التنفيذ، أم أول Event بنتيجة `mating_performed` فعلًا. رفض الأنثى أو عدم اكتمال العملية لا يجب أن يولد حملًا أو توقيت فحص حمل وهميًا.

**المطلوب حسمه:** Boundary دقيقة لإنشاء Reproductive Cycle وبدء Timeline التناسلي في حالات عدم حدوث تلقيح فعلي.

---

## OD-032 — حقلا First/Second Mating الثابتان مقابل عدد عمليات تلقيح قابل للضبط

**الحالة:** Open  
**النوع:** Blocking Conflict  
**الأولوية:** Blocking before reproductive data model  
**الأقسام المتأثرة:** 4.4 + Settings 6.5 + المرجع الوظيفي

**Question Keys:**

```text
mating.attempt_event_cardinality
mating_rules.mating_events_per_attempt
```

**القرار الحالي في 4.4:** `fixed_first_second_fields`، أي عمليتا تلقيح ثابتتان كحد عملي داخل Attempt.

**المشكلة:** المرجع الوظيفي يرفض الحقول الثابتة ويفضل Ordered Mating Events، كما أن Settings تحتوي قرارًا لعدد عمليات التلقيح داخل المحاولة.

**المطلوب حسمه:**

```text
A) حد ثابت = 2 ويصبح Settings متوافقًا معه
أو
B) variable_ordered_mating_events ويحدد Settings العدد الفعلي
```

**مصدر التفاصيل:** `questionnaire-export/guides/04-workflow/04-matings.md`

---

## OD-033 — الأبوة عند استخدام أكثر من ذكر داخل الفترة المؤدية للحمل

**الحالة:** Open  
**النوع:** Blocking Cross-section Conflict / Data Integrity  
**الأولوية:** Critical before Pedigree finalization  
**الأقسام المتأثرة:** 4.4 + 4.5 + 4.6 + 4.8 + 3.3

**Question Key الرئيسي:** `mating.multiple_males_paternity_policy`

**القرار الحالي:** الإجابة لم تعتمد جعل الأبوة Unknown/Uncertain تلقائيًا عند تعدد الذكور.

**التعارض:** المرجع الوظيفي ينص على أن استخدام أكثر من ذكر في الفترة المؤدية لنفس الحمل يجعل الأب غير مؤكد ما لم توجد وسيلة إثبات أخرى. الأقسام 4.5 و4.6 و4.8 تحمل `paternity_reference` وتعتمد على نتيجة هذه السياسة.

**المطلوب حسمه:** هل تعدد الذكور يجعل Paternity غير مؤكدة، وكيف يمثل `paternity_reference` عند عدم اليقين، وما أثر ذلك على Pedigree والنسل.

**مصادر التفاصيل:**

```text
questionnaire-export/guides/04-workflow/04-matings.md
questionnaire-export/guides/04-workflow/05-pregnancy-checks.md
questionnaire-export/guides/04-workflow/06-births.md
questionnaire-export/guides/04-workflow/08-weanings.md
```

---

## OD-034 — بدء مسار النمو بعد الفطام: Explicit أم Automatic

**الحالة:** Open  
**النوع:** Blocking Cross-section Conflict  
**الأولوية:** High  
**الأقسام المتأثرة:** 4.8 + 4.9

**Question Keys:**

```text
weaning.post_completion_transition_model
growth_sorting.program_entry_model
```

**القرارات الحالية:**

```text
4.8 = explicit_growth_start_after_weaning
4.9 = auto_start_after_successful_weaning
```

**المشكلة:** القرار الأول يطلب Transition/Event مستقل بعد الفطام، والثاني يجعل مسار النمو يبدأ تلقائيًا دون Start Action منفصلة.

**المطلوب حسمه:** مصدر Boundary واحد لبدء Growth / Sorting Context.

---

## OD-035 — مصدر `breed_origin` عند إنشاء الحيوان في الفطام غير محسوم

**الحالة:** Open  
**النوع:** Integration Gap  
**الأولوية:** High  
**الأقسام المتأثرة:** 4.8 + 3.3

**Question Keys:**

```text
weaning.inherited_animal_fields
animal.offspring_breed_derivation
```

**القرارات الحالية:** 4.8 ينسخ `breed_origin` إذا كانت معروفة، بينما 3.3 يمنع استنتاج Breed تلقائيًا من سلالتي الأب والأم ويطلب اختيارها من Master Data.

**المشكلة:** الأقسام السابقة للفطام لا تحسم بوضوح أين تنشأ Breed approved للمولود قبل إنشاء Animal Record الدائم.

**المطلوب حسمه:** مصدر Breed المعتمد قبل/أثناء الفطام دون Auto-Inference من الوالدين.

---

## OD-036 — Lifecycle قرار المصير المعلق بين 4.9 و4.10 غير معرف

**الحالة:** Open  
**النوع:** Integration Gap  
**الأولوية:** High  
**الأقسام المتأثرة:** 4.9 + 4.10

**Question Keys:**

```text
growth_sorting.result_to_fate_link_model
fate_decision.*
```

**القرار الحالي:** 4.9 ينشئ `Pending Fate Decision` لبعض النتائج، بينما 4.10 يعرف القرار المعتمد وتاريخه وتسليم المسار التالي.

**غير المحسوم:** State/Lifecycle بين Pending وApproved، وكيف يتم رفض/إلغاء/تعديل القرار المعلق، ومن يملك اعتماده، وهل يوجد Record واحد يتغير أم Event اعتماد منفصل.

**المطلوب حسمه:** Lifecycle صريح دون اختراع `pending/approved/rejected` من الـBlueprint من تلقاء نفسه.

---

## OD-037 — `candidate_reference` في اعتماد الإحلال: اختياري في الصياغة لكنه منطقيًا إلزامي

**الحالة:** Open  
**النوع:** Semantic / Integration Gap  
**الأولوية:** Medium  
**القسم:** 4.11 الإحلال والاعتماد

**Question Keys:**

```text
replacement.candidate_stage_policy
replacement.approval_record_fields
```

**القرارات الحالية:**

```text
all_replacements_use_candidate_stage
```

لكن وصف `candidate_reference` في Approval Record يتعامل معه بصيغة «عند وجوده / when applicable».

**المشكلة:** إذا كانت Candidate Stage إلزامية لكل Replacement، فيجب أن يكون كل Approval مرتبطًا Candidate Record؛ وإلا توجد حالة اعتماد لا يمكن تفسير أصلها رغم مخالفة Stage Policy.

**المطلوب حسمه:** هل `candidate_reference` Required لكل Production Herd Approval الناتج من Replacement أم توجد استثناءات معتمدة لStage Policy.

**ملاحظة عابرة:** Group Assignment في 4.11 لا يحسم تعارض `OD-025` الخاص بالإدارة الفردية.

---

## OD-038 — بداية التسمين: عند Fate Decision أم بعد Pending Transition

**الحالة:** Open  
**النوع:** Blocking Cross-section Conflict  
**الأولوية:** Critical before Fattening state machine  
**الأقسام المتأثرة:** 4.10 + 4.12

**Question Keys:**

```text
fate_decision.downstream_transition_model
fattening.entry_boundary_model
```

**القرارات الحالية:**

```text
4.10 = create_pending_downstream_transition
→ Decision → Pending handoff → downstream execution later

4.12 = start_on_fattening_fate_decision
→ Fattening Period starts at approved Fate Decision timestamp
```

**المطلوب حسمه:** هل Fate Decision نفسها تبدأ Fattening Period، أم تنشئ Pending Transition فقط ويبدأ التسمين عند Execution Boundary لاحقة.

---

## OD-039 — معنى `housing_reference` عند بداية التسمين

**الحالة:** Open  
**النوع:** Integration Gap  
**الأولوية:** High  
**القسم:** 4.12

**Question Keys:**

```text
fattening.entry_boundary_model
fattening.start_record_fields
```

**المشكلة:** Fattening Period قد تبدأ قبل اكتمال حركة التسكين، بينما Start Record يحتوي `housing_reference = مرجع موقع التسكين عند البداية`.

**المطلوب حسمه:** هل المرجع هو Current Housing عند القرار، Planned Fattening Housing، Nullable حتى الحركة، أم Reference ترتبط لاحقًا عند اكتمال Housing Movement. لا يعتمد أي نموذج دون قرار صريح.

---

## OD-040 — Sale Readiness المشتقة تلقائيًا مقابل Lifecycle الـPending Exit Transition

**الحالة:** Open  
**النوع:** Integration Gap / Automation Integrity  
**الأولوية:** High  
**الأقسام المتأثرة:** 4.12 + 4.15

**Question Keys:**

```text
fattening.readiness_evaluation_model
fattening.sale_handoff_model
```

**القرارات الحالية:** Sale Readiness مشتقة تلقائيًا من القواعد والسجلات، وعند الجاهزية/اتجاه البيع ينشأ `Pending Exit Transition`.

**غير المحسوم:**

```text
- هل Transition تنشأ تلقائيًا أول مرة تصبح readiness = ready_for_sale
- منع التكرار / idempotency
- ماذا يحدث إذا عادت readiness إلى not_ready قبل الخروج
- هل Transition تلغى أو تعلق أو تبقى
- الفرق بين readiness مشتقة وقرار بشري plan_sale_at_current_state
```

**المطلوب حسمه:** Lifecycle واضح للـPending Exit Transition دون تحويل الجاهزية المشتقة إلى Exit Event فعلي.

---

## OD-041 — `performed_by` عند بداية التسمين دون Start Event مستقل

**الحالة:** Open  
**النوع:** Implementation / Event Boundary Gap  
**الأولوية:** Medium  
**القسم:** 4.12

**Question Keys:**

```text
fattening.entry_boundary_model
fattening.start_record_fields
```

**المشكلة:** التسمين يبدأ عند Fate Decision نفسها ولا يوجد Start Action مستقل، بينما Start Record يطلب `performed_by`.

**المطلوب حسمه:** هل Actor مشتق من `decided_by` في Fate Decision أم يوجد Event مستقل فعلًا أم تمثيل آخر، مع منع خلق واقعتين متنافستين لنفس البداية.

---

## OD-042 — مصدر الحقيقة لنفوق المواليد أثناء الرضاعة

**الحالة:** Open  
**النوع:** Blocking Cross-section Conflict  
**الأولوية:** Critical / Data Integrity  
**الأقسام المتأثرة:** 4.7 + 4.13 + 4.8 reconciliation

**Question Keys:**

```text
lactation.mortality_recording_model
mortality.subject_scopes
```

**القرارات الحالية:**

```text
4.7 = dedicated_lactation_mortality_event

4.13 = general Mortality Event supports:
individual_animal
preweaning_litter_quantity
identified_preweaning_offspring
```

**المشكلة:** نفس واقعة نفوق مولود بعد الولادة يمكن أن تصبح لها مصدران Canonical، ما قد يضاعف Current Alive Count أو Weaning Reconciliation أو يجزئ التاريخ.

**المطلوب حسمه:** مصدر نفوق واحد بعد الولادة؛ إما 4.13 Canonical مع ربطه بالرضاعة، أو Event متخصص 4.7 مع تحديد دور 4.13، أو نموذج آخر لا يكرر الواقعة.

---

## OD-043 — مرحلة النفوق: Manual أم Derived تلقائيًا

**الحالة:** Open  
**النوع:** Conflict with Functional Source  
**الأولوية:** High  
**القسم:** 4.13

**Question Key:** `mortality.stage_derivation_model`

**القرار الحالي:** `manual_stage_per_mortality_event`.

**التعارض:** المرجع الوظيفي يفضل استنتاج مرحلة النفوق تلقائيًا من Timeline/Current Operational Context لمنع الخطأ وتكرار الحقيقة.

**المطلوب حسمه:** هل المرحلة Snapshot يختارها المستخدم يدويًا، أم قيمة Derived من السياق، أم Derived مع Override/Audit في حالات استثنائية.

---

## OD-044 — `operational_restriction_decision` مقابل القيود المشتقة من Health + Settings

**الحالة:** Open  
**النوع:** Internal Semantic Conflict  
**الأولوية:** High  
**القسم:** 4.13

**Question Keys:**

```text
health.observation_record_fields
health.operational_restriction_model
```

**القرارات الحالية:** Observation Record يتضمن `operational_restriction_decision`، بينما النموذج المتخصص هو `derive_restrictions_from_health_and_settings` دون Case-level restriction مستقل كمصدر حقيقة.

**المطلوب حسمه:** هل الحقل Audit/Snapshot لسياق القرار وقت المراجعة فقط، أم قرار Authoritative مستقل، أم يجب إعادة صياغة دوره؛ مع منع وجود مصدرين للمنع التشغيلي.

---

## OD-045 — النموذج العام لإعادة البناء اليدوي يتعارض مع حالات تطلب Reconstruction صريحًا

**الحالة:** Open  
**النوع:** Blocking Internal Conflict  
**الأولوية:** High  
**القسم:** 4.14 الحالات الاستثنائية وإعادة بناء المسار

**Question Keys:**

```text
workflow_reconstruction.action_plan_model
workflow_reconstruction.maternal_death_with_litter_model
event_correction.correction_model
```

**القرارات الحالية:**

```text
action_plan_model = exception_record_only_manual_rebuild
→ لا Reconstruction Plan عام؛ إعادة البناء يدوية بالكامل

لكن:
maternal death with active litter
→ urgent orphan-litter reconstruction

wrong sensitive event correction
→ preserve original + rebuild resulting effects
```

**المشكلة:** القاعدة العامة تقول لا Reconstruction منظم، بينما حالتان متخصصتان تتطلبان Reconstruction فعليًا.

**المطلوب حسمه:** هل توجد Reconstruction Mechanism عامة تستخدمها الحالات المتخصصة، أم أن كل حالة تملك Orchestration خاصة، أم المقصود Manual Rebuild فقط ويجب تعديل الحالات المتخصصة.

---

## OD-046 — من يكتشف السياقات المتأثرة: المستخدم أم النظام

**الحالة:** Open  
**النوع:** Blocking Cross-section Conflict  
**الأولوية:** High  
**الأقسام المتأثرة:** 4.14 + 4.15 + المرجع الوظيفي

**Question Keys:**

```text
workflow_reconstruction.context_detection_model
farm_exit.active_context_handling_model
```

**القرارات الحالية:**

```text
4.14 = manual_affected_context_selection
→ المستخدم يحدد السياقات المتأثرة

4.15 = system detects active contexts before exit
→ النظام يكتشفها ثم يحل/يعيد بناء ما يلزم
```

والمرجع الوظيفي أيضًا يميل إلى اكتشاف النظام للسياقات مثل Pregnancy/Litter/Tasks.

**المطلوب حسمه:** نموذج موحد للمسؤولية: Detection آلي، يدوي، أو Hybrid (auto-detect + human confirmation) مع تعريف مصدر الحقيقة.

---

## OD-047 — معالجة المهام بعد الاستثناء يدوية رغم وجود Task Engine وتوقعات المرجع

**الحالة:** Deferred  
**النوع:** Integration Gap  
**الأولوية:** Medium / Before exception-task integration freeze  
**الأقسام المتأثرة:** 4.14 + 4.17 + Settings 6.12

**Question Key:** `workflow_reconstruction.task_integration_model`

**القرار الحالي:** `manual_task_review_after_exception`، أي مراجعة المهام المتأثرة واحدة تلو الأخرى.

**النقطة المفتوحة:** المرجع الوظيفي يتوقع في بعض الاستثناءات إلغاء المهام التي أصبحت غير صالحة وإنشاء الإجراء التالي المناسب، بينما 4.17 يملك Lifecycle تاريخي للإلغاء/الإكمال لكنه لا يحسم Orchestration تلقائية من الاستثناء.

**المطلوب حسمه:** هل 4.14 يولد Task Review Actions فقط، أم يملك قواعد Auto-cancel/Regenerate من Settings، أم يبقى كل شيء يدويًا.

---

## OD-048 — أثر `Missing Event` على Active Occupancy والسعة

**الحالة:** Open  
**النوع:** Integration Gap / Presence Integrity  
**الأولوية:** High  
**الأقسام المتأثرة:** 4.14 + 4.2 + 4.15

**القرار الحالي:** Missing ليس Farm Exit ولا Mortality، ويمكن أن يتبعه Found Event على نفس Animal Record.

**المشكلة:** موقع الحيوان الفعلي يصبح غير موثوق، لكن 4.2 يعتمد Active Occupancy كمصدر Current Location والسعة. لم يحسم هل Occupancy:

```text
- تبقى Active وتحجز السعة
- تعلق كUnknown occupancy
- تغلق مع إمكانية الاستعادة
- أو نموذج Presence/Occupancy آخر
```

**المطلوب حسمه:** أثر Missing/Found على Current Location، Available Capacity، Cage Occupancy، وPresence State دون اختراع Exit.

---

## OD-049 — Presence عند إعادة الدخول: الوصول الفعلي أم اعتماد Intake النهائي

**الحالة:** Open  
**النوع:** Blocking Cross-section Conflict  
**الأولوية:** Critical / Presence model  
**الأقسام المتأثرة:** 4.1 + 4.15

**Question Keys:**

```text
animal_intake.reentry_process_model
animal_reentry.presence_transition_model
```

**القرارات الحالية:**

```text
4.1:
Physical arrival ≠ Intake approval ≠ Production readiness
والحيوان قد يكون موجودًا فعليًا داخل المزرعة تحت التقييم/الحجر

4.15:
present_after_intake_finalization
→ Presence لا تعود إلا بعد اعتماد Intake النهائي
```

**المطلوب حسمه:** هل Presence Episode تبدأ عند Physical Arrival وتظل Operationally Restricted حتى Final Approval، أم لا تبدأ إلا بعد Finalization، وكيف يمثل الحيوان الموجود فعليًا أثناء فترة Intake قبل الاعتماد.

---

## OD-050 — خروج الحيوان فعليًا مع بقاء Occupancy مفتوحة حتى Post-exit Action

**الحالة:** Open  
**النوع:** Blocking Integration / State Integrity  
**الأولوية:** Critical  
**الأقسام المتأثرة:** 4.15 + 4.2

**Question Keys:**

```text
farm_exit.post_event_transition_model
housing_movement.single_active_occupancy
```

**القرار الحالي:** Actual Farm Exit يجعل الحيوان خارج المزرعة، لكن `create_pending_post_exit_actions` يؤجل إغلاق Occupancy/Paths/Tasks.

**المشكلة:** قد تظهر فترة:

```text
Presence = outside
Active Occupancy = still in Cage
```

فتصبح Current Location وAvailable Capacity غير متسقتين مع الواقع.

**المطلوب حسمه:** هل إغلاق Active Occupancy أثر Atomic مع Exit Event، أم توجد Transition State تمنع اعتبارها Current، أم يجب أن يسبق Exit إخلاء 4.2، أم نموذج آخر يحافظ على Source of Truth.

---

## OD-051 — `explicit_readiness_confirmation_action` غير ممثلة ضمن Cage Actions وعلاقتها بـReturn to Service غير محسومة

**الحالة:** Open  
**النوع:** Cross-section Integration Gap  
**الأولوية:** High  
**الأقسام المتأثرة:** 4.16 + 2.4

**Question Keys:**

```text
housing_site_sanitation.readiness_after_completion_model
cage.supported_actions
```

**القرارات الحالية:**

```text
4.16 = explicit_readiness_confirmation_action

2.4 Cage Actions include:
activate / stop / return_to_service / start_maintenance /
complete_maintenance / retire / start_sanitation / complete_sanitation

ولا يوجد confirm_readiness
```

**المطلوب حسمه:** هل Readiness Confirmation Record/Action مستقل عن Cage Action History، أم Action جديدة، أم `return_to_service` تقوم بالدور؛ وكذلك ترتيب:

```text
Sanitation Complete
→ Readiness Confirmation
→ Return to Service
```

وهل الخطوتان الأخيرتان مطلوبتان دائمًا أم لكل منهما معنى مختلف.

---

## OD-052 — Child Actions المستقلة مقابل Parent→Descendant Effective Availability

**الحالة:** Deferred  
**النوع:** Integration Gap  
**الأولوية:** High / Before Settings 6.3 freeze  
**الأقسام المتأثرة:** 4.16 + Farm Structure + Settings 6.3

**Question Key:** `housing_site_operation.parent_child_history_model`

**القرار الحالي:** عملية Parent قد تنشئ Action مستقلة لكل Child متأثر.

**النقطة المفتوحة:** Settings 6.3 ستحدد هل عدم إتاحة Barn/Battery ينتقل إلى الأبناء كـDerived Effective Availability. يجب منع وجود مصدرين متنافسين:

```text
Parent unavailable → Child unavailable derived
```

وفي الوقت نفسه:

```text
Parent operation → Child Action changes child operational state
```

**المطلوب حسمه:** الفرق بين Historical Child Action وبين Derived Parent Effect، ومتى يحتاج Child Transition فعليًا ومتى يكفي Effective Availability مشتقة.

---

## OD-053 — Common Fields لسجلات صيانة Barn/Battery غير محددة

**الحالة:** Open  
**النوع:** Requirement Gap  
**الأولوية:** Medium  
**القسم:** 4.16

**Question Keys:**

```text
housing_site_maintenance.target_scopes
housing_site_maintenance.record_model
housing_site_maintenance.lifecycle_model
```

**القرار الحالي:** الصيانة مدعومة لـCage/Battery/Barn بسجلات منفصلة حسب نوع الموقع، وStart/Completion Events مترابطة.

**المشكلة:** لا يوجد Question Key يحسم Common Record Fields لـBarn/Battery مثل reason, performed_by, notes, parent operation reference وغيرها، بينما Cage Actions لديها Audit Fields أكثر تحديدًا.

**المطلوب حسمه:** الحد الأدنى الموحد لمعلومات Audit لكل Maintenance Event على كل Scope دون نسخ Cage Schema تلقائيًا.

---

## OD-054 — `current_status` للمهمة مقابل Schedule State المشتقة وExecution Enum غير المحسوم

**الحالة:** Open  
**النوع:** Semantic / State Model Gap  
**الأولوية:** High  
**القسم:** 4.17 تنفيذ وإدارة المهام

**Question Keys:**

```text
operational_task.record_fields
operational_task.lifecycle_model
operational_task.schedule_state_model
```

**القرارات الحالية:** `Upcoming / Due / Overdue` مشتقة من `current_due_at + execution state + time` وليست Status يغيره المستخدم، بينما Task Record يحتوي `current_status`.

**المطلوب حسمه:** تعريف `current_status` صراحة كـExecution Lifecycle State، واعتماد Enum/Transitions التنفيذية النهائية دون خلطها بالـSchedule State.

---

## OD-055 — العلاقة بين Start Task Execution وفتح الـCanonical Workflow Action

**الحالة:** Open  
**النوع:** Workflow Boundary Gap  
**الأولوية:** Medium  
**القسم:** 4.17

**Question Keys:**

```text
operational_task.in_progress_model
operational_task.execution_routing_model
```

**القرارات الحالية:** يوجد `explicit_in_progress_transition`، والضغط على تنفيذ المهمة يوجه إلى Canonical Workflow Action.

**غير المحسوم:** هل زر Execute نفسه ينشئ Start/In-Progress Event تلقائيًا قبل فتح النموذج، أم يجب تنفيذ Start Action مستقلة، وماذا يحدث إذا فُتح النموذج ثم لم تُسجل العملية.

**المطلوب حسمه:** مسار واحد غير متنافس لبداية التنفيذ وربطه بالعملية Canonical.

---

## OD-056 — عدم وجود اعتماد بعد تنفيذ المهمة يقيد Settings 6.12

**الحالة:** Deferred  
**النوع:** Cross-section Constraint  
**الأولوية:** High / During Settings 6.12 review  
**الأقسام المتأثرة:** 4.17 + Settings 6.12

**Question Key:** `operational_task.post_execution_approval_model`

**القرار الحالي:** `no_post_execution_approval`.

**الأثر:** Settings لا يجوز أن تضيف Approval Step عامة بعد التنفيذ أو تجعل اكتمال المهمة معلقًا على Reviewer ثانٍ دون إعادة فتح قرار 4.17.

**المطلوب عند مراجعة 6.12:** التحقق أن قواعد المهام والتصعيد والصلاحيات لا تعيد إدخال Post-execution Approval ضمنيًا.

---

# القسم الخامس — التقارير والتحليلات والتنبيهات ومؤشرات الأداء

## OD-057 — تقرير الخصوبة يلغي تحليل المحاولات والدورات بينما يعتمد عليها في بقية المؤشرات

**الحالة:** Open  
**النوع:** Blocking Internal Report Conflict  
**الأولوية:** Critical before Fertility Report Requirements  
**القسم:** 5.3 تقارير الخصوبة والتلقيح والحمل

**Question Keys:**

```text
fertility_report.mating_counting_granularity
fertility_report.primary_views
fertility_report.core_metrics
pregnancy_check_report.aggregation_model
fertility_report.success_rate_model
```

**القرارات الحالية:**

```text
fertility_report.mating_counting_granularity
= mating_events_only
→ الاعتماد على Mating Events فقط دون تحليل مستقل للمحاولات أو الدورات
```

لكن نفس القسم يعتمد صراحة مؤشرات وتحليلات تحتاج `Mating Attempt` و`Reproductive Cycle` كوحدات تحليل مستقلة، مثل:

```text
mating_attempts_count
average_attempts_to_pregnancy
females_requiring_multiple_attempts
single final attempt outcome for pregnancy-check aggregation
cycles_started
cycles_ending_in_birth
attempt-level success/failure
```

كما أن Workflow 4.4 يثبت أصلًا:

```text
Mating Event ≠ Mating Attempt ≠ Reproductive Cycle
```

**المشكلة:** لا يمكن تنفيذ هذه المقاييس مع الحفاظ على معنى Q2 الحرفي الذي يلغي التحليل المستقل للمحاولات والدورات.

**المطلوب حسمه:** هل التقرير يدعم المقاييس المنفصلة للأحداث والمحاولات والدورات، أم يعتمد Events فقط ويجب إزالة/إعادة تعريف كل المؤشرات القائمة على Attempts/Cycles.

**مصدر التفاصيل:** `questionnaire-export/answers/05-reports/03-fertility-reports.md`

---

## OD-058 — مراحل النمو الثابتة في 5.5 تتعارض مع المراحل القابلة للضبط في Workflow والمرجع

**الحالة:** Open  
**النوع:** Blocking Cross-section Conflict  
**الأولوية:** Critical before Growth Report / KPI freeze  
**الأقسام المتأثرة:** 5.5 تقارير النمو + 4.9 النمو والفرز + Settings 6.9 + 5.15 KPIs

**Question Keys:**

```text
growth_report.stage_model
growth_report.core_metrics
growth_sorting.stage_reference_model
farm_kpi.growth_metrics
```

**القرارات الحالية:**

```text
5.5:
growth_report.stage_model = fixed_standard_age_stages
→ 45 يومًا / 70 يومًا / 3 أشهر كمراحل ثابتة لكل المزارع
```

وفي نفس 5.5 توجد مؤشرات باسم:

```text
weight_at_configured_stages
gain_between_configured_stages
```

بينما 4.9 يعتمد:

```text
growth_sorting.stage_reference_model = reference_configured_sorting_stage
```

والمرجع الوظيفي ينص صراحة أن أعمار ومراحل النمو نفسها **قابلة للتعديل من الإعدادات** وأن 45/70 يومًا/3 أشهر أمثلة وليست Constants نهائية.

**المشكلة:** لا يمكن أن تكون نفس مراحل التحليل Fixed عالميًا وConfigured حسب التشغيل في الوقت نفسه، كما أن KPI `average_weight_at_sorting_stages` سيتأثر مباشرة بنموذج المرحلة النهائي.

**المطلوب حسمه:** اعتماد مصدر واحد لمراحل النمو التي تستخدمها Workflow والتقارير وKPIs: مراحل Configured، أو مراحل Fixed مع تعديل Workflow/Settings/مصطلحات المؤشرات بما يتوافق معها.

**مصادر التفاصيل:**

```text
questionnaire-export/answers/05-reports/05-growth-reports.md
questionnaire-export/guides/04-workflow/09-growth-sortings.md
docs/تصور_مشروع_الارانب.md
```

---

## OD-059 — غياب بيانات الأداء يعامل كصفر رغم أن المشروع يميز بين Unknown وZero

**الحالة:** Open  
**النوع:** Blocking Cross-section Conflict / Data Semantics  
**الأولوية:** Critical before Productive Performance scoring  
**الأقسام المتأثرة:** 5.7 أداء الحيوانات الإنتاجية + 3.2 مصدر الحيوان + 3.4 القطيع الافتتاحي

**Question Key:**

```text
productive_animal_performance.missing_data_handling
```

**القرار الحالي:**

```text
treat_missing_as_zero
```

**المشكلة:** المشروع يعتمد في الحيوانات الخارجية والقطيع الافتتاحي أن التاريخ السابق قد يكون غير معروف أو غير مكتمل، وأن:

```text
Missing history ≠ Event did not happen
Unknown ≠ Zero
```

لكن 5.7 سيحول غياب بيانات مثل حمل/ولادة/فطام/إجهاض/نمو إلى صفر فعلي يدخل في تقييم الحيوان ومقارنته، وهو ما قد يخفض أداء الحيوان لمجرد أن النظام لم يكن يملك تاريخه السابق.

ويصبح الأثر أخطر لأن 5.7 اختار أيضًا `transparent_weighted_composite_score` كتقييم إجمالي؛ أي أن الأصفار الناتجة عن Missing Data قد تدخل مباشرة في الدرجة المركبة.

**المطلوب حسمه:** التمييز الصريح بين القيمة الصفرية الموثقة وبين البيانات غير المتاحة، وتحديد سياسة حساب/استبعاد المؤشر عند نقص البيانات مع إظهار Sample/Data Availability بصورة تمنع المقارنة المضللة.

**مصادر التفاصيل:**

```text
questionnaire-export/answers/05-reports/07-productive-animal-performances.md
questionnaire-export/guides/03-animal-herd/02-animals.md
questionnaire-export/guides/03-animal-herd/04-animals.md
```

---

## OD-060 — الدرجة المركبة لأداء الحيوان معتمدة لكن أوزانها وتطبيعها غير معرفة

**الحالة:** Deferred  
**النوع:** Requirement Gap / Calculation Model  
**الأولوية:** High / Before performance classification implementation  
**الأقسام المتأثرة:** 5.7 + Settings 6.13 عند الحاجة

**Question Key:**

```text
productive_animal_performance.classification_model
```

**القرار الحالي:**

```text
transparent_weighted_composite_score
→ درجة مركبة من مؤشرات وأوزان واضحة مع إظهار مكوناتها
```

**غير المحسوم:** لا توجد في 5.7 قرارات تحدد:

```text
- Weight لكل مؤشر
- Normalization بين مؤشرات بوحدات ومقاييس مختلفة
- Direction لكل مؤشر: الأعلى أفضل أم الأقل أفضل
- Minimum Data Coverage اللازمة لإصدار الدرجة
- التعامل مع Metric غير متاحة
- هل توجد أوزان مختلفة حسب Sex / Role / Farm
```

و6.13 حاليًا غير مجاب عنه، ويركز على Targets/Ranges/Benchmarks ولا توجد إجابة معتمدة بعد تغلق هذا النموذج.

**المطلوب حسمه:** تعريف نموذج Composite Score كامل وقابل للتدقيق قبل اعتباره Classification تنفيذية، مع ربطه صراحة بسياسة Missing Data بعد حسم `OD-059`.

**مصدر التفاصيل:** `questionnaire-export/answers/05-reports/07-productive-animal-performances.md`

---

## OD-061 — 5.9 يفترض أثر Parent مشتقًا دون Child Events بينما 4.16 ينشئ Child Actions مستقلة

**الحالة:** Open  
**النوع:** Blocking Cross-section Conflict / Availability Source of Truth  
**الأولوية:** High  
**الأقسام المتأثرة:** 5.9 تقارير الإشغال + 4.16 تشغيل المواقع + `OD-052`

**Question Keys:**

```text
housing_report.parent_site_unavailability_effect
housing_site_operation.parent_child_history_model
```

**القرارات الحالية:**

```text
4.16:
parent_child_history_model = independent_child_actions
→ عملية Parent تنشئ Action مستقلة لكل Child متأثر
```

بينما سؤال 5.9 نفسه يقرر إظهار أثر عدم إتاحة Barn/Battery على المواقع التابعة **دون اعتبار كل قفص حدث صيانة مستقل**، ويصف الشرح الأثر بأنه Derived من Parent.

**المشكلة:** يوجد نموذجان مختلفان لمصدر الحقيقة التاريخي لحالة Child:

```text
Parent Event → Derived Child Effective Availability
```

مقابل:

```text
Parent Event → Independent Child Actions
```

ولا يجوز أن يحسب التقرير السعة من نموذج يختلف عن التاريخ Canonical المعتمد في Workflow.

**المطلوب حسمه:** توحيد الفرق بين Parent Historical Event، Child Historical Action، وDerived Effective Availability، ثم جعل 5.9 يقرأ المصدر النهائي نفسه بدل افتراض نموذج مستقل.

**ملاحظة:** هذه النقطة توسع أثر `OD-052` ولا تلغيه؛ `OD-052` سيحسم أيضًا تكامل Settings 6.3.

---

## OD-062 — الصفحة التحليلية تخفي البيانات التاريخية الناقصة دون إظهار وجود فجوة

**الحالة:** Open  
**النوع:** Cross-section Conflict / Information Integrity  
**الأولوية:** High  
**الأقسام المتأثرة:** 5.12 الصفحات التحليلية + 3.2 + 3.4 + 5.14 جودة البيانات

**Question Key:**

```text
analytical_entity_page.incomplete_data_model
```

**القرار الحالي:**

```text
hide_missing_sections
→ إخفاء الأجزاء التي لا تحتوي بيانات دون إظهار وجود فجوة
```

**المشكلة:** هذا قد يجعل المستخدم غير قادر على التمييز بين:

```text
Not Applicable
```

و:

```text
Applicable but Unknown / Missing / history starts later
```

بينما المشروع يحافظ صراحة على `History before system start may be incomplete` للحيوانات الافتتاحية، وعلى أن تاريخ الحيوان الخارجي يبدأ من أول Entry موثق، و5.14 نفسها تعتبر نقص البيانات قضية جودة يجب كشفها لا إخفاءها.

**المطلوب حسمه:** فصل حالة `Not Applicable` التي يمكن إخفاؤها عن حالة `Unknown / Incomplete History` التي تحتاج إشارة واضحة، دون اختراع بيانات أو تقديرات لملء الفراغ.

**مصادر التفاصيل:**

```text
questionnaire-export/answers/05-reports/12-analytical-entity-pages.md
questionnaire-export/guides/03-animal-herd/02-animals.md
questionnaire-export/guides/03-animal-herd/04-animals.md
```

---

## OD-063 — سجل التنبيه مطلوب تاريخيًا لكن 5.13 يحتفظ بالحالة الحالية فقط

**الحالة:** Open  
**النوع:** Blocking Internal Conflict / Audit Integrity  
**الأولوية:** Critical before Alert model freeze  
**القسم:** 5.13 التنبيهات والإنذار المبكر

**Question Keys:**

```text
alert.lifecycle_states
alert.lifecycle_history_model
alert.resolution_model
```

**القرارات الحالية:**

```text
Lifecycle states:
new / viewed / under_review / handled / no_longer_active
```

لكن:

```text
alert.lifecycle_history_model = current_state_only
→ يحتفظ النظام بالحالة الحالية فقط
```

**التعارض الداخلي:** شرح السؤال نفسه ينص أن القسم يحتاج `Alert History` وأن تغيير الحالة لا يجب أن يمحو من شاهد التنبيه أو من راجعه أو متى عولج أو متى زالت الحالة. والمرجع الوظيفي يطلب سجلًا يوضح:

```text
هل تمت مراجعته؟
من راجعه؟
الإجراء المتخذ
تاريخ الإغلاق
```

كما أن `auto_close_only_when_condition_clears` يحتاج التمييز تاريخيًا بين المعالجة وبين زوال الشرط.

**المطلوب حسمه:** هل Alert Lifecycle Append-only Events، أم Current State + Key Lifecycle Timestamps/Audit، أم إسقاط Requirement التاريخ؛ ولا يمكن اعتماد `current_state_only` مع متطلبات Audit الحالية في الوقت نفسه.

**مصادر التفاصيل:**

```text
questionnaire-export/answers/05-reports/13-alerts.md
docs/تصور_مشروع_الارانب.md
```

---

## OD-064 — فحوص جودة البيانات القابلة للضبط تستخدم قيمة ثابتة عالميًا

**الحالة:** Open  
**النوع:** Blocking Cross-section Conflict / Settings Boundary  
**الأولوية:** Critical before Data Quality rules freeze  
**الأقسام المتأثرة:** 5.14 جودة البيانات + Settings + 3.1/4.3 حسب الفحص

**Question Key:**

```text
data_quality_report.rule_driven_checks_model
```

**القرار الحالي:**

```text
use_global_fixed_value
```

لسيناريوهات مثل:

```text
مدة غياب الوزن التي تجعل الوزن Stale
العمر/المرحلة التي يصبح بعدها غياب الجنس مشكلة
```

**التعارض:** نص السؤال نفسه يقرر أن هذه القيم قد تختلف حسب التشغيل ويجب ألا تكون Hardcoded داخل التقرير. كما أن Project Context يضع Targets/Thresholds/Periods القابلة للضبط في Settings، والمرجع الوظيفي يسجل هذه الحدود ضمن «نقاط تحتاج مراجعة» وليس Constants عامة.

**المطلوب حسمه:** هل هذه القيم Configurable Rules من Settings، أم Fixed System Values مقصودة فعليًا؛ وإذا كانت Fixed يجب إعادة توحيد حدود Architecture والشرح والمصدر الذي يثبت قيمتها الفعلية.

**مصادر التفاصيل:**

```text
questionnaire-export/answers/05-reports/14-data-quality-reports.md
docs/FARM_BLUEPRINT_PROJECT_CONTEXT.md
docs/تصور_مشروع_الارانب.md
```

---

## OD-065 — KPIs تنشئ معادلات مستقلة لمؤشرات موجودة بالفعل في التقارير المتخصصة

**الحالة:** Open  
**النوع:** Blocking Analytical Source-of-Truth Conflict  
**الأولوية:** Critical before KPI formula freeze  
**الأقسام المتأثرة:** 5.15 KPIs + 5.2–5.10 التقارير المتخصصة + Settings 6.13

**Question Key:**

```text
farm_kpi.calculation_source_model
```

**القرار الحالي:**

```text
independent_kpi_formula
→ لكل KPI معادلة مستقلة حتى لو كان له مؤشر مشابه في تقرير متخصص
```

**المشكلة:** نفس المفهوم موجود بالفعل في تقارير متخصصة، مثل:

```text
pregnancy_rate
average_weaning_weight
average_daily_growth_rate
mortality_rate
on_time_task_completion_rate
cage_occupancy_rate
projected_capacity_shortage
```

وبالتالي يسمح القرار الحالي بوجود رقمين مختلفين تحت نفس الاسم بحسب ما إذا فتح المستخدم التقرير المتخصص أو شاشة KPI. وهذا يناقض الغرض المذكور في سؤال 5.15 نفسه: منع منطق حساب موازٍ لنفس المؤشر.

**المطلوب حسمه:** هل KPI يعيد استخدام تعريف المؤشر المتخصص كمصدر حساب واحد، أم يسمح بتعريف مستقل فقط إذا لم يوجد مؤشر سابق، أم أن الاستقلال مقصود ويجب عندها إعطاء المؤشر اسمًا/دلالة مختلفة تمنع اعتباره نفس المقياس.

**ملاحظة:** 6.13 غير مجاب عنه حاليًا، ولا يجوز استخدامه لافتراض Resolution قبل حسم هذه النقطة.

**مصدر التفاصيل:** `questionnaire-export/answers/05-reports/15-farm-kpis.md`

---

## OD-066 — درجة الخطورة الصحية مستخدمة في التقارير دون نموذج قيم معتمد

**الحالة:** Deferred  
**النوع:** Requirement Gap / Cross-section Integration  
**الأولوية:** High / Before health analytics and thresholds  
**الأقسام المتأثرة:** 4.13 الصحة + 5.6 تقارير الصحة + Settings 6.11/6.13 عند الحاجة

**Question Keys:**

```text
health.observation_record_fields
health_report.metrics
health_report.analysis_dimensions
```

**القرارات الحالية:** `severity` موجودة على Health Observation، و5.6 يعتمد `cases_by_severity` ويستخدم Severity كبعد تحليلي، كما يستخدمها في سياق العزل.

**غير المحسوم:** 4.13 لا يحدد حتى الآن:

```text
- قيم/مستويات Severity
- هل القائمة Fixed أم Managed
- ترتيبها Ordinal
- هل يمكن مقارنتها عدديًا
- هل Severity للحالة نفسها تختلف عن Alert Severity
```

**المشكلة:** لا يمكن تجميع الحالات «حسب الخطورة» أو بناء Thresholds موثوقة دون Semantic Model موحد للقيمة.

**المطلوب حسمه:** تعريف Severity Model الصحي ومصدره، مع فصله صراحة عن مستويات أولوية/خطورة Alert إذا كانا مفهومين مختلفين.

---

## OD-067 — تقرير القرابة يحتاج نموذج حساب وقواعد لم تُحسم بعد

**الحالة:** Deferred  
**النوع:** Integration Gap / Calculation Requirement  
**الأولوية:** High / Before kinship analytics  
**الأقسام المتأثرة:** 3.3 النسب + 4.4 التلقيح + 5.8 تقارير النسب + Settings 6.5

**Question Keys:**

```text
mating.runtime_kinship_check
pedigree_report.kinship_analysis_model
```

**القرارات الحالية:** 4.4 يطلب Runtime Kinship Check، و5.8 يطلب عرض:

```text
relationship level
+ common ancestry
+ current rule result: allowed / warning / not allowed
```

**غير المحسوم:** لا توجد إجابات معتمدة حتى الآن تحدد طريقة حساب مستوى القرابة، Depth/Scope البيانات المستخدمة، أو Rules التي تحول العلاقة إلى Allowed/Warning/Blocked. هذه التفاصيل مكانها الطبيعي Pedigree/Settings وليست تقريرًا يخترعها.

**المطلوب حسمه:** نموذج Kinship Computation/Classification ومصدر Rules قبل تحويل 5.8 إلى Requirement حسابي نهائي.

---

## OD-068 — Project Context ما زال يسجل 15 Report Subsections بينما التصدير الحالي يحتوي 16

**الحالة:** Deferred  
**النوع:** Documentation / Architecture Consistency Gap  
**الأولوية:** Low / Before final documentation freeze  
**المصادر المتأثرة:** `FARM_BLUEPRINT_PROJECT_CONTEXT.md` + `questionnaire-export/answers/05-reports/`

**الوضع الحالي:** Project Context ما زال يحتوي:

```text
Reports = 15
```

بينما التصدير الحالي يحتوي 16 ملفًا من `5.1` حتى `5.16`، ومنها:

```text
5.16 خصائص التقارير والتصفية والتصدير
```

كما أن بعض قائمة أرقام Reports المختصرة داخل Project Context تعكس ترقيمًا أقدم من الشجرة الحالية.

**المشكلة:** لا يخلق ذلك تعارضًا وظيفيًا في الإجابات نفسها، لكنه قد يجعل أي مراجعة لاحقة تعتمد على العدد/الترقيم القديم وتفوت Subsection موجودة فعليًا.

**المطلوب حسمه لاحقًا:** تحديث Project Context/Architecture Documentation ليعكس شجرة Reports الحالية بعد ثباتها، دون تعديل الملف الآن خارج الطلب الحالي.

---

## نقاط القسم الخامس المرتبطة بقرارات مفتوحة سابقة — بدون إنشاء IDs مكررة

### `OD-022` — Genetic Line

5.8 يعتمد `genetic_line_spread` و`individuals_per_line`، لذلك لا يمكن تنفيذ تحليل انتشار الخطوط الوراثية قبل تعريف نموذج `GeneticLine` نفسه في `OD-022`.

### `OD-033` — الأبوة عند تعدد الذكور

أثر القرار يمتد الآن إلى:

```text
5.3 Male Fertility Metrics
5.7 Male Performance / Offspring Performance
5.8 Pedigree / Genetic Spread
5.12 Male Analytical Page
```

إسناد حمل أو بطن أو أبناء أو نتائج نمو لذكر معين يجب ألا يتجاوز درجة ثقة الأبوة المعتمدة نهائيًا.

### `OD-042` — مصدر نفوق المواليد أثناء الرضاعة

5.4 و5.6 يعتمدان على فصل وتحليل نفوق ما بعد الولادة وعلى Drilldown إلى Canonical Mortality Records. لذلك يجب حسم مصدر النفوق مرة واحدة قبل تثبيت معدلات النفوق والبقاء وعدم السماح بعد مزدوج.

### `OD-043` — مرحلة النفوق

5.6 و5.15 يعتمدان `mortality_by_stage`. إذا بقيت المرحلة مدخلة يدويًا ستعتمد التحليلات على Snapshot بشرية؛ وإذا أصبحت Derived فيجب أن يستخدم التقرير المصدر المشتق نفسه. لا ينشئ التقرير Stage Logic مستقلة.

### `OD-049` — Presence عند إعادة الدخول

5.1 و5.2 يعتمدان أعداد الحيوانات الموجودة حاليًا وميزان حركة القطيع. توقيت بداية Presence بعد Re-entry سيغير الرصيد الحالي وOpening/Closing Balance، لذلك لا تثبت معادلات الأعداد قبل حسم `OD-049`.

### `OD-050` — Exit مقابل Occupancy

5.2 ميزان القطيع و5.9 السعة والإشغال قد يعرضان حالتين متعارضتين إذا أصبح الحيوان خارج المزرعة بينما Occupancy ما زالت Active. يجب أن تقرأ التقارير State Model النهائي نفسه بعد حسم `OD-050`.

### `OD-052` — Parent / Child Availability

5.9 يعتمد السعة التشغيلية المتاحة، لذلك نتيجة `OD-052` تحدد كيف يحسب التقرير أثر Barn/Battery غير المتاح على الأقفاص التابعة دون مضاعفة Child Actions وDerived Parent Effects.

### `OD-054` — Task Execution State مقابل Schedule State

5.1 و5.10 يعتمدان `Today / Overdue / Completed / Late Completed`. يجب أن تظل Schedule State مشتقة ومنفصلة عن Execution Lifecycle، ويجب أن تستخدم التقارير الـExecution Enum النهائي بعد حسم `OD-054`.

---

# Resolved Decisions

## OD-004 — طريقة إدارة قاموس الأنواع الفيزيائية للبطاريات

**الحالة:** Resolved  
**القرار:** `BatteryType` هي Managed Master Data قابلة للإدارة من لوحة التحكم.  
**مصدر الحسم:** `battery.physical_type_management` في قسم 2.3 بيانات البطارية.  
**ملاحظة:** Initial Dataset ما زالت غير محسومة بصورة مستقلة في `OD-005`.
