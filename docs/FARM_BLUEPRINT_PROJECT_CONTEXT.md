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
- `أسباب النقل` و`أسباب النفوق` و`أسباب الاستبعاد` و`أسباب الخروج` لا يعاد تعريف قيمها داخل Workflow.
- `ExclusionReason` يشرح لماذا حدث الاستبعاد؛ `Exclusion Decision` يسجل الحيوان والوقت والمسار والمصير التالي؛ `ExitReason` يخص الخروج الفعلي من المزرعة.
- `Excluded ≠ Exited`.
- `الأغراض الإنتاجية` تعرف في Master Data؛ Workflow الإحلال يشير إلى الغرض المرجعي ولا يعيد تعريف قيمه.

Farm Structure:

```text
Farm → Barn → Battery → Cage / Cell
```

- Cage يولد من Battery ولا Create مستقل له.
- Cage Code فريد على مستوى النظام وQR مرتبط بهويته.
- الهوية والموقع الهيكلي Immutable بعد التفعيل.
- Current Occupancy / Available Capacity مشتقان من الحركات.
- Housing Eligibility يعتمد على الحالة والسعة والاستخدام والصيانة والتطهير وSettings.
- التاريخ التشغيلي يقفل الهوية الهيكلية التاريخية.

الفصل:

```text
تعريف المواقع → Farm Structure
التسكين/النقل/الإخلاء/الصيانة/التطهير الفعلي → Workflow
قواعد السعة والإتاحة والتطهير → Settings
عرض الإشغال والسعة → Reports
```

سؤال مؤجل لا يمنع التقدم: كيف يجب إدارة الأنواع الفيزيائية للبطاريات؟

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
نسبة الإناث لكل ذكر والجاهزية → Settings
تغيير عضو المجموعة فعليًا → Workflow
أسباب تغيير الذكر → Master Data
التلقيح الفعلي وإثبات الأبوة → Workflow / Pedigree
الأداء → Reports
```

---

## 6. القسم الرابع — Workflow

Workflow يجيب عن: ماذا حدث؟ ومتى؟ ولماذا؟ ومن نفذه؟

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

Seeder: `Questions/Workflow/AnimalIntakeQuestionsSeeder.php` — 11 سؤالًا.

الحدود: المصدر→3.2؛ الهوية→3.1؛ التسكين→4.2؛ الوزن→4.3؛ قواعد الحجر/القبول→Settings؛ الصحة→4.13؛ الخروج→4.15.

### 4.2 Housing / Movement / Vacating / Occupancy — IMPLEMENTED / WAITING LOCAL SEED

Seeder: `Questions/Workflow/HousingMovementQuestionsSeeder.php` — 12 سؤالًا.

الإشغال Derived من الحركات؛ السعة/Housing Rules→Settings؛ أسباب النقل→Master Data؛ تجهيز/صيانة الموقع→4.16.

### 4.3 Operational Weight / Measurements — IMPLEMENTED / WAITING LOCAL SEED

Seeder: `Questions/Workflow/OperationalMeasurementsQuestionsSeeder.php` — 9 أسئلة.

```text
Actual Weight → 4.3
Timing / Target / Threshold → Settings
Growth Analysis → Reports
```

سجل الوزن يدعم سياق `fattening` وجلسات إدخال جماعية مع سجل فردي عند اعتماد ذلك.

### 4.4 Mating / Attempts — IMPLEMENTED / WAITING LOCAL SEED

Seeder: `Questions/Workflow/MatingAttemptsQuestionsSeeder.php` — 12 سؤالًا.

```text
Mating Event ≠ Mating Attempt ≠ Reproductive Cycle
```

Assigned Male→3.5؛ Actual Mating→4.4؛ readiness/repetition/Kinship→Settings؛ Pregnancy Check→4.5.

### 4.5 Pregnancy Check / Follow-up / Birth Preparation — IMPLEMENTED / WAITING LOCAL SEED

Seeder: `Questions/Workflow/PregnancyFollowUpQuestionsSeeder.php` — 12 سؤالًا.

Pregnancy Check المنفذ ≠ Task غير منفذة؛ يمكن أكثر من Check داخل Attempt؛ دخول Expected Birth Window لا ينشئ ولادة؛ Timing/Gestation/Window→Settings؛ Birth→4.6.

### 4.6 Birth Registration / Litter Creation — IMPLEMENTED / WAITING LOCAL SEED

Seeder: `Questions/Workflow/BirthLitterQuestionsSeeder.php` — 11 سؤالًا.

```text
Pregnancy / Cycle → Actual Birth Event → Litter Record → Lactation عند وجود مواليد أحياء
```

Birth Counts تاريخية؛ الولادة خارج النطاق تسجل بتاريخها الحقيقي؛ Foster→4.7؛ Weaning→4.8؛ Litter Code Pattern→Settings.

### 4.7 Lactation / Litter Follow-up / Overlapping Cycles — IMPLEMENTED / WAITING LOCAL SEED

Seeder: `Questions/Workflow/LactationOverlapQuestionsSeeder.php` — 12 سؤالًا.

- Current Alive Count ينتج من الأحداث ولا يعيد كتابة Birth Counts.
- Foster Transfer يحافظ على Biological Mother / Original Litter حسب النموذج المعتمد.
- Temporary Preweaning Tracking يحسم في 4.7 ثم Final Origin Resolution في 4.8.
- Mating أثناء الرضاعة يستخدم 4.4؛ 4.7 يحدد تداخل الدورة مع البطن النشطة.

### 4.8 Weaning / Individual Tracking — IMPLEMENTED / WAITING LOCAL SEED

Seeder: `Questions/Workflow/WeaningIndividualTrackingQuestionsSeeder.php` — 12 سؤالًا.

```text
Litter Tracking → Actual Weaning Event → Individual Animal Records → Growth / Sorting
```

الفطام عملية تحول؛ Partial Weaning سؤال صريح؛ كل مفطوم يصبح Animal Record مستقلًا؛ Weight→4.3 Integration؛ Housing→4.2 Integration؛ شروط الفطام→Settings.

### 4.9 Growth / Sorting / Re-evaluation — IMPLEMENTED / WAITING LOCAL SEED

Seeder: `Questions/Workflow/GrowthSortingEvaluationQuestionsSeeder.php` — 10 أسئلة.

```text
Weaning / Individual Animal
→ Growth Tracking
→ Sorting / Evaluation Event(s)
→ Preliminary Direction / Re-evaluation
→ Actual Fate Decision in 4.10
```

- الأعمار مثل 45/70 يومًا و3 أشهر أمثلة؛ المراحل ومعاييرها→Settings 6.9.
- كل تقييم يحتفظ بتاريخه.
- Derived Growth Metrics مصدرها Weight Records في 4.3.
- نتائج 4.9 مبدئية؛ 4.10 يسجل القرار الفعلي.

### 4.10 Fate Decision / Exclusion from Path — IMPLEMENTED / WAITING LOCAL SEED

Seeder: `Questions/Workflow/FateExclusionQuestionsSeeder.php` — 11 سؤالًا.

```text
Evaluation / Review
→ Actual Fate Decision
→ Downstream Transition
→ 4.11 Replacement OR 4.12 Fattening OR 4.15 Exit / other path
```

```text
ExclusionReason = لماذا؟ → Master Data
Exclusion Decision = من أي مسار؟ ومتى؟ وما التالي؟ → 4.10
Exit = خروج فعلي من المزرعة → 4.15
Excluded ≠ Exited
```

### 4.11 Replacement / Production Herd Approval — IMPLEMENTED / WAITING LOCAL SEED

Seeder: `Questions/Workflow/ReplacementApprovalQuestionsSeeder.php` — 11 سؤالًا.

```text
Existing Animal Record
→ Replacement Candidate / Follow-up عند انطباقه
→ Production Herd Approval Event
→ Production Herd Member

Candidate ≠ Production Herd Member
Production Herd Member ≠ Mating Ready
```

- نفس Animal Record يستمر؛ لا تنشأ هوية جديدة.
- الإحلال قد يكون داخليًا أو خارجيًا.
- شروط الاعتماد وعلاقة اختلاف الذكر/الأنثى→Settings 6.9.
- Production Purpose values→Master Data.
- Group Definition→3.5؛ Group Assignment Integration عند الاعتماد→4.11؛ Actual Mating→4.4.
- ربط الحيوان المستبدل ممكن عند الإحلال المباشر حسب الإجابة.

### 4.12 Fattening / Sale Readiness — IMPLEMENTED ON GITHUB / WAITING LOCAL SEED

Seeder: `database/seeders/Questions/Workflow/FatteningSaleReadinessQuestionsSeeder.php` — **10 أسئلة**.

```text
fattening.entry_boundary_model
fattening.start_record_fields
fattening.individual_tracking_model
fattening.progress_context_sources
fattening.readiness_evaluation_model
fattening.readiness_outcome_categories
fattening.target_miss_review_outcomes
fattening.review_record_fields
fattening.sale_handoff_model
fattening.path_closure_model
```

المبدأ الوظيفي:

```text
Fate Decision to Fattening
→ Fattening Start / Housing Integration
→ Periodic Weight & Growth Follow-up
→ Sale Readiness / Review
→ Planned Sale Transition
→ Actual Sale / Exit in 4.15
```

والقاعدة الأساسية:

```text
Sale Readiness ≠ Sale / Exit Event
```

القرارات التي تغطيها 4.12:

- `fattening.entry_boundary_model` يحسم هل يبدأ مسار التسمين عند Fate Decision أو بعد Housing Movement إلى موقع تسمين صالح أو بحدث Start مستقل.
- Start Record يمكن أن يحتفظ بالحيوان ووقت البداية والعمر وStart Weight Reference وSource Fate Decision وسبب التحويل وHousing Reference والمنفذ والملاحظات حسب الإجابات.
- المصدر يؤكد أن Group Fattening لا يلغي Individual Tracking؛ `fattening.individual_tracking_model` يحسم مستوى السجل مع السماح بBatch Sessions للتشغيل فقط عند اعتمادها.
- Progress Context يمكن أن يعرض العمر وStart/Latest Weight وWeight History وDerived Growth Metrics وFattening Duration وHealth Context وآخر Review، دون نسخ مصادرها الأصلية.
- شروط العمر/الوزن/النمو/الصحة/المدة للجاهزية لا تعرف في Workflow؛ `fattening.readiness_evaluation_model` يحسم فقط Derived vs Derived+Review vs Manual Readiness Model.
- Readiness Outcomes المدروسة من المصدر: Not Ready، Approaching Target، Ready for Sale، Target Age/Duration Exceeded، Growth/Weight Below Target.
- عدم الوصول للهدف لا يخرج الحيوان تلقائيًا؛ يمكن أن تنتج المراجعة Continue Fattening أو Plan Sale at Current State أو Health Review أو Fate/Exclusion Change أو Re-evaluation أو Other Documented Decision حسب الإجابات.
- Review Record يمكن أن يحفظ Trigger وWeight Reference وEvaluation Context Snapshot والنتيجة والمنفذ والملاحظات لأغراض التاريخ والتدقيق.
- عند الجاهزية أو اعتماد البيع بالوضع الحالي، `fattening.sale_handoff_model` يحسم طريقة تسليم الحالة إلى 4.15 دون إنشاء Exit داخل 4.12.
- `fattening.path_closure_model` يحسم هل يبقى Fattening Period مفتوحًا حتى Actual Terminal Event أم يغلق في Boundary أسبق حسب الإجابة؛ المصدر يفضل عدم اعتبار readiness نفسها خروجًا.

Dependencies:

```text
لا توجد Dependencies داخل 4.12 حاليًا؛ الأسئلة كلها قرارات أساسية لمسار التسمين والجاهزية.
```

حدود 4.12:

```text
Actual Fate Decision to Fattening → 4.10
Actual Housing Movement → 4.2
Actual Weight Record / Batch Weight Entry → 4.3
Fattening Start / Follow-up / Readiness Review → 4.12
Health Event / Health Review execution → 4.13
Actual Sale / Exit / Exit Weight / Farm Presence Change → 4.15
Actual Death → 4.13
Target Age / Target Weight / Minimum Sale Weight / Max Fattening Duration / Weight Frequency / Growth Target / Low Growth Threshold / readiness criteria → Settings 6.10
Task Generation / Timing → Settings 6.12؛ Task Lifecycle → 4.17
Fattening Growth / Sale Readiness / Duration Analytics → Reports 5.5 / 5.10 / 5.12
```

---

## 7. Reports — حدود مختصرة

Reports مسؤولة عن فهم وتجميع ومقارنة الأحداث، وليس تعديل السجلات الأصلية.

ذات الصلة بالمرحلة الحالية:

```text
5.2 تقارير القطيع والجاهزية
5.3 تقارير الخصوبة والتلقيح والحمل
5.4 تقارير الولادة والرضاعة والفطام
5.5 تقارير النمو والأوزان والتسمين
5.7 تحليل أداء الحيوانات الإنتاجية
5.8 تقارير النسب والإحلال
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

ذات الصلة بالمراحل الحالية:

```text
6.4 قواعد التسكين وتنظيم القطيع والجاهزية
→ Housing Eligibility / Capacity / Group Rules / Production Herd organizational approval rules

6.5 قواعد التلقيح والخصوبة والجاهزية التناسلية
→ Mating Readiness / Male Usage / Kinship / Mating Rules

6.9 قواعد النمو والوزن والفرز والإحلال
→ Growth / Sorting Stages / Targets / Evaluation Criteria / Replacement Approval Criteria

6.10 قواعد التسمين والجاهزية للبيع
→ Target Age / Target Weight / Minimum Sale Weight
→ Max Fattening Duration
→ Weight Frequency
→ Growth Target / Low Growth Threshold
→ Sale Readiness Criteria
→ rules for continuing or reviewing animals that miss targets

6.12 قواعد المهام والتنبيهات والمواعيد والأولويات
→ Task Generation / Timing / Priority
```

باقي Pregnancy / Birth / Lactation / Weaning / Health Rules موزعة على Subsections Settings المقابلة ولا تحول إلى Workflow Events.

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
4.10 Fate Decision / Exclusion from Path → IMPLEMENTED ON GITHUB / WAITING LOCAL SEED
4.11 Replacement / Production Herd Approval → IMPLEMENTED ON GITHUB / WAITING LOCAL SEED
4.12 Fattening / Sale Readiness → IMPLEMENTED ON GITHUB / WAITING LOCAL SEED
```

لتحديث البيئة المحلية دون فقد الإجابات:

```bash
git pull origin master
php artisan db:seed --class=QuestionnaireAnimalHerdQuestionsSeeder
php artisan db:seed --class=QuestionnaireWorkflowQuestionsSeeder
```

إذا كان AnimalHerd قد تم Seed له بالفعل، يشغل Workflow Seeder فقط.

**التالي:** `4.13 الصحة والعزل والتعافي والنفوق`

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
