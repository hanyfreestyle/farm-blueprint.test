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

الحدود: source→3.2؛ identity→3.1؛ housing→4.2؛ weight→4.3؛ intake quarantine/acceptance rules→Settings؛ health→4.13؛ exit/re-entry history boundary→4.15.

`animal_intake.reentry_process_model` يحسم فقط خطوات الاستقبال التي يعاد تطبيقها على نفس Animal Record بعد العودة، ولا يحسم علاقة العودة بواقعة الخروج أو Presence History.

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

يدعم Intake / Pregnancy / Lactation / Weaning / Growth / Sorting / Replacement / Fattening، وأي وزن خروج فعلي يجب أن يحافظ على Canonical Weight History بدل إنشاء مصدر وزن متنافس.

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

Birth Counts تاريخية؛ Stillborn at Birth→4.6؛ mortality after birth→4.13؛ Foster→4.7؛ Weaning→4.8.

### 4.7 Lactation / Litter Follow-up / Overlapping Cycles — IMPLEMENTED / WAITING LOCAL SEED

Seeder: `Questions/Workflow/LactationOverlapQuestionsSeeder.php` — 12.

Current Alive Count ينتج من الأحداث؛ Foster يحافظ على Biological Origin؛ Preweaning temporary tracking هنا ثم Final Identity في 4.8؛ Mortality after birth→4.13؛ Mating أثناء الرضاعة يستخدم 4.4.

### 4.8 Weaning / Individual Tracking — IMPLEMENTED / WAITING LOCAL SEED

Seeder: `Questions/Workflow/WeaningIndividualTrackingQuestionsSeeder.php` — 12.

```text
Litter Tracking → Weaning Event → Individual Animal Records → Growth / Sorting
```

Partial Weaning سؤال صريح؛ كل مفطوم يصبح Animal Record مستقلًا؛ Weight→4.3؛ Housing→4.2؛ criteria→Settings.

### 4.9 Growth / Sorting / Re-evaluation — IMPLEMENTED / WAITING LOCAL SEED

Seeder: `Questions/Workflow/GrowthSortingEvaluationQuestionsSeeder.php` — 10.

```text
Weaning / Individual Animal
→ Growth Tracking
→ Evaluation Event(s)
→ Preliminary Direction / Re-evaluation
→ Actual Fate Decision in 4.10
```

المراحل والمعايير→Settings 6.9؛ Derived Growth من Weight History؛ كل تقييم يحتفظ بتاريخه؛ نتائج 4.9 مبدئية ولا تنفذ المصير.

### 4.10 Fate Decision / Exclusion — IMPLEMENTED / WAITING LOCAL SEED

Seeder: `Questions/Workflow/FateExclusionQuestionsSeeder.php` — 11.

```text
Evaluation / Review → Actual Fate Decision → Downstream Transition
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

Approval criteria→Settings 6.9؛ Production Purpose→Master Data؛ Group Definition→3.5؛ Actual Mating→4.4.

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

Targets / duration / growth / readiness criteria→Settings 6.10؛ actual weight→4.3؛ actual housing→4.2؛ actual sale/exit→4.15؛ health→4.13.

### 4.13 Health / Isolation / Recovery / Mortality — IMPLEMENTED / WAITING LOCAL SEED

Seeder: `Questions/Workflow/HealthIsolationMortalityQuestionsSeeder.php` — 13.

- MVP لا يصبح Veterinary Treatment Module كاملًا.
- Health History لا يمحى بتغير Current Summary.
- Isolation Period/Decision→4.13؛ actual housing transfer→4.2.
- Recovery لا يعني Automatic Readiness بالضرورة.
- Stillborn→4.6؛ Mortality after Birth→4.13.
- MortalityReason values→Master Data؛ Mortality Event→4.13.
- Mortality Post-event Integration يربط Presence / Occupancy / Litter / Active Paths / Tasks دون حذف التاريخ.

### 4.14 Exceptional Cases / Path Reconstruction — IMPLEMENTED / WAITING LOCAL SEED

Seeder: `Questions/Workflow/ExceptionalPathRecoveryQuestionsSeeder.php` — 13.

```text
Normal Workflow
→ Exceptional Event / Domain Event
→ Detect affected context
→ Preserve history
→ Determine invalid old steps/tasks
→ Determine next valid actions
→ Execute domain actions in canonical sections
```

- Canonical Domain Events لا تكرر بلا قرار.
- Pregnancy Exceptions: abortion / pregnancy loss / false pregnancy-misdiagnosis / health-related termination / other documented exception.
- Historical Pregnancy Check لا يمحى لمجرد اكتشاف نتيجة لاحقة.
- Maternal Death itself→4.13؛ Reconstruction of active litter→4.14؛ Foster→4.7؛ Early Weaning→4.8.
- Missing Animal ≠ Death / Sale / Exit.
- Sensitive wrong-event correction لا يفترض Hard Delete؛ permissions/override/audit→Settings 6.2.

### 4.15 Farm Exit / Re-entry — IMPLEMENTED ON GITHUB / WAITING LOCAL SEED

Seeder: `database/seeders/Questions/Workflow/FarmExitReentryQuestionsSeeder.php` — **11 سؤالًا**.

Stable keys:

```text
farm_exit.canonical_event_routing_model
farm_exit.record_fields
farm_exit.reason_reference_policy
farm_exit.active_context_handling_model
farm_exit.post_event_transition_model
farm_exit.batch_operation_model
sale_exit.commercial_data_scope
interfarm_transfer.boundary_model
animal_reentry.previous_exit_link_model
animal_reentry.presence_transition_model
animal_reentry.episode_history_model
```

المبدأ الوظيفي:

```text
Actual Farm Exit
→ Preserve Animal Record / Pedigree / Historical Links
→ Presence becomes outside according to chosen model
→ Housing / Active Paths / Tasks integrations

وعند عودة نفس الحيوان:

Verified Same Animal
→ Re-entry Event on same Animal Record
→ Link to previous exit/history according to chosen model
→ 4.1 Intake/Re-entry Process
→ Housing / Operational Return according to resulting events and rules
```

قواعد وحدود 4.15:

- خروج الحيوان **لا يحذف** Animal Record أو النسب أو الوزن أو التاريخ الإنتاجي؛ الحيوان الخارج يبقى قابلًا للعرض والتقارير لكنه لا يظهر ضمن التشغيل النشط حسب النموذج المعتمد.
- قائمة أسباب الخروج موجودة بالفعل في Master Data ولا تعاد هنا. القائمة الحالية تحتوي Sale / Mortality / Final Exclusion / Transfer to Another Farm / Internal Slaughter-Consumption / Lost / Other.
- لأن Mortality له Canonical Event في 4.13 وMissing/Lost له مسار في 4.14، `farm_exit.canonical_event_routing_model` يحسم Domain-only Derived Presence vs Linked Exit Event vs Always Exit Event، لمنع ازدواج الواقعة.
- Exit Record يدرس Animal / exited_at / age / Weight Reference / Source Housing / Operational Stage / ExitReason Reference / destination-recipient / Source Decision / performed_by / notes.
- `farm_exit.reason_reference_policy` يحسم إلزام ExitReason والتعامل مع Other، دون إعادة تعريف قيم القائمة.
- إذا كان للحيوان Pregnancy / Lactation / Fattening / Isolation أو مسار آخر نشط عند الخروج، 4.15 لا يختلق Rules السماح والمنع؛ يحسم Integration مع Resolution / 4.14 Reconstruction. Rules / Warning / Block / Override→Settings.
- بعد الخروج يجب الحفاظ على التاريخ مع تحديث Presence وإغلاق/تحرير Occupancy عبر 4.2 ومعالجة المسارات والمهام عبر أقسامها المختصة حسب النموذج المختار.
- المصدر يدعم Batch Sale / Batch Exit؛ `farm_exit.batch_operation_model` يحسم Batch operation with individual exit events vs Group-only vs Individual-only. التتبع الفردي لا يفترض فقده لمجرد العملية الجماعية.
- `sale_exit.commercial_data_scope` يحسم Operational-only vs Basic Optional Commercial Fields vs External Commercial Transaction Reference. Full Accounting/Finance Module غير مفترض داخل هذا Blueprint.
- وزن البيع/الخروج الفعلي، إذا سجل، يجب أن يحافظ على Canonical Weight History في 4.3؛ هل الوزن مطلوب ومتى يصبح إلزاميًا Rule قابل للضبط ويعالج في Settings المناسب.
- **Inter-farm Transfer داخل نفس النظام ليس بالضرورة Final Exit.** `interfarm_transfer.boundary_model` يحسم Standard Cross-farm Housing Movement in 4.2 vs Dedicated Inter-farm Transfer vs Linked Exit+Re-entry، مع الحفاظ على نفس Animal Record وتاريخه.
- Architecture Review معتمدة على أن الخروج يتطور ليشمل إعادة الدخول مع الحفاظ على الهوية.
- 4.15 لا يكرر سؤال 4.1 عن خطوات إعادة الاستقبال؛ `animal_reentry.previous_exit_link_model` يحسم فقط الربط التاريخي بواقعة الخروج السابقة.
- `animal_reentry.presence_transition_model` يفصل Physical Presence بعد العودة عن Operational/Production Readiness؛ الوصول الفعلي قد يسبق اعتماد 4.1 حسب الإجابة.
- `animal_reentry.episode_history_model` يحسم Append-only Presence Episodes vs Reopened Presence Record with Audit vs Current Presence derived from Exit/Re-entry Events. لا تنشأ هوية حيوان جديدة عند إثبات أنه نفس الحيوان.

Dependencies:

```text
لا توجد Dependencies داخل 4.15 حاليًا؛ الأسئلة كلها Core Decisions في Exit / Presence / Re-entry Boundary.
```

حدود 4.15:

```text
ExitReason values → Master Data
Actual Exit / Sale / Final removal from farm → 4.15
Sale Readiness / Planned Sale → 4.12
Mortality Event → 4.13
Missing / Found → 4.14
Active-path reconstruction بسبب الخروج → 4.14 عند الحاجة
Actual Housing close / new housing → 4.2
Actual exit weight → 4.3
Re-entry history / presence link → 4.15
Re-entry intake steps / evaluation / quarantine / approval → 4.1
Re-entry identity remains same Animal Record → 3.1 + approved architecture
Task Lifecycle → 4.17
Rules / Warning / Block / Override / required weight / restrictions → Settings
Exit / sales / churn / presence analytics → Reports
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
5.9 الإشغال والسعة والمواقع
5.10 الاتجاهات والمقارنات عبر الزمن
5.12 التنبيهات والإنذار المبكر
5.13 جودة البيانات والاستثناءات الإدارية
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
→ sensitive correction permissions / approval / audit / override

6.4 Housing / Herd Organization / Readiness
6.5 Mating / Fertility / Reproductive Readiness
6.9 Growth / Sorting / Replacement
6.10 Fattening / Sale Readiness
→ targets / required sale-readiness data / weight rules when configurable

6.11 Health / Isolation / Mortality / Exceptional Cases
6.12 Tasks / Alerts / Timing / Priority
6.13 Report / KPI Targets and Thresholds
```

قواعد السماح بالخروج من حالات نشطة، Requirement وزن البيع/الخروج، Warning/Block/Override، وقواعد العودة أو النقل بين المزارع عند جعلها قابلة للتهيئة تبقى Settings ولا تتحول إلى أحداث داخل 4.15.

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
FarmExitReentryQuestionsSeeder
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
4.15 Farm Exit / Re-entry → IMPLEMENTED ON GITHUB / WAITING LOCAL SEED
```

لتحديث البيئة المحلية دون فقد الإجابات:

```bash
git pull origin master
php artisan db:seed --class=QuestionnaireAnimalHerdQuestionsSeeder
php artisan db:seed --class=QuestionnaireWorkflowQuestionsSeeder
```

إذا كان AnimalHerd تم Seed له بالفعل، يشغل Workflow Seeder فقط.

**التالي:** `4.16 تشغيل وصيانة وتجهيز مواقع الإيواء`

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
