# 4.13 الصحة والعزل والتعافي والنفوق — دليل تفسير الإجابات

> **الحالة:** مسودة أولى مبنية على الإجابات الحالية للمراجعة — القسم مكتمل 13/13، مع تعارضين عابرين مهمين: نموذج نفوق المواليد أثناء الرضاعة مع 4.7، وطريقة تحديد مرحلة النفوق مقارنة بالمرجع الوظيفي؛ كما توجد نقطة تكامل داخلية تخص `operational_restriction_decision` مقابل القيود المشتقة من Settings  
> **ملف الإجابات المقابل:** `questionnaire-export/answers/04-workflow/13-healths.md`  
> **Question Keys المغطاة:** 13 / 13

---

## 1. الغرض من هذا الملف

هذا الملف يفسر **المعنى الوظيفي والتقني للإجابات المعتمدة فعليًا** في قسم `الصحة والعزل والتعافي والنفوق` ضمن `الحركات ودورة التشغيل الفعلية`.

هو لا يغير الإجابات، ولا يحول المشروع إلى نظام إدارة بيطرية كامل، ولا يخترع تشخيصات أو علاجات أو قيم Master Data غير معتمدة.

```text
answers/04-workflow/13-healths.md
= ماذا قررنا؟

                    ↓ تفسير

guides/04-workflow/13-healths.md
= ماذا تعني القرارات تقنيًا؟ وما حدودها؟
```

الحد المعماري الأساسي:

```text
HealthProblemCategory / IsolationReason / MortalityReason
→ Master Data

Actual Health Observation / Episode
+ Health Isolation / Recovery
+ Mortality Event
→ 4.13

Actual Housing Transfer to/from isolation
→ 4.2

Litter current alive count / lactation context
→ 4.7

Tasks / follow-up scheduling execution
→ 4.17

Health / Isolation / Mortality rules and thresholds
→ Settings 6.11

Actual Farm Exit
→ 4.15
```

ويجب الحفاظ على الفصل:

```text
Health Problem Category
≠ Isolation Reason
≠ Mortality Reason
```

وكذلك:

```text
Mortality Reason
≠ Mortality Event
```

---

## 2. الهدف الوظيفي من القسم

الغرض من 4.13 هو تسجيل الأحداث الصحية التي تؤثر على دورة التشغيل مع الحفاظ على التاريخ الكامل، وليس اختزال الحيوان في Status صحي واحد.

الصورة الحالية:

```text
Health Observation / Review
→ Health Episode History
→ Current Health State derived from history
→ Operational restrictions derived from Health + Settings
→ Isolation when applicable
→ Recovery
→ Re-evaluation
→ Return to eligible operations
```

أما النفوق فهو Event نهائي مستقل يمكن أن يحدث في أي مرحلة تشغيلية:

```text
Production Herd
Pregnancy
Lactation
Post-weaning Growth
Fattening
Other operational contexts
```

ويجب أن يؤدي إلى تحديثات مترابطة دون حذف التاريخ أو إنشاء مصادر متنافسة.

---

## 3. ملخص القرارات المعتمدة

```text
Health History
└── independent health events linked within Health Episode

Health Observation Fields
├── animal
├── observed_at
├── problem_or_observation
├── severity
├── resulting_health_status
├── isolation_decision
├── operational_restriction_decision
├── performed_by
└── notes

Current Health States
├── normal
├── under_observation
├── sick
├── isolated
└── recovered

Operational Restrictions
└── derived from current health + Settings

Health Isolation
├── independent Health Isolation record
├── linked to canonical Housing Movement in 4.2
└── actual isolation starts when transfer completes

Isolation Record
├── animal
├── source_health_reference
├── isolation_reason
├── started_at
├── isolation_housing_movement_reference
├── review_task_reference
├── ended_at
├── performed_by
└── notes

Recovery
└── recovery recorded
    → health/isolation closes
    → re-evaluation required
    → operational eligibility restored only after appropriate re-check

Mortality Subjects
├── individual_animal
├── preweaning_litter_quantity
└── identified_preweaning_offspring

Mortality Reason
└── required Master Data reference
    ├── unknown used when cause is unknown
    └── other requires text detail

Mortality Stage
└── manual entry per Mortality Event

Post Mortality
└── automatic linked transitions
    ├── presence
    ├── occupancy
    ├── active paths
    ├── litter current count when applicable
    └── open tasks
```

---

## 4. الصحة والمتابعة

### 4.1 `health.followup_history_model`

القرار:

```text
independent_health_events_with_episode_link
```

كل ملاحظة أو مراجعة صحية Event مستقل داخل `Health Episode`، ويمكن ربط المراجعة الجديدة بما سبقها.

إذن لا يجوز:

```text
Overwrite previous health state
```

بل يجب أن نتمكن من إعادة بناء:

```text
Observation A
→ Review B
→ Isolation / Monitoring
→ Review C
→ Recovery
```

والـCurrent Health State يكون **ملخصًا مشتقًا من آخر سياق صحي صالح** وليس بديلًا عن التاريخ.

---

### 4.2 `health.observation_record_fields`

تم اعتماد جميع الحقول التسعة.

`problem_or_observation` يجب قراءته مع Master Data 1.21 التي اعتمدت أن Health Record الفعلي يستطيع استخدام:

```text
one or more HealthProblemCategory records
+
optional textual details
```

إذن لا ينبغي اختزال المشكلة الصحية في Free Text فقط، ولا اختراع قائمة أمراض داخل 4.13.

`severity` مسجلة على Event الصحي نفسه؛ Master Data لا تعرف Severity Levels، لذلك القيم أو المقياس المستخدم للخطورة لم يتم حسمه هنا.

`resulting_health_status` يصف النتيجة الصحية بعد المراجعة، لكن لا يمحو Events السابقة.

---

### 4.3 `health.status_categories`

الحالات المعتمدة:

```text
normal
under_observation
sick
isolated
recovered
```

هذه حالات **Health Summary** فقط.

لا يجوز منها استنتاج أن الحيوان:

- جاهز للتلقيح.
- صالح للنقل.
- جاهز للبيع.
- عضو أو غير عضو في القطيع الإنتاجي.

هذه الأهلية تنتج من تكامل الحالة الصحية مع Settings والسياق التشغيلي.

---

### 4.4 `health.operational_restriction_model`

القرار المتخصص:

```text
derive_restrictions_from_health_and_settings
```

أي أن القيود التشغيلية لا تحفظ ككيان مستقل لكل حالة.

الصورة المقصودة:

```text
Current Health Context
+
Settings 6.11
+
Requested Operation
→ Eligible / Restricted
```

مثلًا قد تؤثر الصحة على التلقيح أو استخدام الذكر أو النقل العادي أو الاعتماد داخل القطيع أو الجاهزية للبيع، لكن **النطاق والقواعد الفعلية ما زالت غير محسومة** لأن 6.11 لم تتم الإجابة عليها بعد.

#### نقطة تكامل داخلية

سؤال `health.observation_record_fields` اختار أيضًا:

```text
operational_restriction_decision
```

ووصفه كـ«قرار إيقاف عمليات تشغيلية مؤقتًا عند الحاجة».

لكن السؤال المتخصص 4.4 حسم أن القيود **مشتقة من Health + Settings دون حفظ قيود مستقلة للحالة**.

لذلك لا يجوز تفسير الحقل كـCase-level restriction authoritative مستقل يناقض القرار المتخصص.

حتى يتم حسم الصياغة النهائية، التفسير الأكثر أمانًا هو أنه يمثل **السياق/النتيجة التشغيلية الظاهرة وقت المراجعة** وليس مصدر الحقيقة المستقل للقيود.

هذه نقطة تحتاج توحيدًا لاحقًا في الـRequirements النهائية.

---

## 5. العزل الصحي

### 5.1 `health.isolation_integration_model`

القرار:

```text
health_isolation_linked_to_completed_housing_transfer
```

إذن يوجد كيان/فترة Health Isolation مستقلة، لكن **الموقع الفعلي** لا يسجل داخلها كمصدر حركة مستقل.

المسار:

```text
Health Review / Isolation Decision
→ Housing Movement in 4.2
→ transfer completed to eligible isolation cage
→ actual Health Isolation starts
```

وهذا يمنع وجود سجلين متنافسين للموقع.

بالتالي:

```text
Isolation Decision Time
≠ necessarily Isolation started_at
```

وفق القرار الحالي، `started_at` يمثل بداية العزل الفعلية عند اكتمال حركة النقل.

---

### 5.2 `health.isolation_record_fields`

تم اعتماد:

```text
animal
source_health_reference
isolation_reason
started_at
isolation_housing_movement_reference
review_task_reference
ended_at
performed_by
notes
```

`isolation_reason` يجب ربطه بالقاموس المرجعي `IsolationReason` من Master Data 1.20 عند استخدام السبب المرجعي؛ لا يعاد إنشاء قائمة الأسباب داخل Workflow.

ويجب مراعاة أن Master Data الحالية حسمت Scope واحدًا فقط لأسباب العزل:

```text
health_isolation
```

ولم تعتمد أسباب حجر/ملاحظة الاستقبال ضمن نفس القاموس.

كما أن Initial Dataset لأسباب العزل غير محددة حاليًا، لذلك لا يجوز توليد قيم من المعرفة العامة.

`review_task_reference` مجرد Reference إلى Task عند إنشائها؛ توقيت المراجعة وأولويتها وقاعدة إنشاء المهمة تنتمي إلى Settings / 4.17.

---

## 6. التعافي والعودة للتشغيل

### 6.1 `health.recovery_transition_model`

القرار:

```text
recovery_then_reevaluation_before_operational_return
```

المسار:

```text
Recovery Event
→ close Health Episode / Isolation as applicable
→ Re-evaluation
→ re-check operational eligibility
→ return only to operations whose rules are satisfied
```

إذن:

```text
Recovered
≠ automatically ready for all operations
```

وهذا متوافق مع المرجع الوظيفي:

```text
انتهاء الحالة الصحية
→ إعادة تقييم
→ العودة للتشغيل
```

لكن توجد Dependency مهمة على Settings 6.11؛ لأن القيود التشغيلية مشتقة من القواعد، فيجب لاحقًا أن توضح Settings كيف تمنع عودة الأهلية قبل اجتياز إعادة التقييم المطلوبة.

---

### 6.2 `health.recovery_record_fields`

حدث التعافي يحتفظ بـ:

```text
animal
health_episode_reference
isolation_reference
recovered_at
recovery_outcome
next_review_reference
performed_by
notes
```

ولا يغير أو يحذف الحالة المرضية السابقة.

`next_review_reference` يربط بالمتابعة التالية عند إنشائها، لكنه لا يحدد توقيتها؛ هذه قاعدة منفصلة.

---

## 7. النفوق

### 7.1 `mortality.subject_scopes`

تم اعتماد ثلاثة Scopes:

```text
individual_animal
preweaning_litter_quantity
identified_preweaning_offspring
```

إذن `Mortality Event` العام في 4.13 قادر على تمثيل:

1. نفوق حيوان بعد وجود Animal Record مستقل.
2. نفوق عدد من مواليد البطن قبل الفطام.
3. نفوق مولود معين قبل الفطام إذا كان له Temporary Identifier من 4.7.

وهذا متوافق مع المرجع الوظيفي الذي يفرق بين:

```text
Before Weaning
→ litter / quantity tracking

After Weaning
→ individual animal tracking
```

---

### 7.2 تعارض 4.13 مع 4.7 في نفوق الرضاعة

4.7 اعتمد:

```text
dedicated_lactation_mortality_event
```

أي Event خاص بالرضاعة منفصل عن النفوق العام.

بينما 4.13 اعتمد صراحة أن `Mortality Event` العام يدعم:

```text
preweaning_litter_quantity
identified_preweaning_offspring
```

كما أن Project Context والمرجع الوظيفي يضعان نفوق ما بعد الولادة ضمن مسار النفوق العام، مع تأثيره على `Current Alive Count` للبطن.

إذن يوجد تعارض معماري مباشر:

```text
4.7 Dedicated Lactation Mortality Event
VS
4.13 General Canonical Mortality Event for pre-weaning subjects
```

ولا يجوز للـBlueprint النهائي إنشاء مصدرين مستقلين لنفس واقعة النفوق دون قرار صريح لاحق.

حتى الحسم، يجب الحفاظ على الإجابتين كما هما وتسجيل التعارض فقط.

---

### 7.3 `mortality.event_record_fields`

تم اعتماد:

```text
subject
mortality_count
discovered_at
age_at_mortality
sex
last_weight_reference
housing_reference
operational_stage
mortality_reason_reference
recorded_by
notes
```

التفسير:

- `mortality_count` يستخدم فقط عند Scope يعتمد الكمية قبل الفطام.
- `last_weight_reference` Reference إلى Weight History، وليس نسخة وزن مستقلة.
- `housing_reference` Reference إلى Housing History، وليس تعديلًا للموقع الحالي يدويًا.
- `mortality_reason_reference` يعود إلى `MortalityReason` في Master Data 1.7.
- `age_at_mortality` مشتق عند إمكان حسابه، ولا يجب اختلاق عمر عند عدم توفر بيانات الميلاد الكافية.

---

### 7.4 `mortality.stage_derivation_model`

الإجابة الحالية:

```text
manual_stage_per_mortality_event
```

أي أن المستخدم يسجل المرحلة التشغيلية يدويًا مع كل حالة نفوق.

#### تعارض مباشر مع المرجع الوظيفي

المرجع الوظيفي ينص صراحة على أن:

```text
المرحلة يفضل أن يستنتجها النظام تلقائيًا
من حالة الأرنب وقت تسجيل النفوق
```

وبالتالي القرار الحالي يخالف الاتجاه الوظيفي المفضل في المصدر.

هذا ليس مجرد Detail تنفيذية، لأنه يؤثر على:

- دقة تقارير النفوق حسب المرحلة.
- احتمال عدم التطابق بين Timeline الفعلي والقيمة اليدوية.
- تحليل النفوق أثناء الحمل/الرضاعة/التسمين/القطيع.

لا يتم تعديل الإجابة من هذا الـGuide، لكن يجب حسم هذا التعارض قبل تحويل الـBlueprint إلى Requirement نهائي للتنفيذ.

---

### 7.5 `mortality.reason_reference_policy`

القرار:

```text
reason_required_unknown_value_and_other_detail
```

كل Mortality Event يجب أن يحمل `MortalityReason` مرجعيًا.

إذا كان السبب غير معروف:

```text
MortalityReason = unknown
```

وإذا كان:

```text
MortalityReason = other
```

فيجب تسجيل وصف نصي للسبب الفعلي.

وهذا يكمل Master Data 1.7 التي كانت قد اعتمدت `unknown` و`other` كقيم مرجعية، دون أن تحسم هناك إلزام الوصف عند `other`.

---

### 7.6 `mortality.post_event_transition_model`

القرار:

```text
automatic_linked_post_mortality_transitions
```

بمجرد تسجيل Mortality Event، يجب أن يطلق النظام آثارًا مترابطة تلقائيًا، منها حسب انطباق الحالة:

```text
Presence update
Occupancy closure / capacity release
Active workflow path closure
Litter current alive count update
Open task handling
```

لكن يجب تنفيذ كل أثر عبر مصدره Canonical:

```text
Housing / occupancy → 4.2
Litter current count → derived through 4.7 events
Tasks → 4.17
Presence / exit interpretation → 4.15
```

ولا يعني النفوق إنشاء Farm Exit Event ثاني إذا كان 4.15 يعتمد الحدث المتخصص كمصدر Canonical لحالة الوجود.

وبذلك يجب أن تظل قاعدة:

```text
One physical mortality occurrence
→ one canonical Mortality Event
```

مع آثار مشتقة ومترابطة، لا أحداث متنافسة.

---

## 8. العلاقة مع Settings 6.11

القسم 4.13 يسجل **ما حدث فعليًا**.

أما 6.11 فما زال غير مجاب عنه، وهو المسؤول عن قواعد مثل:

- ما العمليات التي تمنعها الحالة الصحية.
- ما البيانات التي تستخدم لتحديد القيود.
- توقيت المتابعة الصحية.
- شروط العودة للتشغيل.
- متى يصبح العزل مطلوبًا أو موصى به.
- عوامل قرار العزل.
- قواعد مدة ومراجعة العزل.
- حدود ومؤشرات النفوق غير الطبيعي.
- أثر الحالات الاستثنائية على المسار.

إذن لا يجوز من 4.13 وحده اختراع Thresholds أو مواعيد أو قواعد منع.

---

## 9. حدود الكيانات ومصادر الحقيقة

```text
HealthProblemCategory
→ what kind of problem / observation

Health Observation / Episode
→ what health event/review happened

Current Health State
→ derived summary

Health Operational Restriction
→ derived eligibility from Health + Settings

IsolationReason
→ why isolation was chosen

Health Isolation
→ actual isolation period

Housing Movement
→ where the animal physically moved

MortalityReason
→ why mortality occurred

Mortality Event
→ actual mortality occurrence
```

ولا يجوز:

- تخزين الموقع الحالي داخل Health Isolation كمصدر مستقل عن 4.2.
- تخزين وزن جديد داخل Mortality بدل Weight History.
- استخدام HealthProblemCategory كسبب نفوق تلقائيًا.
- استخدام IsolationReason كتشخيص صحي.
- استخدام MortalityReason بدل Mortality Event.
- حذف Health Episode عند التعافي.
- اعتبار التعافي عودة تلقائية لكل العمليات.

---

## 10. فحص الاتساق

### متماسك داخليًا

- التاريخ الصحي Event-based وليس Overwrite.
- Current Health State ملخص منفصل عن التاريخ.
- العزل يرتبط بحركة Housing Canonical.
- التعافي لا يعيد التشغيل مباشرة دون Re-evaluation.
- النفوق يدعم الفرد وما قبل الفطام.
- سبب النفوق إلزامي مع `unknown` و`other` بصورة واضحة.
- Post-mortality effects مترابطة تلقائيًا دون حذف التاريخ.

### يحتاج حسمًا

#### تعارض 1 — 4.7 مقابل 4.13

```text
Dedicated Lactation Mortality Event
VS
General Mortality Event supporting pre-weaning mortality
```

#### تعارض 2 — المرحلة التشغيلية للنفوق

```text
Current answer:
manual_stage_per_mortality_event

Functional source preference:
auto-derived stage from current timeline/context
```

#### نقطة تكامل 3 — القيود التشغيلية

```text
health.observation_record_fields
includes operational_restriction_decision

VS

health.operational_restriction_model
= derive_restrictions_from_health_and_settings
```

يجب ألا يتحول الحقل الأول إلى مصدر حقيقة مستقل يناقض النموذج المشتق.

#### نقطة تكامل 4 — العودة بعد التعافي

التعافي يتطلب Re-evaluation قبل العودة، لكن المنع الفعلي مشتق من Settings؛ لذلك يجب أن تكمل 6.11 قاعدة عدم استعادة الأهلية قبل اجتياز إعادة التقييم.

---

## 11. المخرجات المطلوبة للـRequirements

1. Health Episode يحتفظ بتاريخ مستقل لكل ملاحظة/مراجعة صحية.
2. Health Observation يدعم الحقول التسعة المعتمدة.
3. Health Record يتكامل مع `HealthProblemCategory` حسب نموذج Master Data المعتمد: one or more categories + optional details.
4. Current Health State مشتق ويدعم الحالات الخمس المعتمدة.
5. Operational Restrictions مشتقة من الحالة الصحية + Settings، ولا تنشأ كقائمة قيود مستقلة لكل Event دون قرار جديد.
6. Health Isolation سجل مستقل مرتبط بحركة Housing Canonical، وبدايته الفعلية عند اكتمال النقل.
7. `IsolationReason` يبقى Master Data منفصلة ومخصصة حاليًا لنطاق `health_isolation`.
8. Recovery Event يحافظ على التاريخ ويحتاج Re-evaluation قبل استعادة الأهلية التشغيلية.
9. Mortality Event العام يدعم individual / pre-weaning quantity / identified pre-weaning offspring.
10. Mortality Event يحتفظ بالحقول الأحد عشر المعتمدة مع References لمصادر الوزن والموقع.
11. MortalityReason مرجع إلزامي؛ `unknown` للسبب غير المعروف، و`other` يحتاج وصفًا نصيًا.
12. Post-mortality transitions تحدث تلقائيًا ومترابطة على الوجود والإشغال والمسارات والبطن والمهام حسب الانطباق.
13. لا ينشأ Farm Exit Event منافس لمجرد النفوق إذا كان Mortality Event هو الحدث Canonical لحالة الوجود.
14. يجب حسم تعارض Mortality 4.7/4.13 قبل المتطلبات النهائية.
15. يجب حسم Manual vs Derived Mortality Stage قبل المتطلبات النهائية.
16. يجب توحيد معنى `operational_restriction_decision` مع نموذج القيود المشتق.
17. جميع Health/Isolation/Mortality thresholds والتوقيتات وقواعد المنع والعودة تظل في Settings 6.11.
