# 4.1 استقبال الحيوان من الخارج وإعادة الإدخال — دليل تفسير الإجابات

> **الحالة:** مسودة أولى مبنية على الإجابات الحالية للمراجعة  
> **ملف الإجابات المقابل:** `questionnaire-export/answers/04-workflow/01-animal-intakes.md`  
> **Question Keys المغطاة:** 11 / 11 — منها 10 مطبقة حاليًا و1 غير مطبق بسبب الـDependency

---

## 1. الغرض من هذا الملف

هذا الملف يفسر **المعنى الوظيفي والتقني للإجابات المعتمدة فعليًا** في قسم `استقبال الحيوان من الخارج وإعادة الإدخال` ضمن `الحركات ودورة التشغيل الفعلية`.

هو ليس مصدرًا بديلًا للإجابات، ولا يغير أي قرار موجود في ملف الإجابات.

```text
answers/04-workflow/01-animal-intakes.md
= ماذا قررنا؟

                    ↓ تفسير

guides/04-workflow/01-animal-intakes.md
= ماذا تعني هذه القرارات تقنيًا؟ وما حدودها؟
```

القاعدة المعمارية الأساسية:

```text
Animal Source
= من أين جاء الحيوان؟

Animal Intake
= ماذا حدث فعليًا عند وصوله؟

Animal Re-entry
= عودة نفس الحيوان المسجل سابقًا مع الحفاظ على هويته

Housing
= أين تم تسكينه بعد ذلك؟
```

---

## 2. الهدف الوظيفي من القسم

هذا القسم يمثل **مسار الاستقبال الفعلي للحيوان القادم من خارج المزرعة**، وكذلك إعادة استقبال نفس الحيوان إذا عاد بعد خروج سابق.

المسار المفاهيمي الحالي:

```text
Arrival
→ Animal identity / source already known or completed
→ Entry Weight
→ Initial Evaluation
→ Initial Decision
→ Temporary Monitoring / Isolation when needed
→ Re-evaluation / Preventive Actions when applicable
→ Explicit Final Approval
→ Housing / Further Operation
```

المبدأ الوظيفي من المرجع:

> دخول الحيوان إلى المزرعة لا يعني تلقائيًا دخوله إلى القطيع الإنتاجي.

ويجب الفصل بين:

```text
Physical arrival
≠
Intake approval
≠
Production readiness
≠
Housing
```

فالحيوان قد يكون موجودًا فعليًا داخل المزرعة لكنه ما زال تحت التقييم أو الملاحظة أو العزل، وغير متاح بعد للتشغيل الإنتاجي.

---

## 3. ملخص القرارات المعتمدة

الصورة الحالية التي تحسمها الإجابات:

```text
Animal Intake
├── Reception Mode
│   └── Individual reception only
│
├── Entry Weight
│   └── required before intake completion
│
├── Initial Evaluation
│   ├── required
│   ├── general condition
│   ├── visible injury
│   ├── visible disease signs
│   ├── body condition
│   └── notes
│
├── Initial Outcomes
│   ├── accepted
│   ├── observation
│   ├── health_isolation
│   └── rejected
│
├── Temporary Monitoring / Quarantine Stage
│   └── supported
│       ├── monitoring_started
│       ├── re_evaluated
│       ├── monitoring_extended
│       ├── passed
│       ├── transferred_to_health_workflow
│       └── rejected
│
├── Preventive Actions / Checks
│   └── actual performed actions can be recorded
│
├── Finalization
│   └── explicit final approval required
│
└── Re-entry
    └── full intake cycle on the same Animal Record
```

---

## 4. تفسير Question Keys والإجابات

### 4.1 `animal_intake.reception_modes`

القرار الحالي:

```text
individual_reception
```

أي أن استقبال الحيوان يتم بصورة فردية، ولكل حيوان مسار استقبال مستقل.

لم يتم اعتماد:

```text
batch_reception_with_individual_records
```

لذلك لا يوجد Requirement حالي لدعم دفعة شراء / صفقة استقبال جماعية في نفس العملية.

هذا لا يمنع وجود أكثر من حيوان يصل في نفس اليوم، لكنه يعني أن النظام لا يحتاج حاليًا إلى مفهوم `AnimalIntakeBatch` لتجميعهم في عملية واحدة.

### 4.2 `animal_intake.batch_shared_fields`

هذا السؤال **غير مطبق حاليًا** بسبب عدم اعتماد الاستقبال الجماعي.

إذن لا تعتمد حاليًا حقول مشتركة مثل:

```text
batch_reference
shared arrival datetime
shared source party
shared notes
```

ولا يجوز إنشاء كيان Batch من تلقاء نفسه.

### 4.3 `animal_intake.entry_weight_policy`

القرار:

```text
required_during_intake
```

أي أن **وزن الدخول إلزامي قبل إكمال مسار الاستقبال**.

لكن الوزن لا يحفظ كحقل ثابت داخل Animal Record.

التفسير الصحيح:

```text
Actual measurement
→ Weight Event / Weight History
→ Measurement Type = Entry Weight
```

وهذا متوافق مع المرجع الوظيفي الذي يعتبر وزن الدخول نقطة بداية مهمة للحيوان، ومع القاعدة العامة للمشروع:

```text
Actual Weight → Workflow
Current Weight → Derived from Weight History
```

لا يحسم هذا السؤال:

- وحدة القياس.
- الجهاز المستخدم.
- حدود الوزن المقبولة.
- شروط رفض الحيوان بسبب الوزن.

هذه تفاصيل وقواعد مستقلة.

### 4.4 `animal_intake.initial_evaluation_required`

الإجابة: **نعم**.

كل حيوان خارجي يجب أن يمر بتقييم أولي مستقل قبل الاعتماد النهائي.

هذا التقييم ليس ملفًا صحيًا كاملًا، وإنما **نقطة تقييم تشغيلية عند الدخول** تساعد في تحديد المسار التالي.

### 4.5 `animal_intake.initial_evaluation_fields`

التقييم الأولي يدعم:

```text
general_condition
visible_injury
visible_disease_signs
body_condition
notes
```

هذه البيانات تصف حالة الحيوان عند واقعة الاستقبال.

ولا تعني إنشاء نظام تشخيص أو علاج بيطري كامل.

لا يجوز استنتاج:

- تشخيص مرض محدد.
- بروتوكول علاجي.
- قائمة أدوية.
- تصنيف طبي تفصيلي.

أي انتقال إلى متابعة صحية متخصصة يتم ضمن المسار الصحي المخصص في Workflow.

### 4.6 `animal_intake.initial_decision_outcomes`

تم اعتماد أربع نتائج:

```text
accepted
observation
health_isolation
rejected
```

المعنى:

- `accepted` → الحيوان يمكنه الاستمرار في مسار الإدخال نحو الاعتماد.
- `observation` → يحتاج متابعة وإعادة تقييم قبل القرار النهائي.
- `health_isolation` → يحتاج الانتقال إلى مسار عزل / متابعة صحية مناسب.
- `rejected` → غير مناسب للاستمرار في الإدخال.

هذه نتائج فعلية للتقييم، لكن **قواعد اختيار النتيجة نفسها لا يحددها هذا القسم**.

مثلًا لا يحدد هذا القسم:

- أي وزن يسبب الرفض.
- أي عرض صحي يستوجب العزل.
- مدة الملاحظة.
- شروط القبول النهائي.

هذه قواعد مكانها Settings عند اعتمادها.

### 4.7 `animal_intake.temporary_monitoring_stage`

الإجابة: **نعم**.

إذن النظام يجب أن يدعم مرحلة مؤقتة للحيوان أثناء الاستقبال، مثل الملاحظة أو الحجر، عند الحاجة.

لكن القرار لا يقول إن كل حيوان يجب أن يدخل الحجر.

```text
Temporary Monitoring Stage supported
≠
Mandatory quarantine for every animal
```

هل المرحلة مطلوبة؟ وكم مدتها؟ ومتى تنتهي؟ هذه قواعد تشغيلية مستقلة.

### 4.8 `animal_intake.temporary_monitoring_events`

أحداث مرحلة الملاحظة / الحجر المعتمدة:

```text
monitoring_started
re_evaluated
monitoring_extended
passed
transferred_to_health_workflow
rejected
```

إذن هذه المرحلة ليست Status واحدًا فقط، وإنما مسار له أحداث قابلة للتتبع تاريخيًا.

ويجب الحفاظ على الفرق بين:

```text
Observation / intake monitoring
≠
Health Workflow
```

فإذا احتاج الحيوان متابعة صحية متخصصة، يسجل التحويل إلى المسار الصحي بدل تحميل Intake بكل التفاصيل الطبية.

### 4.9 `animal_intake.preventive_actions_recording`

الإجابة: **نعم**.

يجب أن يستطيع النظام تسجيل أن فحصًا أو إجراءً وقائيًا تم **فعليًا** أثناء الاستقبال قبل الاعتماد عند الحاجة.

لكن هذا السؤال لا يحدد:

- قائمة الإجراءات.
- أسماء الفحوص.
- هل إجراء معين إلزامي لكل حيوان.
- دورية أو بروتوكول طبي.

إذن الـRequirement هنا هو **إثبات التنفيذ الفعلي**، وليس اختراع Catalogue طبي أو قواعد وقائية غير محسومة.

### 4.10 `animal_intake.finalization_model`

القرار:

```text
explicit_final_approval
```

أي أن مسار الاستقبال لا يغلق تلقائيًا بمجرد آخر نتيجة إيجابية.

يجب وجود **حدث اعتماد نهائي صريح**.

المعنى المفاهيمي:

```text
Required steps completed
        ↓
Explicit Final Approval
        ↓
Intake completed
```

وهذا الحدث يعني أن الحيوان اجتاز مسار الدخول، لكنه لا يعني تلقائيًا:

- جاهز للتلقيح.
- جاهز للانضمام إلى مجموعة إنتاجية.
- مستوفٍ كل شروط الإنتاج.
- تم تسكينه فعلًا.

هذه مراحل / قرارات أخرى.

### 4.11 `animal_intake.reentry_process_model`

القرار:

```text
full_intake_cycle_same_animal
```

عند عودة حيوان ثبت أنه **نفس الحيوان المسجل سابقًا**:

```text
Existing Animal Record
→ Re-entry Event
→ Full Intake / Evaluation Cycle again
```

ولا يتم:

```text
Create new Animal
Reset identity
Delete previous history
Replace old internal code
```

هذا متوافق مع القاعدة الأساسية:

```text
One physical animal
→ One continuing Animal Record throughout its life
```

إعادة تطبيق دورة الاستقبال تعني إعادة الوزن والتقييم والمراحل المطلوبة وفق هذا المسار، لكنها لا تعيد إنشاء الهوية.

---

## 5. العلاقة مع الأقسام الأخرى

### مع `3.1 بيانات وهوية الحيوان`

الهوية والكود والجنس والسلالة ليست أحداث Intake جديدة في كل مرة.

إعادة الإدخال لا تعيد إنشاء `Animal Record`.

### مع `3.2 مصدر الحيوان وبداية السجل`

```text
3.2 Source
= من أين جاء الحيوان؟

4.1 Intake
= ماذا حدث عند وصوله فعليًا؟
```

بيانات المصدر لا تكرر كحدث استقبال، والعكس صحيح.

### مع `4.2 التسكين والنقل والإخلاء`

اعتماد الاستقبال لا يساوي حركة تسكين.

بعد الاعتماد، التسكين الفعلي يجب أن ينتج من Workflow التسكين حتى يظل الإشغال والموقع الحالي مشتقين من الحركات.

### مع `4.3 الوزن والقياسات التشغيلية`

وزن الدخول يجب أن يدخل في **Canonical Weight History** كقياس فعلي، ولا ينشئ مصدر وزن موازيًا خاصًا بالاستقبال.

### مع `4.13 الصحة والعزل والتعافي والنفوق`

التقييم الأولي والملاحظة في Intake يظلان ضمن نطاق الاستقبال.

إذا تحولت الحالة إلى متابعة صحية متخصصة، يستخدم المسار الصحي بدل توسيع Intake إلى ملف علاجي.

### مع `4.15 الخروج من المزرعة وإعادة الدخول`

`animal_intake.reentry_process_model` يحسم **كيف يعاد تطبيق مسار الاستقبال على نفس الحيوان**.

لكنه لا يحسم وحده:

- كيف ترتبط العودة بواقعة الخروج السابقة.
- Presence History.
- شروط اعتبار الحيوان خارج المزرعة أو عائدًا رسميًا.

هذه الحدود تنتمي إلى Workflow الخروج وإعادة الدخول.

---

## 6. حدود مهمة للرفض والخروج

`rejected` في التقييم أو في الملاحظة يعني أن الحيوان **لم يجتز مسار الإدخال**.

لكن لا يجوز تفسير ذلك بأنه:

```text
Delete Animal Record
```

إذا تم إنشاء سجل للحيوان أو تم إثبات وجوده فعليًا، يجب الحفاظ على السجل والتاريخ.

كما أن سؤال Intake لا يحسم تلقائيًا كيفية تسجيل مغادرته الفعلية بعد الرفض؛ حدث الخروج وحفظ Presence History مكانه في المسار المختص.

المرجع الوظيفي يقرر صراحة أن الحيوان المرفوض لا يحذف، بل يحتفظ بتاريخه.

---

## 7. ما لا يجوز استنتاجه

لا يجوز استنتاج أي من التالي من الإجابات الحالية:

```text
Batch intake support
Mandatory quarantine for every external animal
Fixed quarantine duration
Medical diagnostic system
Fixed preventive procedure list
Automatic acceptance criteria
Automatic rejection criteria
Specific health thresholds
Automatic housing after approval
Production readiness after intake approval
New Animal Record on re-entry
New internal code on re-entry
Automatic deletion when rejected
Automatic paternity / production-group assignment
```

كما لا يجوز اختراع إجراءات صحية أو أرقام علمية أو مدد حجر لم يعتمدها الاستبيان.

---

## 8. فحص الاتساق

**الحالة:** لا يوجد تعارض داخلي مانع في الإجابات الحالية.

القرارات متسقة مع المرجع الوظيفي ومع الأقسام السابقة:

```text
External Animal
→ Arrival
→ Entry Weight
→ Initial Evaluation
→ Monitoring / Isolation when needed
→ Re-evaluation
→ Explicit Approval
→ Housing / Operation
```

كما أن قرار إعادة الإدخال متوافق مع مبدأ استمرار نفس `Animal Record`.

النقاط المفتوحة ليست تعارضات، وإنما تفاصيل تحسم في أقسام أخرى، أهمها:

- قواعد الحجر ومدته → Settings.
- معايير القبول والرفض → Settings.
- التسكين الفعلي → 4.2.
- السجل الصحي التفصيلي → 4.13.
- علاقة العودة بالخروج وPresence History → 4.15.

---

## 9. المخرجات المطلوبة للـRequirements

1. دعم استقبال **حيوان واحد بصورة فردية** في المسار الحالي.
2. عدم إنشاء مفهوم Batch Intake في النسخة الحالية من المتطلبات.
3. إلزام وزن دخول قبل إكمال الاستقبال، مع حفظه في Weight History.
4. إلزام تقييم أولي مستقل قبل الاعتماد.
5. دعم عناصر التقييم الخمسة المعتمدة.
6. دعم نتائج `accepted / observation / health_isolation / rejected`.
7. دعم مرحلة ملاحظة / حجر مؤقتة عند الحاجة.
8. حفظ أحداث بدء الملاحظة، إعادة التقييم، التمديد، الاجتياز، التحويل للصحة، والرفض.
9. دعم إثبات الفحوص أو الإجراءات الوقائية المنفذة فعليًا دون افتراض قائمتها.
10. اشتراط اعتماد نهائي صريح لإغلاق مسار الاستقبال.
11. إعادة إدخال نفس الحيوان عبر **دورة استقبال كاملة جديدة على نفس Animal Record**.
12. عدم مساواة اعتماد الاستقبال بجاهزية الإنتاج أو التسكين الفعلي.
13. عدم حذف سجل الحيوان بسبب الرفض، والحفاظ على التاريخ التشغيلي.
14. إبقاء قواعد الحجر والقبول والرفض والجاهزية خارج هذا القسم ضمن Settings.
