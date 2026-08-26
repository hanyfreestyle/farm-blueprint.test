# 3.4 القطيع الافتتاحي وتهيئة نقطة البداية — دليل تفسير الإجابات

> **الحالة:** مسودة أولى مبنية على الإجابات الحالية للمراجعة  
> **ملف الإجابات المقابل:** `questionnaire-export/answers/03-animal-herd/04-animals.md`  
> **Question Keys المغطاة:** 11 / 11 — منها 10 مطبقة حاليًا و1 غير مطبق بسبب الـDependency

---

## 1. الغرض من هذا الملف

هذا الملف يفسر **المعنى الوظيفي والتقني للإجابات المعتمدة فعليًا** في قسم `القطيع الافتتاحي وتهيئة نقطة البداية` ضمن `بيانات الحيوان وتكوين القطيع`.

هو ليس مصدرًا بديلًا للإجابات، ولا يغير أي قرار موجود في ملف الإجابات.

```text
answers/03-animal-herd/04-animals.md
= ماذا قررنا؟

                    ↓ تفسير

guides/03-animal-herd/04-animals.md
= ماذا تعني هذه القرارات تقنيًا؟ وما حدودها؟
```

القاعدة الأساسية لهذا القسم:

```text
Opening Herd Setup
= إثبات الوضع الحالي عند بدء استخدام النظام

وليس

Historical Reconstruction
= إعادة اختراع الأحداث التي حدثت قبل وجود النظام
```

---

## 2. الهدف الوظيفي من القسم

يعالج هذا القسم حالة **مزرعة قائمة بالفعل** تبدأ استخدام النظام بينما لديها حيوانات موجودة في مراحل وأوضاع مختلفة، وبعض تاريخها السابق غير معروف أو غير مكتمل.

الهدف هو إنشاء نقطة بداية قابلة للتشغيل دون إجبار المزرعة على:

- بدء كل الحيوانات من أول دورة تشغيلية جديدة.
- اختلاق تواريخ أو أحداث لم يتم توثيقها.
- استكمال كل البيانات القديمة قبل السماح بالبدء.

المبدأ المعتمد:

```text
ابدأ من أول معلومة موثوقة متاحة
+
سجل الوضع الحالي
+
ميز أن التاريخ السابق غير مكتمل
+
ابدأ التاريخ التشغيلي الرسمي من نقطة الاعتماد
```

---

## 3. ملخص القرارات المعتمدة

```text
Opening Herd Setup
├── Current Snapshot
│   ├── current weight
│   ├── current housing
│   ├── current health state
│   ├── current operational stage
│   └── current reproductive context
│
├── Missing Data
│   └── incomplete data allowed and flagged
│       until required by a later operation
│
├── Starting Contexts
│   ├── production male
│   ├── production female without active cycle
│   ├── female in active reproductive cycle
│   ├── replacement candidate
│   ├── growing
│   ├── fattening
│   └── health observation / isolation
│
├── Female Reproductive Starting Points
│   ├── mated / awaiting pregnancy check
│   ├── confirmed pregnant
│   ├── pregnant near kindling
│   ├── lactating
│   └── lactating and remated
│
├── Prior History
│   ├── current snapshot only
│   └── no reconstructed prior event history
│
├── Data Quality
│   └── record-level flag: history before system start incomplete
│
├── Pre-Activation Review
│   ├── valid housing where required
│   ├── one current location only
│   ├── capacity respected
│   ├── operationally available housing
│   ├── usage compatibility
│   ├── valid isolation housing
│   ├── required grouping complete
│   └── no critical missing data
│
├── Activation
│   └── Draft → Review → Activate
│
├── Baseline
│   └── preserve opening herd snapshot as historical baseline
│
└── After Activation
    └── run task evaluation from the registered current state
```

---

## 4. لقطة الوضع التشغيلي الحالية

### `animal.opening_snapshot_operational_fields`

تم اعتماد تسجيل المعلومات التالية عند نقطة البداية:

```text
current_weight
current_housing
current_health_state
current_operational_stage
current_reproductive_context
```

هذه المعلومات تمثل **Snapshot افتتاحية** وليست حقولًا ثابتة يتم تعديلها يدويًا إلى الأبد.

بعد بدء التشغيل الطبيعي يجب أن تأتي القيم الحالية من مصادرها التشغيلية الصحيحة، مثل:

- الوزن الحالي من سجل القياسات / الأوزان.
- الموقع الحالي من حركات التسكين والنقل.
- الحالة الصحية من Workflow الصحة والعزل.
- المرحلة التشغيلية من الأحداث والمسارات ذات الصلة.
- السياق التناسلي من دورة التلقيح والحمل والولادة والرضاعة.

إذن الـOpening Snapshot هي **تهيئة لأول حالة معروفة**، وليست مصدرًا موازيًا دائمًا للحقيقة التشغيلية.

---

## 5. التعامل مع البيانات الناقصة

### `animal.opening_missing_data_policy`

القرار المعتمد:

```text
allow_incomplete_flag_and_require_when_needed
```

أي أنه يسمح بإدخال الحيوان حتى لو كانت بعض البيانات غير متاحة، بشرط:

1. عدم اختراع قيمة بدل المعلومة المفقودة.
2. تمييز أن البيانات ناقصة / غير معروفة.
3. إلزام استكمال المعلومة عندما تصبح مطلوبة لتنفيذ عملية فعلية تعتمد عليها.

مثال مفاهيمي:

```text
Breed unknown now
→ animal can exist in opening setup

Later operation requires Breed
→ operation cannot proceed until Breed is supplied if the rule requires it
```

هذه السياسة لا تعني أن كل البيانات اختيارية؛ فهناك مراجعات حرجة قبل الاعتماد، كما توجد قواعد تشغيل لاحقة قد تجعل معلومة معينة مطلوبة.

---

## 6. أوضاع البداية المدعومة

### `animal.opening_starting_contexts`

يجب ألا يفترض النظام أن كل الحيوانات تبدأ من نقطة موحدة.

تم اعتماد دعم:

- ذكر ضمن القطيع الإنتاجي.
- أنثى إنتاج بدون دورة تناسلية نشطة حاليًا.
- أنثى داخل دورة تناسلية نشطة بالفعل.
- مرشح / مرشحة للإحلال.
- حيوان في مرحلة النمو.
- حيوان في مسار التسمين.
- حيوان تحت الملاحظة الصحية أو العزل.

هذه **Contexts افتتاحية** وليست Enum واحدة تختصر كل حالة الحيوان المستقبلية.

لا يجوز بناء `Animal.status` واحد يحتوي كل هذه المفاهيم ثم استخدامه لوصف حياة الحيوان بالكامل؛ المشروع يعتمد الفصل بين المسارات والأحداث المتزامنة.

---

## 7. نقطة البداية التناسلية للأنثى

### `animal.opening_reproductive_contexts`

عند وجود أنثى داخل دورة تناسلية قائمة قبل بدء النظام، يجب دعم البداية من أي من الحالات المعتمدة:

```text
Mated → Awaiting Pregnancy Check
Confirmed Pregnant
Pregnant Near Kindling
Lactating
Lactating + Remated
```

هذا القرار مهم لأنه يسمح ببدء النظام **من منتصف دورة حقيقية قائمة** بدل إنشاء تلقيح أو ولادة وهمية فقط لاستكمال التسلسل.

كما يؤكد دعم:

```text
Lactating
+
Remated / new reproductive cycle
```

في الوقت نفسه، وبالتالي لا يجوز اختزال الوضع الإنتاجي للأنثى في Status واحد مانع للتداخل.

### حدود البيانات الزمنية

الإجابات الحالية تثبت **السياق التناسلي الافتتاحي** لكنها لا تحدد صراحة كل التاريخ/الوقت المطلوب لكل Context.

لذلك لا يجوز اختراع:

- تاريخ تلقيح سابق.
- تاريخ ولادة سابق.
- تاريخ بداية رضاعة.
- أي موعد تاريخي غير معروف.

إذا كان Task Engine أو Workflow يحتاج Anchor زمنيًا لحساب موعد قادم، تطبق سياسة البيانات الناقصة:

```text
Known reliable date → use it
Unknown date → do not invent it
Required for next operation → request/complete it before that operation can rely on it
```

---

## 8. سياسة التاريخ السابق لبدء النظام

### `animal.opening_trusted_prior_history_strategy`

القرار المعتمد هو:

```text
current_snapshot_only
```

أي أن القطيع الافتتاحي لا يستخدم لإعادة بناء Timeline تاريخية قبل بدء النظام.

حتى إذا كانت هناك معلومات عامة معروفة عن الماضي، القرار الحالي لا يعتمد إدخالها كأحداث سابقة ضمن عملية Opening Herd Setup.

لهذا أصبح السؤال:

`animal.opening_trusted_prior_fact_types`

**غير مطبق حاليًا** بسبب الـDependency، ولا تنتج عنه Requirements في الوضع الحالي.

هذا ينسجم مع المبدأ:

```text
Unknown past ≠ Event did not happen
Unknown past ≠ Reconstructed event
```

---

## 9. تتبع عدم اكتمال التاريخ

### `animal.opening_history_completeness_tracking`

القرار هو:

```text
record_level_incomplete_before_start
```

أي أن الحيوان الداخل ضمن القطيع الافتتاحي يحمل علامة عامة توضح أن:

```text
History before system start may be incomplete
```

ولا يتم حاليًا تتبع Provenance / Completeness منفصل لكل حقيقة تاريخية.

الهدف منع تفسير عدم وجود سجل تلقيح أو ولادة قديم مثلًا على أنه دليل أن العملية لم تحدث.

---

## 10. المراجعات السابقة لاعتماد القطيع

### `animal.opening_pre_activation_checks`

قبل اعتماد نقطة البداية يجب تنفيذ جميع المراجعات المختارة:

```text
1. Valid housing when housing is required
2. No animal in multiple current locations
3. Housing capacity not exceeded
4. Housing location operationally available
5. Housing usage compatible with animal / operation
6. Isolation / observation animals housed appropriately
7. Required grouping / assignments complete
8. No critical missing data blocking first operations
```

هذه المراجعات **لا تعيد تعريف قواعد التسكين والسعة والإتاحة**.

بل تستخدم القواعد المعتمدة من:

```text
Farm Structure
+ Housing Workflow
+ Production Group organization
+ Operational Settings
```

للتأكد أن نقطة البداية قابلة للتشغيل.

---

## 11. دورة اعتماد القطيع الافتتاحي

### `animal.opening_activation_model`

المسار المعتمد:

```text
Draft
  ↓
Review
  ↓
Resolve blocking issues
  ↓
Activate Opening Baseline
```

ولا تصبح كل إضافة فردية نقطة تشغيل رسمية فور إدخالها.

مرحلة الـDraft / Review تسمح بتصحيح:

- التسكين المتعارض.
- تجاوز السعة.
- النقص الحرج.
- التخصيصات غير المكتملة.
- أي تعارض يمنع بدء التشغيل.

---

## 12. الرصيد الافتتاحي كـBaseline تاريخية

### `animal.opening_baseline_snapshot`

تم اعتماد الاحتفاظ بصورة القطيع عند التفعيل كـHistorical Baseline.

يمكن أن تعكس الـBaseline مثلًا الأعداد المشتقة عند لحظة الاعتماد:

- الذكور الإنتاجية.
- الإناث الإنتاجية.
- الحوامل.
- المرضعات.
- مرشحي الإحلال.
- حيوانات النمو أو التسمين.
- الحيوانات تحت الملاحظة / العزل.

لكن هذه الأعداد **تحسب من سجلات الحيوانات وقت الاعتماد** ولا تخزن كحقول يكتبها المستخدم بصورة مستقلة.

الغرض هو إمكانية المقارنة مستقبلًا بين:

```text
Opening State
vs
Later Herd State
```

---

## 13. تشغيل محرك المهام بعد الاعتماد

### `animal.opening_task_evaluation_after_activation`

بعد اعتماد القطيع الافتتاحي يجب تشغيل تقييم المهام اعتمادًا على الوضع الحالي المسجل.

مثال مفاهيمي:

```text
Female = awaiting pregnancy check
+ sufficient timing data
→ Task Engine evaluates pregnancy-check requirement

Female = near kindling
+ sufficient timing/context data
→ Task Engine evaluates preparation tasks
```

هذا السؤال **لا يحدد توقيت المهام أو أولوياتها أو Thresholds**.

تلك القواعد مصدرها `Operational Settings`.

كما لا يسمح هذا القرار للنظام باختراع تاريخ سابق لإنشاء مهمة؛ يجب توفر البيانات اللازمة وفق القواعد المعتمدة.

---

## 14. الحدود مع الأقسام الأخرى

### بيانات وهوية الحيوان — 3.1

الهوية الأساسية والكود والجنس والسلالة ومعلومات الميلاد تبقى في قسم هوية الحيوان.

Opening Herd Setup يستخدمها ولا يعيد تعريفها.

### مصدر الحيوان — 3.2

مصدر الحيوان وبداية سجله شيء مستقل عن كونه جزءًا من القطيع الافتتاحي.

### النسب — 3.3

الأب والأم وشجرة العائلة لا يعاد اختراعها داخل التهيئة.

### تكوين القطيع الإنتاجي — 3.5

المجموعات والتخصيصات الدائمة تنتمي إلى تنظيم القطيع؛ Opening Setup يتحقق فقط من اكتمال المطلوب منها قبل التشغيل.

### Workflow

بعد اعتماد نقطة البداية، كل تغيير لاحق يجب أن يسجل كحدث تشغيلي طبيعي:

```text
Housing / Movement
Weight
Mating
Pregnancy Check
Birth
Lactation
Weaning
Health / Isolation
Fate / Exit
```

ولا تستمر شاشة Opening Setup كوسيلة لتعديل الواقع الحالي بعد بدء التشغيل.

### Settings

معايير الجاهزية، توقيت المهام، قواعد السعة والتسكين، وقواعد العمليات تأتي من Settings ولا تثبت داخل القطيع الافتتاحي.

---

## 15. ما الذي لا يجوز استنتاجه

لا تستنتج الإجابات الحالية:

- ضرورة إدخال تاريخ كامل لكل حيوان قبل التفعيل.
- إنشاء أحداث تاريخية سابقة وهمية لإكمال Timeline.
- أن غياب حدث قبل بدء النظام يعني أنه لم يحدث.
- أن كل حيوان يجب أن يمتلك جميع بيانات الـSnapshot قبل إدخاله.
- أن Context البداية يصبح Status دائمًا للحيوان.
- أن الـOpening Baseline قيمة يدوية قابلة للتعديل لاحقًا.
- توقيت أي Task بعد التفعيل دون الرجوع إلى Settings والبيانات الزمنية المتاحة.
- آلية فنية محددة لتخزين Snapshot أو Baseline.

---

## 16. فحص الاتساق

**الحالة:** لا يوجد تعارض داخلي مانع في الإجابات الحالية.

التسلسل متناسق:

```text
Allow incomplete known state
→ Capture current snapshot
→ Mark prior history incomplete
→ Do not reconstruct prior events
→ Validate housing / grouping / critical data
→ Draft / Review / Activate
→ Preserve opening baseline
→ Start task evaluation
→ Continue with normal Workflow
```

### نقطة تحتاج الانتباه عند التصميم

`current_snapshot_only` مع `opening_task_evaluation_after_activation = نعم` لا يمثلان تعارضًا، لكنهما يفرضان قاعدة واضحة:

> لا يمكن لمحرك المهام الاعتماد على تاريخ سابق غير معروف. إذا احتاجت قاعدة تشغيلية إلى تاريخ أو Anchor زمني غير متوفر، يجب اعتباره بيانات ناقصة لازمة قبل تنفيذ/جدولة القرار المعتمد عليه، وليس اختلاق تاريخ افتراضي.

---

## 17. المخرجات المطلوبة للـRequirements

1. توفير Workflow مستقل لتهيئة القطيع الافتتاحي للمزارع القائمة.
2. دعم Snapshot للوضع التشغيلي الحالي لكل حيوان.
3. السماح بالبيانات الناقصة مع تمييزها وإلزام استكمالها عند الحاجة التشغيلية.
4. دعم جميع Starting Contexts السبعة المعتمدة.
5. دعم نقاط البداية التناسلية الخمس المعتمدة للأنثى.
6. عدم إعادة بناء Timeline تاريخية قبل بدء النظام ضمن Opening Setup.
7. وضع علامة عامة بأن التاريخ السابق لبدء النظام غير مكتمل.
8. تنفيذ مراجعات التسكين والسعة والإتاحة والاستخدام والعزل والتنظيم والنقص الحرج قبل الاعتماد.
9. تطبيق دورة `Draft → Review → Activate`.
10. حفظ Opening Herd Baseline تاريخية مشتقة عند الاعتماد.
11. تشغيل تقييم المهام بعد الاعتماد من الوضع الحالي والبيانات المتاحة.
12. بدء جميع التغييرات اللاحقة عبر الـWorkflow الطبيعي بدل تعديل الـOpening Snapshot.
13. عدم اختراع بيانات أو تواريخ مفقودة لأي سبب.
