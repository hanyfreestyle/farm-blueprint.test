# 4.15 الخروج من المزرعة وإعادة الدخول — دليل تفسير الإجابات

> **الحالة:** مسودة أولى مبنية على الإجابات الحالية للمراجعة — القسم مكتمل 11/11، مع تعارضين عابرين مباشرين مع 4.1 و4.14، ونقطتي تكامل تحتاجان حسمًا تخصان إغلاق الإشغال بعد الخروج وتفسير حالة الفقد داخل نموذج الوجود  
> **ملف الإجابات المقابل:** `questionnaire-export/answers/04-workflow/15-farm-exits.md`  
> **Question Keys المغطاة:** 11 / 11

---

## 1. الغرض من هذا الملف

هذا الملف يفسر **المعنى الوظيفي والتقني للإجابات المعتمدة فعليًا** في قسم `الخروج من المزرعة وإعادة الدخول` ضمن `الحركات ودورة التشغيل الفعلية`.

هو لا يغير الإجابات، ولا يعيد تعريف النفوق أو الفقد أو النقل أو التسكين داخل هذا القسم، ولا ينشئ هوية جديدة للحيوان عند عودته.

```text
answers/04-workflow/15-farm-exits.md
= ماذا قررنا؟

                    ↓ تفسير

guides/04-workflow/15-farm-exits.md
= ماذا تعني القرارات تقنيًا؟ وما حدودها وتعارضاتها؟
```

الحد المعماري الأساسي:

```text
ExitReason
→ Master Data

Actual Farm Exit / Sale Exit / Re-entry
→ 4.15

Mortality
→ 4.13

Missing / Found
→ 4.14

Actual Housing Vacate / Housing
→ 4.2

Intake after Re-entry
→ 4.1

Workflow Reconstruction when active contexts are affected
→ 4.14
```

ويجب الحفاظ على الفصل:

```text
ExitReason
≠ Farm Exit Event
≠ Mortality Event
≠ Missing Event
≠ Fate / Exclusion Decision
```

---

## 2. الهدف الوظيفي من القسم

الغرض من 4.15 هو تسجيل **متى خرج الحيوان فعليًا من المزرعة، ولماذا، ومن أين، وما علاقته بأي قرار أو عملية سبقت الخروج**، ثم دعم عودة نفس الحيوان لاحقًا مع الحفاظ على هويته وتاريخه كاملًا.

النموذج الحالي:

```text
Animal inside farm
→ Farm Exit Event
→ Presence episode closes / animal becomes outside
→ history remains intact
→ later, if same animal returns
→ Re-entry Event linked to previous Exit
→ Intake 4.1 on same Animal Record
→ new Presence episode
```

لكن توجد نقاط تكامل مفتوحة حول **التوقيت الفعلي لعودة Presence** وحول **إغلاق Occupancy بعد الخروج**، موضحة في قسم التعارضات.

---

## 3. ملخص القرارات المعتمدة

الإجابات الحالية تحسم النموذج التالي:

```text
Canonical Specialized Events
├── Mortality → 4.13 only
├── Missing / Found → 4.14 only
└── no duplicate Farm Exit Event for those specialized events

Farm Exit Event Fields
├── animal
├── exited_at
├── age_at_exit
├── weight_reference
├── source_housing_reference
├── operational_stage
├── exit_reason_reference
├── destination_or_recipient
├── source_decision_or_operation_reference
├── performed_by
└── notes

Exit Reason
└── required for every Farm Exit Event
    └── `other` requires additional description

Active Context Before Exit
└── system detects active contexts
    └── they must be resolved / reconstructed before exit completes

Post-exit Effects
└── create pending actions/tasks
    ├── close occupancy
    ├── close/adjust active paths
    └── process invalid tasks

Batch Exit
└── supported
    └── one shared batch operation + independent Farm Exit Event per animal

Sale Commercial Scope
└── operational sale only
    └── no buyer / price requirement in this version

Inter-farm Transfer
└── linked Exit + Re-entry on same Animal Record

Re-entry Link
└── explicit reference to previous Exit Event

Presence on Re-entry
└── becomes present only after Intake 4.1 finalization

Presence History
└── append-only Presence Episodes
```

---

## 4. تفسير Question Keys والإجابات

### 4.1 `farm_exit.canonical_event_routing_model`

القرار:

```text
canonical_domain_event_only_derive_presence
```

المعنى أن الحدث الذي له سجل Canonical متخصص لا يعاد تسجيله كـFarm Exit Event مستقل.

أمثلة:

```text
Mortality Event in 4.13
→ no second Farm Exit Event

Missing / Found in 4.14
→ no second Farm Exit Event
```

الهدف هو منع:

```text
one real-world event
→ two competing historical records
```

لكن هذا القرار يجب تطبيقه **بحسب دلالة كل الحدث المتخصص**، وليس باعتبار كل Specialized Event خروجًا فعليًا بنفس المعنى.

خصوصًا:

```text
Mortality
→ animal is no longer physically present

Missing
→ current physical location is unknown / unreliable
→ not automatically a Farm Exit
```

وهذه نقطة تكامل موضحة لاحقًا.

---

### 4.2 `farm_exit.record_fields`

تم اعتماد جميع الحقول التالية:

```text
animal
exited_at
age_at_exit
weight_reference
source_housing_reference
operational_stage
exit_reason_reference
destination_or_recipient
source_decision_or_operation_reference
performed_by
notes
```

التفسير:

- `animal` → نفس Animal Record التاريخي.
- `exited_at` → وقت الخروج الفعلي، وليس وقت إدخال السجل فقط.
- `age_at_exit` → قيمة مشتقة عند إمكان تحديدها، وليست عمرًا جديدًا مستقلًا.
- `weight_reference` → مرجع Weight Record من 4.3 عند وجود وزن خروج أو آخر وزن موثوق.
- `source_housing_reference` → الموقع التاريخي الذي كان الحيوان يشغله وقت الخروج؛ الموقع نفسه مصدره 4.2.
- `operational_stage` → السياق التشغيلي وقت الخروج.
- `exit_reason_reference` → `ExitReason` من Master Data.
- `destination_or_recipient` → تستخدم عند انطباقها، لكنها لا تنشئ Customer/CRM Module من نفسها.
- `source_decision_or_operation_reference` → يسمح بربط الخروج بقرار بيع أو Fate Decision أو عملية أخرى سبقت الخروج.
- `performed_by` → المستخدم / المنفذ.
- `notes` → معلومات إضافية دون تحويلها إلى مصادر حقيقة بديلة.

الوزن والموقع الحاليان لا ينسخان كمصادر مستقلة تنافس 4.3 و4.2.

---

### 4.3 `farm_exit.reason_reference_policy`

القرار:

```text
reason_required_other_detail_required
```

إذن كل **Farm Exit Event فعلي** يجب أن يحمل مرجع `ExitReason`.

وعند اختيار:

```text
other
```

يجب وجود وصف إضافي يشرح السبب.

هذا الحكم يطبق فقط عندما يوجد Farm Exit Event أصلًا.

بالتالي لا يجوز استنتاج أن `Mortality Event` أو `Missing Event` يحتاجان أيضًا إلى Farm Exit Reason لمجرد أن Master Data تحتوي قيمًا مثل `mortality` و`lost`؛ القرار 4.1 اختار عدم إنشاء Farm Exit Event مكرر للحالات Canonical.

---

### 4.4 `farm_exit.active_context_handling_model`

القرار:

```text
resolve_or_reconstruct_active_context_before_exit
```

المعنى أن خروج الحيوان لا يعتمد بصورة عمياء إذا كان لديه مسار نشط قد يصبح غير صالح، مثل:

```text
pregnancy
lactation-related context
fattening
health isolation
production path
open workflow tasks
```

النموذج المقصود:

```text
Exit requested
→ detect active contexts
→ resolve / reconstruct affected contexts
→ only then complete actual Exit
```

لكن هذا القرار يحمل تعارضًا مباشرًا مع 4.14 حول **من يحدد السياقات المتأثرة**، موضح لاحقًا.

هذا السؤال لا يحدد قواعد السماح والمنع نفسها؛ مثل منع بيع أنثى حامل أو السماح بتجاوز معين. هذه Policies تنتمي إلى Settings عند اعتمادها.

---

### 4.5 `farm_exit.post_event_transition_model`

القرار:

```text
create_pending_post_exit_actions
```

بعد تسجيل Farm Exit Event لا تغلق كل الآثار المرتبطة تلقائيًا في نفس اللحظة، بل ينشئ النظام Actions / Tasks معلقة لمعالجة:

```text
Housing Occupancy
Active Workflow Paths
Open Tasks
Other dependent operational state
```

إذن النموذج الحالي:

```text
Actual Farm Exit Event
→ animal considered outside
→ pending post-exit actions
→ occupancy / workflows / tasks closed or adjusted afterward
```

هذا يختلف عن نموذج `automatic_linked_exit_transitions` الذي لم يتم اختياره.

لكن هذا القرار يخلق **مشكلة Integrity محتملة** مع 4.2 لأن Current Location / Occupancy مشتقان من Active Occupancy. إذا ظل الإشغال مفتوحًا بعد `exited_at` حتى تنفيذ Action لاحقة، فقد يظهر مؤقتًا:

```text
Animal = outside farm
+
Active Occupancy = still in Cage
```

كما أن المرجع الوظيفي يقول إن إشغال القفص يغلق عند خروج الحيوان.

لذلك يجب حسم هذه النقطة قبل Requirements النهائية، أو تعريف حالة انتقالية صريحة تمنع اعتبار Occupancy القديمة Current بعد الخروج.

---

### 4.6 `farm_exit.batch_operation_model`

القرار:

```text
batch_operation_with_individual_exit_events
```

يدعم النظام عملية خروج جماعية، مثل بيع مجموعة من حيوانات التسمين، مع إدخال البيانات المشتركة مرة واحدة.

لكن كل حيوان يحتفظ بـ:

```text
independent Farm Exit Event
```

مرتبط بالـBatch المشتركة.

إذن:

```text
Batch Exit
= operational convenience container

Animal Exit Event
= canonical individual history
```

ولا يسمح النموذج الحالي بسجل جماعي واحد يمحو تاريخ كل حيوان.

هذا متسق مع قاعدة المشروع العامة أن Animal Timeline يجب أن يظل فرديًا وقابلًا للتدقيق.

---

### 4.7 `sale_exit.commercial_data_scope`

القرار:

```text
operational_sale_only
```

في النسخة الحالية، بيع الحيوان يسجل كحدث تشغيلي يثبت:

```text
sale / exit happened
```

لكن لا يوجد Requirement حالي لإدارة:

```text
buyer
price
invoice
payment
accounting
commercial transaction lifecycle
```

حتى لو ذكر المرجع أن المشتري أو السعر يمكن تسجيلهما إذا دخل الجانب المالي في نطاق النظام، فإن الإجابة الحالية اختارت بوضوح **عدم إدخال هذه البيانات في هذه النسخة**.

إذن لا يجوز إنشاء Sales / Accounting Module من هذا القسم.

---

### 4.8 `interfarm_transfer.boundary_model`

القرار:

```text
linked_exit_and_reentry_same_record
```

إذا انتقل الحيوان من مزرعة إلى أخرى داخل نفس النظام، فالعملية ليست مجرد Housing Movement في 4.2.

النموذج الحالي:

```text
Same Animal Record
→ Farm Exit from source farm
→ linked Re-entry into destination farm
→ same identity / pedigree / weight / production history
```

إذن:

```text
Inter-farm transfer
≠ new Animal
```

وكذلك:

```text
Inter-farm transfer
≠ simple Cage-to-Cage movement only
```

هذا القرار يحافظ على نفس الكود والهوية التاريخية مع إنشاء Boundary واضح لفترة الوجود بكل مزرعة.

لكن بسبب قرار Re-entry Presence الحالي، قد توجد فترة يكون الحيوان قد وصل فعليًا إلى المزرعة المستقبلة لكنه لا يعتبر `present` حتى انتهاء Intake، وهي نقطة مرتبطة بالتعارض الموضح لاحقًا.

---

### 4.9 `animal_reentry.previous_exit_link_model`

القرار:

```text
explicit_previous_exit_reference
```

عند عودة حيوان ثبت أنه نفس الحيوان:

```text
Re-entry Event
→ explicit reference to the Exit Event it returns from
```

ولا يعتمد النظام فقط على:

```text
latest exit by chronology
```

أو على وجود الحدثين في نفس Timeline دون علاقة مباشرة.

هذا مهم خصوصًا إذا كان الحيوان قد خرج وعاد أكثر من مرة.

---

### 4.10 `animal_reentry.presence_transition_model`

القرار:

```text
present_after_intake_finalization
```

وفق الإجابة الحالية، الحيوان لا يعود إلى حالة:

```text
Present in Farm
```

عند الوصول الفعلي، بل فقط بعد إكمال واعتماد Intake في 4.1.

المعنى الحرفي:

```text
Physical Re-entry Arrival
→ Full Intake Cycle
→ Explicit Intake Final Approval
→ Present in Farm
```

هذا القرار **يتعارض مباشرة** مع الحد الوظيفي المعتمد في 4.1 والمرجع الوظيفي، لأنهما يفصلان بين:

```text
Physical Presence
≠ Operational / Production Readiness
```

4.1 يسمح بأن يكون الحيوان موجودًا فعليًا داخل المزرعة لكنه ما زال تحت الاستقبال أو الحجر وغير متاح للتشغيل.

إذن هذه النقطة تحتاج حسمًا صريحًا قبل Blueprint النهائي.

---

### 4.11 `animal_reentry.episode_history_model`

القرار:

```text
append_only_presence_episodes
```

كل فترة وجود داخل المزرعة تحفظ Episode مستقلة تاريخيًا.

النموذج:

```text
Presence Episode 1
→ Exit 1

Presence Episode 2
→ Re-entry 1
→ Exit 2

Presence Episode 3
→ Re-entry 2
→ ...
```

ولا يعاد فتح Presence Record قديم بطريقة تمحو حدود الفترات السابقة.

هذا متسق مع قاعدة:

```text
same Animal identity
+
append-only operational history
```

---

## 5. العلاقة مع 4.1 — الاستقبال وإعادة الإدخال

4.1 قرر:

```text
full_intake_cycle_same_animal
```

إذن عند عودة نفس الحيوان:

```text
Existing Animal Record
→ Re-entry
→ Full Intake / Evaluation Cycle again
```

ولا يتم إنشاء Animal جديد.

هذا متوافق مع 4.15 في:

- الحفاظ على نفس الهوية.
- الربط بواقعة الخروج السابقة.
- دعم عدة فترات حضور خلال حياة الحيوان.

لكن يوجد تعارض مباشر في **Presence Boundary**:

```text
4.1 / Functional Source
Physical arrival can exist before final approval

4.15 current answer
Presence starts only after final approval
```

وهذا التعارض لا يجوز حلّه ضمن الـGuide من دون تعديل قرار صريح.

---

## 6. العلاقة مع 4.2 — الإشغال والموقع

4.2 يجعل:

```text
Current Location / Occupancy
= derived from Housing Movement History
```

وعند الخروج الفعلي، المرجع الوظيفي ينص على إغلاق إشغال القفص.

لكن 4.15 اختار:

```text
Exit Event
→ Pending post-exit action
→ later close occupancy
```

وهذا يخلق سؤالًا تنفيذيًا مهمًا:

```text
Between exited_at and occupancy closure action:
Is the old occupancy still considered Active?
```

إذا كانت الإجابة نعم، يظهر تعارض في Current State.

إذا كانت الإجابة لا، فيجب تعريف آلية تجعل Exit Event نفسه يمنع احتساب Occupancy القديمة كحالية حتى قبل تسجيل `explicit_vacate` أو إغلاقها.

القرار الحالي لا يحسم هذه الآلية.

---

## 7. العلاقة مع 4.14 — الحالات الاستثنائية

هناك تعارض مباشر بين:

```text
4.15 active_context_handling_model
= system detects active contexts before exit
```

وبين:

```text
4.14 workflow_reconstruction.context_detection_model
= manual_affected_context_selection
```

أي أن 4.15 يتوقع Detection تلقائيًا، بينما 4.14 يجعل المستخدم هو من يحدد المسارات والعلاقات المتأثرة.

كذلك يجب قراءة `farm_exit.canonical_event_routing_model` مع قرار Missing في 4.14:

```text
Missing Event
≠ Farm Exit Event
```

الحيوان المفقود لا يعتبر مباعًا أو نافقًا أو خارج المزرعة تلقائيًا؛ موقعه يصبح غير موثوق حتى Found Event.

لذلك لا يجوز تفسير عبارة "derive presence/exit" في سؤال 4.15 على أنها تحول Missing إلى Exit فعلي.

---

## 8. العلاقة مع Master Data — ExitReason

قائمة `ExitReason` الحالية تشمل قيمًا مثل:

```text
sale
mortality
final_exclusion
transfer_to_another_farm
internal_slaughter_consumption
lost
other
```

لكن وجود هذه القيم لا يعني دمج الأحداث.

الصحيح:

```text
ExitReason
= reference classification

Mortality Event
= 4.13

Missing Event
= 4.14

Fate / Exclusion Decision
= 4.10

Farm Exit Event
= 4.15 when an actual general exit event is required
```

القرار 4.15.1 يمنع إنشاء Farm Exit Event إضافي للحالات Canonical المتخصصة.

---

## 9. فحص الاتساق والتعارضات المفتوحة

### تعارض 1 — Presence عند إعادة الدخول

```text
4.15
present_after_intake_finalization
```

مقابل:

```text
4.1 + functional reference
animal can be physically inside farm
while still under intake / quarantine / observation
```

هذا تعارض مباشر يحتاج حسمًا.

---

### تعارض 2 — اكتشاف السياقات المتأثرة قبل الخروج

```text
4.15
system detects active contexts
```

مقابل:

```text
4.14
manual_affected_context_selection
```

هذا أيضًا تعارض مباشر يحتاج حسمًا.

---

### نقطة تكامل 3 — خروج فعلي مع Occupancy ما زالت Pending

القرار الحالي:

```text
Actual Exit
→ pending action to close occupancy
```

بينما 4.2 يجعل Current Location مشتقًا من Active Occupancy، والمرجع الوظيفي يتوقع إغلاق إشغال القفص عند الخروج.

يلزم تحديد كيفية منع ظهور الحيوان خارج المزرعة وفي قفص نشط في الوقت نفسه.

---

### نقطة تكامل 4 — Missing ليس Exit

رغم وجود `lost` ضمن ExitReason ورغم صياغة سؤال Canonical Routing، فإن 4.14 حسم:

```text
Missing Event
→ same Animal Record
→ not automatically an Exit
```

إذن Presence في حالة Missing يجب ألا تساوي تلقائيًا `outside farm` دون قرار جديد.

---

## 10. ما لا يحسمه القسم

القسم لا يحسم:

- قواعد السماح بخروج حيوان حامل أو مرضع أو معزول.
- من يملك صلاحية Override لهذه القواعد.
- هل يلزم وزن جديد عند كل خروج أم يكفي آخر وزن موثوق.
- شكل Transaction التجاري أو المحاسبي؛ وقد استبعد حاليًا أصلًا من Scope البيع.
- شروط إثبات أن الحيوان العائد هو نفس الحيوان السابق.
- قواعد Intake بعد العودة؛ مكانها 4.1 / Settings.
- كيف تتعامل المزرعة المستقبلة مع Inter-farm Transfer قبل اكتمال Intake.
- آلية الـPending Actions بعد الخروج وصلاحيات إكمالها.
- حالة Occupancy الدقيقة أثناء الفترة بين Exit Event وإغلاق الإشغال.

ولا يجوز اختراع هذه التفاصيل من المعرفة العامة.

---

## 11. المخرجات المطلوبة للـRequirements

يجب أن تغطي المتطلبات النهائية على الأقل:

1. `FarmExitEvent` مستقل للأحداث العامة التي تمثل خروجًا فعليًا.
2. عدم إنشاء Farm Exit Event مكرر للـCanonical Events المتخصصة.
3. دعم الحقول الأحد عشر المعتمدة لواقعة الخروج.
4. إلزام `ExitReason` لكل Farm Exit Event، مع وصف إضافي لـ`other`.
5. منع إكمال الخروج قبل تسوية السياقات النشطة وفق النموذج النهائي بعد حسم التعارض مع 4.14.
6. دعم Post-exit actions حسب القرار الحالي مع حسم Integrity الإشغال.
7. دعم Batch Exit مع Event مستقل لكل حيوان.
8. حصر البيع حاليًا في إثبات تشغيلي دون مشتري أو سعر أو محاسبة.
9. تمثيل النقل بين مزرعتين كـLinked Exit + Re-entry على نفس Animal Record.
10. ربط Re-entry صراحة بواقعة الخروج التي يعود منها الحيوان.
11. عدم إنشاء Animal جديد عند العودة.
12. دعم Append-only Presence Episodes متعددة خلال حياة الحيوان.
13. عدم اعتبار Missing Farm Exit تلقائيًا.
14. حسم تعارض Physical Presence vs Intake Finalization قبل Blueprint النهائي.
15. الحفاظ على النسب، الأبناء، الأوزان، الإنتاج، والحالة التاريخية بعد خروج الحيوان وعدم حذف سجله.

---

## 12. حالة المراجعة النهائية للقسم

```text
Answers completeness: 11 / 11
Internal completeness: complete
Blocking cross-section conflicts: 2
Important integration gaps: 2
Guide status: ready for review, not yet conflict-free
```

لا يوجد ما يمنع الانتقال للقسم التالي من ناحية اكتمال الإجابات، لكن التعارضات المسجلة أعلاه يجب أن تدخل مرحلة مراجعة الاتساق قبل توليد الـBlueprint النهائي.