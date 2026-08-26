# 4.8 الفطام والتحول إلى التتبع الفردي — دليل تفسير الإجابات

> **الحالة:** مسودة أولى مبنية على الإجابات الحالية للمراجعة — يوجد تعارض مباشر مع 4.9 حول طريقة بدء مسار النمو، ونقطة تكامل تحتاج ضبطًا تخص مصدر `breed_origin` عند إنشاء الأفراد  
> **ملف الإجابات المقابل:** `questionnaire-export/answers/04-workflow/08-weanings.md`  
> **Question Keys المغطاة:** 12 / 12

---

## 1. الغرض من هذا الملف

هذا الملف يفسر **المعنى الوظيفي والتقني للإجابات المعتمدة فعليًا** في قسم `الفطام والتحول إلى التتبع الفردي` ضمن `الحركات ودورة التشغيل الفعلية`.

هو ليس مصدرًا بديلًا للإجابات، ولا يغير أي قرار موجود في ملف الإجابات.

```text
answers/04-workflow/08-weanings.md
= ماذا قررنا؟

                    ↓ تفسير

guides/04-workflow/08-weanings.md
= ماذا تعني هذه القرارات تقنيًا؟ وما حدودها؟
```

القاعدة المعمارية الأساسية لهذا القسم:

```text
Litter / Lactation tracking
→ 4.6 / 4.7

Actual Weaning Operation
→ 4.8

Permanent Animal Identity
→ 3.1

Pedigree / Biological Origin
→ 3.3

Actual Weight Record
→ 4.3

Housing Movement
→ 4.2

Weaning Readiness / Age / Weight / Separation Rules
→ Settings 6.8

Growth / Sorting / Re-evaluation
→ 4.9
```

الفطام هنا هو **Boundary تشغيلي حقيقي** بين مرحلتين مختلفتين جذريًا في نموذج البيانات:

```text
Before Weaning
→ offspring mainly managed through Litter / Lactation context

After Weaning
→ each weaned offspring becomes a persistent Animal Record
```

---

## 2. الهدف الوظيفي من القسم

الغرض من 4.8 ليس مجرد تسجيل أن البطن "تم فطامها"، بل تنفيذ عملية انتقال كاملة تحفظ سلامة العدد والأصل والهوية والتسكين والتاريخ.

الصورة الحالية:

```text
Active Litter / Lactation
        ↓
Open Weaning Session
        ↓
Reconcile expected vs actual offspring
        ↓
Select full or partial weaning scope
        ↓
Create permanent Animal Record(s)
        ↓
Resolve biological origin / foster history
        ↓
Create or link canonical Weaning Weight when applicable
        ↓
Complete Housing through 4.2
        ↓
Finalize Weaning
        ↓
Close or keep Litter open depending on remaining offspring
        ↓
Transition toward Growth / Sorting
```

المبدأ المهم:

```text
Weaning
≠ simple Status change
```

وإنما عملية لها Draft/Session، تحقق، إنشاء أفراد، تكامل مع الوزن والتسكين، واعتماد نهائي.

---

## 3. ملخص القرارات المعتمدة

الإجابات الحالية تحسم النموذج التالي:

```text
Weaning Operation
└── staged session with finalization

Weaning Event
├── litter
├── weaned_at
├── expected_alive_count
├── actual_weaned_count
├── age_at_weaning
├── general_condition
├── count_discrepancy_reason
├── performed_by
└── notes

Count Reconciliation
└── finalization blocked until counts reconcile
    or documented events explain the difference

Partial Weaning
└── supported
    ├── create Animal Records only for weaned offspring
    └── keep Litter / Lactation open for remaining offspring

Individual Creation
└── one Animal Record at a time
    + final count validation

Inherited Data
├── birth_date
├── biological_mother
├── paternity_reference when available
├── original_litter
├── birth_event
├── breed_origin according to approved rules
├── farm
├── weaning_reference
├── foster_mother when applicable
├── current_lactation_group when applicable
└── preweaning_transfer_reference when applicable

Transferred Offspring Origin
└── origin must be resolved before finalizing the individual

Sex at Weaning
└── not captured during Weaning workflow
    → resolved later

Weaning Weight
└── create/link one canonical Weight Record per individual
    with context = weaning

Housing
└── individuals created first
    → housing performed as a separate staged step
    → weaning cannot finalize before housing completes

Weaning Summary
└── derived from Weaning Event + linked Animal Records
    → no independent editable summary

Post-Weaning Growth Transition
└── current 4.8 answer says explicit recorded transition
    ⚠ conflicts directly with current 4.9 answer
```

---

## 4. تفسير Question Keys والإجابات

### 4.1 `weaning.operation_structure`

القرار:

```text
staged_session_with_finalization
```

أي أن الفطام ينفذ من خلال **جلسة مرحلية** يمكن حفظها كمسودة واستكمالها قبل الاعتماد النهائي.

هذا يسمح بتنفيذ خطوات مثل:

```text
Create Session
→ review expected count
→ identify actual offspring
→ create individual records
→ resolve origin
→ record/link weights
→ complete housing
→ validate
→ Finalize
```

ولا يجوز تفسير الجلسة على أنها مجموعة أحداث منفصلة بلا رابط؛ يجب أن تظل هناك وحدة تشغيلية واحدة تعرف أن هذه الخطوات تنتمي إلى نفس عملية الفطام.

كما أن وجود Draft لا يعني أن جميع الآثار التشغيلية تصبح نهائية فور إدخالها؛ نقطة الاعتماد النهائي هي التي تفصل بين العمل الجاري والعملية المكتملة، مع مراعاة أن أي سجلات Canonical يتم إنشاؤها أثناء الجلسة تحتاج سياسة تنفيذ تمنع التكرار أو الضياع عند الاستكمال.

---

### 4.2 `weaning.event_record_fields`

تم اعتماد:

```text
litter
weaned_at
expected_alive_count
actual_weaned_count
age_at_weaning
general_condition
count_discrepancy_reason
performed_by
notes
```

التفسير:

- `litter` → البطن الذي تنفذ عليه العملية.
- `weaned_at` → وقت الفطام الفعلي، وليس وقت بدء إعداد الجلسة بالضرورة.
- `expected_alive_count` → العدد الذي يفترض وجوده وفق Timeline البطن.
- `actual_weaned_count` → العدد الذي تم فطامه فعليًا في هذه العملية.
- `age_at_weaning` → مشتق من تاريخ الميلاد عندما يكون موثوقًا.
- `general_condition` → ملاحظة تشغيلية عن الحالة العامة وقت التنفيذ.
- `count_discrepancy_reason` → سياق فرق العدد عند وجوده، لكن قرار السؤال التالي يجعل مجرد كتابة سبب غير كافٍ إذا كان الفرق غير مدعوم بأحداث تفسره.
- `performed_by` → منفذ/مسجل العملية.
- `notes` → ملاحظات إضافية.

`expected_alive_count` لا يصبح رقمًا يدويًا منفصلًا عن 4.7؛ الأصل أن يأتي من الحالة المشتقة للبطن وفق الأحداث المسجلة.

---

### 4.3 `weaning.count_reconciliation_model`

القرار:

```text
require_reconciliation_before_finalize
```

وهذا قرار سلامة بيانات قوي.

المعنى:

```text
Expected Alive Count
must reconcile with
Actual offspring accounted for by Weaning / remaining Litter state
```

إذا ظهر فرق، لا يكفي تجاهله أو الاكتفاء بتحذير.

يجب أن يكون الفرق مفسرًا بسجلات مثل:

```text
Mortality
Foster Transfer Out / In
Partial Weaning from earlier operation
Documented terminal event
Other approved event affecting litter count
```

ولا يجوز إصلاح الفرق عبر تعديل `live_born` التاريخي في 4.6 أو تعديل Current Alive Count مباشرة.

#### أثر التعارض المفتوح من 4.7

4.7 لديه تعارض غير محسوم حول مصدر نفوق المواليد بعد الولادة:

```text
dedicated_lactation_mortality_event
vs
Canonical Mortality Event in 4.13
```

هذا لا يغير قرار 4.8، لكنه يعني أن **مصدر أحداث النفوق الذي سيدخل في Reconciliation يجب أن يحسم قبل الـBlueprint النهائي** حتى لا يحسب نفس النفوق مرتين أو يفقد من الحساب.

---

### 4.4 `weaning.partial_weaning_model`

القرار:

```text
partial_weaning_keep_litter_open
```

إذن يمكن تنفيذ فطام جزئي.

مثال مفاهيمي:

```text
Litter current alive = 8
Weaning Event #1 = 5
→ create 5 Animal Records
→ 3 offspring remain under Litter / Lactation
→ Litter remains open

Later
Weaning Event #2 = 3
→ create remaining 3 Animal Records
→ no offspring remain under lactation
→ Litter can complete its weaning lifecycle
```

هذا يعني أن:

```text
Litter
can have
more than one Weaning Event
```

عند اعتماد الفطام الجزئي.

ولا يجوز أن يؤدي أول Weaning Event إلى إغلاق البطن بالكامل إذا بقيت مواليد تحت الرعاية.

---

### 4.5 `weaning.individual_creation_model`

القرار:

```text
create_individuals_one_by_one_with_final_validation
```

أي أن النظام لا يولد جميع Animal Records تلقائيًا من العدد فقط، ولا يعتمد إدخال Batch غير مفصل.

يتم إنشاء كل فرد واحدًا تلو الآخر داخل جلسة الفطام، مع تحقق نهائي أن عدد الأفراد المنشأة يطابق `actual_weaned_count` للعملية.

المبدأ:

```text
actual_weaned_count
=
number of finalized Animal Records created/linked by this Weaning Event
```

ولا يجوز اعتماد جلسة بها:

```text
actual_weaned_count = 5
but only 4 Animal Records accounted for
```

أو العكس.

#### هوية الحيوان

إنشاء الفرد هنا لا يعيد تعريف سياسة الكود.

قواعد 3.1 تظل الحاكمة:

```text
Animal Internal Code
→ automatic
→ globally unique
→ stable for life
```

وبالتالي 4.8 يبدأ وجود `Animal Record` الدائم، لكنه لا يقرر شكل الكود أو مكوناته.

---

### 4.6 `weaning.inherited_animal_fields`

تم اعتماد نقل كل البيانات التالية عند توفرها بصورة موثوقة:

```text
birth_date
biological_mother
paternity_reference
original_litter
birth_event
breed_origin
farm
weaning_reference
foster_mother_if_applicable
current_lactation_group_if_applicable
preweaning_transfer_reference
```

الهدف هو منع إعادة إدخال بيانات معروفة بالفعل.

#### الأصل البيولوجي

يجب الحفاظ على:

```text
Biological Mother
Original Litter
Birth Event
Paternity Reference
```

كمصادر أصل، وعدم استبدالها بعلاقة الحضانة.

#### الحضانة البديلة

عند وجود Foster Transfer في 4.7:

```text
Biological Mother remains biological origin
Original Litter remains original origin
Foster Mother is additional care relationship
Current Lactation Group is current care context
Preweaning Transfer Reference preserves how the move happened
```

#### `paternity_reference`

يجب نقل **نفس حالة الأبوة الموثقة** من المسار السابق، وليس إجبار القيمة على ذكر واحد مؤكد.

بسبب التعارض المفتوح في 4.4 حول تعدد الذكور، يمكن أن يكون مفهوم الأبوة لاحقًا بحاجة إلى تمثيل:

```text
confirmed father
or
uncertain paternity / candidate males
```

وفق القرار النهائي.

4.8 لا يحل هذا التعارض من نفسه.

#### `breed_origin` — نقطة تكامل تحتاج ضبطًا

3.3 قرر صراحة أن **سلالة النسل لا تستنتج تلقائيًا من سلالتي الأب والأم**، بل يتم اختيار Breed من Master Data وفق القرار المعتمد.

وفي 4.8 تم اختيار `breed_origin` ضمن البيانات التي تنتقل تلقائيًا إلى الفرد.

يمكن جعل القرارين متوافقين فقط بالتفسير التالي:

```text
If an approved Breed value already exists from a trusted prior source
→ copy/link it to the Animal Record

Do NOT infer Breed automatically from parents
```

لكن الأقسام السابقة للولادة/البطن لا تحسم حاليًا بوضوح **أين يتم إنشاء Breed value الخاصة بالمولود قبل الفطام**.

لذلك مصدر `breed_origin` عند الفطام يحتاج ضبطًا لاحقًا، ولا يجوز تنفيذ Auto-Inference من الوالدين لتغطية هذا الفراغ.

---

### 4.7 `weaning.preweaning_origin_resolution_model`

القرار:

```text
require_origin_resolution_before_finalize
```

عند وجود مولود تم نقله قبل الفطام، لا يعتمد Animal Record النهائي إلا بعد حسم:

```text
Biological Mother
Original Litter
Foster Relationship when applicable
Preweaning Transfer history
```

هذا يتكامل مباشرة مع قرار 4.7:

```text
temporary_identifier_per_transferred_offspring
```

أي أن Temporary Identifier ليس هو الكود النهائي للحيوان، لكنه **Bridge Identity** يستخدم لحماية الأصل حتى الفطام.

المسار:

```text
Transferred Preweaning Offspring
→ Temporary Identifier in 4.7
→ Foster Transfer history
→ Weaning Session
→ Resolve origin
→ Create Permanent Animal Record
→ Permanent Internal Code from 3.1
```

لا يجوز استخدام بطن الرضاعة الحالية كبديل عن البطن البيولوجية الأصلية.

---

### 4.8 `weaning.sex_capture_model`

القرار:

```text
do_not_capture_sex_during_weaning
```

أي أن جلسة الفطام نفسها **لا تطلب تحديد الجنس**.

هذا لا يعني أن Animal Record لا يدعم الجنس.

3.1 يدعم أن يكون الجنس:

```text
temporarily unknown
```

وبالتالي عند إنشاء الفرد في 4.8 يمكن أن يبدأ الجنس غير محسوم، ثم يتم تحديده لاحقًا في المسار المناسب.

هذا القرار يعني أيضًا أن أي ملخص جنس مباشرة لحظة الفطام لا يمكن افتراض أنه يحتوي على Male/Female counts مؤكدة ما لم تكن هذه البيانات جاءت من مصدر آخر موثوق.

ولا يجوز أن يمنع الفطام لمجرد أن الجنس لم يحدد، لأن الإجابة الحالية اختارت صراحة عدم التقاطه في هذه العملية.

أما متى يصبح تحديد الجنس إلزاميًا، ومتى يجب الفصل بين الجنسين، فهذه قواعد Settings / Housing وليست قرارًا في 4.8.

---

### 4.9 `weaning.weight_integration_model`

القرار:

```text
create_or_link_canonical_weaning_measurement
```

أي أن وزن الفطام، عندما يتم تسجيله، لا يخزن كنسخة مستقلة داخل Weaning Event.

المصدر الوحيد:

```text
Weight Measurement in 4.3
├── subject = newly created Animal Record
├── context = weaning
├── measured_at
└── related_workflow_reference = Weaning Session/Event
```

وهذا متوافق مع قرار 4.3 الذي يجعل الوزن بعد وجود Animal Record قياسًا فرديًا Canonical.

#### توقيت إنشاء الوزن داخل الجلسة

بما أن 4.8 ينشئ Animal Record لكل مفطوم قبل الاعتماد النهائي، يصبح من الممكن ربط وزن الفطام بالفرد خلال الجلسة دون الحاجة إلى وزن Litter ككيان.

لكن السؤال الحالي لا يقول إن **كل فرد يجب أن يُوزن إلزاميًا**.

الإلزام، الحد الأدنى، الوزن المستهدف، وطريقة استخدام الوزن في قرار الجاهزية موجودة في Settings 6.8، وهي غير مجاب عنها حاليًا.

---

### 4.10 `weaning.housing_integration_model`

القرار:

```text
staged_housing_required_before_weaning_finalize
```

المسار:

```text
Create Individual Animal Records
→ Housing step executed separately
→ Housing Movements recorded canonically in 4.2
→ Finalize Weaning only after required housing completes
```

المعنى أن التسكين **شرط لاستكمال العملية**، لكنه لا يصبح سجل موقع خاصًا بالفطام.

المصدر الوحيد للموقع الفعلي يبقى:

```text
Housing / Movement History in 4.2
```

ولا يجوز تخزين `current_location` كقيمة يدوية مستقلة داخل Animal Record نتيجة الفطام.

#### توزيع الأفراد

يمكن للجلسة أن ترتب تسكين الأفراد في أكثر من قفص حسب السعة والقواعد، لكن كل حركة تسكين فعلية تبقى Canonical في 4.2.

قواعد:

```text
Cage eligibility
Capacity
Usage compatibility
Sex separation when applicable
Housing readiness
```

تبقى في Settings/Farm Structure، ولا يحددها 4.8.

---

### 4.11 `weaning.summary_persistence_model`

القرار:

```text
derive_summary_from_event_and_individuals
```

أي أن النظام لا يحتاج Summary مستقلة قابلة للتحرير يدويًا.

يمكن اشتقاق:

```text
weaned count
weaning age
individual records created
weights when available
sex distribution when sex becomes available
housing distribution
origin/foster context
```

من السجلات الأصلية.

المبدأ:

```text
Source Records
→ Derived Summary
```

وليس:

```text
Source Records
+
Independent Editable Summary
```

حتى لا تظهر قيم متعارضة.

#### ملاحظة حول توزيع الجنس

لأن 4.8 اختار عدم تسجيل الجنس أثناء الفطام، فإن Sex Distribution قد تكون وقت الاعتماد:

```text
unknown / not yet resolved
```

ولا يجوز اختلاق توزيع Male/Female لمجرد أن التقرير يدعمه مستقبلًا.

---

### 4.12 `weaning.post_completion_transition_model`

القرار الحالي في 4.8:

```text
explicit_growth_start_after_weaning
```

أي أن:

```text
Weaning Finalized
→ Animal becomes independent / weaned
→ Growth Program does NOT start merely by finalization
→ separate recorded transition starts Growth / Sorting context
```

لكن هذه الإجابة **تتعارض مباشرة مع الإجابة الحالية في 4.9**.

4.9 / `growth_sorting.program_entry_model` قرر:

```text
auto_start_after_successful_weaning
```

أي:

```text
Successful Weaning
→ Growth / Sorting starts automatically
```

إذن لدينا حاليًا قراران متضادان لنفس Boundary:

```text
4.8 says:
Explicit Growth Start

4.9 says:
Automatic Growth Start
```

هذا **تعارض مانع لتحديد Requirement نهائي لهذه النقطة** ويحتاج قرارًا صريحًا لاحقًا قبل بناء الـBlueprint التنفيذي.

لا يجوز اختيار أحدهما من الـGuide أو دمجهما في سلوك هجين بدون قرار جديد.

---

## 5. التوافق مع المرجع الوظيفي

القرارات الأساسية في 4.8 تتوافق مع المنطق الوظيفي الذي يجعل الفطام نقطة انتقال رئيسية من إدارة المواليد كبطن إلى تتبع كل فرد بصورة مستقلة.

المعاني المتوافقة:

```text
Before Weaning
→ Litter-based tracking

At Weaning
→ reconcile offspring
→ create individual records
→ preserve birth / mother / father / litter origin
→ link weaning weight when used
→ establish housing

After Weaning
→ persistent Animal Record
→ individual growth / sorting / later fate
```

كما أن الحضانة البديلة لا تعيد كتابة الأصل البيولوجي؛ علاقة الأم الحاضنة تحفظ منفصلة عن الأم البيولوجية.

المرجع لا يعطي دعمًا لاختراع:

- Breed آلية من الأب والأم.
- جنس مؤكد لكل مولود عند الفطام إذا لم يتم تحديده فعليًا.
- أحداث نفوق أو نقل غير مسجلة لتسوية الفرق.
- قيم عمر/وزن ثابتة للفطام دون قرار Settings.

---

## 6. المراجعة العابرة بين الأقسام

### 6.1 تعارض مباشر — بدء مسار النمو بعد الفطام

**المشكلة:**

```text
4.8 = explicit_growth_start_after_weaning
4.9 = auto_start_after_successful_weaning
```

**المكان:**

- `weaning.post_completion_transition_model`
- `growth_sorting.program_entry_model`

**الأثر:**

لا يمكن كتابة Requirement نهائي واحد يحدد هل يحتاج المستخدم إلى Action مستقلة بعد الفطام أم يبدأ النظام Growth Tracking تلقائيًا.

**الحالة:** يحتاج حسمًا صريحًا لاحقًا. لا تعديل تلقائي.

---

### 6.2 نقطة تكامل — مصدر `breed_origin`

**المشكلة:**

4.8 يطلب نقل `breed_origin` تلقائيًا إذا كانت من البيانات المعروفة، بينما 3.3 يمنع استنتاج Breed للنسل تلقائيًا من الوالدين.

**التفسير الآمن الحالي:**

```text
Copy an already approved Breed value
≠
Infer Breed from parents
```

**المفتوح:**

أين ومتى تنشأ Breed value الموثوقة للمولود قبل/أثناء الفطام إذا لم تكن موجودة مسبقًا؟

**الحالة:** نقطة تكامل تحتاج تحديد مصدر القيمة، وليست إذنًا بالاستنتاج الآلي.

---

### 6.3 ترابط مع تعارض الأبوة السابق

4.8 ينقل `paternity_reference` لكنه لا يعيد حساب الأبوة.

بسبب تعارض 4.4 عند استخدام أكثر من ذكر، يجب أن يحافظ 4.8 على **درجة اليقين الأصلية** ولا يحول الأبوة غير المؤكدة إلى أب مؤكد أثناء إنشاء Animal Record.

---

### 6.4 ترابط مع تعارض نفوق الرضاعة

Reconciliation عند الفطام يعتمد على أحداث النفوق الصحيحة.

قبل التنفيذ النهائي يجب أن يكون هناك مصدر Canonical واحد لنفوق ما بعد الولادة، لأن 4.7 وBoundary 4.13 غير متفقين حاليًا.

---

### 6.5 Settings 6.8 غير محسومة بعد

الإجابات الحالية في 6.8 غير مكتملة، لذلك 4.8 **لا يحسم**:

```text
Target Weaning Age
Minimum Weaning Age
Maximum Age / overdue threshold
Target Weaning Weight
Minimum Weaning Weight
Age + Weight decision logic
Early Weaning rules
Sex separation timing
Housing capacity requirements specific to Weaning
Other readiness thresholds
```

وبالتالي لا يجوز تحويل أمثلة التصور إلى أرقام ثابتة داخل الـRequirements.

---

## 7. حدود الكيانات والمسؤوليات

### `Litter`

قبل اكتمال الفطام الكامل:

```text
Litter
→ remains source of biological/original grouping
→ may remain open after partial weaning
→ current remaining offspring derived from events
```

### `Weaning Session / Event`

يمثل:

```text
what was actually weaned
when
from which litter
how many
with which reconciliation
by whom
```

ولا يمثل قواعد الجاهزية.

### `Animal`

يبدأ هنا ككيان فردي دائم للمفطوم:

```text
Permanent Identity
+ Biological Origin
+ Historical Links
```

ولا يخزن الموقع/الوزن الحالي كمصادر يدوية مستقلة.

### `Weight Measurement`

يبقى المصدر الوحيد للوزن الفعلي في 4.3.

### `Housing Movement`

يبقى المصدر الوحيد للتسكين الفعلي في 4.2.

### `Growth Tracking`

مكانه 4.9، لكن طريقة بدءه من 4.8 ما زالت متعارضة بين القسمين.

---

## 8. ما لا يجوز استنتاجه من هذه الإجابات

الإجابات الحالية **لا تقول** إن:

- كل البطن يجب أن تفطم دفعة واحدة.
- الجنس يجب أن يحدد وقت الفطام.
- وزن الفطام إلزامي لكل حيوان.
- هناك عمر فطام ثابت.
- هناك وزن فطام ثابت.
- Breed تستنتج من الوالدين.
- Foster Mother تصبح Biological Mother.
- Current Lactation Group تصبح Original Litter.
- الفطام ينشئ موقعًا يدويًا داخل Animal.
- الفطام ينشئ وزنًا خارج Weight History.
- Growth يبدأ تلقائيًا أو يدويًا بصورة نهائية قبل حسم تعارض 4.8/4.9.

---

## 9. Requirements الناتجة من القسم

### Requirement 1 — Staged Weaning Session
يجب دعم جلسة فطام مرحلية قابلة للحفظ والاستكمال ولها نقطة Finalization واضحة.

### Requirement 2 — Historical Weaning Event
يجب حفظ Event تاريخي للفطام بالحقول المعتمدة وربطه بالبطن والأفراد الناتجين.

### Requirement 3 — Count Reconciliation Before Finalization
يجب منع اعتماد الفطام عندما يوجد فرق غير مفسر بين العدد المتوقع والحساب الفعلي.

### Requirement 4 — Partial Weaning
يجب دعم الفطام الجزئي مع بقاء البطن/الرضاعة مفتوحة للمواليد غير المفطومة.

### Requirement 5 — One-by-One Animal Creation
يجب إنشاء Animal Records للمفطومين واحدًا تلو الآخر داخل الجلسة والتحقق من العدد قبل الاعتماد.

### Requirement 6 — Permanent Identity from 3.1
كل فرد مفطوم يصبح Animal Record دائمًا بهوية وفق قواعد 3.1، ولا يعاد تصميم الكود في 4.8.

### Requirement 7 — Trusted Data Inheritance
يجب نقل البيانات المعروفة والموثوقة إلى الفرد دون إعادة إدخال، مع منع اشتقاق Breed من الوالدين دون قرار.

### Requirement 8 — Preserve Biological Origin
يجب الحفاظ على الأم البيولوجية والبطن الأصلية ومرجع الولادة والأبوة، مع الفصل عن Foster Mother / Current Lactation Group.

### Requirement 9 — Resolve Transferred-Offspring Origin
عند وجود نقل قبل الفطام يجب حسم أصل الفرد من Temporary Tracking قبل اعتماد Animal Record النهائي.

### Requirement 10 — Sex Not Required in Weaning Workflow
عملية الفطام نفسها لا تلتقط الجنس وفق القرار الحالي، ويمكن أن يظل الجنس غير محدد إلى مرحلة لاحقة.

### Requirement 11 — Canonical Weaning Weight
أي وزن فطام مسجل يجب أن يكون Canonical Weight Record في 4.3 مرتبطًا بالفرد وبعملية الفطام.

### Requirement 12 — Housing Required Before Finalization
لا تكتمل عملية الفطام قبل تنفيذ التسكين المطلوب، مع بقاء Housing History في 4.2 كمصدر الحقيقة.

### Requirement 13 — Derived Weaning Summary
ملخص الفطام يجب أن يشتق من Event + Animal Records ولا يكون نسخة مستقلة قابلة للتعديل.

### Requirement 14 — Growth Transition Unresolved
لا يمكن اعتماد Requirement نهائي لطريقة بدء Growth Tracking حتى يحسم التعارض بين 4.8 و4.9.

---

## 10. الحالة النهائية للمراجعة

```text
Question Keys reviewed: 12 / 12
Applicable: 12
Answered: 12
Open review in Answer export: 0
Internal blocking conflicts inside 4.8: 0
Cross-section blocking conflicts: 1
Cross-section integration points requiring clarification: 3+
```

### التعارض المانع الرئيسي

```text
4.8 explicit_growth_start_after_weaning
vs
4.9 auto_start_after_successful_weaning
```

### النقاط المفتوحة المهمة

```text
Source of approved breed_origin at Weaning
Paternity uncertainty inheritance from 4.4
Canonical mortality source for count reconciliation
Actual readiness thresholds from Settings 6.8
```

وباستثناء هذه النقاط، فإن بنية الفطام نفسها مترابطة بوضوح وتحافظ على الفصل بين:

```text
Litter Tracking
→ Weaning Operation
→ Permanent Animal Identity
→ Canonical Weight / Housing
→ Individual Post-Weaning Lifecycle
```
