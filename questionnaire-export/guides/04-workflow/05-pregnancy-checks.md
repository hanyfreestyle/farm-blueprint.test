# 4.5 فحص الحمل ومتابعة الحمل وتجهيز الولادة — دليل تفسير الإجابات

> **الحالة:** مسودة أولى مبنية على الإجابات الحالية للمراجعة — القسم متماسك داخليًا، مع نقطة ترابط مفتوحة مع 4.4 تخص مرجع الأبوة عند استخدام أكثر من ذكر  
> **ملف الإجابات المقابل:** `questionnaire-export/answers/04-workflow/05-pregnancy-checks.md`  
> **Question Keys المغطاة:** 12 / 12

---

## 1. الغرض من هذا الملف

هذا الملف يفسر **المعنى الوظيفي والتقني للإجابات المعتمدة فعليًا** في قسم `فحص الحمل ومتابعة الحمل وتجهيز الولادة` ضمن `الحركات ودورة التشغيل الفعلية`.

هو ليس مصدرًا بديلًا للإجابات، ولا يغير أي قرار موجود في ملف الإجابات.

```text
answers/04-workflow/05-pregnancy-checks.md
= ماذا قررنا؟

                    ↓ تفسير

guides/04-workflow/05-pregnancy-checks.md
= ماذا تعني هذه القرارات تقنيًا؟ وما حدودها؟
```

القاعدة المعمارية الأساسية:

```text
Mating / Attempts
→ 4.4

Actual Pregnancy Check
→ 4.5

Pregnancy Timing / Check Windows / Gestation / Birth Preparation Rules
→ Settings 6.6

Actual Birth
→ 4.6

Exceptional pregnancy/birth situations
→ 4.14
```

ويجب الحفاظ على الفصل التالي:

```text
Pregnancy Check Task
≠
Pregnancy Check Record

Expected Birth Date / Window
≠
Actual Birth Event
```

---

## 2. الهدف الوظيفي من القسم

الغرض من هذا القسم هو تسجيل **ما حدث فعليًا** بعد اكتمال مرحلة التلقيح وانتقال المحاولة إلى انتظار فحص الحمل، ثم إدارة النتيجة الفعلية للحمل والمتابعات المنفذة حتى وقوع الولادة أو ظهور حالة استثنائية.

المسار الحالي:

```text
Mating Attempt waiting for pregnancy check
        ↓
Pregnancy Check
        ├── Positive
        │    ↓
        │  Pregnancy Record
        │    ↓
        │  Follow-up / Birth Preparation
        │    ↓
        │  Wait for Actual Birth or Exception
        │
        ├── Negative
        │    ↓
        │  Close Attempt as Unsuccessful
        │    ↓
        │  Return to Remating Flow by Rules
        │
        └── Uncertain
             ↓
           Keep Attempt Open
             ↓
           Recheck later
```

---

## 3. ملخص القرارات المعتمدة

```text
Pregnancy Check History
└── Multiple independent check records with full history

Pregnancy Check Result
├── positive
├── negative
└── uncertain

Check not performed
└── no Pregnancy Check Record; task remains overdue

Uncertain
└── keep same Mating Attempt open and allow another check

Positive
└── close attempt as successful + auto-create Pregnancy Record

Negative
└── close attempt as unsuccessful + return to remating flow by rules

Pregnancy Record
├── female
├── reproductive_cycle
├── successful_mating_attempt
├── paternity_reference
├── reference_mating_at
├── confirmed_at
├── expected_birth_date
├── expected_birth_window_start
├── expected_birth_window_end
├── current_state
└── notes

Pregnancy Current State
└── derived from events and dates

Pregnancy Follow-up Events
├── general_condition_followup
├── pregnancy_weight_reference
├── birth_site_preparation
├── nest_box_installation
└── approaching_birth_followup

Expected Birth Window reached/exceeded
└── wait for actual birth or exception event
```

---

## 4. تفسير Question Keys والإجابات

### 4.1 `pregnancy_check.history_model`

القرار:

```text
multiple_check_records_with_history
```

كل فحص حمل يتم فعليًا يجب أن ينتج **Pregnancy Check Record مستقلًا** مرتبطًا بنفس محاولة التلقيح عند الحاجة.

مثال:

```text
Attempt #1
├── Check #1 → uncertain
└── Check #2 → positive
```

ولا يجوز استبدال نتيجة الفحص الأول بالثاني أو فقد التاريخ السابق.

هذا يجعل التقارير قادرة على معرفة:

- عدد الفحوص داخل المحاولة.
- الفاصل بين الفحوص.
- وجود نتائج غير مؤكدة قبل التأكيد.
- من نفذ كل فحص ومتى.

---

### 4.2 `pregnancy_check.record_fields`

سجل الفحص الفعلي يدعم:

```text
female
mating_attempt
reproductive_cycle
performed_at
days_from_reference_mating
result
performed_by
notes
```

`days_from_reference_mating` قيمة مشتقة من:

```text
performed_at
-
reference mating date
```

لكن **اختيار أي Mating Event هو المرجع الزمني** لم يحسم هنا، ومكانه Settings 6.6.

لذلك لا يجوز افتراض من هذا القسم أن المرجع هو أول تلقيح أو آخر تلقيح.

---

### 4.3 `pregnancy_check.result_categories`

النتائج الطبية/التشغيلية المعتمدة للفحص المنفذ فعليًا:

```text
positive
negative
uncertain
```

هذه النتائج تخص **فحصًا حدث فعليًا**.

ولا تدخل ضمنها حالة:

```text
not_performed
```

لأن عدم التنفيذ ليس نتيجة Clinical Result.

---

### 4.4 `pregnancy_check.not_performed_handling`

القرار:

```text
no_check_record_task_overdue
```

إذا حل موعد الفحص ولم يتم تنفيذه:

```text
Pregnancy Check Task
→ remains overdue

Pregnancy Check Record
→ NOT created
```

إذن لا يجوز:

- إنشاء نتيجة حمل تلقائية.
- اعتبار الأنثى حاملًا.
- اعتبارها غير حامل.
- إنشاء سجل فحص وهمي فقط لأن الموعد مر.

ويجب الفصل بوضوح بين:

```text
Scheduled / Overdue Task
≠
Actual Clinical Check
```

كيفية معالجة المهمة المتأخرة أو عدم التنفيذ النهائي وصلاحيات ذلك مكانها Workflow المهام / Settings، وليس نتيجة حمل.

---

### 4.5 `pregnancy_check.uncertain_result_flow`

القرار:

```text
keep_attempt_open_allow_recheck
```

إذا كانت النتيجة `uncertain`:

- لا تعتبر المحاولة ناجحة.
- لا تعتبر المحاولة فاشلة.
- لا ينشأ Pregnancy Record مؤكد.
- تبقى نفس `Mating Attempt` مفتوحة.
- يسمح بفحص جديد لاحقًا مرتبط بنفس المحاولة.
- يحتفظ الفحص غير المؤكد في التاريخ.

توقيت إعادة الفحص وعدد مرات الإعادة والتنبيهات لا تحسم هنا؛ مكانها Settings 6.6.

---

### 4.6 `pregnancy_check.positive_result_flow`

القرار:

```text
close_attempt_success_create_pregnancy_automatically
```

عند تسجيل فحص إيجابي:

```text
Positive Pregnancy Check
        ↓
Mating Attempt = Successful
        ↓
Create Pregnancy Record automatically
```

لا يحتاج المستخدم إلى خطوة مستقلة ثانية لتأكيد إنشاء الحمل.

لكن الأتمتة هنا لا تسمح باختراع قيم غير محسومة؛ مثل:

- مدة الحمل.
- تاريخ التلقيح المرجعي.
- نافذة الولادة.
- مواعيد تجهيز بيت الولادة.

هذه تأتي من Settings 6.6 عند اعتمادها.

---

### 4.7 `pregnancy_check.negative_result_flow`

القرار:

```text
close_attempt_unsuccessful_return_to_remating_flow
```

عند النتيجة السلبية:

```text
Negative Pregnancy Check
        ↓
Close current Mating Attempt as unsuccessful
        ↓
Female may return to remating flow according to rules
```

هذا لا يعني بدء محاولة جديدة فورًا بلا شروط.

الجاهزية، فترة الانتظار، عدد المحاولات المسموح بها، التحذيرات، تغيير الذكر وغيرها تنتمي إلى Settings 6.5.

كما أن إغلاق المحاولة لا يحذف:

- أحداث التلقيح السابقة.
- فحوص الحمل.
- الذكور المستخدمة.
- التاريخ الكامل للمحاولة.

---

### 4.8 `pregnancy.record_fields`

سجل الحمل المؤكد يجب أن يوفر المعلومات التالية:

```text
female
reproductive_cycle
successful_mating_attempt
paternity_reference
reference_mating_at
confirmed_at
expected_birth_date
expected_birth_window_start
expected_birth_window_end
current_state
notes
```

#### تصنيف المعنى

- `female` → Relationship إلى Animal.
- `reproductive_cycle` → Relationship إلى الدورة.
- `successful_mating_attempt` → Relationship إلى المحاولة التي أثبتها فحص الحمل.
- `paternity_reference` → مرجع الأبوة الناتج من بيانات التلقيح.
- `reference_mating_at` → التاريخ المرجعي للحساب.
- `confirmed_at` → تاريخ التأكيد الفعلي.
- `expected_birth_*` → قيم متوقعة محسوبة وفق Settings.
- `current_state` → حالة مشتقة وليست قيمة يدوية مستقلة.
- `notes` → بيانات وصفية.

### حد مهم حول القيم المتوقعة

اختيار هذه الحقول لا يعني أن أرقام أو معادلات الحمل حسمت.

قسم Settings 6.6 ما زال هو المسؤول عن:

```text
reference mating event
first check timing
recheck timing
gestation length / range
expected birth date calculation
expected birth window
birth preparation timing
```

إذن الـWorkflow يستهلك القواعد، ولا يخترعها.

---

### 4.9 `pregnancy.current_state_model`

القرار:

```text
derived_from_events_and_dates
```

الحالة الحالية للحمل لا تعدل يدويًا كـStatus مستقل.

يمكن أن يعرض النظام مفاهيم مثل:

```text
confirmed pregnancy
approaching expected birth
inside expected birth window
overdue beyond expected window
ended by actual birth
ended by exception
```

لكن مصدر هذه الحالة يجب أن يكون:

```text
Pregnancy Checks
+ Dates
+ Follow-up Events
+ Actual Birth / Exception Events
```

ولا يجوز أن تصبح `current_state` مصدر حقيقة يدوي ينافس الـTimeline.

حتى مع وجود `current_state` ضمن `pregnancy.record_fields`، التفسير المعتمد هو أنها **معلومة مشتقة متاحة على Pregnancy**، وليس Requirement لحقل يدوي قابل للتحرير.

ولا يحسم هذا القسم إن كان التنفيذ سيحسبها لحظيًا أو يخزن Cache / Materialized value؛ هذه تفاصيل تقنية لاحقة بشرط أن يبقى مصدر الحقيقة هو الأحداث والتواريخ.

---

### 4.10 `pregnancy.followup_event_types`

أحداث المتابعة أو الربط المدعومة أثناء الحمل:

```text
general_condition_followup
pregnancy_weight_reference
birth_site_preparation
nest_box_installation
approaching_birth_followup
```

#### `general_condition_followup`

يسجل متابعة تشغيلية فعلية للحالة العامة للأنثى أثناء الحمل.

لا يعني ذلك إنشاء تشخيص أو علاج صحي داخل هذا القسم؛ الأحداث الصحية المتخصصة تذهب إلى 4.13.

#### `pregnancy_weight_reference`

الوزن الفعلي لا يتكرر داخل Pregnancy Follow-up.

النموذج:

```text
Weight Measurement in 4.3
        ↓ reference
Pregnancy Follow-up
```

وبذلك يظل Weight History هو المصدر Canonical للوزن.

#### `birth_site_preparation`

يمثل تنفيذ تجهيز موقع الأنثى للولادة فعليًا.

#### `nest_box_installation`

يمثل تركيب / تجهيز بيت الولادة كحدث فعلي قابل للتتبع.

#### `approaching_birth_followup`

يمثل متابعة فعلية عند اقتراب موعد الولادة.

مواعيد إنشاء المهام أو توقيت كل متابعة لا تحسم هنا؛ مكانها Settings.

---

### 4.11 `pregnancy.followup_event_record_fields`

سجل المتابعة / التجهيز الفعلي يدعم:

```text
pregnancy
event_type
performed_at
cage
weight_measurement_reference
result
performed_by
notes
```

هذا يثبت أن النظام يحتاج أثرًا تاريخيًا لما **تم تنفيذه فعليًا**.

ويجب الانتباه إلى أن `result` هنا عنصر عام، لكن هذا القسم **لم يعتمد قائمة Result Categories تفصيلية لكل نوع متابعة**.

لذلك لا يجوز اختراع حالات مثل:

```text
passed / failed
normal / abnormal
ready / not_ready
```

دون قرار صريح لاحق.

أما `cage` فهو مرجع للموقع وقت التنفيذ عند الحاجة، ولا يصبح Current Location مستقلة؛ الموقع الحالي يظل مشتقًا من 4.2.

---

### 4.12 `pregnancy.expected_window_birth_behavior`

القرار:

```text
wait_for_actual_birth_or_exception_event
```

الوصول إلى `expected_birth_date` أو نهاية `expected_birth_window` لا ينشئ ولادة ولا يغلق الحمل تلقائيًا.

```text
Expected date/window reached
        ↓
Still waiting
        ├── Actual Birth Event → 4.6
        └── Exception Event → 4.14
```

إذن ممنوع استنتاج:

```text
auto_create_birth
auto_close_pregnancy
assume pregnancy failure because window passed
```

التواريخ المتوقعة تستخدم للمتابعة والمهام والتنبيهات فقط حتى تحدث واقعة فعلية.

---

## 5. نقطة ترابط مفتوحة مع 4.4 — مرجع الأبوة

هذه ليست مشكلة داخلية في أسئلة 4.5 نفسها، لكنها تؤثر مباشرة على:

```text
pregnancy.record_fields
→ paternity_reference
```

في 4.4 تم اعتماد إجابة:

```text
mating.multiple_males_paternity_policy = false
```

بينما المرجع الوظيفي ينص على أنه إذا استخدم أكثر من ذكر في الفترة التي أدت إلى نفس الحمل ولم يمكن إثبات الأب، فيجب ألا يختار النظام ذكرًا عشوائيًا، وأن تكون الأبوة **غير مؤكدة** مع الاحتفاظ بالذكور المحتملة عند معرفتهم.

لذلك لا يجوز تفسير `paternity_reference` حاليًا على أنه:

```text
always one confirmed male
```

ولا يجوز داخل 4.5 حسم الطريقة من تلقاء نفسه.

الحسم النهائي يعتمد أولًا على معالجة التعارض المسجل في Guide 4.4.

### أثر Requirement

يجب أن يكون تصميم `Pregnancy` قابلًا لتمثيل مرجع الأبوة بصورة لا تجبر النظام على فساد بيانات النسب عندما تكون الأبوة غير محسومة.

لكن شكل الـData Model النهائي:

```text
nullable father_id
paternity status
possible males relation
paternity confidence
```

**غير معتمد من هذا القسم** ولا يجوز فرض واحد منها الآن.

---

## 6. التوافق مع المرجع الوظيفي

الإجابات الحالية متوافقة مع المبادئ الرئيسية في `تصور_مشروع_الارانب.md`:

1. أكثر من فحص حمل يمكن أن يرتبط بنفس محاولة التلقيح.
2. النتيجة غير المؤكدة لا تغلق المحاولة، بل تؤدي لإعادة الفحص.
3. عدم إجراء الفحص لا يعني وجود نتيجة حمل.
4. النتيجة الإيجابية تثبت الحمل وتبدأ متابعة الحمل وتجهيز الولادة.
5. النتيجة السلبية تغلق المحاولة كغير ناجحة وتعيد الأنثى لمسار التلقيح حسب القواعد.
6. المواعيد والتوقيتات تأتي من Settings.
7. الوزن أثناء الحمل يبقى داخل Weight History.
8. الوصول إلى موعد الولادة المتوقع لا يساوي حدوث الولادة فعليًا.

---

## 7. حدود المسؤولية مع الأقسام الأخرى

### 7.1 مع 4.4 التلقيح وإدارة المحاولات

4.4 مسؤول عن:

```text
Mating Event
Mating Attempt
Actual male used
Attempt mating phase
```

4.5 يستخدم المحاولة المكتملة في مرحلة انتظار الفحص ولا يعيد تعريف أحداث التلقيح.

### 7.2 مع 4.3 الوزن والقياسات التشغيلية

```text
Actual Weight
→ 4.3

Pregnancy context for that weight
→ reference from 4.5
```

لا تنشئ نسخة وزن مستقلة داخل الحمل.

### 7.3 مع 4.6 تسجيل الولادة وإنشاء البطن

4.5 يتوقف عند:

```text
Pregnancy follow-up / birth preparation / waiting
```

والولادة الفعلية تبدأ في 4.6.

### 7.4 مع 4.14 الحالات الاستثنائية

الحالات مثل:

- ولادة بدون فحص حمل مؤكد.
- فقد حمل.
- أحداث لا تتبع المسار الطبيعي.

لا يجوز منع تسجيل الواقعة الفعلية لمجرد نقص خطوة سابقة؛ تعالج عبر المسار الاستثنائي المناسب مع الحفاظ على التاريخ.

### 7.5 مع Settings 6.6

Settings مسؤولة عن القواعد، ومنها:

```text
Which Mating Event is timing reference?
When is first pregnancy check due?
Allowed check window?
When to recheck uncertain result?
Gestation length / range?
How to calculate expected birth date/window?
When to prepare nest box?
When is birth considered overdue?
```

حتى وقت إعداد هذا الـGuide، هذه القواعد لم تعتمد بعد، لذلك لا يجوز إدخال أرقام افتراضية داخل Requirements.

---

## 8. ما لا يجوز استنتاجه من هذا القسم

لا يجوز استنتاج أي من التالي:

```text
Fixed pregnancy-check day
First mating is always the reference date
Last mating is always the reference date
Fixed gestation length
Fixed expected birth window
Fixed nest-box preparation day
Automatic birth creation
Automatic pregnancy closure on expected date
Manual pregnancy status editing
Pregnancy check result when task was not performed
Duplicate pregnancy weight value outside canonical Weight History
Automatic confirmed father when multiple males were used
Specific follow-up result categories not selected in the questions
Medical diagnosis/treatment workflow inside pregnancy follow-up
```

---

## 9. Requirements الناتجة من القسم

### R-4.5-01 — Pregnancy Check History
النظام يجب أن يحتفظ بكل فحص حمل كسجل مستقل مع التاريخ الكامل وعدم الكتابة فوق فحص سابق.

### R-4.5-02 — Check Record Relationships
كل Pregnancy Check يجب أن يدعم ربطه بالأنثى، محاولة التلقيح، دورة الإنتاج، وقت التنفيذ، النتيجة، المنفذ والملاحظات، مع دعم عدد الأيام من مرجع التلقيح.

### R-4.5-03 — Check Result Categories
الفحص الفعلي يدعم `positive`, `negative`, `uncertain`.

### R-4.5-04 — No Fake Check Record
حلول موعد الفحص دون تنفيذ فعلي لا ينشئ Pregnancy Check Record ولا نتيجة حمل.

### R-4.5-05 — Uncertain Recheck
النتيجة غير المؤكدة تبقي نفس Mating Attempt مفتوحة وتسمح بفحص لاحق مع الاحتفاظ بكل النتائج السابقة.

### R-4.5-06 — Positive Automation
النتيجة الإيجابية تغلق المحاولة كناجحة وتنشئ Pregnancy Record تلقائيًا.

### R-4.5-07 — Negative Flow
النتيجة السلبية تغلق المحاولة كغير ناجحة وتعيد الأنثى لمسار محاولة جديدة وفق Settings.

### R-4.5-08 — Pregnancy Record
Pregnancy يجب أن يرتبط بالأنثى، الدورة، محاولة التلقيح الناجحة، مرجع الأبوة، مرجع التلقيح الزمني، تاريخ التأكيد والقيم الزمنية المتوقعة والملاحظات.

### R-4.5-09 — Derived Pregnancy State
الحالة الحالية للحمل يجب أن تكون مشتقة من التاريخ والأحداث، لا قيمة يدوية مستقلة قابلة للتعديل المباشر.

### R-4.5-10 — Follow-up Event Support
النظام يجب أن يدعم تسجيل متابعة الحالة العامة، ربط أوزان الحمل، تجهيز موقع الولادة، تركيب بيت الولادة ومتابعة اقتراب الولادة.

### R-4.5-11 — Canonical Weight Reference
أي وزن للحمل يجب أن يشير إلى سجل الوزن Canonical في 4.3 بدل إنشاء قيمة وزن مكررة.

### R-4.5-12 — Follow-up Audit History
كل حدث متابعة أو تجهيز فعلي يجب أن يحتفظ بوقت التنفيذ والمنفذ والسياق والملاحظات والمرجع المناسب.

### R-4.5-13 — Expected Dates Are Not Events
تاريخ الولادة المتوقع ونافذتها قيم متابعة مشتقة، وليسا Birth Events.

### R-4.5-14 — Wait for Actual Outcome
لا يغلق الحمل ولا تنشأ الولادة تلقائيًا عند الوصول إلى الموعد المتوقع أو تجاوزه؛ يلزم Birth Event فعلي أو Exception Event.

### R-4.5-15 — Settings-driven Timing
مرجع التلقيح ومواعيد الفحص وإعادة الفحص والحمل المتوقع وتجهيز الولادة يجب أن تأتي من Settings 6.6.

### R-4.5-16 — Paternity Integrity Boundary
Pregnancy يجب ألا يفرض أبًا مؤكدًا تلقائيًا قبل حسم تعارض سياسة تعدد الذكور في 4.4.

---

## 10. حالة المراجعة النهائية

```text
Question Keys covered: 12 / 12
Applicable questions: 12
Answered: 12
Pending review in Answers: 0
Internal blocking conflict: none
Cross-section open issue: paternity reference depends on unresolved 4.4 multiple-male policy
Settings dependency: 6.6 currently unanswered, so timing/threshold values remain unresolved
```

القسم صالح للبناء عليه كـBlueprint Requirement من ناحية بنية الفحوص والحمل والمتابعة، مع إبقاء **قيم التوقيت والحمل المتوقع وتجهيز الولادة** معلقة حتى اعتماد Settings، وإبقاء **مرجع الأبوة** خاضعًا لحسم تعارض 4.4.