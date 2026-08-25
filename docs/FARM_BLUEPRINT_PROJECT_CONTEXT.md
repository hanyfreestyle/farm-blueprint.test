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
→ Questionnaire Guides
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

يجب مراجعته قبل إنشاء أسئلة جديدة، مع الحفاظ على مصطلحاته ومنطقه وعدم اختراع Requirements غير مدعومة بالمصدر.

Architecture Review مكتملة ومعتمدة، وإعادة الهيكلة P0→P10 منفذة ومتحقق منها.

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

قواعد الهوية والحفاظ على الإجابات:

```text
Stable Section Record
+ Stable section_id
+ Stable seed_key
+ Stable option value
+ preserveAnswers = true
```

- لا يتغير `seed_key` بسبب إعادة صياغة نفس القرار.
- لا تتغير `option.value` لمجرد تغيير الـLabel.
- أي تغيير يهدد إجابة محفوظة يجب أن يفشل بوضوح بدل حذفها بصمت.
- لا يستخدم `migrate:refresh --seed` روتينيًا على قاعدة تحتوي إجابات نريد الاحتفاظ بها.
- لا يستخدم Type أو Dependency Operator جديد دون مراجعة Enums / Models / Engine.

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

أعداد الـSubsections:

```text
Master Data = 15
Farm Structure = 4
Animal / Herd = 5
Workflow = 17
Reports = 15
Settings = 13
Total = 69
```

القاعدة المعمارية الإلزامية:

```text
تعريف الكيان / What IS it? → Data
ما الذي حدث فعليًا؟ → Workflow
ماذا نعرف أو نعرض؟ → Reports / Analytics
ما القواعد القابلة للضبط؟ → Settings
```

---

## 4. حدود Master Data وFarm Structure المهمة

Master Data الحالية تشمل: الأنشطة التشغيلية، الأغراض الإنتاجية، مؤشرات السلالات، بيانات السلالات، المستخدمين وفريق التشغيل، أسباب النقل/النفوق/الاستبعاد/الخروج/تغيير الذكر، المحافظات، المدن، وأنظمة التهوية/التبريد/التدفئة.

قواعد ثابتة:

- Breed Master Data ≠ Animal Pedigree.
- قوائم الأسباب تعرف القيم المرجعية؛ استخدامها الفعلي يسجل في Workflow.
- `أسباب النقل` موجودة ولا يعاد تعريفها داخل 4.2.

Farm Structure:

```text
Farm → Barn → Battery → Cage / Cell
```

قرارات مهمة:

- Cage يولد من Battery ولا Create مستقل له.
- Cage Code فريد على مستوى النظام وQR مرتبط بهويته.
- الهوية والموقع الهيكلي Immutable بعد التفعيل.
- Current Occupancy / Available Capacity مشتقان من الحركات.
- Housing Eligibility يعتمد على الحالة والسعة والاستخدام والصيانة والتطهير وSettings.
- Battery تتبع Barn واحدًا، Code فريد، وبنيتها تولد Cages.
- التاريخ التشغيلي يقفل الهوية الهيكلية التاريخية.

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

## 5. القسم الثالث — بيانات الحيوان وتكوين القطيع

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
- القطيع الافتتاحي يبدأ من أول معلومة موثوقة ولا يختلق تاريخًا سابقًا.

### 3.1 Animal Identity — LOCAL SEED VERIFIED

Seeder: `Questions/AnimalHerd/AnimalIdentityQuestionsSeeder.php` — **9 أسئلة**.

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

### 3.2 Animal Source / Record Start — LOCAL SEED VERIFIED

Seeder: `Questions/AnimalHerd/AnimalSourceQuestionsSeeder.php` — **7 أسئلة**.

```text
animal.source_origin_categories
animal.outside_source_types
animal.internal_source_derivation
animal.outside_source_fields
animal.interfarm_source_reference
animal.pre_entry_history_policy
animal.other_source_description_required
```

### 3.3 Pedigree / Family Tree — LOCAL SEED VERIFIED

Seeder: `Questions/AnimalHerd/AnimalPedigreeQuestionsSeeder.php` — **10 أسئلة**.

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

Biological Mother تبقى منفصلة عن Foster Mother؛ القرابة والتحذير/المنع → Settings / Reports.

### 3.4 Initial Herd Setup — LOCAL SEED VERIFIED

Seeder: `Questions/AnimalHerd/InitialHerdSetupQuestionsSeeder.php` — **11 سؤالًا**.

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

### 3.5 Production Herd / Groups — IMPLEMENTED ON GITHUB / WAITING LOCAL SEED

Seeder: `Questions/AnimalHerd/ProductionHerdGroupsQuestionsSeeder.php` — **10 أسئلة**.

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

## 6. القسم الرابع — Workflow

Workflow يجيب عن:

> ماذا حدث؟ ومتى؟ ولماذا؟ ومن نفذه؟

الشجرة المعتمدة:

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

### 4.1 Animal Intake / Re-entry — IMPLEMENTED / WAITING LOCAL SEED

Seeder: `Questions/Workflow/AnimalIntakeQuestionsSeeder.php` — **11 سؤالًا**.

الحدود: المصدر→3.2، الهوية→3.1، التسكين→4.2، سجل الوزن→4.3، قواعد الحجر/القبول→Settings، الصحة→4.13، الخروج→4.15.

### 4.2 Housing / Movement / Vacating / Occupancy — IMPLEMENTED / WAITING LOCAL SEED

Seeder: `Questions/Workflow/HousingMovementQuestionsSeeder.php` — **12 سؤالًا**.

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

الإشغال Derived من الحركات؛ السعة/Housing Rules→Settings؛ أسباب النقل→Master Data؛ تجهيز/صيانة الموقع→4.16.

### 4.3 Operational Weight / Measurements — IMPLEMENTED / WAITING LOCAL SEED

Seeder: `Questions/Workflow/OperationalMeasurementsQuestionsSeeder.php` — **9 أسئلة**.

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

قاعدة الفصل:

```text
الوزن الفعلي → 4.3
موعد/دورية/Target/Threshold → Settings
منحنيات النمو والمقارنات → Reports
```

المصدر يوثق الوزن كقياس رقمي تشغيلي واضح؛ لا تضاف قياسات جسم أخرى دون دعم وظيفي.

### 4.4 Mating / Attempts — IMPLEMENTED / WAITING LOCAL SEED

Seeder: `Questions/Workflow/MatingAttemptsQuestionsSeeder.php` — **12 سؤالًا**.

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

المبدأ:

```text
Mating Event ≠ Mating Attempt ≠ Reproductive Cycle
```

التصور يدعم `Reproductive Cycle → Attempt(s) → Mating Event(s)` مع بقاء القرار النهائي عبر الإجابات.

الحدود:

```text
Assigned Male / Production Group → 3.5
Actual Male + Mating Event → 4.4
جاهزية الأنثى والذكر والعمر/الوزن → Settings 6.5
عدد التلقيحات والفاصل بينها → Settings 6.5
Kinship Warning/Block/Override → Settings 6.2/6.5
Reference Date لموعد الجس → Settings 6.6
Pregnancy Check / Pregnancy → 4.5
التقارير الخصوبية → Reports 5.3/5.7
```

### 4.5 Pregnancy Check / Follow-up / Birth Preparation — IMPLEMENTED / WAITING LOCAL SEED

Seeder: `Questions/Workflow/PregnancyFollowUpQuestionsSeeder.php` — **12 سؤالًا**.

```text
pregnancy_check.history_model
pregnancy_check.record_fields
pregnancy_check.result_categories
pregnancy_check.not_performed_handling
pregnancy_check.uncertain_result_flow
pregnancy_check.positive_result_flow
pregnancy_check.negative_result_flow
pregnancy.record_fields
pregnancy.current_state_model
pregnancy.followup_event_types
pregnancy.followup_event_record_fields
pregnancy.expected_window_birth_behavior
```

القرارات الأساسية:

- Pregnancy Check المنفذ فعليًا ≠ Task لم تنفذ.
- يمكن الاحتفاظ بأكثر من Pregnancy Check داخل نفس Attempt.
- Positive / Negative / Uncertain نتائج فحص؛ المسارات الناتجة تحسم بالأسئلة.
- Pregnancy Record مستقل بعد التأكيد ويمكن أن يحتفظ بمرجع Attempt/Paternity/Reference Mating/Expected Birth Window.
- حالة الحمل لا تعامل كEdit يدوي بلا تاريخ.
- Follow-up Events الفعلية يمكن ربطها بالحمل، وWeight الفعلي يبقى في 4.3.
- دخول Expected Birth Window لا ينشئ ولادة تلقائيًا.

الحدود:

```text
موعد الجس / Recheck / Reference Mating Rule / مدة الحمل / Birth Window → Settings 6.6
Weight الحمل الفعلي → 4.3
تسجيل الولادة → 4.6
الحالات الاستثنائية → 4.14 / 4.13 حسب الحدث
Tasks Rules → Settings 6.12؛ تنفيذ Tasks → 4.17
التحليل والتنبيهات → Reports
```

### 4.6 Birth Registration / Litter Creation — IMPLEMENTED ON GITHUB / WAITING LOCAL SEED

Seeder:
`database/seeders/Questions/Workflow/BirthLitterQuestionsSeeder.php`

عدد الأسئلة: **11**.

```text
birth.event_record_fields
birth.offspring_count_fields
birth.count_entry_model
birth.special_condition_representation
birth.outside_expected_window_behavior
birth.historical_location_model
litter.creation_model
litter.record_fields
litter.code_strategy
birth.no_live_offspring_behavior
birth.post_registration_transitions
```

المبدأ الوظيفي:

```text
Pregnancy / Reproductive Cycle
→ Actual Birth Event
→ Litter Record
→ Lactation إذا وُجد مواليد أحياء
```

القرارات التي تغطيها 4.6:

- Birth Event يحتفظ بمرجع الأم والحمل والدورة والأبوة والتاريخ الفعلي والموقع والمنفذ حسب الإجابات.
- أعداد الولادة الأساسية تميز `Total Born / Live Born / Stillborn at Birth`.
- حسم هل `Total Born` يحسب تلقائيًا من الأحياء + النافقين أم يدخل ثم يتحقق منه.
- الحالات الخاصة مثل عيب خلقي ظاهر لا تفترض كطرف ثالث في معادلة الإجمالي؛ يمكن تمثيلها كصفة متداخلة إذا اعتمد ذلك.
- الولادة المبكرة أو المتأخرة تسجل بتاريخها الفعلي؛ لا يعدل Mating Date لتجميل Gestation Length.
- موقع الولادة التاريخي يمكن أن يخزن كمرجع داخل الحدث أو يستنتج من Occupancy History حسب الإجابة.
- Litter Record ينشأ من Birth Event، مع حسم Automatic vs Explicit Creation.
- Litter Record يمكن أن يحتفظ بالكود وBirth Event وBiological Mother وPaternity Reference وCycle وBirth Counts والحالة التاريخية.
- `litter.code_strategy` يحسم Automatic vs Manual فقط؛ شكل الكود وتكوينه القابل للتهيئة → Settings.
- الولادة بدون مواليد أحياء لا يجب أن تختفي تاريخيًا؛ يحسم السؤال هل ينشأ Litter مغلق مباشرة أو نموذج آخر.
- Post-Birth Transitions تراجع إغلاق الحمل بنتيجة ولادة، تسجيل Cycle Outcome، إنشاء/تفعيل Litter، وبدء Lactation فقط عند وجود مواليد أحياء.

Dependencies:

```text
birth.special_condition_representation
→ birth.offspring_count_fields CONTAINS special_conditions

litter.code_strategy
→ litter.record_fields CONTAINS litter_code
```

حدود 4.6:

```text
Expected Birth Date / Window / تعريف مبكر أو متأخر → Settings 6.6
مستوى Warning / Block عند البيانات غير المنطقية → Settings 6.2 عند الحاجة؛ المعادلة الحسابية نفسها تحسم في 4.6
نقل المواليد بين الأمهات / Foster Mother → 4.7
متابعة الرضاعة والنفوق أثناء الرضاعة → 4.7 / 4.13 حسب الحدث
الفطام وإنشاء/اعتماد Individual Animals → 4.8
طريقة توليد شكل Litter Code → Settings 6.2
قواعد توليد مهام متابعة البطن والفطام وإعادة التلقيح → Settings 6.7 / 6.8 / 6.12
تنفيذ Tasks → 4.17
تحليل الولادات وحجم البطن والأحياء والنافقين → Reports 5.4 / 5.7
```

---

## 7. Reports — حدود مختصرة

Reports مسؤولة عن فهم وتجميع ومقارنة الأحداث، وليس تعديل السجلات الأصلية.

ذات الصلة بالمرحلة الحالية:

```text
5.3 تقارير الخصوبة والتلقيح والحمل
5.4 تقارير الولادة والرضاعة والفطام
5.5 تقارير النمو والأوزان والتسمين
5.7 تحليل أداء الحيوانات الإنتاجية
5.10 الاتجاهات والمقارنات عبر الزمن
5.12 التنبيهات والإنذار المبكر
```

Targets / Thresholds / Periods / Severity Rules القابلة للضبط → Settings.

---

## 8. Settings — حدود مختصرة

Settings = القواعد القابلة للضبط، وليست مكانًا لكل Business Rule.

Open Requirements العامة:

- Scope: System / Farm / Barn / Profile.
- Defaults / Inheritance / Overrides.
- Effective Date / Versioning.
- Historical Settings Reference / Snapshot.
- Information / Warning / Block.
- Override Policy.
- Sensitive Record Correction + Audit.

للتلقيح والحمل والولادة خصوصًا:

```text
جاهزية الأنثى والذكر
العمر والوزن والقيود الصحية
التلقيح أثناء الرضاعة
عدد Mating Events والفاصل بينها
Male Usage Limits / Rest Periods
Kinship Rules
موعد Pregnancy Check ونطاقه
موعد Recheck
Reference Mating Date Rule
مدة الحمل وExpected Birth Window
موعد تجهيز Nest Box
متى تعتبر الولادة مبكرة/متأخرة
شكل وتكوين Litter Code عند جعله قابلًا للتهيئة
→ Settings 6.2 / 6.5 / 6.6 / 6.7 / 6.12
```

---

## 9. قواعد إنشاء الأسئلة وملفات الشرح

قبل أي Question Seeder جديد:

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

## 10. Question Orchestrators الحالية

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
PregnancyFollowUpQuestionsSeeder
BirthLitterQuestionsSeeder
```

Reports / Settings Orchestrators ما زالت بلا Question Seeders فعلية حتى يبدأ تصميمها.

---

## 11. حالة التنفيذ الحالية

```text
Architecture → IMPLEMENTED & VERIFIED
Question Creation → IN PROGRESS

3.1 Animal Identity → LOCAL SEED VERIFIED
3.2 Animal Source / Record Start → LOCAL SEED VERIFIED
3.3 Animal Pedigree / Family Tree → LOCAL SEED VERIFIED
3.4 Initial Herd Setup → LOCAL SEED VERIFIED
3.5 Production Herd / Groups → IMPLEMENTED ON GITHUB / WAITING LOCAL SEED

4.1 Animal Intake / Re-entry → IMPLEMENTED ON GITHUB / WAITING LOCAL SEED
4.2 Housing / Movement / Vacating / Occupancy → IMPLEMENTED ON GITHUB / WAITING LOCAL SEED
4.3 Operational Weight / Measurements → IMPLEMENTED ON GITHUB / WAITING LOCAL SEED
4.4 Mating / Attempts → IMPLEMENTED ON GITHUB / WAITING LOCAL SEED
4.5 Pregnancy Check / Follow-up / Birth Preparation → IMPLEMENTED ON GITHUB / WAITING LOCAL SEED
4.6 Birth Registration / Litter Creation → IMPLEMENTED ON GITHUB / WAITING LOCAL SEED
```

لتحديث البيئة المحلية دون فقد الإجابات:

```bash
git pull origin master
php artisan db:seed --class=QuestionnaireAnimalHerdQuestionsSeeder
php artisan db:seed --class=QuestionnaireWorkflowQuestionsSeeder
```

إذا كان AnimalHerd قد تم Seed له بالفعل، يشغل Workflow Seeder فقط.

**التالي:**

`4.7 الرضاعة ومتابعة البطن وتداخل دورات الأم`

---

## 12. قاعدة GitHub الإلزامية

المستودع افتراضيًا **READ ONLY**.

أي تعديل / إنشاء / حذف / Commit / Branch / PR / Seeder يحتاج طلبًا صريحًا ومباشرًا من المستخدم في نفس السياق.

وجود الصلاحية التقنية لا يعني وجود إذن بالتعديل.

`تصور_مشروع_الارانب.md` Reference Only ولا يعدل إلا بطلب صريح.

---

## 13. المبدأ الأساسي

هذا المشروع هو:

**أداة تحليل متطلبات قبل التطوير**

وليس:

**نظام إدارة المزرعة النهائي.**

الهدف تقليل الافتراضات وتحويل الإجابات إلى Requirements قابلة للتنفيذ مع الحفاظ على التاريخ والاتساق وعدم الاعتماد على الذاكرة وحدها.
