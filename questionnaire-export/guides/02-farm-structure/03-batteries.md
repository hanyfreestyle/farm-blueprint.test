# 2.3 بيانات البطارية — دليل تفسير الإجابات

> **الحالة:** مسودة أولى مبنية على الإجابات الحالية للمراجعة  
> **ملف الإجابات المقابل:** `questionnaire-export/answers/02-farm-structure/03-batteries.md`  
> **Question Keys المغطاة:** 45 / 45

---

## 1. الغرض من هذا الملف

هذا الملف يفسر **المعنى الوظيفي والتقني للإجابات المعتمدة فعليًا** في قسم `بيانات البطارية` ضمن `هيكل المزرعة`.

هو ليس مصدرًا بديلًا للإجابات، ولا يغير أي قرار موجود في ملف الإجابات.

```text
answers/02-farm-structure/03-batteries.md
= ماذا قررنا؟

                    ↓ تفسير

guides/02-farm-structure/03-batteries.md
= ماذا تعني هذه القرارات تقنيًا؟ وما حدودها؟
```

تم بناء التفسير وفق منهج `docs/questionnaire-guide/` مع مراجعة التصور الوظيفي الحالي، مع اعتبار **الإجابات الحالية المصدرة هي مصدر القرار النهائي لهذا القسم** إذا اختلفت عن تفسير قديم سابق.

---

## 2. الهدف الوظيفي من الكيان

`Battery` تمثل **وحدة هيكلية فعلية داخل العنبر**، وهي المستوى الذي يربط بين تصميم البطارية وبين الأقفاص / العيون الفعلية التي سيستخدمها النظام لاحقًا في التسكين والحركات والصيانة والتطهير.

الصورة الهيكلية الأساسية:

```text
Farm
└── Barn
    └── Battery
        └── Cage / Cell
            └── Occupancy / Animals / Operational History
```

البطارية ليست مجرد تجميع للأقفاص. التكوين الهيكلي الخاص بها هو المصدر الذي يحدد عدد الأقفاص ومواقعها وأكوادها عند التوليد.

لذلك يجب الفصل بين:

```text
Battery Physical Type
= نوع / تصميم فيزيائي مرجعي

Battery Operational Activities
= الاستخدامات التشغيلية الحالية للبطارية

Battery Structural Configuration
= التكوين الفعلي الذي يحدد مواقع وعدد الأقفاص

Generated Cages
= الأقفاص الفعلية الناتجة من التكوين
```

كما يجب الفصل بين **الحالة المحلية للبطارية** و**الإتاحة التشغيلية الفعلية** و**إشغال الأقفاص** و**مرحلة تفعيل الأقفاص**؛ هذه مفاهيم مستقلة.

---

## 3. ملخص القرارات المعتمدة من الإجابات الحالية

الصورة الحالية التي تحسمها الإجابات هي:

```text
Battery
├── Identity
│   ├── name
│   └── code → automatic + globally unique
│
├── Parent
│   └── belongs to exactly one Barn
│
├── Classification
│   ├── Physical Type → managed Master Data
│   └── Activities → multiple activities from parent Barn activities
│
├── Direct Data
│   ├── started_at
│   ├── manufacturer
│   ├── model
│   └── notes
│
├── Structural Configuration
│   ├── simple model supported
│   ├── flexible model supported
│   ├── non-uniform structures supported
│   └── structure is source of actual cage count
│
├── Cage Provisioning
│   ├── no independent manual Cage create
│   ├── explicit Generate Cages action
│   ├── preview before generation
│   ├── structure-based numbering
│   ├── numbering direction configurable
│   ├── generated cages start Pending Activation
│   └── bulk and selective activation supported
│
├── Structural History
│   ├── rebuild allowed before any Cage operational history
│   ├── any operational record locks structure
│   ├── historical Cage identity cannot be reused
│   └── physical redesign after history → retire old Battery + create new Battery
│
├── Status
│   ├── active
│   ├── stopped
│   ├── maintenance
│   └── statuses are managed values
│
├── Sanitation
│   ├── Cage / Cage Group / Whole Battery
│   ├── bulk operation creates per-Cage history
│   ├── policies come from Operational Settings
│   └── records preserve settings reference
│
└── Derived Metrics
    ├── total cages
    ├── active cages
    ├── occupied cages
    ├── housing-eligible cages
    ├── current animals
    ├── effective capacity
    └── available capacity
```

---

## 4. تفسير القرارات الرئيسية

### 4.1 البيانات المباشرة والعلاقات

`battery.fields` يعتمد:

```text
name
code
barn
physical_type
activity
structural_configuration
status
started_at
manufacturer
model
notes
```

لكن هذه العناصر ليست كلها Columns مباشرة داخل جدول واحد.

التفسير الصحيح:

- `name`, `code`, `started_at`, `manufacturer`, `model`, `notes` → بيانات مباشرة محتملة.
- `barn` → Relationship إلى Barn.
- `physical_type` → Relationship إلى Battery Physical Type كـMaster Data مستقلة.
- `activity` → Relationship إلى OperationalActivity.
- `structural_configuration` → بنية / كيان أو مجموعة بيانات هيكلية متخصصة.
- `status` → Lifecycle / Operational State.

**لا يجوز** تحويل كل اختيار في `battery.fields` إلى نص أو Column بسيط دون تحليل طبيعته.

### 4.2 البيانات الإلزامية عند الإنشاء

`battery.required_fields` يجعل جميع العناصر السابقة إلزامية عند إنشاء البطارية **باستثناء `notes` فقط**.

إذن عند الإنشاء يجب توافر:

```text
name
code
barn
physical_type
activity
structural_configuration
status
started_at
manufacturer
model
```

و`notes` مدعومة لكنها اختيارية.

هذه الإجابة تعني كذلك أن `manufacturer` و`model` ليسا مجرد بيانات اختيارية للتوثيق؛ هما **مطلوبان عند الإنشاء وفق القرار الحالي**.

### 4.3 هوية البطارية والكود

يجب أن تدعم البطارية **اسمًا وكودًا معًا**.

الكود:

```text
Generated automatically by system
+ Globally unique
```

ولا يجوز استنتاج:

- Prefix معين.
- Pattern معين.
- طول ثابت.
- تركيب الكود من كود المزرعة أو العنبر.
- إمكانية تعديل الكود بعد التوليد.

كل هذه تفاصيل غير محسومة من الأسئلة الحالية.

### 4.4 علاقة البطارية بالعنبر

كل Battery تتبع **Barn واحدًا فقط**.

```text
Barn 1 ─── N Batteries
Battery N ─── 1 Barn
```

ولا يجوز إنشاء Battery بلا Barn أو إسناد Battery واحدة لأكثر من Barn.

### 4.5 النوع الفيزيائي مستقل عن النشاط والتكوين

القرار المعتمد يفصل بشكل صريح بين:

```text
Physical Type
Operational Activities
Structural Configuration
```

`Battery Physical Type` يدار كـMaster Data قابلة للإضافة والتعديل من لوحة التحكم.

ولا يجوز استخدام النوع الفيزيائي نفسه كبديل عن النشاط التشغيلي أو عن وصف التكوين الفعلي للبطارية.

### 4.6 بيانات الشركة المصنعة والموديل

`battery.manufacturer_tracking = نعم` يؤكد أن الشركة المصنعة والموديل لهما قيمة فعلية داخل ملف البطارية.

وبالاقتران مع `battery.required_fields` فإن كلاهما مطلوب عند إنشاء البطارية في القرار الحالي.

هذا لا ينشئ تلقائيًا كيانات Master Data مستقلة للمصنعين أو الموديلات؛ شكل تمثيلهما الفني لم يحسمه هذا القسم.

---

## 5. النشاط التشغيلي للبطارية

### `battery.activity_assignment_rule`

القرار الحالي هو:

```text
multiple_from_barn
```

أي أن البطارية يمكن أن ترتبط **بأكثر من نشاط تشغيلي في نفس الوقت**، بشرط أن تكون الأنشطة المختارة ضمن الأنشطة المتاحة للعنبر الأب.

إذن النموذج المفاهيمي الحالي:

```text
Barn
└── Available Operational Activities
        ↓ subset
Battery
└── One or more Operational Activities
```

ولا يوجد قاموس أنشطة منفصل للبطاريات.

**لا يجوز** تفسير الإجابة كأن البطارية لها نشاط واحد فقط؛ القرار الحالي صريح في دعم التعدد.

### `battery.activity_change_policy`

تغيير الأنشطة بعد بدء الاستخدام مسموح فقط عندما:

```text
Battery is empty
AND
Battery is effectively ready for new housing according to active operational settings
AND
Activity change history is preserved
```

إذن:

```text
Empty ≠ Automatically Ready
```

قد تكون البطارية فارغة لكنها غير جاهزة بسبب صيانة أو تطهير أو فترة انتظار أو مانع تشغيلي آخر.

هذا السؤال لا يحدد شكل سجل تاريخ تغيير الأنشطة، لكنه يثبت ضرورة الحفاظ على التاريخ.

---

## 6. التكوين الهيكلي للبطارية

### `battery.structure_model`

يجب دعم نموذجين:

```text
Simple Uniform Model
+
Flexible Structural Model
```

النموذج البسيط يخدم البطاريات المنتظمة، بينما النموذج المرن يسمح بتمثيل التصميمات المختلفة أو غير المنتظمة.

### `battery.structure_components`

محرك التكوين يجب أن يستطيع تمثيل عند الحاجة:

```text
Sides / Faces
Levels / Tiers
Segments / Sections
Cages per structural unit
Unit ordering
Numbering direction
Unit code / label
```

اختيار هذه العناصر يعني أن المحرك يدعمها، **ولا يعني بالضرورة أن كل عنصر مطلوب في كل Battery** إلا إذا كانت قواعد النموذج المختار تتطلبه.

### `battery.non_uniform_structure`

يجب دعم بطاريات تختلف فيها أعداد الأقفاص بين جانب وآخر أو مستوى وآخر أو قطاع وآخر.

لا يجوز افتراض معادلة ثابتة من نوع:

```text
levels × cages_per_level
```

لكل البطاريات.

### `battery.total_cages_strategy`

إجمالي الأقفاص الفعلي:

```text
Calculated from Structural Configuration
```

ولا يدخل يدويًا كرقم مستقل يمكن أن يتعارض مع التكوين.

مصدر الحقيقة:

```text
Structural Configuration
        ↓
Generated Cages
        ↓
Actual Cage Count
```

ولا توجد إجابة حالية تعتمد قيمة Planned Cage Count منفصلة.

---

## 7. توليد الأقفاص وترقيمها وتفعيلها

### `battery.auto_generate_cages`

النظام ينشئ الأقفاص من تكوين البطارية بدل إدخالها واحدًا واحدًا.

### `battery.manual_cage_creation_policy`

لا يوجد Create مستقل للقفص خارج تكوين البطارية.

```text
Battery Structure
→ Generate Cages
→ Cage Records
```

### `battery.cage_generation_timing`

التوليد لا يحدث بمجرد كل Save.

المسار المعتمد:

```text
Create / edit Battery
→ Complete Structural Configuration
→ Preview
→ Explicit "Generate Cages" action
→ Cage Records created
```

### `battery.cage_generation_preview`

قبل التنفيذ النهائي يجب عرض معاينة تساعد على مراجعة:

- العدد المتوقع.
- الأكواد المتوقعة.
- المواقع الهيكلية.

### `battery.cage_numbering_strategy`

الترقيم يجب أن يكون **Structure Based** ويعكس الموقع الفعلي داخل البطارية.

مثلًا مفاهيميًا فقط:

```text
Battery + Side + Level + Position
```

لكن لا يثبت هذا المثال Format نهائيًا للكود.

### `battery.cage_numbering_direction`

التكوين يجب أن يدعم اتجاه وترتيب الترقيم بما يطابق الواقع الميداني، مثل اليمين / اليسار أو أعلى / أسفل.

### `battery.generated_cage_initial_state`

القفص المولد لا يصبح جاهزًا للتشغيل فورًا.

```text
Generated
→ Pending Activation
→ Activated / Operationally Eligible when prerequisites are met
```

ولا يجب خلط `Pending Activation` مع حالة الإشغال.

### `battery.cage_activation_scope`

يجب دعم:

```text
Bulk activation
+
Selective activation
```

أي يمكن تفعيل جميع الأقفاص المناسبة أو مجموعة مختارة منها.

### `battery.cage_activation_prerequisites`

قبل تفعيل القفص يجب التحقق من جميع الشروط المعتمدة:

1. اعتماد التكوين الهيكلي.
2. وجود كود قفص صالح وفريد.
3. أن تكون البطارية في حالة تسمح بالتشغيل.
4. أن يكون العنبر متاحًا تشغيليًا.
5. اكتمال بيانات القفص المطلوبة.
6. عدم وجود مانع صيانة أو تجهيز.

هذه شروط تفعيل أولي، ولا تلغي قواعد Housing Eligibility اللاحقة التي قد تعتمد على إعدادات التشغيل والتطهير والصيانة والإشغال.

---

## 8. القفل الهيكلي والحفاظ على التاريخ

### `battery.regeneration_before_history`

قبل وجود أي سجل تشغيلي على الأقفاص الناتجة، يسمح بتصحيح التكوين وإعادة توليد الأقفاص.

هذا يعني إمكانية حذف / إعادة بناء **الأقفاص غير المستخدمة الناتجة من التكوين**، وليس حذف سجل البطارية نفسها.

### `battery.structural_lock_trigger`

Trigger القفل هو:

```text
Any operational record on any Cage
```

وليس فقط أول حركة حيوان.

أمثلة:

```text
Housing
Movement
Sanitation
Maintenance
Other operational record
```

### `battery.structural_lock_effects`

بعد القفل يمنع:

- تغيير عدد الأقفاص بصورة تعيد كتابة التاريخ.
- إعادة توليد الأقفاص.
- حذف قفص تاريخي.
- إعادة ترقيم الأقفاص المستخدمة.
- تغيير الموقع الهيكلي التاريخي.
- إعادة استخدام هوية / كود قفص قديم لقفص آخر.

### `battery.physical_reconfiguration_after_history`

إذا حدث تعديل مادي حقيقي بعد وجود تاريخ ويغير التكوين جذريًا:

```text
Old Battery
→ Retire
→ Preserve old Cages + History

New physical configuration
→ New Battery Record
→ New Identity / Code
→ New Structural Configuration
→ New Generated Cages
```

ولا يعاد تشكيل البطارية القديمة كأن تاريخها حدث على التكوين الجديد.

### `battery.cage_identity_after_history`

هوية القفص وكوده وموقعه التاريخي تبقى محفوظة ولا يعاد استخدامها لقفص آخر.

الصيانة أو التطهير أو التوقف لا تنشئ Cage جديدًا.

---

## 9. حالات البطارية والإتاحة التشغيلية

### `battery.statuses`

القيم الحالية المختارة هي:

```text
active
stopped
maintenance
```

### `battery.status_management`

القرار الحالي هو:

```text
managed
```

أي أن حالات البطارية **قابلة للإضافة والتعديل من لوحة التحكم** وليست Enum مغلقة وفق الإجابة الحالية.

لكن الأسئلة الحالية تحدد Business Rules صريحة فقط لحالات مثل `stopped` و`maintenance`.

لذلك إذا أضيفت حالة جديدة مستقبلًا، **لا يجوز استنتاج سلوكها أو قواعد الانتقال إليها تلقائيًا**؛ يجب أن تكون لها قواعد واضحة في Settings أو Requirements أخرى عند الحاجة.

### `battery.status_change_requires_empty`

للانتقال من التشغيل إلى `Stopped` أو `Maintenance` يجب أن تكون جميع الأقفاص خالية من الحيوانات.

### `battery.status_change_requires_inactive_cages`

بالإضافة إلى الخلو، يجب ألا تبقى أقفاص تابعة في حالة تشغيلية محلية غير مسموح بها.

إذن الشرطان مستقلان ومتراكمـان:

```text
Battery empty
AND
All child Cages in allowed non-operational state
```

ولا يغير النظام حالات الأقفاص تلقائيًا لتجاوز الشرط.

### `battery.parent_status_effect`

إذا أصبح العنبر غير متاح تشغيليًا:

```text
Battery local status remains unchanged
Cage local statuses remain unchanged
BUT
Effective operational availability becomes unavailable
```

### `battery.non_operational_child_effect`

وبالمثل، عندما تصبح Battery متوقفة أو تحت الصيانة:

```text
Child Cage local status remains unchanged
BUT
New housing / operational use is blocked because parent Battery is unavailable
```

هذا يثبت الفصل بين:

```text
Local Status
vs
Effective Operational Availability
```

---

## 10. التطهير وسجله التاريخي

### `battery.sanitation_supported_scopes`

التطهير Workflow تشغيلي يدعم:

```text
Single Cage
Cage Group
Whole Battery
```

### `battery.sanitation_requires_empty_cages`

تطهير Battery كاملة لا يبدأ إذا كان أي Cage تابع ما زال يحتوي حيوانًا.

### `battery.sanitation_generates_cage_records`

عند تنفيذ عملية جماعية على مجموعة أقفاص أو Battery كاملة:

```text
One parent / bulk sanitation operation
        ↓
Automatic per-Cage sanitation records
```

حتى يظهر سجل التطهير داخل Timeline كل Cage بصورة صحيحة.

### `battery.sanitation_policy_from_settings`

القواعد التالية لا تثبت داخل Battery كقيم جامدة، بل تأتي من Operational Settings Profile الساري:

- هل التطهير إلزامي بعد دورة إشغال.
- هل التطهير يمنع التسكين أثناء التنفيذ.
- هل توجد فترة انتظار بعد التطهير.
- هل الصيانة تستلزم تطهيرًا قبل إعادة الاستخدام.

### `battery.sanitation_record_fields`

سجل التطهير يدعم:

```text
started_at
completed_at
executed_by
status
trigger
scope
notes
parent_operation_reference
settings_reference
```

الهدف هو Audit وتشخيص ما حدث تاريخيًا، وليس بناء بروتوكول بيطري أو كيميائي غير معتمد.

### `battery.sanitation_completion_rule`

طريقة اعتماد اكتمال التطهير تحدد من **Operational Settings**، ولا يفرض هذا القسم أن المنفذ وحده أو مستخدمًا ثانيًا هو من يعتمدها دائمًا.

### `battery.sanitation_settings_reference`

سجل التطهير يحتفظ بمرجع إلى إعدادات التشغيل المطبقة وقت التنفيذ.

الهدف أن يظل السجل قابلًا للتفسير بعد تغير الإعدادات مستقبلًا.

السؤال لا يحسم هل التنفيذ الفني يكون Foreign Key أو Version Reference أو Snapshot كامل؛ يثبت فقط ضرورة الحفاظ على المرجع التاريخي المناسب.

---

## 11. القيم المشتقة للبطارية

`battery.derived_metrics` يقرر أن النظام يحسب تلقائيًا:

```text
Total Cages
Active Cages
Occupied Cages
Housing-Eligible Cages
Current Animals
Effective Capacity
Available Capacity
```

ولا تخزن هذه القيم كمدخلات يدوية موازية.

يجب التفريق خصوصًا بين:

```text
Active Cage
≠ Occupied Cage
≠ Housing-Eligible Cage
```

فقفص قد يكون Active محليًا وفارغًا لكنه غير مؤهل للتسكين بسبب:

- حالة Barn أو Battery الأب.
- عدم اكتمال التفعيل.
- صيانة.
- تطهير جارٍ.
- فترة انتظار.
- قاعدة تشغيل أخرى.

كما أن `Available Capacity` ليست مجرد `Effective Capacity - Current Animals` ما لم تعتمد Requirements الحساب هذه المعادلة؛ تفاصيل الحساب تعتمد على بيانات الأقفاص وقواعد السعة والتسكين.

---

## 12. الإخراج من الخدمة والحذف

### `battery.delete_policy`

السياسة الحالية:

```text
Battery record created
→ No hard delete
→ Retire / take out of service only
```

حتى لو لم يوجد تاريخ تشغيلي، الإجابة الحالية لا تسمح بحذف سجل البطارية نفسه بعد إنشائه.

وهذا لا يتعارض مع `battery.regeneration_before_history`؛ لأن التصحيح المبكر يخص الأقفاص المولدة والتكوين قبل التاريخ، وليس حذف هوية البطارية.

### `battery.additional_requirements`

الإجابة الحالية: `لا توجد`.

هذا سؤال دراسة مفتوح من `manual_review` ولا ينتج Requirement نهائيًا مباشرة. إذا ظهرت لاحقًا نقطة حقيقية، يجب تحويلها إلى سؤال منظم مستقل قبل اعتمادها.

---

## 13. تفسير Question Keys — مرجع سريع

| Question Key | المعنى الذي يجب أن ينتج عنه |
|---|---|
| `battery.fields` | نطاق بيانات وعلاقات ومفاهيم Battery المدعومة. |
| `battery.required_fields` | البيانات المطلوبة عند إنشاء Battery؛ جميع المختارات عدا notes حاليًا. |
| `battery.barn_relation` | كل Battery تتبع Barn واحدة. |
| `battery.identity_strategy` | الهوية التشغيلية تعتمد الاسم والكود معًا. |
| `battery.code_strategy` | الكود مولد تلقائيًا. |
| `battery.code_unique_scope` | الكود فريد عالميًا. |
| `battery.physical_type_separation` | فصل Physical Type عن Activities وعن Structural Configuration. |
| `battery.physical_type_management` | Battery Types Master Data مُدارة. |
| `battery.manufacturer_tracking` | دعم الشركة المصنعة والموديل. |
| `battery.activity_assignment_rule` | دعم عدة Activities من Activities العنبر الأب. |
| `battery.activity_change_policy` | التغيير بعد التشغيل يحتاج الخلو + الجاهزية + حفظ التاريخ. |
| `battery.structure_model` | دعم Simple + Flexible configuration. |
| `battery.structure_components` | دعم العناصر الهيكلية المختارة عند الحاجة. |
| `battery.non_uniform_structure` | دعم تصميمات غير منتظمة. |
| `battery.total_cages_strategy` | Actual Cage Count مشتق من Structure. |
| `battery.auto_generate_cages` | الأقفاص تنشأ من التكوين. |
| `battery.manual_cage_creation_policy` | منع Create مستقل للقفص. |
| `battery.cage_generation_timing` | توليد صريح بعد استكمال التكوين. |
| `battery.cage_generation_preview` | معاينة قبل التوليد. |
| `battery.cage_numbering_strategy` | ترقيم يعكس الموقع الهيكلي. |
| `battery.cage_numbering_direction` | اتجاه وترتيب الترقيم قابلان للضبط. |
| `battery.generated_cage_initial_state` | الأقفاص تبدأ Pending Activation. |
| `battery.cage_activation_scope` | تفعيل جماعي وانتقائي. |
| `battery.cage_activation_prerequisites` | تحقق شروط الجاهزية الستة قبل التفعيل. |
| `battery.regeneration_before_history` | إعادة التوليد مسموحة قبل أي تاريخ تشغيلي للقفص. |
| `battery.structural_lock_trigger` | أي Operational Record يقفل الهيكل. |
| `battery.structural_lock_effects` | منع التغييرات الهدامة بعد القفل. |
| `battery.physical_reconfiguration_after_history` | Retire old + create new Battery عند تغيير مادي جوهري. |
| `battery.cage_identity_after_history` | الحفاظ الدائم على هوية Cage التاريخية. |
| `battery.statuses` | الحالات الحالية Active / Stopped / Maintenance. |
| `battery.status_management` | الحالات Managed وقابلة للإدارة. |
| `battery.status_change_requires_empty` | الإخلاء شرط قبل Stopped / Maintenance. |
| `battery.status_change_requires_inactive_cages` | الأقفاص يجب أن تكون غير تشغيلية أيضًا. |
| `battery.parent_status_effect` | حالة Barn الأب تؤثر على Effective Availability لا Local Status. |
| `battery.non_operational_child_effect` | Battery غير التشغيلية تمنع استخدام الأقفاص دون Cascade status. |
| `battery.sanitation_supported_scopes` | التطهير على Cage / Group / Battery. |
| `battery.sanitation_requires_empty_cages` | Whole-Battery sanitation يتطلب الإخلاء. |
| `battery.sanitation_generates_cage_records` | Bulk sanitation يولد سجلات فردية لكل Cage. |
| `battery.sanitation_policy_from_settings` | قواعد التطهير وإعادة الاستخدام مصدرها Operational Settings. |
| `battery.sanitation_record_fields` | بيانات Audit لسجل التطهير. |
| `battery.sanitation_completion_rule` | اعتماد اكتمال التطهير تحدده Settings. |
| `battery.sanitation_settings_reference` | حفظ مرجع إعدادات التنفيذ تاريخيًا. |
| `battery.derived_metrics` | كل مؤشرات العدد والإشغال والسعة المحددة مشتقة. |
| `battery.delete_policy` | لا Hard Delete؛ إخراج من الخدمة فقط. |
| `battery.additional_requirements` | سؤال دراسة فقط، ولا توجد ملاحظة حالية. |

---

## 14. فحص اتساق الإجابات

### 14.1 النتيجة العامة

الإجابات الحالية **متماسكة ولا يوجد تعارض داخلي مفتوح يمنع اعتماد القسم**.

من أبرز نقاط الاتساق:

- `auto_generate_cages` + منع الإنشاء اليدوي + الحساب من Structure كلها تدعم مصدر حقيقة واحد للأقفاص.
- السماح بإعادة التوليد قبل التاريخ يتوافق مع القفل بعد `any_operational_record`.
- `retire_only` للبطارية لا يتعارض مع إعادة بناء الأقفاص قبل التاريخ، لأن أحدهما يخص هوية Battery والآخر يخص Structure / Generated Cages.
- تغيير النشاط المشروط بالخلو والجاهزية يتوافق مع قواعد التطهير والإتاحة الفعلية.
- منع تغيير حالة Battery قبل إخلائها وقبل جعل الأقفاص غير تشغيلية يتوافق مع التسلسل من الأسفل إلى الأعلى.
- تأثير حالة Barn أو Battery على الأبناء مبني على Effective Availability ولا يغير Local Status تلقائيًا.

### 14.2 نقاط تحتاج ألا تُفسر بأكثر مما تحسمه الإجابات

#### أ. حالات Battery القابلة للإدارة

القيم الحالية هي:

```text
active / stopped / maintenance
```

لكن `status_management = managed` يسمح بإدارة القائمة.

هذا **ليس تعارضًا**، لكنه يعني أن سلوك أي Status جديدة مستقبلًا غير محدد من هذا القسم ولا يجوز اختراعه.

#### ب. صيغة الأكواد

القرارات تحسم التوليد التلقائي والفريدة العالمية والترقيم الهيكلي للأقفاص، لكنها لا تحسم Format نصي نهائي لأي Code.

#### ج. تعدد الأنشطة

الإجابة الحالية تعتمد `multiple_from_barn`، ولذلك يجب اعتبار التعدد هو القرار الحالي، ولا تستخدم أي تفسيرات قديمة كانت تفترض Activity واحدة للبطارية.

#### د. الشركة المصنعة والموديل

الإجابات الحالية تجعل كلاهما Required عند إنشاء Battery. لا يجوز تخفيف ذلك إلى Optional لمجرد أن المرجع الوظيفي الأصلي وصف الشركة المصنعة بصيغة «إن كان مهمًا»؛ الإجابة الحالية هي القرار الأحدث.

---

## 15. ما الذي لا يجب استنتاجه من هذا القسم

لا يتم استنتاج أي من التالي بدون سؤال / Requirement صريح:

```text
Battery code format or prefix
Editable generated Battery code
Manufacturer as separate Master Data entity
Model as separate Master Data entity
Fixed number of sides / levels / cages
One cage = one animal
Planned cage count not selected by strategy
Independent Cage creation
Automatic Cage activation after generation
Automatic deletion of unused Battery records
Automatic cascade of Barn status into Battery local status
Automatic cascade of Battery status into Cage local status
Automatic animal relocation on status change
Automatic Activity replacement without readiness checks
Sanitation chemical protocol
Sanitation duration defaults
Universal sanitation waiting period
Fixed approval model for sanitation
Custom Battery status semantics not explicitly defined
Derived metric formulas not explicitly approved
```

كما لا يجوز:

- تخزين Current Occupancy كمدخل يدوي.
- استخدام Active Cage كمرادف لـHousing Eligible Cage.
- إعادة استخدام Code أو Identity لقفص تاريخي.
- تعديل Battery تاريخية جوهريًا لتطابق تركيبًا ماديًا جديدًا.

---

## 16. المخرجات المطلوبة من وكيل الـRequirements

بعد قراءة هذا الدليل مع ملف الإجابات النهائي، يجب أن تنتج Requirements تغطي على الأقل:

1. **Battery Entity Scope** وعلاقتها بـBarn.
2. **Create-time Required Fields** وفق الإجابات الحالية.
3. **Identity & Code Rules**.
4. **Physical Type Relationship & Management**.
5. **Manufacturer / Model Tracking**.
6. **Multi-Activity Assignment from Barn**.
7. **Activity Change Preconditions & History**.
8. **Simple + Flexible Structural Configuration** ودعم Non-uniform layouts.
9. **Derived Total Cage Count**.
10. **Cage Generation Workflow**: Preview → Explicit Generate.
11. **No Independent Cage Create**.
12. **Structure-Based Numbering & Direction**.
13. **Pending Activation + Bulk / Selective Activation**.
14. **Cage Activation Preconditions**.
15. **Pre-history Regeneration**.
16. **Structural Lock Trigger & Effects**.
17. **Post-history Physical Reconfiguration** عبر Retire + New Battery.
18. **Historical Cage Identity Protection**.
19. **Battery Status Model & Status Management**.
20. **Status Transition Preconditions**.
21. **Parent / Child Effective Availability Rules** بدون Cascade Local Status.
22. **Sanitation Supported Scopes**.
23. **Sanitation Empty Preconditions**.
24. **Bulk Sanitation → Per-Cage Audit Records**.
25. **Operational Settings Integration for Sanitation**.
26. **Sanitation Audit Fields & Completion Rule**.
27. **Historical Settings Reference**.
28. **Derived Battery Metrics**.
29. **Retire-only Battery Lifecycle**.
30. **Explicit exclusions / non-inferences** المذكورة في هذا الدليل.

---

## 17. الخلاصة التنفيذية

وفق الإجابات الحالية، `Battery` هي **كيان هيكلي طويل العمر داخل Barn**، وتكوينها هو مصدر إنشاء Cage Records وهوياتها الفعلية.

المسار الأساسي المعتمد:

```text
Create Battery
→ define required identity / parent / type / activities / structure
→ preview generated Cages
→ explicit Generate Cages
→ Cages created Pending Activation
→ validate activation prerequisites
→ activate all or selected Cages
→ operational use begins
→ first operational record locks structural history
```

بعد وجود التاريخ:

```text
No destructive regeneration
No historical Cage renumbering
No identity reuse
Physical redesign → Retire old Battery + Create new Battery
```

والإشغال والسعة والإتاحة الفعلية كلها تعتمد على الأقفاص والحركات والحالات والإعدادات، وليست أرقامًا يكتبها المستخدم يدويًا.
