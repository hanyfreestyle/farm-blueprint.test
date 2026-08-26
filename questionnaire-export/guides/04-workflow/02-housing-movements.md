# 4.2 التسكين والنقل والإخلاء وإدارة الإشغال — دليل تفسير الإجابات

> **الحالة:** مسودة أولى مبنية على الإجابات الحالية للمراجعة — توجد نقطة تحتاج حسمًا تخص رفض النقل الجماعي العام مع اعتماد نقل جماعي متخصص لإخلاء هيكل مشغول  
> **ملف الإجابات المقابل:** `questionnaire-export/answers/04-workflow/02-housing-movements.md`  
> **Question Keys المغطاة:** 12 / 12 — منها 10 مطبقة حاليًا و2 غير مطبقين بسبب الـDependency

---

## 1. الغرض من هذا الملف

هذا الملف يفسر **المعنى الوظيفي والتقني للإجابات المعتمدة فعليًا** في قسم `التسكين والنقل والإخلاء وإدارة الإشغال` ضمن `الحركات ودورة التشغيل الفعلية`.

هو ليس مصدرًا بديلًا للإجابات، ولا يغير أي قرار موجود في ملف الإجابات.

```text
answers/04-workflow/02-housing-movements.md
= ماذا قررنا؟

                    ↓ تفسير

guides/04-workflow/02-housing-movements.md
= ماذا تعني هذه القرارات تقنيًا؟ وما حدودها؟
```

القاعدة المعمارية الأساسية لهذا القسم:

```text
Farm / Barn / Battery / Cage
= تعريف مواقع الإيواء → Farm Structure

Housing / Transfer / Vacating
= ما حدث فعليًا للحيوان → Workflow 4.2

Housing Eligibility / Capacity Rules
= ما الذي يسمح بالحركة أو يمنعها → Settings

Site Maintenance / Sanitation
= تشغيل وتجهيز الموقع → Workflow 4.16

Current Location / Occupancy
= نتائج مشتقة من سجل الحركات
```

---

## 2. الهدف الوظيفي من القسم

الغرض من هذا القسم هو جعل موقع الحيوان وإشغال الأقفاص **نتيجة لتاريخ حركات موثق**، وليس قيمًا حالية يتم تعديلها يدويًا بصورة منفصلة.

الصورة المفاهيمية:

```text
Animal
  ↓
Housing Movement History
  ↓
Current Active Occupancy
  ↓
Current Cage
  ↓
Battery → Barn → Farm derived from cage hierarchy
```

وبالتالي:

```text
Current Animal Location
≠ manually maintained Animal field

Current Cage Occupancy
≠ manually entered count
```

كلاهما ينتج من الحركات النشطة والتاريخ المسجل.

---

## 3. ملخص القرارات المعتمدة

```text
Housing Movement Types
├── initial_housing
├── cage_transfer
└── explicit_vacate

Movement Record
├── animal
├── movement_type
├── source_cage
├── destination_cage
├── occurred_at
├── recorded_at
├── reason
├── performed_by
└── notes

Location Reference
└── user selects Cage only
    └── Battery / Barn / Farm derived automatically

Transfer Model
└── linked_vacate_and_housing_events

Occupancy Integrity
└── max one active occupancy per animal

General Batch Transfer
└── not supported

Reason Required For
├── initial housing
├── transfer
└── explicit vacate

Transfer Reason
└── linked to TransferReason Master Data

Time Tracking
└── occurred_at + recorded_at

Occupied Structure Relocation
└── supported as a group relocation operation
```

---

## 4. تفسير Question Keys والإجابات المعتمدة

### 4.1 `housing_movement.event_types`

تم اعتماد الأحداث:

```text
initial_housing
cage_transfer
explicit_vacate
```

#### `initial_housing`

يمثل أول إثبات فعلي لتسكين الحيوان في Cage داخل النظام.

لا يعني هذا أن الحيوان أصبح جاهزًا إنتاجيًا؛ التسكين يثبت الموقع فقط.

#### `cage_transfer`

يمثل انتقال الحيوان من Cage قائم إلى Cage آخر.

#### `explicit_vacate`

يمثل إنهاء الإشغال الحالي دون فتح إشغال جديد في نفس الواقعة.

إذن النظام يدعم حالة يكون فيها الحيوان:

```text
كان له Current Cage
→ تم إخلاؤه
→ لا يوجد Destination Cage ضمن نفس العملية
```

لكن هذا السؤال لا يحدد لماذا بقي الحيوان بلا تسكين أو إلى متى؛ هذه التفاصيل تأتي من سبب الحركة والمسار التشغيلي المرتبط بها.

كما أن الخروج من المزرعة لا يعاد تعريفه هنا؛ له Workflow مستقل.

---

### 4.2 `housing_movement.record_fields`

كل حركة يجب أن تستطيع الاحتفاظ بـ:

```text
animal
movement_type
source_cage
destination_cage
occurred_at
recorded_at
reason
performed_by
notes
```

طبيعة الحقول تختلف حسب نوع الحركة:

```text
Initial Housing
source_cage      → غير مطلوب بالضرورة
destination_cage → الموقع الجديد

Transfer
source_cage      → الموقع السابق
destination_cage → الموقع الجديد

Explicit Vacate
source_cage      → الموقع الذي تم إخلاؤه
destination_cage → غير موجود في نفس الحركة
```

`occurred_at` يمثل وقت الواقعة الفعلي، بينما `recorded_at` يمثل وقت إدخالها للنظام؛ الفرق بينهما مهم للتدقيق التاريخي.

---

### 4.3 `housing_movement.location_reference_model`

الإجابة: **نعم**.

المستخدم يختار `Cage / Cell` كموقع فعلي فقط.

ومن علاقة القفص الهيكلية يستنتج النظام:

```text
Cage
→ Battery
→ Barn
→ Farm
```

إذن لا ينبغي أن يطلب سجل الحركة من المستخدم اختيار القفص والبطارية والعنبر والمزرعة بصورة مستقلة لنفس الحركة، لأن ذلك يخلق مصادر حقيقة متنافسة.

هذا القرار متوافق مع الهيكل المعتمد:

```text
Farm → Barn → Battery → Cage
```

---

### 4.4 `housing_movement.transfer_atomicity`

القرار:

```text
linked_vacate_and_housing_events
```

أي أن النقل بين قفصين يمثل من الناحية التاريخية:

```text
Vacate source cage
+
Housing into destination cage
```

لكن الحدثين يجب أن يكونا **مرتبطين كعملية نقل واحدة**.

المطلوب وظيفيًا:

- الاحتفاظ بحقيقة الإخلاء من المصدر.
- الاحتفاظ بحقيقة التسكين في الوجهة.
- منع ظهور النقل كحدثين غير مترابطين.
- منع أن يبدو الحيوان في قفصين في نفس اللحظة.

لا تحسم الإجابة شكل المفتاح التقني الذي يربط الحدثين، ولا بنية Transaction في قاعدة البيانات، لكن النتيجة المنطقية يجب أن تكون متسقة كوحدة نقل واحدة.

---

### 4.5 `housing_movement.single_active_occupancy`

الإجابة: **نعم**.

قاعدة Integrity أساسية:

```text
One Animal
→ maximum one active housing occupancy at any moment
```

وبالتالي:

- لا يسمح بوجود الحيوان في Cage A وCage B في نفس الوقت.
- الموقع الحالي ينتج من Active Occupancy الوحيد.
- كل الإشغالات السابقة تبقى محفوظة كتاريخ.

هذا لا يعني أن Cage نفسه يسع حيوانًا واحدًا فقط؛ سعة القفص مسألة مستقلة، وقد يحتوي القفص أكثر من حيوان حسب نوعه وقواعد السعة.

---

### 4.6 `housing_movement.batch_transfer_support`

الإجابة: **لا**.

إذن النظام، وفق القرار العام الحالي، **لا يحتاج إلى عملية Generic Batch Transfer** تسمح للمستخدم باختيار عدة حيوانات وتنفيذ نقلهم ضمن أمر جماعي عام.

ونتيجة ذلك أصبح السؤالان التاليان غير مطبقين حاليًا:

```text
housing_movement.batch_transfer_scopes
housing_movement.batch_individual_history
```

لا يجوز استنتاج دعم:

- نقل كل شاغلي Cage في أمر جماعي عام.
- اختيار مجموعة من حيوانات Cage وتنفيذ نقل جماعي عام.
- كيان HousingBatchMovement عام.

لكن يوجد قرار مستقل لاحقًا بخصوص إخلاء هيكل مشغول، وهو ما يخلق نقطة تحتاج حسمًا موضحة أدناه.

---

### 4.7 `housing_movement.batch_transfer_scopes`

**غير مطبق حاليًا** بسبب:

```text
batch_transfer_support = false
```

لذلك لم يعتمد النظام حاليًا أيًا من:

```text
all_source_occupants
selected_source_animals
```

ولا يجب اختيار Scope افتراضي من تلقاء نفسه.

---

### 4.8 `housing_movement.batch_individual_history`

**غير مطبق حاليًا** بسبب رفض النقل الجماعي العام.

لذلك هذا السؤال لا يثبت Requirement عام لإنشاء سجل فردي لكل حيوان داخل Batch.

مع ذلك، تبقى القاعدة الأعلى في المشروع أن Timeline الحيوان يجب أن تكون قابلة للتتبع، وأي عملية نقل متخصصة تُعتمد لاحقًا يجب ألا تجعل الموقع الحالي للحيوان غير قابل للاشتقاق من تاريخ موثق.

لكن شكل التسجيل داخل عملية الإخلاء الجماعي المتخصصة لا يجوز اختراعه من هذا السؤال المخفي.

---

### 4.9 `housing_movement.reason_requirement_scope`

تم اعتماد أن السبب مطلوب في كل الأنواع الثلاثة:

```text
initial_housing
transfer
explicit_vacate
```

إذن سجل الحركة لا يكتفي بإثبات الموقع والتوقيت، بل يجب أن يوضح السبب التشغيلي للحركة في كل حالة معتمدة.

لكن يوجد فرق بين:

```text
Reason is required
```

وبين:

```text
Which lookup / source provides that reason?
```

السؤال التالي حسم مصدر السبب للنقل بين المواقع فقط.

---

### 4.10 `housing_movement.transfer_reason_reference`

الإجابة: **نعم**.

سبب **النقل بين مواقع الإيواء** يجب أن يرتبط بقائمة:

```text
TransferReason Master Data
```

بدل إدخاله كنص حر فقط.

يمكن استخدام `notes` لشرح تفاصيل الواقعة، لكن سبب النقل الأساسي يظل قيمة مرجعية قابلة للتحليل.

هذا يحافظ على الفصل:

```text
TransferReason
= قيمة مرجعية

Housing Transfer Event
= الواقعة الفعلية التي تستخدم السبب
```

### نقطة غير محسومة

القرار الحالي يفرض أيضًا سببًا على:

```text
initial_housing
explicit_vacate
```

لكن السؤال `transfer_reason_reference` يحسم صراحة مرجع **سبب النقل** فقط.

لذلك لا يجوز استنتاج من الإجابات الحالية وحدها هل:

- `initial_housing` يستخدم أيضًا `TransferReason`،
- `explicit_vacate` يستخدم `TransferReason`،
- أم لكل منهما مصدر سبب مختلف.

هذه ليست مشكلة تمنع فهم المسار، لكنها تفصيلة Requirement تحتاج حسمًا قبل تثبيت نموذج الأسباب بالكامل.

---

### 4.11 `housing_movement.time_tracking_model`

القرار:

```text
event_datetime_and_recorded_at
```

أي يجب الاحتفاظ بالقيمتين:

```text
occurred_at
= متى حدثت الحركة فعليًا

recorded_at
= متى سجل المستخدم الحركة في النظام
```

هذا يسمح بالتفرقة بين حدث وقع ميدانيًا في وقت معين وتم إدخاله لاحقًا.

ولا يجوز استبدال `occurred_at` تلقائيًا دائمًا بـ`created_at` إذا كان وقت التنفيذ الفعلي مختلفًا.

---

### 4.12 `housing_movement.occupied_structure_relocation_support`

الإجابة: **نعم**.

إذن النظام يجب أن يدعم **عملية مرتبطة بإخلاء موقع إيواء مشغول** عندما يكون سبب الإخلاء إيقافًا أو صيانةً على مستوى مثل:

```text
Cage
Battery
Barn
```

الغرض من هذا القرار هو تنفيذ النقل الفعلي للحيوانات قبل أو ضمن معالجة خروج الهيكل من التشغيل.

لكن:

```text
قرار صيانة / إيقاف الموقع نفسه
→ Workflow 4.16 / Settings

حركة الحيوانات الناتجة عنه
→ Workflow 4.2
```

ولا يجوز أن يجعل إجراء الصيانة نفسه يعدل إشغال الحيوانات بصمت بدون Housing Movement تاريخية.

---

## 5. نقطة تحتاج حسمًا — النقل الجماعي العام مقابل إخلاء هيكل مشغول

يوجد تداخل مباشر بين مفتاحين:

```text
housing_movement.batch_transfer_support = false
```

وفي المقابل:

```text
housing_movement.occupied_structure_relocation_support = true
```

والسؤال الثاني يصف نفسه صراحة بأنه:

> عملية نقل جماعي مرتبطة بإخلاء موقع إيواء مشغول.

### تفسير ممكن يحافظ على القرارين

يمكن أن يكون المقصود:

```text
Generic Batch Transfer
= غير مدعوم

Specialized Occupied-Structure Relocation
= مدعوم فقط كعملية خاصة عند الصيانة / الإيقاف
```

في هذا التفسير لا يوجد Batch Tool عام للمستخدم، لكن توجد عملية متخصصة لإخلاء هيكل كامل عند الحاجة التشغيلية.

### لكن لا يجوز اعتماد هذا التفسير نهائيًا دون قرار صريح

لأن الإجابات الحالية لا تقول بوضوح إن `occupied_structure_relocation_support` هو **استثناء متعمد** من رفض النقل الجماعي العام.

إذا كان رفض `batch_transfer_support` مقصوده منع **أي** عملية جماعية، فالسؤالان متعارضان ويجب تعديل أحد القرارين.

إذن قبل Final Requirements يلزم حسم واحد من الآتي:

```text
A) لا يوجد Batch عام، لكن يوجد Specialized Relocation لإخلاء الهيكل.

أو

B) لا توجد أي عمليات نقل جماعي، بما فيها إخلاء الهيكل.

أو

C) نعيد اعتماد Batch Transfer العام وتحديد Scopes وتاريخ كل حيوان.
```

لا يختار هذا الـGuide أيًا منها من تلقاء نفسه.

---

## 6. العلاقة مع الأقسام الأخرى

### مع Farm Structure

```text
Farm → Barn → Battery → Cage
```

هذا القسم لا يغير تعريف الهيكل.

هو يستخدم Cage كمرجع فعلي، ثم يستنتج المستويات الأعلى تلقائيًا.

### مع `Cage / Cell`

بيانات القفص وحالته واستخدامه وسعته تعرف في Farm Structure.

أما:

```text
من دخل القفص؟
متى خرج؟
إلى أين انتقل؟
```

فتأتي من Housing Movement History.

### مع Master Data — أسباب النقل

قائمة `TransferReason` تعرف في Master Data، ولا تعاد كتابتها داخل Workflow.

### مع Settings

Settings تحسم قواعد مثل:

- هل Cage صالح لاستقبال الحيوان؟
- هل يوجد Capacity متاحة؟
- هل الاستخدام متوافق؟
- هل الموقع أو أحد آبائه متوقف أو تحت الصيانة؟
- هل يلزم تطهير قبل إعادة الاستخدام؟

هذا القسم يسجل **الحركة التي حدثت**، وليس القاعدة التي تحكم السماح بها.

### مع Workflow 4.16

```text
Site Maintenance / Sanitation / Operational Site Action
→ 4.16

Animal Relocation caused by it
→ 4.2
```

### مع Workflow الخروج من المزرعة

`explicit_vacate` لا يعني تلقائيًا خروج الحيوان من المزرعة.

إذا خرج الحيوان فعليًا من المزرعة، يجب أن تسجل واقعة الخروج في المسار المتخصص مع الحفاظ على العلاقة المناسبة بإخلاء الموقع.

---

## 7. ما لا يجوز استنتاجه من هذا القسم

لا يجوز استنتاج أي من التالي دون قرار آخر صريح:

```text
Current location stored manually on Animal
Current occupancy count stored manually on Cage
Farm/Barn/Battery selected independently in every movement
Generic batch transfer support
Batch transfer scopes
Batch individual history model
Automatic maintenance when cage is vacated
Automatic sanitation after every vacate
Hard delete old occupancy records
Multiple simultaneous active cages for one animal
Transfer reason as free text only
Initial-housing reason source = TransferReason
Vacate reason source = TransferReason
Housing approval = production readiness
Vacating = farm exit
```

كما لا يجوز افتراض أن كل حركة صالحة لمجرد اختيار Cage؛ قواعد الأهلية والسعة والاستخدام والحالة تظل في Settings.

---

## 8. فحص الاتساق الداخلي

### متسق بوضوح

```text
Cage is canonical location reference
+ Parent structure derived automatically
+ one active occupancy per animal
+ full movement history retained
+ actual time separated from recording time
+ TransferReason reused from Master Data
```

كما أن اختيار:

```text
linked_vacate_and_housing_events
```

متوافق مع الاحتفاظ بتاريخ المصدر والوجهة طالما يتم ربطهما كعملية نقل واحدة ولا ينشأ تداخل إشغال.

### يحتاج حسمًا

1. **رفض Batch Transfer العام مقابل دعم Group Relocation لإخلاء هيكل مشغول.**
2. **مصدر السبب للتسكين الأول والإخلاء الصريح** بعد أن أصبح السبب إلزاميًا لهما، بينما Reference المحسوم حاليًا يخص TransferReason للنقل فقط.

النقطة الأولى تؤثر مباشرة في نموذج Workflow الجماعي ويجب حسمها قبل Final Requirements.

النقطة الثانية أقل خطورة، لكنها يجب أن تحسم قبل تصميم Lookup / Validation النهائي للأسباب.

---

## 9. Requirements الناتجة من الإجابات الحالية

### RQ-WF-HM-001
يجب أن يدعم النظام أحداث التسكين الأول والنقل بين الأقفاص والإخلاء الصريح.

### RQ-WF-HM-002
يجب أن يحتفظ كل سجل حركة بالحيوان ونوع الحركة والمصدر والوجهة عند انطباقهما ووقت التنفيذ ووقت التسجيل والسبب والمنفذ والملاحظات.

### RQ-WF-HM-003
يجب استخدام Cage / Cell كمرجع الموقع الفعلي للحركة، مع اشتقاق Battery وBarn وFarm من الهيكل.

### RQ-WF-HM-004
يجب تمثيل النقل بين قفصين كحدث إخلاء وحدث تسكين مرتبطين كعملية نقل واحدة.

### RQ-WF-HM-005
يجب منع وجود أكثر من Active Occupancy لنفس الحيوان في نفس اللحظة.

### RQ-WF-HM-006
لا يعتمد حاليًا Generic Batch Transfer، ويظل نطاق الإخلاء الجماعي المتخصص بحاجة إلى الحسم الموضح أعلاه.

### RQ-WF-HM-007
يجب تسجيل سبب صريح للتسكين الأول والنقل والإخلاء الصريح.

### RQ-WF-HM-008
يجب أن يرتبط سبب النقل بين مواقع الإيواء بقائمة `TransferReason` المعتمدة في Master Data.

### RQ-WF-HM-009
يجب الاحتفاظ بوقت حدوث الحركة فعليًا ووقت تسجيلها في النظام بصورة مستقلة.

### RQ-WF-HM-010
يجب دعم عملية مرتبطة بإخلاء موقع إيواء مشغول بسبب إيقاف أو صيانة Cage/Battery/Barn وفق الإجابة الحالية، مع بقاء طريقة التوفيق بينها وبين رفض Batch Transfer العام مفتوحة للحسم.

---

## 10. الخلاصة

القسم يثبت نموذجًا واضحًا قائمًا على:

```text
Movement History
→ Active Occupancy
→ Current Location
→ Derived Cage Occupancy
```

بدل تعديل الموقع والإشغال كقيم حالية منفصلة.

القرارات الأساسية متماسكة، وأهم نقطة يجب عدم فقدها في المرحلة النهائية هي حسم العلاقة بين:

```text
No Generic Batch Transfer
```

و:

```text
Specialized Group Relocation for Occupied Structure
```

إلى أن يصدر قرار صريح، يجب إبقاء هذه النقطة مفتوحة وعدم تحويل أي من التفسيرين إلى Requirement نهائي من تلقاء نفسه.
