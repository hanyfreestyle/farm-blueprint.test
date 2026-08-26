# 3.2 مصدر الحيوان وبداية السجل — دليل تفسير الإجابات

> **الحالة:** مسودة أولى مبنية على الإجابات الحالية للمراجعة  
> **ملف الإجابات المقابل:** `questionnaire-export/answers/03-animal-herd/02-animals.md`  
> **Question Keys المغطاة:** 7 / 7

---

## 1. الغرض من هذا الملف

هذا الملف يفسر **المعنى الوظيفي والتقني للإجابات المعتمدة فعليًا** في قسم `مصدر الحيوان وبداية السجل` ضمن `بيانات الحيوان وتكوين القطيع`.

هو ليس مصدرًا بديلًا للإجابات، ولا يغير أي قرار موجود في ملف الإجابات.

```text
answers/03-animal-herd/02-animals.md
= ماذا قررنا؟

                    ↓ تفسير

guides/03-animal-herd/02-animals.md
= ماذا تعني هذه القرارات تقنيًا؟ وما حدودها؟
```

تم بناء التفسير وفق سياق المشروع والمرجع الوظيفي الحالي، مع الحفاظ على القاعدة المعمارية الأساسية:

```text
Animal Record
= نفس سجل الحيوان المستمر طوال حياته

Animal Source
= من أين جاء الحيوان / كيف بدأ وجوده بالنسبة للمزرعة الحالية

Intake / Housing / Movement
= أحداث Workflow مستقلة
```

---

## 2. الهدف الوظيفي من القسم

هذا القسم لا يعرّف هوية الحيوان نفسها؛ الهوية حُسمت في `3.1 بيانات وهوية الحيوان`.

الغرض هنا هو تحديد:

1. هل الحيوان **مولود داخل المزرعة** أم **قادم من خارج المزرعة الحالية**.
2. إذا كان خارجيًا، ما نوع المصدر.
3. ما البيانات الوصفية التي نحفظها عن المصدر السابق.
4. من أين تبدأ الـTimeline الموثوقة للحيوان عند دخوله من الخارج.
5. كيف نتعامل مع النقل بين مزارع يديرها نفس النظام.

الفصل الأساسي:

```text
Animal Identity
≠
Animal Source
≠
Animal Intake Event
≠
Housing / Movement Event
```

---

## 3. ملخص القرارات المعتمدة من الإجابات الحالية

الصورة الحالية هي:

```text
Animal Source
├── Born in current farm
│   └── source derived automatically from Birth / Litter record
│
└── Outside current farm
    ├── External Purchase
    ├── Inter-farm Transfer
    └── Other

Outside Source Metadata
├── source party
├── source animal code
└── source notes

Inter-farm Transfer inside same system
└── reference original Farm record directly

Pre-entry History
└── system Timeline starts from actual Entry event

Other Source
└── additional description is NOT mandatory
```

---

## 4. تفسير Question Keys

### `animal.source_origin_categories`

**القرار:** يدعم النظام فئتين أساسيتين لبداية مصدر الحيوان:

```text
born_in_farm
outside_current_farm
```

المقصود هو تصنيف أصل الحيوان بالنسبة للمزرعة الحالية، وليس تسجيل حركة الاستقبال أو التسكين.

**لا تستنتج:** أن كل انتقال من مزرعة إلى أخرى ينشئ Animal Record جديدًا.

---

### `animal.outside_source_types`

عندما يكون الحيوان قادمًا من خارج المزرعة الحالية، يجب دعم:

```text
external_purchase
inter_farm_transfer
other
```

هذه قيم **تصنيف للمصدر**، وليست أحداث Workflow مستقلة في حد ذاتها.

مثال:

```text
Source Type = External Purchase
```

لا يساوي تلقائيًا:

```text
Purchase Transaction
Intake Check
Quarantine
Housing
```

هذه العمليات تحسم في أقسامها المتخصصة.

---

### `animal.internal_source_derivation`

**القرار:** الحيوان المولود داخل المزرعة لا يطلب من المستخدم إدخال مصدره يدويًا.

المصدر يستنتج تلقائيًا من:

```text
Birth Record / Litter
        ↓
Animal individual record
```

هذا يمنع تكرار نفس الحقيقة يدويًا ويضمن استمرار العلاقة التاريخية بين الحيوان والبطن والولادة.

لا يعني ذلك أن كل مولود يحصل بالضرورة على سجل فردي في لحظة الولادة؛ توقيت إنشاء السجل الفردي يحسم في Workflow الفطام والتحول إلى التتبع الفردي.

---

### `animal.outside_source_fields`

للحيوان القادم من الخارج يجب دعم البيانات التالية عن مصدره السابق:

```text
source_party
source_animal_code
source_notes
```

التفسير:

- `source_party` → المزرعة / المربي / الجهة المصدر.
- `source_animal_code` → الكود الذي كان الحيوان معروفًا به عند المصدر.
- `source_notes` → معلومات وصفية عن المصدر.

هذه البيانات تصف **المصدر السابق** ولا تستبدل:

- الكود الداخلي الدائم للحيوان.
- تاريخ الدخول.
- حدث الاستقبال.
- حركة التسكين الأولى.

`source_animal_code` لا يصبح هو `animal.internal_code` ولا يغيره.

---

### `animal.interfarm_source_reference`

**القرار:** عند النقل من مزرعة أخرى يديرها نفس النظام، يجب استخدام Relationship مباشرة إلى المزرعة الأصلية المسجلة بدل الاكتفاء باسم نصي.

النموذج المفاهيمي:

```text
Source Farm
    ↓
Animal source / transfer context
    ↓
Current Farm
```

### حد معماري مهم

هذا المفتاح لا يسمح بإنشاء هوية حيوان جديدة لمجرد انتقاله بين مزرعتين داخل نفس النظام.

القاعدة الأعلى في المشروع هي:

```text
One physical Animal
→ One continuing Animal Record throughout its life
```

لذلك إذا كان الحيوان مسجلًا بالفعل في مزرعة A ثم انتقل إلى مزرعة B داخل نفس النظام، يجب الحفاظ على نفس الهوية والسجل، بينما يسجل الانتقال نفسه في Workflow المناسب.

هذا القسم يحدد **مرجع المصدر** فقط، ولا يحسم تفاصيل حدث الخروج من المزرعة الأولى أو الدخول إلى الثانية.

---

### `animal.pre_entry_history_policy`

**القرار:** للحيوان القادم من خارج المزرعة يبدأ التاريخ التشغيلي الذي يديره النظام من **حدث الدخول الفعلي**.

```text
External Animal
Unknown / untracked previous operational history
        ↓
Actual Entry Event
        ↓
System operational history starts here
```

حتى إذا كانت هناك معلومات سابقة متاحة، الإجابة الحالية لم تعتمد إدخال أحداث تشغيلية سابقة على الدخول كـTimeline تاريخية داخل النظام.

لكن هذا لا يمنع الاحتفاظ ببيانات المصدر الوصفية المعتمدة في:

```text
animal.outside_source_fields
```

أي يجب الفصل بين:

```text
Source Metadata
≠
Pre-entry Operational Timeline
```

**لا يجوز** اختراع تلقيحات أو أوزان أو حركات أو سجلات صحية سابقة لم يسجلها النظام قبل الدخول.

---

### `animal.other_source_description_required`

**القرار الحالي:** عند اختيار `other` لا يكون وصف إضافي للمصدر إلزاميًا.

إذن النظام يجب أن يسمح بحفظ:

```text
Source Type = Other
```

حتى لو لم يكتب المستخدم وصفًا إضافيًا.

هذا قرار صريح ويجب الحفاظ عليه، رغم أن ذلك قد يجعل بعض سجلات `Other` أقل تفسيرًا في التقارير.

**لا يجوز** فرض حقل نص إضافي Required من تلقاء نفسه.

وفي المقابل يمكن أن تظل `source_notes` متاحة إذا أراد المستخدم إضافة توضيح، لأنها من البيانات المدعومة للمصدر الخارجي.

---

## 5. العلاقة مع الأقسام الأخرى

### مع `3.1 بيانات وهوية الحيوان`

هذا القسم لا يعيد إنشاء أو تغيير:

```text
internal_code
sex
breed
birth identity
external identifier policy
```

المصدر معلومة مستقلة عن هوية الحيوان.

### مع `3.3 النسب وشجرة العائلة`

مصدر الحيوان الخارجي لا يعني تلقائيًا أن الأب أو الأم معروفان أو مجهولان.

النسب له قواعد ثقة ومصادر مستقلة في قسم النسب.

### مع `4.1 استقبال الحيوان من الخارج وإعادة الإدخال`

```text
3.2 Animal Source
= من أين جاء الحيوان؟

4.1 Animal Intake
= ماذا حدث عند وصوله فعليًا؟
```

الفحص، القبول، الحجر، الوزن عند الدخول، التسكين الأولي وغيرها لا تنتمي إلى `Animal Source`.

### مع `4.2 التسكين والنقل والإخلاء`

الموقع الحالي لا يخزن في مصدر الحيوان، بل ينتج من حركات التسكين والنقل.

### مع `4.15 الخروج من المزرعة وإعادة الدخول`

إذا خرج نفس الحيوان ثم عاد، لا يتم إنشاء Animal جديد أو محو تاريخه السابق.

العلاقة بين واقعة الخروج والعودة تحسم في Workflow، بينما المصدر هنا يظل وصفًا لبداية / أصل السجل وليس بديلًا عن Presence History.

---

## 6. ما لا يجوز استنتاجه من هذا القسم

لا يجوز استنتاج أي من التالي دون Question Key أو Requirement صريح آخر:

```text
Create new Animal Record on every inter-farm transfer
Create new Animal Record on re-entry
Pre-entry weights
Pre-entry health events
Pre-entry reproductive events
Pre-entry housing history
Purchase financial details
Supplier Master Data entity
Mandatory description for Other source
Automatic quarantine rule
Automatic intake acceptance rule
Initial housing location
Source animal code replacing internal animal code
```

كما لا يجوز استخدام `source_party` كنص بديل للمزرعة الأصلية عندما يكون النقل بين مزرعتين معروفتين داخل نفس النظام، لأن الإجابة الحالية اعتمدت Relationship مباشرة للمزرعة الأصلية.

---

## 7. فحص الاتساق

**الحالة:** الإجابات السبع متوافقة داخليًا ولا يوجد تعارض مانع لاعتماد القسم.

التسلسل منطقي:

```text
Origin Category
→ Outside Source Type when applicable
→ Source Metadata
→ Direct Farm Reference for managed inter-farm transfer
→ Entry as start of external operational Timeline
```

النقطة التي يجب الحفاظ عليها في الـRequirements هي أن `inter_farm_transfer` لا يكسر قاعدة استمرار `Animal Record` طوال حياة الحيوان.

كما أن اختيار:

```text
other_source_description_required = false
```

ليس تعارضًا؛ هو قرار صريح بأن وصف «مصدر آخر» غير إلزامي.

---

## 8. المخرجات المطلوبة للـRequirements

بعد قراءة هذا الدليل مع ملف الإجابات يجب أن تغطي المتطلبات على الأقل:

1. **Animal Source Classification** — مولود داخلي أو قادم من خارج المزرعة الحالية.
2. **Outside Source Types** — شراء خارجي / نقل بين المزارع / مصدر آخر.
3. **Internal Birth Derivation** — اشتقاق المصدر الداخلي من Birth / Litter بدون إدخال يدوي مكرر.
4. **Outside Source Metadata** — الجهة المصدر، كود المصدر، الملاحظات.
5. **Inter-farm Reference** — علاقة مباشرة بالمزرعة الأصلية عندما تكون ضمن نفس النظام.
6. **Animal Identity Continuity** — النقل بين المزارع لا ينشئ هوية حيوان جديدة إذا كان الحيوان معروفًا بالفعل للنظام.
7. **External Timeline Boundary** — تاريخ التشغيل للحيوان الخارجي يبدأ من حدث الدخول.
8. **No Invented Pre-entry History** — عدم إنشاء أحداث تاريخية سابقة غير مسجلة.
9. **Other Source Validation** — الوصف الإضافي غير إلزامي حسب القرار الحالي.
10. **Separation from Workflow** — المصدر لا يكرر الاستقبال أو التسكين أو النقل أو الخروج/العودة.

---

## 9. الخلاصة التنفيذية

```text
Animal born in farm
→ Source derived automatically from Birth / Litter

Animal comes from outside current farm
→ classify as Purchase / Inter-farm / Other
→ keep source party + source animal code + notes
→ operational Timeline starts at actual Entry

Inter-farm transfer inside same system
→ reference original Farm
→ preserve same Animal identity / record

Other source
→ description not mandatory
```

القسم يحدد **أصل الحيوان وحد بداية السجل**، لكنه لا يحول المصدر إلى Workflow ولا يعيد إنشاء هوية الحيوان عند كل انتقال.