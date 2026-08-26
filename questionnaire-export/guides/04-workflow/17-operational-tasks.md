# 4.17 تنفيذ وإدارة المهام التشغيلية — دليل تفسير الإجابات

> **الحالة:** مسودة أولى مبنية على الإجابات الحالية للمراجعة — القسم مكتمل 12/12 ومتماسك داخليًا، مع نقطة ضبط مهمة للفصل بين `current_status` وSchedule State المشتقة، وقيد عابر يجب مراعاته لاحقًا في Settings 6.12 بخصوص عدم وجود اعتماد بعد التنفيذ  
> **ملف الإجابات المقابل:** `questionnaire-export/answers/04-workflow/17-operational-tasks.md`  
> **Question Keys المغطاة:** 12 / 12

---

## 1. الغرض من هذا الملف

هذا الملف يفسر **المعنى الوظيفي والتقني للإجابات المعتمدة فعليًا** في قسم `تنفيذ وإدارة المهام التشغيلية` ضمن `الحركات ودورة التشغيل الفعلية`.

هذا القسم لا يقرر من نفسه متى تنشأ المهمة، ولا يحسب موعدها أو أولويتها أو قواعد التكرار والتنبيه، ولا يعيد تعريف أنواع المهام أو الأحداث التشغيلية الأصلية.

```text
answers/04-workflow/17-operational-tasks.md
= ماذا قررنا؟

                    ↓ تفسير

guides/04-workflow/17-operational-tasks.md
= ماذا تعني القرارات تقنيًا؟ وما حدودها ونقاط التكامل؟
```

الحد المعماري الأساسي:

```text
OperationalTaskType
→ Master Data 1.19

Task Generation / Due Date / Priority / Recurrence / Alerts / Assignment Rules
→ Settings 6.12

Actual Task Lifecycle / Execution / Postponement / Cancellation / Completion
→ Workflow 4.17

Canonical Operational Event produced by execution
→ its original Workflow section

Today's Tasks / Overdue / workload / KPIs
→ Dashboard / Reports
```

ويجب الحفاظ على الفصل:

```text
Task Type
≠ Task Generation Rule
≠ Actual Task Record
≠ Canonical Domain Event
≠ Alert
```

---

## 2. الهدف الوظيفي من القسم

الغرض من 4.17 هو إدارة **المهمة الفعلية بعد أن أصبحت موجودة** داخل التشغيل اليومي، بحيث نعرف:

- ما المطلوب تنفيذه.
- لمن / لأي سياق تتبع المهمة.
- موعدها الأصلي والحالي.
- من المسؤول عنها.
- هل بدأ تنفيذها.
- هل اكتملت أو ألغيت أو أجلت.
- ما العملية التشغيلية التي نتجت من تنفيذها.
- من نفذها ومتى.
- ما تاريخ كل تغيير مر عليها.

النموذج الوظيفي المتوافق مع المرجع:

```text
Workflow Event / Condition / Schedule
        ↓
Settings Rule
        ↓
Due Date + Priority + Assignment
        ↓
Operational Task
        ↓
Execute Task
        ↓
Canonical Workflow Action / Direct Task Completion
        ↓
Result
        ↓
Task Completion
```

والمرجع يلخص الدورة الأوسع بالشكل:

```text
حدث
→ إعدادات
→ موعد
→ مهمة
→ تنفيذ
→ نتيجة
→ حدث جديد
```

إذن المهمة **تنظم وتوجه التنفيذ**، لكنها لا تصبح بديلًا عن الحدث التشغيلي الحقيقي.

---

## 3. ملخص القرارات المعتمدة

الإجابات الحالية تحسم الصورة التالية:

```text
Operational Task
├── Fields
│   ├── task_type
│   ├── farm
│   ├── original_due_at
│   ├── current_due_at
│   ├── subject_reference
│   ├── source_event_reference
│   ├── priority
│   ├── current_status
│   ├── assigned_to
│   ├── performed_by
│   ├── started_at
│   ├── completed_or_closed_at
│   ├── result_event_reference
│   └── execution_result_or_notes
│
├── Context
│   └── one primary Subject + optional source event
│
├── Lifecycle
│   └── append historical lifecycle events + current state
│
├── Schedule State
│   └── Upcoming / Due / Overdue derived from due date + execution state
│
├── In Progress
│   └── explicit Start Execution transition
│
├── Execution Routing
│   └── open canonical Workflow action with task context
│
├── Canonical Completion
│   └── canonical event auto-completes and links the task
│
├── Non-domain Completion
│   └── direct task completion + optional result / notes
│
├── Postponement
│   └── append-only postponement events
│
├── Cancellation
│   └── cancellation event with reason + actor + time + source
│
├── Assignment
│   └── one assigned user at a time + reassignment history
│
└── Post-execution Approval
    └── none
```

---

## 4. تفسير Question Keys والإجابات

### 4.1 `operational_task.record_fields`

تم اعتماد جميع الحقول المعروضة:

```text
task_type
farm
original_due_at
current_due_at
subject_reference
source_event_reference
priority
current_status
assigned_to
performed_by
started_at
completed_or_closed_at
result_event_reference
execution_result_or_notes
```

هذه القائمة تعني أن سجل المهمة يستطيع حفظ **سياقها وتنفيذها وتاريخها الحالي**، لكنها لا تعني أن كل قيمة يدخلها المستخدم يدويًا.

يجب التفريق مثلًا بين:

```text
original_due_at
= الموعد الذي أنشئت المهمة عليه أول مرة

current_due_at
= الموعد الساري بعد آخر Postponement صحيح
```

وكذلك:

```text
assigned_to
= المسؤول الحالي عن المهمة

performed_by
= من نفذ العمل فعليًا
```

وقد يكونان نفس الشخص أو شخصين مختلفين إذا حدث Reassignment أو نفذ مستخدم مخول مهمة مسندة لغيره وفق الصلاحيات التي ستعتمد لاحقًا.

`source_event_reference` يسمح بتتبع سبب نشوء المهمة عند وجود Event أو عملية أصلية، بينما `result_event_reference` يربط المهمة بالواقعة التي نتجت من تنفيذها.

إذن يمكن أن نرى مسارًا مثل:

```text
Mating Event
→ source of Pregnancy Check Task
→ Pregnancy Check Event
→ result of Task Execution
```

ولا يجوز نسخ بيانات الحدثين داخل المهمة بدل العلاقات المرجعية.

---

### 4.2 `operational_task.context_link_model`

القرار:

```text
primary_subject_with_optional_source_event
```

أي أن لكل مهمة **Subject رئيسيًا واحدًا** يمثل المورد أو السياق الأساسي الذي تخصه المهمة، مع مرجع اختياري إلى الحدث / العملية التي أدت إلى توليدها.

الـSubject قد يكون وفق نوع المهمة والسياق:

```text
Animal
Litter
Housing Site
Operational Context / supported resource
```

لكن هذه الإجابة لا تحسم آلية Polymorphic التقنية أو أسماء العلاقات في قاعدة البيانات.

المبدأ هو:

```text
Task stores references
≠ Task duplicates source entity data
```

كما لم يتم اعتماد نموذج عدة Context References مباشرة لكل مهمة.

إذا كانت عملية معينة تحتاج سياقات إضافية، يفترض الوصول إليها من Subject أو Source Event أو من العلاقات Canonical الخاصة بالعملية، ما لم يعتمد Requirement مستقل لاحقًا.

---

### 4.3 `operational_task.lifecycle_model`

القرار:

```text
lifecycle_events_with_current_state
```

كل انتقال مهم في Lifecycle يجب أن يسجل تاريخيًا.

مثلًا مفاهيميًا:

```text
Created / Open
→ Assigned / Reassigned when applicable
→ Started
→ Postponed
→ Started again
→ Completed
```

أو:

```text
Created
→ Cancelled
```

ولا يتم الاحتفاظ بآخر Status فقط بطريقة تمحو ما حدث سابقًا.

يمكن الاحتفاظ بـ`current_status` لسهولة العرض أو الأداء، لكن يجب أن يظل ناتجًا متسقًا مع Lifecycle History، وليس قيمة يدوية مستقلة يمكن أن تناقضها.

---

### 4.4 `operational_task.schedule_state_model`

القرار:

```text
derive_schedule_state_from_due_at_and_execution_state
```

الحالات الزمنية مثل:

```text
قادمة
مستحقة
متأخرة
```

لا تسجل كTransitions يغيرها المستخدم يدويًا.

بل تشتق من:

```text
current_due_at
+
current execution lifecycle
+
current time
```

مثلًا:

```text
Task still open
AND current_due_at > now
→ Upcoming

Task still open
AND due window includes now
→ Due

Task still open
AND current_due_at passed
→ Overdue
```

شكل الـDue Window والدقة الزمنية نفسها لا يحسمها 4.17؛ مكانها Settings 6.12.

#### نقطة ضبط مهمة: `current_status` مقابل Schedule State

بما أن `current_status` موجود ضمن حقول المهمة، فلا يجوز تفسيره كحقل يدوي يكرر:

```text
Upcoming / Due / Overdue
```

لأن هذه الثلاثة معتمدة كقيم **Derived**.

التفسير الآمن حاليًا:

```text
Execution Lifecycle State
≠ Schedule State
```

أي يمكن أن تكون المهمة مثلًا:

```text
Execution State = Open
Schedule State = Overdue
```

أو:

```text
Execution State = In Progress
Schedule State = Overdue
```

لكن الـEnum النهائي لحالات التنفيذ لم يحسم كسؤال مستقل، لذلك لا يجوز اختراع قائمة Status نهائية من هذا الملف وحده.

---

### 4.5 `operational_task.in_progress_model`

القرار:

```text
explicit_in_progress_transition
```

بدء العمل على المهمة نفسه Action تاريخي مستقل.

المعنى:

```text
Start Task Execution
→ record started_at
→ record actor
→ task becomes In Progress
```

ثم لاحقًا تنتهي المهمة بإكمال أو إلغاء أو انتقال آخر مناسب.

هذا يفيد في معرفة الفرق بين:

```text
Task not started
≠ Task currently being worked on
≠ Task completed
```

لكن هناك Gap صغيرة غير مانعة: لم يحسم السؤال هل الضغط على زر «تنفيذ المهمة» الذي يفتح الـCanonical Workflow Action **ينشئ تلقائيًا Start/In-Progress Event**، أم يحتاج Action بدء منفصلًا قبل فتح النموذج.

المطلوب النهائي يجب أن يمنع وجود مسارين متنافسين لبدء التنفيذ.

---

### 4.6 `operational_task.execution_routing_model`

القرار:

```text
route_to_canonical_workflow_action_with_context
```

عندما يضغط المستخدم «تنفيذ المهمة»، لا يتم تنفيذ الوزن أو الجس أو النقل داخل Generic Task Form.

بل:

```text
Task
→ Open Canonical Workflow Action / Form
→ pass subject + relevant context
```

مثلًا:

```text
Pregnancy Check Task
→ Pregnancy Check Form in 4.5
```

أو:

```text
Weight Task
→ Operational Measurement in 4.3
```

أو:

```text
Animal Transfer Task
→ Housing Movement in 4.2
```

هذا يحافظ على **Single Source of Truth** لكل حدث تشغيلي.

---

### 4.7 `operational_task.domain_completion_model`

القرار:

```text
canonical_event_auto_completes_and_links_task
```

إذا كانت المهمة تطلب عملية لها Event Canonical، فإن تسجيل العملية بنجاح هو الذي يكمل المهمة.

```text
Task
→ Execute Canonical Action
→ Canonical Event created successfully
→ Task auto-completed
→ result_event_reference linked
→ performed_by / completion time recorded consistently
```

وهذا يطبق المبدأ الوظيفي المعتمد:

```text
المهمة تقود إلى العملية
والعملية هي التي تكمل المهمة
```

ولا يجب أن يطلب من المستخدم بعد حفظ الحدث أن يعود إلى المهمة ويضغط «مكتملة» يدويًا لنفس الواقعة.

#### حماية التكامل

يجب أن يحدث Auto Completion فقط بعد نجاح إنشاء / اعتماد الحدث Canonical المطلوب، وليس بمجرد فتح النموذج أو بدء الإدخال.

كما يجب منع إنشاء حدثين Canonical لنفس تنفيذ المهمة بسبب إعادة الضغط أو إعادة المحاولة دون معالجة صحيحة للـIdempotency؛ تفاصيل الآلية التقنية نفسها تحسم في التصميم النهائي.

---

### 4.8 `operational_task.manual_completion_model`

القرار:

```text
direct_task_completion_with_optional_result
```

بعض المهام لا تنتج Domain Event مستقلًا، مثل مراجعة تشغيلية بسيطة أو متابعة لا يوجد لها Event متخصص.

في هذه الحالة يجوز:

```text
Task
→ Direct Completion Action
→ performed_by + completion time
→ optional result / notes
```

ولا يجب اختراع Event تشغيلي جديد لمجرد إغلاق المهمة.

لكن استخدام هذا المسار يجب أن يكون فقط للمهام التي **لا يوجد لها Canonical Event فعلي مطلوب**.

إذا كان النوع في الحقيقة يمثل وزنًا أو نقلًا أو فحص حمل مثلًا، فيجب استخدام القسم Canonical بدل Direct Completion.

---

### 4.9 `operational_task.postponement_model`

القرار:

```text
append_only_postponement_events
```

كل تأجيل يحفظ كواقعة مستقلة تتضمن مفاهيميًا:

```text
previous_due_at
new_due_at
reason
performed_by
postponed_at
```

ويمكن أن يتكرر التأجيل أكثر من مرة.

إذن:

```text
original_due_at
= never overwritten

current_due_at
= result of latest valid postponement
```

ويصبح Timeline قابلًا لإظهار:

```text
Original Due
→ Postponed to A
→ Postponed to B
→ Completed
```

بدل أن يظهر للمستخدم فقط الموعد الأخير وكأن المواعيد السابقة لم توجد.

قواعد السماح بالتأجيل، الحد الأقصى، المدة، أو أنواع المهام التي لا يجوز تأجيلها ليست من 4.17؛ مكانها Settings 6.12 إذا تم اعتمادها.

---

### 4.10 `operational_task.cancellation_model`

القرار:

```text
cancellation_event_with_reason_actor_time_and_source
```

الإلغاء لا يعني حذف المهمة.

عند إلغاء مهمة يجب حفظ:

```text
reason
actor
cancelled_at
source event / decision when applicable
```

هذا مهم للحالات التي يصبح فيها موعد مستقبلي غير صالح بعد تغير المسار.

مثلًا:

```text
Pregnancy Exception in 4.14
→ old Expected Birth Task no longer valid
→ user reviews affected task under current 4.14 decision
→ Cancellation Event executed in 4.17
```

إذن 4.14 يحدد أن المهمة أصبحت متأثرة، و4.17 يحتفظ بـLifecycle الإلغاء نفسه.

هذا متوافق مع قرار 4.14 الحالي `manual_task_review_after_exception`؛ فلا يوجد تعارض بينهما، لكن الإلغاء لا يحدث تلقائيًا في هذا السيناريو وفق القرارات الحالية.

---

### 4.11 `operational_task.assignment_model`

القرار:

```text
single_user_assignment_with_history
```

كل مهمة يمكن أن يكون لها **مستخدم واحد مسؤول حاليًا**.

ويمكن إعادة إسنادها مع حفظ التاريخ.

```text
Assigned to User A
→ Reassigned to User B
→ performed by User B
```

أو حسب الصلاحيات:

```text
Assigned to User A
→ performed by another authorized User
```

لكن القرار لا يدعم حاليًا إسناد المهمة مباشرة إلى:

```text
Role
Team
Multiple simultaneous assignees
```

قواعد الإسناد التلقائي التي ستنشئ `assigned_to` يمكن أن تأتي من Settings 6.12، لكنها يجب أن تنتج قيمة متوافقة مع نموذج **مستخدم واحد مسؤول** المعتمد هنا.

---

### 4.12 `operational_task.approval_model`

القرار:

```text
no_post_execution_approval
```

لا توجد مرحلة:

```text
Executed
→ Waiting Approval
→ Approved / Closed
```

في Lifecycle الحالي.

بل:

```text
Valid Canonical Execution
→ Task completed
```

أو للمهمة غير المرتبطة بحدث:

```text
Direct valid completion
→ Task completed
```

هذا لا يلغي الصلاحيات اللازمة لتنفيذ العملية نفسها أو حفظ Audit Trail.

لكنه يعني أن **المهمة بعد التنفيذ لا تحتاج اعتماد مستخدم آخر حتى تغلق**.

---

## 5. العلاقة مع OperationalTaskType في Master Data 1.19

Master Data 1.19 يعتمد `OperationalTaskType` كقاموس مرجعي مستقل.

هناك 35 نوعًا مبدئيًا، منها مثلًا:

```text
periodic_weight
pregnancy_check
pregnancy_recheck
nest_box_preparation
weaning_weight
growth_periodic_weight
sale_readiness_review
animal_transfer
cage_cleaning_sanitation
animal_under_observation_followup
isolation_review
```

لكن:

```text
Task Type exists
≠ Task is automatically generated
```

فوجود `pregnancy_check` في Master Data لا يعني أن كل تلقيح يولد مهمة جس دون قاعدة Settings فعالة.

والتقسيم الصحيح هو:

```text
OperationalTaskType
= ما اسم / نوع المهمة؟

Settings 6.12
= متى تتولد؟ وما موعدها وأولويتها وإسنادها؟

4.17
= ماذا حدث للمهمة الفعلية بعد إنشائها؟
```

كما أن 1.19 ما زال لا يحسم Fixed vs Managed لقائمة أنواع المهام، ولا Mapping تفصيلي للـCategories؛ لا يعالج 4.17 هذه الفجوة.

---

## 6. العلاقة مع Settings 6.12

قسم `6.12 قواعد المهام والتنبيهات والمواعيد والأولويات` لم تتم الإجابة عليه حتى الآن.

هو يحتوي القواعد التي ستحدد أمورًا مثل:

```text
Trigger Source
Due Calculation
Due Model
Priority Rule
Assignment Rule
Recurrence
Postponement Rules
Alerts / reminders
```

ولذلك لا يجوز لـ4.17 أن يفترض قيمًا أو مددًا أو قواعد إنشاء لم يتم اعتمادها.

### قيد مهم يجب حمله إلى 6.12

4.17 حسم صراحة:

```text
no_post_execution_approval
```

بينما 6.12 يحتوي ضمن تصميم Rule حقولًا / أسئلة تسمح من حيث المبدأ بمناقشة `approval_rule`.

إذن عند الإجابة على 6.12 يجب الحفاظ على القرار الأعلى الحالي:

```text
Task generation/settings
must not introduce post-execution approval
while 4.17 = no_post_execution_approval
```

إذا أراد المستخدم لاحقًا دعم Approval لبعض المهام، فذلك يتطلب **إعادة فتح قرار 4.17 نفسه**، لا تفعيله بصمت من Settings.

### قيد الإسناد

4.17 حسم:

```text
single_user_assignment_with_history
```

لذلك أي Assignment Rule في 6.12 يجب أن ينتهي إلى **مستخدم واحد مسؤول حاليًا**، ما لم يعاد فتح هذا القرار لاحقًا.

---

## 7. العلاقة مع الحالات الاستثنائية 4.14

4.14 اختار حاليًا:

```text
manual_task_review_after_exception
```

أي أن الاستثناء لا يغلق المهام المتأثرة تلقائيًا؛ المستخدم يراجعها.

4.17 يوفر بعد ذلك آلية الإلغاء التاريخية الصحيحة:

```text
Affected Task detected / selected in 4.14
→ user decides task is no longer valid
→ Cancellation Event in 4.17
→ preserve reason + actor + time + source
```

إذن القسمان متوافقان في النموذج الحالي.

لكن لو تم لاحقًا تغيير 4.14 إلى Auto-close أو Linked Task Lifecycle Actions، فيجب أن تظل النتيجة النهائية تستخدم Lifecycle 4.17 ولا تحذف المهمة أو تعدلها بصمت.

---

## 8. العلاقة مع الخروج 4.15

4.15 اختار بعد الخروج:

```text
create_pending_post_exit_actions
```

بما في ذلك معالجة المهام التي أصبحت غير صالحة.

التفسير مع 4.17:

```text
Actual Exit
→ identify / create required follow-up actions
→ affected Operational Tasks handled through 4.17 lifecycle
```

ولا يجب أن يصبح الخروج نفسه طريقة مباشرة لتغيير سجل المهمة خارج Lifecycle المعتمد.

لكن 4.15 لم يحسم نوع كل Pending Action وهل كلها `OperationalTask` فعلية أم بعضها Workflow Transition مختلف؛ لذلك لا يجوز تحويل كل Post-exit Action تلقائيًا إلى Task دون Requirement صريح.

---

## 9. الفرق بين المهمة والتنبيه

المرجع الوظيفي يفرق بين:

```text
Task
= شيء مطلوب تنفيذه وله Lifecycle

Alert
= معلومة تستدعي الانتباه ولا تعني بالضرورة وجود عملية مجدولة
```

مثلًا:

```text
Pregnancy Check due
→ Task

High mortality rate detected
→ Alert / Review signal
```

4.17 يتعامل مع **المهام** فقط.

قواعد تحويل بعض التنبيهات إلى Tasks أو إبقائها Alerts فقط تنتمي إلى 6.12 / Reports حسب القرار النهائي.

لا يجوز إنشاء OperationalTask لكل Alert تلقائيًا من هذا القسم.

---

## 10. نقاط تحتاج حسمًا أو ضبطًا لاحقًا

### 10.1 `current_status` مقابل Schedule State

هذه أهم نقطة تفسيرية داخل القسم.

لدينا:

```text
current_status
```

كحقل في المهمة، وفي نفس الوقت:

```text
Upcoming / Due / Overdue
```

معتمدة كحالات مشتقة من الموعد والتنفيذ.

لذلك يجب في الـRequirements النهائية تحديد أن:

```text
Execution Lifecycle State
≠ Schedule State
```

وعدم إنشاء Status يدوي واحد يجمع الاثنين بطريقة تؤدي لتناقضات.

لا يحسم هذا الـGuide أسماء Enum التنفيذ النهائية.

### 10.2 متى يبدأ `In Progress` عند فتح الـCanonical Action؟

تم اعتماد Start Execution كحدث مستقل، وتم اعتماد أن زر التنفيذ يفتح العملية Canonical.

لكن لم يحسم صراحة هل:

```text
Click Execute
→ automatically Start Task
→ open Canonical Form
```

أم:

```text
Start Task Action
→ then open Canonical Form
```

يجب لاحقًا اختيار مسار UX واحد واضح حتى لا توجد مهمات مفتوحة رغم بدء العمل عليها أو Start Events مكررة.

### 10.3 قاموس Execution Status النهائي

المرجع يذكر حالات مثل مكتملة / مؤجلة / ملغاة / قيد التنفيذ، لكن الأسئلة الحالية حسمت **سلوك الانتقالات** ولم تعتمد Question Key يجمع Enum نهائيًا لحالة التنفيذ.

لذلك لا يجوز اختراع Enum نهائي الآن.

### 10.4 Idempotency عند الإكمال من Canonical Event

بما أن العملية Canonical تكمل المهمة تلقائيًا، فيجب في التصميم النهائي منع تكرار الإكمال أو إنشاء أكثر من Result Event لنفس تنفيذ المهمة نتيجة إعادة إرسال Form أو إعادة الضغط.

هذا Requirement تكاملي لازم، لكن آلية التنفيذ التقنية ليست محسومة هنا.

---

## 11. فحص الاتساق

### متوافق داخليًا

النموذج العام متماسك:

```text
Task History preserved
+ Schedule state derived
+ explicit execution start
+ canonical action routing
+ canonical result auto-completes task
+ append-only postponement
+ historical cancellation
+ assignment history
+ no post-execution approval
```

ولا يوجد تعارض داخلي مانع داخل 4.17.

### متوافق مع المرجع الوظيفي

الإجابات تطابق بوضوح مبادئ المرجع:

- المهمة لا تختفي عندما تتأخر.
- التأجيل يحتفظ بالموعد الأصلي والسبب.
- المهمة تقود مباشرة للعملية المطلوبة.
- العملية الفعلية تكمل المهمة.
- وجود `In Progress` أصبح قرارًا معتمدًا بدل بقائه نقطة مفتوحة.
- المسؤولية تحفظ ويمكن تتبع المنفذ الفعلي.

### نقاط يجب حملها للأقسام اللاحقة

```text
4.17 no post-execution approval
→ constrains Settings 6.12

4.17 single user assignment
→ constrains Settings assignment rules

Schedule State is derived
→ Reports/Dashboard must calculate/filter from due + execution state

Canonical event completes task
→ every routed task type needs a clear canonical execution mapping when applicable
```

---

## 12. المخرجات المطلوبة للـRequirements

1. وجود `OperationalTask` فعلي منفصل عن `OperationalTaskType` وعن Rule التي ولدته.
2. دعم الحقول الأربعة عشر المعتمدة مع التمييز بين القيم المدخلة والمشتقة والمرجعية.
3. ربط المهمة بـPrimary Subject واحد وSource Event اختياري.
4. حفظ Lifecycle كتاريخ Events مع Current State متسقة معه.
5. اشتقاق Upcoming / Due / Overdue من الموعد وحالة التنفيذ وعدم تعديلها يدويًا.
6. دعم Explicit In-Progress Transition وتسجيل `started_at` والمنفذ.
7. زر تنفيذ المهمة يفتح الـCanonical Workflow Action المناسب مع تمرير السياق.
8. نجاح الـCanonical Event المطلوب يكمل المهمة تلقائيًا ويربط `result_event_reference` والمنفذ والوقت.
9. دعم Direct Completion للمهام التي لا ينتج عنها Canonical Event، مع نتيجة / ملاحظة اختيارية.
10. كل Postponement يسجل كEvent مستقل مع الحفاظ على Original Due Date وتحديث Current Due Date فقط كنتيجة للتاريخ.
11. كل Cancellation يسجل كEvent مستقل يحفظ السبب والمنفذ والوقت ومصدر الإلغاء عند وجوده.
12. دعم مستخدم واحد مسؤول حاليًا مع Reassignment History.
13. عدم وجود Post-execution Approval في النموذج الحالي.
14. إبقاء قواعد التوليد والتوقيت والأولوية والتكرار والتنبيه في Settings 6.12.
15. إبقاء تعريف نوع المهمة في Master Data 1.19.
16. عدم تحويل Alert إلى Task تلقائيًا دون Rule معتمدة.
17. عدم تنفيذ Domain Operation داخل Generic Task Form إذا كان لها Workflow Canonical.
18. الفصل صراحة في التصميم النهائي بين Execution Lifecycle State وSchedule State.
19. حسم UX بدء `In Progress` عند فتح العملية Canonical قبل التنفيذ النهائي.
20. الحفاظ على Idempotent linking بين المهمة والـCanonical Result Event في التصميم النهائي.

---

## 13. حالة المراجعة النهائية للقسم

```text
Questions answered: 12 / 12
Applicable: 12 / 12
Pending questionnaire review: 0
Blocking internal conflicts: 0
Open interpretation/integration points: 4
```

النقاط المفتوحة لا تمنع الانتقال للقسم التالي:

1. الفصل التنفيذي النهائي بين `current_status` وSchedule State.
2. طريقة Start/In-Progress عند فتح Canonical Action.
3. Enum حالات التنفيذ النهائي لم يعتمد كسؤال مستقل.
4. يجب أن تلتزم Settings 6.12 لاحقًا بقراري `no_post_execution_approval` و`single_user_assignment_with_history` ما لم يعاد فتحهما صراحة.
