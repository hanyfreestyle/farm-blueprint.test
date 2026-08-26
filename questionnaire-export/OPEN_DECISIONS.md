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
| 2. هيكل المزرعة | Pending Review |
| 3. بيانات الحيوان وتكوين القطيع | Pending Review |
| 4. الحركات ودورة التشغيل الفعلية | Pending Review |
| 5. التقارير والتحليلات والتنبيهات ومؤشرات الأداء | Pending Review |
| 6. الإعدادات وقواعد التشغيل | Pending Review |

> تمت مراجعة الـGuides الحالية للقسم الأول، مع دمج النقاط المتكررة وعدم تحويل حدود التفسير العامة إلى Open Decision إلا عندما كان هناك قرار فعلي ناقص أو تعارض أو فجوة يمكن أن تؤثر على الـBlueprint النهائي.

---

# القسم الأول — إدارة البيانات الأساسية

## Blocking Conflicts

### OD-001 — مكان مواصفات النوع الفيزيائي للقفص

**الحالة:** Open  
**النوع:** Blocking Conflict  
**الأولوية:** Blocking before Final Requirements  
**القسم:** 1.16 أنواع الأقفاص الفيزيائية

**Question Keys:**

```text
cage_physical_type.fields
cage_physical_type.required_fields
cage_physical_type.attribute_model
```

**القرارات الحالية:**

```text
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

**المشكلة:**

لا يمكن بناء Schema / Requirement نهائي واحد يجمع القرارين في الوقت نفسه. لم يُحسم هل `dimensions` و`default_capacity` و`physical_features` خصائص للنوع الفيزيائي أم خصائص للتكوين الفعلي للبطارية/القفص.

**المطلوب حسمه:**

```text
A) CagePhysicalType يحمل المواصفات الأساسية
   → dimensions/default_capacity وربما physical_features تبقى داخله

أو

B) CagePhysicalType مجرد اسم/وصف مرجعي
   → المواصفات تنتقل إلى Battery/Cage Configuration
```

**مصادر التفاصيل:**

```text
questionnaire-export/guides/01-master-data/16-cage-physical-types.md
questionnaire-export/answers/01-master-data/16-cage-physical-types.md
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
**النوع:** Lifecycle Conflict  
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

**الحالة:** Open  
**النوع:** Requirement Gap  
**الأولوية:** Medium  
**القسم:** 1.18 الأنواع الفيزيائية للبطاريات

**Question Keys:**

```text
لا يوجد Question Key مستقل يحسم Fixed vs Managed
```

**الوضع الحالي:**

`BatteryType` معرف ككيان Master Data مرجعي، لكن مجموعة الأسئلة الحالية لا تحسم صراحة هل القاموس:

```text
Fixed / system-defined
أم
Managed from admin
```

**المطلوب حسمه:**

تحديد Management Mode النهائي للقاموس وصلاحية إضافة/تعديل تعريفات جديدة.

**مصدر التفاصيل:**

```text
questionnaire-export/guides/01-master-data/18-battery-types.md
```

**القرار النهائي:** لم يحسم بعد.

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

يمكن اعتماد بنية `BatteryType`، لكن لا يمكن إنشاء Dataset فعلية للأنواع دون اختراع قيم غير مدعومة بالمصدر.

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

# Resolved Decisions

لا توجد نقاط من هذا السجل تم حسمها بعد.
