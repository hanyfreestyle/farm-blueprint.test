# 4.9 النمو والفرز وإعادة التقييم — دليل تفسير الإجابات

> **الحالة:** مسودة أولى مبنية على الإجابات الحالية للمراجعة — يوجد تعارض مباشر مع 4.8 حول طريقة بدء مسار النمو، مع نقطتي تفسير تخص مؤشرات النمو ونتيجة إعادة التقييم  
> **ملف الإجابات المقابل:** `questionnaire-export/answers/04-workflow/09-growth-sortings.md`  
> **Question Keys المغطاة:** 10 / 10

---

## 1. الغرض من هذا الملف

هذا الملف يفسر **المعنى الوظيفي والتقني للإجابات المعتمدة فعليًا** في قسم `النمو والفرز وإعادة التقييم` ضمن `الحركات ودورة التشغيل الفعلية`.

هو لا يغير الإجابات، ولا يحول أمثلة التصور الوظيفي إلى أرقام ثابتة، ولا يحسم تعارضات مع أقسام أخرى من تلقاء نفسه.

```text
answers/04-workflow/09-growth-sortings.md
= ماذا قررنا؟

                    ↓ تفسير

guides/04-workflow/09-growth-sortings.md
= ماذا تعني القرارات تقنيًا؟ وما حدودها؟
```

الحد المعماري الأساسي:

```text
Weaning / Individual Creation
→ 4.8

Actual Growth / Sorting / Re-evaluation Events
→ 4.9

Actual Fate Decision
→ 4.10

Replacement Execution / Approval
→ 4.11

Fattening Execution
→ 4.12

Actual Weight History
→ 4.3

Growth / Sorting Stages / Thresholds / Criteria
→ Settings 6.9

Reports / Comparison / KPIs
→ Reports
```

---

## 2. الهدف الوظيفي من القسم

بعد الفطام يصبح كل أرنب `Animal Record` مستقلًا، ويبدأ مسار متابعة فردي يسمح بتقييمه على مراحل حتى تتضح وجهته التشغيلية.

المرجع الوظيفي يصف المسار بصورة عامة كالتالي:

```text
فطام
→ تتبع فردي
→ فرز / تقييم على مراحل
→ استمرار أو إعادة تقييم
→ ترشيح مبدئي للإحلال / توصية تسمين / توصية استبعاد
→ قرار مصير فعلي في 4.10
```

الأعمار مثل 45 يومًا و70 يومًا و3 أشهر الواردة في التصور **أمثلة مبدئية فقط** وليست Requirements ثابتة. تعريف مراحل الفرز وتوقيتها ومعاييرها يجب أن يأتي من Settings 6.9.

---

## 3. ملخص القرارات المعتمدة

```text
Growth Program Entry
└── auto_start_after_successful_weaning
    ⚠ يتعارض مباشرة مع 4.8 الذي اختار explicit growth transition

Sorting Stage
└── reference configured stage from Settings

Evaluation History
└── independent evaluation events with links

Evaluation Record
├── animal
├── sorting stage
├── evaluated_at
├── age_at_evaluation
├── current weight reference
├── previous weight references
├── growth metrics context
├── health context
├── pedigree context
├── previous evaluation reference
├── performed_by
└── notes

Evaluation Context
├── weaning weight
├── weight history
├── derived growth metrics
├── sex
├── health context
├── pedigree / litter origin
├── parent performance
├── litter / sibling context
└── previous evaluations

Growth Metrics
└── derived live from Weight History

Preliminary Results
├── continue growth monitoring
├── preliminary replacement candidate
├── preliminary fattening
├── preliminary exclusion
└── reevaluation

Link to 4.10
└── preliminary direction creates pending Fate Decision
    except reevaluation path, which has a specialized flow below

Re-evaluation
└── create linked reevaluation request
    → later create a new independent Evaluation Event
```

---

## 4. بدء مسار النمو بعد الفطام

### `growth_sorting.program_entry_model`

القرار الحالي:

```text
auto_start_after_successful_weaning
```

المعنى داخل 4.9:

```text
Successful Weaning
+ Individual Animal Record exists
→ Growth / Sorting context starts automatically
```

لا يحتاج الحيوان وفق هذا القرار إلى `Start Growth Program Action` مستقلة.

لكن هذا القرار **يتعارض مباشرة** مع 4.8، حيث القرار الحالي هناك:

```text
weaning.post_completion_transition_model
= explicit_growth_start_after_weaning
```

أي:

```text
4.8 → يحتاج Transition مستقل ومسجل
4.9 → يبدأ تلقائيًا بعد الفطام
```

وهذا ليس مجرد اختلاف صياغة؛ بل يحدد هل يوجد Event/Action انتقال مستقل أم لا.

إذن يجب الحفاظ على القرارين كما هما حاليًا، مع اعتبار هذه النقطة **تعارضًا يحتاج حسمًا صريحًا قبل الـBlueprint النهائي**.

---

## 5. مراحل الفرز تأتي من Settings وليست Hardcoded

### `growth_sorting.stage_reference_model`

القرار:

```text
reference_configured_sorting_stage
```

كل `Growth Evaluation Event` يجب أن يشير إلى مرحلة فرز معرفة في إعدادات المزرعة.

إذن:

```text
45 days
70 days
3 months
```

لا تصبح أنواع مراحل ثابتة داخل Workflow.

المرجع الوظيفي يستخدمها كأمثلة، بينما Settings 6.9 هو المكان الذي سيحسم:

- عدد المراحل.
- أسماء المراحل.
- العمر المستهدف لكل مرحلة.
- فترات السماح.
- هل الوزن مطلوب.
- الأوزان المستهدفة والحدود الدنيا.
- عوامل التقييم.
- النتائج المبدئية المسموحة.

بالتالي 4.9 يسجل **المرحلة التي نُفذ عندها التقييم**، ولا يعرف قواعد المرحلة من نفسه.

---

## 6. تاريخ التقييمات لا يُستبدل

### `growth_sorting.evaluation_history_model`

القرار:

```text
independent_evaluation_events_with_links
```

كل فرز أو إعادة تقييم هو سجل مستقل.

```text
Evaluation 1
→ Evaluation 2
→ Evaluation 3
```

مع إمكانية الربط بالتقييم السابق.

هذا يسمح بمعرفة:

- ماذا كان تقييم الحيوان في كل مرحلة.
- لماذا تغير الاتجاه لاحقًا.
- هل كان مرشحًا للإحلال ثم تغيرت التوصية.
- هل كان يحتاج إعادة تقييم ثم تحسن أو تراجع.

ولا يجوز إعادة فتح التقييم السابق وتغيير نتيجته بطريقة تمحو القرار التاريخي القديم.

---

## 7. بيانات سجل التقييم الفعلي

### `growth_sorting.evaluation_record_fields`

الحقول المعتمدة تصف **سياق التقييم وقت حدوثه**.

#### العلاقات الأساسية

```text
animal
sorting_stage
evaluated_at
previous_evaluation_reference
performed_by
```

#### بيانات مشتقة أو مرجعية

```text
age_at_evaluation
current_weight_reference
previous_weight_references
growth_metrics_context
health_context
pedigree_context
```

#### الملاحظات

```text
notes
```

`current_weight_reference` و`previous_weight_references` تعني أن الوزن نفسه لا يتكرر داخل Evaluation؛ المصدر القياسي يبقى 4.3.

### قاعدة مهمة حول `growth_metrics_context`

اسم الاختيار في سؤال الحقول يسمح نظريًا بـ:

```text
مرجع / لقطة مؤشرات النمو المستخدمة
```

لكن السؤال المتخصص `growth_sorting.derived_growth_metric_model` حسم:

```text
derive_live_from_weight_history
```

أي أن المؤشرات **لا تُخزن كقيم مشتقة مستقلة داخل التقييم**.

لذلك التفسير المتوافق هو:

```text
growth_metrics_context
→ مرجع / سياق يوضح أي بيانات أو فترة حساب استُخدمت
→ وليس Snapshot رقمية مستقلة لمؤشرات النمو
```

ولا يجوز اعتماد قيم مشتقة مخزنة داخل Evaluation دون قرار جديد يغير السؤال المتخصص.

---

## 8. المعلومات المتاحة أثناء التقييم

### `growth_sorting.evaluation_context_sources`

تم اعتماد عرض / الرجوع إلى:

```text
weaning_weight
weight_history
derived_growth_metrics
sex
health_context
pedigree_and_litter_origin
parent_performance_context
litter_sibling_context
previous_evaluations
```

هذه **مصادر سياق تساعد المسؤول على اتخاذ القرار** وليست كلها حقولًا جديدة في `Growth Evaluation`.

أمثلة:

```text
Weight History
→ من 4.3

Pedigree
→ من 3.3

Health Context
→ من 4.13 / الحالة الصحية المتاحة

Previous Evaluations
→ من 4.9 نفسه

Parent / Litter Performance
→ فقط عند وجود مؤشرات موثوقة قابلة للاستخدام
```

وجود `parent_performance_context` أو `litter_sibling_context` لا يفرض من هذا القسم طريقة حساب KPI جديدة؛ حساب المؤشرات وتقارير المقارنة يبقى في Reports / Analytics وSettings عند الحاجة.

---

## 9. مؤشرات النمو مشتقة من سجل الأوزان

### `growth_sorting.derived_growth_metric_model`

القرار:

```text
derive_live_from_weight_history
```

مثل:

```text
Gain Since Previous Weight
Gain Since Weaning
Average Daily Gain
Growth Rate
```

تُحسب من Weight History عند الاستخدام ولا تدخل يدويًا ولا تخزن كنسخة مستقلة داخل Evaluation.

المصدر الحقيقي:

```text
Weight Measurements in 4.3
→ Derived Growth Metrics
→ displayed / used during evaluation
```

الميزة المعمارية هنا هي منع وجود مصدرين مختلفين لنفس معدل النمو.

لكن هذا يعني أيضًا أن أي تصحيح مشروع في Weight History قد يؤدي إلى اختلاف القيمة المعاد حسابها لاحقًا عمّا ظهر تاريخيًا وقت تقييم قديم؛ القرار الحالي قبل هذا الأثر ولم يعتمد Snapshot تاريخية للمؤشرات.

---

## 10. نتائج الفرز مبدئية وليست تنفيذًا للمصير

### `growth_sorting.preliminary_result_categories`

النتائج المدعومة:

```text
continue_growth_monitoring
preliminary_replacement_candidate
preliminary_fattening
preliminary_exclusion
reevaluation
```

المعنى:

### الاستمرار

```text
continue_growth_monitoring
→ الحيوان يستمر في المتابعة / المرحلة التالية
```

### مرشح إحلال مبدئي

```text
preliminary_replacement_candidate
≠ Production Herd Approval
```

المرجع الوظيفي واضح أن الترشيح لا يعني الانضمام الفوري للقطيع؛ الاعتماد الفعلي له مسار لاحق.

### تسمين مبدئي

```text
preliminary_fattening
≠ Fattening Start Event
```

التحويل الفعلي إلى التسمين لا يحدث داخل 4.9.

### استبعاد مبدئي

```text
preliminary_exclusion
≠ Final Exit
```

وقد يعني لاحقًا استبعادًا من مسار الإحلال ثم الانتقال إلى التسمين.

### إعادة تقييم

```text
reevaluation
→ لا نجبر المسؤول على قرار مصير نهائي الآن
```

إذن 4.9 يسجل **توصية / اتجاهًا مبدئيًا**، بينما 4.10 هو صاحب قرار المصير المعتمد.

---

## 11. الربط مع قرار المصير 4.10

### `growth_sorting.result_to_fate_link_model`

القرار:

```text
create_pending_fate_decision
```

أي أن نتيجة الفرز المبدئية التي تحتاج حسم مصير تنشئ `Pending Fate Decision` لكي يتم اعتماد القرار صراحة في 4.10.

الصورة العامة:

```text
Growth Evaluation
→ Preliminary Direction
→ Pending Fate Decision
→ Explicit approval / decision in 4.10
```

وهذا متوافق مع 4.10 الذي يحفظ `source_reference` اختياريًا إلى التقييم الذي أدى للقرار، ويحافظ على تاريخ قرارات المصير كسجلات مستقلة.

### استثناء تفسيري ضروري: `reevaluation`

لا يجوز تطبيق `create_pending_fate_decision` حرفيًا على نتيجة:

```text
reevaluation
```

لأن السؤال المتخصص التالي اعتمد مسارًا واضحًا مختلفًا:

```text
create_linked_reevaluation_request_then_new_event
```

إذن التفسير المتناسق:

```text
preliminary replacement / fattening / exclusion / fate-oriented continuation
→ Pending Fate Decision when a fate decision is required

reevaluation
→ Reevaluation Request
→ later New Evaluation Event
```

ولا نحتاج لإنشاء `Pending Fate Decision` لمجرد أن النتيجة الحالية هي «إعادة تقييم» ما دام القرار النهائي مؤجلًا أصلًا.

هذه نقطة تفسير مهمة لأن سؤال الربط مع 4.10 صيغ بصورة عامة، بينما سؤال إعادة التقييم أكثر تخصصًا.

---

## 12. نموذج إعادة التقييم

### `growth_sorting.reevaluation_model`

القرار:

```text
create_linked_reevaluation_request_then_new_event
```

عند عدم حسم الاتجاه:

```text
Current Evaluation
→ Reevaluation Request
→ Wait / Monitor
→ New Independent Evaluation Event
```

لا يتم:

```text
reopen old evaluation
أو
overwrite old result
```

وهذا متسق مع قرار حفظ كل تقييم كسجل مستقل.

### `growth_sorting.reevaluation_record_fields`

طلب إعادة التقييم يحتفظ بـ:

```text
source_evaluation
reason
planned_review_at
monitoring_requirements
requested_by
notes
```

الغرض هو توثيق:

- لماذا لم يحسم القرار.
- متى يُقترح الرجوع للحالة.
- ما البيانات المطلوب متابعتها قبل المراجعة التالية.
- من طلب إعادة التقييم.

لكن:

```text
planned_review_at
≠ automatically executed evaluation
```

إنشاء المهمة وتوقيت التنبيه ومتابعة التأخير يبقى ضمن Settings / 4.17.

---

## 13. التوافق مع التصور الوظيفي

القرارات الحالية متوافقة مع جوهر المرجع في عدة نقاط:

1. الفرز عملية **مرحلية** وليست قرارًا واحدًا نهائيًا بعد الفطام.
2. مراحل 45/70 يومًا/3 أشهر أمثلة وليست Hardcoded.
3. التقييم يستفيد من الوزن والنمو والصحة والنسب وأداء الأسرة عند توفره.
4. تغير نتيجة الحيوان في مرحلة لاحقة لا يمحو المرحلة السابقة.
5. يسمح بتأجيل القرار وإعادة التقييم.
6. الترشيح للإحلال لا يعني اعتماد الحيوان إنتاجيًا.
7. توصية التسمين أو الاستبعاد في 4.9 لا تنفذ المسار فعليًا قبل قرار المصير.
8. استمرار نفس `Animal Record` عبر النمو والفرز والتسمين أو الإحلال قاعدة ثابتة.

---

## 14. النقاط المفتوحة / التعارضات

### 14.1 تعارض مباشر: بدء مسار النمو 4.8 ↔ 4.9

```text
4.8:
explicit_growth_start_after_weaning

4.9:
auto_start_after_successful_weaning
```

يجب حسم هل:

- نجاح الفطام يبدأ Growth Tracking تلقائيًا، أو
- توجد Transition مستقلة مسجلة بعد الفطام.

لا يمكن اعتبار النموذجين نهائيين معًا لنفس الانتقال.

### 14.2 `growth_metrics_context` مقابل Live Derivation

السؤال العام للحقول اختار `growth_metrics_context` بصياغة تسمح «مرجع / لقطة»، لكن القرار المتخصص حسم عدم تخزين المؤشرات المشتقة.

الحل التفسيري الحالي:

```text
keep references/context only
no stored derived metric snapshot
```

ولا يعتبر هذا تغييرًا للإجابة.

### 14.3 نتيجة `reevaluation` مقابل Pending Fate Decision

السؤال العام يربط نتيجة الفرز بـPending Fate Decision، بينما `reevaluation` لها Flow متخصص مستقل.

لذلك:

```text
reevaluation
→ Reevaluation Request
→ New Evaluation
```

ولا تُعامل كقرار مصير جاهز للاعتماد.

### 14.4 Settings 6.9 لم تُحسم بعد

لا يزال غير محسوم من هذا القسم:

- عدد مراحل الفرز.
- أسماء المراحل.
- الأعمار المستهدفة.
- الأوزان المطلوبة / المستهدفة.
- حدود انخفاض النمو.
- عوامل التقييم المطلوبة لكل مرحلة.
- تأثير الجنس / الصحة / النسب / أداء الوالدين.
- طريقة تقييم التكوين والصفات الظاهرية.
- توقيت إعادة التقييم.

هذه ليست فجوات في Workflow نفسه؛ هي Rules قابلة للضبط موجودة عمدًا في Settings 6.9.

---

## 15. حدود الكيانات وعدم الاستنتاج

### `GrowthEvaluation`

يمثل واقعة تقييم فعلية.

لا يمثل:

- تعريف مرحلة الفرز.
- قاعدة الوزن المستهدف.
- قرار المصير النهائي.
- بداية التسمين الفعلية.
- اعتماد الحيوان داخل القطيع.

### `GrowthReevaluationRequest`

يمثل طلب / سبب / موعد مقترح لمراجعة لاحقة.

لا يمثل Evaluation جديدة قبل تنفيذها.

### `FateDecision`

ينتمي إلى 4.10.

### `WeightMeasurement`

المصدر القياسي للوزن في 4.3.

### `SortingStage`

تعريفه وقواعده في Settings 6.9.

---

## 16. Requirements الناتجة من القسم

1. **Individual Growth Tracking**  
   النظام يحتاج مسار نمو/فرز مرتبط بكل Animal Record بعد الفطام، مع بقاء طريقة بدء المسار معلقة بسبب تعارض 4.8/4.9.

2. **Configured Sorting Stage Reference**  
   كل Evaluation ترتبط بمرحلة معرفة في Settings بدل Hardcoded Ages.

3. **Immutable Evaluation History**  
   كل تقييم وإعادة تقييم سجل مستقل ويمكن ربطه بالسابق.

4. **Canonical Weight Integration**  
   الأوزان ومؤشرات النمو تعتمد على 4.3 ولا تنشئ مصدر أرقام مستقل داخل الفرز.

5. **Rich Evaluation Context**  
   التقييم يستطيع الرجوع للوزن والنمو والجنس والصحة والنسب وأداء الأسرة والتقييمات السابقة عند توفر البيانات.

6. **Live Derived Growth Metrics**  
   مؤشرات النمو تحسب من Weight History عند الاستخدام ولا تخزن كقيم يدوية مستقلة.

7. **Preliminary Outcome Layer**  
   نتائج 4.9 تظل توصيات / اتجاهات مبدئية ولا تنفذ Fate نهائيًا.

8. **Pending Fate Handoff**  
   الاتجاهات التي تحتاج قرار مصير تنقل إلى Pending Fate Decision في 4.10.

9. **Dedicated Reevaluation Flow**  
   `reevaluation` تنشئ طلب إعادة تقييم ثم Evaluation جديدة مستقلة لاحقًا.

10. **No Hardcoded Sorting Ages**  
    أمثلة 45/70 يومًا/3 أشهر لا تصبح Requirements حتى اعتماد Settings.

11. **Separation of Workflow and Rules**  
    ما حدث فعليًا في 4.9؛ متى ولماذا وكيف يجب أن يحدث تحدده 6.9.

12. **Animal Record Continuity**  
    الفرز والتقييم وتغير الاتجاه لا ينشئ Animal جديدًا ولا ينهي السجل الحالي.

---

## 17. حالة المراجعة النهائية

```text
Current Answer Coverage: 10 / 10
Applicable: 10 / 10
Unanswered: 0
Open Reviews: 0
Internal Blocking Conflict: لا يوجد داخل 4.9 نفسه بعد تطبيق أولوية السؤال المتخصص في تفسير مؤشرات النمو وإعادة التقييم
Cross-section Blocking Conflict: نعم — بدء مسار النمو بين 4.8 و4.9
Settings Dependency: 6.9 غير مجاب عنها حاليًا
```

القسم صالح للانتقال إلى القسم التالي، مع إبقاء تعارض بداية مسار النمو مسجلًا للحسم لاحقًا.