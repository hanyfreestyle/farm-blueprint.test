# 4.3 الوزن والقياسات التشغيلية — دليل تفسير الإجابات

> **الحالة:** مسودة أولى مبنية على الإجابات الحالية للمراجعة — توجد نقطة تحتاج حسمًا تخص اختيار التتبع الفردي فقط مع اعتماد سياق وزن أثناء الرضاعة للمواليد / البطن  
> **ملف الإجابات المقابل:** `questionnaire-export/answers/04-workflow/03-operational-measurements.md`  
> **Question Keys المغطاة:** 9 / 9 — منها 8 مطبقة حاليًا و1 غير مطبق بسبب الـDependency

---

## 1. الغرض من هذا الملف

هذا الملف يفسر **المعنى الوظيفي والتقني للإجابات المعتمدة فعليًا** في قسم `الوزن والقياسات التشغيلية` ضمن `الحركات ودورة التشغيل الفعلية`.

هو ليس مصدرًا بديلًا للإجابات، ولا يغير أي قرار موجود في ملف الإجابات.

```text
answers/04-workflow/03-operational-measurements.md
= ماذا قررنا؟

                    ↓ تفسير

guides/04-workflow/03-operational-measurements.md
= ماذا تعني هذه القرارات تقنيًا؟ وما حدودها؟
```

القاعدة المعمارية الأساسية:

```text
Actual Weight / Measurement
→ Workflow 4.3

Timing / Target / Threshold / Required Frequency
→ Settings

Growth Curves / Comparison / KPI / Alerts
→ Reports / Analytics
```

والوزن هنا **واقعة تاريخية مستقلة**، وليس قيمة ثابتة يتم استبدالها داخل ملف الحيوان.

---

## 2. الهدف الوظيفي من القسم

الغرض من هذا القسم هو إنشاء مصدر تاريخي موحد لكل وزن فعلي يتم تسجيله، مع الاحتفاظ بسياق القياس وتوقيته والمرجع التشغيلي المرتبط به.

الصورة المفاهيمية:

```text
Operational Event / Stage
        ↓
Actual Measurement
        ↓
Canonical Weight History
        ↓
Derived Current Weight / Growth Analysis / Reports
```

وبالتالي يجب الفصل بين:

```text
Weight Measurement Event
≠
Current Weight Field
≠
Target Weight
≠
Weight Rule
≠
Growth Report
```

---

## 3. ملخص القرارات المعتمدة

الصورة الحالية التي تحسمها الإجابات:

```text
Weight Tracking Subject
└── Individual Animal only

Weight Record
├── subject
├── weight_value
├── weight_unit
├── measured_at
├── measurement_context
├── age_at_measurement
├── related_workflow_reference
├── performed_by
└── notes

Weight Unit
└── One fixed unit system-wide

Measurement Contexts
├── intake
├── pregnancy
├── lactation
├── weaning
├── growth_periodic
├── sorting_evaluation
├── replacement_followup
├── fattening
└── other

Single Source of Truth
└── one canonical Weight Record linked to originating Workflow

Age at Measurement
└── derived from birth data when available; otherwise unknown

Pre-weaning Litter Weight
└── not currently applicable because preweaning_litter is not selected as a subject

Batch Weight Entry
├── supported
└── each animal gets its own independent Weight Record linked to the session
```

---

## 4. تفسير Question Keys والإجابات

### 4.1 `operational_measurement.subject_types`

القرار الحالي:

```text
individual_animal
```

ولم يتم اعتماد:

```text
preweaning_litter
```

المعنى أن الـCanonical Weight History الحالي يستهدف **الحيوان الذي لديه سجل فردي مستقل**.

إذن لا يجوز من هذا القسم إنشاء وزن للبطن ككيان مستقل ما دام `preweaning_litter` غير معتمد.

هذا متسق مع القاعدة العامة التي تجعل التتبع الفردي الكامل هو المصدر الأساسي لتحليل الأوزان بعد إنشاء Animal Record.

لكن توجد نقطة تداخل مع سياق `lactation` موضحة لاحقًا في قسم المراجعة المفتوحة.

---

### 4.2 `operational_measurement.record_fields`

تم اعتماد الحقول التالية:

```text
subject
weight_value
weight_unit
measured_at
measurement_context
age_at_measurement
related_workflow_reference
performed_by
notes
```

التفسير:

- `subject` → الكيان موضوع الوزن. وبحسب القرار الحالي هو Animal فردي فقط.
- `weight_value` → القيمة الفعلية المقاسة.
- `weight_unit` → وحدة الوزن المستخدمة وفق السياسة المعتمدة.
- `measured_at` → وقت القياس الفعلي، وليس وقت إدخال السجل بالضرورة.
- `measurement_context` → لماذا / في أي مرحلة تم الوزن.
- `age_at_measurement` → قيمة مشتقة عند إمكان الحساب.
- `related_workflow_reference` → رابط إلى الحدث أو الدورة التي نتج عنها الوزن عند انطباقه.
- `performed_by` → من نفذ / سجل القياس وفق نموذج الصلاحيات والتدقيق.
- `notes` → تفاصيل إضافية خاصة بالواقعة.

لا يجوز تفسير وجود `subject` بصياغة «الحيوان أو البطن» كاعتماد فعلي للبطن، لأن `subject_types` هو السؤال الأعلى الذي حسم النطاق الحالي واختار الحيوان الفردي فقط.

---

### 4.3 `operational_measurement.weight_unit_policy`

القرار:

```text
single_fixed_unit
```

أي أن النظام يستخدم **وحدة وزن واحدة ثابتة على مستوى النظام كله**.

هذا القرار يمنع تعدد الوحدات في الإدخال ويجعل المقارنة المباشرة بين السجلات ممكنة بدون Normalization متعدد الوحدات.

لكن الإجابة الحالية **لا تحدد ما هي الوحدة نفسها**.

إذن لا يجوز استنتاج أنها:

```text
kg
أو
g
أو أي وحدة أخرى
```

إلى أن يوجد قرار صريح يحدد القيمة الفعلية للوحدة أو مكان ضبطها.

هذه نقطة غير محسومة، لكنها ليست تعارضًا داخليًا مع نموذج السؤال.

---

### 4.4 `operational_measurement.context_types`

تم اعتماد السياقات:

```text
intake
pregnancy
lactation
weaning
growth_periodic
sorting_evaluation
replacement_followup
fattening
other
```

الغرض من `measurement_context` هو وصف **سبب / مرحلة القياس** بدون إنشاء جدول وزن منفصل لكل Workflow.

مثال:

```text
Weight Record
├── value = actual measured value
├── context = intake
└── related workflow = Animal Intake Event
```

أو:

```text
Weight Record
├── context = pregnancy
└── related workflow = reproductive cycle / pregnancy context
```

ولا يعني وجود السياق أن الوزن إلزامي دائمًا في تلك المرحلة.

مثلًا:

```text
pregnancy context supported
≠
weight required during every pregnancy
```

إلزام القياس وتوقيته ودوريته مكانه Settings.

#### ملاحظة حول `other`

تم اعتماد سياق `other`، لكن هذا القسم لا يحسم:

- هل الوصف النصي الإضافي إلزامي.
- هل يمكن إنشاء Context Master Data جديدة.
- هل `other` يتحول إلى قيمة مرجعية لاحقًا.

لذلك لا يجوز فرض تفاصيل إضافية من تلقاء النظام دون قرار آخر.

---

### 4.5 `operational_measurement.single_source_linking`

الإجابة: **نعم**.

هذه قاعدة Integrity أساسية:

```text
One actual measurement
→ One canonical Weight Record
```

إذا تم الوزن من داخل Workflow آخر، مثل:

```text
Animal Intake
Pregnancy
Weaning
Sorting
Replacement
Fattening
```

فلا تُحفظ نسخة من الوزن داخل الـWorkflow ونسخة أخرى منفصلة في سجل الأوزان.

الصحيح:

```text
Original Workflow
        ↓ reference
Canonical Weight Record
```

أو العكس حسب التصميم التنفيذي، بشرط بقاء **مصدر تاريخي واحد للقيمة نفسها**.

هذا يمنع اختلاف قيمتين لنفس القياس ويضمن أن:

```text
Current Weight
Growth Analysis
Reports
```

كلها تعتمد على نفس السجل التاريخي.

---

### 4.6 `operational_measurement.age_handling`

القرار:

```text
derive_from_birth_or_unknown
```

أي أن العمر وقت القياس:

1. يُحسب تلقائيًا من بيانات الميلاد عندما تكون متاحة.
2. يظل غير معروف عندما لا تتوفر بيانات ميلاد تسمح بالحساب.

ولا يسمح هذا القرار بـ:

```text
manual_age_per_measurement
estimated_age_when_birth_missing
```

من تلقاء نفسه.

وهذا متسق مع القرارات السابقة في هوية الحيوان التي تسمح بوجود معلومات ميلاد غير مكتملة دون اختراع بيانات.

`age_at_measurement` يجب فهمه كـSnapshot مشتق مرتبط بتاريخ القياس، وليس كـCurrent Age field ثابت.

---

### 4.7 `operational_measurement.preweaning_weight_model`

هذا السؤال **غير مطبق حاليًا** بسبب الـDependency، لأن:

```text
operational_measurement.subject_types
```

لم يتضمن:

```text
preweaning_litter
```

إذن الخيارات التالية لم تعتمد حاليًا:

```text
no_preweaning_weight
litter_total_weight
litter_total_with_derived_average
individual_when_identifiable
litter_and_individual_when_identifiable
```

وهذا يعني أنه لا يوجد حاليًا Requirement نهائي يحدد **كيف** يتم تمثيل وزن المواليد قبل اكتمال التتبع الفردي.

لكن المرجع الوظيفي نفسه يعتبر هذه النقطة قرارًا مهمًا ويطرح احتمالات مثل وزن البطن ككل أو المتوسط أو وزن أفراد عند إمكان تعريفهم.

لذلك لا يجوز حسم نموذج وزن ما قبل الفطام من هذا القسم بصورته الحالية.

---

### 4.8 `operational_measurement.batch_entry_support`

الإجابة: **نعم**.

النظام يجب أن يدعم **جلسة إدخال وزن واحدة لعدة حيوانات** لتسهيل العمل الميداني، خصوصًا في النمو أو التسمين أو أي سيناريو مشابه.

لكن هذه الجلسة ليست قياسًا جماعيًا واحدًا.

```text
Batch Weight Entry Session
≠
One shared weight value
```

بل هي Container / Session لتسريع الإدخال.

ولا يحسم السؤال:

- هل الحيوانات تُختار من قفص أو مجموعة أو قائمة.
- هل الجلسة لها كود مستقل.
- هل يمكن استيرادها من جهاز وزن.
- هل يمكن تعديل الجلسة بعد الإغلاق.

هذه تفاصيل تنفيذية غير معتمدة هنا.

---

### 4.9 `operational_measurement.batch_individual_records`

الإجابة: **نعم**.

عند استخدام جلسة وزن جماعية:

```text
Measurement Session
├── Animal A → independent Weight Record
├── Animal B → independent Weight Record
└── Animal C → independent Weight Record
```

كل حيوان يحتفظ بسجل وزن مستقل داخل Timeline الخاصة به، مع إمكانية ربط السجلات بالجلسة المشتركة.

وهذا يحقق الجمع بين:

```text
Fast operational entry
+
Correct individual history
```

ولا يجوز تخزين متوسط المجموعة أو رقم واحد باعتباره وزنًا فرديًا لكل الحيوانات.

---

## 5. العلاقة مع الأقسام الأخرى

### مع `3.1 بيانات وهوية الحيوان`

الوزن ليس جزءًا ثابتًا من هوية الحيوان.

```text
Animal Identity
≠
Current Weight
```

الحيوان يحتفظ بنفس هويته بينما يتغير وزنه بمرور الوقت.

### مع `3.4 القطيع الافتتاحي`

إذا تم تسجيل وزن حالي ضمن Opening Herd Setup، فيجب الحفاظ على مبدأ المصدر الواحد وعدم إنشاء Weight Source موازٍ ينافس سجل الأوزان التشغيلي.

### مع `4.1 استقبال الحيوان من الخارج`

تم اعتماد وزن الدخول كجزء إلزامي من الاستقبال.

التكامل الصحيح:

```text
Animal Intake
→ creates / links one Weight Record
→ context = intake
```

وليس حفظ الوزن مرتين.

### مع `4.5 الحمل`

وزن الأنثى أثناء الحمل — إذا تم فعليًا — يستخدم نفس Weight History، مع:

```text
context = pregnancy
related workflow reference = relevant reproductive / pregnancy context
```

أما متى يكون الوزن مطلوبًا أو ما الحدود المقبولة فلا يحسم هنا.

### مع `4.7 الرضاعة`

هذا القسم يدعم Context باسم `lactation`، لكن نموذج Subject الحالي لا يدعم `preweaning_litter`.

لذلك يجب عدم افتراض نموذج وزن للمواليد قبل الفطام حتى حسم النقطة المفتوحة.

### مع `4.8 الفطام`

عندما يصبح الحيوان فردًا مستقلًا، يمكن أن يكون وزن الفطام جزءًا من نفس Weight History:

```text
context = weaning
```

بدون إنشاء مصدر وزن منفصل.

### مع `4.9 النمو والفرز`

الأوزان الدورية والمرتبطة بالفرز تظل Actual Measurements داخل هذا القسم، بينما:

```text
Growth Rate / Trend / Comparison
→ Derived / Reports
```

### مع `4.11 الإحلال`

وزن مرشح الإحلال يدخل تحت:

```text
replacement_followup
```

ولا يعني تلقائيًا أنه اجتاز شروط الاعتماد؛ معايير الإحلال مكانها Settings وقرار الاعتماد الفعلي في Workflow الإحلال.

### مع `4.12 التسمين`

أوزان التسمين الفعلية تسجل هنا، ويمكن استخدام Batch Measurement Session لتسهيل الإدخال، بينما أهداف الوزن وجاهزية البيع تظل قواعد منفصلة.

### مع Reports

Reports يجب أن تعتمد على Weight History لبناء:

- Current Weight.
- Weight Trend.
- Growth Curve.
- Average Daily Gain عند وجود قاعدة معتمدة.
- المقارنات بين الحيوانات أو السلالات أو المراحل.

لكن هذا القسم نفسه لا يعرّف معادلات التحليل.

---

## 6. ما لا يجوز استنتاجه من هذا القسم

لا يجوز استنتاج أي من التالي بدون قرار صريح آخر:

```text
Specific fixed weight unit value (g / kg)
Mandatory weight in every selected context
Weight target values
Minimum / maximum acceptable weights
Growth thresholds
Alert thresholds
Automatic rejection based on weight
Automatic readiness based on weight
Specific weighing schedule
Pre-weaning litter weight model
Sample-size rules for pre-weaning weighing
Medical interpretation of weight
Automatic breed comparison
Automatic ADG formula requirements
Device / scale integration
CSV / hardware import
Mandatory description for Other context
```

---

## 7. فحص الاتساق الداخلي

### نقاط متسقة بوضوح

1. **الوزن واقعة تاريخية وليس حقلًا ثابتًا**.
2. `single_source_linking = true` يمنع ازدواج القيمة بين Workflows.
3. العمر وقت القياس مشتق بدل إدخاله يدويًا.
4. Batch Entry لا يفقد التتبع الفردي لأن لكل حيوان Weight Record مستقلًا.
5. توقيت القياس وأهدافه وحدوده لم تُخلط مع Actual Measurement.
6. دعم Contexts متعددة لا ينشئ Weight Tables متنافسة.

---

## 8. نقطة تحتاج حسمًا — Pre-weaning / Lactation Weight

يوجد تداخل بين قرارين حاليين:

### القرار الأول

`operational_measurement.subject_types` اعتمد فقط:

```text
individual_animal
```

ولم يعتمد:

```text
preweaning_litter
```

وبالتالي أصبح:

```text
operational_measurement.preweaning_weight_model
```

غير مطبق حاليًا.

### القرار الثاني

`operational_measurement.context_types` اعتمد:

```text
lactation
```

ووصفه في السؤال هو:

> متابعة البطن / المواليد أثناء الرضاعة

### سبب الحاجة للحسم

إذا كان المقصود من `lactation` هو **وزن المواليد قبل الفطام**، فهناك تعارض مع عدم دعم `preweaning_litter` كـSubject.

أما إذا كان المقصود أن `lactation` يستخدم فقط عندما يوجد بالفعل Animal Record فردي صالح للوزن، فيجب توضيح هذا الحد حتى لا يُفهم أن وزن البطن قبل الفطام مدعوم.

### ما لا يتم فعله الآن

لا يتم من هذا الـGuide اختيار أي من الحلول التالية نيابة عن المستخدم:

```text
Enable preweaning_litter
Remove lactation context
Restrict lactation context to individually identified animals only
Introduce litter weight tracking
```

هذه تحتاج قرارًا صريحًا لاحقًا.

---

## 9. تفاصيل غير محسومة وليست تعارضًا مانعًا

### 9.1 ما هي وحدة الوزن الثابتة؟

تم حسم أن الوحدة واحدة على مستوى النظام، لكن لم يتم تحديد قيمتها الفعلية.

### 9.2 Other Context

تم دعم `other` بدون تحديد قواعد وصفه أو إدارته.

### 9.3 Batch Measurement Session

تم اعتماد وجودها، لكن لم تحدد بيانات Session نفسها أو آلية اختيار الحيوانات أو إغلاق الجلسة.

هذه تفاصيل يمكن حسمها لاحقًا دون تغيير جوهر القرارات الحالية.

---

## 10. Requirements الناتجة من الإجابات الحالية

يمكن استخراج المتطلبات التالية بصورة آمنة:

### RQ-WF-OM-001
يجب أن يحتفظ النظام بسجل وزن تاريخي مستقل لكل Animal Record قابل للتتبع الفردي.

### RQ-WF-OM-002
يجب أن يحتوي سجل الوزن على Subject والقيمة والوحدة ووقت القياس والسياق والعمر المشتق عند توفر بيانات الميلاد ومرجع الـWorkflow والمنفذ والملاحظات.

### RQ-WF-OM-003
يجب استخدام وحدة وزن واحدة ثابتة على مستوى النظام، مع بقاء نوع الوحدة نفسها غير محسوم في الإجابات الحالية.

### RQ-WF-OM-004
يجب دعم Contexts التشغيلية المعتمدة للوزن بدون إنشاء مصدر وزن مستقل لكل Context.

### RQ-WF-OM-005
يجب أن يكون لكل قياس فعلي مصدر تاريخي واحد، وأن ترتبط Workflows الأخرى بسجل الوزن بدل حفظ نسخ متنافسة من القيمة.

### RQ-WF-OM-006
يجب اشتقاق العمر وقت القياس من بيانات الميلاد عندما تكون متاحة، واعتباره غير معروف عندما يتعذر الاشتقاق وفق القرارات الحالية.

### RQ-WF-OM-007
يجب دعم جلسات إدخال جماعي للأوزان لعدة حيوانات.

### RQ-WF-OM-008
يجب إنشاء سجل وزن مستقل لكل حيوان داخل جلسة الوزن الجماعية وربطه بالجلسة المشتركة.

### RQ-WF-OM-009
لا يوجد Requirement معتمد حاليًا لنموذج وزن البطن / المواليد قبل الفطام حتى يتم حسم التداخل بين Subject Model وسياق Lactation.

---

## 11. الخلاصة

النموذج الحالي يحقق قاعدة قوية:

```text
One Actual Weight
→ One Historical Weight Record
→ Linked to its Workflow Context
→ Used later for Derived Current Weight and Analytics
```

مع دعم الإدخال الجماعي دون التضحية بالتاريخ الفردي.

النقطة الوحيدة التي تحتاج حسمًا قبل اعتبار نطاق الوزن مكتملًا بالكامل هي:

```text
Individual Animal only
VS
Lactation context for litter / offspring
```

أما بقية قرارات القسم فهي متماسكة وقابلة للتحويل إلى Requirements دون افتراضات إضافية.
