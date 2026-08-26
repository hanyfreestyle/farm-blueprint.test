# 4.4 التلقيح وإدارة المحاولات — دليل تفسير الإجابات

> **الحالة:** مسودة أولى مبنية على الإجابات الحالية للمراجعة — توجد نقطتا تعارض صريحتان مع المرجع الوظيفي تخصان بنية تكرار التلقيح وحماية الأبوة عند استخدام أكثر من ذكر  
> **ملف الإجابات المقابل:** `questionnaire-export/answers/04-workflow/04-matings.md`  
> **Question Keys المغطاة:** 12 / 12

---

## 1. الغرض من هذا الملف

هذا الملف يفسر **المعنى الوظيفي والتقني للإجابات المعتمدة فعليًا** في قسم `التلقيح وإدارة المحاولات` ضمن `الحركات ودورة التشغيل الفعلية`.

هو ليس مصدرًا بديلًا للإجابات، ولا يغير أي قرار موجود في ملف الإجابات.

```text
answers/04-workflow/04-matings.md
= ماذا قررنا؟

                    ↓ تفسير

guides/04-workflow/04-matings.md
= ماذا تعني هذه القرارات تقنيًا؟ وما حدودها؟
```

القاعدة المعمارية الأساسية لهذا القسم:

```text
Production Group / Assigned Male
= تنظيم القطيع → 3.5

Mating Event
= عملية التلقيح التي حدثت فعليًا

Mating Attempt
= محاولة قد تضم عملية تلقيح أو أكثر

Reproductive Cycle
= الإطار الأوسع الذي ترتبط به المحاولات ثم الحمل والولادة والبطن

Readiness / repetition / timing / male-use rules
= Settings

Pregnancy Check
= Workflow 4.5
```

ويجب الحفاظ على الفصل التالي:

```text
Assigned Male
≠
Actual Male Used

Mating Event
≠
Mating Attempt
≠
Reproductive Cycle

Mating Result
≠
Pregnancy Result
```

---

## 2. الصورة الوظيفية الحالية

الإجابات الحالية ترسم النموذج التالي:

```text
Female enters mating workflow
        ↓
First actual mating starts Reproductive Cycle automatically
        ↓
Reproductive Cycle
└── Mating Attempt
    ├── First Mating
    └── Second Mating
        ↓
Attempt completes automatically when configured rules are met
        ↓
Waiting for Pregnancy Check
```

مع دعم:

- تسجيل الذكر المستخدم فعليًا في كل عملية.
- عرض الذكر المخصص كاقتراح فقط.
- إعادة فحص القرابة وقت التنفيذ.
- الاحتفاظ بنتيجة كل عملية تلقيح.
- إلغاء محاولة بدأ تنفيذها مع الحفاظ على التاريخ.
- عدم فتح محاولة جديدة قبل إغلاق السابقة.

---

## 3. تفسير Question Keys

### 3.1 `mating.structure_model`

القرار:

```text
cycle_attempts_mating_events
```

أي أن البنية المعتمدة هي:

```text
Reproductive Cycle
    ↓
Mating Attempt(s)
    ↓
Mating Event(s)
```

هذا قرار معماري مهم لأنه يسمح بفصل:

- الدورة الإنتاجية الكاملة للأنثى.
- محاولة التلقيح الحالية.
- كل تنفيذ ميداني للتلقيح.

ولا يجوز دمج هذه المستويات في Record واحد يفقد القدرة على تتبع المحاولات والنتائج تاريخيًا.

---

### 3.2 `mating.reproductive_cycle_start_model`

القرار:

```text
auto_on_first_mating
```

أي أن النظام لا يحتاج من المستخدم إلى إنشاء دورة الإنتاج يدويًا قبل التلقيح؛ تنشأ الدورة تلقائيًا عند أول تلقيح فعلي في المسار الجديد.

لكن يوجد حد تفسير مهم:

نتائج حدث التلقيح المعتمدة تتضمن:

```text
mating_performed
female_refused
not_completed_other_reason
```

والمرجع الوظيفي يقرر أن التلقيح الذي **لم يحدث فعليًا** لا ينبغي أن يبدأ منه تلقائيًا توقيت فحص الحمل.

لذلك لا يجوز افتراض أن مجرد إنشاء Record بنتيجة `female_refused` أو `not_completed_other_reason` يكفي وحده لبدء Timeline الحمل.

القسم لا يحسم بصورة صريحة هل إنشاء `Reproductive Cycle` نفسه يحدث عند أول محاولة مسجلة أم فقط عند أول `mating_performed`؛ يجب الحفاظ على هذا الفرق عند استخراج المتطلبات النهائية.

---

### 3.3 `mating.attempt_event_cardinality`

القرار الحالي:

```text
fixed_first_second_fields
```

أي أن محاولة التلقيح تمثل حاليًا بموقعي تلقيح ثابتين:

```text
First Mating
Second Mating
```

هذا يعني وظيفيًا أن النموذج الحالي يفترض حدًا أقصى عمليًا قدره عمليتا تلقيح داخل المحاولة الواحدة.

لكن هذا القرار **يتعارض مباشرة مع المرجع الوظيفي الأساسي**؛ المرجع ينص على أن الأفضل ألا توجد حقول ثابتة مثل `first_mating_date` و`second_mating_date` لأن ذلك يفترض مسبقًا حدًا أقصى ثابتًا، ويقترح بدلًا من ذلك أن تمتلك المحاولة مجموعة من Mating Events قابلة للزيادة حسب نظام التشغيل.

كما أن قسم Settings يحتوي سؤالًا مستقلاً:

```text
mating_rules.mating_events_per_attempt
```

لتحديد عدد عمليات التلقيح داخل المحاولة عند اعتماد التكرار.

### نتيجة التعارض

لا يمكن اعتماد Requirement نهائي يجمع في الوقت نفسه بين:

```text
Fixed First + Second fields only
```

و

```text
Configurable number of mating events per attempt without an explicit maximum of 2
```

بدون قرار لاحق.

الحسم المستقبلي يجب أن يختار أحد الاتجاهين:

1. تثبيت الحد الأقصى في النظام عند عمليتين فقط وجعل Settings متوافقة مع ذلك.
2. أو الرجوع إلى نموذج `variable_ordered_mating_events` الأكثر مرونة والمتوافق مع المرجع الوظيفي.

لا يحسم هذا الـGuide الاختيار.

---

### 3.4 `mating.event_record_fields`

سجل عملية التلقيح الفعلية يدعم:

```text
female
actual_male
occurred_at
reproductive_cycle
mating_attempt
sequence_within_attempt
result
female_weight_reference
male_weight_reference
performed_by
notes
```

المعنى الأساسي:

```text
Mating Event
= واقعة تاريخية مستقلة لما حدث بالفعل
```

ويجب تسجيل `actual_male` نفسه، وليس الاكتفاء بالذكر المخصص تنظيميًا.

مراجع وزن الأنثى والذكر يجب أن تشير إلى سجل الوزن الموثوق في 4.3، ولا تنشئ نسخة وزن منافسة داخل حدث التلقيح.

---

### 3.5 `mating.event_result_categories`

النتائج المباشرة المعتمدة:

```text
mating_performed
female_refused
not_completed_other_reason
```

هذه نتائج **تنفيذ عملية التلقيح نفسها**.

ولا يجوز تحويلها إلى نتائج حمل:

```text
mating_performed
≠ pregnant

female_refused
≠ not_pregnant
```

الحمل لا يثبت إلا من Workflow فحص الحمل في 4.5.

---

### 3.6 `mating.execution_context_fields`

شاشة التنفيذ يجب أن تستطيع عرض السياق التالي دون إعادة إدخاله:

#### للأنثى

- الهوية والموقع الحالي.
- العمر.
- آخر وزن وتاريخه.
- السياق الصحي / العزل.
- التاريخ التناسلي والمحاولات السابقة.
- حالة الرضاعة الحالية.

#### للذكر

- الهوية والعمر والسلالة.
- آخر وزن وتاريخه.
- الحالة الصحية والاستخدامات الأخيرة.

#### سياق القرار

- هل الذكر هو المخصص للأنثى / المجموعة.
- نتيجة فحص القرابة.

هذه معلومات **عرض سياقي** وليست بالضرورة Snapshot مكررة داخل كل Mating Event.

---

### 3.7 `mating.assigned_male_execution_role`

القرار:

```text
recommend_assigned_male
```

أي أن الذكر المخصص من تنظيم القطيع يظهر كاقتراح أو أولوية، لكن المستخدم يحدد `actual_male` المستخدم فعليًا.

وهذا متوافق مع قرار القسم 3.5:

```text
Group Assignment
= تنظيم

Actual Mating
= ما حدث فعليًا
```

ولا يجوز إثبات الأبوة من الذكر المخصص وحده.

---

### 3.8 `mating.runtime_kinship_check`

الإجابة: **نعم**.

يجب إعادة فحص القرابة وقت التلقيح الفعلي حتى لو تم التحقق منها عند تكوين المجموعة.

السبب الوظيفي أن المستخدم قد يختار ذكرًا مختلفًا عن المخصص.

لكن هذا السؤال لا يحدد:

- مستويات القرابة.
- طريقة الحساب.
- هل النتيجة Information أو Warning أو Block.
- من يملك صلاحية التجاوز.

هذه القواعد مكانها Settings.

---

### 3.9 `mating.multiple_males_paternity_policy`

الإجابة الحالية: **لا** على السؤال:

> إذا تم استخدام أكثر من ذكر داخل نفس محاولة التلقيح، هل يجب اعتبار الأبوة غير محسومة وعدم اختيار أب تلقائيًا؟

هذه الإجابة تعني أن القسم **لم يعتمد قاعدة تلقائية تجعل الأبوة Unknown / Uncertain عند تعدد الذكور**.

لكن هذا القرار يتعارض صراحة مع المرجع الوظيفي الأساسي، الذي يقرر:

```text
إذا تم استخدام أكثر من ذكر في الفترة التي أدت إلى نفس الحمل
→ هوية الأب غير مؤكدة ما لم توجد وسيلة لإثباته
→ لا يتم اختيار أحد الذكور عشوائيًا
```

كما أن المرجع يعتبر هذه النقطة مهمة لحماية:

- Pedigree.
- تقييم الذكر.
- شجرة العائلة.
- قرارات التزاوج المستقبلية.

### نتيجة التعارض

الإجابة الحالية لا تقدم بديلًا يوضح **كيف يتم إثبات الأب** عند وجود أكثر من ذكر.

لذلك لا يجوز استنتاج أي قاعدة من نوع:

```text
first male = father
last male = father
assigned male = father
user chooses one automatically
```

بدون قرار صريح جديد.

هذه نقطة **Blocking لسلامة النسب** عند تحويل الإجابات إلى Final Requirements إذا كان استخدام أكثر من ذكر ممكنًا فعليًا.

ويجب أيضًا ملاحظة أن السؤال الحالي يتحدث عن **نفس المحاولة**، بينما المرجع الوظيفي أوسع ويتحدث عن أكثر من ذكر خلال الفترة التي أدت إلى نفس الحمل.

---

### 3.10 `mating.attempt_completion_model`

القرار:

```text
auto_complete_by_configured_rules
```

أي أن المستخدم لا يحتاج إلى إغلاق المحاولة يدويًا في الحالة الطبيعية.

عندما تتحقق قواعد المحاولة المحددة في Settings:

```text
Mating phase completed
→ Attempt moves to waiting for pregnancy check
```

لكن هذا السؤال لا يحدد بنفسه:

- عدد التلقيحات المطلوبة.
- الفاصل بينها.
- ما هو التلقيح المرجعي لحساب موعد فحص الحمل.

هذه القواعد تبقى في Settings.

---

### 3.11 `mating.new_attempt_boundary`

القرار:

```text
after_previous_attempt_closed
```

أي أنه لا يسمح بفتح محاولة جديدة لنفس المسار بينما السابقة ما زالت مفتوحة.

المحاولة السابقة يجب أن تغلق بنتيجة أو إلغاء معتمد أولًا.

وهذا يحافظ على وضوح:

- عدد المحاولات.
- نتائج الخصوبة.
- العلاقة بين فحص الحمل والمحاولة التي سببتها.

---

### 3.12 `mating.attempt_cancellation_model`

القرار:

```text
explicit_cancellation_with_history
```

إذن يمكن إلغاء محاولة بدأت بالفعل، لكن الإلغاء حدث تشغيلي تاريخي ولا يمحو ما سبق.

يجب الاحتفاظ على الأقل بمفهوم:

```text
Cancellation reason
Cancellation occurred_at
Performed by
Notes
```

مع بقاء Mating Events المسجلة سابقًا في التاريخ.

ولا يحسم القسم:

- صلاحيات الإلغاء.
- هل يمكن الإلغاء بعد فحص حمل.
- متى يسمح بالتجاوز.
- قواعد تعديل الخطأ التاريخي.

---

## 4. العلاقة مع الأقسام الأخرى

### مع 3.5 تكوين القطيع الإنتاجي

```text
Assigned Male
= تنظيم

Actual Male
= Mating Event
```

وجود الذكر في المجموعة لا يثبت التلقيح ولا الأبوة.

### مع 4.3 الوزن والقياسات

الأوزان المرتبطة بالتلقيح يجب أن تكون References إلى Canonical Weight History، لا قيمًا تاريخية منفصلة متنافسة.

### مع 4.5 فحص الحمل

```text
Mating Attempt completed
→ Pregnancy Check workflow
```

نتيجة فحص الحمل هي التي تحدد نجاح / فشل المحاولة بحسب قواعد القسم التالي، وليس مجرد نجاح تنفيذ التلقيح ميدانيًا.

### مع 3.3 النسب وشجرة العائلة

سلامة Pedigree تعتمد على الذكر المستخدم فعليًا وعلى معالجة صحيحة لحالات تعدد الذكور وعدم اليقين في الأبوة.

### مع Settings 6.5

Settings تحسم:

- جاهزية الأنثى والذكر.
- توقيت التلقيح وإعادة التلقيح.
- عدد التلقيحات داخل المحاولة.
- الفاصل بين التلقيحات.
- قواعد استخدام ذكر مختلف.
- حدود استخدام الذكر.
- التعامل مع القرابة.
- قواعد اكتمال / فشل المحاولة.

ويجب ألا تعيد Settings تعريف بنية Workflow بما يناقض القرارات المعمارية النهائية لهذا القسم.

---

## 5. ما لا يجوز استنتاجه

لا يجوز استنتاج أي من التالي من الإجابات الحالية وحدها:

```text
Fixed scientific mating age
Fixed mating weight
Fixed 6-hour repeat interval
Exactly two mating events as a scientific rule
Maximum male uses per day/week
Automatic father from assigned male
Automatic father from first mating
Automatic father from last mating
Pregnancy from mating_performed result
Automatic pregnancy-check timing from refused mating
Kinship blocking threshold
Authorization rules for override/cancellation
```

كما لا يجوز اختراع حل للتعارضين المسجلين أدناه.

---

## 6. مراجعة الاتساق

### القرارات المتسقة

- `Reproductive Cycle → Attempts → Mating Events` بنية واضحة.
- الدورة تبدأ تلقائيًا من مسار التلقيح بدل Create يدوي منفصل.
- كل عملية تحتفظ بالذكر المستخدم فعليًا.
- التخصيص التنظيمي لا يساوي التلقيح الفعلي.
- فحص القرابة يعاد وقت التنفيذ.
- المحاولة تغلق تلقائيًا وفق Settings.
- لا توجد محاولتان مفتوحتان بالتوازي لنفس المسار.
- الإلغاء يحفظ التاريخ ولا يمحوه.

### التعارض المفتوح الأول — بنية تكرار التلقيح

```text
Current Answer:
fixed_first_second_fields

Functional Reference:
Mating Attempt should own a flexible list of Mating Events
and should not assume first/second fields as a permanent maximum.
```

الأثر:

- Schema.
- Settings compatibility.
- قابلية تغيير عدد مرات التلقيح.
- تاريخ المحاولة وتقاريرها.

### التعارض المفتوح الثاني — تعدد الذكور والأبوة

```text
Current Answer:
multiple_males_paternity_policy = No

Functional Reference:
Multiple possible males for the same pregnancy
→ paternity must remain uncertain unless proven.
```

الأثر:

- Pedigree integrity.
- Family tree.
- Male performance metrics.
- Kinship decisions in future generations.

هذه النقطة أعلى خطورة من مجرد اختلاف في الواجهة، لأنها قد تنتج بيانات نسب خاطئة.

### نقطة تحتاج توضيحًا — بدء الدورة عند عملية غير مكتملة

يجب التفريق بين:

```text
Mating record created
```

و

```text
Actual mating performed successfully as an act
```

لأن `female_refused` و`not_completed_other_reason` لا يمثلان تلقيحًا فعليًا مكتملًا، والمرجع لا يسمح ببناء توقيت فحص الحمل على واقعة لم يحدث فيها تلقيح.

---

## 7. Requirements outputs الحالية

يمكن استخراج Requirements مبدئية آمنة من القسم الحالي:

### REQ-MATING-001
يجب أن يدعم النظام بنية مستقلة لـReproductive Cycle وMating Attempt وMating Event مع روابط واضحة بينها.

### REQ-MATING-002
يجب أن يسجل كل Mating Event الأنثى والذكر المستخدم فعليًا والتوقيت والنتيجة والمنفذ والملاحظات والعلاقات المطلوبة.

### REQ-MATING-003
يجب ألا يستخدم Assigned Male كإثبات تلقائي لحدوث التلقيح أو الأبوة.

### REQ-MATING-004
يجب عرض السياق التشغيلي الحالي للأنثى والذكر وفحص القرابة للمستخدم أثناء تنفيذ التلقيح من البيانات الموجودة بالفعل.

### REQ-MATING-005
يجب إعادة فحص القرابة وقت التلقيح الفعلي، بينما تحدد Settings نتيجة الفحص وما إذا كانت تحذيرًا أو منعًا.

### REQ-MATING-006
يجب أن تغلق محاولة التلقيح تلقائيًا عند تحقق القواعد المعتمدة في Settings وتنتقل إلى انتظار فحص الحمل.

### REQ-MATING-007
لا يجوز بدء محاولة جديدة قبل إغلاق أو إلغاء المحاولة السابقة.

### REQ-MATING-008
يجب دعم إلغاء محاولة التلقيح كحدث تاريخي صريح مع الحفاظ على جميع الأحداث السابقة.

### REQ-MATING-BLOCKED-001
بنية عدد Mating Events داخل المحاولة **غير جاهزة Requirement نهائيًا** حتى يحسم التعارض بين `fixed_first_second_fields` والنموذج المرن الموجود في المرجع وSettings.

### REQ-MATING-BLOCKED-002
سياسة الأبوة عند استخدام أكثر من ذكر **غير جاهزة Requirement نهائيًا** حتى يحسم التعارض بين الإجابة الحالية وقاعدة المرجع التي تعتبر الأبوة غير مؤكدة عند تعدد الذكور دون إثبات.

---

## 8. الخلاصة

القسم يثبت بنية جيدة وواضحة لمسار التلقيح:

```text
Production organization
→ actual mating execution
→ attempt tracking
→ completion
→ pregnancy check
```

ويحافظ بصورة صحيحة على الفصل بين الذكر المخصص والذكر المستخدم فعليًا، وعلى تاريخ المحاولات والإلغاء.

لكن قبل اعتبار القسم مكتملًا نهائيًا من ناحية المتطلبات، يجب حسم نقطتين:

1. هل المحاولة مقيدة دائمًا بتلقيحين فقط، أم تحتوي قائمة Mating Events مرنة حسب Settings؟
2. كيف تسجل الأبوة إذا استخدم أكثر من ذكر في الفترة المرتبطة بالحمل؟

حتى يتم الحسم، يجب إبقاء هاتين النقطتين **مفتوحتين وعدم اختراع Implementation نهائي لهما**.
