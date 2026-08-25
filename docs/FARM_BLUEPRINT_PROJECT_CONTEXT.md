# Farm Blueprint — Project Context & Working Rules

## 1. تعريف المشروع والمراجع

المستودع: `hanyfreestyle/farm-blueprint.test`

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

المرجع الوظيفي الأساسي: `docs/تصور_مشروع_الارانب.md`.

يجب مراجعته قبل إنشاء أسئلة جديدة، مع الحفاظ على مصطلحاته ومنطقه وعدم اختراع Requirements غير مدعومة بالمصدر.

Architecture Review مكتملة ومعتمدة، وإعادة الهيكلة P0→P10 منفذة ومتحقق منها.

---

## 2. التقنية ومحرك الأسئلة والحفاظ على الإجابات

- Laravel 12
- PHP 8.2
- Filament 4
- MySQL
- Questionnaire Engine مخصص

Question Types الحالية فقط:

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

قواعد ثابتة:

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

الأعداد:

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
What IS it? → Data
What HAPPENED? → Workflow
What do we KNOW? → Reports / Analytics
What RULES control it? → Settings
```

---

## 4. حدود Master Data وFarm Structure

Master Data تشمل الأنشطة التشغيلية، الأغراض الإنتاجية، مؤشرات وبيانات السلالات، المستخدمين، أسباب النقل/النفوق/الاستبعاد/الخروج/تغيير الذكر، المحافظات، المدن، وأنظمة البيئة.

قواعد ثابتة:

```text
Reference Reason Values → Master Data
Actual use of reason → Workflow

ExclusionReason ≠ Exclusion Decision
ExitReason ≠ Exit Event
MortalityReason ≠ Mortality Event
Excluded ≠ Exited
```

- لا تعاد قوائم الأسباب داخل Workflow.
- `الأغراض الإنتاجية` تعرف في Master Data؛ Workflow يشير إليها فقط.
- Breed Master Data ≠ Animal Pedigree.

Farm Structure:

```text
Farm → Barn → Battery → Cage / Cell
```

- Cage يولد من Battery ولا Create مستقل له.
- Cage Code فريد وQR مرتبط بهويته.
- الهوية والموقع الهيكلي Immutable بعد التفعيل.
- Current Occupancy / Available Capacity مشتقان من الحركات.
- Housing Eligibility يعتمد على Settings والحالة والسعة والاستخدام والصيانة والتطهير.

```text
تعريف المواقع → Farm Structure
التسكين/النقل/الإخلاء الفعلي → 4.2
تشغيل/صيانة/تطهير الموقع → 4.16
قواعد السعة والإتاحة والتطهير → Settings
العرض والتحليل → Reports
```

سؤال مؤجل لا يمنع التقدم: إدارة الأنواع الفيزيائية للبطاريات.

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
- الموقع والوزن والجاهزية والحالة التشغيلية الحالية ليست حقولًا تاريخية ثابتة تعدل يدويًا.
- القطيع الافتتاحي يبدأ من أول معلومة موثوقة ولا يختلق تاريخًا سابقًا.
- Biological Mother تبقى منفصلة عن Foster Mother.

الحالة:

```text
3.1 Animal Identity → LOCAL SEED VERIFIED — 9
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
Group Targets / readiness → Settings
Actual group membership change → Workflow
Male Change Reasons → Master Data
Actual Mating → 4.4
Performance → Reports
```

---

## 6. القسم الرابع — Workflow

Workflow يجيب عن: **ماذا حدث؟ ومتى؟ ولماذا؟ ومن نفذه؟**

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

Seeder: `Questions/Workflow/AnimalIntakeQuestionsSeeder.php` — 11.

الحدود: source→3.2؛ identity→3.1؛ housing→4.2؛ weight→4.3؛ intake quarantine/acceptance rules→Settings؛ health→4.13؛ exit→4.15.

### 4.2 Housing / Movement / Vacating / Occupancy — IMPLEMENTED / WAITING LOCAL SEED

Seeder: `Questions/Workflow/HousingMovementQuestionsSeeder.php` — 12.

الإشغال Derived من الحركات؛ Housing Rules→Settings؛ أسباب النقل→Master Data؛ Site Maintenance→4.16.

### 4.3 Operational Weight / Measurements — IMPLEMENTED / WAITING LOCAL SEED

Seeder: `Questions/Workflow/OperationalMeasurementsQuestionsSeeder.php` — 9.

```text
Actual Weight → 4.3
Timing / Target / Threshold → Settings
Analysis → Reports
```

يدعم Intake / Pregnancy / Lactation / Weaning / Growth / Sorting / Replacement / Fattening، مع Batch Entry كوسيلة تشغيل فقط إذا اعتمدت الإجابة.

### 4.4 Mating / Attempts — IMPLEMENTED / WAITING LOCAL SEED

Seeder: `Questions/Workflow/MatingAttemptsQuestionsSeeder.php` — 12.

```text
Mating Event ≠ Mating Attempt ≠ Reproductive Cycle
```

Assigned Male→3.5؛ actual mating→4.4؛ readiness/repetition/Kinship→Settings؛ pregnancy check→4.5.

### 4.5 Pregnancy / Follow-up / Birth Preparation — IMPLEMENTED / WAITING LOCAL SEED

Seeder: `Questions/Workflow/PregnancyFollowUpQuestionsSeeder.php` — 12.

Pregnancy Check المنفذ ≠ Task غير منفذة؛ أكثر من Check ممكن داخل Attempt؛ Expected Birth Window لا ينشئ ولادة؛ timing/gestation/window→Settings؛ actual birth→4.6.

### 4.6 Birth / Litter Creation — IMPLEMENTED / WAITING LOCAL SEED

Seeder: `Questions/Workflow/BirthLitterQuestionsSeeder.php` — 11.

```text
Pregnancy / Cycle → Actual Birth Event → Litter Record → Lactation عند وجود Live Offspring
```

Birth Counts تاريخية؛ الولادة خارج النطاق تسجل بتاريخها الحقيقي؛ Stillborn عند الولادة→4.6؛ mortality بعد الولادة→4.13؛ Foster→4.7؛ Weaning→4.8.

### 4.7 Lactation / Litter Follow-up / Overlapping Cycles — IMPLEMENTED / WAITING LOCAL SEED

Seeder: `Questions/Workflow/LactationOverlapQuestionsSeeder.php` — 12.

- Current Alive Count ينتج من الأحداث ولا يعيد كتابة Birth Counts.
- Foster Transfer يحافظ على Biological Mother / Original Litter.
- Preweaning temporary tracking يحسم هنا ثم Final Identity Resolution في 4.8.
- Mortality بعد الولادة يمكن أن يستخدم Canonical Mortality Event في 4.13 ويرتبط بالبطن.
- Mating أثناء الرضاعة يستخدم 4.4؛ 4.7 يسجل علاقة التداخل فقط.

### 4.8 Weaning / Individual Tracking — IMPLEMENTED / WAITING LOCAL SEED

Seeder: `Questions/Workflow/WeaningIndividualTrackingQuestionsSeeder.php` — 12.

```text
Litter Tracking → Weaning Event → Individual Animal Records → Growth / Sorting
```

Partial Weaning سؤال صريح؛ كل مفطوم يصبح Animal Record مستقلًا؛ Weight→4.3؛ Housing→4.2؛ weaning criteria→Settings.

### 4.9 Growth / Sorting / Re-evaluation — IMPLEMENTED / WAITING LOCAL SEED

Seeder: `Questions/Workflow/GrowthSortingEvaluationQuestionsSeeder.php` — 10.

```text
Weaning / Individual Animal
→ Growth Tracking
→ Evaluation Event(s)
→ Preliminary Direction / Re-evaluation
→ Actual Fate Decision in 4.10
```

المراحل والأعمار والمعايير→Settings 6.9؛ Derived Growth من Weight History؛ كل تقييم يحتفظ بتاريخه؛ نتائج 4.9 مبدئية ولا تنفذ المصير.

### 4.10 Fate Decision / Exclusion — IMPLEMENTED / WAITING LOCAL SEED

Seeder: `Questions/Workflow/FateExclusionQuestionsSeeder.php` — 11.

```text
Evaluation / Review
→ Actual Fate Decision
→ Downstream Transition
→ Replacement / Fattening / Exit / Other Path
```

```text
ExclusionReason → Master Data
Exclusion Decision → 4.10
Actual Exit → 4.15
Excluded ≠ Exited
```

### 4.11 Replacement / Production Herd Approval — IMPLEMENTED / WAITING LOCAL SEED

Seeder: `Questions/Workflow/ReplacementApprovalQuestionsSeeder.php` — 11.

```text
Existing Animal Record
→ Candidate / Follow-up عند انطباقه
→ Production Herd Approval Event
→ Production Herd Member

Candidate ≠ Production Herd Member
Production Herd Member ≠ Mating Ready
```

نفس Animal Record يستمر؛ internal/external replacement مدعومان للدراسة؛ approval criteria→Settings 6.9؛ production purpose→Master Data؛ group definition→3.5؛ actual mating→4.4.

### 4.12 Fattening / Sale Readiness — IMPLEMENTED / WAITING LOCAL SEED

Seeder: `Questions/Workflow/FatteningSaleReadinessQuestionsSeeder.php` — 10.

```text
Fate Decision to Fattening
→ Fattening Start / Housing Integration
→ Weight & Growth Follow-up
→ Sale Readiness / Review
→ Planned Sale Transition
→ Actual Sale / Exit in 4.15
```

```text
Sale Readiness ≠ Sale / Exit Event
```

Target Age / Weight / Duration / Growth / Readiness Criteria→Settings 6.10؛ actual weight→4.3؛ actual housing→4.2؛ actual sale/exit→4.15؛ health event→4.13.

### 4.13 Health / Isolation / Recovery / Mortality — IMPLEMENTED / WAITING LOCAL SEED

Seeder: `Questions/Workflow/HealthIsolationMortalityQuestionsSeeder.php` — 13.

```text
Health Observation / Review
→ Health State + Operational Impact
→ Isolation عند الحاجة
→ Follow-up
→ Recovery / Re-evaluation / Return

أو

Actual Mortality Event
→ Presence / Occupancy / Active Path / Task integrations
```

قواعد وحدود:

- لا يتحول الـMVP إلى Veterinary Treatment Module كامل.
- Health History لا يمحى بتغير Current Health Summary.
- Health restrictions rules / Warning / Block / Override→Settings 6.11.
- Isolation Period/Decision→4.13؛ actual transfer to isolation housing→4.2.
- Recovery لا يعني Automatic Readiness بالضرورة؛ طريقة العودة يحسمها السؤال.
- Stillborn at Birth→4.6؛ Mortality after Birth→4.13.
- MortalityReason values→Master Data؛ actual Mortality Event→4.13.
- Mortality Stage يدرس كDerived from Timeline / Snapshot / Manual.
- Mortality Post-event integration يربط Presence / Occupancy / Litter / Active Paths / Tasks دون حذف التاريخ.

### 4.14 Exceptional Cases / Path Reconstruction — IMPLEMENTED ON GITHUB / WAITING LOCAL SEED

Seeder: `database/seeders/Questions/Workflow/ExceptionalPathRecoveryQuestionsSeeder.php` — **13 سؤالًا**.

```text
exception_handling.canonical_routing_model
exception_handling.unmodeled_case_policy
pregnancy_exception.outcome_categories
pregnancy_exception.record_fields
pregnancy_exception.history_integrity_model
workflow_reconstruction.context_detection_model
workflow_reconstruction.action_plan_model
workflow_reconstruction.task_integration_model
workflow_reconstruction.maternal_death_with_litter_model
missing_animal.lifecycle_model
missing_animal.record_fields
sex_correction.conflict_model
event_correction.correction_model
```

المبدأ الوظيفي:

```text
Normal Workflow
→ Exceptional Event / Domain Event
→ Detect affected active context
→ Preserve history
→ Determine invalid old steps/tasks
→ Determine next valid actions
→ Execute domain actions in their canonical sections
```

والقاعدة الأساسية:

```text
Exceptional Case = Event / Orchestration
وليس مجرد Status
```

حدود وقرارات 4.14:

- إذا كان الحدث له Canonical Event موجود بالفعل، مثل Mortality في 4.13 أو Foster Transfer في 4.7 أو Weaning في 4.8، فلا يجب افتراض إنشاء سجل منافس؛ `exception_handling.canonical_routing_model` يحسم Domain Event + Reconstruction Layer vs Generic Wrapper vs Domain-only.
- للحالات غير المغطاة بEvent متخصص، `exception_handling.unmodeled_case_policy` يحسم Structured Generic Exception vs Timeline Note vs Require Dedicated Event.
- Pregnancy Exceptions التشغيلية المدروسة من المصدر: Observed Abortion، Pregnancy Loss without observed abortion، False Pregnancy / Misdiagnosis، Health-related Pregnancy Termination، Other Documented Exception. التصنيف الطبي الدقيق يظل Open Requirement ولا يفترضه Seeder.
- Pregnancy Exception Record يمكن أن يحفظ Female / Cycle-Pregnancy / Outcome / Occurred-or-Discovered At / Gestational Age or Stage / Last Pregnancy Check Reference / Known Reason / Health Reference / User / Notes حسب الإجابات.
- لا يجب تعديل نتيجة Pregnancy Check تاريخية لإخفاء ما كان مسجلًا وقتها دون قرار صريح؛ `pregnancy_exception.history_integrity_model` يحسم Preserve Prior Check + Later Event vs Revision with Audit vs Replacement.
- Reconstruction يجب ألا يعتمد على Status واحد؛ `context_detection_model` يحسم استنتاج الحمل/الرضاعة/التسكين/المهام/المسارات النشطة من Timeline مع أو بدون إضافات يدوية.
- `action_plan_model` يحسم وجود Reconstruction Plan موثق مقابل Direct Auto-Rebuild أو Manual Rebuild.
- Task rules/generation→Settings 6.12؛ actual Task Lifecycle→4.17؛ 4.14 يحسم فقط Integration للمهام التي لم تعد صالحة بعد الاستثناء.
- نفوق الأم أثناء الرضاعة نفسه يسجل Canonically في 4.13؛ 4.14 يحسم إعادة بناء مسار البطن. المصدر يؤكد أن البطن لا تغلق تلقائيًا لمجرد نفوق الأم، بل يحتاج المواليد إجراء رعاية/نقل/فطام فعلي حسب القواعد.
- Complete Litter Loss لا يعاد تعريفه كسجل نفوق جديد هنا؛ Mortality→4.13 وCurrent Alive Count/Lactation End→4.7، بينما Reconstruction ينسق إغلاق المهام والخطوة التالية عند الحاجة.
- Missing Animal ≠ Death / Sale / Exit. السؤال يحسم Missing Event ثم Found Event على نفس Animal Record مقابل Status مؤقت أو Exit/Re-entry model.
- Missing Record يدرس discovered_at / last known housing / last movement / reporter / found_at / found location / recovery action / notes.
- Sex Correction: المصدر يسمح بالتصحيح مع Audit قبل وجود تعارض تاريخي، أما التعارض مع أحداث إنتاجية سابقة فيحتاج Exceptional Review حسب القرار. Permissions / general correction policy→Settings 6.2.
- Wrong Sensitive Event Correction: لا يفترض Hard Delete. `event_correction.correction_model` يحسم Linked Correction Event preserving original vs Edit with Full Audit vs Hard Delete؛ أي آثار تابعة يجب إعادة بنائها عند اعتماد ذلك.

Dependencies:

```text
لا توجد Dependencies داخل 4.14 حاليًا؛ القرارات كلها Core Decisions في Exception Handling / Reconstruction.
```

حدود 4.14:

```text
Actual Mortality / Health Event → 4.13
Actual Foster Transfer / Litter management → 4.7
Actual Weaning → 4.8
Pregnancy normal checks/follow-up → 4.5
Birth Event → 4.6
Actual Housing Movement → 4.2
Actual Fate/Exclusion → 4.10
Actual Exit/Re-entry → 4.15
Task Lifecycle → 4.17
Task generation/timing → Settings 6.12
Sensitive correction permissions / override / audit policy → Settings 6.2
Health/exception thresholds and configurable rules → Settings 6.11
Exception / abortion / loss analytics and alerts → Reports
```

---

## 7. Reports — حدود مختصرة

Reports = فهم وتجميع ومقارنة الأحداث، وليس تعديل السجلات الأصلية.

ذات الصلة:

```text
5.2 القطيع والجاهزية
5.3 الخصوبة والتلقيح والحمل
5.4 الولادة والرضاعة والفطام
5.5 النمو والأوزان والتسمين
5.6 الصحة والنفوق والعزل
5.7 أداء الحيوانات الإنتاجية
5.8 النسب والإحلال
5.10 الاتجاهات والمقارنات عبر الزمن
5.12 التنبيهات والإنذار المبكر
```

Targets / Thresholds / Periods / Severity Rules القابلة للضبط → Settings.

---

## 8. Settings — حدود مختصرة

Settings = القواعد والسياسات القابلة للضبط، وليست مكانًا لكل Business Rule.

Open Requirements العامة:

- Scope: System / Farm / Barn / Profile.
- Defaults / Inheritance / Overrides.
- Effective Date / Versioning.
- Historical Settings Reference / Snapshot.
- Information / Warning / Block.
- Override Policy.
- Sensitive Record Correction + Audit.

ذات الصلة:

```text
6.2 General Control / Override / Audit
→ Sensitive correction permissions / approval / audit rules

6.4 Housing / Herd Organization / Readiness
6.5 Mating / Fertility / Reproductive Readiness
6.9 Growth / Sorting / Replacement
6.10 Fattening / Sale Readiness
6.11 Health / Isolation / Mortality / Exceptional Cases
→ health restrictions / isolation-return rules / exception handling rules / thresholds

6.12 Tasks / Alerts / Timing / Priority
→ generation / timing / priority / rules after events

6.13 Report / KPI Targets and Thresholds
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
FateExclusionQuestionsSeeder
ReplacementApprovalQuestionsSeeder
FatteningSaleReadinessQuestionsSeeder
HealthIsolationMortalityQuestionsSeeder
ExceptionalPathRecoveryQuestionsSeeder
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
4.5 Pregnancy / Follow-up / Birth Preparation → IMPLEMENTED ON GITHUB / WAITING LOCAL SEED
4.6 Birth / Litter Creation → IMPLEMENTED ON GITHUB / WAITING LOCAL SEED
4.7 Lactation / Litter Follow-up / Overlapping Cycles → IMPLEMENTED ON GITHUB / WAITING LOCAL SEED
4.8 Weaning / Individual Tracking → IMPLEMENTED ON GITHUB / WAITING LOCAL SEED
4.9 Growth / Sorting / Re-evaluation → IMPLEMENTED ON GITHUB / WAITING LOCAL SEED
4.10 Fate Decision / Exclusion → IMPLEMENTED ON GITHUB / WAITING LOCAL SEED
4.11 Replacement / Production Herd Approval → IMPLEMENTED ON GITHUB / WAITING LOCAL SEED
4.12 Fattening / Sale Readiness → IMPLEMENTED ON GITHUB / WAITING LOCAL SEED
4.13 Health / Isolation / Recovery / Mortality → IMPLEMENTED ON GITHUB / WAITING LOCAL SEED
4.14 Exceptional Cases / Path Reconstruction → IMPLEMENTED ON GITHUB / WAITING LOCAL SEED
```

لتحديث البيئة المحلية دون فقد الإجابات:

```bash
git pull origin master
php artisan db:seed --class=QuestionnaireAnimalHerdQuestionsSeeder
php artisan db:seed --class=QuestionnaireWorkflowQuestionsSeeder
```

إذا كان AnimalHerd تم Seed له بالفعل، يشغل Workflow Seeder فقط.

**التالي:** `4.15 الخروج من المزرعة وإعادة الدخول`

---

## 12. قاعدة GitHub الإلزامية

المستودع افتراضيًا **READ ONLY**.

أي تعديل / إنشاء / حذف / Commit / Branch / PR / Seeder يحتاج طلبًا صريحًا ومباشرًا من المستخدم في نفس السياق.

وجود الصلاحية التقنية لا يعني وجود إذن بالتعديل.

`تصور_مشروع_الارانب.md` Reference Only ولا يعدل إلا بطلب صريح.

---

## 13. المبدأ الأساسي

هذا المشروع هو **أداة تحليل متطلبات قبل التطوير** وليس **نظام إدارة المزرعة النهائي**.

الهدف تقليل الافتراضات وتحويل الإجابات إلى Requirements قابلة للتنفيذ مع الحفاظ على التاريخ والاتساق وعدم الاعتماد على الذاكرة وحدها.
