# Farm Blueprint — Project Context & Working Rules

## 1. تعريف المشروع والمراجع

المستودع:

`hanyfreestyle/farm-blueprint.test`

هذا المشروع **Blueprint / أداة تحليل متطلبات قبل التطوير** وليس نظام إدارة المزرعة النهائي.

المسار:

```text
تصور وظيفي
→ Sections / Subsections
→ Questions
→ Answers / Review
→ Decisions / Requirements
→ Blueprint
→ النظام النهائي لاحقًا
```

### أولوية المراجع

```text
أحدث قرار صريح معتمد من المستخدم
→ docs/FARM_BLUEPRINT_PROJECT_CONTEXT.md
→ docs/QUESTIONNAIRE_SECTION_ARCHITECTURE_REVIEW.md
→ Approved Answers / Questionnaire Guides
→ الكود الحالي
→ السجل التاريخي القديم
```

المرجع الوظيفي الأساسي:

`docs/تصور_مشروع_الارانب.md`

يجب قراءته قبل إنشاء أسئلة جديدة، ولا يتم اختراع Requirement غير مدعوم بالمصدر وكأنه حقيقة.

---

## 2. التقنية ومحرك الأسئلة

- Laravel 12
- PHP 8.2
- Filament 4
- MySQL
- Questionnaire Engine مخصص

Question Types الحالية:

```text
text
textarea
number
date
yes_no
single_choice
multi_choice
select
```

Dependency Operators الحالية فقط:

```text
EQUALS
CONTAINS
```

الموديل:

`App\Models\QuestionnaireQuestion`

خدمة المزامنة:

`App\Services\Questionnaire\QuestionSeederSyncService`

لا يستخدم Type أو Operator جديد دون مراجعة Enums / Models / Engine.

---

## 3. الهيكل الرئيسي — IMPLEMENTED & VERIFIED

```text
1. إدارة البيانات الأساسية
2. هيكل المزرعة
3. بيانات الحيوان وتكوين القطيع
4. الحركات ودورة التشغيل الفعلية
5. التقارير والتحليلات والتنبيهات ومؤشرات الأداء
6. الإعدادات وقواعد التشغيل
```

Subsections:

```text
Master Data = 15
Farm Structure = 4
Animal / Herd = 5
Workflow = 17
Reports = 15
Settings = 13
Total = 69
```

القاعدة المعمارية:

```text
تعريف الكيان / ما هو؟ → Data
ما الذي حدث فعليًا؟ → Workflow
ماذا نعرف أو نعرض؟ → Reports / Analytics
ما القواعد القابلة للضبط؟ → Settings
```

---

## 4. Master Data — ملخص الحدود المهمة

القسم يحتوي على:

```text
الأنشطة التشغيلية
الأغراض الإنتاجية
مؤشرات السلالات
بيانات السلالات
المستخدمون وفريق التشغيل
أسباب النقل
أسباب النفوق
أسباب الاستبعاد
أسباب الخروج
أسباب تغيير الذكر
المحافظات
المدن
أنظمة التهوية
أنظمة التبريد
أنظمة التدفئة
```

قواعد مهمة:

- Breed Master Data ≠ Animal Pedigree.
- قوائم الأسباب تعرف القيم المرجعية فقط؛ واقعة الاستخدام الفعلية تسجل في Workflow.
- `أسباب النقل` موجودة بالفعل ولا يعاد تعريفها في 4.2.
- BreedMetric يعرف المؤشر ووحدته وتعريف القياس واتجاه المقارنة، بينما الأداء الفعلي ينتج من البيانات التشغيلية والتقارير.

---

## 5. Farm Structure — ملخص الحدود المهمة

```text
Farm
↓
Barn
↓
Battery
↓
Cage / Cell
```

### Cage

- يولد من Battery؛ لا Create مستقل.
- لا Delete مباشر بعد التاريخ التشغيلي.
- Code فريد على مستوى النظام.
- QR مرتبط بالهوية.
- الهوية والموقع الهيكلي Immutable بعد التفعيل.
- Status Changes = Actions + History.
- Current Occupancy / Available Capacity مشتقان من الحركات.
- Housing Eligibility يجمع الحالة والسعة والاستخدام والصيانة والتطهير وSettings.

### Battery

- تتبع Barn واحدًا.
- Code فريد على مستوى النظام.
- بنيتها تولد Cages.
- التاريخ التشغيلي يقفل الهوية الهيكلية التاريخية.
- Stop / Maintenance يؤثر على إتاحة الأقفاص دون تغيير Local Status لكل Cage تلقائيًا.

الفصل:

```text
تعريف المواقع → Farm Structure
التسكين/النقل/الإخلاء/الصيانة/التطهير الفعلي → Workflow
قواعد السعة والإتاحة والتطهير → Settings
عرض الإشغال والسعة → Reports
```

سؤال مؤجل لا يمنع التقدم:

> كيف يجب إدارة الأنواع الفيزيائية للبطاريات؟

---

## 6. القسم الثالث — بيانات الحيوان وتكوين القطيع

```text
3.1 بيانات وهوية الحيوان
3.2 مصدر الحيوان وبداية السجل
3.3 النسب وشجرة العائلة
3.4 القطيع الافتتاحي وتهيئة نقطة البداية
3.5 تكوين القطيع الإنتاجي وتنظيم المجموعات
```

قواعد عامة:

- Animal Record يستمر لنفس الحيوان طوال حياته.
- الموقع الحالي والوزن الحالي والجاهزية والحالة الإنتاجية المشتقة ليست حقولًا ثابتة تعدل يدويًا.
- القطيع الافتتاحي يبدأ من أول معلومة موثوقة دون اختراع تاريخ.

### 6.1 Animal Identity — IMPLEMENTED & LOCAL SEED VERIFIED

Seeder:
`database/seeders/Questions/AnimalHerd/AnimalIdentityQuestionsSeeder.php`

عدد الأسئلة: **9**.

```text
animal.identity_fields
animal.internal_code_strategy
animal.internal_code_unique_scope
animal.internal_code_lifetime
animal.external_identifier_cardinality
animal.external_identifier_types
animal.temporary_unknown_sex
animal.breed_requirement
animal.birth_information_methods
```

### 6.2 Animal Source / Record Start — IMPLEMENTED & LOCAL SEED VERIFIED

Seeder:
`database/seeders/Questions/AnimalHerd/AnimalSourceQuestionsSeeder.php`

عدد الأسئلة: **7**.

```text
animal.source_origin_categories
animal.outside_source_types
animal.internal_source_derivation
animal.outside_source_fields
animal.interfarm_source_reference
animal.pre_entry_history_policy
animal.other_source_description_required
```

### 6.3 Pedigree / Family Tree — IMPLEMENTED & LOCAL SEED VERIFIED

Seeder:
`database/seeders/Questions/AnimalHerd/AnimalPedigreeQuestionsSeeder.php`

عدد الأسئلة: **10**.

```text
animal.pedigree_relationships
animal.internal_pedigree_derivation
animal.pedigree_completeness_states
animal.external_ancestor_strategy
animal.external_ancestor_fields
animal.pedigree_evidence_types
animal.family_tree_build_strategy
animal.biological_vs_foster_mother
animal.genetic_line_usage
animal.offspring_breed_derivation
```

حدود مهمة:

- Biological Mother تبقى منفصلة عن Foster Mother.
- القرابة والتحذير/المنع → Settings / Reports.

### 6.4 Initial Herd Setup — IMPLEMENTED & LOCAL SEED VERIFIED

Seeder:
`database/seeders/Questions/AnimalHerd/InitialHerdSetupQuestionsSeeder.php`

عدد الأسئلة: **11**.

```text
animal.opening_snapshot_operational_fields
animal.opening_missing_data_policy
animal.opening_starting_contexts
animal.opening_reproductive_contexts
animal.opening_trusted_prior_history_strategy
animal.opening_trusted_prior_fact_types
animal.opening_history_completeness_tracking
animal.opening_pre_activation_checks
animal.opening_activation_model
animal.opening_baseline_snapshot
animal.opening_task_evaluation_after_activation
```

### 6.5 Production Herd / Groups — IMPLEMENTED ON GITHUB / WAITING LOCAL SEED

Seeder:
`database/seeders/Questions/AnimalHerd/ProductionHerdGroupsQuestionsSeeder.php`

عدد الأسئلة: **10**.

```text
production_herd.organization_methods
production_group.record_fields
production_group.code_policy
production_group.female_membership_model
production_group.primary_male_model
production_group.primary_male_assignment_scope
production_group.alternate_male_model
production_group.statuses
production_group.assignment_vs_mating
production_group.history_policy
```

الحدود:

```text
تعريف المجموعة وعلاقاتها → 3.5
نسبة الإناث لكل ذكر والجاهزية → Settings
تغيير الذكر أو نقل الأنثى فعليًا → Workflow
أسباب تغيير الذكر → Master Data
التلقيح الفعلي وإثبات الأبوة → Workflow / Pedigree
الأداء → Reports
```

---

## 7. القسم الرابع — Workflow

Workflow يجيب عن:

> ماذا حدث؟ ومتى؟ ولماذا؟ ومن نفذه؟

الشجرة:

```text
4.1 استقبال الحيوان من الخارج وإعادة الإدخال
4.2 التسكين والنقل والإخلاء وإدارة الإشغال
4.3 الوزن والقياسات التشغيلية
4.4 التلقيح وإدارة المحاولات
4.5 فحص الحمل ومتابعة الحمل وتجهيز الولادة
4.6 تسجيل الولادة وإنشاء البطن
4.7 الرضاعة ومتابعة البطن وتداخل دورات الأم
4.8 الفطام والتحول إلى التتبع الفردي
4.9 النمو والفرز وإعادة التقييم
4.10 تحديد المصير والاستبعاد من المسار
4.11 الإحلال والاعتماد داخل القطيع الإنتاجي
4.12 التسمين والجاهزية للبيع
4.13 الصحة والعزل والتعافي والنفوق
4.14 الحالات الاستثنائية وإعادة بناء المسار
4.15 الخروج من المزرعة وإعادة الدخول
4.16 تشغيل وصيانة وتجهيز مواقع الإيواء
4.17 تنفيذ وإدارة المهام التشغيلية
```

قاعدة الوزن:

```text
الوزن الفعلي → Workflow
موعد الوزن / المستهدف / Threshold → Settings
منحنيات النمو والمقارنة والتحليل → Reports
```

### 7.1 Animal Intake / Re-entry — IMPLEMENTED ON GITHUB / WAITING LOCAL SEED

Seeder:
`database/seeders/Questions/Workflow/AnimalIntakeQuestionsSeeder.php`

عدد الأسئلة: **11**.

```text
animal_intake.reception_modes
animal_intake.batch_shared_fields
animal_intake.entry_weight_policy
animal_intake.initial_evaluation_required
animal_intake.initial_evaluation_fields
animal_intake.initial_decision_outcomes
animal_intake.temporary_monitoring_stage
animal_intake.temporary_monitoring_events
animal_intake.preventive_actions_recording
animal_intake.finalization_model
animal_intake.reentry_process_model
```

حدود 4.1:

```text
المصدر → 3.2
الهوية → 3.1
التسكين → 4.2
تفاصيل سجل الوزن → 4.3
مدة/إلزام الحجر ومعايير القبول → Settings
المسار الصحي → 4.13
الخروج بسبب الرفض → 4.15
```

### 7.2 Housing / Movement / Vacating / Occupancy — IMPLEMENTED ON GITHUB / WAITING LOCAL SEED

Seeder:
`database/seeders/Questions/Workflow/HousingMovementQuestionsSeeder.php`

عدد الأسئلة: **12**.

```text
housing_movement.event_types
housing_movement.record_fields
housing_movement.location_reference_model
housing_movement.transfer_atomicity
housing_movement.single_active_occupancy
housing_movement.batch_transfer_support
housing_movement.batch_transfer_scopes
housing_movement.batch_individual_history
housing_movement.reason_requirement_scope
housing_movement.transfer_reason_reference
housing_movement.time_tracking_model
housing_movement.occupied_structure_relocation_support
```

Dependencies:

```text
transfer_atomicity
→ event_types CONTAINS cage_transfer

batch_transfer_scopes
batch_individual_history
→ batch_transfer_support EQUALS 1
```

حدود 4.2:

```text
تعريف المواقع → Farm Structure
Housing Eligibility / Capacity Rules → Settings
الإشغال الحالي → Derived من الحركات
أسباب النقل → Master Data
التطهير وتجهيز الموقع → 4.16 + Settings
الصيانة نفسها → 4.16؛ نقل الحيوانات الناتج عنها → 4.2
الخروج النهائي → 4.15
عرض الإشغال وعجز السعة → Reports
```

### 7.3 Operational Weight / Measurements — IMPLEMENTED ON GITHUB / WAITING LOCAL SEED

Seeder:
`database/seeders/Questions/Workflow/OperationalMeasurementsQuestionsSeeder.php`

عدد الأسئلة: **9**.

```text
operational_measurement.subject_types
operational_measurement.record_fields
operational_measurement.weight_unit_policy
operational_measurement.context_types
operational_measurement.single_source_linking
operational_measurement.age_handling
operational_measurement.preweaning_weight_model
operational_measurement.batch_entry_support
operational_measurement.batch_individual_records
```

القرارات التي تغطيها المجموعة:

- الوزن الفعلي يمكن أن يرتبط بالحيوان الفردي وبالبطن/المواليد قبل التتبع الفردي إذا اعتمد ذلك.
- سجل الوزن يحفظ Value / Unit / Measured At / Context / Age عند الإمكان / Workflow Reference / Performer / Notes حسب الإجابات.
- توحيد طريقة التعامل مع وحدات الوزن حتى تكون القيم قابلة للمقارنة.
- تمييز سياقات الوزن الموثقة في المصدر: Intake، Pregnancy، Lactation، Weaning، Growth، Sorting، Replacement Follow-up، Fattening.
- عند تسجيل الوزن من Workflow آخر يراجع هل توجد قيمة قياس واحدة مرتبطة بالحدث بدل Duplicate Values.
- العمر وقت القياس يمكن اشتقاقه من بيانات الميلاد مع حسم التعامل مع العمر التقديري أو المجهول.
- حسم السؤال المفتوح في المصدر: هل وكيف يتم وزن المواليد قبل الفطام.
- دعم Batch Weighing كواجهة إدخال عند الحاجة مع بقاء Individual Weight Records.

Dependencies:

```text
operational_measurement.preweaning_weight_model
→ operational_measurement.subject_types CONTAINS preweaning_litter

operational_measurement.batch_individual_records
→ operational_measurement.batch_entry_support EQUALS 1
```

حدود 4.3:

```text
Weight at Entry required/optional → 4.1
موعد ودورية الوزن حسب المرحلة → Settings 6.9 / 6.10 وغيرها
Target Weight / Minimum / Maximum / Threshold → Settings
تفسير النمو والزيادة ومتوسط الزيادة اليومية والمقارنات → Reports 5.5 / 5.10
وزن الحمل الفعلي → نفس Weight History مع Context للحمل
وزن الفطام أو النمو أو الفرز أو الإحلال أو التسمين → نفس سجل القياسات مع Context مناسب
الوزن الحالي للحيوان → Derived من أحدث سجل وزن صالح؛ ليس حقل Edit مستقل
تفاصيل تعديل/إلغاء قياس خاطئ وسجل التدقيق → سياسة التصحيح العامة في Settings 6.2
```

ملاحظة Scope:

> المصدر الوظيفي الحالي يوثق **الوزن** كقياس رقمي تشغيلي واضح. لا يتم اختراع أنواع قياسات جسم أخرى من تلقاء المشروع؛ إذا ظهر احتياج موثق لاحقًا يضاف بسؤال مستقل.

### 7.4 Mating / Attempts — IMPLEMENTED ON GITHUB / WAITING LOCAL SEED

Seeder:
`database/seeders/Questions/Workflow/MatingAttemptsQuestionsSeeder.php`

عدد الأسئلة: **12**.

```text
mating.structure_model
mating.reproductive_cycle_start_model
mating.attempt_event_cardinality
mating.event_record_fields
mating.event_result_categories
mating.execution_context_fields
mating.assigned_male_execution_role
mating.runtime_kinship_check
mating.multiple_males_paternity_policy
mating.attempt_completion_model
mating.new_attempt_boundary
mating.attempt_cancellation_model
```

المبدأ الوظيفي الذي تحسمه المجموعة:

```text
Mating Event
≠ Mating Attempt
≠ Reproductive Cycle
```

والتصور المرجعي يدعم النموذج:

```text
Reproductive Cycle
→ Mating Attempt(s)
→ Mating Event(s)
```

مع بقاء الاختيار النهائي سؤالًا داخل الـBlueprint.

القرارات التي تغطيها 4.4:

- البنية بين Reproductive Cycle / Attempt / Mating Event.
- هل أول Mating Event ينشئ الدورة تلقائيًا أم تبدأ الدورة بإجراء مستقل.
- هل Attempt يحتوي عددًا متغيرًا من Mating Events بدل حقول ثابتة First/Second Mating.
- بيانات كل Mating Event، وبالأخص Actual Male وOccurred At والنتيجة المباشرة وربط الأوزان عند الحاجة بالسجل الموثوق.
- نتائج الحدث الفعلي قبل Pregnancy Check، وتشمل توثيق رفض الأنثى للتلقيح.
- المعلومات التي يحتاجها العامل وقت التنفيذ دون إعادة إدخال بيانات موجودة أصلًا.
- دور Assigned Male / Production Group في شاشة التنفيذ مع الفصل بين Assignment وبين Actual Male.
- إعادة فحص القرابة عند التلقيح الفعلي حتى إذا فحصت أثناء تكوين المجموعة.
- حماية بيانات النسب عند استخدام أكثر من ذكر داخل نفس Attempt وعدم اختيار أب تلقائيًا إذا اعتمد هذا القرار.
- كيفية انتقال Attempt إلى Waiting Pregnancy Check بعد استيفاء قواعد التكرار.
- الحد الفاصل بين Repeated Mating داخل Attempt وبين New Attempt بعد إغلاق السابقة.
- إلغاء Attempt كحدث محفوظ تاريخيًا أو معالجته من خلال General Correction Policy حسب الإجابة.

Dependencies:

```text
mating.event_result_categories
→ mating.event_record_fields CONTAINS result

mating.attempt_event_cardinality
mating.multiple_males_paternity_policy
mating.attempt_completion_model
mating.new_attempt_boundary
mating.attempt_cancellation_model
→ mating.structure_model EQUALS cycle_attempts_mating_events
```

حدود 4.4:

```text
تعريف Production Group والذكر المخصص → 3.5
الذكر المستخدم فعليًا → 4.4 Mating Event
شروط جاهزية الأنثى والذكر → Settings 6.5
العمر/الوزن المطلوب للتلقيح → Settings 6.5
عدد التلقيحات داخل Attempt والفاصل بينها → Settings 6.5
قواعد استخدام ذكر مختلف عن Assigned Male → Settings 6.5 / 6.2 عند الحاجة للتحكم والتجاوز
مستوى القرابة وحدود Information / Warning / Block / Override → Settings 6.2 و6.5
هل يلزم وزن حديث في يوم التلقيح وما معنى حديث → Settings 6.5؛ الوزن الفعلي نفسه → 4.3
مرجع احتساب موعد Pregnancy Check من أول/آخر Mating → Settings 6.6
Pregnancy Check ونتيجته وإغلاق Attempt كناجحة/فاشلة → 4.5
إعادة تلقيح الأم أثناء الرضاعة تستخدم نفس Mating Workflow لكن تداخل الدورات يعالج في 4.7
تقارير الخصوبة وأداء الذكر/الأنثى والتوافق → Reports 5.3 / 5.7
قواعد توليد المهام بعد Mating → Settings 6.12؛ تنفيذ المهمة نفسها → 4.17
```

---

## 8. Reports — حدود مختصرة

الشجرة المعتمدة: **15 Subsection**.

ذات الصلة بالعمل الحالي:

```text
5.3 تقارير الخصوبة والتلقيح والحمل
5.5 تقارير النمو والأوزان والتسمين
5.7 تحليل أداء الحيوانات الإنتاجية
5.10 الاتجاهات والمقارنات والتحليل عبر الزمن
```

Reports مسؤولة عن:

- منحنيات الوزن والنمو.
- الزيادة بين القياسات.
- Average Daily Gain عند اعتماد طريقة الحساب.
- نسب نجاح الحمل وربط النتائج بالإناث والذكور والمحاولات.
- تقييم تكرار الفشل عند ذكر أو أنثى أو توليفة معينة.
- المقارنة بين الحيوانات/البطون/السلالة/المزرعة حسب Requirements لاحقة.
- اكتشاف الحالات وعرضها، لا تغيير السجلات التشغيلية الأصلية.

Thresholds / Targets / Periods القابلة للضبط → Settings.

---

## 9. Settings — حدود مختصرة

الشجرة المعتمدة: **13 Subsection**.

المبدأ:

```text
Structural / System Rule ≠ Operational Setting
```

Open Requirements تشمل:

- Scope: System / Farm / Barn / Profile.
- Defaults / Inheritance / Overrides.
- Effective Date / Versioning.
- Historical Settings Reference.
- Information / Warning / Block.
- Override Policy.
- Sensitive Record Correction + Audit.
- الأعمار والأوزان والمدد والفواصل وThresholds وTargets.

للوزن خصوصًا:

```text
مواعيد الوزن
دورية الوزن
المستهدفات
حدود القبول/التنبيه
قواعد الجاهزية المبنية على الوزن
→ Settings
```

للتلقيح خصوصًا:

```text
جاهزية الأنثى والذكر
العمر والوزن والقيود الصحية
التلقيح أثناء الرضاعة
عدد Mating Events المطلوبة داخل Attempt
الفاصل بينها
Male Usage Limits / Rest Periods
قواعد استخدام ذكر غير المخصص
Kinship Warning / Block / Override
متى تعتبر Attempt مكتملة أو تحتاج مراجعة
→ Settings 6.5 أساسًا
```

---

## 10. Question Seeder Sync والحفاظ على الإجابات

المبدأ:

```text
Stable Section Record
+ Stable section_id
+ Stable seed_key
+ Stable option value
+ preserveAnswers = true
```

- لا يغير `seed_key` عند إعادة صياغة نفس القرار.
- لا تتغير `option.value` لمجرد تعديل Label.
- المزامنة لا تحذف إجابة مستقرة بصمت.
- أي تغيير غير متوافق مع إجابة محفوظة يجب أن يفشل بوضوح.
- جميع Question Seeders الجديدة تستخدم `preserveAnswers: true`.
- لا يستخدم `migrate:refresh --seed` روتينيًا على قاعدة تحتوي إجابات نريد الحفاظ عليها.

---

## 11. قواعد إنشاء الأسئلة وملفات الشرح

قبل Question Seeder جديد:

1. قراءة هذا الملف أولًا.
2. مراجعة الجزء المقابل من `تصور_مشروع_الارانب.md`.
3. مراجعة Architecture Review وحدود Subsection.
4. مراجعة الأسئلة السابقة لمنع التكرار.
5. مراجعة Enums / Models / Engine.
6. تحويل النقاط غير المحسومة فقط إلى Questions تنتج Decisions فعلية.
7. Stable Keys / Option Values.
8. Dependencies فقط عند حاجة وظيفية.
9. تحديد `report_category` و`target_entity` حسب conventions الحالية.
10. عدم تحويل مثال إلى Requirement نهائي بلا سؤال/قرار.
11. عدم تضخيم الأسئلة.
12. `preserveAnswers = true`.

عند إعداد Questionnaire Guide لاحقًا **لا تعاد الدراسة من الصفر**. يعتمد على:

```text
FARM_BLUEPRINT_PROJECT_CONTEXT.md
+ Seeder النهائي
+ الإجابات المسجلة
+ مراجعة سريعة للجزء المقابل من تصور_مشروع_الارانب.md
```

---

## 12. Question Orchestrators الحالية

`QuestionnaireAnimalHerdQuestionsSeeder`:

```text
AnimalIdentityQuestionsSeeder
AnimalSourceQuestionsSeeder
AnimalPedigreeQuestionsSeeder
InitialHerdSetupQuestionsSeeder
ProductionHerdGroupsQuestionsSeeder
```

`QuestionnaireWorkflowQuestionsSeeder`:

```text
AnimalIntakeQuestionsSeeder
HousingMovementQuestionsSeeder
OperationalMeasurementsQuestionsSeeder
MatingAttemptsQuestionsSeeder
```

شجرة الأسئلة الحالية ذات الصلة:

```text
database/seeders/Questions/
├── AnimalHerd/
│   ├── AnimalIdentityQuestionsSeeder.php
│   ├── AnimalSourceQuestionsSeeder.php
│   ├── AnimalPedigreeQuestionsSeeder.php
│   ├── InitialHerdSetupQuestionsSeeder.php
│   └── ProductionHerdGroupsQuestionsSeeder.php
└── Workflow/
    ├── AnimalIntakeQuestionsSeeder.php
    ├── HousingMovementQuestionsSeeder.php
    ├── OperationalMeasurementsQuestionsSeeder.php
    └── MatingAttemptsQuestionsSeeder.php
```

---

## 13. حالة التنفيذ الحالية

```text
Architecture → IMPLEMENTED & VERIFIED
Question Creation → IN PROGRESS

3.1 Animal Identity → IMPLEMENTED & LOCAL SEED VERIFIED
3.2 Animal Source / Record Start → IMPLEMENTED & LOCAL SEED VERIFIED
3.3 Animal Pedigree / Family Tree → IMPLEMENTED & LOCAL SEED VERIFIED
3.4 Initial Herd Setup → IMPLEMENTED & LOCAL SEED VERIFIED
3.5 Production Herd / Groups → IMPLEMENTED ON GITHUB / WAITING LOCAL SEED

4.1 Animal Intake / Re-entry → IMPLEMENTED ON GITHUB / WAITING LOCAL SEED
4.2 Housing / Movement / Vacating / Occupancy → IMPLEMENTED ON GITHUB / WAITING LOCAL SEED
4.3 Operational Weight / Measurements → IMPLEMENTED ON GITHUB / WAITING LOCAL SEED
4.4 Mating / Attempts → IMPLEMENTED ON GITHUB / WAITING LOCAL SEED
```

لتحديث التطوير المحلي دون فقد الإجابات:

```bash
git pull origin master
php artisan db:seed --class=QuestionnaireAnimalHerdQuestionsSeeder
php artisan db:seed --class=QuestionnaireWorkflowQuestionsSeeder
```

إذا كان AnimalHerd قد تم Seed له بالفعل، يشغل Workflow Seeder فقط.

**التالي بعد 4.4:**

`4.5 فحص الحمل ومتابعة الحمل وتجهيز الولادة`

---

## 14. قاعدة GitHub الإلزامية

المستودع افتراضيًا **READ ONLY**.

أي تعديل / إنشاء / حذف / Commit / Branch / PR / Seeder يحتاج طلبًا صريحًا ومباشرًا من المستخدم في نفس السياق.

وجود الصلاحية التقنية لا يعني وجود إذن بالتعديل.

`تصور_مشروع_الارانب.md` Reference Only ولا يعدل إلا بطلب صريح.

---

## 15. المبدأ الأساسي

هذا المشروع هو:

**أداة تحليل متطلبات قبل التطوير**

وليس:

**نظام إدارة المزرعة النهائي.**

الهدف تقليل الافتراضات وتحويل الإجابات إلى Requirements قابلة للتنفيذ مع الحفاظ على التاريخ والاتساق وعدم الاعتماد على الذاكرة وحدها.
