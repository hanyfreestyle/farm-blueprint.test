# 4.12 التسمين والجاهزية للبيع — دليل تفسير الإجابات

> **الحالة:** مسودة أولى مبنية على الإجابات الحالية للمراجعة — القسم متماسك داخليًا، مع تعارض عابر مع 4.10 حول Boundary بداية مسار التسمين، وفجوتي تكامل تخصان مرجع التسكين عند البداية وLifecycle انتقال الخروج الناتج من جاهزية مشتقة تلقائيًا  
> **ملف الإجابات المقابل:** `questionnaire-export/answers/04-workflow/12-fattenings.md`  
> **Question Keys المغطاة:** 10 / 10

---

## 1. الغرض من هذا الملف

هذا الملف يفسر **المعنى الوظيفي والتقني للإجابات المعتمدة فعليًا** في قسم `التسمين والجاهزية للبيع` ضمن `الحركات ودورة التشغيل الفعلية`.

هو لا يغير الإجابات، ولا يحدد أرقام العمر أو الوزن أو معدل النمو من نفسه، ولا يسجل البيع أو الخروج الفعلي داخل 4.12.

```text
answers/04-workflow/12-fattenings.md
= ماذا قررنا؟

                    ↓ تفسير

guides/04-workflow/12-fattenings.md
= ماذا تعني القرارات تقنيًا؟ وما حدودها؟
```

الحد المعماري الأساسي:

```text
Fate Decision / Change of Path
→ 4.10

Fattening Period / Progress / Sale Readiness / Fattening Review
→ 4.12

Actual Weight Measurement
→ 4.3

Actual Housing Movement
→ 4.2

Health Review / Health Event
→ 4.13

Actual Sale / Exit Event
→ 4.15

Fattening Targets / Thresholds / Weight Schedule / Readiness Rules
→ Settings 6.10
```

والقاعدة المركزية:

```text
Fattening Fate Decision
≠
Fattening Follow-up
≠
Sale Readiness
≠
Sale Decision / Planned Exit
≠
Actual Sale / Farm Exit
```

---

## 2. الهدف الوظيفي من القسم

الغرض من 4.12 هو إدارة الفترة التي يصبح فيها الحيوان **داخل مسار التسمين** بعد قرار تحويله لهذا المسار، مع الاستمرار في نفس `Animal Record` ونفس التاريخ السابق، ثم تقييم تقدمه حتى الوصول إلى حالة بيع مناسبة أو قرار تشغيلي آخر.

الصورة الحالية:

```text
Approved Fattening Fate Decision
        ↓
Fattening Period starts
        ↓
Housing may still need execution in 4.2
        ↓
Weight / Growth / Health follow-up
        ↓
Derived Sale Readiness
        ├── not ready
        ├── approaching target
        ├── ready for sale
        ├── target age/duration exceeded
        └── growth/weight below target
        ↓
when review is required
        → Fattening Review
        ↓
possible next handling
        ├── continue fattening
        ├── plan sale at current state
        ├── health review
        ├── fate/exclusion change
        ├── reevaluate later
        └── other documented decision
        ↓
when sale/exit direction is ready
        → Pending Exit Transition to 4.15
        ↓
Actual Exit Event
        ↓
Fattening Period closes
```

---

## 3. ملخص القرارات المعتمدة

```text
Fattening Entry Boundary
└── starts on approved fattening Fate Decision
    even if housing movement is still pending

Fattening Start Record
├── animal
├── started_at
├── age_at_start
├── start_weight_reference
├── source_fate_decision_reference
├── fattening_reason
├── housing_reference
├── performed_by
└── notes

Tracking Model
└── individual fattening record per animal
    + optional shared batch sessions for operational convenience

Progress Context
├── current_age
├── start_weight
├── latest_weight
├── fattening_weight_history
├── derived_growth_metrics
├── fattening_duration
├── health_context
└── latest_fattening_review

Sale Readiness
└── derived automatically from current records + Settings rules
    no manually maintained readiness status

Readiness Outcomes
├── not_ready
├── approaching_target
├── ready_for_sale
├── target_age_or_duration_exceeded
└── growth_or_weight_below_target

Target-miss Review Outcomes
├── continue_fattening
├── plan_sale_at_current_state
├── health_review
├── exclusion_or_fate_change
├── reevaluate_later
└── other_documented_decision

Fattening Review Record
├── fattening_period
├── reviewed_at
├── review_trigger
├── weight_reference
├── evaluation_context_snapshot
├── review_outcome
├── reviewed_by
└── notes

Sale / Exit Handoff
└── create pending exit transition to 4.15
    no actual exit in 4.12

Fattening Path Closure
└── only on terminal actual event
    ├── actual sale / exit
    ├── approved transition to another path
    └── mortality
```

---

## 4. تفسير Question Keys والإجابات

### 4.1 `fattening.entry_boundary_model`

القرار:

```text
start_on_fattening_fate_decision
```

أي أن `Fattening Period` يبدأ تاريخيًا بمجرد **اعتماد قرار المصير الذي يحول الحيوان إلى التسمين**.

إذن:

```text
FatteningPeriod.started_at
≈
Approved Fattening FateDecision.decided_at
```

بحسب النموذج الحالي، ولا ننتظر اكتمال حركة التسكين إلى قفص تسمين.

هذا يحقق الفصل بين:

```text
Decision to enter fattening
≠
Physical movement to fattening housing
```

لكن هذا القرار يحمل تعارضًا عابرًا مع 4.10 موضح في قسم المراجعة المفتوحة، لأن 4.10 اختار `create_pending_downstream_transition` بدل بدء سياق المسار التالي تلقائيًا.

---

### 4.2 `fattening.start_record_fields`

تم اعتماد:

```text
animal
started_at
age_at_start
start_weight_reference
source_fate_decision_reference
fattening_reason
housing_reference
performed_by
notes
```

التفسير:

- `animal` → نفس Animal Record، ولا تنشأ هوية جديدة.
- `started_at` → بداية Fattening Period وفق Boundary السؤال السابق.
- `age_at_start` → مشتق عند إمكان حساب العمر من بيانات الميلاد.
- `start_weight_reference` → مرجع إلى Weight History في 4.3، وليس قيمة وزن Canonical ثانية داخل 4.12.
- `source_fate_decision_reference` → مرجع القرار المعتمد في 4.10 الذي أدى إلى التسمين.
- `fattening_reason` → مبرر التحويل، ولا يحل محل Fate Decision أو Exclusion Reason إذا كان التحويل ناتجًا من استبعاد.
- `housing_reference` → مرجع متعلق بالتسكين عند بداية المسار، لكن معناه النهائي يحتاج ضبطًا بسبب إمكان بدء المسار قبل اكتمال حركة التسكين؛ راجع قسم المراجعة المفتوحة.
- `performed_by` → صاحب/منفذ البداية بحسب النموذج التنفيذي النهائي، مع الانتباه إلى أنه لا يوجد حاليًا Start Event مستقل عن Fate Decision.
- `notes` → ملاحظات تشغيلية.

#### أثر قرار البداية على `performed_by`

بما أن البداية تحدث عند Fate Decision نفسها ولا يوجد `Explicit Fattening Start Event` مستقل، فلا يجوز افتراض وجود إجراء ميداني ثانٍ فقط لتعبئة `performed_by`.

الـBlueprint النهائي يحتاج أن يمنع خلق واقعتين متنافستين:

```text
Fate Decision actor
vs
Fattening Start actor
```

إذا لم يكن هناك Start Event مستقل بالفعل.

---

### 4.3 `fattening.individual_tracking_model`

القرار:

```text
individual_fattening_record_with_optional_batch_sessions
```

أي أن التسمين يظل **فرديًا على مستوى الحقيقة التاريخية** حتى لو كانت عدة أرانب تسكن نفس القفص أو تنفذ عليها عملية مشتركة.

```text
Animal A → Fattening Period A
Animal B → Fattening Period B
Animal C → Fattening Period C
```

ويمكن فوق ذلك وجود Session مشتركة لأغراض سهولة التنفيذ.

لكن:

```text
Batch Session
≠
Group-only animal history
```

كل حيوان يحتفظ بهويته ووزنه وعمره وتاريخه ومصيره بصورة مستقلة.

#### حد مع 4.2

هذا القرار **لا يعيد فتح قرار النقل الجماعي العام** في 4.2 من تلقاء نفسه.

السؤال هنا يقرر إمكان تجميع عمليات التسمين في Session مشتركة فقط، لكنه لا يحدد أن كل نوع من العمليات — وخصوصًا Housing Transfer — أصبح جماعيًا.

إذا كانت Batch Session ستنفذ حركة نقل، يجب الالتزام بنموذج 4.2 المعتمد وحسم أي تعارض هناك بصورة مستقلة.

---

### 4.4 `fattening.progress_context_sources`

تم اعتماد جميع المصادر التالية:

```text
current_age
start_weight
latest_weight
fattening_weight_history
derived_growth_metrics
fattening_duration
health_context
latest_fattening_review
```

هذه **مصادر عرض وتقييم** وليست نسخ بيانات جديدة.

الفصل المطلوب:

```text
Actual Weights
→ Weight History 4.3

Current Age
→ derived from birth information when possible

Derived Growth Metrics
→ calculated from Weight History

Health Context
→ health records / events in 4.13

Fattening Duration
→ derived from FatteningPeriod.started_at
```

ولا يجوز إنشاء حقول يدوية موازية مثل:

```text
current_weight_manual
current_age_manual
current_growth_rate_manual
```

إذا كانت مصادرها Canonical موجودة بالفعل.

---

### 4.5 `fattening.readiness_evaluation_model`

القرار:

```text
derived_automatically_from_rules_and_records
```

أي أن `Sale Readiness` ليست Status يختاره المستخدم يدويًا ويحافظ عليه منفصلًا عن البيانات.

المفهوم:

```text
Current Operational Records
+
Settings 6.10 Rules
=
Derived Sale Readiness
```

ويجب إعادة حسابها عندما تتغير المدخلات المؤثرة وفق النموذج التنفيذي النهائي.

لكن:

```text
Ready for Sale
≠
Sold
```

وكذلك:

```text
Ready for Sale
≠
Exited from Farm
```

البيع والخروج الفعليان يبقيان في 4.15.

#### حدود القرار

4.12 لا يحدد بنفسه:

- العمر المستهدف.
- وزن البيع المستهدف.
- الحد الأدنى المقبول للوزن.
- الحد الأقصى لمدة التسمين.
- معدل النمو المستهدف.
- حدود انخفاض النمو.
- دورية الوزن.
- المنطق الدقيق الذي يجمع العمر والوزن والنمو والصحة والمدة.

هذه جميعًا في Settings 6.10.

---

### 4.6 `fattening.readiness_outcome_categories`

تم اعتماد الحالات الخمس:

```text
not_ready
approaching_target
ready_for_sale
target_age_or_duration_exceeded
growth_or_weight_below_target
```

التفسير:

- `not_ready` → لم تتحقق شروط الجاهزية الحالية.
- `approaching_target` → الحيوان قريب من الهدف ويحتاج متابعة قصيرة وفق القواعد.
- `ready_for_sale` → القواعد تعتبره جاهزًا لتسليمه لمسار البيع/الخروج، لكن لم يحدث بيع بعد.
- `target_age_or_duration_exceeded` → تجاوز هدفًا زمنيًا ويحتاج مراجعة، ولا يخرج تلقائيًا.
- `growth_or_weight_below_target` → توجد مشكلة في الوصول للهدف وتحتاج مراجعة، ولا تعني استبعادًا تلقائيًا.

إذن الحالات الأخيرة ليست Terminal States.

---

### 4.7 `fattening.target_miss_review_outcomes`

إذا لم يصل الحيوان للهدف أو تجاوز مدة التسمين، يدعم Fattening Review:

```text
continue_fattening
plan_sale_at_current_state
health_review
exclusion_or_fate_change
reevaluate_later
other_documented_decision
```

وهذه الخيارات لا تنفذ كل آثارها داخل 4.12.

```text
continue_fattening
→ same Fattening Period continues

plan_sale_at_current_state
→ sale/exit handoff, actual exit remains 4.15

health_review
→ health workflow/context in 4.13

exclusion_or_fate_change
→ new Fate / Exclusion Decision in 4.10

reevaluate_later
→ another fattening review later
```

المرجع الوظيفي واضح أن عدم بلوغ الوزن المستهدف لا يخرج الحيوان تلقائيًا.

---

### 4.8 `fattening.review_record_fields`

تم اعتماد:

```text
fattening_period
reviewed_at
review_trigger
weight_reference
evaluation_context_snapshot
review_outcome
reviewed_by
notes
```

التفسير:

- `fattening_period` → الحيوان والمسار الذي تتم مراجعته.
- `reviewed_at` → وقت المراجعة الفعلية.
- `review_trigger` → لماذا أصبحت المراجعة مطلوبة.
- `weight_reference` → مرجع الوزن المستخدم.
- `evaluation_context_snapshot` → لقطة تدقيقية للسياق الذي كان ظاهرًا وقت القرار.
- `review_outcome` → القرار الناتج من المراجعة.
- `reviewed_by` → صاحب القرار.
- `notes` → ملاحظات.

#### Snapshot لا تصبح مصدر حقيقة جديدًا

وجود `evaluation_context_snapshot` لا يعني إنشاء مصدر Canonical جديد لمؤشرات النمو أو المدة.

الصحيح:

```text
Canonical Weight History / Dates
→ source truth

Evaluation Context Snapshot
→ historical audit of what was used at review time
```

لذلك لا تستخدم الـSnapshot لتعديل Weight History أو لإعادة كتابة المدة الفعلية.

---

### 4.9 `fattening.sale_handoff_model`

القرار:

```text
create_pending_exit_transition
```

عندما يصبح الحيوان جاهزًا للبيع أو يتم اعتماد البيع بالوضع الحالي:

```text
Sale Readiness / Sale Direction
        ↓
Pending Exit Transition
        ↓
4.15 Farm Exit Workflow
        ↓
Actual Sale / Exit Event
```

4.12 لا يسجل:

- تاريخ البيع الفعلي.
- وزن الخروج الفعلي.
- الجهة المستقبلة كواقعة خروج نهائية.
- إخلاء القفص كحركة فعلية مستقلة عن 4.2/4.15 integration.
- تغيير Presence إلى خارج المزرعة.

كل هذا يحدث عند Event الخروج الفعلي في 4.15.

#### فجوة Lifecycle للانتقال الناتج من جاهزية مشتقة

بما أن `ready_for_sale` **مشتقة تلقائيًا** وليست اعتمادًا يدويًا، بينما هذا السؤال يقول إن الجاهزية يمكن أن تنشئ `Pending Exit Transition`، يبقى مطلوبًا لاحقًا حسم:

- هل ينشأ Transition تلقائيًا أول مرة تصبح فيها النتيجة `ready_for_sale`؟
- كيف يمنع إنشاء Transition مكرر عند كل إعادة حساب؟
- ماذا يحدث إذا تغيرت البيانات وأصبحت النتيجة `not_ready` قبل تنفيذ الخروج؟
- هل يلغى / يعلق Transition السابق أم يبقى كقرار تاريخي؟

هذه التفاصيل غير محسومة في الإجابات الحالية، ولا يجوز اختراع Lifecycle لها من الـGuide.

أما `plan_sale_at_current_state` فهو قرار مراجعة فعلي، ولذلك مسار handoff منه أوضح من حالة الجاهزية المشتقة وحدها.

---

### 4.10 `fattening.path_closure_model`

القرار:

```text
close_on_terminal_actual_event
```

أي أن Fattening Period لا تغلق بسبب:

```text
ready_for_sale
```

ولا بسبب:

```text
planned sale
```

ولا بسبب:

```text
pending exit transition
```

بل تظل مفتوحة حتى Event نهائي فعلي مثل:

```text
Actual Sale / Farm Exit
Approved actual transition to another operational path
Mortality
```

وهذه قاعدة مهمة لضمان أن الحيوان يظل في مساره التشغيلي الحقيقي طالما لم ينفذ الحدث النهائي.

---

## 5. التكامل مع 4.10 — تعارض Boundary بداية التسمين

هناك تعارض عابر مباشر بين القرارين:

### 4.10

```text
fate_decision.downstream_transition_model
= create_pending_downstream_transition
```

المعنى هناك:

```text
Approved Fate Decision
→ Pending downstream transition
→ actual downstream workflow starts later
```

### 4.12

```text
fattening.entry_boundary_model
= start_on_fattening_fate_decision
```

المعنى هنا:

```text
Approved Fattening Fate Decision
= Fattening Period starts immediately
```

إذن السؤال النهائي الذي يحتاج حسمًا:

```text
هل قرار التسمين نفسه يبدأ Fattening Period؟

أم

قرار التسمين ينشئ Pending Transition فقط
ثم يبدأ Fattening Period في نقطة تنفيذ لاحقة؟
```

الـGuide يحافظ على القرارين كما هما ولا يختار بينهما.

---

## 6. فجوة تكامل `housing_reference` عند بداية التسمين

الإجابات الحالية تجمع بين:

```text
Fattening starts before housing movement may complete
```

وبين اختيار حقل:

```text
housing_reference
= مرجع موقع التسكين عند بداية التسمين
```

لذلك لا يمكن من الإجابات الحالية افتراض أن `housing_reference` هو دائمًا **قفص التسمين النهائي** لحظة `started_at`.

قد تكون الحركة ما زالت Pending.

المطلوب حسمه لاحقًا في الـBlueprint النهائي هو معنى الحقل بالضبط، مثل أحد النماذج الممكنة دون اعتماد أي منها هنا:

```text
current housing at the decision timestamp
or
planned fattening housing
or
nullable until actual housing completes
or
reference added/linked after movement
```

القرار الحالي لا يحدد أي نموذج منها، ولذلك يجب عدم اختراع واحد أثناء التنفيذ.

---

## 7. التكامل مع 4.3 — الوزن ومؤشرات النمو

كل وزن فعلي في التسمين يبقى ضمن:

```text
Canonical Weight History → 4.3
```

4.12 يستخدم:

```text
start_weight_reference
latest_weight
fattening_weight_history
derived_growth_metrics
weight_reference in review
```

لكن لا يملك مصدر وزن مستقلًا.

المؤشرات مثل:

```text
Weight Gain
Average Daily Gain
Growth Rate
```

تشتق من سجلات الوزن ولا تدخل يدويًا كحقائق موازية ما لم يحسم قسم آخر خلاف ذلك.

الـSnapshot في Fattening Review للأغراض التاريخية والتدقيقية فقط.

---

## 8. التكامل مع 4.15 — الجاهزية ليست خروجًا

القراران متوافقان في المبدأ:

```text
4.12
Ready / Plan Sale
→ Pending Exit Transition

4.15
Actual Farm Exit Event
→ changes actual presence / closes operational consequences
```

و4.15 يحتفظ في Event الخروج الفعلي بمرجع الوزن والموقع والسبب والجهة والمصدر عند انطباقها.

بالتالي:

```text
Sale Readiness Timestamp
≠
Sale / Exit Timestamp
```

وقد توجد فترة بينهما يظل فيها الحيوان:

- موجودًا فعليًا في المزرعة.
- مشغولًا لموقع إيواء.
- داخل Fattening Period مفتوحة.
- قابلًا لحدوث وزن أو صحة أو مراجعة جديدة.

وهذا متسق مع `close_on_terminal_actual_event`.

---

## 9. التكامل مع Settings 6.10

4.12 يحدد **ما يحدث فعليًا وما النتيجة التشغيلية**، بينما 6.10 يجب أن يحدد قواعد الحساب نفسها.

حاليًا 6.10 غير مجاب عنه، ولذلك لا توجد أرقام أو قواعد نهائية معتمدة لـ:

- العمر المستهدف لنهاية التسمين.
- الوزن المستهدف للبيع.
- الحد الأدنى المقبول لوزن البيع.
- الحد الأقصى لمدة التسمين.
- معدل النمو المستهدف.
- حد انخفاض النمو.
- دورية الوزن أثناء التسمين.
- منطق دمج العمر والوزن والنمو والصحة والمدة في Sale Readiness.

إذن لا يجوز تحويل أمثلة `تصور_مشروع_الارانب.md` إلى Hardcoded Requirements.

```text
Actual Facts → 4.12 / 4.3 / 4.13
Rules / Thresholds → 6.10
```

---

## 10. حدود لا يجوز استنتاجها من هذا القسم

الإجابات الحالية لا تحسم:

- قيم العمر أو الوزن المستهدفة.
- وحدة الوزن؛ تعتمد على وحدة النظام في 4.3.
- دورية الوزن أثناء التسمين.
- شكل Batch Session النهائي وما أنواع العمليات التي يسمح بتجميعها.
- السماح العام بالنقل الجماعي؛ 4.2 هو الحاكم للحركات.
- نوع أو سعة قفص التسمين؛ Farm Structure / Housing Rules هي الحاكمة.
- هل `housing_reference` عند البداية Current أم Planned أم nullable.
- Lifecycle الكامل لـPending Exit Transition الناتج من readiness مشتقة.
- هل الجاهزية للبيع تعني وجود مشتري أو عملية بيع تجارية؛ لا.
- السعر أو الفاتورة أو تفاصيل المبيعات المالية؛ التصور الوظيفي يعتبرها توسعًا مستقبليًا خارج دورة الإنتاج الحالية.
- قواعد صحة تمنع البيع أو تستوجب Override؛ مكانها Settings / Health rules.

---

## 11. فحص الاتساق

### داخل 4.12

القسم متماسك داخليًا في المبادئ التالية:

```text
Individual tracking always preserved
Canonical Weight History preserved
Sale Readiness is derived
Target miss does not cause automatic exit
Sale readiness does not equal sale
Pending exit handoff precedes actual exit
Fattening stays open until actual terminal event
```

### تعارض عابر

يوجد تعارض مباشر مع 4.10 في Boundary بداية Fattening Period:

```text
4.10 → pending downstream transition first
4.12 → Fattening starts at Fate Decision itself
```

### فجوات تكامل

1. معنى `housing_reference` عندما يبدأ التسمين قبل اكتمال التسكين.
2. Lifecycle / idempotency لـPending Exit Transition الناتج من `ready_for_sale` المشتقة تلقائيًا.
3. العلاقة التنفيذية بين `performed_by` في بداية التسمين وصاحب Fate Decision، لعدم وجود Start Event مستقل في القرار الحالي.

هذه النقاط يجب أن تبقى مفتوحة حتى قرار صريح أو مرحلة تجميع Requirements نهائية.

---

## 12. المخرجات المطلوبة للـRequirements

يجب أن تغطي المتطلبات النهائية على الأقل:

1. دعم `FatteningPeriod` فردية لكل Animal Record.
2. الحفاظ على نفس هوية الحيوان وتاريخه السابق عند دخوله التسمين.
3. ربط بداية التسمين بقرار المصير وفق القرار الحالي، مع إبقاء تعارض 4.10 ظاهرًا حتى الحسم.
4. دعم حقول بداية التسمين التسعة المعتمدة دون إنشاء مصدر وزن أو موقع متنافس.
5. السماح بـBatch Sessions اختيارية دون إلغاء التتبع الفردي ودون تجاوز قواعد 4.2 للحركات.
6. عرض تقدم التسمين من العمر والأوزان ومؤشرات النمو والمدة والصحة والمراجعات.
7. اشتقاق Sale Readiness تلقائيًا من Records + Settings 6.10، لا من Status يدوي.
8. دعم نتائج الجاهزية الخمس المعتمدة.
9. دعم Fattening Review ونتائجها الست عند عدم الوصول للهدف أو الحاجة لقرار.
10. حفظ مراجعات التسمين تاريخيًا مع Weight Reference وAudit Context Snapshot دون تحويل الـSnapshot لمصدر حقيقة.
11. عدم خروج الحيوان تلقائيًا عند تجاوز العمر/المدة أو عدم بلوغ الوزن المستهدف.
12. إنشاء Pending Exit Transition عند الجاهزية/قرار البيع وفق الإجابة الحالية، مع حسم Lifecycle هذا الانتقال لاحقًا.
13. تنفيذ البيع والخروج الفعلي في 4.15 فقط.
14. إبقاء Fattening Period مفتوحة حتى Terminal Actual Event.
15. عدم Hardcode أي Threshold أو Schedule قبل إجابات Settings 6.10.
16. حسم معنى `housing_reference` عند البداية قبل تصميم Schema النهائي.

---

## 13. نتيجة المراجعة الحالية

**القسم صالح للتقدم ولا يوجد تعارض داخلي مانع.**

لكن توجد ثلاث نقاط يجب أن تبقى ظاهرة في التجميع النهائي:

```text
A. 4.10 Pending Downstream Transition
   vs
   4.12 Start Fattening on Fate Decision

B. housing_reference
   when actual fattening housing may still be pending

C. automatically derived ready_for_sale
   → pending exit transition lifecycle / duplicate prevention
```

ولا يغير هذا الملف أيًا من الإجابات الحالية.