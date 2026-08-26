# 4.11 الإحلال والاعتماد داخل القطيع الإنتاجي — دليل تفسير الإجابات

> **الحالة:** مسودة أولى مبنية على الإجابات الحالية للمراجعة — القسم متماسك داخليًا، مع نقطتي تكامل مفتوحتين تخصان إلزام مرجع الترشيح عند الاعتماد، وربط عضوية المجموعة بتعارض 3.5 المفتوح  
> **ملف الإجابات المقابل:** `questionnaire-export/answers/04-workflow/11-replacements.md`  
> **Question Keys المغطاة:** 11 / 11

---

## 1. الغرض من هذا الملف

هذا الملف يفسر **المعنى الوظيفي والتقني للإجابات المعتمدة فعليًا** في قسم `الإحلال والاعتماد داخل القطيع الإنتاجي` ضمن `الحركات ودورة التشغيل الفعلية`.

هو لا يغير الإجابات، ولا يحدد شروط الاعتماد الرقمية أو قواعد الجاهزية التناسلية من نفسه، ولا ينشئ هوية جديدة للحيوان عند انتقاله إلى القطيع الإنتاجي.

```text
answers/04-workflow/11-replacements.md
= ماذا قررنا؟

                    ↓ تفسير

guides/04-workflow/11-replacements.md
= ماذا تعني القرارات تقنيًا؟ وما حدودها؟
```

الحد المعماري الأساسي:

```text
Growth / Sorting / Re-evaluation
→ 4.9

Approved Fate Decision / Replacement Path Decision
→ 4.10

Replacement Candidate Follow-up
+ Production Herd Approval
→ 4.11

Production Group Definition
→ 3.5

Actual Group Membership Change
→ Workflow event

Actual Mating
→ 4.4

Approval Criteria / Thresholds
→ Settings 6.9

Mating Readiness
→ Settings 6.5 + validation at 4.4
```

والقاعدة المركزية:

```text
Replacement Candidate
≠
Approved Production Herd Member
≠
Ready for Mating
```

كما أن:

```text
Animal Record
= نفس الحيوان طوال المراحل
```

فلا ينشأ Animal جديد بسبب الترشيح أو الاعتماد أو تغيير الدور الإنتاجي.

---

## 2. الهدف الوظيفي من القسم

الغرض من 4.11 هو إدارة الانتقال بين ثلاث حالات مفاهيمية منفصلة:

```text
Animal selected for replacement path
        ↓
Replacement Candidate under follow-up
        ↓
Explicit rule evaluation
        ↓
Human approval
        ↓
Approved Production Herd Membership
```

مع الحفاظ على تاريخ كامل لما سبق الاعتماد.

المرجع الوظيفي يقرر بوضوح أن:

```text
«مرشح للقطيع»
≠
«فرد إنتاجي معتمد»
```

وقد يظل الحيوان فترة تحت المتابعة حتى يستوفي شروط القطيع، ثم يتم اعتماده كفرد إنتاجي.

كما أن الاعتماد داخل القطيع لا يعني بالضرورة أن الحيوان أصبح جاهزًا لأول استخدام تناسلي.

---

## 3. ملخص القرارات المعتمدة

الإجابات الحالية تحسم النموذج التالي:

```text
Candidate Stage
└── mandatory for all replacement sources
    ├── internal replacement
    └── external replacement

Replacement Candidate Record
├── animal
├── nominated_at
├── replacement_source
├── source_decision_reference
├── age_at_nomination
├── weight_reference
├── sorting_context
├── target_production_role
├── selection_reason
├── nominated_by
└── notes

Candidate Follow-up Context
├── Weight History
├── Growth / Sorting History
├── Health Context
├── Pedigree / Litter Origin
├── Current Age
├── Target Production Role
├── Approval Rule Evaluation
└── Replaced Animal Context when applicable

Candidate Not Approved
├── reevaluation → 4.9
└── rejection / path change → new Fate / Exclusion Decision in 4.10

Final Approval
└── explicit human approval after automated rule check

Approval Record
├── animal
├── candidate_reference
├── approved_at
├── age_at_approval
├── weight_reference
├── approved_production_role
├── production_purpose_reference
├── current_housing_reference
├── production_group_reference
├── replaced_animal_reference
├── approval_reason
├── approval_rule_evaluation_reference
├── approved_by
└── notes

Production Role
└── explicitly assigned at approval

Production Purpose
└── reference to Master Data

Group Assignment
└── may happen during approval
    but must create a separate historical membership event

Direct Replacement Link
└── optional reference to replaced animal

Approval vs Mating Readiness
└── explicitly separate
```

---

## 4. تفسير Question Keys والإجابات

### 4.1 `replacement.candidate_stage_policy`

القرار:

```text
all_replacements_use_candidate_stage
```

المعنى أن كل حيوان سيدخل القطيع الإنتاجي عبر الإحلال يجب أن يمر أولًا بمرحلة:

```text
Replacement Candidate
```

سواء كان مصدره:

```text
Internal Replacement
→ مولود داخل المزرعة
→ فطام
→ نمو / فرز
→ Fate Decision
→ Candidate
```

أو:

```text
External Replacement
→ Animal Intake / Re-entry process as applicable
→ Candidate
```

ولا يوجد في القرار الحالي مسار:

```text
External animal
→ direct production herd approval
```

حتى لو بدا الحيوان مؤهلًا؛ يجب أولًا إنشاء مرحلة Candidate ثم متابعة الاعتماد منها.

هذا لا يعني أن فترة المتابعة يجب أن تكون طويلة أو أن لها مدة ثابتة؛ مدة المتابعة وقواعدها مكانها Settings 6.9 ولم تحسم بعد.

---

### 4.2 `replacement.candidate_record_fields`

تم اعتماد الحقول التالية:

```text
animal
nominated_at
replacement_source
source_decision_reference
age_at_nomination
weight_reference
sorting_context
target_production_role
selection_reason
nominated_by
notes
```

التفسير:

- `animal` → نفس Animal Record الموجود بالفعل.
- `nominated_at` → وقت الترشيح الفعلي.
- `replacement_source` → داخلي أو خارجي.
- `source_decision_reference` → مرجع قرار المصير أو التقييم الذي أدى للترشيح عندما يوجد.
- `age_at_nomination` → العمر وقت الترشيح، مشتق عندما يكون تاريخ الميلاد معروفًا.
- `weight_reference` → مرجع Weight History في 4.3، وليس نسخة وزن مستقلة.
- `sorting_context` → مرحلة / نتيجة فرز مرتبطة إن كانت موجودة.
- `target_production_role` → الدور الذي يرشح الحيوان له، مثل ذكر إنتاج أو أنثى إنتاج.
- `selection_reason` → لماذا تم ترشيحه.
- `nominated_by` → المستخدم الذي اعتمد الترشيح.
- `notes` → ملاحظات إضافية.

#### الترشيح الداخلي والخارجي

الحيوان الداخلي غالبًا يمكن أن يحمل:

```text
sorting_context
source_decision_reference
```

من 4.9 / 4.10.

أما الحيوان الخارجي فقد لا يكون له تاريخ نمو وفرز داخلي سابق، لذلك غياب بعض السياقات لا يعني خطأ تلقائيًا ما لم تجعل Settings هذه البيانات شرطًا للاعتماد.

إذن:

```text
Supported context field
≠
Always required data point
```

---

### 4.3 `replacement.candidate_followup_context_sources`

تم اعتماد جميع المصادر التالية للعرض والمراجعة أثناء فترة الترشيح:

```text
weight_history
growth_and_sorting_history
health_context
pedigree_and_litter_origin
current_age
target_production_role
approval_rule_evaluation
replaced_animal_context
```

هذه البيانات **مصادر سياق** وليست نسخًا جديدة داخل Candidate Record.

مثال:

```text
Candidate Follow-up Screen
├── reads Weight History from 4.3
├── reads Growth Evaluations from 4.9
├── reads Health records from 4.13
├── reads Pedigree from 3.3
├── computes Current Age
└── evaluates Settings 6.9 approval rules
```

ولا يجوز بناء حقول يدوية منافسة مثل:

```text
candidate.current_weight
candidate.current_health_state
candidate.current_age
```

إذا كانت هذه القيم مشتقة من مصادرها المعتمدة.

#### `replaced_animal_context`

هذا السياق يستخدم فقط عندما تكون هناك علاقة فعلية بحيوان يراد إحلال المرشح مكانه.

الإحلال لا يلزم دائمًا أن يكون:

```text
one animal out
→ one animal in
```

فقد يكون الهدف زيادة القطيع أو تغطية احتياج عام.

---

### 4.4 `replacement.candidate_nonapproval_handoff_model`

القرار:

```text
reevaluation_or_fate_decision_by_outcome
```

المعنى أن عدم اعتماد المرشح ليس Outcome واحدًا.

يجب الفصل بين حالتين:

#### الحالة الأولى — يحتاج فقط لمتابعة أو تقييم جديد

```text
Candidate
→ Needs Reevaluation
→ 4.9 Reevaluation Request / New Evaluation
```

ولا ينشأ Fate Decision جديد لمجرد أن المسؤول يريد وقتًا أو بيانات إضافية.

#### الحالة الثانية — تم رفض الترشيح أو تغير مصيره

```text
Candidate
→ Rejected / Path Changed
→ New Fate Decision / Exclusion Decision in 4.10
```

وبالتالي لا يتم إنهاء Candidate Record عن طريق تعديل صامت في Status مع كتابة المسار التالي داخله.

يجب الحفاظ على السبب والتاريخ والحدث الذي غير المصير.

---

### 4.5 `replacement.approval_trigger_model`

القرار:

```text
explicit_approval_after_rule_check
```

أي أن الاعتماد النهائي يحتاج عنصرين معًا:

```text
1. System evaluates approval rules
2. Authorized human explicitly approves
```

فاستيفاء الشروط لا ينتج اعتمادًا تلقائيًا.

المسار:

```text
Candidate
→ Rule Evaluation
→ Pass / Result shown to responsible user
→ Explicit Approval Action
→ Production Herd Approval Event
```

ولا يجوز تنفيذ:

```text
rules pass
→ auto approve
```

لأن هذا يخالف القرار الحالي.

#### حدود القرار

السؤال لا يحسم:

- من هي الصلاحية المطلوبة للاعتماد.
- هل يحتاج مستوى إداري أعلى.
- ما المعايير نفسها.
- هل يوجد Override أو Warning لبعض المعايير.

هذه التفاصيل تبقى في Settings / Permissions بحسب القرارات اللاحقة.

---

### 4.6 `replacement.approval_record_fields`

تم اعتماد:

```text
animal
candidate_reference
approved_at
age_at_approval
weight_reference
approved_production_role
production_purpose_reference
current_housing_reference
production_group_reference
replaced_animal_reference
approval_reason
approval_rule_evaluation_reference
approved_by
notes
```

### تفسير الحقول

- `animal` → نفس الحيوان الموجود أصلًا.
- `candidate_reference` → سجل الترشيح الذي سبق الاعتماد.
- `approved_at` → وقت اعتماد الحيوان فعليًا كعضو قطيع إنتاجي.
- `age_at_approval` → العمر في هذه اللحظة.
- `weight_reference` → مرجع الوزن الذي استخدم في القرار.
- `approved_production_role` → الدور الإنتاجي الذي تم اعتماده.
- `production_purpose_reference` → مرجع من Master Data.
- `current_housing_reference` → مرجع الموقع الفعلي / Occupancy الحالي، لا نسخة يدوية من الموقع.
- `production_group_reference` → المجموعة عند تعيينها أثناء نفس العملية.
- `replaced_animal_reference` → الحيوان المستبدل عند وجود إحلال مباشر.
- `approval_reason` → مبرر الاعتماد.
- `approval_rule_evaluation_reference` → دليل / مرجع نتيجة فحص قواعد الاعتماد.
- `approved_by` → صاحب قرار الاعتماد.
- `notes` → ملاحظات.

#### نقطة تكامل: `candidate_reference`

صياغة خيار الحقل تقول:

```text
مرجع سجل الترشيح عند وجوده
```

لكن القرار الأعلى في السؤال 1 حسم:

```text
all_replacements_use_candidate_stage
```

وبناء على **مجموعة الإجابات الحالية معًا** يصبح المسار المنطقي:

```text
Every approved replacement
→ must have passed through Candidate Stage
→ therefore approval should normally have candidate_reference
```

هذا ليس تعديلًا للإجابة، لكنه Requirement تكامل ناتج عن الجمع بين القرارين.

إذا أريد مستقبلًا السماح باعتماد لا يمر بCandidate، عندها فقط يصبح المرجع اختياريًا فعليًا.

---

## 5. الدور الإنتاجي والغرض الإنتاجي

### 5.1 `replacement.production_role_assignment_model`

القرار:

```text
explicit_role_on_approval
```

أي أن دور الحيوان داخل القطيع لا يشتق تلقائيًا من الجنس فقط ولا من هدف الترشيح السابق وحده.

بل يسجل صراحة عند الاعتماد، مثل:

```text
Production Male
Production Female
```

مع التحقق من اتساقه مع بيانات الحيوان والقواعد المعتمدة.

هذا يسمح بالفصل بين:

```text
Candidate Target Role
≠
Final Approved Role
```

فقد يرشح الحيوان لهدف معين ثم يتغير القرار قبل الاعتماد النهائي، بشرط أن يكون التغيير موثقًا ومتسقًا مع المسار.

ولا يعني الدور الإنتاجي أن الحيوان جاهز للتلقيح؛ هذه نقطة مستقلة في السؤال 11.

---

### 5.2 `replacement.production_purpose_reference`

الإجابة:

```text
true
```

أي أن حدث الاعتماد يجب أن يستخدم `ProductionPurpose` من Master Data بدل كتابة الغرض كنص حر.

```text
ProductionPurpose
→ managed reference data

Production Herd Approval
→ references approved ProductionPurpose
```

هذا يحافظ على الاتساق والتحليل التاريخي.

ولا يجوز إعادة إنشاء قائمة أغراض ثابتة داخل 4.11.

كذلك إذا تم تعطيل غرض إنتاجي مستقبلًا، يجب الحفاظ على المرجع التاريخي للسجلات التي استخدمته سابقًا وفق سياسة Master Data، وعدم حذف المعنى من الأحداث التاريخية.

---

## 6. عضوية المجموعة عند الاعتماد

### `replacement.group_assignment_integration_model`

القرار:

```text
allow_group_assignment_with_membership_event
```

أي أن شاشة / عملية الاعتماد يمكن أن تسمح بتعيين الحيوان في Production Group ضمن نفس تجربة العمل.

لكن التعيين نفسه يجب ألا يكون تعديلًا صامتًا داخل Group Record.

المطلوب:

```text
Production Herd Approval Event
        +
Production Group Membership Event
```

مع رابط بين الحدثين عندما تم التعيين كجزء من نفس عملية الاعتماد.

هذا يحافظ على Timeline المجموعة والحيوان.

### لا يعني التعيين تلقيحًا

```text
Group Membership Event
≠
Mating Event
```

حتى لو كانت الأنثى في مجموعة لها Primary Male، لا يثبت ذلك تلقيحًا ولا أبوة.

التلقيح الفعلي يظل في 4.4.

### نقطة تكامل مفتوحة مع 3.5

3.5 يحتوي حاليًا قرارين بينهما تعارض مفتوح:

```text
individual_management
= يمكن إدارة أفراد القطيع دون اشتراط مجموعة
```

مقابل:

```text
required_exactly_one_active_group
= كل أنثى إنتاجية يجب أن تكون في مجموعة نشطة واحدة
```

4.11 الحالي اختار فقط:

```text
allow_group_assignment_with_membership_event
```

أي أنه **يسمح** بالتعيين أثناء الاعتماد لكنه لا يحسم من نفسه أن العضوية شرط لإغلاق الاعتماد.

لذلك لا يجوز من 4.11 وحده فرض:

```text
Female approval blocked unless group assigned
```

ولا يجوز أيضًا إلغاء شرط 3.5.

الـBlueprint النهائي يحتاج أولًا حسم التعارض الموجود في 3.5، ثم تطبيق نتيجته على Validation الاعتماد في 4.11.

---

## 7. ربط الحيوان الذي يتم إحلاله

### `replacement.replaced_animal_link_model`

القرار:

```text
optional_direct_replaced_animal_reference
```

أي أن النظام يدعم حفظ مرجع مباشر إلى الحيوان المستبدل عندما يكون هناك إحلال واضح واحد مقابل واحد، لكنه **ليس إلزاميًا**.

مثال:

```text
Old production male A
↓ replacement relationship when known
New approved male B
```

لكن يمكن أيضًا اعتماد حيوان بدون `replaced_animal_reference` عندما يكون الهدف:

- زيادة عدد القطيع.
- تعويض نقص عام.
- عدم وجود علاقة واحد-مقابل-واحد واضحة.

#### أثر الاختيار الاختياري

حتى في الإحلال المباشر، الإجابة الحالية لا تجعل المرجع Mandatory.

لذلك لا يجوز افتراض أن النظام سيكون قادرًا دائمًا على استخراج تحليل دقيق:

```text
who replaced whom
```

إلا في الحالات التي تم فيها تسجيل المرجع فعليًا.

هذه ليست مخالفة داخلية، وإنما مستوى دقة تاريخي ناتج عن اختيار المرجع الاختياري.

---

## 8. الاعتماد داخل القطيع مقابل جاهزية التلقيح

### `replacement.approval_vs_mating_readiness`

الإجابة:

```text
true
```

وهي من أهم قرارات القسم.

المعنى:

```text
Production Herd Approval
≠
Mating Readiness
```

يمكن أن يصبح الحيوان:

```text
Approved Production Herd Member
```

لكنه يظل:

```text
Not Yet Ready for Mating
```

حتى يستوفي قواعد الجاهزية التناسلية.

هذا متوافق مع المرجع الوظيفي الذي يفرق بين:

```text
أرنب واعد نريد الاحتفاظ به
→ Candidate

أرنب تم اعتماده داخل القطيع
→ Production Herd Member

حيوان بلغ شروط الاستخدام التناسلي
→ Ready for Mating
```

وهذه مفاهيم يجب ألا تختزل في Status واحد.

### أثر ذلك على Settings

6.9 يحدد شروط اعتماد المرشح داخل القطيع.

6.5 يحدد شروط الجاهزية للتلقيح.

لذلك يجب أن تكون القواعد قادرة على تمثيل سيناريو:

```text
Approval rules = passed
Mating readiness rules = not yet passed
```

دون اعتبار ذلك تناقضًا.

---

## 9. العلاقة مع Settings 6.9

4.11 يحدد **ماذا يحدث فعليًا عند الترشيح والاعتماد**، لكنه لا يحدد القيم والمعايير التي تجعل الحيوان مؤهلًا.

الأسئلة الحالية في 6.9 ما زالت غير مجاب عنها، ومنها:

```text
Candidate minimum age
Candidate follow-up duration
Approval factors
Approval age / weight source model
Sex-specific or shared thresholds
Health / pedigree / sorting requirements
Replacement need / operational need
```

ومنها تحديدًا:

```text
replacement_rules.approval_factors
```

الذي يمكن أن يحدد عناصر مثل:

- حد أدنى للعمر.
- حد أدنى للوزن.
- حالة صحية مناسبة.
- عدم وجود قرار مانع.
- قبول النسب.
- قبول نتيجة الفرز.
- استيفاء التكوين المطلوب.
- وجود احتياج تشغيلي للدور الإنتاجي.

لكن هذه الاختيارات **ليست Requirements معتمدة بعد** لأنها لم تتم الإجابة عليها.

إذن:

```text
4.11 says:
Run rule check before approval

6.9 still must say:
What exact rules are checked?
```

ولا يجوز للـGuide اختراع الشروط أو الأرقام.

---

## 10. علاقة 4.11 مع 4.10

4.10 اعتمد:

```text
Replacement Candidate Path
→ Approved Fate Decision
→ Pending Downstream Transition
```

وعند تنفيذ هذا الانتقال في 4.11:

```text
Pending Replacement Transition
→ Replacement Candidate Record
```

ثم تستمر المتابعة حتى الاعتماد أو تغير المصير.

إذا تغير قرار المرشح لاحقًا:

```text
Candidate
→ rejection / change of path
→ new 4.10 Fate / Exclusion Decision
```

وبهذا لا يتم تعديل القرار السابق أو اختفاء تاريخ سبب دخول الحيوان لمسار الإحلال.

### المرشح الخارجي

الحيوان الخارجي قد يأتي من 4.1 ثم يدخل Candidate Stage.

وجود `source_decision_reference` ليس شرطًا منطقيًا دائمًا للحيوان الخارجي إذا لم يسبقه Fate Decision داخلي؛ لكن Candidate Record نفسه مطلوب وفق السؤال 1.

---

## 11. العلاقة مع هوية الحيوان وTimeline

لا ينتج عن أي من الخطوات التالية هوية حيوان جديدة:

```text
Candidate nomination
Production herd approval
Production role assignment
Group assignment
Replacement link
```

كلها ترتبط بنفس Animal Record.

الصورة:

```text
Animal Identity
        ↓
Growth / Sorting History
        ↓
Fate Decision
        ↓
Replacement Candidate
        ↓
Production Herd Approval
        ↓
Production Group Membership when applicable
        ↓
Later Mating / Production History
```

ويمكن الرجوع إلى كل مرحلة تاريخيًا.

---

## 12. فحص الاتساق

### 12.1 الاتساق الداخلي

القسم متماسك داخليًا في جوهره:

- الترشيح منفصل عن الاعتماد.
- الاعتماد صريح وليس تلقائيًا.
- فحص القواعد يسبق الاعتماد.
- الوزن والموقع والصحة تستخدم كمراجع من مصادرها الأصلية.
- رفض المرشح لا يمسح تاريخه.
- الاعتماد لا ينشئ Animal جديدًا.
- Group Assignment Event منفصل عن Approval Event.
- الاعتماد منفصل عن جاهزية التلقيح.

لا يوجد تعارض داخلي يمنع التقدم.

### 12.2 نقطة تكامل: مرجع Candidate عند الاعتماد

```text
candidate_stage_policy
= all replacements must use candidate stage
```

بينما حقل الاعتماد يسمي:

```text
candidate_reference when applicable
```

وفق الإجابات الحالية معًا، يجب اعتبار `candidate_reference` **متوقعًا عمليًا لكل اعتماد Replacement** ما لم يتغير قرار Stage Policy مستقبلًا.

### 12.3 نقطة تكامل مفتوحة مع 3.5

إلزام عضوية الأنثى في مجموعة قبل إغلاق الاعتماد لا يمكن حسمه من 4.11 لأن 3.5 نفسه يحتوي تعارضًا مفتوحًا حول الإدارة الفردية مقابل إلزام المجموعة.

### 12.4 Settings غير مكتملة

الاعتماد يعتمد على Rule Check، لكن قواعد 6.9 لم تتم الإجابة عليها بعد؛ لذلك لا يمكن حتى الآن تحويل 4.11 إلى Validation نهائي قابل للتنفيذ دون إكمال Settings.

هذا **ليس تعارضًا**؛ هو اعتماد متوقع بين Workflow وSettings.

---

## 13. حدود الكيانات والمسؤوليات

```text
Animal
→ الهوية المستمرة

Replacement Candidate
→ مرحلة ترشيح ومتابعة

Production Herd Approval
→ واقعة الاعتماد النهائي داخل القطيع

Production Herd Membership / Role
→ النتيجة التنظيمية للاعتماد

Production Group
→ تعريف المجموعة في 3.5

Production Group Membership Event
→ التغيير الفعلي في العضوية

Mating Event
→ التلقيح الفعلي في 4.4

ProductionPurpose
→ Master Data reference

Weight
→ 4.3

Health
→ 4.13

Approval Rules
→ Settings 6.9

Mating Readiness Rules
→ Settings 6.5
```

ويجب عدم دمج هذه الكيانات في Status واحد عام للحيوان.

---

## 14. ما لا يجوز استنتاجه من الإجابات الحالية

لا يجوز استنتاج أي من التالي دون قرار إضافي:

- عمر ثابت للترشيح.
- مدة ثابتة لمتابعة المرشح.
- وزن ثابت للاعتماد.
- أن الذكور والإناث لهم نفس شروط الاعتماد.
- أن النسب شرط إلزامي للاعتماد.
- أن أي مرشح يستوفي الشروط يعتمد تلقائيًا.
- أن الاعتماد يعني الجاهزية للتلقيح.
- أن كل اعتماد يستبدل حيوانًا محددًا.
- أن كل فرد إنتاجي يجب أن يكون داخل Production Group قبل حسم تعارض 3.5.
- أن المجموعة تثبت الذكر المستخدم في التلقيح أو الأبوة.
- أن الحيوان الخارجي يمكنه تجاوز Candidate Stage.
- أن `approval_rule_evaluation_reference` يعني Schema معينًا أو Snapshot كاملة من كل البيانات؛ بنيته التفصيلية لم تحسم.

---

## 15. المخرجات المطلوبة للـRequirements

يجب أن تعكس المتطلبات النهائية لهذا القسم على الأقل:

1. وجود `Replacement Candidate` كمرحلة مستقلة قبل عضوية القطيع الإنتاجي.
2. إلزام كل إحلال داخلي وخارجي بالمرور بمرحلة Candidate وفق القرار الحالي.
3. الحفاظ على نفس Animal Record طوال الترشيح والاعتماد.
4. إنشاء Candidate Record بالحقول الأحد عشر المعتمدة.
5. عرض سياق المتابعة من مصادر الوزن والنمو والصحة والنسب والعمر والقواعد بدل نسخها يدويًا.
6. توجيه إعادة التقييم إلى 4.9، ورفض الترشيح أو تغيير المصير إلى قرار جديد في 4.10.
7. تنفيذ الاعتماد كحدث بشري صريح بعد Rule Check.
8. عدم تنفيذ اعتماد تلقائي بمجرد نجاح الشروط.
9. إنشاء Production Herd Approval Record بالحقول المعتمدة.
10. ربط الوزن والموقع بمراجع Canonical وعدم إنشاء مصادر منافسة.
11. تسجيل الدور الإنتاجي صراحة عند الاعتماد مع Validation للاتساق.
12. ربط الغرض الإنتاجي بمرجع Master Data بدل النص الحر.
13. السماح بتعيين المجموعة أثناء الاعتماد فقط عبر Membership Event تاريخي مستقل.
14. الحفاظ على الفصل بين Group Assignment وActual Mating.
15. دعم رابط اختياري للحيوان المستبدل عند وجود إحلال مباشر.
16. الفصل الصريح بين Production Herd Approval وبين Mating Readiness.
17. اعتبار Candidate Reference متوقعًا لكل Approval وفق سياسة Candidate Stage الحالية.
18. انتظار حسم تعارض 3.5 قبل جعل Group Membership شرط اعتماد للأنثى الإنتاجية.
19. الاعتماد على Settings 6.9 لتحديد شروط وحدود الاعتماد دون Hardcoding داخل Workflow.
20. الحفاظ على Timeline كامل للترشيح والاعتماد والرفض أو تغيير المسار.

---

## 16. حالة المراجعة النهائية

```text
Internal consistency: PASS
Answered keys: 11 / 11
Blocking internal conflict: NONE
Cross-section open issue: Production Group requirement from 3.5
Integration clarification: Candidate Reference expected under current mandatory Candidate policy
Settings dependency: 6.9 approval rules still unanswered
Mating readiness separation: CONFIRMED
Ready to continue questionnaire guide workflow: YES
```
