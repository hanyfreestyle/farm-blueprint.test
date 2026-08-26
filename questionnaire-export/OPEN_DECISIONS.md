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
| 3. بيانات الحيوان وتكوين القطيع | Pending Review |
| 4. الحركات ودورة التشغيل الفعلية | Pending Review |
| 5. التقارير والتحليلات والتنبيهات ومؤشرات الأداء | Pending Review |
| 6. الإعدادات وقواعد التشغيل | Pending Review |

> تمت مراجعة الـGuides الحالية للقسمين الأول والثاني، مع دمج النقاط المتكررة وعدم تحويل حدود التفسير العامة إلى Open Decision إلا عندما كان هناك قرار فعلي ناقص أو تعارض أو فجوة يمكن أن تؤثر على الـBlueprint النهائي. مراجعة القسم الثاني وسعت `OD-001` وكشفت أن `OD-004` أصبح محسومًا من خلال قرار صريح في 2.3.

---

# القسم الأول — إدارة البيانات الأساسية

## Blocking Conflicts

### OD-001 — مكان مواصفات النوع الفيزيائي للقفص ودور `CagePhysicalType`

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

cage_physical_type.fields
→ dimensions
→ physical_features
→ default_capacity
موجودة ضمن خصائص النوع

cage_physical_type.required_fields
→ dimensions
→ default_capacity
إلزامية داخل النوع

لكن:

cage_physical_type.attribute_model
= name_description_only

→ النوع الفيزيائي يحتفظ بالاسم والوصف فقط
→ المواصفات التفصيلية تكون خارج CagePhysicalType
```

وفي المقابل قرر قسم 2.4:

```text
cage.physical_profile_strategy
= per_cage_physical_specs

→ المواصفات الفيزيائية تسجل مباشرة على القفص
→ لا يعتمد القرار الحالي على اختيار CagePhysicalType كمرجع لكل Cage

cage.capacity_strategy
= from_physical_profile_with_pre_activation_override

→ السعة مرتبطة بالمواصفات الفيزيائية الفعلية للقفص
```

**المشكلة:**

لم يعد التعارض متعلقًا فقط بمكان `dimensions/default_capacity/physical_features` داخل `CagePhysicalType`، بل أصبح أيضًا متعلقًا **بدور الكيان نفسه في نموذج القفص الفعلي**.

لا يمكن حاليًا تثبيت Requirement نهائي يقرر أيًا من التالي:

```text
Cage → CagePhysicalType relationship is required
```

أو:

```text
Cage has direct physical specifications only
and does not use CagePhysicalType as its physical source
```

كما لا يمكن تحديد المصدر النهائي للسعة والمواصفات قبل حسم العلاقة بين القرارات.

**المطلوب حسمه:**

```text
A) CagePhysicalType مرجع فعلي للقفص ويحمل أو يساهم في المواصفات الأساسية
   → يحدد دور dimensions/default_capacity/physical_features داخله
   → ويحدد هل يسمح Override على Cage

أو

B) المواصفات الفيزيائية مباشرة على Cage / Battery Structure
   → يعاد تقييم دور CagePhysicalType نفسه
   → ولا تستخدم مواصفاته كمصدر متنافس
```

**مصادر التفاصيل:**

```text
questionnaire-export/guides/01-master-data/16-cage-physical-types.md
questionnaire-export/answers/01-master-data/16-cage-physical-types.md
questionnaire-export/guides/02-farm-structure/04-cages.md
questionnaire-export/answers/02-farm-structure/04-cages.md
```

**القرار النهائي:** لم يحسم بعد.

---

## Semantic / Lifecycle Conflicts

### OD-002 — `متعدد الأغراض` مقابل ربط السلالة بأكثر من غرض إنتاجي

**الحالة:** Open  
**النوع:** Semantic Overlap  
**الأولوية:** Medium  
**الأقسام المتأثرة:** 1.2 الأغراض الإنتاجية + 1.4 بيانات السلالات

**Question Keys:**

```text
production_purpose.initial_values
breed.multiple_production_purposes
```

**القرارات الحالية:**

```text
ProductionPurpose Initial Dataset
→ يوجد تعريف مستقل: multi_purpose / متعدد الأغراض

وفي نفس الوقت:

Breed
→ يسمح بربط السلالة بأكثر من ProductionPurpose Record
```

**المشكلة:**

يوجد احتمال لتمثيل نفس المعنى بطريقتين:

```text
Breed → [meat, breeding_production]
```

مقابل:

```text
Breed → [multi_purpose]
```

وقد يؤدي ذلك إلى غموض في البحث والتقارير والقواعد التي تعتمد على الغرض الإنتاجي.

**المطلوب حسمه:**

هل `multi_purpose` غرض مستقل له معنى وظيفي خاص، أم أن تعدد علاقات السلالة مع الأغراض يغني عنه، أم يسمح بالنموذجين مع تعريف واضح للفرق بينهما.

**مصادر التفاصيل:**

```text
questionnaire-export/guides/01-master-data/02-production-purposes.md
questionnaire-export/guides/01-master-data/04-breeds.md
```

**القرار النهائي:** لم يحسم بعد.

---

### OD-003 — حالة السلالة `inactive` غير مضمونة رغم سياسة التعطيل

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

**القرارات الحالية:**

```text
breed.statuses
→ active فقط ضمن القيم الحالية

breed.status_management
→ managed

breed.retirement_policy
→ disable_only
→ السلالة تتحول إلى غير نشطة عند إخراجها من الاستخدام
```

**المشكلة:**

سياسة التقاعد تحتاج حالة `inactive` أو معنى مكافئًا مضمون الوجود، بينما القائمة الأساسية الحالية لا تضمن إلا `active`. كون القائمة Managed لا يحسم هل `inactive` قيمة System-required أم قيمة يجب أن ينشئها المستخدم يدويًا.

**المطلوب حسمه:**

تحديد ما إذا كانت `inactive`:

```text
- حالة أساسية ثابتة يضمنها النظام
- قيمة مبدئية تضاف للقائمة Managed
- أو أن التعطيل يمثل بآلية أخرى غير status value
```

**مصدر التفاصيل:**

```text
questionnaire-export/guides/01-master-data/04-breeds.md
```

**القرار النهائي:** لم يحسم بعد.

---

## Requirement Gaps

### OD-004 — طريقة إدارة قاموس الأنواع الفيزيائية للبطاريات

**الحالة:** Resolved  
**النوع:** Requirement Gap تم حسمه بقرار عابر بين الأقسام  
**الأولوية:** Resolved  
**الأقسام المتأثرة:** 1.18 الأنواع الفيزيائية للبطاريات + 2.3 بيانات البطارية

**Question Keys:**

```text
1.18 → لم يكن يوجد Question Key مستقل يحسم Fixed vs Managed
2.3 → battery.physical_type_management
```

**الوضع الذي فتح النقطة:**

في 1.18 كان `BatteryType` معرفًا ككيان Master Data مرجعي، لكن مجموعة الأسئلة الخاصة به لم تحسم صراحة هل القاموس Fixed أم Managed.

**القرار الذي حسمها:**

قسم 2.3 قرر صراحة:

```text
battery.physical_type_management
= managed

Battery Physical Type
→ Managed Master Data
→ قابلة للإضافة والتعديل من لوحة التحكم
```

**القرار النهائي:**

```text
BatteryType Management Mode = Managed Master Data
```

يظل هذا القرار خاصًا **بطريقة إدارة القاموس** ولا يحسم Initial Dataset، وهي مسجلة بصورة مستقلة في `OD-005`.

**مصادر التفاصيل:**

```text
questionnaire-export/guides/01-master-data/18-battery-types.md
questionnaire-export/guides/02-farm-structure/03-batteries.md
questionnaire-export/answers/02-farm-structure/03-batteries.md
```

**Resolved By:** `battery.physical_type_management` في 2.3.

---

### OD-005 — لا توجد Initial Dataset معتمدة للأنواع الفيزيائية للبطاريات

**الحالة:** Open  
**النوع:** Requirement Gap  
**الأولوية:** Medium  
**القسم:** 1.18 الأنواع الفيزيائية للبطاريات

**Question Key:**

```text
battery_type.initial_values
```

**القرار الحالي:**

```text
لا توجد قيم مبدئية محددة حاليًا
```

**المشكلة:**

يمكن اعتماد بنية `BatteryType` وطريقة إدارتها كـManaged Master Data، لكن لا يمكن إنشاء Dataset فعلية للأنواع دون اختراع قيم غير مدعومة بالمصدر.

**المطلوب حسمه:**

اعتماد أسماء/قيم الأنواع الفيزيائية التي يجب أن تبدأ بها المنظومة، أو اعتماد قرار صريح بأن القائمة تبدأ فارغة ويتم إدخالها ميدانيًا.

**مصدر التفاصيل:**

```text
questionnaire-export/guides/01-master-data/18-battery-types.md
```

**القرار النهائي:** لم يحسم بعد.

---

### OD-006 — طريقة إدارة قاموس أنواع المهام التشغيلية

**الحالة:** Open  
**النوع:** Requirement Gap  
**الأولوية:** Medium  
**القسم:** 1.19 أنواع المهام التشغيلية

**Question Keys:**

```text
لا يوجد Question Key مستقل يحسم Fixed vs Managed
```

**الوضع الحالي:**

تم اعتماد 35 نوع مهمة مبدئيًا، لكن لم يحسم هل `OperationalTaskType`:

```text
Fixed / system-defined catalogue
أم
Managed from admin
```

**المطلوب حسمه:**

تحديد Management Mode النهائي، مع مراعاة أن تعريف نوع المهمة مستقل عن قواعد توليد المهمة في Settings وعن المهمة الفعلية في Workflow.

**مصدر التفاصيل:**

```text
questionnaire-export/guides/01-master-data/19-operational-task-types.md
```

**القرار النهائي:** لم يحسم بعد.

---

### OD-007 — Mapping أنواع المهام إلى التصنيفات غير محسوم

**الحالة:** Open  
**النوع:** Requirement Gap  
**الأولوية:** Medium  
**القسم:** 1.19 أنواع المهام التشغيلية

**Question Keys ذات الصلة:**

```text
operational_task_type.fields
operational_task_type.categories
operational_task_type.initial_values
```

**القرارات الحالية:**

تم اعتماد:

```text
9 Task Categories
35 Initial Task Types
```

لكن لا يوجد قرار يحدد:

```text
أي Task Type يتبع أي Category
```

كما لم تحسم Cardinality العلاقة هل النوع يملك Category واحدة فقط أم أكثر، ووجود `category` كحقل لا يكفي وحده لاستنتاج النموذج النهائي.

**المطلوب حسمه:**

- Mapping الفعلي للأنواع المبدئية إلى التصنيفات.
- Cardinality النهائية للتصنيف.

**مصدر التفاصيل:**

```text
questionnaire-export/guides/01-master-data/19-operational-task-types.md
```

**القرار النهائي:** لم يحسم بعد.

---

### OD-008 — طريقة إدارة قاموس أسباب العزل

**الحالة:** Open  
**النوع:** Requirement Gap  
**الأولوية:** Medium  
**القسم:** 1.20 أسباب العزل

**Question Keys:**

```text
لا يوجد Question Key مستقل يحسم Fixed vs Managed
```

**الوضع الحالي:**

`IsolationReason` معرف كقاموس مرجعي، لكن لا يوجد قرار صريح يحدد هل القيم ثابتة في النظام أم قابلة للإدارة من لوحة التحكم.

**المطلوب حسمه:**

تحديد Management Mode النهائي للقاموس.

**مصدر التفاصيل:**

```text
questionnaire-export/guides/01-master-data/20-isolation-reasons.md
```

**القرار النهائي:** لم يحسم بعد.

---

### OD-009 — لا توجد Initial Dataset معتمدة لأسباب العزل

**الحالة:** Open  
**النوع:** Requirement Gap  
**الأولوية:** High  
**القسم:** 1.20 أسباب العزل

**Question Key:**

```text
isolation_reason.initial_values
```

**القرار الحالي:**

```text
غير محدد
```

**المشكلة:**

البنية المرجعية محسومة، لكن لا توجد أسباب عزل فعلية يمكن Seedها دون اختراع متطلبات طبية/تشغيلية غير معتمدة.

**المطلوب حسمه:**

اعتماد Initial Dataset لأسباب العزل الصحي، أو اعتماد قرار صريح بأن القاموس يبدأ فارغًا ويتم إدخاله إداريًا.

**مصدر التفاصيل:**

```text
questionnaire-export/guides/01-master-data/20-isolation-reasons.md
```

**القرار النهائي:** لم يحسم بعد.

---

### OD-010 — لا توجد Initial Dataset معتمدة لتصنيفات المشاكل الصحية

**الحالة:** Open  
**النوع:** Requirement Gap  
**الأولوية:** High  
**القسم:** 1.21 تصنيفات المشاكل / الملاحظات الصحية

**Question Key:**

```text
health_problem_category.initial_values
```

**القرار الحالي:**

```text
غير محدد حاليًا
```

**المشكلة:**

تم حسم بنية القاموس ومستوى التفصيل ودعم أكثر من تصنيف للحالة الصحية، لكن لا توجد قائمة أولية معتمدة يمكن استخدامها في Seed أو Requirements للبيانات المبدئية.

**المطلوب حسمه:**

اعتماد قائمة التصنيفات الصحية المبدئية دون تحويل المشروع إلى نظام بيطري تفصيلي غير مدعوم بالتصور الحالي.

**مصدر التفاصيل:**

```text
questionnaire-export/guides/01-master-data/21-health-problem-categories.md
```

**القرار النهائي:** لم يحسم بعد.

---

## Scope / Integration Decisions

### OD-011 — أسباب العزل لا تغطي حجر/ملاحظة الاستقبال حاليًا

**الحالة:** Deferred  
**النوع:** Scope Decision / Integration Gap  
**الأولوية:** Medium  
**الأقسام المتأثرة:** 1.20 أسباب العزل + Workflow استقبال الحيوان

**Question Key:**

```text
isolation_reason.scopes
```

**القرار الحالي:**

```text
health_isolation فقط
```

ولم يتم اعتماد:

```text
intake_quarantine_observation
other
```

**المعنى الحالي:**

قائمة `IsolationReason` تستخدم للعزل الصحي فقط، ولا تعتبر مصدرًا مرجعيًا لأسباب حجر/ملاحظة الحيوان أثناء الاستقبال.

**النقطة المفتوحة:**

إذا احتاج Workflow الاستقبال إلى أسباب حجر/ملاحظة مرجعية، فلا يوجد مصدر Master Data معتمد لها حاليًا.

**المطلوب حسمه لاحقًا:**

```text
- توسيع IsolationReason ليغطي Intake Quarantine
أو
- إنشاء/استخدام مصدر مرجعي آخر لأسباب حجر الاستقبال
أو
- إبقاء أسباب مرحلة الاستقبال دون قاموس مرجعي إذا كان ذلك هو القرار المقصود
```

**مصدر التفاصيل:**

```text
questionnaire-export/guides/01-master-data/20-isolation-reasons.md
```

**القرار النهائي:** مؤجل لحين مراجعة التكامل مع Workflow/Settings.

---

## Cross-cutting Implementation Boundaries

### OD-012 — تطبيق فريدة الاسم على الحقول متعددة اللغات

**الحالة:** Deferred  
**النوع:** Implementation Boundary  
**الأولوية:** Low / Before implementation schema freeze  
**الأقسام المتأثرة:** عدة قواميس Master Data التي تعتمد `unique_name`

**أمثلة Question Keys:**

```text
operational_activity.unique_name
production_purpose.unique_name
breed_metric.unique_name
breed.unique_name
```

**القرار الوظيفي المحسوم:**

```text
يجب منع تكرار الاسم
```

**غير المحسوم:**

طريقة تطبيق ذلك على أسماء عربية/إنجليزية متعددة اللغات، مثل:

```text
- uniqueness لكل لغة بصورة مستقلة
- normalization / case handling
- قاعدة التعامل مع اختلاف لغة دون الأخرى
```

**المطلوب حسمه:**

تحديد قاعدة تنفيذ موحدة قبل تثبيت Constraints النهائية لقاعدة البيانات، دون تغيير القرار الوظيفي الأساسي بمنع التكرار.

**مصادر التفاصيل:**

```text
questionnaire-export/guides/01-master-data/01-operational-activities.md
questionnaire-export/guides/01-master-data/02-production-purposes.md
questionnaire-export/guides/01-master-data/03-breed-metrics.md
questionnaire-export/guides/01-master-data/04-breeds.md
```

**القرار النهائي:** مؤجل لمرحلة تثبيت نموذج البيانات/الترجمة.

---

# القسم الثاني — هيكل المزرعة

## Blocking Conflicts

### OD-013 — إلزام علاقات العنبر عند الإنشاء يتعارض مع القرارات المتخصصة

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

**القرارات الحالية:**

```text
barn.required_fields
→ يعتبر عند إنشاء Barn أن:
   settings_profile
   ventilation_system
   cooling_system
   heating_system
   مطلوبة
```

لكن الأسئلة المتخصصة تقرر:

```text
barn.settings_profile_requirement
= required_before_operation
→ يمكن إنشاء Barn قبل ربط Settings Profile

barn.ventilation_relation
= optional_multiple

barn.cooling_relation
= optional_multiple

barn.heating_relation
= optional_multiple
```

**المشكلة:**

لا يمكن للعلاقات الأربع أن تكون في الوقت نفسه:

```text
Required on Create
```

و:

```text
Required later / Optional
```

ولا يمكن تثبيت Validation إنشاء العنبر قبل حسم أي قرار هو المقصود.

**المطلوب حسمه:**

```text
A) تعديل barn.required_fields
   → Settings Profile يبقى Required Before Operation
   → Environment Systems تبقى Optional Multiple

أو

B) اعتماد الإلزام عند الإنشاء
   → تعديل القرارات المتخصصة لتصبح متوافقة معه
```

**مصادر التفاصيل:**

```text
questionnaire-export/guides/02-farm-structure/02-barns.md
questionnaire-export/answers/02-farm-structure/02-barns.md
```

**القرار النهائي:** لم يحسم بعد.

---

## Integration / Requirement Gaps

### OD-014 — تعدد ملفات إعدادات التشغيل النشطة للعنبر دون قاعدة دمج أو أولوية

**الحالة:** Deferred  
**النوع:** Integration Gap  
**الأولوية:** High / Before Settings model freeze  
**الأقسام المتأثرة:** 2.2 بيانات العنبر + قسم الإعدادات وقواعد التشغيل

**Question Keys:**

```text
barn.multiple_active_settings_profiles
barn.settings_profile_requirement
```

**القرار الحالي:**

```text
Barn
→ يسمح بأكثر من Operational Settings Profile فعال في نفس الوقت
```

لكن لم يُحسم داخل 2.2:

```text
- أي Profile له الأولوية إذا تعارضت القيم
- هل القيم تدمج أم يطبق كل Profile على نطاق مختلف
- Effective Dates
- Versioning
- أي Profile يحكم أي عملية عند وجود أكثر من واحد
```

**المشكلة:**

السماح بأكثر من Profile فعال يمكن أن ينتج أكثر من قيمة نافذة لنفس القاعدة إذا لم يوجد نظام Scope / Precedence / Resolution واضح.

**المطلوب حسمه لاحقًا:**

يجب أن يقرر قسم Settings النموذج الذي يمنع وجود قاعدتين نهائيتين متعارضتين لنفس السياق، دون افتراض Merge Logic من قسم Barn.

**مصدر التفاصيل:**

```text
questionnaire-export/guides/02-farm-structure/02-barns.md
```

**القرار النهائي:** مؤجل لمراجعة قسم الإعدادات.

---

### OD-015 — آلية تقاعد البطارية غير ممثلة صراحة ضمن الحالات الحالية

**الحالة:** Open  
**النوع:** Requirement Gap  
**الأولوية:** High  
**القسم:** 2.3 بيانات البطارية

**Question Keys:**

```text
battery.statuses
battery.status_management
battery.physical_reconfiguration_after_history
battery.delete_policy
```

**القرارات الحالية:**

```text
battery.statuses
→ active
→ stopped
→ maintenance

battery.status_management
= managed
```

وفي الوقت نفسه:

```text
battery.physical_reconfiguration_after_history
→ Retire old Battery + create new Battery

battery.delete_policy
→ no hard delete / retire or take out of service only
```

**المشكلة:**

الـLifecycle يعتمد مفهوم `Retire Battery` بصورة صريحة، لكن القيم الحالية لا تتضمن `retired` ولا يوجد قرار متخصص يحدد هل التقاعد:

```text
- Status محددة
- Action ينتج حالة Managed جديدة
- استخدام stopped كحالة نهائية
- أو آلية Lifecycle مستقلة
```

كون قائمة الحالات Managed يجعل إضافة قيمة ممكنة، لكنه لا يجعل `retired` قيمة مضمونة أو يحدد معناها تلقائيًا.

**المطلوب حسمه:**

تحديد التمثيل النهائي لتقاعد Battery بما يحافظ على الفرق بين:

```text
Stopped temporarily
Maintenance temporarily
Retired permanently from future operation
```

**مصدر التفاصيل:**

```text
questionnaire-export/guides/02-farm-structure/03-batteries.md
questionnaire-export/answers/02-farm-structure/03-batteries.md
```

**القرار النهائي:** لم يحسم بعد.

---

### OD-016 — الحالات الإضافية للبطارية Managed لكن دلالتها التشغيلية غير معرفة

**الحالة:** Deferred  
**النوع:** Integration Gap  
**الأولوية:** Medium / Before allowing custom statuses  
**الأقسام المتأثرة:** 2.3 بيانات البطارية + Settings / Lifecycle Rules

**Question Keys:**

```text
battery.statuses
battery.status_management
battery.status_change_requires_empty
battery.status_change_requires_inactive_cages
battery.non_operational_child_effect
```

**القرار الحالي:**

```text
battery.status_management
= managed

Initial/current values
= active / stopped / maintenance
```

Business Rules الحالية معرّفة صراحة أساسًا للحالات المعروفة مثل `stopped` و`maintenance`.

**المشكلة:**

إذا أضاف المستخدم Status جديدة من لوحة الإدارة، فلا يوجد قرار يحدد:

```text
- هل تمنع التسكين
- هل تتطلب إخلاء البطارية
- هل تتطلب أن تكون الأقفاص غير تشغيلية
- هل تنتقل عدم الإتاحة إلى الأقفاص التابعة
- ما الانتقالات المسموحة منها وإليها
```

ولا يجوز اشتقاق هذه الدلالات من اسم الحالة فقط.

**المطلوب حسمه لاحقًا:**

إما:

```text
A) تقييد الحالات ذات الدلالة التشغيلية إلى مجموعة System-defined
```

أو:

```text
B) السماح بالحالات Managed مع نموذج قواعد يحدد Semantics لكل Status
```

أو اعتماد نموذج آخر صريح يمنع Status إدارية بلا دلالة تشغيلية معروفة.

**مصدر التفاصيل:**

```text
questionnaire-export/guides/02-farm-structure/03-batteries.md
```

**القرار النهائي:** مؤجل لحين مراجعة Settings / Lifecycle Rules.

---

## Cross-section Physical Model

> لا توجد نقطة جديدة منفصلة هنا؛ تعارض 2.4 الخاص بتمثيل المواصفات الفيزيائية للقفص تم دمجه داخل `OD-001` حتى لا يتكرر نفس القرار في أكثر من موضع.

---

## Implementation Boundaries

### OD-017 — نطاق السماح بتعديل كود المزرعة بعد بدء التاريخ التشغيلي

**الحالة:** Deferred  
**النوع:** Implementation Boundary  
**الأولوية:** Medium / Before identity rules freeze  
**القسم:** 2.1 بيانات المزرعة

**Question Key:**

```text
farm.code_strategy
```

**القرار الحالي:**

```text
automatic_editable

→ النظام يولد الكود تلقائيًا
→ المستخدم المخول يستطيع تعديله
```

**غير المحسوم:**

السؤال لا يحدد **متى تتوقف قابلية التعديل** أو هل تظل متاحة بعد وجود تاريخ تشغيلي وعلاقات كثيرة بالمزرعة.

الاحتمالات غير المحسومة تشمل مثلًا:

```text
- editable only during creation / before first operation
- editable later with audit/history
- editable دائمًا ما دام uniqueness محفوظًا
```

**المشكلة:**

Farm Code جزء من الهوية التشغيلية الظاهرة، وتغييرها بعد وجود تاريخ قد يؤثر على الروابط البشرية والتقارير والمراجع الخارجية، حتى لو ظلت الـPrimary Key الداخلية ثابتة.

**المطلوب حسمه:**

تحديد Lifecycle واضح لقابلية تعديل Farm Code بعد الإنشاء، مع الحفاظ على `farm.unique_rule` وعدم إعادة كتابة التاريخ بصورة مضللة.

**مصدر التفاصيل:**

```text
questionnaire-export/guides/02-farm-structure/01-farms.md
```

**القرار النهائي:** مؤجل قبل تثبيت Identity / Audit Requirements.

---

# Resolved Decisions

## OD-004 — طريقة إدارة قاموس الأنواع الفيزيائية للبطاريات

**الحالة:** Resolved  
**القرار:** `BatteryType` هي Managed Master Data قابلة للإدارة من لوحة التحكم.  
**مصدر الحسم:** `battery.physical_type_management` في قسم 2.3 بيانات البطارية.  
**ملاحظة:** Initial Dataset ما زالت غير محسومة بصورة مستقلة في `OD-005`.
