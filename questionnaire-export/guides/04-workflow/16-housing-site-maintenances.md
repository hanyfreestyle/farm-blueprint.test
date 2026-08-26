# 4.16 تشغيل وصيانة وتجهيز مواقع الإيواء — دليل تفسير الإجابات

> **الحالة:** مسودة أولى مبنية على الإجابات الحالية للمراجعة — القسم مكتمل 9/9 ومتماسك داخليًا في جوهره، مع نقطة تكامل مفتوحة تخص `explicit_readiness_confirmation_action` مقابل Actions المعتمدة للقفص، ونقطة تحتاج ضبطًا عند ربط Child Actions بقواعد انتقال عدم الإتاحة من الموقع الأب في Settings 6.3  
> **ملف الإجابات المقابل:** `questionnaire-export/answers/04-workflow/16-housing-site-maintenances.md`  
> **Question Keys المغطاة:** 9 / 9

---

## 1. الغرض من هذا الملف

هذا الملف يفسر **المعنى الوظيفي والتقني للإجابات المعتمدة فعليًا** في قسم `تشغيل وصيانة وتجهيز مواقع الإيواء` ضمن `الحركات ودورة التشغيل الفعلية`.

هذا القسم لا يعيد تعريف Farm / Barn / Battery / Cage، ولا يحدد من نفسه قواعد السعة أو إلزام التطهير أو فترات الانتظار، ولا ينشئ إشغالًا أو حركة حيوان بديلة عن 4.2.

```text
answers/04-workflow/16-housing-site-maintenances.md
= ماذا قررنا؟

                    ↓ تفسير

guides/04-workflow/16-housing-site-maintenances.md
= ماذا تعني القرارات تقنيًا؟ وما حدودها ونقاط التكامل؟
```

الحد المعماري الأساسي:

```text
Farm / Barn / Battery / Cage Identity & Local Structural State
→ Farm Structure

Actual Housing / Transfer / Vacate
→ 4.2

Actual Maintenance / Sanitation / Return-to-Service Actions
→ 4.16

Rules that decide:
- when sanitation is required
- waiting periods
- parent availability effect
- reuse requirements
- effective availability
→ Settings 6.3

Housing Eligibility for an actual new housing operation
→ Derived from canonical state + history + settings
```

ويجب الحفاظ على الفصل:

```text
Empty
≠ Ready for Reuse
≠ Locally Active
≠ Effectively Available for Housing
```

---

## 2. الهدف الوظيفي من القسم

الغرض من 4.16 هو تسجيل **ما حدث فعليًا لموقع الإيواء تشغيليًا** عندما يدخل الصيانة أو ينتهي منها، أو يحتاج إلى تنظيف / تطهير، أو يعود للخدمة.

الصورة المفاهيمية العامة:

```text
Housing Site
→ operational action happens
→ historical event is recorded
→ effective availability changes according to event + Settings
→ site later returns to service only through the adopted lifecycle
```

القسم يجب أن يسمح بفهم تاريخ الموقع، لا بمجرد رؤية حالته الحالية.

مثلًا:

```text
Active Cage
→ Vacated in 4.2
→ Sanitation Required
→ Sanitation Completed
→ Readiness Confirmed
→ Returned to Service / Eligible according to rules
```

أو:

```text
Occupied Battery
→ Animals Vacated / Relocated in 4.2
→ Maintenance Start
→ Maintenance Completion
→ Sanitation Required if policy says so
→ Preparation Completed
→ Return to Service
```

الترتيب الدقيق لبعض الخطوات يعتمد على Settings الفعالة وعلى نقطة التكامل المفتوحة بين Readiness Confirmation وReturn to Service الموضحة لاحقًا.

---

## 3. ملخص القرارات المعتمدة

الإجابات الحالية تحسم النموذج التالي:

```text
Maintenance Target Scopes
├── Cage
├── Battery
└── Barn

Maintenance Storage Model
└── separate records per site type

Maintenance Lifecycle
└── linked independent Start + Completion events

Occupied Site Maintenance
└── actual animals must be vacated / transferred in 4.2
    BEFORE maintenance is considered started

Post-Occupancy Sanitation
└── if active policy requires sanitation:
    auto-create Sanitation Requirement when occupancy cycle ends

Parent → Child Operational Effect History
└── independent Action per affected child site

After Maintenance Completion
└── if sanitation is required:
    site automatically transitions to Sanitation Required
    and cannot return to housing yet

Return to Service
└── explicit Return-to-Service Action after requirements are met

Readiness after Sanitation
└── explicit Readiness Confirmation Action
```

---

## 4. تفسير Question Keys والإجابات

### 4.1 `housing_site_maintenance.target_scopes`

تم اعتماد:

```text
cage
battery
barn
```

إذن الصيانة الفعلية ليست مقصورة على القفص فقط.

النظام يجب أن يستطيع توثيق أن العملية حدثت على:

- قفص بعينه.
- بطارية كاملة.
- عنبر كامل.

لكن هذا لا يعني أن `Farm` هدف صيانة ضمن هذا القسم؛ لم يتم اختياره.

كما لا يعني أن تشغيل الصيانة يعيد تعريف الكيان أو يغير هويته الهيكلية.

```text
Maintenance Event
≠ Structural Identity Change
```

---

### 4.2 `housing_site_maintenance.record_model`

القرار:

```text
separate_records_per_site_type
```

أي لا يتم اعتماد سجل Maintenance موحد Polymorphic لجميع المستويات.

المعنى المعماري الحالي:

```text
Barn Maintenance History
Battery Maintenance History
Cage Maintenance History
```

تظل سجلات / نماذج الصيانة **منفصلة حسب نوع الموقع**.

لكن يجب عدم استنتاج أسماء Tables أو Models تقنية بعينها من هذه الإجابة وحدها.

كما يجب، رغم الفصل، الحفاظ على معنى تشغيلي موحد قدر الإمكان:

```text
what happened
when it happened
what site was affected
who performed / recorded it
what previous / next operation it belongs to
```

تفاصيل Common Fields النهائية لم تحسم كسؤال مستقل في 4.16، لذلك لا يجوز اختراع Schema نهائي من هذا القرار فقط.

---

### 4.3 `housing_site_maintenance.lifecycle_model`

القرار:

```text
linked_start_and_completion_events
```

إذن الصيانة ليست مجرد Record واحد يتم تعديل `ended_at` داخله حتى يبدو كأن حالته الحالية هي الحقيقة الوحيدة.

المسار التاريخي:

```text
Maintenance Start Event
        ↓ linked
Maintenance Completion Event
```

المطلوب وظيفيًا:

- الاحتفاظ بوقت بدء الصيانة.
- الاحتفاظ بوقت اكتمالها كواقعة مستقلة.
- ربط الحدثين ببعضهما.
- عدم حذف أو استبدال حدث البداية عند الإكمال.
- إمكانية معرفة مدة بقاء الموقع تحت الصيانة من التاريخ.

هذا يتوافق مع القاعدة العامة للمشروع التي تجعل Current State ناتجًا من الأحداث، لا بديلًا عنها.

---

### 4.4 `housing_site_maintenance.occupied_target_integration`

القرار:

```text
vacate_before_maintenance_start
```

إذا كان الموقع المراد صيانته يحتوي حيوانات، فلا تعتبر الصيانة قد بدأت فعليًا قبل إتمام الإخلاء / النقل في 4.2.

الصورة:

```text
Maintenance Intended / Requested
        ↓
Occupied animals detected
        ↓
Actual Housing Vacate / Transfer in 4.2
        ↓
Target becomes empty as required
        ↓
Maintenance Start Event in 4.16
```

إذن:

```text
Maintenance Start
≠ decision to maintain
```

إذا احتاج التنفيذ لاحقًا مفهوم `Maintenance Request` أو `Planned Maintenance` قبل الإخلاء، فهذا غير محسوم من هذا السؤال؛ القرار الحالي يحسم فقط **متى تبدأ الصيانة الفعلية**.

#### التكامل مع 4.2

4.2 هو مصدر الحقيقة للحركات والإشغال.

لا يجوز لـ4.16 أن يفرغ القفص أو البطارية منطقيًا من خلال تغيير Status فقط.

```text
Actual animal relocation
→ 4.2

Maintenance event
→ 4.16
```

وبالنسبة لإخلاء Battery أو Barn مشغول، يجب استخدام آلية النقل / الإخلاء المتخصصة التي يتم اعتمادها في 4.2 دون إنشاء Movement History بديلة داخل 4.16.

---

### 4.5 `housing_site_sanitation.post_vacate_trigger_model`

القرار:

```text
auto_create_sanitation_requirement
```

عند انتهاء دورة إشغال القفص، **إذا كانت السياسة الفعالة في Settings تشترط التطهير**، ينشئ النظام تلقائيًا `Sanitation Requirement`.

المهم هنا:

```text
Vacate happened
+
Applicable sanitation rule = required
→ Sanitation Requirement created automatically
```

ولا يعني القرار:

```text
Every vacate always requires sanitation
```

لأن **إلزام التطهير نفسه** ما زال من Settings 6.3.

كما أن إنشاء Requirement لا يعني أن عملية التطهير تم تنفيذها أو بدأت فعليًا.

```text
Sanitation Required
≠ Sanitation Started
≠ Sanitation Completed
```

هذا متوافق مع المرجع الوظيفي الذي يسمح بأن ينتقل القفص بعد الإخلاء إلى:

```text
بانتظار تنظيف / تعقيم
→ جاهز لإعادة الاستخدام لاحقًا
```

بدل أن يصبح متاحًا مباشرة.

---

### 4.6 `housing_site_operation.parent_child_history_model`

القرار:

```text
independent_child_actions
```

عند تنفيذ إجراء تشغيلي على Parent Site مثل Battery أو Barn، يتم إنشاء **Action مستقل لكل موقع تابع متأثر**.

مثلًا، إذا أثرت عملية على Battery كاملة:

```text
Battery Operation
→ Child Cage Action A
→ Child Cage Action B
→ Child Cage Action C
...
```

وهذا يعني أن أثر العملية على كل Child ليس مجرد View مشتقة في التاريخ الحالي، بل هناك Action مسجل لكل موقع متأثر.

#### الربط المطلوب تاريخيًا

يجب ألا تظهر Child Actions وكأنها عمليات بشرية مستقلة لا علاقة بينها.

بما أن نموذج القفص المعتمد يدعم:

```text
parent_operation_reference
```

فالتفسير المتسق هو أن Child Action يمكن أن يحتفظ بمرجع للعملية الأب التي أنشأته، مع بقائه Action تاريخيًا للموقع نفسه.

لكن هذه الإجابة لا تحسم شكل الربط لبقية المستويات تقنيًا.

#### نقطة تحتاج ضبطًا مع Settings 6.3

Settings 6.3 ما زالت غير مجاب عنها، وبها سؤال مستقل عن كيفية تأثير عدم إتاحة Parent على Descendants.

لذلك يجب لاحقًا منع وجود **مصدرين متنافسين** للأثر:

```text
Parent unavailable → derived child unavailability
```

وفي الوقت نفسه:

```text
Independent child status/action transitions
```

إذا تم اعتماد الاثنين دون Boundary واضح، قد يصبح Child غير متاح مرتين منطقيًا أو تختلف نهاية أثر Parent عن حالة Child المحلية.

إذن عند الإجابة على Settings 6.3 يجب تحديد هل:

- Child Actions هي نفسها التي تمثل الأثر التشغيلي المحلي؛ أو
- Parent availability يظل عاملًا مشتقًا إضافيًا مستقلًا عن Local Child Action؛ أو
- بعض العمليات تحتاج Child Actions وبعضها يكتفي بالتأثير المشتق.

هذا الملف لا يحسم الاختيار من نفسه.

---

### 4.7 `housing_site_maintenance.post_completion_sanitation_model`

القرار:

```text
transition_to_sanitation_required
```

إذا اكتملت الصيانة وكانت Settings الفعالة تشترط التطهير بعدها:

```text
Maintenance Completion
→ Sanitation Required
→ Site is NOT ready for new housing yet
```

الصيانة المكتملة لا تجعل الموقع متاحًا تلقائيًا.

وهذه قاعدة مهمة:

```text
Maintenance Complete
≠ Ready for Housing
```

إذا كانت السياسة لا تشترط التطهير بعد هذه الصيانة، فلا يجوز إنشاء Sanitation Requirement من هذه الإجابة وحدها.

إلزام التطهير ما زال في Settings 6.3.

---

### 4.8 `housing_site_operation.return_to_service_model`

القرار:

```text
explicit_return_to_service_action
```

الموقع لا يعود للخدمة تلقائيًا لمجرد انتهاء الصيانة أو اكتمال تجهيزاته.

المسار:

```text
Required maintenance / preparation complete
        ↓
Prerequisites validated
        ↓
Explicit Return to Service Action
```

هذا يتوافق مع نموذج القفص الحالي الذي يحتوي Action باسم:

```text
return_to_service
```

ولا يجوز تفسير `complete_maintenance` على أنه يساوي `return_to_service`.

```text
Complete Maintenance
≠ Return to Service
```

وقد توجد بينهما متطلبات مثل:

- التطهير.
- فترة انتظار.
- تحقق / اعتماد جاهزية.
- إتاحة Parent Site.

القواعد الفعلية تأتي من Settings 6.3.

---

### 4.9 `housing_site_sanitation.readiness_after_completion_model`

القرار:

```text
explicit_readiness_confirmation_action
```

إتمام التطهير وحده لا يكفي لإعلان الموقع جاهزًا.

المسار المفاهيمي:

```text
Sanitation Completed
→ verify applicable requirements
→ Explicit Readiness Confirmation
```

إذن:

```text
Sanitation Completed
≠ Readiness Confirmed
```

وهذا يسمح بأن توجد فترة انتظار أو Requirement آخر قبل التأكيد.

لكن يجب أيضًا قراءة هذا القرار مع سؤال العودة للخدمة السابق.

---

## 5. الفصل بين Readiness Confirmation وReturn to Service

الإجابتان الحاليتان تفرضان Action صريحًا في نقطتين:

```text
After sanitation
→ Explicit Readiness Confirmation

After maintenance / preparation requirements
→ Explicit Return to Service
```

يمكن تفسيرهما مفاهيميًا كطبقتين مختلفتين لأن المشروع يفصل أصلًا بين:

```text
Local Operational State
≠ Effective Housing Readiness / Eligibility
```

بالتالي يمكن أن يكون المعنى:

```text
Readiness Confirmation
= إثبات أن متطلبات التجهيز / التطهير / الانتظار استوفيت

Return to Service
= تغيير Lifecycle / Local Operational State للموقع إلى الخدمة
```

لكن **الإجابات لا تحسم ترتيب الاثنين بصورة صريحة في كل سيناريو**، ولا تحسم هل كلاهما مطلوبان دائمًا بعد التطهير عندما يكون الموقع أصلًا غير موجود في Status صيانة.

### نقطة التكامل مع Cage Actions

قسم القفص 2.4 اعتمد Actions التالية:

```text
activate
stop
return_to_service
start_maintenance
complete_maintenance
retire
start_sanitation
complete_sanitation
```

ولا توجد حاليًا Action باسم مستقل مثل:

```text
confirm_readiness
```

لذلك `explicit_readiness_confirmation_action` يخلق **Requirement Gap عابر بين 4.16 و2.4**:

- إما أن Readiness Confirmation تكون Action / Record في كيان `housing_site_readiness` مستقل عن Cage Action History.
- أو يجب لاحقًا توسيع Actions القفص / المواقع لتضم هذا النوع.
- أو يتم حسم أن `return_to_service` نفسها تؤدي وظيفة التأكيد، لكن هذا سيحتاج تعديل القرار أو تفسير معتمد صريح لأن السؤال الحالي قال `Action مستقل` لتأكيد الجاهزية.

لا يحسم هذا الـGuide أيًا من الخيارات الثلاثة.

---

## 6. العلاقة مع Farm Structure

4.16 لا يغير الهوية الهيكلية للموقع.

```text
Barn / Battery / Cage
→ Existing structural record

Maintenance / Sanitation
→ Historical operational actions against that record
```

### القفص

النموذج الحالي للقفص يميز بين:

```text
Local Status
Occupancy
Sanitation
Maintenance
Housing Eligibility
```

وهذا متوافق جدًا مع 4.16.

مثلًا:

```text
Cage empty
but sanitation required
→ not Housing Eligible
```

أو:

```text
Cage local status = active
but parent Battery unavailable
→ not effectively available
```

### البطارية والعنبر

4.16 يثبت أن كليهما يمكن أن يكون هدف صيانة مستقلًا.

ولا يجوز عند صيانة Parent:

- تغيير هوية الأبناء.
- حذف تاريخهم.
- اعتبارهم متقاعدين لمجرد أن Parent تحت الصيانة.

أي تأثير تشغيلي يجب أن يكون قابلًا للتتبع والعودة عند انتهاء السبب.

---

## 7. العلاقة مع 4.2 — الإشغال والحركات

القاعدة الأساسية:

```text
Site Operation does not move animals by itself
```

إذا احتاجت الصيانة إلى إخلاء موقع:

```text
Actual Vacate / Transfer
→ 4.2

Maintenance Start
→ 4.16 only after required vacating is complete
```

هذا مهم لأن Current Occupancy مشتق من Housing Movement History.

لا يجوز تحقيق الإخلاء عن طريق:

```text
site.status = maintenance
```

مع ترك Occupancy History تقول إن الحيوانات ما زالت موجودة.

كذلك إذا أصبح Cage فارغًا بعد حركة فعلية، فهذا هو Trigger الذي يمكن أن تنطبق عليه قاعدة Post-Vacate Sanitation.

---

## 8. العلاقة مع Settings 6.3

قسم 4.16 يحدد **الأحداث الفعلية وكيف تتكامل**.

أما Settings 6.3 فتحدد القواعد التي لم تحسم بعد، ومنها:

- مكونات الإتاحة التشغيلية الفعلية.
- أثر عدم إتاحة Parent على Descendants.
- السعة التشغيلية.
- متى يكون التطهير إلزاميًا.
- ما Triggers التطهير.
- هل يلزم تطهير بعد الصيانة.
- فترات الانتظار.
- نقطة بدء فترة الانتظار.
- متطلبات إعادة الاستخدام.
- أثر تغيير الاستخدام على التجهيز.
- اختلاف القواعد حسب المستوى / الاستخدام / النوع.
- كيف تنتج قيمة Effective Availability.

الحالة الحالية لـSettings 6.3:

```text
13 total
13 applicable
0 answered
```

لذلك لا يجوز من 4.16 وحده افتراض:

```text
sanitation always required
waiting period always exists
parent always blocks every descendant
site becomes automatically available after X hours
```

كل هذه ما زالت قرارات Settings.

---

## 9. العلاقة مع Tasks / 4.17

4.16 يحدد العملية التشغيلية نفسها، بينما 4.17 يدير تنفيذ المهام عند استخدامها.

مثلًا:

```text
Sanitation Requirement
≠ Sanitation Task
≠ Sanitation Execution Event
```

وقد تؤدي Settings إلى توليد Task، لكن لا يجب اعتبار وجود Task دليلًا على تنفيذ التطهير.

وبالمثل:

```text
Maintenance planned task
≠ Maintenance Start Event
```

خصوصًا أن القرار الحالي يشترط إخلاء الموقع قبل بدء الصيانة الفعلية.

---

## 10. نقاط مفتوحة / تعارضات تحتاج حسمًا لاحقًا

### 10.1 Readiness Confirmation Action غير ممثلة حاليًا ضمن Cage Supported Actions

4.16 يعتمد:

```text
explicit_readiness_confirmation_action
```

بينما 2.4 لا يحتوي حاليًا على `confirm_readiness` ضمن Cage Actions المعتمدة.

هذه **فجوة تكامل حقيقية** تحتاج تصميمًا أو قرارًا لاحقًا قبل التنفيذ النهائي.

---

### 10.2 Readiness Confirmation مقابل Return to Service

يوجد Action صريح لكل منهما، لكن حدود الترتيب والضرورة ليست محسومة بالكامل.

يجب لاحقًا تحديد State / Lifecycle واضح يمنع:

- تكرار نفس الاعتماد مرتين بمسميين مختلفين.
- أن يكون الموقع `Ready` لكنه لم يعد `In Service` دون معنى واضح.
- أو أن يعود للخدمة قبل استيفاء Readiness Confirmation المطلوبة.

يمكن إبقاء المفهومين منفصلين، لكن التنفيذ النهائي يحتاج Boundary صريحة.

---

### 10.3 Parent Operation + Independent Child Actions مقابل Parent Availability Propagation

4.16 يطلب Child Actions مستقلة.

Settings 6.3 لم تحسم بعد هل عدم إتاحة Parent تنتقل تلقائيًا إلى Descendants.

عند الإجابة على 6.3 يجب منع ازدواجية المصدر أو اختلاف Timeline الطفل عن Effective Availability المشتقة من الأب.

---

### 10.4 حقول سجل الصيانة على Barn / Battery

4.16 يحسم:

- نوع المواقع.
- الفصل بين السجلات.
- Start / Completion Events.

لكنه لا يحتوي Question Key مستقلًا يحدد Common Record Fields لكل Barn / Battery Maintenance Event.

القفص لديه Action Audit Fields معتمدة في 2.4، لكن لا يجوز تعميمها تلقائيًا على Barn / Battery دون قرار أو Requirement واضح.

إذن هذه **Requirement Gap منخفضة المستوى** يجب ملاحظتها عند تحويل الـGuides إلى Blueprint تنفيذي.

---

## 11. ما لا يجب استنتاجه من الإجابات

لا يجوز استنتاج أي من التالي:

- أن كل إخلاء يتطلب تطهيرًا.
- مدة تطهير أو انتظار محددة.
- نوع مواد أو بروتوكول التعقيم.
- أن الصيانة على Parent تغير هوية أو تكوين الأبناء.
- أن Status `active` وحده يعني أن الموقع صالح للتسكين.
- أن اكتمال الصيانة يعني الجاهزية.
- أن اكتمال التطهير يعني الجاهزية.
- أن الجاهزية تعني تلقائيًا أن حيوانًا معينًا مؤهل لهذا الموقع.
- أن Farm نفسها هدف صيانة في هذا القسم.
- شكل Tables أو Models النهائي لمجرد اختيار سجلات منفصلة حسب نوع الموقع.

---

## 12. المخرجات المطلوبة للـRequirements

1. دعم Maintenance Workflow فعلي على Cage / Battery / Barn.
2. استخدام سجلات صيانة منفصلة حسب نوع الموقع.
3. تمثيل الصيانة كـStart Event وCompletion Event مرتبطين تاريخيًا.
4. منع بدء الصيانة الفعلية على موقع مشغول قبل إتمام الإخلاء / النقل Canonically في 4.2.
5. إنشاء Sanitation Requirement تلقائيًا بعد انتهاء دورة الإشغال عندما تقرر Settings أن التطهير مطلوب.
6. دعم Child Action مستقل لكل موقع تابع متأثر بعملية Parent، مع ضرورة الحفاظ على مرجع العملية الأب وعدم اختراع أحداث غير مترابطة.
7. عند اشتراط التطهير بعد الصيانة، انتقال الموقع إلى `Sanitation Required` وعدم اعتباره جاهزًا للتسكين.
8. وجود Explicit Return-to-Service Action بعد تحقق المتطلبات.
9. وجود Explicit Readiness Confirmation بعد اكتمال التطهير وفق القرار الحالي.
10. الحفاظ على الفصل بين Local State وReadiness وEffective Housing Availability.
11. اشتقاق Occupancy من 4.2 وعدم تغيير موقع الحيوانات من 4.16.
12. ترك قواعد إلزام التطهير والانتظار والإتاحة والسعة وأثر Parent إلى Settings 6.3.
13. حسم Integration Gap الخاص بـReadiness Confirmation قبل التنفيذ النهائي.
14. حسم Boundary بين Child Actions وParent Availability Propagation عند الإجابة على Settings 6.3.
15. استكمال Common Maintenance Event Fields لـBarn / Battery إذا احتاج الـBlueprint التنفيذي Schema صريحًا.

---

## 13. المراجعة النهائية

### مكتمل ومتسق حاليًا

- Scopes الصيانة.
- الفصل بين سجلات المواقع.
- Start / Completion Lifecycle.
- إخلاء الحيوانات قبل Maintenance Start.
- Post-Vacate Sanitation Requirement Trigger عند انطباق السياسة.
- Post-Maintenance Sanitation Boundary.
- Explicit Return to Service.
- الحفاظ على الفصل بين الصيانة والإشغال.

### يحتاج حسمًا لاحقًا

1. تمثيل `explicit_readiness_confirmation_action` تقنيًا وعلاقته بقائمة Actions الحالية للقفص.
2. الفرق التنفيذي النهائي وترتيب `Readiness Confirmation` و`Return to Service`.
3. كيفية الجمع بين `independent_child_actions` وقاعدة Parent→Descendant Availability التي ستعتمد في Settings 6.3.
4. Common Fields لسجلات صيانة Barn / Battery.

**النتيجة:** لا يوجد تعارض داخلي يمنع التقدم إلى القسم التالي، لكن توجد فجوات تكامل يجب الاحتفاظ بها صراحة قبل إنتاج الـBlueprint التنفيذي النهائي.