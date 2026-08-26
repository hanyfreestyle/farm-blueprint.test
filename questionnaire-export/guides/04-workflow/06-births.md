# 4.6 تسجيل الولادة وإنشاء البطن — دليل تفسير الإجابات

> **الحالة:** مسودة أولى مبنية على الإجابات الحالية للمراجعة — القسم متماسك داخليًا، مع نقطة ترابط مفتوحة مع 4.4/4.5 تخص مرجع الأبوة عند تعدد الذكور  
> **ملف الإجابات المقابل:** `questionnaire-export/answers/04-workflow/06-births.md`  
> **Question Keys المغطاة:** 11 / 11

---

## 1. الغرض من هذا الملف

هذا الملف يفسر **المعنى الوظيفي والتقني للإجابات المعتمدة فعليًا** في قسم `تسجيل الولادة وإنشاء البطن` ضمن `الحركات ودورة التشغيل الفعلية`.

هو ليس مصدرًا بديلًا للإجابات، ولا يغير أي قرار موجود في ملف الإجابات.

```text
answers/04-workflow/06-births.md
= ماذا قررنا؟

                    ↓ تفسير

guides/04-workflow/06-births.md
= ماذا تعني هذه القرارات تقنيًا؟ وما حدودها؟
```

القاعدة المعمارية الأساسية:

```text
Pregnancy / Reproductive Cycle
→ 4.5 / 4.4

Actual Birth Event
→ 4.6

Litter Record
→ 4.6

Lactation / Foster / Preweaning Follow-up
→ 4.7

Mortality after birth
→ 4.13

Weaning / Individual Animal Creation
→ 4.8
```

---

## 2. الهدف الوظيفي من القسم

هذا القسم يحسم **ما حدث عند الولادة فعلًا**، ثم كيفية إنشاء سجل البطن الناتج عنها.

المسار المفاهيمي الحالي:

```text
Confirmed Pregnancy / Reproductive Cycle
        ↓
Actual Birth Event
        ↓
Record actual birth counts and historical location
        ↓
Close Pregnancy as ended by birth
        ↓
Record cycle birth outcome
        ↓
Explicit Litter Creation Step
        ↓
If live offspring exist → start Lactation
If no live offspring → create historical litter then close it
```

المبدأ الأساسي:

```text
Expected Birth
≠
Actual Birth

Birth Event
≠
Litter Record

Stillborn at Birth
≠
Mortality after Birth
```

---

## 3. ملخص القرارات المعتمدة

الصورة الحالية:

```text
Birth Event
├── mother
├── pregnancy
├── reproductive_cycle
├── paternity_reference
├── actual birth date
├── actual birth time
├── birth location
├── recorded_by
└── notes

Birth Counts
├── total_born
├── live_born
├── stillborn_at_birth
└── special conditions → notes only

Validation
└── total_born = live_born + stillborn_at_birth
    user enters all three and system validates

Birth outside expected window
└── allowed
    + actual date preserved
    + timing classification derived automatically

Historical Location
└── stored on Birth Event
    + validated against Occupancy History

Litter Creation
└── explicit step after Birth Event

Litter Code
└── automatic

No Live Offspring
└── create historical Litter
    + close immediately
    + no Lactation
    + no Weaning
```

---

## 4. تفسير Question Keys

### 4.1 `birth.event_record_fields`

سجل الولادة الفعلية يحتفظ بـ:

```text
mother
pregnancy
reproductive_cycle
paternity_reference
birth_date
birth_time
birth_location
recorded_by
notes
```

هذا يعني أن `Birth Event` هو **واقعة تاريخية مستقلة** مرتبطة بسياق الحمل والدورة، وليس مجرد تحديث لحالة الأنثى.

### دلالة مهمة

`birth_date` و`birth_time` يمثلان **وقت حدوث الولادة فعليًا**، وليس موعد تسجيلها أو موعد الولادة المتوقع.

لا يجوز تعديل تاريخ التلقيح أو الحمل من أجل جعل مدة الحمل تبدو طبيعية إذا كانت الولادة الفعلية خارج المدى المتوقع.

### `paternity_reference`

الحقل هو **مرجع الأبوة الناتج من مسار التلقيح والحمل**، وليس بالضرورة `male_id` مؤكدًا في جميع الحالات.

بسبب النقطة المفتوحة المسجلة في 4.4:

```text
Multiple males in same relevant mating period
→ paternity may be uncertain
```

لذلك لا يجوز من هذا القسم استنتاج:

```text
paternity_reference = always one confirmed male
```

وقد يحتاج التصميم النهائي إلى تمثيل حالة أبوة مؤكدة / غير مؤكدة حسب القرار النهائي في 4.4.

---

### 4.2 `birth.offspring_count_fields`

الولادة تسجل:

```text
total_born
live_born
stillborn_at_birth
special_conditions
```

الفصل الزمني مهم:

```text
Stillborn at Birth
→ جزء من نتيجة الولادة 4.6

Animal born alive then dies later
→ Mortality Event in 4.13
```

لا يجوز نقل نفوق حدث بعد الولادة إلى `stillborn_at_birth` لمجرد أنه حدث في نفس اليوم.

---

### 4.3 `birth.count_entry_model`

القرار:

```text
enter_all_and_validate_total
```

أي أن المستخدم يدخل القيم الثلاث:

```text
total_born
live_born
stillborn_at_birth
```

ويطبق النظام تحققًا إلزاميًا:

```text
total_born = live_born + stillborn_at_birth
```

إذن `total_born` ليس Derived فقط؛ هو **قيمة مدخلة تخضع للتحقق**.

إذا لم تتطابق القيم، يجب منع اعتماد التسجيل أو إظهار خطأ واضح بدل السماح ببيانات تاريخية متعارضة.

---

### 4.4 `birth.special_condition_representation`

القرار:

```text
notes_only
```

الحالات الخاصة عند الولادة، مثل عيب خلقي ظاهر، لا تنشئ حاليًا:

- فئة عدد مستقلة.
- Catalogue طبي مستقل.
- Structured subtype requirement.

إنما تحفظ كمعلومة وصفية / ملاحظة فقط.

لذلك حتى مع وجود `special_conditions` ضمن بيانات Birth/Litter، التفسير الحالي لها هو **بيانات وصفية غير منظمة عدديًا**.

لا تدخل هذه الحالات في معادلة:

```text
total_born = live_born + stillborn_at_birth
```

---

### 4.5 `birth.outside_expected_window_behavior`

القرار:

```text
allow_and_derive_timing_classification
```

الولادة الفعلية تسجل حتى لو حدثت قبل أو بعد النافذة المتوقعة.

النظام:

```text
preserves actual birth date
+ compares against configured expected window
+ derives classification
```

مثل:

```text
within_expected_window
early
late
```

لكن أسماء التصنيفات النهائية وطريقة حساب حدودها تعتمد على Settings 6.6.

### قاعدة مهمة

لا يجوز:

```text
change mating date
change pregnancy date
block a real birth
create a fake expected birth
```

بغرض جعل التاريخ متوافقًا مع التوقعات.

---

### 4.6 `birth.historical_location_model`

القرار:

```text
store_and_validate_against_occupancy
```

أي أن `Birth Event` يحتفظ بمرجع مباشر إلى القفص / الموقع وقت الولادة **كلقطة تاريخية**، وفي نفس الوقت يتحقق النظام من أنه متسق مع Occupancy History في 4.2.

النموذج:

```text
Birth Event.birth_location
        ↓ validate against
Housing / Occupancy History at birth time
```

هذا لا يحول `birth_location` إلى مصدر الموقع الحالي للحيوان.

```text
Current Location
→ derived from 4.2

Historical Birth Location
→ snapshot/reference on Birth Event
```

إذا ظهر اختلاف بين الاثنين عند التسجيل، يجب التعامل معه كمسألة تحقق/مراجعة، وليس تعديل التاريخ بصمت.

---

### 4.7 `litter.creation_model`

القرار:

```text
explicit_create_after_birth
```

إذن `Birth Event` و`Litter Record` مرحلتان منفصلتان:

```text
1. Record / approve actual Birth Event
2. Explicitly create Litter linked to that Birth Event
```

لا ينشئ النظام Litter تلقائيًا بمجرد اعتماد الولادة.

### حد تفسير مهم مع `birth.post_registration_transitions`

اختيار `create_or_activate_litter` ضمن انتقالات ما بعد الولادة **لا يلغي** القرار الأعلى `explicit_create_after_birth`.

التفسير المتسق هو:

```text
Birth registration
→ establishes requirement/context for Litter creation
→ user performs explicit Litter creation step
```

وليس:

```text
Birth registration
→ auto-create Litter silently
```

---

### 4.8 `litter.record_fields`

سجل البطن يحتوي على:

```text
litter_code
birth_event
biological_mother
paternity_reference
reproductive_cycle
birth_date
birth_location
total_born
live_born
stillborn_at_birth
special_conditions
current_state
notes
```

### مصدر البيانات

بما أن Litter مرتبط بـBirth Event، فالبيانات التاريخية التي أصلها الولادة يجب أن تأتي منها أو تكون مرتبطة بها بصورة موثوقة، لا أن يعيد المستخدم إدخالها كحقائق مستقلة متنافسة.

أمثلة:

```text
biological_mother
birth_date
birth_location
total_born
live_born
stillborn_at_birth
paternity_reference
```

يجب أن تظل متسقة مع واقعة الولادة المصدر.

### `biological_mother`

الأم البيولوجية ثابتة تاريخيًا ولا تتغير إذا حدث Foster لاحقًا.

```text
Biological Mother
≠
Foster Mother
```

وتغييرات التحضين تنتمي إلى 4.7.

### `current_state`

القسم يقرر وجود مفهوم `current_state` للبطن، لكنه **لا يحسم هنا نموذج إدارة هذه الحالة**: هل هي Derived بالكامل، أم Stored Transition State.

لذلك لا يجوز اختراع Enum أو Transition Matrix من هذا السؤال وحده.

يجب تفسير حالتها لاحقًا مع 4.7 و4.8.

---

### 4.9 `litter.code_strategy`

القرار:

```text
automatic
```

النظام يولد كود / رقم البطن تلقائيًا.

لكن هذا السؤال لا يحسم:

- شكل الكود.
- Prefix.
- هل يتضمن كود الأم.
- السنة.
- ترتيب الولادات.
- Scope of uniqueness.

إذن لا يجوز افتراض صيغة نهائية مثل:

```text
F023-2026-03
```

إلا إذا حسمت في إعدادات الأكواد أو Requirement مستقل.

---

### 4.10 `birth.no_live_offspring_behavior`

القرار:

```text
create_and_close_no_live_offspring
```

حتى إذا كانت:

```text
live_born = 0
```

لا يكتفي النظام بـBirth Event فقط.

يجب وجود Litter Record تاريخي لأنه يحمل نتيجة إنتاجية مهمة للأم والأب والدورة، ثم يغلق مباشرة كحالة لا يوجد بها مواليد أحياء.

المسار:

```text
Birth Event
→ Explicit Litter Creation
→ Litter created for history
→ close as no live offspring
→ no Lactation
→ no Weaning
```

لا يتم حذف البطن أو تجاهل نتيجة الولادة لأن عدم وجود مواليد أحياء معلومة مهمة للتقارير وتحليل الأداء.

---

### 4.11 `birth.post_registration_transitions`

عند اعتماد تسجيل الولادة، تم اعتماد النتائج التالية:

```text
close_pregnancy_as_birth
record_cycle_birth_outcome
create_or_activate_litter
start_lactation_if_live_offspring
derive_gestation_and_birth_timing
```

التفسير:

#### أ. إغلاق الحمل

```text
Pregnancy
→ ended_by_birth
```

ولا يظل Pregnancy مفتوحًا بعد وجود Birth Event مؤكدة.

#### ب. تحديث دورة الإنتاج

يسجل أن الدورة وصلت إلى **ولادة فعلية**، وليس مجرد حمل مؤكد.

#### ج. البطن

بسبب `explicit_create_after_birth`:

```text
Birth Event approval
→ makes Litter creation step applicable
→ Litter created explicitly
```

ولا يجوز تحويله إلى Auto Create من غير تغيير القرار الأصلي.

#### د. الرضاعة

تبدأ فقط عند:

```text
Litter exists
AND live_born > 0
```

إذا لم يوجد مولود حي:

```text
No Lactation
No Weaning
```

#### هـ. الحسابات المشتقة

يسمح النظام باشتقاق:

```text
actual gestation duration
birth timing classification
```

لكن المعادلة الدقيقة تعتمد على `reference_mating_at` والقواعد الموجودة في Settings 6.6.

---

## 5. العلاقة مع الأقسام الأخرى

### مع 4.4 التلقيح وإدارة المحاولات

مرجع الأبوة يأتي من مسار التلقيح الحقيقي، وليس من الذكر المخصص للمجموعة.

النقطة المفتوحة الخاصة بتعدد الذكور تستمر إلى Birth/Litter ولا يجوز تجاهلها.

### مع 4.5 فحص الحمل والحمل

```text
Pregnancy
→ may predict expected birth

Birth Event
→ records what actually happened
```

الوصول إلى الموعد المتوقع لا ينشئ Birth Event.

### مع 4.7 الرضاعة ومتابعة البطن

4.6 ينشئ حقيقة البداية:

```text
Birth counts + Litter identity
```

أما الأحداث التالية مثل:

```text
current alive count changes
foster transfers
preweaning follow-up
```

فتنتمي إلى 4.7.

### مع 4.8 الفطام

مواليد البطن لا تصبح Animal Records فردية من هذا القسم لمجرد تسجيل الولادة.

التحول النهائي إلى التتبع الفردي يحسم في 4.8.

### مع 4.13 الصحة والنفوق

```text
Stillborn at actual birth → 4.6
Death after live birth → 4.13
```

### مع 4.2 التسكين والإشغال

موقع الولادة Historical Snapshot، بينما Current Occupancy يستمر مشتقًا من Housing Movements.

---

## 6. نقاط الاتساق والتعارض

### 6.1 لا يوجد تعارض داخلي مانع داخل 4.6

القرارات الحالية يمكن تنفيذها معًا إذا تم الحفاظ على الفصل بين:

```text
Birth Event
Litter explicit creation
Lactation start
```

### 6.2 نقطة ترابط مفتوحة — الأبوة

كل من:

```text
birth.paternity_reference
litter.paternity_reference
```

يعتمد على نتيجة حسم تعدد الذكور في 4.4.

لذلك يجب ألا يحول التصميم هذه الحقول مبكرًا إلى FK إلزامي لذكر واحد مؤكد بدون دعم حالة الأبوة غير المحسومة إذا بقيت ممكنة تشغيليًا.

### 6.3 حد تفسير — إنشاء البطن

يوجد ظاهريًا تقارب بين:

```text
litter.creation_model = explicit_create_after_birth
```

و:

```text
birth.post_registration_transitions includes create_or_activate_litter
```

ولا يعتبر تعارضًا إذا تم تطبيق الانتقال **وفق نموذج الإنشاء المعتمد** كما ينص السؤال نفسه.

القرار الحاكم:

```text
No automatic Litter creation on Birth approval
```

### 6.4 حد تفسير — special_conditions

وجود `special_conditions` ضمن Litter fields لا يحولها إلى Structured Count، لأن القرار المتخصص حسمها كـ:

```text
notes_only
```

---

## 7. ما لا يجوز استنتاجه

لا يجوز من هذا القسم وحده افتراض:

```text
Automatic Litter creation on Birth approval
One confirmed father in every Birth/Litter
Structured congenital-condition catalogue
Individual Animal records at birth
Sex determination at birth
Individual birth weights
Litter total weight at birth
Mandatory birth time if genuinely unknown
Automatic correction of mating dates
Automatic closing of a birth outside expected window as error
Foster mother assignment
Post-birth mortality inside Birth counts
Lactation without live offspring
Weaning without live offspring
Litter code format
Litter code uniqueness scope
Manual editable current litter state
```

---

## 8. Requirements الناتجة

يمكن استخراج المتطلبات التالية:

1. وجود `Birth Event` مستقل لكل ولادة فعلية.
2. ربط Birth Event بالأم والحمل ودورة الإنتاج ومرجع الأبوة.
3. حفظ تاريخ الولادة الفعلي ووقت الولادة عند توافره.
4. حفظ موقع الولادة تاريخيًا مع التحقق منه ضد Occupancy History.
5. تسجيل `total_born`, `live_born`, `stillborn_at_birth`.
6. فرض تحقق `total_born = live_born + stillborn_at_birth`.
7. حفظ الحالات الخاصة كملاحظات غير منظمة عدديًا.
8. السماح بتسجيل الولادة خارج النافذة المتوقعة مع الحفاظ على التاريخ الحقيقي.
9. اشتقاق تصنيف توقيت الولادة بدل منع الواقعة الحقيقية.
10. وجود `Litter Record` مستقل عن Birth Event.
11. إنشاء Litter بخطوة صريحة بعد تسجيل الولادة.
12. توليد كود Litter تلقائيًا.
13. ربط Litter بالـBirth Event والأم البيولوجية ودورة الإنتاج ومرجع الأبوة.
14. الحفاظ على Birth counts داخل Litter متسقة مع Birth Event المصدر.
15. إنشاء Litter تاريخي حتى عند عدم وجود مواليد أحياء ثم إغلاقه مباشرة.
16. عدم بدء Lactation أو Weaning عند `live_born = 0`.
17. عند الولادة يتم إغلاق Pregnancy بنتيجة الولادة.
18. تسجيل أن Reproductive Cycle وصلت إلى Birth Outcome.
19. بدء Lactation تلقائيًا فقط بعد وجود Litter فعلي وبوجود مواليد أحياء.
20. حساب مدة الحمل الفعلية وتصنيف توقيت الولادة كمعلومات مشتقة.
21. عدم اعتبار Stillborn at Birth مساويًا لنفوق حدث بعد الولادة.
22. عدم إنشاء Animal Records فردية للمواليد من 4.6 وحده.
23. دعم مرجع أبوة قابلًا للتعامل مع حالة عدم اليقين إذا حسمت 4.4 بذلك.

---

## 9. حالة المراجعة النهائية

```text
Questions reviewed: 11 / 11
Applicable: 11 / 11
Answered: 11 / 11
Pending review: 0
Internal blocking conflicts: 0
Cross-section open issue: paternity handling inherited from 4.4
Guide status: Ready for Blueprint synthesis after upstream paternity decision is resolved
```
