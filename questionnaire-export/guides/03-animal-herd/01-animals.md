# 3.1 بيانات وهوية الحيوان — دليل تفسير الإجابات

> **الحالة:** مسودة أولى مبنية على الإجابات الحالية للمراجعة  
> **ملف الإجابات المقابل:** `questionnaire-export/answers/03-animal-herd/01-animals.md`  
> **Question Keys المغطاة:** 9 / 9

---

## 1. الغرض من هذا الملف

هذا الملف يفسر **المعنى الوظيفي والتقني للإجابات المعتمدة فعليًا** في قسم `بيانات وهوية الحيوان` ضمن `بيانات الحيوان وتكوين القطيع`.

هو ليس مصدرًا بديلًا للإجابات، ولا يغير أي قرار موجود في ملف الإجابات.

```text
answers/03-animal-herd/01-animals.md
= ماذا قررنا؟

                    ↓ تفسير

guides/03-animal-herd/01-animals.md
= ماذا تعني هذه القرارات تقنيًا؟ وما حدودها؟
```

تم بناء التفسير وفق سياق المشروع والمرجع الوظيفي الحالي. لا يوجد داخل `docs/questionnaire-guide/` دليل قديم مستقل لهذا القسم؛ لذلك يعتمد هذا الملف على **الإجابات الحالية المصدرة + القواعد المعمارية المعتمدة للمشروع + تصور دورة الحيوان**.

---

## 2. الهدف الوظيفي من الكيان

`Animal` يمثل **السجل الدائم لنفس الحيوان طوال حياته داخل النظام**.

هذا السجل يحتفظ بالهوية الأساسية والمعلومات الثابتة أو شبه الثابتة نسبيًا، بينما الأحداث المتغيرة لا تتحول إلى حقول ثابتة يتم تعديلها يدويًا.

الفصل الأساسي:

```text
Animal Identity
= هوية الحيوان الأساسية التي تستمر معه

Animal Events / History
= ما حدث للحيوان عبر الزمن
```

لذلك لا يجب التعامل مع المعلومات التالية كحقول هوية ثابتة داخل `Animal`:

```text
current location
current weight
current production state
current health state
current readiness
current group membership
```

هذه المعلومات يجب أن تأتي من الحركات والسجلات والأحداث والقواعد ذات الصلة.

---

## 3. ملخص القرارات المعتمدة من الإجابات الحالية

الصورة الحالية التي تحسمها الإجابات هي:

```text
Animal
├── Internal Identity
│   ├── internal_code
│   ├── generated automatically
│   ├── globally unique
│   └── remains unchanged for lifetime
│
├── External Identifier
│   ├── supported
│   ├── maximum one identifier at the same time
│   └── supported type currently: ring_number only
│
├── Sex
│   └── may be temporarily unknown
│
├── Breed
│   └── may be temporarily unknown and completed later
│
├── Birth Information
│   ├── confirmed birth date supported
│   └── may remain temporarily unknown
│
├── Photo
└── Distinguishing Marks / Notes
```

ولا توجد مراجعات معلقة في القسم الحالي.

---

## 4. تفسير القرارات الرئيسية

### 4.1 بيانات الهوية المدعومة

`animal.identity_fields` يعتمد:

```text
internal_code
external_identifiers
sex
breed
birth_information
photo
distinguishing_marks
```

هذا يعني أن ملف الحيوان يجب أن **يدعم** هذه المعلومات، لكنه لا يعني أن جميعها إلزامية عند إنشاء كل Animal Record.

القسم الحالي لا يحتوي على Question Key عام يحدد Requiredness لجميع هذه الحقول، لذلك لا يجوز استنتاج أن:

```text
photo is required
external identifier is required
breed is required immediately
confirmed birth date is required immediately
sex must always be known immediately
```

بل يجب قراءة كل جزء مع السؤال المتخصص الخاص به.

### 4.2 الكود الداخلي هو الهوية التشغيلية الأساسية للحيوان

القرارات الثلاثة المرتبطة بالكود متكاملة:

```text
animal.internal_code_strategy = automatic
animal.internal_code_unique_scope = globally_unique
animal.internal_code_lifetime = true
```

إذن النظام يجب أن يولد لكل Animal Record **كودًا داخليًا تلقائيًا، فريدًا على مستوى النظام بالكامل، ويظل ثابتًا مع نفس الحيوان طوال حياته**.

هذا الكود لا يتغير بسبب:

- انتقال الحيوان بين المزارع أو العنابر أو البطاريات أو الأقفاص إذا كانت العمليات المستقبلية تسمح بذلك.
- تغير المرحلة الإنتاجية.
- تغير المجموعة.
- تغير الاستخدام أو المسار التشغيلي.
- الخروج ثم إعادة الدخول عندما تقرر الأقسام الأخرى استمرار نفس Animal Record.

**لا يجوز استنتاج** من هذه الإجابات:

- صيغة الكود.
- Prefix معين.
- تضمين سنة الميلاد أو رقم البطن داخل الكود.
- طول ثابت.
- إمكانية تعديل الكود يدويًا بعد التوليد.

هذه التفاصيل تحتاج Requirement أو قرارًا صريحًا مستقلًا.

---

## 5. المعرف الخارجي للحيوان

### `animal.external_identifier_cardinality`

القرار الحالي:

```text
false
```

أي أن الحيوان **لا يحتفظ بأكثر من معرف خارجي واحد في نفس الوقت** وفق الإجابة الحالية.

هذا لا يلغي الكود الداخلي؛ المعرف الخارجي وسيلة تعريف إضافية، بينما `internal_code` هو هوية النظام الأساسية.

### `animal.external_identifier_types`

القيمة المعتمدة حاليًا:

```text
ring_number
```

أي أن نوع المعرف الخارجي المطلوب دعمه حاليًا هو **رقم الحلقة** فقط.

لا يجب إدخال أنواع أخرى مثل:

```text
external_number
tattoo
other
```

في الـRequirements الحالية لمجرد أنها كانت خيارات متاحة في السؤال.

كما لا يحسم القسم الحالي:

- هل رقم الحلقة إلزامي لكل حيوان.
- نطاق فريدة رقم الحلقة.
- هل يمكن استبدال الحلقة لاحقًا.
- هل يجب حفظ تاريخ معرفات خارجية سابقة إذا تم تغييرها.

هذه النقاط غير محسومة، ولا يجب اختراع قواعد لها.

---

## 6. الجنس وإمكانية عدم الحسم المؤقت

`animal.temporary_unknown_sex = نعم`.

إذن يجب أن يدعم النظام حالة يكون فيها جنس الحيوان **غير محدد مؤقتًا** عندما لا يمكن حسمه بعد.

المعنى ليس إنشاء جنس بيولوجي ثالث دائم، وإنما السماح بمرحلة بيانات مؤقتة مثل:

```text
Sex = Unknown / Not yet determined
```

ثم يتم استكمال الجنس لاحقًا عندما يصبح ممكنًا أو مطلوبًا تشغيليًا.

هذا القسم **لا يحدد متى يجب إجبار المستخدم على حسم الجنس**.

توقيت الحسم وقواعد الفصل أو التشغيل المرتبطة بالجنس تنتمي إلى أقسام الفطام والنمو والتشغيل والإعدادات حسب القرار المعتمد لاحقًا.

---

## 7. السلالة

`animal.breed_requirement` يعتمد:

```text
may_be_unknown_temporarily
```

إذن علاقة الحيوان بالسلالة مدعومة، لكن يسمح بإنشاء أو استمرار Animal Record **بدون معرفة السلالة مؤقتًا**، ثم استكمالها لاحقًا.

السلالة نفسها مرجع إلى `Breed` المعرفة في Master Data، وليست نصًا حرًا جديدًا داخل الحيوان إذا كانت العلاقة المرجعية تغطي الغرض.

يجب الفصل بين:

```text
Animal → Breed
```

وبين:

```text
Animal → Father / Mother / Pedigree
```

فالسلالة لا تستبدل النسب وشجرة العائلة، والنسب له قسم مستقل.

هذا السؤال لا يحسم:

- موعد إلزام تحديد السلالة لاحقًا.
- كيفية التعامل مع السلالة المختلطة أو غير المعروفة خارج ما تسمح به Master Data.
- استنتاج السلالة تلقائيًا من الأب والأم.
- تعديل السلالة بعد وجود سجل تاريخي.

---

## 8. معلومات الميلاد والعمر

`animal.birth_information_methods` يعتمد طريقتين فقط:

```text
confirmed_birth_date
temporarily_unknown
```

إذن النظام يجب أن يدعم حالتين:

1. **تاريخ ميلاد مؤكد** عندما تكون المعلومة معروفة.
2. **معلومات ميلاد غير معروفة مؤقتًا** عندما لا توجد معلومة موثوقة.

الخيارات التالية **لم يتم اعتمادها** في الإجابة الحالية:

```text
estimated_birth_date
estimated_age_at_registration
```

لذلك لا يجوز للـRequirements أن تفرض إدخال تاريخ ميلاد تقديري أو عمر تقديري عند التسجيل كبديل إجباري عن التاريخ غير المعروف.

عندما يوجد تاريخ ميلاد مؤكد، فإن **العمر الحالي قيمة مشتقة** من التاريخ والوقت الحالي، وليس حقلًا ثابتًا يتم تحديثه يدويًا.

هذا يتفق مع قاعدة المشروع العامة بعدم تخزين القيم التي يمكن اشتقاقها من السجل الموثوق.

---

## 9. الصورة والعلامات المميزة

اختيار:

```text
photo
distinguishing_marks
```

يعني أن ملف الحيوان يجب أن يدعم:

- صورة للحيوان.
- علامات تعريفية أو ملاحظات مميزة تساعد على التعرف عليه.

لكن الإجابات الحالية لا تحدد:

- أن الصورة إلزامية.
- عدد الصور.
- تنسيق أو حجم الصورة.
- هل العلامات المميزة نص حر أم بنية منظمة.

لذلك تظل هذه التفاصيل مفتوحة للتصميم الفني دون تحويلها إلى Business Rules غير معتمدة.

---

## 10. العلاقة مع دورة الفطام والتتبع الفردي

المرجع الوظيفي يعتبر الفطام نقطة مهمة لبدء التتبع الفردي للمواليد، وعند إنشاء سجل مستقل لكل أرنب يجب أن يحصل على هوية مستقلة تستمر معه.

لكن هذا لا يعني أن `Animal Record` لا يمكن أن يوجد قبل الفطام في أي سيناريو آخر؛ **مصدر الحيوان وبداية السجل** له قسم مستقل (`3.2`) يحسم متى وكيف يبدأ السجل في السيناريوهات المختلفة.

لذلك هذا القسم يجيب فقط عن:

```text
What identifies the Animal Record?
```

ولا يجيب عن:

```text
When is the Animal Record created?
How did the animal enter the system?
What was its source?
What is its initial housing?
```

---

## 11. ما الذي لا يجب تخزينه كجزء من الهوية الثابتة

بناءً على سياق المشروع، لا يجب أن ينتج هذا القسم حقولًا ثابتة قابلة للتعديل يدويًا مثل:

```text
animal.current_cage_id as historical identity field
animal.current_weight
animal.current_age
animal.current_readiness
animal.current_health_status as source of history
animal.current_production_status as single source of lifecycle history
animal.current_group as immutable identity
```

قد يحتفظ التصميم الفني بـSnapshot أو مرجع حالي لأسباب أداء، لكن **مصدر الحقيقة التاريخي** يجب أن يبقى الأحداث والعلاقات والسجلات المعتمدة في الأقسام الأخرى.

---

## 12. حدود القسم مع الأقسام الأخرى

```text
Animal Identity
→ 3.1 بيانات وهوية الحيوان

Animal Source / Record Start
→ 3.2 مصدر الحيوان وبداية السجل

Father / Mother / Pedigree
→ 3.3 النسب وشجرة العائلة

Initial Herd Starting Point
→ 3.4 القطيع الافتتاحي

Production Group Membership
→ 3.5 تكوين القطيع الإنتاجي

Actual Housing / Movement
→ 4.2

Actual Weight History
→ 4.3

Health Events
→ 4.13

Exit / Re-entry
→ 4.15

Readiness / thresholds / operating rules
→ Settings
```

هذا الفصل يمنع تحميل `Animal` بمجموعة حقول حالية تعيد تمثيل معلومات مصدرها الحقيقي Workflows أو Settings.

---

## 13. فحص الاتساق بين الإجابات

**الحالة:** لا يوجد تعارض داخلي ظاهر يمنع اعتماد القسم.

التوافق واضح في النقاط التالية:

- `internal_code` مدعوم، ثم تم تحديد أنه تلقائي وفريد عالميًا وثابت مدى الحياة.
- `external_identifiers` مدعومة، ثم تم تحديد Cardinality = واحد فقط، والنوع المعتمد = رقم حلقة.
- `sex` مدعوم، مع السماح بعدم تحديده مؤقتًا.
- `breed` مدعومة، مع السماح بأن تكون غير معروفة مؤقتًا.
- `birth_information` مدعومة، مع اعتماد التاريخ المؤكد أو عدم المعرفة المؤقتة دون فرض تقديرات.

### نقاط غير محسومة وليست تعارضات

لا توجد إجابات حالية تحسم:

1. Requiredness الكامل لكل حقل من حقول الهوية عند إنشاء Animal Record.
2. صيغة الكود الداخلي.
3. فريدة رقم الحلقة أو سياسة استبداله.
4. توقيت إلزام حسم الجنس.
5. توقيت إلزام حسم السلالة.
6. هل وكيف يمكن تصحيح تاريخ الميلاد بعد تسجيله.
7. قواعد تعديل السلالة أو الجنس بعد وجود تاريخ تشغيلي.

لا يجب اختراع هذه القرارات داخل هذا الدليل.

---

## 14. المخرجات المطلوبة للـRequirements

عند دمج هذا الدليل مع ملف الإجابات يجب أن تنتج Requirements تغطي على الأقل:

1. **Persistent Animal Record** — نفس الحيوان يحتفظ بنفس السجل والهوية طوال حياته.
2. **Supported Identity Data** — الكود الداخلي، المعرف الخارجي، الجنس، السلالة، معلومات الميلاد، الصورة، العلامات المميزة.
3. **Internal Code Generation** — توليد تلقائي.
4. **Global Code Uniqueness** — عدم تكرار الكود على مستوى النظام بالكامل.
5. **Lifetime Code Stability** — عدم تغيير الكود بسبب الحركات أو تغير المرحلة التشغيلية.
6. **External Identifier Cardinality** — معرف خارجي واحد في نفس الوقت وفق القرار الحالي.
7. **Ring Number Support** — رقم الحلقة هو نوع المعرف الخارجي المعتمد حاليًا.
8. **Temporary Unknown Sex** — السماح بعدم حسم الجنس مؤقتًا.
9. **Breed Relationship** — علاقة إلى Breed Master Data مع السماح بعدم المعرفة مؤقتًا.
10. **Birth Information Model** — تاريخ مؤكد أو غير معروف مؤقتًا، دون فرض تاريخ/عمر تقديري.
11. **Derived Age** — العمر يحسب ولا يخزن كقيمة يدوية ثابتة.
12. **Photo & Distinguishing Marks Support** — دعمها دون اختراع Requiredness أو قيود لم تحسم.
13. **Identity vs History Separation** — الموقع والوزن والجاهزية والحالات التشغيلية المتغيرة تأتي من مصادرها التاريخية وليس من حقول هوية مستقلة.
14. **No Unsupported Assumptions** — النقاط غير المحسومة تبقى خارج Requirements حتى يظهر لها قرار صريح.

---

## 15. الخلاصة التنفيذية

الصورة المعتمدة حاليًا لملف الحيوان هي:

```text
Animal Record
├── stable system-generated globally unique internal code
├── one optional/external identity slot currently supporting ring number
├── sex that may be temporarily unknown
├── breed that may be temporarily unknown
├── confirmed birth date OR temporarily unknown birth information
├── photo support
└── distinguishing marks support
```

والقاعدة الأهم:

```text
Animal Identity stays stable.
Animal life changes are recorded as history elsewhere.
```

بهذا يظل نفس الحيوان قابلًا للتتبع عبر كل المراحل والحركات دون إعادة إنشاء هويته أو طمس تاريخه.
