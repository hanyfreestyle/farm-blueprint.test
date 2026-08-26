# 2.2 بيانات العنبر — دليل تفسير الإجابات

> **الحالة:** مسودة أولى مبنية على الإجابات الحالية للمراجعة  
> **ملف الإجابات المقابل:** `questionnaire-export/answers/02-farm-structure/02-barns.md`  
> **Question Keys المغطاة:** 22 / 22

---

## 1. الغرض من هذا الملف

هذا الملف يفسر **المعنى الوظيفي والتقني للإجابات المعتمدة فعليًا** في قسم `بيانات العنبر` ضمن `هيكل المزرعة`.

هو ليس مصدرًا بديلًا للإجابات، ولا يغير أي قرار موجود في ملف الإجابات.

```text
answers/02-farm-structure/02-barns.md
= ماذا قررنا؟

                    ↓ تفسير

guides/02-farm-structure/02-barns.md
= ماذا تعني هذه القرارات تقنيًا؟ وما حدودها؟
```

تم بناء التفسير وفق منهج `docs/questionnaire-guide/` مع الاستفادة من الدليل السابق لبيانات العنبر، وتطبيقه على الإجابات الحالية بعد انتقال `Barn` إلى قسم `هيكل المزرعة`.

---

## 2. الهدف الوظيفي من الكيان

`Barn` يمثل **وحدة تنظيمية ومكانية داخل المزرعة**، ويتبع مزرعة واحدة ويحتوي على البطاريات التي تنتج عنها الأقفاص / العيون ومؤشرات الإشغال الفعلية.

الصورة الهيكلية الأساسية:

```text
Farm
└── Barn
    └── Battery
        └── Cage / Cell
            └── Occupancy / Animals
```

يجب التمييز داخل ملف العنبر بين ثلاث طبقات:

```text
Direct / Planned Barn Data
= الاسم، الكود، المساحة، السعة التصميمية، تاريخ البدء، وصف الموقع، الملاحظات...

Relationships
= المزرعة، الأنشطة التشغيلية، ملفات إعدادات التشغيل، أنظمة البيئة

Derived Data
= عدد البطاريات الفعلي، السعة الفعلية، عدد الأقفاص، الإشغال، الحيوانات الحالية...
```

ولا يجوز تحويل القيم المشتقة إلى مدخلات يدوية موازية للواقع التشغيلي.

---

## 3. ملخص القرارات المعتمدة من الإجابات الحالية

الصورة الحالية التي تحسمها الإجابات هي:

```text
Barn
├── Identity
│   ├── name
│   └── code → automatic + global unique
│
├── Parent
│   └── belongs to exactly one Farm
│
├── Activities
│   ├── required on create
│   └── multiple activities allowed
│
├── Operational Settings Profiles
│   ├── required before operation
│   └── multiple active profiles allowed
│
├── Environment Relationships
│   ├── Ventilation → optional + multiple
│   ├── Cooling → optional + multiple
│   └── Heating → optional + multiple
│
├── Status
│   ├── active
│   ├── stopped
│   └── maintenance
│       └── fixed values
│
├── Capacity
│   ├── planned/design capacity stored
│   └── actual capacity calculated
│
├── Batteries
│   ├── planned battery count stored
│   └── actual battery count calculated
│
├── Occupancy
│   └── derived automatically from child structure / movements
│
├── Status Transition Guards
│   ├── Barn must be empty
│   └── child Batteries must be non-operational as required by Battery rules
│
└── Lifecycle
    └── disable only; no deletion
```

هذه الخريطة **مفهومية** ولا تفرض أسماء جداول أو أعمدة أو Pivot tables نهائية.

---

## 4. تعارض مفتوح داخل الإجابات الحالية

### تعارض Required Fields مع الأسئلة المتخصصة

يوجد تعارض يحتاج قرارًا بشريًا قبل تحويل هذه الجزئية إلى Requirement نهائي.

**Question Keys المرتبطة:**

```text
barn.required_fields
barn.settings_profile_requirement
barn.ventilation_relation
barn.cooling_relation
barn.heating_relation
```

`barn.required_fields` يعتمد حاليًا جميع العناصر التالية كإلزامية **عند إنشاء العنبر**:

```text
settings_profile
ventilation_system
cooling_system
heating_system
```

لكن الأسئلة المتخصصة تقرر:

```text
barn.settings_profile_requirement
= required_before_operation
→ يمكن إنشاء العنبر بدون ملف إعدادات، لكنه يصبح مطلوبًا قبل التشغيل

barn.ventilation_relation
= optional_multiple

barn.cooling_relation
= optional_multiple

barn.heating_relation
= optional_multiple
```

وبالتالي لا يمكن اعتبار هذه العلاقات الأربع في الوقت نفسه:

```text
Required on Create
```

و:

```text
Deferred / Optional
```

### الأثر

يمكن اعتماد بقية تصميم `Barn`، لكن **Requiredness وقت الإنشاء** لهذه العلاقات الأربع لا يجب تثبيتها نهائيًا قبل حسم أي الإجابتين هي المقصودة.

### القرار المطلوب لاحقًا

يجب تحديد هل:

1. `barn.required_fields` يحتاج تعديلًا بحيث يقتصر على العلاقات المطلوبة فعلًا عند الإنشاء، مع إبقاء ملف الإعدادات Required Before Operation وأنظمة البيئة Optional؛ أو
2. المقصود أن هذه العلاقات كلها إلزامية عند الإنشاء، وفي هذه الحالة يجب تعديل الأسئلة المتخصصة لتتوافق مع ذلك.

لا يحسم هذا الملف الاختيار من تلقاء نفسه.

---

## 5. تفسير Question Keys والإجابات المعتمدة

### `barn.fields`

**الإجابة المعتمدة:** جميع العناصر المعروضة تم اختيارها.

### المعنى التقني

ملف العنبر يجب أن يدعم وظيفيًا:

```text
name
code
farm
usage / operational activities
settings_profile
ventilation_system
cooling_system
heating_system
area
capacity
status
started_at
location_description
notes
```

لكن هذه ليست كلها Columns مباشرة.

- `farm` علاقة مع `Farm`.
- `usage` علاقة مع `OperationalActivity`.
- `settings_profile` علاقة مع ملفات إعدادات التشغيل.
- أنظمة التهوية والتبريد والتدفئة علاقات مع Master Data مستقلة.
- `capacity` يجب قراءتها مع `barn.capacity_strategy` للفصل بين التخطيطي والمشتق.

**لا يجوز استنتاج** Schema مباشرة من قائمة الحقول وحدها.

---

### `barn.required_fields`

الإجابة الحالية تجعل **كل العناصر المختارة في السؤال إلزامية عند إنشاء العنبر** بما فيها الملاحظات ووصف الموقع وأنظمة البيئة وملف الإعدادات.

هذا القرار صالح للعناصر التي لا تعارضها أسئلة متخصصة، لكنه **غير قابل للاعتماد النهائي** فيما يخص:

```text
settings_profile
ventilation_system
cooling_system
heating_system
```

إلى أن يحسم التعارض الموضح أعلاه.

أما بقية العناصر المختارة، فتنتج حاليًا Create-time Validation يمنع إنشاء العنبر بدونها.

---

### `barn.code_strategy`

**الإجابة:** `automatic`.

يجب أن يولد النظام كود العنبر تلقائيًا.

**لا تستنتج:**

- Prefix.
- Pattern.
- طولًا ثابتًا.
- قاعدة ترقيم تعتمد على المزرعة.
- قابلية تعديل الكود بعد التوليد.

هذه التفاصيل لم تحسم هنا.

---

### `barn.code_unique_scope`

**الإجابة:** `global_unique`.

كود العنبر يجب أن يكون فريدًا على مستوى النظام بالكامل، وليس فقط داخل المزرعة التابعة له.

هذا القرار متسق مع دعم أكثر من مزرعة من الإصدار الأول.

---

### `barn.unique_name_within_farm`

**الإجابة:** نعم.

يجب منع وجود عنبرين بالاسم نفسه **داخل المزرعة نفسها**.

لا تعني هذه القاعدة أن الاسم يجب أن يكون فريدًا عالميًا بين جميع المزارع.

```text
Farm A → Barn "إنتاج 1"
Farm B → Barn "إنتاج 1"
```

لا يمنعه هذا السؤال وحده.

---

### `barn.farm_relation`

**الإجابة:** نعم.

كل عنبر يتبع مزرعة واحدة ضمن التسلسل:

```text
Farm 1 ──< Barns
```

ولا يجوز تمثيل المزرعة كنص حر داخل Barn بدل العلاقة المرجعية.

---

### `barn.activity_requirement`

**الإجابة:** `required_on_create`.

يجب تحديد النشاط التشغيلي عند إنشاء العنبر.

الأنشطة نفسها معرفة في `OperationalActivity` ولا يعاد تعريفها داخل العنبر.

---

### `barn.multiple_activities`

**الإجابة:** نعم.

العنبر يمكن أن يرتبط بأكثر من نشاط تشغيلي في الوقت نفسه.

المعنى الوظيفي:

```text
Barn ↔ OperationalActivities
```

ولا يعني ذلك إنشاء قيمة مركبة جديدة تجمع أسماء الأنشطة، ولا أن تعدد الأنشطة يولد Workflow خاصًا من تلقاء نفسه.

---

### `barn.settings_profile_requirement`

**الإجابة:** `required_before_operation`.

المعنى المقصود من هذا المفتاح المتخصص:

```text
Create Barn
→ يمكن بدون Operational Settings Profile

Before Barn Operation
→ يجب وجود Profile مناسب
```

لكن هذه الإجابة تتعارض حاليًا مع `barn.required_fields` الذي جعله إلزاميًا عند الإنشاء؛ لذلك لا تثبت Requiredness النهائية قبل حسم التعارض.

---

### `barn.multiple_active_settings_profiles`

**الإجابة:** نعم.

يسمح للعنبر بأكثر من ملف إعدادات تشغيل فعال في الوقت نفسه.

هذا القرار يحدد Cardinality فقط، ولا يحدد:

- أولوية Profile على آخر.
- طريقة دمج قيم متعارضة.
- Versioning.
- Effective dates.
- أي Profile يحكم أي عملية.

هذه القواعد يجب أن تأتي من قسم `الإعدادات وقواعد التشغيل` ولا يجوز اختراعها هنا.

---

### `barn.ventilation_relation`

**الإجابة:** `optional_multiple`.

يمكن للعنبر ألا يرتبط بأي نظام تهوية، ويمكن عند وجود العلاقة ربطه بأكثر من تعريف `VentilationSystem`.

هذا لا يعني تسجيل معدات التهوية الفعلية أو عدد المراوح أو قدرة سحب الهواء.

> توجد حاليًا مشكلة اتساق مع `barn.required_fields` لأنها اعتبرت العلاقة إلزامية عند الإنشاء.

---

### `barn.cooling_relation`

**الإجابة:** `optional_multiple`.

يمكن للعنبر الارتباط بأكثر من نظام تبريد، والعلاقة في السؤال المتخصص اختيارية.

لا ينتج عنها عدد أجهزة أو قدرة تبريد أو استهلاك أو Setpoints.

> توجد حاليًا مشكلة اتساق مع `barn.required_fields` لأنها اعتبرت العلاقة إلزامية عند الإنشاء.

---

### `barn.heating_relation`

**الإجابة:** `optional_multiple`.

يمكن للعنبر الارتباط بأكثر من نظام تدفئة، والعلاقة في السؤال المتخصص اختيارية.

لا ينتج عنها عدد سخانات أو قدرة أو وقود أو قواعد أمان أو درجات حرارة مستهدفة.

> توجد حاليًا مشكلة اتساق مع `barn.required_fields` لأنها اعتبرت العلاقة إلزامية عند الإنشاء.

---

### `barn.statuses`

**الإجابة المعتمدة:**

```text
active
stopped
maintenance
```

هذه هي حالات Lifecycle المعتمدة حاليًا للعنبر.

خيار `other` غير معتمد حاليًا ولا يتحول إلى حالة تلقائيًا.

---

### `barn.status_management`

**الإجابة:** `fixed`.

حالات العنبر **قيم ثابتة داخل النظام** وليست Master Data قابلة للإضافة من لوحة التحكم.

لا يجوز تحويلها إلى قائمة Managed لمجرد أن العديد من القوائم الأخرى في المشروع Managed.

---

### `barn.capacity_strategy`

**الإجابة:** `planned_and_calculated`.

يجب الفصل بين:

```text
Planned / Design Capacity
= قيمة محفوظة تخص التصميم أو التخطيط

Actual Capacity
= قيمة محسوبة من الهيكل الفعلي للبطاريات والأقفاص وقدراتها
```

لا يجوز أن تكون `Actual Capacity` رقمًا يدخله المستخدم بالتوازي مع البيانات التابعة.

ولا يجوز افتراض:

```text
1 Cage = 1 Rabbit
```

لأن السعة الفعلية تعتمد على سعة الأقفاص وقواعد التسكين المعتمدة لاحقًا.

---

### `barn.battery_count_strategy`

**الإجابة:** `planned_and_calculated`.

يجب دعم مفهومين مختلفين:

```text
Planned Battery Count
= قيمة تخطيطية مستقلة

Actual Battery Count
= عدد مشتق من البطاريات المرتبطة فعليًا بالعنبر
```

العدد الفعلي لا يدخل يدويًا.

---

### `barn.occupancy_calculation`

**الإجابة:** نعم.

يجب حساب مؤشرات الإشغال الحالية تلقائيًا من البيانات التابعة، وتشمل وظيفيًا على الأقل مفاهيم مثل:

```text
Actual Cage Count
Occupied Cage Count
Available Cage Count
Current Animal Count
```

هذه **Derived Values** وليست حقول إدخال يدوي على Barn.

ويجب أن يكون الواقع التشغيلي المشتق هو Source of Truth عند استخدام هذه الأرقام في قواعد المنع أو التقارير.

---

### `barn.status_change_requires_empty`

**الإجابة:** نعم.

لا يسمح بالانتقال من `active` إلى `stopped` أو `maintenance` ما دام هناك حيوان داخل العنبر.

التحقق يعتمد على الإشغال الفعلي المشتق، وليس على السعة التصميمية أو قيمة تخطيطية.

هذا السؤال **لا يعني** أن النظام ينقل الحيوانات تلقائيًا؛ يجب إخلاء العنبر عبر الـWorkflow المناسب أولًا.

---

### `barn.status_change_requires_inactive_batteries`

**الإجابة:** نعم.

إضافة إلى شرط خلو العنبر من الحيوانات، يجب أن تكون البطاريات التابعة قد وصلت أولًا إلى حالة غير تشغيلية مسموح بها وفق قواعد `Battery`.

الشرطان مستقلان ويجب تحققهما معًا:

```text
Barn Occupancy = 0
AND
All child Batteries satisfy allowed non-operational state rule
```

لا يقوم النظام بتغيير حالات البطاريات تلقائيًا لتسهيل تغيير حالة العنبر.

---

### `barn.delete_policy`

**الإجابة:** `disable_only`.

لا يسمح بحذف العنبر نهائيًا؛ يتم إخراجه من الاستخدام من خلال حالته التشغيلية مع الحفاظ على:

- البطاريات والأقفاص التابعة.
- تاريخ الإشغال.
- الحركات.
- السجلات التشغيلية المرتبطة.

ولا ينتج عن ذلك Cascade Delete.

---

### `barn.additional_requirements`

**الإجابة الحالية:** `لا توجد`.

هذا سؤال دراسة مفتوح ولا ينتج Requirement جديدًا حاليًا.

إذا احتوى مستقبلًا على متطلب حقيقي، يجب تحويله إلى سؤال منظم مستقل قبل Final Requirements Input.

---

## 6. قواعد الاتساق بين القرارات

### قرارات متسقة

القرارات التالية تعمل معًا بدون تعارض ظاهر:

```text
Barn belongs to one Farm
+ Global unique Barn Code
+ Name unique within Farm
```

وكذلك:

```text
Planned Capacity + Calculated Actual Capacity
Planned Battery Count + Calculated Actual Battery Count
Automatic Occupancy Calculation
```

وكذلك قواعد تغيير الحالة:

```text
Status Change
→ Barn must be empty
→ Batteries must satisfy non-operational rules
→ no automatic cascade
```

كما أن:

```text
status_management = fixed
statuses = active / stopped / maintenance
```

قراران متوافقان.

### القرار غير المحسوم بسبب التعارض

Requiredness عند إنشاء العنبر للعلاقات التالية:

```text
Operational Settings Profile
Ventilation Systems
Cooling Systems
Heating Systems
```

لا تعتمد نهائيًا حتى يحسم التعارض بين `barn.required_fields` والأسئلة المتخصصة.

---

## 7. حدود كيان العنبر — ما الذي لا يجب إضافته من تلقاء نفسه

لا يتم استنتاج أي من التالي من هذا القسم وحده:

```text
Barn.actual_battery_count as manual input
Barn.actual_cage_count as manual input
Barn.occupied_cage_count as manual input
Barn.available_cage_count as manual input
Barn.current_animal_count as manual input
Fixed actual-capacity formula
One rabbit per cage assumption
Activity-specific workflows
Automatic activity changes
Settings-profile merge precedence
Settings-profile versioning not explicitly approved
Ventilation equipment inventory
Cooling equipment inventory
Heating equipment inventory
Environmental setpoints / sensors / control logic
Automatic cascade of Barn status to Batteries/Cages
Automatic relocation of animals on status change
Cascade delete of Batteries/Cages
```

ولا يجب:

- نسخ أسماء الأنشطة أو أنظمة البيئة كنصوص حرة بدل العلاقات المرجعية.
- استخدام Planned Capacity كبديل للإشغال الفعلي.
- اعتبار خلو العنبر من الحيوانات دليلًا على أن جميع البطاريات أصبحت غير تشغيلية.
- اختراع ترتيب أولوية بين ملفات إعدادات التشغيل المتعددة.

---

## 8. المخرجات المطلوبة من وكيل الـRequirements

بعد قراءة ملف الإجابات مع هذا الدليل، يجب أن تنتج Requirements تغطي على الأقل:

1. **Barn Entity Scope** — العنبر كوحدة مكانية وتنظيمية داخل Farm.
2. **Supported Barn Data** — الحقول والعلاقات والقيم التخطيطية المعتمدة.
3. **Create-time Requiredness** — مع تجميد الجزء المتعارض فقط حتى يصدر قرار صريح.
4. **Farm Relationship** — كل Barn يتبع Farm واحدة.
5. **Code Strategy & Uniqueness** — توليد تلقائي + فريدة عالمية للكود + فريدة الاسم داخل المزرعة.
6. **Operational Activities** — Required on Create + Multiple Activities.
7. **Settings Profiles** — توقيت الإلزام وعدد الملفات النشطة، مع عدم اختراع Merge/Precedence Rules.
8. **Environmental Relationships** — Ventilation / Cooling / Heating كعلاقات مستقلة مع Cardinality المعتمدة.
9. **Barn Status Model** — القيم الثابتة Active / Stopped / Maintenance.
10. **Planned vs Actual Capacity** — تخطيطي محفوظ مقابل فعلي مشتق.
11. **Planned vs Actual Battery Count** — تخطيطي محفوظ مقابل عدد فعلي مشتق.
12. **Derived Occupancy Metrics** — الأقفاص والإشغال والحيوانات الحالية كمعلومات محسوبة.
13. **Status Transition Guards** — شرط الإخلاء + شرط حالة البطاريات، كشرطين متراكمين.
14. **Lifecycle / Delete Policy** — تعطيل فقط مع حماية التاريخ.
15. **Usage Boundaries** — عدم نقل منطق Workflow أو Settings أو Equipment Inventory إلى Barn من دون Requirements مستقلة.

---

## 9. نتيجة المراجعة الحالية

### ما يمكن اعتماده

باقي القرارات الـ22 ترسم نموذجًا متماسكًا للعنبر من حيث:

- هويته وعلاقته بالمزرعة.
- الأنشطة المتعددة.
- بنية الحالات.
- السعة والعدد التخطيطي مقابل الفعلي.
- الإشغال المشتق.
- شروط الانتقال للحالات غير التشغيلية.
- حماية السجل التاريخي.

### ما يحتاج حسمًا لاحقًا

يوجد **تعارض واحد مركزي** في Requiredness عند إنشاء العنبر للعلاقات الأربع التالية:

```text
settings_profile
ventilation_system
cooling_system
heating_system
```

ولا يجب لوكيل الـRequirements حسمه أو تجاهله تلقائيًا.
