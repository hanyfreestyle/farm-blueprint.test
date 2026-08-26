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
| 4. الحركات ودورة التشغيل الفعلية | Pending Review |
| 5. التقارير والتحليلات والتنبيهات ومؤشرات الأداء | Pending Review |
| 6. الإعدادات وقواعد التشغيل | Pending Review |

> تمت مراجعة الـGuides الحالية للأقسام 1 و2 و3. يتم دمج النقاط المتكررة، ولا تُحوّل حدود التفسير العامة إلى Open Decision إلا إذا كانت تؤثر على Requirement نهائي أو تمنع ربط الأقسام ببعضها بصورة واضحة.

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

**مصدر التفاصيل:**

```text
questionnaire-export/guides/01-master-data/04-breeds.md
```

**القرار النهائي:** لم يحسم بعد.

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

**مصدر التفاصيل:**

```text
questionnaire-export/guides/01-master-data/18-battery-types.md
```

---

## OD-006 — طريقة إدارة قاموس أنواع المهام التشغيلية

**الحالة:** Open  
**النوع:** Requirement Gap  
**الأولوية:** Medium  
**القسم:** 1.19

**الوضع الحالي:** توجد 35 قيمة مبدئية، لكن لا يوجد Question Key يحسم هل `OperationalTaskType` Fixed أم Managed.

**المطلوب حسمه:** Management Mode النهائي للقاموس.

**مصدر التفاصيل:**

```text
questionnaire-export/guides/01-master-data/19-operational-task-types.md
```

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

**المشكلة:** لا يوجد سؤال عام أو مجموعة قرارات تغلق بدقة الحد الأدنى الإلزامي عند إنشاء كل Animal Record، خصوصًا بالنسبة إلى:

```text
external_identifier
photo
distinguishing_marks
sex when unknown is allowed
breed when unknown is allowed
birth_information when unknown is allowed
```

**المطلوب حسمه:** تحديد Minimum Creation Contract للحيوان، مع الحفاظ على السماح بالقيم غير المعروفة حيث تم اعتماده وعدم تحويل الحقول المدعومة تلقائيًا إلى Required Fields.

**مصادر التفاصيل:**

```text
questionnaire-export/guides/03-animal-herd/01-animals.md
questionnaire-export/answers/03-animal-herd/01-animals.md
```

**القرار النهائي:** لم يحسم بعد.

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

**القرار الحالي:**

```text
maximum one external identifier at the same time
supported type = ring_number
```

**غير المحسوم:**

```text
- هل رقم الحلقة إلزامي أصلًا
- نطاق Uniqueness لرقم الحلقة
- هل يمكن استبداله أو تصحيحه بعد التشغيل
- إذا استُبدل: هل تُحفظ الحلقة السابقة تاريخيًا
- هل يمكن إعادة استخدام رقم حلقة قديم لحيوان آخر
```

**المشكلة:** المعرف الخارجي قد يستخدم ميدانيًا للتعرف على الحيوان، لذلك ترك Lifecycle غير محسوم قد ينتج التباسًا أو فقدًا للتتبع رغم ثبات `internal_code`.

**المطلوب حسمه:** سياسة كاملة للـExternal Identifier تشمل Requiredness وUniqueness والتغيير والتاريخ وإعادة الاستخدام.

**مصدر التفاصيل:**

```text
questionnaire-export/guides/03-animal-herd/01-animals.md
```

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

**القرارات الحالية:** يسمح مؤقتًا بعدم معرفة الجنس والسلالة ومعلومات الميلاد.

**غير المحسوم:**

```text
- متى يصبح حسم الجنس إلزاميًا
- متى تصبح السلالة مطلوبة تشغيليًا
- متى يحتاج تاريخ الميلاد إلى استكمال
- ما قواعد تصحيح Sex/Breed/Birth Date بعد وجود أحداث تشغيلية
- هل بعض التصحيحات تحتاج Audit أو مراجعة استثنائية
```

**المطلوب حسمه:** تحديد Boundaries التشغيلية التي تحول البيانات من `Unknown` إلى Required، وسياسة تصحيحها بعد وجود تاريخ، دون إعادة كتابة الماضي بصمت.

**مصادر التفاصيل:**

```text
questionnaire-export/guides/03-animal-herd/01-animals.md
```

**القرار النهائي:** مؤجل لمراجعة التكامل مع Workflow/Settings.

---

## OD-021 — استكمال النسب يدويًا مقابل تصحيح علاقة نسب مشتقة من النظام

**الحالة:** Open  
**النوع:** Requirement Gap / Data Integrity  
**الأولوية:** High  
**القسم:** 3.3 النسب وشجرة العائلة

**Question Key:**

```text
animal.internal_pedigree_derivation
```

**القرار الحالي:**

```text
automatic_when_available_manual_completion
```

أي أن النظام يشتق الأب/الأم/البطن من سجلات التكاثر والولادة عند توفرها، مع السماح باستكمال الناقص يدويًا.

**المشكلة:** لم تُحسم قواعد التفرقة بين:

```text
Manual completion of missing pedigree
```

و:

```text
Correction / override of pedigree already derived from canonical system records
```

ولا توجد قاعدة لاعتماد التصحيح أو أثره على Birth/Litter/Reproductive History وشجرة العائلة.

**المطلوب حسمه:** تحديد متى يسمح بالاستكمال فقط، ومتى يسمح بتصحيح Parentage موثقة، وما Audit/Review المطلوبة وكيف يعاد بناء الآثار دون تعديل التاريخ بصمت.

**مصدر التفاصيل:**

```text
questionnaire-export/guides/03-animal-herd/03-animals.md
```

---

## OD-022 — `Genetic Line` مطلوب كمفهوم لكن نموذجه غير معرف

**الحالة:** Open  
**النوع:** Requirement Gap  
**الأولوية:** High / Before Final Data Model  
**القسم:** 3.3

**Question Key:**

```text
animal.genetic_line_usage = true
```

**المحسوم:** المشروع يحتاج مفهومًا مستقلاً باسم `Genetic Line` بجانب `Breed` و`Pedigree`.

**غير المحسوم بالكامل تقريبًا:**

```text
- هل GeneticLine Master Data أم Entity من نوع آخر
- الحقول
- Lifecycle
- Cardinality مع Animal
- العلاقة مع Breed
- هل يرثه النسل أم يحدد يدويًا
- طريقة الإنشاء والتعطيل
```

**المشكلة:** لا يمكن تحويل `genetic_line_usage = true` إلى Schema نهائي موثوق دون مجموعة قرارات إضافية.

**المطلوب حسمه:** تعريف نموذج `GeneticLine` وحدوده وعلاقاته قبل Final Requirements/Data Model.

**مصدر التفاصيل:**

```text
questionnaire-export/guides/03-animal-herd/03-animals.md
```

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

**القرار الحالي:** Opening Herd يسجل Snapshot للوضع الحالي تشمل الوزن والموقع والحالة الصحية والمرحلة والسياق التناسلي، ثم بعد بدء التشغيل يجب أن تأتي القيم الحالية من المصادر التشغيلية الصحيحة.

**المشكلة:** لم يُحسم النموذج الذي يربط Baseline الافتتاحية بهذه المصادر دون اختراع تاريخ سابق:

```text
Current Weight → هل ينشئ Baseline Weight Record؟
Current Housing → هل ينشئ Initial Occupancy/Housing Baseline؟
Current Health → هل ينشئ Opening Health State أم Health Event؟
Current Reproductive Context → كيف يصبح نقطة بداية Canonical للمسار؟
```

إذا بقيت Snapshot منفصلة فقط فقد يظهر مصدران للحالة الحالية، وإذا تم تحويلها إلى Events تاريخية قد نختلق أحداثًا لم تحدث داخل النظام.

**المطلوب حسمه:** تعريف آلية `Opening Baseline → Canonical Current State` لكل مجال مع الحفاظ على قاعدة `current_snapshot_only` وعدم إنشاء تاريخ مزيف.

**مصادر التفاصيل:**

```text
questionnaire-export/guides/03-animal-herd/04-animals.md
```

**القرار النهائي:** مؤجل لمراجعة Workflow المتخصص لكل مجال.

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

**القرار الحالي:** يمكن بدء أنثى من منتصف دورة حقيقية، مثل `awaiting pregnancy check`, `confirmed pregnant`, `near kindling`, `lactating`, `lactating + remated`، وبعد Activation يتم تقييم المهام من الوضع الحالي.

**المشكلة:** لم يحدد القسم Minimum Temporal Anchors المطلوبة لكل Context. بعض المهام تحتاج تاريخ تلقيح/ولادة أو Anchor زمني حتى تُحسب بصورة صحيحة، بينما السياسة تمنع اختراع تاريخ غير معروف.

**المطلوب حسمه لاحقًا:** لكل Starting Context، تحديد الحد الأدنى من البيانات الزمنية اللازمة لكي يمكن:

```text
- اعتماد الحالة الافتتاحية
- حساب Due Dates
- إنشاء المهام الأولى بصورة صحيحة
```

مع السماح باستمرار البيانات الناقصة عندما لا تمنع التشغيل فعليًا.

**مصدر التفاصيل:**

```text
questionnaire-export/guides/03-animal-herd/04-animals.md
```

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

**القرارات الحالية:**

```text
production_herd.organization_methods
→ individual_management
→ production_groups
→ individual_management موصوف بأنه دون اشتراط مجموعة

production_group.female_membership_model
= required_exactly_one_active_group
→ كل أنثى إنتاجية يجب أن تكون في مجموعة نشطة واحدة بالضبط
```

**المشكلة:** لا يمكن حسم هل أنثى إنتاجية يمكن تشغيلها خارج Production Group أم لا.

**النماذج المحتملة غير المحسومة:**

```text
A) Individual Management وGroups نمطان بديلان على مستوى المزرعة
B) الفردي لبعض الحيوانات فقط والإناث الإنتاجية دائمًا داخل Group
C) كل Animal يدار فرديًا كسجل، لكن Group ما زالت إلزامية تنظيميًا للإناث
D) Female Membership يجب أن تكون اختيارية لكي يعمل Individual Management فعلًا بلا Group
```

**المطلوب حسمه:** نطاق `individual_management` وعلاقته بإلزام عضوية الأنثى الإنتاجية.

**مصدر التفاصيل:**

```text
questionnaire-export/guides/03-animal-herd/05-production-groups.md
```

**القرار النهائي:** لم يحسم بعد.

---

## OD-026 — التاريخ الكامل للمجموعة معتمد لكن Canonical Change Events غير محددة بعد

**الحالة:** Deferred  
**النوع:** Integration Gap  
**الأولوية:** High / Before group-change workflow freeze  
**الأقسام المتأثرة:** 3.5 + Workflow

**Question Keys:**

```text
production_group.history_policy
production_group.primary_male_model
production_group.alternate_male_model
production_group.female_membership_model
production_group.statuses
```

**القرار الحالي:**

```text
production_group.history_policy = full_effective_history
```

ويجب معرفة تاريخ تغير الذكر الأساسي، عضوية الإناث، وحالة المجموعة.

**المشكلة:** 3.5 ينقل التغييرات الفعلية إلى Workflow، لكنه لا يحدد بنفسه Canonical Event Model لتغيرات مثل:

```text
Add/Remove Female
Move Female Between Groups
Change Primary Male
Change Alternate Male
Stop/Dissolve/Reactivate Group when applicable
```

**المطلوب حسمه لاحقًا:** تحديد مصدر الحقيقة التاريخي لكل تغيير، وهل توجد Membership/Assignment/Status Events مستقلة وكيف تحفظ effective_from/effective_to والسبب والمنفذ، مع منع تعديل Current Relations بصمت.

**مصدر التفاصيل:**

```text
questionnaire-export/guides/03-animal-herd/05-production-groups.md
```

**القرار النهائي:** مؤجل لمراجعة Workflow؛ إذا كان Workflow الحالي يغطيها صراحة تُحدث هذه النقطة إلى Resolved أو تُضيق للنقص المتبقي.

---

# Resolved Decisions

## OD-004 — طريقة إدارة قاموس الأنواع الفيزيائية للبطاريات

**الحالة:** Resolved  
**القرار:** `BatteryType` هي Managed Master Data قابلة للإدارة من لوحة التحكم.  
**مصدر الحسم:** `battery.physical_type_management` في قسم 2.3 بيانات البطارية.  
**ملاحظة:** Initial Dataset ما زالت غير محسومة بصورة مستقلة في `OD-005`.
