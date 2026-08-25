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
- `أسباب النقل` و`أسباب النفوق` و`أسباب الاستبعاد` و`أسباب الخروج` لا يعاد تعريف قيمها داخل Workflow.
- `ExclusionReason` يشرح لماذا حدث الاستبعاد؛ `Exclusion Decision` يسجل الحيوان والوقت والمسار والمصير التالي؛ `ExitReason` يخص الخروج الفعلي من المزرعة.
- `Excluded ≠ Exited`.
- `الأغراض الإنتاجية` تعرف في Master Data؛ Workflow الإحلال يقرر فقط هل يشير حدث الاعتماد إلى الغرض المرجعي ولا يعيد تعريف قيمه.

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

الإشغال Derived من الحركات؛ السعة/Housing Rules→Settings؛ أسباب النقل→Master Data؛ تجهيز/صيانة الموقع→4.16.

### 4.3 Operational Weight / Measurements — IMPLEMENTED / WAITING LOCAL SEED

Seeder: `Questions/Workflow/OperationalMeasurementsQuestionsSeeder.php` — **9 أسئلة**.

```text
الوزن الفعلي → 4.3
موعد/دورية/Target/Threshold → Settings
منحنيات النمو والمقارنات → Reports
```

### 4.4 Mating / Attempts — IMPLEMENTED / WAITING LOCAL SEED

Seeder: `Questions/Workflow/MatingAttemptsQuestionsSeeder.php` — **12 سؤالًا**.

```text
Mating Event ≠ Mating Attempt ≠ Reproductive Cycle
```

Assigned Male→3.5؛ Actual Mating→4.4؛ قواعد الجاهزية/التكرار/Kinship→Settings؛ Pregnancy Check→4.5؛ التحليل→Reports.

### 4.5 Pregnancy Check / Follow-up / Birth Preparation — IMPLEMENTED / WAITING LOCAL SEED

Seeder: `Questions/Workflow/PregnancyFollowUpQuestionsSeeder.php` — **12 سؤالًا**.

Pregnancy Check المنفذ ≠ Task غير منفذة؛ يمكن أكثر من Check داخل Attempt؛ دخول Expected Birth Window لا ينشئ ولادة؛ Timing / Recheck / Gestation / Expected Window→Settings؛ Weight→4.3؛ Birth→4.6؛ Exceptions→4.14/4.13؛ Tasks→4.17.

### 4.6 Birth Registration / Litter Creation — IMPLEMENTED / WAITING LOCAL SEED

Seeder: `Questions/Workflow/BirthLitterQuestionsSeeder.php` — **11 سؤالًا**.

```text
Pregnancy / Cycle → Actual Birth Event → Litter Record → Lactation إذا وُجد مواليد أحياء
```

Birth Counts تاريخية؛ الحالات الخاصة لا تفسد معادلة الأحياء/النافقين؛ الولادة خارج النطاق تسجل بتاريخها الحقيقي؛ Foster→4.7؛ Weaning→4.8؛ Litter Code Pattern→Settings.

### 4.7 Lactation / Litter Follow-up / Overlapping Cycles — IMPLEMENTED / WAITING LOCAL SEED

Seeder: `Questions/Workflow/LactationOverlapQuestionsSeeder.php` — **12 سؤالًا**.

- Current Alive Count ينتج من الأحداث ولا يعيد كتابة Birth Counts.
- Foster Transfer يحافظ على Biological Mother / Original Litter حسب النموذج المعتمد.
- Temporary Preweaning Tracking يحسم في 4.7 ثم Final Origin Resolution في 4.8.
- Mating أثناء الرضاعة يستخدم 4.4؛ 4.7 يحدد تداخل الدورة مع البطن النشطة.

### 4.8 Weaning / Individual Tracking — IMPLEMENTED / WAITING LOCAL SEED

Seeder: `Questions/Workflow/WeaningIndividualTrackingQuestionsSeeder.php` — **12 سؤالًا**.

```text
Litter Tracking → Actual Weaning Event → Individual Animal Records → Growth / Sorting
```

- الفطام عملية تحول وليس Status فقط.
- اختلاف Expected Alive Count عن Actual Weaned Count لا يختفي بصمت.
- Partial Weaning سؤال صريح.
- كل مفطوم يصبح Animal Record مستقلًا.
- Weaning Weight→4.3 Integration؛ Housing→4.2 Integration.
- موعد/عمر/وزن الفطام وEarly Weaning والفصل بين الجنسين→Settings.

### 4.9 Growth / Sorting / Re-evaluation — IMPLEMENTED / WAITING LOCAL SEED

Seeder: `Questions/Workflow/GrowthSortingEvaluationQuestionsSeeder.php` — **10 أسئلة**.

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

المبدأ:

```text
Weaning / Individual Animal
→ Growth Tracking
→ Sorting / Evaluation Event(s)
→ Preliminary Direction / Re-evaluation
→ Actual Fate Decision in 4.10
```

- الأعمار مثل 45/70 يومًا و3 أشهر أمثلة؛ المراحل ومعاييرها→Settings 6.9.
- كل تقييم يحتفظ بتاريخه ولا يمسح التقييم السابق.
- Derived Growth Metrics مصدرها Weight Records في 4.3.
- نتائج 4.9 مبدئية: استمرار، ترشيح إحلال، توصية تسمين، توصية استبعاد، إعادة تقييم.
- Sorting Result لا ينفذ المصير؛ 4.10 يسجل القرار الفعلي.

### 4.10 Fate Decision / Exclusion from Path — IMPLEMENTED ON GITHUB / WAITING LOCAL SEED

Seeder:
`database/seeders/Questions/Workflow/FateExclusionQuestionsSeeder.php`

عدد الأسئلة: **11**.

```text
fate_decision.record_fields
fate_decision.source_reference_model
fate_decision.history_model
fate_decision.outcome_categories
fate_decision.downstream_transition_model
exclusion.scope_categories
exclusion.reason_reference_requirement
exclusion.other_reason_detail_policy
exclusion.next_destination_model
exclusion.next_destination_categories
exclusion.source_path_closure_model
```

المبدأ الوظيفي:

```text
Evaluation / Review
→ Actual Fate Decision
→ Downstream Transition
→ 4.11 Replacement OR 4.12 Fattening OR 4.15 Exit / other path
```

وقاعدة الاستبعاد:

```text
ExclusionReason = لماذا تم الاستبعاد؟ (Master Data)
Exclusion Decision = من أي مسار؟ متى؟ من قرر؟ وما المصير التالي؟ (4.10)
Exit = واقعة خروج فعلية من المزرعة (4.15)

Excluded ≠ Exited
```

القرارات التي تغطيها 4.10:

- Fate Decision Record يمكن أن يحتفظ بالحيوان ووقت القرار والعمر وWeight Reference وسياق القرار وSource Reference ونوع القرار والمنفذ والملاحظات حسب الإجابات.
- القرار يمكن أن يرتبط صراحة بالسجل/التقييم الذي أدى إليه أو يستنتج من Timeline حسب النموذج المعتمد.
- تاريخ قرارات المصير لا يمسح؛ يحسم السؤال Independent Decisions with Supersession vs Current Decision with Full Audit.
- Outcome Categories الفعلية تشمل مسار مرشح الإحلال، مسار التسمين، الاستبعاد من المسار الحالي، استمرار المتابعة/إعادة التقييم، أو مسار آخر موثق حسب الإجابات.
- القرار لا يكرر تنفيذ الـWorkflow التالي؛ `fate_decision.downstream_transition_model` يحسم Pending Transition vs Auto Start Context vs Manual Start.
- الاستبعاد يمكن أن يخرج الحيوان من Growth/Sorting أو Replacement Candidate أو Production/Breeding Path أو مسار تشغيلي آخر حسب الإجابات دون إنهاء هوية الحيوان أو وجوده تلقائيًا.
- قائمة أسباب الاستبعاد لا تعاد داخل 4.10؛ `exclusion.reason_reference_requirement` يحسم هل مرجع Master Data إلزامي أو اختياري مع تبرير.
- لأن Master Data تحتوي `سبب آخر`، تم إضافة سؤال صريح يحسم هل يحتاج Other Reason Detail إلزامي/اختياري/غير مطلوب؛ لا يتم افتراض Free Text تلقائيًا.
- `Excluded` لا يستخدم كنهاية غامضة؛ يجب أن يكون هناك Next Destination Model واضح حسب الإجابة.
- Next Destination Categories المدعومة للدراسة: Fattening، Planned Sale/Exit، Temporary Follow-up، Planned Final Exit، Other Documented Destination.
- `exclusion.source_path_closure_model` يحسم متى يغلق المسار المستبعد منه بالنسبة للحيوان مقارنة ببدء أو اكتمال الـWorkflow التالي.

Dependencies:

```text
لا توجد Dependencies داخل 4.10 حاليًا؛ الاستبعاد جزء أصيل من نطاق القسم وليس ميزة اختيارية جانبية.
```

حدود 4.10:

```text
Preliminary Sorting / Re-evaluation → 4.9
Actual Fate Decision / Exclusion Decision → 4.10
ExclusionReason definitions / values → Master Data
Actual Replacement Candidate Follow-up & Production Herd Approval → 4.11
Actual Fattening Start / Follow-up / Sale Readiness → 4.12
Health Events that may motivate a decision → 4.13
Actual Exit / Sale / Removal from Farm → 4.15
Actual Housing Movement required by a decision → 4.2
Task generation → Settings 6.12؛ Task Lifecycle → 4.17
Thresholds / automatic recommendation rules / approval criteria → Settings حسب المجال
Decision / Exclusion analytics → Reports 5.2 / 5.5 / 5.7 / 5.10
```

### 4.11 Replacement / Production Herd Approval — IMPLEMENTED ON GITHUB / WAITING LOCAL SEED

Seeder:
`database/seeders/Questions/Workflow/ReplacementApprovalQuestionsSeeder.php`

عدد الأسئلة: **11**.

```text
replacement.candidate_stage_policy
replacement.candidate_record_fields
replacement.candidate_followup_context_sources
replacement.candidate_nonapproval_handoff_model
replacement.approval_trigger_model
replacement.approval_record_fields
replacement.production_role_assignment_model
replacement.production_purpose_reference
replacement.group_assignment_integration_model
replacement.replaced_animal_link_model
replacement.approval_vs_mating_readiness
```

المبدأ الوظيفي:

```text
Existing Animal Record
→ Replacement Candidate / Follow-up عند انطباقه
→ Production Herd Approval Event
→ Production Herd Member

Candidate ≠ Production Herd Member
Production Herd Member ≠ Mating Ready
```

القرارات التي تغطيها 4.11:

- الإحلال لا ينشئ Animal Record جديدًا؛ نفس الحيوان يحتفظ بهويته وتاريخه منذ الميلاد/الدخول وحتى الاعتماد والإنتاج اللاحق.
- المصدر الوظيفي يسمح بإحلال داخلي وإحلال خارجي؛ `replacement.candidate_stage_policy` يحسم هل يمر كلاهما بمرحلة Candidate أم يمكن اعتماد الحيوان الخارجي المؤهل مباشرة بعد Intake وفق القواعد.
- Candidate Record يمكن أن يحتفظ بتاريخ الترشيح ومصدر الإحلال وSource Decision Reference والعمر وWeight Reference وSorting Context والهدف الإنتاجي وسبب الاختيار والمنفذ والملاحظات حسب الإجابات.
- متابعة المرشح لا تنشئ نسخًا مستقلة من الوزن أو النمو أو الصحة أو النسب؛ السؤال يحسم أي Canonical Contexts يجب عرضها وربطها أثناء المتابعة.
- إذا احتاج المرشح متابعة إضافية يمكن العودة إلى 4.9 حسب القرار، أما رفض الترشيح أو تغيير المصير فينتقل إلى 4.10 حسب النموذج المختار؛ لا نغلق Candidate بصمت مع تغيير Status فقط.
- شروط الاعتماد مثل العمر والوزن والصحة والنسب والفرز واختلاف شروط الذكر والأنثى تنتمي إلى Settings 6.9؛ 4.11 يحسم فقط كيفية تنفيذ Approval Event بعد تقييم هذه القواعد.
- Approval Record يمكن أن يحتفظ بتاريخ الاعتماد والعمر وWeight Reference والدور الإنتاجي وProduction Purpose Reference وCurrent Housing Reference وProduction Group Reference والحيوان المستبدل وسبب الاعتماد وRule Evaluation Reference والمنفذ والملاحظات حسب الإجابات.
- Production Role عند الاعتماد يمكن أن يكون Explicit أو Derived من Candidate Target أو Sex حسب القرار، دون إنشاء هوية جديدة.
- `replacement.production_purpose_reference` يقرر استخدام الغرض الإنتاجي المرجعي الموجود أصلًا في Master Data بدل إعادة تعريف قيمه داخل Workflow.
- Group Definition / membership policy موجودة في 3.5؛ 4.11 يحسم فقط Integration الفعلي عند إدخال الحيوان للقطيع، مع حفظ تاريخ العضوية وعدم اعتبار التخصيص تلقيحًا.
- المصدر يطرح ربط المرشح بالحيوان الذي يحل محله عند وجود إحلال مباشر؛ `replacement.replaced_animal_link_model` يحسم Optional / Required for Direct Replacement / No Direct Link.
- اعتماد الحيوان للقطيع لا يعني بالضرورة جاهزيته لأول تلقيح؛ `replacement.approval_vs_mating_readiness` يحسم الفصل الصريح بين Membership وبين Mating Readiness.

Dependencies:

```text
لا توجد Dependencies داخل 4.11 حاليًا؛ الأسئلة كلها قرارات أساسية في Lifecycle الإحلال والاعتماد.
```

حدود 4.11:

```text
Animal Identity / Source → 3.1 / 3.2
External Intake / Acceptance → 4.1
Growth / Sorting / Re-evaluation → 4.9
Actual Fate Decision to Replacement Candidate Path → 4.10
Candidate Follow-up / Production Herd Approval → 4.11
Production Group definition and structural membership policy → 3.5
Actual Group Assignment integration عند الاعتماد → 4.11 مع History؛ Actual Mating → 4.4
Actual Housing Movement عند الحاجة → 4.2؛ 4.11 يحتفظ بالمرجع/التكامل فقط
Production Purpose values → Master Data
Approval Criteria / Male-Female differences / Replacement Rules → Settings 6.9
Housing / herd organization eligibility that affects approval → Settings 6.4
Mating Readiness → Settings 6.5؛ Actual Mating → 4.4
Candidate or Replacement analytics / herd readiness → Reports 5.2 / 5.7 / 5.8 / 5.10
Task generation → Settings 6.12؛ Task Lifecycle → 4.17
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
6.4 قواعد التسكين وتنظيم القطيع والجاهزية
→ Housing Eligibility / Capacity / Group Rules / شروط اعتماد الحيوان داخل القطيع عند ارتباطها بالتنظيم

6.5 قواعد التلقيح والخصوبة والجاهزية التناسلية
→ Mating Readiness / Male Usage / Kinship / Mating Rules

6.9 قواعد النمو والوزن والفرز والإحلال
→ Growth / Sorting Stages
→ Weight / Growth Targets and Thresholds
→ Evaluation Criteria
→ اختلاف معايير الذكور والإناث
→ Replacement Candidate Rules
→ Final Production Herd Approval Criteria

6.12 قواعد المهام والتنبيهات والمواعيد والأولويات
→ Task Generation / Timing / Priority
```

باقي Pregnancy / Birth / Lactation / Weaning / Health / Fattening Rules موزعة على Subsections Settings المقابلة ولا تحول إلى Workflow Events.

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
```

لتحديث البيئة المحلية دون فقد الإجابات:

```bash
git pull origin master
php artisan db:seed --class=QuestionnaireAnimalHerdQuestionsSeeder
php artisan db:seed --class=QuestionnaireWorkflowQuestionsSeeder
```

إذا كان AnimalHerd قد تم Seed له بالفعل، يشغل Workflow Seeder فقط.

**التالي:**

`4.12 التسمين والجاهزية للبيع`

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
