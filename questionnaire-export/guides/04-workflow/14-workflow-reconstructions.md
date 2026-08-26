# 4.14 الحالات الاستثنائية وإعادة بناء المسار — دليل تفسير الإجابات

> **الحالة:** مسودة أولى مبنية على الإجابات الحالية للمراجعة — القسم مكتمل 13/13، مع تعارض داخلي مهم بين النموذج العام لإعادة البناء اليدوية وبين حالتي نفوق الأم المرضعة وتصحيح الأحداث التي تطلبان Reconstruction صريحًا، إضافة إلى اختلاف واضح عن المرجع الوظيفي في اكتشاف السياق والمهام المتأثرة  
> **ملف الإجابات المقابل:** `questionnaire-export/answers/04-workflow/14-workflow-reconstructions.md`  
> **Question Keys المغطاة:** 13 / 13

---

## 1. الغرض من هذا الملف

هذا الملف يفسر **المعنى الوظيفي والتقني للإجابات المعتمدة فعليًا** في قسم `الحالات الاستثنائية وإعادة بناء المسار` ضمن `الحركات ودورة التشغيل الفعلية`.

القسم لا يعيد تسجيل الأحداث التي لها مصدر Canonical في Workflow آخر، ولا يمحو التاريخ السابق لتصحيح المسار، ولا يضيف متطلبات طبية أو تشغيلية غير معتمدة.

```text
answers/04-workflow/14-workflow-reconstructions.md
= ماذا قررنا؟

                    ↓ تفسير

guides/04-workflow/14-workflow-reconstructions.md
= ماذا تعني القرارات تقنيًا؟ وما حدودها وتعارضاتها؟
```

الحد المعماري الأساسي:

```text
Canonical Domain Event
→ يسجل في القسم المختص

4.14
→ يصف الانحراف عن المسار
→ يربط ما تأثر به
→ يوجه إعادة بناء الـWorkflow عند الحاجة
```

أمثلة:

```text
Mortality → 4.13
Foster Transfer → 4.7
Actual Weaning → 4.8
Housing Movement → 4.2
Farm Exit → 4.15
Task Lifecycle → 4.17
```

ولا يجوز إنشاء نسخة ثانية من نفس الواقعة داخل `ExceptionEvent` لمجرد أنها أثرت على أكثر من مسار.

---

## 2. الهدف الوظيفي من القسم

المسار الطبيعي قد يكون مثل:

```text
Mating
→ Pregnancy
→ Birth
→ Lactation
→ Weaning
```

لكن التشغيل الواقعي قد ينحرف عنه بسبب:

- فقد حمل أو إجهاض.
- اكتشاف حمل كاذب أو تشخيص سابق لم يعد صحيحًا.
- نفوق أم أثناء حمل أو رضاعة.
- فقد الحيوان ثم العثور عليه.
- خطأ في بيانات الهوية مثل الجنس.
- تسجيل حدث تشغيلي حساس على الحيوان الخطأ.
- حالة استثنائية أخرى لم يخصص لها Event بعد.

القاعدة الأساسية:

```text
Exception
≠ مجرد Status
```

بل يجب الحفاظ على:

```text
What happened?
When?
Why / context?
What became invalid?
What should happen next?
```

مع قاعدة ثابتة:

```text
Correction / Reconstruction
≠ rewriting history
```

---

## 3. ملخص القرارات المعتمدة

```text
Canonical Exception Routing
└── use canonical domain event + 4.14 orchestration only

Unmodeled Exception
└── structured generic Exception Event

Pregnancy Exception Outcomes
├── observed_abortion
├── pregnancy_loss_without_observed_abortion
├── false_pregnancy_or_misdiagnosis
├── health_related_pregnancy_termination
└── other_documented_pregnancy_exception

Pregnancy History Integrity
└── preserve prior positive check + later Exception Event

Affected Context Detection
└── manual selection by user

General Reconstruction Model
└── exception record only + fully manual rebuild

Task Handling after Exception
└── manual review one task at a time

Maternal Death with Active Litter
└── urgent orphan-litter reconstruction + keep Litter open

Missing Animal
└── Missing Event → Found Event → same Animal Record

Sex Correction
└── audited correction when no historical conflict
    + Exceptional Review when conflict exists

Wrong Sensitive Event Correction
└── linked Correction / Cancellation Event
    + preserve original
    + rebuild resulting effects
```

---

## 4. تفسير Question Keys والإجابات

### 4.1 `exception_handling.canonical_routing_model`

القرار:

```text
canonical_event_with_exception_orchestration
```

إذا كانت الواقعة نفسها معروفة أصلًا في قسم متخصص، فيجب تسجيلها هناك مرة واحدة فقط.

مثال:

```text
Mother dies during lactation

Mortality Event
→ 4.13

Impact on active litter / tasks / next care action
→ 4.14 orchestration / reconstruction context
```

ولا يجوز:

```text
Mortality Event in 4.13
+
Second generic death Exception Event in 4.14
```

لنفس الواقعة.

وظيفة 4.14 هي إدارة **أثر الحدث على المسار**، لا إنشاء مصدر حقيقة جديد للحدث نفسه.

---

### 4.2 `exception_handling.unmodeled_case_policy`

القرار:

```text
structured_generic_exception_event
```

إذا حدثت حالة حقيقية لا يوجد لها Event متخصص حاليًا، يسمح بتسجيل `Exception Event` منظم بدل الاكتفاء بملاحظة Timeline غير قابلة للتحليل.

المفهوم المطلوب يشمل على الأقل معنى:

```text
subject
occurred / discovered at
operational context
description
known reason when available
performed / recorded by
next action / outcome
```

لكن هذه الإجابة **لا تسمح باختراع Taxonomy ثابتة لكل الاستثناءات المحتملة**، ولا تعني أن كل حالة استثنائية مستقبلية يجب أن تظل Generic إلى الأبد.

إذا تكرر نوع من الحالات وأصبح له Workflow واضح، يمكن لاحقًا دراسة تحويله إلى Domain Event متخصص.

---

### 4.3 `pregnancy_exception.outcome_categories`

تم اعتماد الفئات التشغيلية التالية:

```text
observed_abortion
pregnancy_loss_without_observed_abortion
false_pregnancy_or_misdiagnosis
health_related_pregnancy_termination
other_documented_pregnancy_exception
```

هذه الفئات لا تعني تشخيصًا بيطريًا تفصيليًا.

الفصل المهم:

```text
Normal Pregnancy Check Result
→ 4.5

Normal Birth Event
→ 4.6

Exceptional termination / invalidation of pregnancy path
→ 4.14
```

ولا يتم تحويل الإجهاض أو فقد الحمل إلى `Birth Event` لمجرد أن الحمل انتهى.

---

### 4.4 `pregnancy_exception.record_fields`

تم اعتماد:

```text
female
reproductive_cycle_or_pregnancy
exception_outcome
occurred_or_discovered_at
gestational_age_or_stage
last_pregnancy_check_reference
known_reason
health_reference
recorded_by
notes
```

التفسير:

- `gestational_age_or_stage` يحسب من المعلومات المتاحة عند الإمكان، ولا يحتاج إدخالًا يدويًا إذا كان يمكن اشتقاقه بصورة موثوقة.
- `last_pregnancy_check_reference` يحتفظ بالصلة مع آخر فحص ذي علاقة دون تعديل نتيجته.
- `health_reference` يشير إلى السجل الصحي في 4.13 عند وجود سبب أو سياق صحي بدل نسخ تفاصيله.
- `known_reason` لا يعني إنشاء قائمة أسباب طبية جديدة داخل 4.14.

---

### 4.5 `pregnancy_exception.history_integrity_model`

القرار:

```text
preserve_prior_check_add_later_exception_event
```

إذا كان لدينا:

```text
Pregnancy Check at T1 = Positive
```

ثم اكتشفنا لاحقًا:

```text
False Pregnancy / Pregnancy Loss at T2
```

يظل سجل T1 كما حدث فعليًا في ذلك الوقت.

ثم يضاف:

```text
Exception Event at T2
```

لتغيير المسار من تاريخ الاكتشاف.

لا يتم:

- حذف الفحص السابق.
- تحويل نتيجته إلى Negative بأثر رجعي.
- تعديل التاريخ لكي يبدو المسار طبيعيًا.

هذه قاعدة Integrity أساسية في المشروع.

---

### 4.6 `workflow_reconstruction.context_detection_model`

القرار الحالي:

```text
manual_affected_context_selection
```

أي أن المستخدم هو الذي يحدد يدويًا المسارات والعلاقات التي تأثرت بالحالة الاستثنائية.

مثال:

```text
Exceptional Event
→ user selects affected Pregnancy / Litter / Housing / Tasks / other context
```

ولا تفرض الإجابة الحالية أن النظام يكتشف هذه العلاقات تلقائيًا من الـTimeline.

#### اختلاف مهم عن المرجع الوظيفي

المرجع الوظيفي يصف في عدة حالات سلوكًا من نوع:

```text
Mother Mortality
→ system detects active Pregnancy or active Litter
```

كما يقرر أن النظام بعد الاستثناء يجب أن يحدد منطقيًا ما لم يعد صالحًا وما الخطوة التالية.

إذن الإجابة الحالية جعلت هذه المسؤولية **أكثر يدوية** من التصور الوظيفي.

هذا اختلاف يجب أن يبقى ظاهرًا في الـBlueprint النهائي ولا يجوز تسويته ضمنيًا.

---

### 4.7 `workflow_reconstruction.action_plan_model`

القرار العام:

```text
exception_record_only_manual_rebuild
```

المعنى الحرفي:

1. يسجل الحدث الاستثنائي.
2. لا ينشأ `Reconstruction Plan` مستقل عام.
3. يترك للمستخدم تنفيذ إعادة بناء المسار يدويًا من خلال الأقسام الأصلية.

مثلًا، إذا انتهى حمل استثنائيًا، فإغلاق/تعديل ما يلزم في المسارات الأخرى لا ينفذ كخطة موحدة مسجلة تلقائيًا وفق هذا القرار العام.

#### تعارض داخلي مهم

هذا القرار يتعارض مع قرارين متخصصين لاحقين داخل نفس القسم:

```text
workflow_reconstruction.maternal_death_with_litter_model
= urgent_orphan_litter_reconstruction_keep_litter_open
```

و:

```text
event_correction.correction_model
= linked_correction_event_preserve_original_and_rebuild_effects
```

الأول يطلب **Reconstruction عاجل** للبطن، والثاني يطلب **Reconstruction للآثار الناتجة عن الحدث المصحح**.

إذن لدينا حاليًا:

```text
General Rule
→ no Reconstruction Plan / fully manual rebuild

VS

Specialized Cases
→ explicit Reconstruction required
```

ولا يجوز للـGuide اختيار أي الطرفين كقاعدة نهائية دون قرار لاحق.

---

### 4.8 `workflow_reconstruction.task_integration_model`

القرار:

```text
manual_task_review_after_exception
```

الاستثناء لا يغلق أو يلغي المهام آليًا.

بعده يجب على المستخدم مراجعة المهام المتأثرة واحدة واحدة، بينما تنفيذ Lifecycle المهمة نفسه يبقى في 4.17.

#### اختلاف عن المرجع الوظيفي

المرجع يعطي أمثلة صريحة مثل:

```text
Abortion
→ cancel birth-preparation tasks
→ cancel birth-date task
→ create maternal follow-up
```

و:

```text
Maternal death during lactation
→ cancel mother's tasks
→ preserve offspring follow-up
→ create urgent action
```

إذن المرجع يفترض على الأقل درجة من **إعادة ترتيب المهام الناتجة عن الاستثناء**، بينما الإجابة الحالية تعتمد مراجعة يدوية بالكامل.

هذا ليس خطأ في التصدير؛ هو قرار حالي يحتاج أن يظل ظاهرًا كاختلاف عن المصدر.

---

### 4.9 `workflow_reconstruction.maternal_death_with_litter_model`

القرار:

```text
urgent_orphan_litter_reconstruction_keep_litter_open
```

نفوق الأم نفسه يسجل مرة واحدة فقط في 4.13.

بعده:

```text
Mortality Event in 4.13
→ active Litter remains open
→ urgent orphan-litter reconstruction
→ actual care action recorded in its canonical workflow
```

الخيارات التشغيلية الممكنة من المرجع تشمل مثلًا:

- Foster Transfer.
- التوزيع على أكثر من أم.
- رعاية بديلة موثقة.
- فطام مبكر إذا سمحت القواعد.

لكن القرار الحالي **لا يسمح بتعيين Foster Mother تلقائيًا من Settings**.

ويجب الحفاظ على:

```text
Mother Death
≠ automatic Litter Closure
```

---

### 4.10 `missing_animal.lifecycle_model`

القرار:

```text
missing_event_then_found_event_same_animal_record
```

عند فقد الحيوان:

```text
Animal Record
→ Missing Event
→ current location can no longer be treated as reliable
```

وعند العثور عليه:

```text
Found Event
→ same Animal Record
→ follow-up / housing action as applicable
```

ولا يتحول Missing تلقائيًا إلى:

```text
Mortality
Sale
Farm Exit
New Animal Record
```

وهذا يحافظ على الهوية والتاريخ.

#### فجوة تكامل مع 4.2

4.2 يعتمد على Housing Movement History كمصدر Canonical للموقع والإشغال.

بينما Missing Event الحالي يقول إن الاعتماد على الموقع الحالي يتوقف.

ما لم يحسم بعد هو:

```text
هل Missing يعلّق Active Occupancy؟
هل ينهيه؟
هل يظل القفص محسوبًا مشغولًا إلى أن تتم تسوية الحالة؟
```

لا يجوز استنتاج أحد هذه السلوكيات من 4.14 وحده؛ يحتاج حسمًا لاحقًا حتى لا تتعارض حالة الفقد مع حساب السعة والإشغال في 4.2.

---

### 4.11 `missing_animal.record_fields`

تم اعتماد:

```text
animal
missing_discovered_at
last_known_housing_reference
last_known_movement_reference
reported_by
found_at
found_location
recovery_action_reference
notes
```

هذه الحقول تدعم إعادة بناء ما كان معروفًا وقت الفقد، ثم ما حدث عند العثور عليه.

`found_location` يصف مكان العثور الفعلي، لكنه لا يغني تلقائيًا عن أي Housing Movement مطلوب لإعادة تسكين الحيوان رسميًا.

`recovery_action_reference` يمكن أن يشير إلى الإجراء Canonical التالي بدل تكرار تفاصيله داخل Missing Event.

---

### 4.12 `sex_correction.conflict_model`

القرار:

```text
audit_correction_or_exception_review_on_conflict
```

الحالتان منفصلتان:

```text
No conflicting operational history
→ allow sex correction
→ preserve audit of old/new value, time, reason, actor
```

لكن إذا كان التغيير يتعارض مع تاريخ تشغيلي قائم:

```text
Sex correction conflicts with prior reproductive events
→ do NOT silently modify
→ open Exceptional Review
```

ولا تنشأ هوية Animal جديدة بسبب تصحيح الجنس.

صلاحية من يملك التصحيح، ومستوى الموافقة، وقواعد التدقيق العامة تبقى في Settings 6.2.

---

### 4.13 `event_correction.correction_model`

القرار:

```text
linked_correction_event_preserve_original_and_rebuild_effects
```

عند تسجيل حدث حساس على نحو خاطئ، مثل Mortality على الحيوان الخطأ:

```text
Original Event
→ remains preserved

Correction / Cancellation Event
→ references original
→ reason
→ actor
→ date/time
→ corrected replacement event when applicable
→ rebuild dependent effects
```

لا يسمح بالنموذج الحالي بـHard Delete للحدث وكأنه لم يوجد.

هذا مهم خصوصًا إذا كان الحدث الأصلي ولّد:

- تغييرات Presence.
- إخلاء Housing.
- إغلاق مسار.
- إلغاء أو إنشاء Tasks.
- تحديث Litter counts.
- تقارير أو تنبيهات مشتقة.

#### تعارض مع النموذج العام

كما سبق، هذا السؤال يطلب صراحة **إعادة بناء آثار الحدث المصحح**، بينما سؤال 4.7 العام اختار `exception_record_only_manual_rebuild`.

إذن نموذج Correction الحالي أقوى من نموذج Reconstruction العام ويحتاج توحيدًا لاحقًا.

---

## 5. حدود الأقسام المجاورة

### مع 4.5 — الحمل

```text
Pregnancy Check actual result
→ 4.5

Later discovery that pregnancy path became invalid / exceptional
→ 4.14
```

الحدث اللاحق لا يعيد كتابة الفحص القديم.

### مع 4.6 — الولادة

الولادة المبكرة أو المتأخرة **إذا حدثت ولادة فعلية** تبقى Birth Event في 4.6 ويحسب توقيتها مقارنة بالنطاق المتوقع.

ولا يجب نقل كل ولادة خارج النافذة إلى Generic Exception Event.

### مع 4.7 — الرضاعة والحضانة البديلة

```text
Need to move offspring after maternal death
→ reconstruction/orchestration in 4.14
→ actual Foster Transfer in 4.7
```

### مع 4.13 — الصحة والنفوق

```text
Health Event / Mortality Event
→ 4.13

Effect on other active workflows
→ 4.14
```

ولا يكرر النفوق كـException Event ثانٍ.

### مع 4.15 — الخروج

Missing لا يعتبر خروجًا عامًا من المزرعة تلقائيًا وفق قرار 4.14.

أما الخروج الحقيقي بسبب بيع أو نقل أو سبب خروج معتمد فيسجل في 4.15.

### مع 4.17 — المهام

4.14 يحدد أن هناك مهامًا قد تصبح غير صالحة، لكن Lifecycle الفعلي للمهمة يبقى في 4.17.

القرار الحالي يجعل مراجعتها يدوية.

---

## 6. فحص الاتساق والتعارضات المفتوحة

### تعارض داخلي A — Reconstruction العام مقابل الحالات المتخصصة

```text
General Reconstruction
= exception only + fully manual rebuild
```

لكن:

```text
Maternal Death with Active Litter
= urgent reconstruction
```

و:

```text
Wrong Sensitive Event Correction
= correction + rebuild effects
```

هذا تعارض حقيقي في نموذج التنفيذ يحتاج حسمًا.

---

### اختلاف B — اكتشاف السياق يدويًا مقابل المرجع الوظيفي

الإجابة الحالية:

```text
manual_affected_context_selection
```

بينما المصدر الوظيفي يصف النظام بأنه يكتشف وجود حمل أو بطن نشطة في عدة سيناريوهات.

يحتاج القرار النهائي تحديد ما إذا كان:

```text
manual only
```

أم:

```text
automatic detection + user confirmation
```

أو نموذج آخر معتمد.

---

### اختلاف C — المهام يدويًا مقابل إعادة ترتيبها في المرجع

الإجابة الحالية:

```text
manual_task_review_after_exception
```

بينما المصدر يطلب في أمثلة الاستثناءات إلغاء مهام لم تعد صالحة وإنشاء متابعة جديدة.

هذا يؤثر مباشرة على تكامل 4.14 مع 4.17 وSettings 6.12.

---

### فجوة D — Missing Event مع Housing Occupancy

الحيوان المفقود لا يمكن الاعتماد على موقعه الحالي، لكن 4.2 يحتاج مصدرًا واضحًا لحساب Active Occupancy.

لا توجد إجابة حالية تحدد أثر Missing Event على الإشغال والسعة.

---

## 7. نقاط من المرجع الوظيفي لا يجوز تحويلها إلى Requirements غير محسومة

المرجع يضع كنقاط تحتاج مراجعة لاحقة:

- التصنيف العلمي النهائي بين الإجهاض وفقد الحمل والحمل الكاذب.
- الإجراءات الدقيقة بعد كل نتيجة.
- مدة الراحة بعد الإجهاض.
- قواعد اختيار الأم الحاضنة.
- الحد الأقصى للمواليد المنقولة.
- شروط الفطام المبكر.
- التعامل التفصيلي مع الأمراض أثناء الحمل والرضاعة.
- الحالات الاستثنائية الواقعية الإضافية.
- صلاحيات إلغاء وتصحيح الأحداث.

لذلك لا يجوز اختراع قيم أو Thresholds أو صلاحيات نهائية من هذا القسم.

هذه القواعد تتكامل أساسًا مع Settings 6.11 و6.2 و6.12 حسب الموضوع.

---

## 8. المخرجات المطلوبة للـRequirements

1. دعم `Canonical Event Routing` بحيث لا ينشأ سجل استثنائي منافس للحدث المتخصص.
2. دعم Structured Generic Exception للحالات غير الممثلة حاليًا.
3. دعم فئات الحمل الاستثنائية الخمس المعتمدة.
4. الاحتفاظ بعلاقة Exception الحمل بدورة الإنتاج والحمل وآخر فحص والحالة الصحية عند انطباقها.
5. الحفاظ على نتيجة فحص الحمل التاريخية وعدم إعادة كتابتها بأثر رجعي.
6. وفق القرار الحالي، تحديد السياقات المتأثرة بالاستثناء يتم يدويًا.
7. وفق القرار العام الحالي، إعادة البناء العام يتم يدويًا بدون Reconstruction Plan مستقل.
8. وفق القرار الحالي، مراجعة المهام المتأثرة تتم يدويًا واحدة واحدة.
9. نفوق الأم المرضعة يسجل في 4.13 مع إبقاء البطن مفتوحة وإنشاء Reconstruction عاجل للرعاية التالية.
10. Missing وFound أحداث مستقلة لنفس Animal Record ولا تتحول تلقائيًا إلى خروج أو نفوق.
11. دعم الحقول التسعة المعتمدة لحالة الفقد/العثور.
12. تصحيح الجنس مسموح مع Audit عندما لا يتعارض مع التاريخ؛ وإلا يفتح Exceptional Review.
13. تصحيح/إلغاء الأحداث الحساسة يتم بحدث مرتبط يحافظ على الأصل ويعيد بناء الآثار، وليس Hard Delete.
14. حسم التعارض بين Reconstruction اليدوي العام والـReconstruction الصريح في الحالات المتخصصة قبل المتطلبات النهائية.
15. حسم أثر Missing على Active Housing Occupancy قبل تثبيت نموذج الإشغال النهائي.
16. عدم اختراع قواعد طبية أو مدد أو Thresholds أو صلاحيات غير معتمدة.

---

## 9. الحالة النهائية للمراجعة

القسم **مكتمل من ناحية الإجابات: 13 / 13** ولا توجد مراجعات معلقة في التصدير الحالي.

لكن لا يمكن اعتباره مغلقًا معماريًا بصورة نهائية قبل حسم:

1. النموذج العام لـWorkflow Reconstruction مقابل الحالات المتخصصة.
2. Manual vs Automatic detection للسياقات المتأثرة.
3. Manual vs linked lifecycle handling للمهام بعد الاستثناء.
4. أثر Missing Event على الإشغال والموقع الحالي.

باقي القرارات قابلة للاستخدام في الـRequirements مع الحفاظ على الحدود المذكورة أعلاه.
