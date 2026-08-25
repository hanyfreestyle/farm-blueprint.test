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

أولوية المراجع:

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

## 2. التقنية ومحرك الأسئلة والحفاظ على الإجابات

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

الموديل: `App\Models\QuestionnaireQuestion`

خدمة المزامنة: `App\Services\Questionnaire\QuestionSeederSyncService`

قاعدة الحفاظ على الإجابات:

```text
Stable Section Record
+ Stable section_id
+ Stable seed_key
+ Stable option value
+ preserveAnswers = true
```

- لا يتغير `seed_key` بسبب إعادة صياغة نفس القرار.
- لا تتغير `option.value` لمجرد تغيير Label.
- أي تغيير يهدد إجابة محفوظة يجب أن يفشل بوضوح بدل حذفها بصمت.
- لا يستخدم `migrate:refresh --seed` روتينيًا على قاعدة تحتوي إجابات نريد الاحتفاظ بها.
- لا يستخدم Type أو Operator جديد دون مراجعة Enums / Models / Engine.

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

Master Data تشمل: الأنشطة التشغيلية، الأغراض الإنتاجية، مؤشرات السلالات، بيانات السلالات، المستخدمين وفريق التشغيل، أسباب النقل/النفوق/الاستبعاد/الخروج/تغيير الذكر، المحافظات، المدن، وأنظمة التهوية/التبريد/التدفئة.

قواعد ثابتة:

- Breed Master Data ≠ Animal Pedigree.
- قوائم الأسباب تعرف القيم المرجعية؛ الاستخدام الفعلي يسجل في Workflow.
- `أسباب النقل` و`أسباب النفوق` لا يعاد تعريفهما داخل Workflow.

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
- Biological Mother تبقى منفصلة عن Foster Mother.

الحالة:

```text
3.1 Animal Identity → LOCAL SEED VERIFIED — 9 questions
3.2 Animal Source / Record Start → LOCAL SEED VERIFIED — 7
3.3 Pedigree / Family Tree → LOCAL SEED VERIFIED — 10
3.4 Initial Herd Setup → LOCAL SEED VERIFIED — 11
3.5 Production Herd / Groups → IMPLEMENTED ON GITHUB / WAITING LOCAL SEED — 10
```

Seeders:

```text
Questions/AnimalHerd/AnimalIdentityQuestionsSeeder.php
Questions/AnimalHerd/AnimalSourceQuestionsSeeder.php
Questions/AnimalHerd/AnimalPedigreeQuestionsSeeder.php
Questions/AnimalHerd/InitialHerdSetupQuestionsSeeder.php
Questions/AnimalHerd/ProductionHerdGroupsQuestionsSeeder.php
```

حدود 3.5:

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

```text
الوزن الفعلي → 4.3
موعد/دورية/Target/Threshold → Settings
منحنيات النمو والمقارنات → Reports
```

### 4.4 Mating / Attempts — IMPLEMENTED / WAITING LOCAL SEED

Seeder: `Questions/Workflow/MatingAttemptsQuestionsSeeder.php` — **12 سؤالًا**.

المبدأ:

```text
Mating Event ≠ Mating Attempt ≠ Reproductive Cycle
```

Assigned Male→3.5؛ Actual Mating→4.4؛ الجاهزية/الأعمار/الأوزان/عدد التلقيحات والفاصل/Kinship Rules→Settings؛ Pregnancy Check→4.5؛ التحليل→Reports.

### 4.5 Pregnancy Check / Follow-up / Birth Preparation — IMPLEMENTED / WAITING LOCAL SEED

Seeder: `Questions/Workflow/PregnancyFollowUpQuestionsSeeder.php` — **12 سؤالًا**.

قواعد مهمة:

- Pregnancy Check المنفذ فعليًا ≠ Task لم تنفذ.
- يمكن وجود أكثر من Check داخل نفس Attempt.
- دخول Expected Birth Window لا ينشئ ولادة تلقائيًا.
- Timing / Recheck / Gestation / Expected Window → Settings.
- Weight الحمل الفعلي → 4.3؛ Birth→4.6؛ الحالات الاستثنائية→4.14/4.13؛ Tasks execution→4.17.

### 4.6 Birth Registration / Litter Creation — IMPLEMENTED / WAITING LOCAL SEED

Seeder: `Questions/Workflow/BirthLitterQuestionsSeeder.php` — **11 سؤالًا**.

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

```text
Pregnancy / Cycle → Actual Birth Event → Litter Record → Lactation إذا وُجد مواليد أحياء
```

- `Total Born / Live Born / Stillborn` أعداد ولادة تاريخية.
- الحالات الخاصة لا تفترض طرفًا ثالثًا في المعادلة إذا كانت قد تتداخل مع حي/نافق.
- الولادة خارج النطاق تسجل بتاريخها الحقيقي؛ لا يعدل Mating Date.
- Litter بدون مواليد أحياء لا يختفي تاريخيًا.
- Foster Transfer→4.7؛ Weaning/Individualization→4.8؛ Litter Code Pattern→Settings؛ تحليل الولادة→Reports.

### 4.7 Lactation / Litter Follow-up / Overlapping Cycles — IMPLEMENTED / WAITING LOCAL SEED

Seeder: `Questions/Workflow/LactationOverlapQuestionsSeeder.php` — **12 سؤالًا**.

```text
lactation.start_model
lactation.current_alive_count_model
lactation.followup_event_types
lactation.mortality_recording_model
lactation.foster_transfer_model
lactation.foster_transfer_scopes
lactation.foster_transfer_record_fields
lactation.transferred_offspring_tracking_model
lactation.foster_relationship_model
lactation.maternal_problem_outcomes
lactation.concurrent_cycle_link_model
lactation.end_model
```

- Current Alive Count لا يعيد كتابة Birth Counts؛ ينتج من الأحداث حسب الإجابة.
- Stillborn→4.6؛ Mortality بعد الولادة حدث جديد ويرتبط 4.13 حسب النموذج المعتمد.
- Foster Transfer يحافظ على Biological Mother / Original Litter إذا اعتمد نموذج الأصل الصحيح.
- Temporary Tracking للمواليد المنقولين قبل الفطام يحسم في 4.7 ثم Resolution إلى Final Animal Identity في 4.8.
- التلقيح أثناء الرضاعة يستخدم نفس 4.4؛ 4.7 يسجل فقط علاقة التداخل بالبطن النشطة.

Dependencies:

```text
lactation.foster_transfer_scopes
lactation.foster_transfer_record_fields
lactation.transferred_offspring_tracking_model
lactation.foster_relationship_model
→ lactation.foster_transfer_model EQUALS structured_foster_transfer
```

### 4.8 Weaning / Individual Tracking — IMPLEMENTED / WAITING LOCAL SEED

Seeder: `Questions/Workflow/WeaningIndividualTrackingQuestionsSeeder.php` — **12 سؤالًا**.

```text
weaning.operation_structure
weaning.event_record_fields
weaning.count_reconciliation_model
weaning.partial_weaning_model
weaning.individual_creation_model
weaning.inherited_animal_fields
weaning.preweaning_origin_resolution_model
weaning.sex_capture_model
weaning.weight_integration_model
weaning.housing_integration_model
weaning.summary_persistence_model
weaning.post_completion_transition_model
```

المبدأ:

```text
Litter Tracking
→ Actual Weaning Event
→ Individual Animal Records
→ Growth / Sorting
```

- الفطام ليس Status فقط؛ هو عملية تحول رئيسية.
- اختلاف Expected Alive Count عن Actual Weaned Count لا يختفي بصمت.
- Partial Weaning Requirement مفتوح ويعالج كسؤال صريح.
- كل مفطوم يصبح Animal Record مستقلًا بنفس قواعد الهوية في 3.1.
- البيانات الموثوقة تورث ولا يعاد إدخالها يدويًا.
- Foster Origin Resolution يتم عند تكوين الهوية النهائية.
- Weaning Weight→4.3 Integration؛ Housing→4.2 Integration.
- موعد/عمر/وزن الفطام وEarly Weaning والفصل بين الجنسين→Settings.

### 4.9 Growth / Sorting / Re-evaluation — IMPLEMENTED ON GITHUB / WAITING LOCAL SEED

Seeder:
`database/seeders/Questions/Workflow/GrowthSortingEvaluationQuestionsSeeder.php`

عدد الأسئلة: **10**.

```text
growth_sorting.program_entry_model
growth_sorting.stage_reference_model
growth_sorting.evaluation_history_model
growth_sorting.evaluation_record_fields
growth_sorting.evaluation_context_sources
growth_sorting.derived_growth_metric_model
growth_sorting.preliminary_result_categories
growth_sorting.result_to_fate_link_model
growth_sorting.reevaluation_model
growth_sorting.reevaluation_record_fields
```

المبدأ الوظيفي:

```text
Weaning / Individual Animal
→ Growth Tracking
→ Sorting / Evaluation Event(s)
→ Preliminary Direction / Re-evaluation
→ Actual Fate Decision in 4.10
```

القرارات التي تغطيها 4.9:

- حسم هل يبدأ Growth/Sorting تلقائيًا بعد الفطام أم بإجراء مستقل.
- الأعمار مثل 45 يومًا و70 يومًا و3 أشهر أمثلة وليست قيمًا ثابتة؛ حدث التقييم يراجع هل يرتبط بمرحلة معرفة في Settings أم بنموذج آخر.
- تاريخ الفرز لا يمسح عند تغير النتيجة لاحقًا؛ السؤال يحسم Independent Evaluation Events vs Current Record with Audit vs Overwrite.
- Evaluation Record يمكن أن يحتفظ بالحيوان والمرحلة والوقت والعمر ومراجع الأوزان ومؤشرات النمو والسياق الصحي والنسب والتقييم السابق والمنفذ والملاحظات.
- المعلومات التي تظهر أثناء التقييم يمكن أن تشمل Weight History / Derived Growth / Sex / Health / Pedigree / Parent Performance / Litter-Sibling Context / Previous Evaluations، بينما درجة تأثير كل عنصر ومعايير القبول تبقى Settings.
- Derived Growth Metrics مثل الزيادة اليومية مصدرها Weight Records في 4.3؛ السؤال يحسم Live Derivation vs Snapshot with Source References vs Manual Values.
- نتائج الفرز هنا **مبدئية** مثل الاستمرار، مرشح مبدئي للإحلال، توصية بالتسمين، توصية بالاستبعاد، أو إعادة تقييم.
- Source + Architecture يؤكدان أن Sorting Result لا تنفذ المصير وحدها؛ `growth_sorting.result_to_fate_link_model` يحسم فقط طريقة إنشاء Pending/Draft/No Automatic Fate Record في 4.10.
- انخفاض النمو مؤشر داخل التقييم ولا يعني Auto Exclusion بمجرده.
- Re-evaluation لا يجب أن تمحو نتيجة التقييم السابق؛ عند اختيارها يمكن حفظ السبب والموعد المقترح وما يجب متابعته وربطها بالتقييم المصدر.

Dependencies:

```text
growth_sorting.reevaluation_model
growth_sorting.reevaluation_record_fields
→ growth_sorting.preliminary_result_categories CONTAINS reevaluation
```

حدود 4.9:

```text
Actual Weight Record → 4.3
موعد/عمر كل Sorting Stage والأوزان المستهدفة والمعايير والThresholds → Settings 6.9
اختلاف معايير الذكر والأنثى ودرجة تأثير Health/Pedigree/Parent Performance → Settings 6.9
Task Generation ومواعيد الفرز/إعادة التقييم → Settings 6.12؛ Task Lifecycle → 4.17
Growth Curves / Sibling Comparison / Analytics → Reports 5.5 / 5.7 / 5.10
Preliminary Sorting Result / Re-evaluation → 4.9
Actual Fate Decision / Exclusion from a path → 4.10
Actual Replacement Approval → 4.11
Actual Fattening Workflow → 4.12
Actual Housing Movement الناتج عن القرار → 4.2
Health Events → 4.13
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

ذات الصلة بالمراحل الحالية:

```text
جاهزية الأنثى والذكر
التلقيح أثناء الرضاعة
عدد Mating Events والفاصل بينها
Male Usage Limits / Rest Periods
Kinship Rules
موعد Pregnancy Check / Recheck
Reference Mating Rule
مدة الحمل وExpected Birth Window
موعد تجهيز Nest Box
متى تعتبر الولادة مبكرة/متأخرة
شكل Litter Code
مواعيد Lactation Follow-up
موعد إعادة تلقيح الأم أثناء الرضاعة
شروط Foster Mother وحدود عدد المواليد لديها
موعد الفطام المستهدف
الحد الأدنى للعمر/الوزن أو Weight Requirement للفطام
قواعد Early Weaning
توقيت وقواعد الفصل بين الجنسين
قواعد Housing Eligibility / Capacity عند الفطام
تعريف مراحل Growth / Sorting وأعمارها أو شروط استحقاقها
Weight / Growth Targets وThresholds لكل مرحلة
Evaluation Criteria ودرجة تأثير كل مؤشر
اختلاف معايير الذكور والإناث
قواعد إعادة التقييم ومواعيدها عند الحاجة
→ Settings 6.4 / 6.5 / 6.6 / 6.7 / 6.8 / 6.9 / 6.12
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
LactationOverlapQuestionsSeeder
WeaningIndividualTrackingQuestionsSeeder
GrowthSortingEvaluationQuestionsSeeder
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
4.7 Lactation / Litter Follow-up / Overlapping Cycles → IMPLEMENTED ON GITHUB / WAITING LOCAL SEED
4.8 Weaning / Individual Tracking → IMPLEMENTED ON GITHUB / WAITING LOCAL SEED
4.9 Growth / Sorting / Re-evaluation → IMPLEMENTED ON GITHUB / WAITING LOCAL SEED
```

لتحديث البيئة المحلية دون فقد الإجابات:

```bash
git pull origin master
php artisan db:seed --class=QuestionnaireAnimalHerdQuestionsSeeder
php artisan db:seed --class=QuestionnaireWorkflowQuestionsSeeder
```

إذا كان AnimalHerd قد تم Seed له بالفعل، يشغل Workflow Seeder فقط.

**التالي:**

`4.10 تحديد المصير والاستبعاد من المسار`

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
