# 4.7 الرضاعة ومتابعة البطن وتداخل دورات الأم — دليل تفسير الإجابات

> **الحالة:** مسودة أولى مبنية على الإجابات الحالية للمراجعة — القسم متماسك داخليًا، مع تعارضين عابرين بين الأقسام يخصان نفوق المواليد أثناء الرضاعة ونموذج وزن المواليد قبل الفطام  
> **ملف الإجابات المقابل:** `questionnaire-export/answers/04-workflow/07-lactations.md`  
> **Question Keys المغطاة:** 12 / 12

---

## 1. الغرض من هذا الملف

هذا الملف يفسر **المعنى الوظيفي والتقني للإجابات المعتمدة فعليًا** في قسم `الرضاعة ومتابعة البطن وتداخل دورات الأم` ضمن `الحركات ودورة التشغيل الفعلية`.

هو ليس مصدرًا بديلًا للإجابات، ولا يغير أي قرار موجود في ملف الإجابات.

```text
answers/04-workflow/07-lactations.md
= ماذا قررنا؟

                    ↓ تفسير

guides/04-workflow/07-lactations.md
= ماذا تعني هذه القرارات تقنيًا؟ وما حدودها؟
```

القاعدة المعمارية الأساسية لهذا القسم:

```text
Birth Event / Litter Creation
→ 4.6

Lactation / Litter Follow-up / Foster Transfer / Current Alive Count
→ 4.7

Actual Mating during Lactation
→ 4.4

Mating / Remating Readiness Rules
→ Settings 6.5 / 6.7

Actual Weight Measurement
→ 4.3

Mortality
→ يوجد تعارض عابر يحتاج حسمًا بين قرار 4.7 الحالي والحد المعماري الذي يضع النفوق العام في 4.13

Actual Weaning / Final Individual Identity
→ 4.8

Health Cause / Health Episode
→ 4.13
```

---

## 2. الهدف الوظيفي من القسم

الغرض من القسم هو إدارة **ما يحدث فعليًا للبطن والأم أثناء فترة الرضاعة** بعد إنشاء Litter Record، مع الحفاظ على الفصل بين:

```text
Birth Facts
≠
Current Litter State
≠
Lactation Follow-up Events
≠
Foster Care Relationship
≠
Biological Origin
≠
New Reproductive Cycle of Mother
≠
Weaning
```

الصورة التشغيلية الحالية:

```text
Birth Event
→ Explicit Litter Creation in 4.6
→ if live offspring > 0
→ Lactation starts automatically
→ Follow-up Events
→ Mortality / Foster Transfers / Maternal Actions
→ Current Alive Count derived from events
→ Mother may start a new reproductive cycle concurrently
→ Weaning or another documented terminal event ends lactation
```

ومن أهم قواعد التصميم هنا أن الأنثى لا يجب اختزالها في Status إنتاجي واحد فقط؛ فمن الممكن أن تكون في الوقت نفسه:

```text
Lactating Mother
+
New Mating Attempt / New Reproductive Cycle
+
Waiting for Pregnancy Check
```

لذلك تداخل المسارات يحفظ كعلاقات وأحداث مستقلة، لا كتبديل لحالة واحدة تمحو السياق السابق.

---

## 3. ملخص القرارات المعتمدة

الإجابات الحالية تحسم النموذج التالي:

```text
Lactation Start
└── automatic when a Litter with live offspring is created

Current Alive Count
└── derived from litter events
    ├── live born
    ├── mortality
    ├── foster transfers out
    └── foster transfers in

Lactation Follow-up
├── litter condition review
├── mother-with-litter review
├── weak offspring observation
├── growth observation
├── Weight History reference
└── other documented follow-up

Mortality during Lactation
└── dedicated lactation mortality event
    ⚠ cross-section conflict with current project architecture / 4.13 boundary

Foster Transfer
└── structured workflow
    ├── partial transfer
    ├── full transfer
    ├── multi-destination distribution
    └── receive from another litter

Transferred Offspring before Weaning
└── temporary internal identifier per transferred offspring

Origin / Foster Relationship
├── biological mother remains unchanged
├── original litter remains unchanged
└── separate foster mother / current lactation group relationship

Maternal / Lactation Problems
├── continue monitoring
├── transfer to foster mother
├── distribute across foster mothers
├── alternative care
├── early weaning if rules allow
└── other documented action

Overlapping Reproductive Cycles
└── explicit link from the new cycle to active litter / lactation context

Lactation End
└── weaning or another documented terminal event
```

---

## 4. تفسير Question Keys والإجابات

### 4.1 `lactation.start_model`

القرار:

```text
auto_start_for_litter_with_live_offspring
```

المعنى أن الرضاعة لا تحتاج `Start Lactation Action` مستقلة بعد وجود Litter Record صالح يحتوي على مواليد أحياء.

لكن يجب قراءة هذا القرار مع قرار 4.6:

```text
Birth Event
→ Litter is NOT auto-created
→ user explicitly creates Litter
→ if Litter has live offspring
→ Lactation starts automatically
```

إذن:

```text
Birth registration
≠
automatic Litter creation
```

لكن:

```text
Litter created with live offspring
=
automatic Lactation start
```

وهذا متسق مع قرار 4.6 الذي يغلق البطن مباشرة بدون رضاعة إذا لم يوجد أي مولود حي.

---

### 4.2 `lactation.current_alive_count_model`

القرار:

```text
derive_from_litter_events
```

أي أن `Current Alive Count` ليس رقمًا يحرره المستخدم يدويًا.

القاعدة المفاهيمية:

```text
Current Alive Count
=
Live Born at Birth
- Post-birth Mortality
- Foster Transfers Out
+ Foster Transfers In
- أي أحداث موثقة أخرى تنهي وجود المولود تحت رعاية هذا البطن
```

مع ملاحظة أن الصيغة التنفيذية النهائية يجب أن تعتمد فقط على أنواع الأحداث التي سيتم اعتمادها فعليًا في النظام.

الأرقام التاريخية الأصلية في 4.6 تبقى ثابتة:

```text
live_born at birth
stillborn_at_birth
 total_born
```

ولا يعاد تعديلها بسبب أحداث حدثت بعد الولادة.

إذن:

```text
Birth Counts = historical facts
Current Alive Count = derived operational state
```

---

### 4.3 `lactation.followup_event_types`

تم اعتماد كل الأنواع التالية:

```text
litter_condition_review
mother_with_litter_review
weak_offspring_observation
growth_observation
weight_measurement_reference
other_followup
```

التفسير:

- `litter_condition_review` → مراجعة حالة البطن ككل.
- `mother_with_litter_review` → متابعة حالة الأم في سياق الرعاية والرضاعة.
- `weak_offspring_observation` → توثيق وجود مواليد ضعيفة أو تحتاج متابعة.
- `growth_observation` → ملاحظات نمو وتطور غير مساوية لسجل الوزن نفسه.
- `weight_measurement_reference` → ربط المتابعة بسجل وزن Canonical من 4.3 بدل نسخ قيمة الوزن داخل 4.7.
- `other_followup` → يسمح بحدث متابعة آخر موثق، لكن لا يعرّف قائمة أو Taxonomy جديدة من نفسه.

وجود نوع متابعة لا يعني أن النظام ينشئها تلقائيًا أو في توقيت ثابت.

```text
Supported Follow-up Type
≠
Scheduled Follow-up Rule
```

الدورية والتوقيت والمحتوى المطلوب عند كل مراجعة تبقى في Settings 6.7.

#### نقطة ترابط مع 4.3

إذا كان الوزن المشار إليه هو **وزن الأم** أثناء الرضاعة، فـ4.3 يدعمه لأنها Animal Record فردي.

أما إذا كان المقصود **وزن البطن أو المواليد قبل الفطام**، فهناك تعارض عابر موضح في قسم المراجعة المفتوحة؛ لأن 4.3 حصر Subject الحالي في `individual_animal` ولم يعتمد `preweaning_litter`.

---

### 4.4 `lactation.mortality_recording_model`

القرار الحالي:

```text
dedicated_lactation_mortality_event
```

أي أن نفوق المواليد بعد الولادة أثناء الرضاعة يصبح **حدث نفوق خاصًا بالرضاعة منفصلًا عن سجل النفوق العام**.

النتيجة داخل 4.7 نفسها واضحة:

```text
Lactation Mortality Event
→ affects Current Alive Count
→ does NOT change live_born historical count
```

لكن هذا القرار يحمل **تعارضًا معماريًا عابرًا بين الأقسام**، لأن Project Context يضع:

```text
mortality after birth → 4.13
```

كما أن 4.13 نفسه مصمم أصلًا بحيث يمكن لسجل النفوق العام أن يمثل:

```text
preweaning_litter_quantity
identified_preweaning_offspring
```

ويربط نفوق ما قبل الفطام بالبطن ويحدث العدد الحالي دون تعديل Birth Counts.

لذلك القرار الحالي في 4.7 يجب الحفاظ عليه كما هو في الإجابات، لكن لا يجوز في الـBlueprint النهائي تجاهل التعارض أو بناء مصدرين نهائيين للنفوق بدون حسم صريح لاحق.

---

### 4.5 `lactation.foster_transfer_model`

القرار:

```text
structured_foster_transfer
```

أي أن نقل المواليد بين الأمهات ليس مجرد Note أو Exception عامة.

يجب وجود Workflow منظم يوضح:

```text
Source Litter
→ Transfer Event
→ Destination Litter / Foster Mother
```

ويحفظ ما حدث فعليًا حتى يمكن إعادة بناء:

- العدد الحالي لكل بطن.
- الأصل البيولوجي.
- علاقة الحضانة الحالية.
- تاريخ انتقال المواليد.
- حالة الأفراد عند الوصول للفطام.

---

### 4.6 `lactation.foster_transfer_scopes`

تم اعتماد جميع النطاقات:

```text
partial_transfer
full_transfer
multi_destination_distribution
receive_from_other_litter
```

المعنى أن Workflow الحضانة البديلة يجب أن يكون قادرًا على تمثيل:

1. نقل جزء فقط من بطن.
2. نقل كل المواليد الأحياء من بطن.
3. توزيع مواليد بطن واحدة على أكثر من أم حاضنة.
4. استقبال بطن حالي لمواليد من بطن أخرى.

هذا يعني أن `Foster Transfer` ليس بالضرورة علاقة One-to-One بسيطة بين بطنين فقط.

في حالة التوزيع على عدة وجهات يجب أن تسمح البنية بتسجيل كل توزيع بشكل يمكن معه معرفة:

```text
Source
Destination
Transferred Quantity / Offspring
Occurred At
```

بدون فقد القدرة على إعادة بناء العدد في كل بطن.

حدود السعة، عدد المواليد المناسب للأم الحاضنة، وأهلية الأم الحاضنة ليست محسومة هنا؛ مكانها Settings.

---

### 4.7 `lactation.foster_transfer_record_fields`

تم اعتماد الحقول:

```text
source_litter
destination_litter_or_foster_mother
offspring_count
occurred_at
age_at_transfer
reason
performed_by
notes
```

التفسير:

- `source_litter` → البطن الذي خرجت منه المواليد.
- `destination_litter_or_foster_mother` → الجهة المستقبلة.
- `offspring_count` → عدد المنقولين في الواقعة.
- `occurred_at` → وقت النقل الفعلي.
- `age_at_transfer` → العمر وقت النقل؛ يمكن اشتقاقه من Birth Date متى كان الأصل معروفًا.
- `reason` → سبب العملية.
- `performed_by` → المنفذ / المسجل.
- `notes` → تفاصيل إضافية.

هذا القسم **لا يحسم** أن `reason` مرتبط بقائمة Master Data معينة، لذلك لا يجوز اختراع `FosterTransferReason` أو إعادة استخدام Lookup آخر بلا قرار صريح.

كما لا يعني اختيار الحقول هنا تلقائيًا أن جميعها إلزامية في كل Scenario؛ Mandatory Rules النهائية تحتاج قرارًا صريحًا إذا لم تكن محسومة في السؤال نفسه.

---

### 4.8 `lactation.transferred_offspring_tracking_model`

القرار:

```text
temporary_identifier_per_transferred_offspring
```

وده قرار مهم جدًا لحماية الأصل عند النقل الجزئي.

قبل الفطام لا يزال الأصل العام للنظام:

```text
Litter-level management
```

لكن إذا نقلنا جزءًا من البطن، فإن العدد وحده لا يكفي لمعرفة عند الفطام أي فرد كان منقولًا من أي أصل.

لذلك يسمح النظام بوجود:

```text
Temporary Pre-weaning Identifier
```

لكل مولود منقول.

هذا المعرف:

- ليس `Animal Code` النهائي.
- لا يحول المولود تلقائيًا إلى Animal Record كامل قبل 4.8.
- وظيفته Traceability خلال الفترة المؤقتة فقط.
- يجب أن يبقى قابلًا للربط بالفرد النهائي عند الفطام.

وهذا متسق مع 4.8 الذي يشترط عند إنشاء Animal Record النهائي حسم:

```text
Biological Mother
Original Litter
Foster Mother
Current Lactation Group
Pre-weaning Transfer Reference
```

قبل اعتماد الأصل النهائي للفرد.

---

### 4.9 `lactation.foster_relationship_model`

القرار:

```text
preserve_biological_origin_add_foster_relation
```

القاعدة هنا أساسية:

```text
Biological Mother
≠
Foster Mother
```

وعند النقل:

```text
Biological Mother = unchanged
Original Litter = unchanged
Foster Mother = separate relationship
Current Lactation Group = may change
```

مثال مفاهيمي:

```text
Biological Mother: F-010
Original Litter: L-015

Transferred for nursing to:
Foster Mother: F-025
Current Lactation Group: L-020
```

عند الفطام يجب ألا يتحول `L-020` إلى الأصل البيولوجي بسبب كونه مكان الرعاية الحالي.

هذه القاعدة متوافقة مع قاعدة المشروع العامة:

```text
Biological Mother remains separate from Foster Mother
```

وتحمي شجرة النسب والتقارير المستقبلية من التلوث.

---

### 4.10 `lactation.maternal_problem_outcomes`

تم اعتماد جميع الإجراءات التالية:

```text
continue_monitoring
transfer_to_foster_mother
distribute_across_foster_mothers
alternative_care
early_weaning_if_allowed
other_action
```

المعنى أن المشكلة التي تؤثر على قدرة الأم على الرعاية لا تؤدي إلى Outcome واحد ثابت.

قد يحدث مثلًا:

```text
Maternal Problem
→ Continue Monitoring
```

أو:

```text
Maternal Problem
→ Foster Transfer
```

أو:

```text
Maternal Problem
→ Early Weaning
```

لكن `early_weaning_if_allowed` يعني بوضوح:

```text
Workflow supports the action
+
Settings must allow it
```

ولا يعطي 4.7 وحده حق تجاوز قواعد الفطام في 6.8 أو تنفيذ الفطام خارج 4.8.

إذا كانت المشكلة صحية، أصل المشكلة الصحية نفسه ينتمي إلى 4.13، بينما 4.7 يحتفظ بأثرها على إدارة البطن والرعاية.

---

### 4.11 `lactation.concurrent_cycle_link_model`

القرار:

```text
explicit_active_litter_context_link
```

أي أنه عند بدء دورة تناسلية جديدة للأم بينما ما زالت ترضع بطنًا نشطًا، لا نكتفي باستنتاج التداخل من التواريخ فقط.

يجب حفظ علاقة صريحة مثل:

```text
New Reproductive Cycle
└── started while mother was caring for Active Litter X
```

الغرض ليس دمج المسارين، وإنما حفظ سياقهما المتوازي.

```text
Lactation Lifecycle
and
Reproductive Cycle Lifecycle
```

يستمران كمسارين مستقلين يمكن أن يتداخلا زمنيًا.

التلقيح الفعلي يبقى في 4.4، وشروط السماح بإعادة التلقيح أثناء الرضاعة أو توقيته تبقى في Settings.

هذا يمنع تصميمًا خاطئًا من النوع:

```text
female_status = lactating
```

ثم استبداله بـ:

```text
female_status = waiting_pregnancy_check
```

لأن الاستبدال سيمحو حقيقة أن الأم لا تزال ترضع البطن السابقة.

---

### 4.12 `lactation.end_model`

القرار:

```text
end_by_weaning_or_documented_terminal_event
```

أي أن مرحلة الرضاعة لا تغلق يدويًا كحالة غامضة.

النهاية الطبيعية:

```text
Actual Weaning Event → 4.8
```

لكن يمكن أن تنتهي قبل الفطام إذا وقع حدث موثق جعل البطن بلا مواليد تحت الرعاية، مثل:

- فقد جميع المواليد وفق نموذج النفوق النهائي المعتمد.
- نقل جميع المواليد إلى جهة رعاية أخرى.
- أي Terminal Event آخر يتم اعتماده صراحة ضمن Workflow.

المهم:

```text
Lactation End
must have a documented cause/event
```

وليس:

```text
manual close without traceable reason
```

---

## 5. العلاقة مع 4.6 — الولادة وإنشاء البطن

الحد بين القسمين:

```text
4.6
Birth Event
Birth Counts
Explicit Litter Creation

4.7
Lactation Start
Current Alive Count
Litter Follow-up
Foster Transfers
Maternal Management Actions
Lactation End
```

قرارات القسمين متوافقة في نقطة البداية:

```text
Birth Event
→ explicit Litter creation
→ live offspring exist
→ Lactation auto-starts
```

أما `live_born` و`stillborn_at_birth` فهي حقائق الولادة ولا يعاد تحريرها من 4.7.

---

## 6. العلاقة مع 4.8 — الفطام والتحول للتتبع الفردي

4.7 يدير المواليد قبل الفطام، بينما 4.8 يمثل نقطة التحول إلى Animal Records فردية دائمة.

```text
Before Weaning
→ Litter / temporary pre-weaning tracking

At Weaning
→ origin reconciliation
→ individual Animal Record
→ permanent Animal Identity
```

قرار `temporary_identifier_per_transferred_offspring` لا يتعارض مع هذه القاعدة، لأنه معرف تتبع مؤقت وليس كود الحيوان النهائي.

4.8 يؤكد أيضًا أن بيانات مثل:

```text
biological_mother
original_litter
foster_mother_if_applicable
current_lactation_group_if_applicable
preweaning_transfer_reference
```

تنتقل إلى السجل الفردي، وأن أصل الفرد يجب حسمه قبل الاعتماد النهائي.

---

## 7. العلاقة مع Settings

4.7 يسجل **ما حدث فعليًا** ولا يحدد القواعد الرقمية أو جداول الاستحقاق.

Settings 6.7 / 6.8 يجب أن تحسم لاحقًا أمورًا مثل:

- مدة الرضاعة المستهدفة.
- دورية متابعة البطن.
- برنامج وزن المواليد أثناء الرضاعة.
- شروط الأم الحاضنة.
- الحد الأقصى أو السعة المناسبة للأم الحاضنة.
- قواعد الفطام المبكر.
- توقيت الاستعداد للفطام.
- إعادة تلقيح الأم أثناء الرضاعة.
- التأجيل وإعادة التقييم.

ملف 6.7 الحالي ما زال غير مجاب عنه، لذلك لا يجوز لهذا الـGuide اختراع أي أرقام أو مدد أو Thresholds.

---

## 8. التعارضات والنقاط المفتوحة

### 8.1 تعارض معماري: نفوق المواليد أثناء الرضاعة

**القرار الحالي في 4.7:**

```text
lactation.mortality_recording_model
= dedicated_lactation_mortality_event
```

أي Event خاص مستقل للنفوق أثناء الرضاعة.

لكن `FARM_BLUEPRINT_PROJECT_CONTEXT.md` يضع الحد:

```text
mortality after birth → 4.13
```

و4.13 مصمم ليكون قادرًا على تمثيل:

```text
preweaning_litter_quantity
identified_preweaning_offspring
```

#### أثر التعارض

إذا نفذنا القرارين معًا حرفيًا قد يصبح لدينا:

```text
Lactation Mortality Event
+
General Mortality Event
```

لنفس الواقعة، أو مصدران مختلفان لحساب النفوق والتقارير.

وهذا يهدد مبدأ Single Source of Truth.

#### الوضع الحالي

لا يتم حسم الحل من هذا الـGuide.

يبقى القرار الحالي محفوظًا، لكن يجب قبل Final Requirements تحديد هل:

1. يبقى Event الرضاعة هو المصدر الفعلي ويتم تعديل Boundary 4.13، أو
2. يعود النفوق إلى Canonical Mortality Event في 4.13 ويرتبط بالبطن، أو
3. يوجد نموذج آخر يمنع الازدواجية ويحدد بوضوح أي سجل هو Canonical.

---

### 8.2 تعارض عابر: وزن المواليد أثناء الرضاعة مقابل 4.3

4.7 اعتمد:

```text
weight_measurement_reference
```

والمرجع الوظيفي يتحدث صراحة عن وزن المواليد أثناء الرضاعة.

لكن قرار 4.3 الحالي هو:

```text
operational_measurement.subject_types
= individual_animal only
```

ولم يعتمد:

```text
preweaning_litter
```

#### ما الذي يعمل حاليًا؟

وزن الأم أثناء الرضاعة يمكن ربطه بـ4.3 لأنها Animal Record فردي.

#### ما الذي لا يزال غير محسوم؟

وزن:

```text
Litter as a whole
or
Pre-weaning offspring without final Animal Record
```

لا يملك حاليًا Subject Model متوافقًا داخل 4.3.

حتى وجود Temporary Identifier للمواليد المنقولين في 4.7 لا يعني تلقائيًا أنهم أصبحوا Subject مدعومًا في Weight History.

لذلك برنامج وزن المواليد الموجود في Settings 6.7 لا يمكن اعتباره قابلًا للتنفيذ نهائيًا قبل حسم هذا التعارض.

---

## 9. الاتساق الداخلي للقسم

باستثناء التعارضين العابرين أعلاه، قرارات 4.7 نفسها متناسقة:

```text
Explicit Litter exists with live offspring
→ auto-start lactation

Current Alive Count
→ derived from events

Foster Transfer
→ structured and historical

Transferred offspring
→ temporary traceable identity

Biological origin
→ immutable through foster care

Maternal problem
→ documented operational outcome

New reproductive cycle during lactation
→ explicit overlap relation

Lactation End
→ Weaning or documented terminal event
```

ولا يوجد داخل إجابات القسم نفسها Dependency مكسورة أو سؤال غير مطبق.

```text
Total Questions = 12
Applicable = 12
Answered = 12
Unanswered = 0
Open Review = 0
```

---

## 10. ما لا يجوز استنتاجه من الإجابات الحالية

لا يجوز استنتاج أي من التالي بدون قرار لاحق:

- مدة الرضاعة بالأيام.
- مواعيد أو دورية متابعة البطن.
- عدد مرات أو مواعيد وزن المواليد.
- طريقة نهائية لوزن البطن قبل الفطام في ظل تعارض 4.3 الحالي.
- الحد الأقصى للمواليد عند الأم الحاضنة.
- شروط اختيار الأم الحاضنة.
- هل سبب Foster Transfer Lookup من Master Data أم نص/مرجع آخر.
- متى يسمح بالفطام المبكر بالأرقام أو المعايير.
- توقيت إعادة تلقيح الأم أثناء الرضاعة.
- قواعد التأجيل أو إعادة المحاولة.
- الشكل النهائي لـLactation Status أو Litter Current State كEnum مخزن يدويًا.
- أن Temporary Pre-weaning Identifier هو Animal Code النهائي.
- أن الأم الحاضنة تستبدل الأم البيولوجية.
- أن `dedicated_lactation_mortality_event` يمكن تنفيذه بالتوازي مع General Mortality Event بدون قرار يمنع الازدواجية.

---

## 11. Requirements outputs المستخرجة من القسم

يمكن تحويل الإجابات الحالية إلى المتطلبات التالية:

1. **Automatic Lactation Start** عند إنشاء Litter بها مواليد أحياء.
2. **Derived Current Alive Count** من الأحداث بدل التعديل اليدوي.
3. **Lactation Follow-up Events** لحالة البطن والأم والمواليد والنمو.
4. **Weight History Linking** بدل تكرار قيمة الوزن داخل 4.7، مع بقاء مشكلة Subject قبل الفطام مفتوحة.
5. **Dedicated Lactation Mortality Model — Conflict Pending** حسب القرار الحالي، مع ضرورة حسم تعارضه مع 4.13.
6. **Structured Foster Transfer Workflow**.
7. **Partial / Full / Multi-destination Foster Transfers**.
8. **Foster Transfer Audit Fields** للمصدر والوجهة والعدد والتوقيت والعمر والسبب والمنفذ والملاحظات.
9. **Temporary Pre-weaning Offspring Identity** للمواليد المنقولين.
10. **Biological Origin Preservation** عند الحضانة البديلة.
11. **Separate Foster Relationship** عن النسب البيولوجي.
12. **Maternal Problem Operational Outcomes** متعددة ومؤرخة.
13. **Early Weaning Integration** فقط عندما تسمح Settings وتنفيذ الفطام عبر 4.8.
14. **Explicit Concurrent Cycle Link** بين الدورة الجديدة وسياق البطن النشطة.
15. **Concurrent Lifecycle Support** للأم بدل Status إنتاجي واحد.
16. **Event-driven Lactation End** بالفطام أو Terminal Event موثق.
17. **Pre-weaning to Final Identity Handoff** إلى 4.8 مع الحفاظ على الأصل ومراجع النقل.
18. **Settings Boundary** للمدد والجداول والسعة والجاهزية وإعادة التلقيح.

---

## 12. مراجعة التنفيذ النهائية

الوضع الحالي للقسم:

```text
Question coverage: 12 / 12
Internal consistency: PASS
Dependency consistency: PASS
Foster-origin integrity: PASS
Concurrent-cycle model: PASS
Cross-section mortality architecture: OPEN CONFLICT
Pre-weaning weight integration with 4.3: OPEN CONFLICT
Settings-dependent numeric rules: NOT YET DECIDED
```

الخلاصة:

**4.7 أصبح يعرّف مسار رضاعة قويًا وواضحًا وظيفيًا، خصوصًا في اشتقاق العدد الحالي، الحضانة البديلة، الحفاظ على الأصل البيولوجي، والتعامل مع تداخل دورة تناسلية جديدة للأم أثناء استمرار الرضاعة. لا يوجد تعارض داخلي يمنع استخدام القسم، لكن قبل الـFinal Requirements يجب حسم مصدر الحقيقة الوحيد لنفوق المواليد أثناء الرضاعة، وكذلك كيفية تمثيل وزن المواليد قبل الفطام داخل Weight History الموحد في 4.3.**
