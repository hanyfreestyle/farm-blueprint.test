# 4.10 تحديد المصير والاستبعاد من المسار — دليل تفسير الإجابات

> **الحالة:** مسودة أولى مبنية على الإجابات الحالية للمراجعة — القسم متماسك داخليًا، مع تعارض عابر مع 4.12 حول توقيت بدء مسار التسمين، وفجوة تكامل مع 4.9 تخص Lifecycle قرار المصير المعلق قبل الاعتماد  
> **ملف الإجابات المقابل:** `questionnaire-export/answers/04-workflow/10-exclusions.md`  
> **Question Keys المغطاة:** 11 / 11

---

## 1. الغرض من هذا الملف

هذا الملف يفسر **المعنى الوظيفي والتقني للإجابات المعتمدة فعليًا** في قسم `تحديد المصير والاستبعاد من المسار` ضمن `الحركات ودورة التشغيل الفعلية`.

هو لا يغير الإجابات، ولا يعيد تعريف أسباب الاستبعاد الموجودة في Master Data، ولا يساوي بين قرار الاستبعاد وبين خروج الحيوان من المزرعة.

```text
answers/04-workflow/10-exclusions.md
= ماذا قررنا؟

                    ↓ تفسير

guides/04-workflow/10-exclusions.md
= ماذا تعني القرارات تقنيًا؟ وما حدودها؟
```

الحد المعماري الأساسي:

```text
Preliminary Evaluation / Direction
→ 4.9

Approved Fate Decision / Exclusion Decision
→ 4.10

Replacement Candidate / Approval Execution
→ 4.11

Fattening Execution
→ 4.12

Actual Exit from Farm
→ 4.15

Exclusion Reason Dictionary
→ Master Data 1.8
```

والقاعدة الأساسية:

```text
ExclusionReason
≠
Exclusion Decision
≠
Exit Event
```

وكذلك:

```text
Excluded from a path
≠
Exited from the farm
```

---

## 2. الهدف الوظيفي من القسم

الغرض هو تحويل **التوصية أو التقييم المبدئي** إلى **قرار مصير معتمد** يمكن تتبعه تاريخيًا، ثم تسليم الحيوان إلى المسار التنفيذي المناسب دون تنفيذ نفس الحدث مرتين.

الصورة الحالية:

```text
Growth / Sorting Evaluation
        ↓
Pending Fate Decision when applicable
        ↓
Approved Fate Decision in 4.10
        ↓
Pending downstream transition
        ↓
Actual downstream workflow
        ├── Replacement → 4.11
        ├── Fattening → 4.12
        ├── Exit → 4.15
        └── Re-evaluation → 4.9
```

بالنسبة للاستبعاد:

```text
Animal in Operational Path
        ↓
Exclusion Decision
        ↓
Source Path closes
        ↓
Next Destination is known
        ↓
Downstream execution happens separately
```

---

## 3. ملخص القرارات المعتمدة

```text
Fate Decision Record
├── animal
├── decided_at
├── age_at_decision
├── weight_reference
├── decision_context
├── source_reference
├── decision_type
├── decision_notes
└── decided_by

Source Reference
└── explicit optional reference

Decision History
└── independent records + supersession link

Supported Outcomes
├── replacement_candidate_path
├── fattening_path
├── exclude_from_current_path
├── continue_monitoring_or_reevaluation
└── other_documented_path

Downstream Handoff
└── create pending downstream transition

Exclusion Scope
├── growth_sorting_path
├── replacement_candidate_path
├── production_breeding_path
└── other_operational_path

Exclusion Reason
├── Master Data reason required
└── if reason = other → text detail required

After Exclusion
├── next destination stored on exclusion decision
└── categories:
    ├── fattening
    ├── planned_sale_or_exit
    ├── temporary_followup
    ├── planned_final_exit
    └── other_documented_destination

Source Path Closure
└── closes immediately when exclusion decision is approved
    while downstream execution remains pending
```

---

## 4. تفسير Question Keys والإجابات

### 4.1 `fate_decision.record_fields`

تم اعتماد جميع الحقول التالية:

```text
animal
decided_at
age_at_decision
weight_reference
decision_context
source_reference
decision_type
decision_notes
decided_by
```

المعنى:

- `animal` → نفس Animal Record المستمر، ولا ينشأ حيوان جديد بسبب تغيير المسار.
- `decided_at` → توقيت القرار الفعلي المعتمد.
- `age_at_decision` → قيمة مشتقة عند إمكان حسابها من تاريخ الميلاد.
- `weight_reference` → مرجع إلى Weight History في 4.3، وليس نسخة وزن مستقلة.
- `decision_context` → المرحلة أو السياق الذي تم فيه القرار.
- `source_reference` → مرجع اختياري إلى التقييم أو السجل الذي أدى للقرار.
- `decision_type` → نوع المصير المعتمد.
- `decision_notes` → مبرر أو تفاصيل القرار.
- `decided_by` → صاحب القرار / المستخدم الذي اعتمده.

لا يجوز تفسير هذه الحقول كبديل لأحداث المسارات التالية؛ فهي تصف **القرار** فقط.

---

### 4.2 `fate_decision.source_reference_model`

القرار:

```text
explicit_optional_source_reference
```

أي أن Fate Decision يمكن أن يحتفظ بمرجع مباشر إلى مصدر القرار عندما يوجد مصدر واضح مثل:

```text
Growth Evaluation in 4.9
Replacement Follow-up in 4.11
Production Performance Review
Other documented operational review
```

لكنه مرجع **اختياري**، لذلك لا يجوز افتراض أن كل قرار مصير يجب أن يأتي من 4.9 تحديدًا.

---

### 4.3 `fate_decision.history_model`

القرار:

```text
independent_decisions_with_supersession_link
```

أي أن قرارات المصير لا يتم Overwrite لها.

إذا تغير القرار لاحقًا:

```text
Fate Decision A
        ↓ superseded by
Fate Decision B
```

يبقى القرار الأول جزءًا من التاريخ.

وهذا يحافظ على معرفة:

- ماذا كان القرار السابق؟
- متى تغير؟
- ما القرار الذي حل محله؟

ولا يسمح بتحويل `decision_type` إلى Status واحد يتغير ويمحو السياق السابق.

---

### 4.4 `fate_decision.outcome_categories`

النتائج المعتمدة:

```text
replacement_candidate_path
fattening_path
exclude_from_current_path
continue_monitoring_or_reevaluation
other_documented_path
```

هذه **قرارات مصير معتمدة** وليست مجرد Recommendations مثل نتائج 4.9.

الفصل المطلوب:

```text
4.9 Preliminary Result
≠
4.10 Approved Fate Decision
```

ثم أيضًا:

```text
4.10 Approved Fate Decision
≠
Actual downstream execution
```

مثلًا:

```text
Fate Decision = Fattening
≠
Housing Transfer to fattening cage
≠
Fattening follow-up events
```

---

### 4.5 `fate_decision.downstream_transition_model`

القرار:

```text
create_pending_downstream_transition
```

المعنى أن اعتماد القرار لا ينفذ تلقائيًا كل أحداث المسار التالي.

بل ينشأ رابط / انتقال معلق:

```text
Approved Fate Decision
        ↓
Pending Downstream Transition
        ↓
Actual downstream workflow execution
```

أمثلة:

```text
Replacement Decision
→ pending handoff
→ 4.11 candidate / replacement workflow
```

```text
Fattening Decision
→ pending handoff
→ 4.12 fattening workflow
```

```text
Planned Exit
→ pending handoff
→ 4.15 actual Exit Event
```

هذا القرار يمنع تكرار نفس الواقعة داخل أكثر من قسم.

#### تعارض عابر مع 4.12

4.12 حسم لاحقًا:

```text
fattening.entry_boundary_model
= start_on_fattening_fate_decision
```

أي أن Fattening Period يبدأ لحظة اعتماد Fate Decision نفسه، حتى لو كان التسكين ما زال معلقًا.

بينما 4.10 يقول إن القرار ينشئ **Pending Downstream Transition** والتنفيذ الفعلي يتم في المسار التالي، وليس `auto_start_downstream_context`.

إذن يوجد تعارض مباشر يحتاج حسمًا:

```text
4.10
Decision → Pending transition → downstream starts later

VS

4.12
Fattening starts at Decision timestamp
```

ولا يجوز للـGuide اختيار أحدهما من تلقاء نفسه.

---

## 5. الاستبعاد كقرار عن مسار وليس خروجًا من المزرعة

### 5.1 `exclusion.scope_categories`

تم اعتماد الاستبعاد من:

```text
growth_sorting_path
replacement_candidate_path
production_breeding_path
other_operational_path
```

المعنى أن Exclusion Decision ينهي **الأهلية أو الاستمرار في مسار محدد**.

مثال:

```text
Animal excluded from replacement candidacy
→ may remain alive and present in farm
→ may move to fattening
```

أو:

```text
Production Animal excluded from breeding program
→ may still remain in farm temporarily
→ next fate determined separately
```

إذن يجب عدم إنشاء حالة عامة غامضة:

```text
animal.status = excluded
```

دون معرفة **من أي مسار** تم استبعاده وماذا سيحدث بعد ذلك.

---

### 5.2 `exclusion.reason_reference_requirement`

القرار:

```text
master_data_reason_required
```

أي أن كل Exclusion Decision يجب أن يشير إلى `ExclusionReason` فعال / صالح للاستخدام من Master Data.

القائمة لا تعاد داخل Workflow.

```text
ExclusionReason Master Data
→ reason dictionary

Exclusion Decision
→ actual use of one reason for one animal at one time
```

---

### 5.3 `exclusion.other_reason_detail_policy`

القرار:

```text
require_other_reason_detail
```

إذا كان السبب المرجعي:

```text
other
```

يجب تسجيل وصف نصي يوضح السبب الفعلي للحالة.

هذه نقطة تكمل قرار Master Data 1.8 الذي كان قد اعتمد `other` كقيمة مرجعية دون أن يحسم هناك هل الوصف النصي مطلوب؛ 4.10 حسم الإلزام في واقعة الاستبعاد نفسها.

---

## 6. المصير التالي بعد الاستبعاد

### 6.1 `exclusion.next_destination_model`

القرار:

```text
store_next_destination_on_exclusion
```

أي أن Exclusion Decision لا يترك الحيوان في حالة غامضة بعد الاستبعاد.

السجل نفسه يعرف:

```text
excluded_from_path
+
next_destination_type
```

لكن `next_destination_type` لا يمثل الحدث التنفيذي نفسه.

مثال:

```text
Exclusion Decision
next destination = fattening

≠
Fattening Period execution
```

---

### 6.2 `exclusion.next_destination_categories`

تم اعتماد:

```text
fattening
planned_sale_or_exit
temporary_followup
planned_final_exit
other_documented_destination
```

التفسير:

- `fattening` → توجيه الحيوان لمسار 4.12.
- `planned_sale_or_exit` → نية / مسار خروج مخطط، وليس Sale/Exit Event فعلية.
- `temporary_followup` → الحيوان يحتاج متابعة قبل قرار جديد.
- `planned_final_exit` → خروج نهائي مخطط، لكن التنفيذ الفعلي يبقى في 4.15.
- `other_documented_destination` → مسار آخر موثق، دون اختراع Taxonomy إضافية غير معتمدة.

يجب المحافظة على:

```text
Planned Exit
≠
Actual Exit Event
```

---

### 6.3 `exclusion.source_path_closure_model`

القرار:

```text
close_source_path_on_exclusion_decision
```

أي أن المسار الذي تم الاستبعاد منه ينتهي **عند اعتماد قرار الاستبعاد**، وليس عند اكتمال المسار التالي.

الصورة:

```text
Source Operational Path
        ↓
Approved Exclusion Decision
        ↓
Source Path CLOSED
        ↓
Next Destination = known
        ↓
Pending downstream transition
        ↓
Actual next workflow execution later
```

هذا يعني أن الحيوان قد يوجد لفترة قصيرة في حالة انتقالية مفهومة:

```text
not active in old path
+
not yet operationally started in next path
+
transition pending
```

وهذا ليس فقدًا للحالة؛ بل نتيجة مباشرة للقرار المعتمد.

---

## 7. العلاقة مع 4.9 — Pending Fate Decision

4.9 اعتمد:

```text
create_pending_fate_decision
```

أي أن بعض نتائج الفرز تنشئ Fate Decision معلقة تحتاج اعتمادًا صريحًا في 4.10.

لكن إجابات 4.10 الحالية لا تحسم بصورة مستقلة:

- ما Statuses سجل Fate Decision قبل الاعتماد وبعده؟
- هل Pending Fate Decision هو نفس السجل الذي يصبح Approved أم ينشأ سجل قرار جديد عند الاعتماد؟
- من يملك صلاحية الاعتماد؟
- هل يمكن رفض الـPending Decision أو تعديله قبل الاعتماد؟
- كيف يحفظ تاريخ التعديل قبل الاعتماد؟

إذن يوجد **Integration Gap** وليس تعارضًا في الهدف.

يجب ألا يختلق الـBlueprint النهائي Lifecycle مثل:

```text
pending / approved / rejected
```

إلا بعد قرار صريح أو سؤال يغطيه.

كذلك `reevaluation` في 4.9 لها Workflow متخصص:

```text
Evaluation
→ Reevaluation Request
→ New Evaluation
```

لذلك لا ينبغي تحويلها آليًا إلى Fate Decision نهائيًا إذا كان القرار الحقيقي هو الاستمرار في إعادة التقييم.

---

## 8. العلاقة مع 4.11 — الإحلال

قرار:

```text
replacement_candidate_path
```

في 4.10 يعني اعتماد اتجاه الحيوان إلى **مسار المرشح**.

لا يعني:

```text
Approved production herd member
```

4.11 يفصل بوضوح:

```text
Replacement Candidate
→ Follow-up
→ Eligibility / Rules
→ Final Approval into production herd
```

ويستمر نفس Animal Record طوال المسار.

---

## 9. العلاقة مع 4.12 — التسمين

قرار:

```text
fattening_path
```

يعني أن القرار المعتمد هو تحويل الحيوان إلى التسمين.

لكن التنفيذ الفعلي للتسمين وتتبعه يبقى 4.12، ويشمل:

- Fattening Period.
- أوزان التسمين من 4.3.
- Housing من 4.2.
- متابعة الجاهزية للبيع.

### نقطة التعارض المفتوحة

كما سبق:

```text
4.10 pending downstream handoff
VS
4.12 fattening starts on fate decision
```

هذه النقطة يجب حسمها قبل تثبيت State Machine النهائي لمسار التسمين.

---

## 10. العلاقة مع 4.15 — الخروج

أي من:

```text
planned_sale_or_exit
planned_final_exit
```

هو **نية / مصير مخطط** فقط.

ولا يغير Presence History للحيوان خارج المزرعة.

الخروج الفعلي يجب أن يكون Event مستقلًا في 4.15 يحتوي التوقيت والسبب والسياق الفعلي.

```text
Decision to exit
≠
Actual exit from farm
```

---

## 11. الاتساق مع المرجع الوظيفي

المرجع الوظيفي يدعم بوضوح الفصل التالي:

```text
استبعاد من برنامج التربية
≠
خروج من المزرعة
```

ويذكر أن الحيوان المستبعد من الإحلال قد يظل صالحًا للتسمين، وأن قرار الاستبعاد يجب أن يحتفظ بتاريخ الاستبعاد والمرحلة والسبب والقرار التالي.

كما يدعم وجود قائمة أسباب استبعاد قابلة للإدارة، مع انتقالات مثل:

```text
استبعاد من التربية
→ تحويل للتسمين
```

الإجابات الحالية متوافقة مع هذا المنطق.

---

## 12. فحص الاتساق

### داخليًا داخل 4.10

لا يوجد تعارض داخلي مانع بين الإجابات.

القرارات تتبع سلسلة واضحة:

```text
Decision recorded
→ history preserved
→ exclusion reason required when applicable
→ old path closes on exclusion
→ next destination known
→ downstream action remains separate
```

### تعارض عابر 4.10 ↔ 4.12

```text
4.10
create_pending_downstream_transition

4.12
start_on_fattening_fate_decision
```

**الحالة:** تعارض مباشر يحتاج حسمًا لاحقًا.

### فجوة تكامل 4.9 ↔ 4.10

```text
4.9 creates Pending Fate Decision
```

لكن 4.10 لا يعرف بعد Lifecycle / approval state لهذا السجل المعلق.

**الحالة:** Requirement Gap تحتاج حسمًا، لكنها لا تمنع تفسير باقي 4.10.

---

## 13. حدود لا يجوز استنتاجها من الإجابات الحالية

لا يجوز من هذا القسم افتراض:

- قائمة Statuses لـFate Decision.
- Workflow صلاحيات اعتماد Fate Decision المعلق.
- أن كل Fate Decision يجب أن يكون مصدره 4.9.
- أن `other_documented_path` له قائمة قيم محددة مسبقًا.
- أن الاستبعاد يعني بيعًا أو خروجًا.
- أن قرار الخروج يغلق Presence History قبل Event 4.15.
- أن التحويل للتسمين بدأ تشغيليًا قبل حسم تعارض 4.10/4.12.
- أن استبعاد الحيوان من التربية يحذف عضوية تاريخية أو نتائج أداء قديمة.
- أن تغيير القرار يسمح بتعديل القرار السابق؛ القرارات مستقلة وتحفظ بعلاقات Supersession.

---

## 14. المخرجات المطلوبة للـRequirements

1. **Fate Decision Entity / Record** يحتفظ بالحقول التسعة المعتمدة.
2. **Optional Explicit Source Reference** إلى التقييم أو السجل المصدر.
3. **Immutable Decision History** عبر سجلات مستقلة مع Supersession Link.
4. **Supported Fate Outcomes** للترشيح والتسمين والاستبعاد والمتابعة والمسارات الأخرى الموثقة.
5. **Pending Downstream Handoff** بدل تكرار التنفيذ داخل Fate Decision.
6. **Scoped Exclusion** بحيث يعرف النظام من أي مسار تم استبعاد الحيوان.
7. **Required ExclusionReason Reference** من Master Data لكل قرار استبعاد.
8. **Required Other Reason Detail** عند استخدام سبب `other`.
9. **Explicit Next Destination on Exclusion** بدل ترك الحيوان في حالة Excluded غامضة.
10. **Supported Next Destinations** للتسمين والخروج المخطط والمتابعة والمصائر الأخرى الموثقة.
11. **Close Source Path on Approved Exclusion** مع بقاء التنفيذ التالي Pending.
12. **Excluded ≠ Exited** كقاعدة سلامة معمارية ثابتة.
13. **Decision ≠ Execution**؛ قرار المصير لا يكرر أحداث 4.11/4.12/4.15.
14. **Preserve Same Animal Record** خلال كل انتقالات المصير.
15. **Resolve 4.10 ↔ 4.12 Fattening Start Conflict** قبل تثبيت State Machine النهائي.
16. **Define Pending Fate Decision Lifecycle** الناتج من 4.9 قبل تنفيذ Approval Workflow النهائي.

---

## 15. حالة المراجعة النهائية

```text
Question Keys reviewed: 11 / 11
Applicable: 11 / 11
Answered: 11 / 11
Pending answer review: 0

Internal blocking conflict: none
Cross-section conflict: 1
Cross-section integration gap: 1
```

الخلاصة:

```text
4.10 defines the approved decision and path handoff.
It does not execute the downstream operational event itself.

Exclusion closes a specific operational path,
not the Animal Record and not automatically the animal's farm presence.
```
