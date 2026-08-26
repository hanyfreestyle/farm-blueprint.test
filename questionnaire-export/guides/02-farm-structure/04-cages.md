# 2.4 بيانات القفص / العين — دليل تفسير الإجابات

> **الحالة:** مسودة أولى مبنية على الإجابات الحالية للمراجعة — توجد نقطة تعارض عابرة بين الأقسام تخص التمثيل الفيزيائي  
> **ملف الإجابات المقابل:** `questionnaire-export/answers/02-farm-structure/04-cages.md`  
> **Question Keys المغطاة:** 25 / 25

---

## 1. الغرض من هذا الملف

هذا الملف يفسر **المعنى الوظيفي والتقني للإجابات المعتمدة فعليًا** في قسم `بيانات القفص / العين` ضمن `هيكل المزرعة`.

هو ليس مصدرًا بديلًا للإجابات، ولا يغير أي قرار موجود في ملف الإجابات.

```text
answers/02-farm-structure/04-cages.md
= ماذا قررنا؟

                    ↓ تفسير

guides/02-farm-structure/04-cages.md
= ماذا تعني هذه القرارات تقنيًا؟ وما حدودها؟
```

تم بناء التفسير وفق منهج `docs/questionnaire-guide/` مع مراجعة سياق المشروع، وقرارات البطارية، ومرجع `تصور_مشروع_الارانب.md` الذي يثبت أن القفص / العين هو **الموقع الفعلي المباشر للحيوان** داخل المزرعة.

---

## 2. الهدف الوظيفي من الكيان

`Cage / Cell` يمثل **أصغر موقع هيكلي فعلي يستخدم في التسكين داخل المزرعة**.

الصورة الأساسية:

```text
Farm
└── Barn
    └── Battery
        └── Cage / Cell
            └── Animal Occupancy / Operational History
```

القفص ليس سجلًا مستقلًا ينشئه المستخدم يدويًا، بل ينتج من التكوين الهيكلي للبطارية ويحتفظ بهوية وموقع ثابتين حتى تظل كل الحركات والأحداث التاريخية قابلة للتفسير.

يجب الفصل بين عدة مفاهيم:

```text
Cage Identity
= code + battery + structural position + QR identity

Physical Specifications
= المواصفات الفيزيائية الفعلية للقفص وفق القرار الحالي

Operational Usage
= الاستخدام التشغيلي الحالي

Local Status
= الحالة التشغيلية المحلية للقفص

Occupancy
= الحيوانات الموجودة فعليًا من الحركات

Housing Eligibility
= هل القفص مؤهل الآن لاستقبال حيوان جديد

Sanitation / Maintenance
= أحداث وتشغيل له تاريخ مستقل
```

ولا يجوز دمج هذه الأبعاد في Status واحد أو حقل واحد.

---

## 3. ملخص القرارات المعتمدة من الإجابات الحالية

الصورة الحالية التي تحسمها الإجابات هي:

```text
Cage
├── Creation
│   ├── generated exclusively from Battery structure
│   └── no independent Create action
│
├── Identity
│   ├── code → globally unique
│   ├── QR → required + generated from Cage identity
│   ├── Battery relation
│   └── structural position / coordinates
│
├── Activation
│   ├── explicit activation Action
│   └── activation is recorded historically
│
├── Immutable Identity after Activation
│   ├── code
│   ├── battery
│   ├── structural position
│   ├── structural coordinates
│   └── QR identity
│
├── Local Lifecycle
│   ├── active
│   ├── stopped
│   ├── maintenance
│   └── retired
│   └── statuses are fixed
│
├── State Changes
│   ├── Actions only
│   ├── full history
│   └── independent permissions
│
├── Physical Definition
│   └── direct physical specifications per Cage
│
├── Operational Usage
│   ├── one managed CageUsage
│   ├── constrained by Battery / Barn
│   └── change only when empty + effectively ready + history preserved
│
├── Capacity
│   ├── based on physical profile/specifications
│   └── pre-activation override allowed
│
├── Occupancy
│   ├── current occupancy derived from housing/movement history
│   └── available capacity derived
│
├── Housing Eligibility
│   └── calculated from local + parent + capacity + usage + maintenance + sanitation + settings rules
│
├── Nest Box
│   └── installed/removed through Workflow, not fixed Cage identity
│
└── History Timeline
    └── generation, activation, status, housing, movement, usage, maintenance, sanitation, retirement
```

---

## 4. إنشاء القفص وهويته

### `cage.creation_source`

القرار الحالي يثبت أن القفص **ينشأ حصريًا من تكوين البطارية**.

المسار المعتمد:

```text
Battery Structural Configuration
        ↓
Preview / Generate Cages
        ↓
Cage Records
```

ولا يوجد:

```text
Create Cage independently
```

من شاشة الأقفاص.

هذا يتوافق مع قرارات قسم البطارية التي تعتبر التكوين الهيكلي مصدر عدد الأقفاص ومواقعها.

### `cage.direct_delete_policy`

الحذف المباشر من شاشة القفص ممنوع.

هذا لا يتعارض مع إمكانية **تصحيح تكوين البطارية قبل وجود أي تاريخ تشغيلي**؛ تلك العملية تتم من مستوى البطارية وقد تؤدي إلى حذف وإعادة توليد أقفاص غير مستخدمة حسب القواعد المعتمدة هناك.

بعد دخول القفص في التاريخ التشغيلي، تظل هويته جزءًا من السجل التاريخي ولا يجوز التعامل معه كسجل Disposable.

### `cage.code_unique_scope`

كود القفص يجب أن يكون:

```text
Globally Unique
```

أي فريدًا على مستوى النظام بالكامل، وليس فقط داخل البطارية أو المزرعة.

لا يحدد هذا السؤال Format الكود نفسه؛ شكل الكود وتركيبه يأتي من قواعد ترقيم البطارية المعتمدة.

### `cage.qr_code_strategy`

لكل قفص QR Code إلزامي يولد تلقائيًا من **هوية / كود القفص**.

الـQR يمثل القفص نفسه، وليس الحيوان الموجود داخله.

إذن:

```text
Cage QR Identity
≠ Animal Identity
```

تغيير الحيوان الموجود في القفص لا ينتج QR جديدًا للقفص.

---

## 5. التفعيل وقفل الهوية

### `cage.activation_model`

توليد القفص لا يعني دخوله التشغيل تلقائيًا.

المسار المعتمد:

```text
Generated
→ Pending / not operational yet
→ Explicit Activation Action
→ Operational Cage
```

التفعيل Action صريح وله سجل تاريخي.

### `cage.identity_lock_after_activation`

بعد التفعيل تصبح بيانات الهوية الأساسية غير قابلة للتعديل المباشر.

### `cage.immutable_identity_fields`

البيانات المقفلة هي:

```text
code
battery
structural_position
structural_coordinates
qr_identity
```

هذه البيانات تمثل **هوية وموقعًا تاريخيًا** وليست مجرد بيانات وصفية.

إذا تغير الواقع الفيزيائي بصورة جوهرية بعد وجود تاريخ، لا يعاد تحرير القفص ليبدو كأنه كان دائمًا في الموقع الجديد؛ تطبق قواعد إعادة تكوين / تقاعد البطارية وحماية التاريخ.

---

## 6. الحالة التشغيلية للقفص وActions

### `cage.operational_statuses`

الحالات المحلية المعتمدة هي:

```text
active
stopped
maintenance
retired
```

ولا تشمل:

```text
occupied
empty
needs_sanitation
```

لأن الإشغال والتطهير أبعاد مستقلة.

### `cage.operational_status_management`

الحالات السابقة **قيم ثابتة داخل النظام** وليست Master Data قابلة للإضافة من لوحة التحكم.

هذا مهم لأن لكل حالة معنى وانتقالات وقواعد، وليس مجرد Label وصفي.

### `cage.status_transition_method`

الحالة لا تعدل مباشرة من Dropdown مستقل.

التغيير يتم فقط عبر:

```text
Operational Action
→ validation
→ permission check
→ history record
→ resulting current state
```

### `cage.supported_actions`

القفص يدعم Actions مسجلة لـ:

```text
activate
stop
return_to_service
start_maintenance
complete_maintenance
retire
start_sanitation
complete_sanitation
```

وجود `start_sanitation` و`complete_sanitation` ضمن Actions القفص لا يعني أن التطهير مجرد Status؛ التطهير يظل Workflow / عملية تشغيلية مستقلة ولها سجلها، لكن يمكن تشغيلها من سياق القفص.

### `cage.action_audit_fields`

كل Action يجب أن يدعم Audit Trail يشمل:

```text
action_type
from_status
to_status
performed_at
performed_by
reason
notes
parent_operation_reference
```

وجود `parent_operation_reference` يسمح بربط Action محلي على القفص بعملية جماعية أصلية مثل تطهير مجموعة أقفاص أو بطارية كاملة.

### `cage.action_permission_rule`

صلاحيات Actions القفص مستقلة.

لا يجوز افتراض أن من يستطيع مشاهدة القفص أو تعديله وصفيًا يستطيع بالضرورة:

- إيقافه.
- إدخاله الصيانة.
- إعادته للخدمة.
- تقاعده.
- بدء / إكمال التطهير.

تفصيل الأدوار ومن يملك كل Permission لا يحسمه هذا القسم.

### `cage.current_state_source`

الحالة الحالية المعروضة هي نتيجة آخر Transition صحيح ضمن التاريخ الكامل:

```text
Action History
→ Current Local State
```

يمكن تقنيًا الاحتفاظ بـSnapshot للحالة الحالية للأداء، لكن لا يجوز أن تصبح هذه القيمة مصدر حقيقة مستقلًا قابلًا للتعديل بعيدًا عن الأحداث التي أنشأتها.

---

## 7. المواصفات الفيزيائية والاستخدام التشغيلي

### `cage.physical_profile_strategy`

القرار الحالي هو:

```text
per_cage_physical_specs
```

أي أن الاختلافات الفيزيائية للقفص تمثل **كمواصفات مباشرة على القفص نفسه**، وليس من خلال اختيار `CagePhysicalType` مستقل لكل قفص.

المقصود هنا دعم واقع أن قفصًا بعينه قد تكون له مواصفات فيزيائية فعلية يجب تسجيلها.

شكل هذه المواصفات التفصيلي لم يحسمه هذا السؤال وحده، ولا يجوز اختراع قائمة خصائص غير معتمدة.

### `cage.operational_usage_model`

الاستخدام التشغيلي الحالي للقفص يمثل بعلاقة واحدة مع `CageUsage` مُدارة:

```text
Cage → one current CageUsage
```

ويجب أن يكون الاستخدام المختار متوافقًا مع ما تسمح به البطارية / العنبر.

`CageUsage` نفسها Master Data مستقلة، ولا يتم إعادة إنشاء قائمة استخدامات داخل سجل القفص.

### `cage.usage_change_policy`

تغيير الاستخدام بعد التشغيل مسموح فقط من خلال Action عندما:

```text
Cage is empty
AND
Cage is effectively ready for reuse
AND
Operational settings allow the change/use
AND
History is preserved
```

إذن:

```text
Empty ≠ Ready
```

وقد يكون القفص فارغًا لكنه غير جاهز بسبب:

- صيانة.
- تطهير.
- فترة انتظار.
- حالة الأب.
- قاعدة تشغيلية أخرى.

ولا يجوز تغيير الاستخدام الحالي مباشرة بما يمحو الاستخدامات التاريخية السابقة.

---

## 8. السعة والإشغال والإتاحة الفعلية

### `cage.capacity_strategy`

القرار الحالي:

```text
from_physical_profile_with_pre_activation_override
```

وبالقراءة مع `cage.physical_profile_strategy = per_cage_physical_specs`، يفسر ذلك حاليًا بأن السعة تأتي من **المواصفات الفيزيائية المسجلة للقفص**، مع السماح بتعديل / Override قبل التفعيل.

بعد التفعيل لا يجوز افتراض أن السعة تظل قابلة للتحرير المباشر إذا كان تعديلها سيغير معنى الموقع التشغيلي أو تاريخه؛ أي تغيير بعد التشغيل يجب أن يخضع للـRequirements النهائية الخاصة بحماية الهوية والتاريخ.

هذه الإجابة تثبت أيضًا أن:

```text
Cage Capacity ≠ Always 1
```

ولا يجوز افتراض أن كل قفص يستوعب أرنبًا واحدًا، خصوصًا مع استخدامات مثل الفطام أو التسمين.

### `cage.occupancy_source`

الإشغال الحالي لا يدخل يدويًا.

مصدر الحقيقة:

```text
Housing / Movement History
        ↓
Current Occupancy
        ↓
Available Capacity
```

إذن:

```text
current_occupancy = Derived
available_capacity = Derived
```

ولا يجوز وجود عداد يدوي مستقل يناقض الحركات.

### `cage.housing_eligibility_factors`

كون القفص فارغًا لا يكفي للسماح بالتسكين.

الإتاحة الفعلية يجب أن تراعي معًا:

```text
local_status_allows_operation
battery_effectively_available
barn_effectively_available
capacity_available
usage_compatible
no_maintenance_block
sanitation_ready
operational_settings_allow_housing
```

الصورة المفاهيمية:

```text
Housing Eligible
=
Local Cage State OK
AND Parent Battery Available
AND Parent Barn Available
AND Capacity Available
AND Usage Compatible
AND No Maintenance Block
AND Sanitation Ready
AND Operational Settings Allow Housing
```

هذا **Business Decision Conceptual** وليس Formula قاعدة بيانات نهائية.

كما لا يجوز اعتبار:

```text
Local Status = Active
```

مرادفًا لـ:

```text
Housing Eligible = true
```

لأن الأب أو السعة أو التطهير أو الإعدادات قد تمنع الاستخدام.

---

## 9. بيت الولادة

### `cage.nest_box_handling`

بيت الولادة ليس جزءًا ثابتًا من هوية القفص وفق القرار الحالي.

هو تجهيز تشغيلي:

```text
Install Nest Box
→ operational event

Remove Nest Box
→ operational event
```

ويتم التعامل معه من خلال Workflow تجهيز الولادة مع الاحتفاظ بالتاريخ.

إذن لا يجوز اختزال القرار في Boolean ثابت مثل:

```text
cage.has_nest_box = true/false
```

إذا كان هذا الحقل سيمحو تاريخ التركيب والإزالة.

تفاصيل توقيت تركيب بيت الولادة وقواعده تأتي من قسم الحمل / تجهيز الولادة وإعدادات التشغيل، وليس من بيانات القفص وحدها.

---

## 10. Timeline التاريخ التشغيلي للقفص

### `cage.history_timeline`

كل Cage يجب أن يعرض Timeline موحدًا لتاريخه التشغيلي، وليس الحالة الحالية فقط.

الـTimeline واجهة تجميع لمصادر الأحداث الأصلية، وليس نسخة مكررة من كل البيانات داخل جدول القفص.

### `cage.history_event_types`

الأحداث المطلوب ظهورها هي:

```text
generated
activated
status_transitions
housing
animal_movements
usage_changes
maintenance
sanitation
retirement
```

المبدأ:

```text
Original Operational Event
        ↓ reference
Cage Timeline
```

ولا يجوز نسخ Event كامل في أكثر من مصدر حقيقة لمجرد عرضه في الـTimeline.

مثلًا:

- حركة الحيوان مصدرها Workflow التسكين / النقل.
- التطهير مصدره عملية التطهير.
- الصيانة مصدرها Workflow تشغيل وصيانة المواقع.
- Timeline القفص يجمعها ويعرضها في سياق القفص.

---

## 11. العلاقة مع الأقسام الأخرى

### مع البطارية

```text
Battery Structural Configuration
→ generates Cage
```

البطارية تحسم:

- مصدر إنشاء القفص.
- التكوين والموقع الهيكلي.
- توليد الأكواد.
- التوليد الجماعي والمعاينة.
- القفل الهيكلي بعد التاريخ.

القفص يحسم:

- هويته المحلية.
- تفعيله.
- حالته وActionsه.
- استخدامه.
- سعته.
- الإشغال والجاهزية.
- Timeline الخاص به.

### مع CageUsage

`CageUsage` Master Data تحدد قاموس الاستخدامات.

هذا القسم يحدد **كيف يستخدم القفص الفعلي هذا القاموس** ومتى يمكن تغيير الاستخدام.

### مع Workflow التسكين والحركات

```text
Cage = location definition
Housing / Movement = what happened at that location
```

القفص لا يخزن تاريخ الحيوانات داخله كحقول يدوية؛ التاريخ ينتج من الحركات.

### مع الصيانة والتطهير

الصيانة والتطهير Events / Workflows لها سجلاتها، بينما Cage يحتفظ بالحالة والـTimeline والإتاحة الناتجة عنها.

### مع Operational Settings

الإعدادات تحكم قواعد مثل:

- التوافق مع الاستخدام.
- شروط الجاهزية للتسكين.
- التطهير وفترة الانتظار.
- ما إذا كان القفص جاهزًا لإعادة الاستخدام.

لا تُثبت هذه القواعد كقيم علمية جامدة داخل Cage ما لم تحسمها Settings.

---

## 12. نقطة تعارض عابرة بين الأقسام — التمثيل الفيزيائي

يوجد قراران لا يمكن اعتبارهـما متطابقين حاليًا:

في قسم Master Data `أنواع الأقفاص الفيزيائية` يوجد قرار بوجود كيان مستقل:

```text
CagePhysicalType
```

مع منع تكرار الاسم والتعطيل بدل الحذف.

لكن قسم القفص الحالي يقرر:

```text
cage.physical_profile_strategy = per_cage_physical_specs
```

أي أن المواصفات الفيزيائية تسجل مباشرة على القفص **دون قائمة أنواع مستقلة مستخدمة كمرجع للقفص**.

كما أن دليل `CagePhysicalType` نفسه يسجل تعارضًا سابقًا بين:

```text
fields / required_fields
vs
attribute_model = name_description_only
```

### الجزء غير المحسوم

لا يمكن حاليًا إغلاق Requirement نهائي يقول أيًا من التالي دون قرار لاحق:

```text
Cage → CagePhysicalType relationship is required
```

أو:

```text
Cage has no CagePhysicalType relationship at all
```

أو تحديد أين تعيش نهائيًا:

- dimensions
- physical_features
- default_capacity

### ما يمكن اعتماده الآن

يمكن اعتماد أن:

1. **المواصفات الفيزيائية منفصلة عن الاستخدام التشغيلي.**
2. القرار الحالي داخل Cage يميل إلى المواصفات المباشرة لكل قفص.
3. `CageUsage` يظل كيانًا مستقلاً ولا يتأثر بهذا التعارض.
4. لا يجوز حذف `CagePhysicalType` أو اختراع استخدام له من هذا الدليل وحده.

### القرار المطلوب لاحقًا

عند مراجعة التعارض يجب حسم أحد الاتجاهات بشكل منظم، مثل:

```text
A) CagePhysicalType مرجع فعلي للقفص ويحمل أو يساهم في المواصفات

أو

B) المواصفات الفيزيائية مباشرة على Cage / Battery structure،
   ويعاد تقييم دور CagePhysicalType نفسه
```

هذا الملف لا يختار اتجاهًا من تلقاء نفسه.

---

## 13. فحص الاتساق الداخلي للقسم

**الحالة الداخلية:** الإجابات الـ25 متوافقة في جوهرها.

أهم نقاط الاتساق:

- إنشاء القفص من البطارية فقط متوافق مع منع الحذف المباشر وحماية الهوية.
- التفعيل الصريح متوافق مع قفل الهوية بعد التفعيل.
- تغيير الحالة عبر Actions فقط متوافق مع اشتقاق Current State من تاريخ الـActions.
- الحالات ثابتة، وبالتالي يمكن بناء قواعد انتقال واضحة دون تغييرات إدارية عشوائية.
- `CageUsage` واحد حالي ومقيد بالأب متوافق مع وجود Master Data مستقلة للاستخدامات.
- تغيير الاستخدام عند الفراغ والجاهزية متوافق مع نموذج Housing Eligibility.
- السعة ليست ثابتة 1، والإشغال مشتق من الحركات؛ لذلك Available Capacity يمكن اشتقاقها بصورة صحيحة.
- بيت الولادة كحدث Workflow متوافق مع الفصل بين القفص الفيزيائي وبين دورة الحمل والولادة.
- Timeline موحد متوافق مع Audit Actions والحركات والصيانة والتطهير.

**الملاحظة الوحيدة المهمة خارج الاتساق الداخلي** هي نقطة `CagePhysicalType` الموضحة في القسم السابق.

---

## 14. ما لا يجوز استنتاجه

لا يتم استنتاج أي من التالي من إجابات القفص الحالية وحدها:

```text
Manual Cage Create
Direct hard delete from Cage screen
Editable Cage identity after activation
Editable structural position after activation
Editable QR identity after activation
Manual current occupancy
Manual available capacity
Occupied / Empty as Cage lifecycle statuses
Sanitation as a simple Cage status only
Automatic usage change based only on occupant
One rabbit per Cage
Automatic housing eligibility from Local Status alone
Automatic Cascade status changes from Barn/Battery to Cage
A fixed Nest Box permanently attached to female Cage
Direct current status edits without Actions
A specific Cage code format not approved in Battery numbering rules
```

كما لا يجوز:

- اختراع مواصفات فيزيائية لم يحددها مصدر معتمد.
- استخدام `CagePhysicalType` كعلاقة فعلية على القفص قبل حسم التعارض العابر.
- استخدام اسم الاستخدام التشغيلي كبديل عن قواعد التوافق الفعلية.
- تغيير تاريخ القفص بإعادة كتابة Current State أو Current Usage فقط.
- اعتبار القفص النشط والفارغ مؤهلًا تلقائيًا للتسكين.

---

## 15. المخرجات المطلوبة للـRequirements

بعد قراءة هذا الدليل مع ملف الإجابات النهائي، يجب أن تنتج Requirements تغطي على الأقل:

1. **Cage Structural Scope** — القفص كموقع فعلي داخل Battery.
2. **Creation Source** — التوليد من Battery فقط ومنع Create مستقل.
3. **Direct Delete Protection** — منع الحذف المباشر مع احترام قواعد التصحيح قبل التاريخ على مستوى Battery.
4. **Global Code Uniqueness** — كود القفص فريد على مستوى النظام.
5. **QR Identity** — QR إلزامي ناتج من هوية القفص وليس الحيوان.
6. **Explicit Activation** — التفعيل Action تاريخي مستقل عن التوليد.
7. **Immutable Identity** — حماية الكود والبطارية والموقع والإحداثيات وهوية QR بعد التفعيل.
8. **Fixed Cage Lifecycle Statuses** — Active / Stopped / Maintenance / Retired.
9. **Action-driven Transitions** — تغيير الحالة عبر Actions فقط.
10. **Action Audit Trail** — الحقول الثمانية المعتمدة لكل Action.
11. **Action Permissions** — صلاحيات مستقلة للإجراءات.
12. **Current State Source** — الحالة الحالية نتيجة التاريخ الصحيح وليست قيمة مستقلة قابلة للتحرير.
13. **Physical Specification Model** — المواصفات المباشرة لكل Cage وفق القرار الحالي، مع إبقاء تعارض `CagePhysicalType` مفتوحًا حتى الحسم.
14. **CageUsage Relationship** — استخدام واحد حالي من Master Data ومقيد بما تسمح به Battery/Barn.
15. **Usage Change Workflow** — تغيير الاستخدام فقط عند الفراغ والجاهزية مع حفظ التاريخ.
16. **Capacity Source** — السعة من الملف/المواصفات الفيزيائية مع Override قبل التفعيل.
17. **Derived Occupancy** — الإشغال والأماكن المتاحة من حركات التسكين والنقل.
18. **Housing Eligibility** — دمج العوامل الثمانية المعتمدة دون اختزالها في الفراغ أو الحالة المحلية.
19. **Nest Box Workflow** — التركيب والإزالة أحداث تشغيلية وليست خاصية ثابتة للهوية.
20. **Unified Cage Timeline** — عرض الأحداث التسعة المعتمدة مع الرجوع لمصادرها الأصلية.
21. **History Preservation** — عدم إعادة كتابة الماضي عند تغير الحالة أو الاستخدام أو الصيانة أو التطهير.
22. **Cross-section Conflict Handling** — عدم إغلاق Schema التمثيل الفيزيائي قبل حسم العلاقة مع `CagePhysicalType`.

---

## 16. الخلاصة التنفيذية

قسم `بيانات القفص / العين` يحسم القفص باعتباره **موقعًا هيكليًا دائم الهوية وتاريخيًا**، وليس مجرد خانة إشغال حالية.

المسار التشغيلي الأساسي يصبح:

```text
Battery Structure
→ Generate Cage
→ Explicit Activation
→ Immutable Cage Identity
→ Action-driven Lifecycle
→ Housing / Movement History
→ Derived Occupancy & Availability
→ Maintenance / Sanitation / Usage Changes
→ Unified Cage Timeline
→ Retire while preserving history
```

الإجابات متماسكة داخليًا وتبني نموذجًا قويًا لحماية التاريخ. النقطة الوحيدة التي يجب أن تبقى مفتوحة للمراجعة هي **مكان تمثيل النوع / المواصفات الفيزيائية** بالنسبة إلى `CagePhysicalType` الموجود في Master Data.
