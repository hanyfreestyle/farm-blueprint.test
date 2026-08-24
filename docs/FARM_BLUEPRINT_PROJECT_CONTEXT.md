# Farm Blueprint — Project Context & Working Rules

## 1. تعريف المشروع

المستودع الأساسي:

`hanyfreestyle/farm-blueprint.test`

هذا المشروع **ليس نظام إدارة مزرعة الأرانب النهائي**.

هو أداة Blueprint / دراسة تحليل متطلبات قبل التطوير، وتحول التصور الوظيفي إلى:

```text
أقسام
→ أقسام فرعية
→ أسئلة
→ إجابات
→ مراجعة
→ قرارات ومتطلبات
→ Software Requirements / Business Rules
→ Blueprint قابل للتنفيذ
```

الهدف من كل سؤال هو الوصول إلى Decision واضح قابل للتحويل إلى Requirement أو Rule، وليس جمع معلومات عامة فقط.

---

## 2. المراجع الحية وأولوية المصادر

### المرجع الأعلى للحالة الحالية

`docs/FARM_BLUEPRINT_PROJECT_CONTEXT.md`

يجب قراءته أولًا قبل أي عمل على المستودع، وتحديثه مع القرارات المعمارية والتنظيمية المعتمدة وانتقالات التنفيذ المهمة.

### سجل القرار المعماري

`docs/QUESTIONNAIRE_SECTION_ARCHITECTURE_REVIEW.md`

يوثق حدود Data / Workflow / Reports / Settings وأسباب نقل أو دمج الأقسام.

**الحالة:** Architecture Review مكتملة ومعتمدة.

### خطة إعادة الهيكلة

`docs/QUESTIONNAIRE_ARCHITECTURE_IMPLEMENTATION_PLAN.md`

```text
ARCHITECTURE_IMPLEMENTED_AND_VERIFIED
P0 → P10 = VERIFIED
```

تم التحقق محليًا سابقًا من Fresh Build بعد إعادة الهيكلة.

### المرجع الوظيفي الأساسي

`docs/تصور_مشروع_الارانب.md`

هو Source of Reference عند إنشاء أو مراجعة الأسئلة. لا يتم اختراع Requirement غير مدعوم بالمحتوى وكأنه حقيقة.

### أولوية المصادر عند التعارض

```text
أحدث قرار صريح معتمد من المستخدم
→ FARM_BLUEPRINT_PROJECT_CONTEXT.md
→ QUESTIONNAIRE_SECTION_ARCHITECTURE_REVIEW.md
→ Approved Answers / Questionnaire Guides
→ الكود الحالي
→ السجل التاريخي القديم
```

---

## 3. التقنية ومحرك الأسئلة

- Laravel 12
- PHP 8.2
- Filament 4
- MySQL
- Questionnaire Engine مخصص

`App\Enums\Questionnaire\QuestionType` يدعم:

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

`App\Enums\Questionnaire\QuestionDependencyOperator` يدعم فقط:

```text
EQUALS
CONTAINS
```

لا يتم اختراع Type أو Operator جديد قبل مراجعة الكود والحاجة الوظيفية واعتماده.

---

## 4. الهيكل الرئيسي — IMPLEMENTED & VERIFIED

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
تعريف الكيان / ما هو؟ → Data
ما الذي حدث فعليًا؟ → Workflow
ماذا نعرف أو نعرض من الأحداث؟ → Reports / Analytics
ما القواعد القابلة للضبط التي تتحكم فيما يحدث؟ → Settings
```

---

## 5. القسم الأول — إدارة البيانات الأساسية

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

- Breed Master Data منفصلة عن Animal Pedigree.
- قوائم الأسباب Master Data ولا يعاد تعريف قيمها داخل Workflow.
- `أسباب النقل` موجودة بالفعل وتشمل مثلًا: إعادة توزيع القطيع، انتقال للفطام، انتقال للتسمين، العزل، العودة من العزل، صيانة القفص، وبين البطاريات/العنابر/المزارع.
- تعريف القائمة / Lifecycle / Uniqueness / Retirement يبقى غالبًا مع Master Data، بينما القاعدة التشغيلية المتغيرة قد تصبح Setting.

---

## 6. القسم الثاني — هيكل المزرعة

```text
Farm
↓
Barn
↓
Battery
↓
Cage / Cell
```

Subsections:

```text
بيانات المزرعة
بيانات العنبر
بيانات البطارية
بيانات القفص / العين
```

قواعد معتمدة مهمة:

### Cage

- لا Create مستقل للقفص؛ يولد من تكوين Battery.
- لا Delete مستقل بعد دخوله التاريخ التشغيلي.
- Cage Code فريد على مستوى النظام.
- QR مرتبط بهوية القفص.
- الهوية والموقع الهيكلي Immutable بعد التفعيل.
- تغييرات الحالة تتم Actions + History.
- الإشغال الحالي والأماكن المتاحة ينتجان من حركات التسكين والنقل، لا من حقول يدوية.
- Housing Eligibility يجمع الحالة المحلية، إتاحة Battery/Barn، السعة، توافق الاستخدام، الصيانة، التطهير، وقواعد Settings.
- Timeline القفص يمكن أن يعرض التسكين والنقل والخروج والصيانة والتطهير وغيرها من مصادرها الأصلية.

### Battery

- تتبع Barn واحدًا.
- Battery Code فريد على مستوى النظام.
- بنيتها تولد الأقفاص التابعة لها.
- وجود تاريخ تشغيلي يقفل الهوية الهيكلية التاريخية.
- توقف/صيانة Battery يؤثر على إتاحة الأقفاص دون تغيير Local Status لكل Cage تلقائيًا.

### فصل المسؤوليات

```text
تعريف المواقع → Farm Structure
التسكين/النقل/الإخلاء والصيانة والتطهير الفعلي → Workflow
قواعد السعة والإتاحة والتطهير → Settings
عرض الإشغال والسعة → Reports
```

### سؤال مؤجل

> كيف يجب إدارة الأنواع الفيزيائية للبطاريات؟

لا يمنع استمرار بناء بقية الأسئلة.

---

## 7. القسم الثالث — بيانات الحيوان وتكوين القطيع

```text
3.1 بيانات وهوية الحيوان
3.2 مصدر الحيوان وبداية السجل
3.3 النسب وشجرة العائلة
3.4 القطيع الافتتاحي وتهيئة نقطة البداية
3.5 تكوين القطيع الإنتاجي وتنظيم المجموعات
```

قواعد عامة:

- Animal Record يستمر مع نفس الحيوان طوال حياته.
- الموقع الحالي والوزن الحالي والجاهزية والحالة الإنتاجية المشتقة ليست حقولًا ثابتة تعدل يدويًا.
- القطيع الافتتاحي يبدأ من أول معلومة موثوقة دون اختراع تاريخ سابق.

### 7.1 بيانات وهوية الحيوان — IMPLEMENTED & LOCAL SEED VERIFIED

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

الهوية لا تشمل الموقع الحالي أو الوزن الحالي أو الجاهزية أو الحالة الإنتاجية الحالية.

### 7.2 مصدر الحيوان وبداية السجل — IMPLEMENTED & LOCAL SEED VERIFIED

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

الحدود:

- المصدر يحدد من أين جاء الحيوان وأول معلومة موثوقة.
- الاستقبال والتقييم والوزن والحجر والتسكين الفعلي → Workflow.

### 7.3 النسب وشجرة العائلة — IMPLEMENTED & LOCAL SEED VERIFIED

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

الحدود:

- Breed ≠ Pedigree.
- نقل المولود لأم مرضعة لا يغير الأم البيولوجية.
- عدد الأجيال والتحليل والقرابة والتنبيهات → Reports / Settings.

### 7.4 القطيع الافتتاحي وتهيئة نقطة البداية — IMPLEMENTED & LOCAL SEED VERIFIED

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

3.4 يحدد Initialization / Go-live Point للمزرعة القائمة، ولا يحول اللقطة الافتتاحية إلى Status يدوي دائم.

### 7.5 تكوين القطيع الإنتاجي وتنظيم المجموعات — IMPLEMENTED ON GITHUB / WAITING LOCAL SEED

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
نموذج المجموعة وعلاقاتها → 3.5
العدد المستهدف للإناث لكل ذكر والجاهزية → Settings
تغيير الذكر أو نقل الأنثى فعليًا → Workflow
أسباب تغيير الذكر → Master Data
التلقيح الفعلي وإثبات الأبوة → Workflow / Pedigree
تقييم الأداء → Reports
```

---

## 8. القسم الرابع — الحركات ودورة التشغيل الفعلية

Workflow مسؤول عن:

> كل حدث أو إجراء فعلي يحدث بمرور الوقت ويغير سجل الحيوان أو البطن أو دورة الإنتاج أو موقع الإيواء أو المهمة التشغيلية.

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

قواعد فصل مهمة:

```text
تسجيل وزن فعلي → Workflow
موعد/مستهدف/حد الوزن → Settings
تحليل النمو → Reports
```

```text
قواعد توليد المهمة → Settings
تنفيذ/تأجيل/إلغاء/إغلاق المهمة → Workflow
عرض مهام اليوم والمتأخرات → Reports / Dashboard
```

### 8.1 استقبال الحيوان من الخارج وإعادة الإدخال — IMPLEMENTED ON GITHUB / WAITING LOCAL SEED

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

يغطي:

- الاستقبال الفردي والجماعي مع Individual Records.
- Weight at Entry كسجل وزن فعلي.
- Initial Evaluation ونتائجه.
- Observation / Quarantine Stage عند الحاجة.
- الفحوص أو الإجراءات الوقائية المنفذة فعليًا دون افتراض Veterinary Module كامل.
- Final Intake Approval.
- Re-entry مع الحفاظ على نفس Animal Record.

الحدود:

```text
بيانات المصدر → 3.2
الهوية → 3.1
موقع التسكين → 4.2
تفاصيل الوزن العامة → 4.3
مدة الحجر وإلزامه ومعايير القبول → Settings
المسار الصحي بعد التحويل → 4.13
الخروج بسبب الرفض → 4.15
الجاهزية الإنتاجية → Settings + Reports
```

الفرق بين 4.1 و4.15:

```text
4.1 → خطوات الاستقبال عند وصول الحيوان أو عودته
4.15 → حدث الخروج وربط العودة اللاحقة به تاريخيًا
```

### 8.2 التسكين والنقل والإخلاء وإدارة الإشغال — IMPLEMENTED ON GITHUB / WAITING LOCAL SEED

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

القرارات التي تغطيها المجموعة:

- أنواع حركات الموقع: تسكين أول، نقل بين الأقفاص، وإخلاء صريح عند الحاجة.
- بيانات الحركة: الحيوان، النوع، المصدر/الوجهة، وقت الحدوث، وقت التسجيل، السبب، المنفذ، الملاحظات.
- هل يكفي اختيار Cage ويستنتج النظام Battery/Barn/Farm تلقائيًا.
- نموذج النقل بين قفصين: Atomic Transfer أو Vacate + Housing مترابطان.
- منع أكثر من Active Occupancy لنفس الحيوان في نفس اللحظة.
- دعم النقل الجماعي.
- دعم نقل كل حيوانات القفص أو جزء محدد منها.
- الاحتفاظ بسجل حركة فردي لكل حيوان داخل Batch Movement.
- تحديد أنواع الحركات التي تحتاج سببًا صريحًا.
- ربط سبب النقل بقائمة `أسباب النقل` في Master Data بدل نص حر.
- الفرق بين Actual Event Time وRecorded At لأغراض التدقيق.
- دعم Bulk Relocation عند إخلاء Cage/Battery/Barn مشغول بسبب الإيقاف أو الصيانة.

Dependencies:

```text
housing_movement.transfer_atomicity
→ housing_movement.event_types CONTAINS cage_transfer

housing_movement.batch_transfer_scopes
housing_movement.batch_individual_history
→ housing_movement.batch_transfer_support EQUALS 1
```

الحدود المعمارية لـ4.2:

```text
تعريف Farm/Barn/Battery/Cage → Farm Structure
Housing Eligibility Factors نفسها → Farm Structure + Settings، ولا يعاد سؤالها هنا
السعة وقواعد الجمع وتجاوز السعة → Settings
الإشغال الحالي / Available Capacity → مشتق من الحركات، لا حقل يدوي
أسباب النقل وقيمها → Master Data؛ 4.2 يستخدمها فقط
التنظيف/التطهير وتجهيز القفص بعد الإخلاء → 4.16 + Settings
الصيانة نفسها → 4.16؛ نقل الحيوانات الناتج عنها → 4.2
الخروج النهائي من المزرعة → 4.15
عرض الإشغال وعجز السعة → Reports
```

المبدأ الوظيفي من المرجع:

```text
أرنب معتمد
→ اختيار موقع صالح
→ تسكين
→ إشغال مشتق
→ نقل عند الحاجة
→ إغلاق الإشغال السابق وحفظ التاريخ
→ إخلاء الموقع
→ تجهيز الموقع لاحقًا في Workflow المواقع
```

---

## 9. القسم الخامس — التقارير والتحليلات والتنبيهات ومؤشرات الأداء

الشجرة المعتمدة: **15 Subsection** وتشمل Dashboard، القطيع والجاهزية، الخصوبة والحمل، الولادة والرضاعة والفطام، النمو والتسمين، الصحة والنفوق، أداء الحيوانات، النسب والإحلال، الإشغال، الاتجاهات، الصفحات التحليلية، التنبيهات، جودة البيانات، KPIs، وخصائص التقارير.

الفصل:

```text
اكتشاف الحالة + العرض + تاريخ التنبيه → Reports
Threshold / Severity / Priority Rules → Settings
الإجراء المنفذ نتيجة التنبيه → Workflow
```

Open Requirement: صلاحيات ونطاق الاطلاع على التقارير لم يحسم بعد.

---

## 10. القسم السادس — الإعدادات وقواعد التشغيل

الشجرة المعتمدة: **13 Subsection**.

المبدأ:

```text
Structural / System Rule ≠ Operational Setting
```

Open Requirements الرئيسية:

- Scope: System / Farm / Barn / Profile.
- Reusable Profiles.
- Defaults / Inheritance / Overrides.
- Effective Date / Versioning.
- أثر تعديل الإعدادات على العمليات الجارية.
- Historical Settings Reference / Snapshot.
- صلاحيات واعتماد تغييرات Settings.
- Information / Warning / Block.
- Override Policy.
- Sensitive Record Correction.
- Minimum Audit Trail.
- الأعمار والأوزان والمدد والفواصل وThresholds وTargets.

لا يفترض المشروع Environmental Control Module أو Veterinary Treatment Module أو Sales/Financial Module كاملًا دون دعم وظيفي صريح.

---

## 11. Question Seeder Sync والحفاظ على الإجابات

الخدمة:

`app/Services/Questionnaire/QuestionSeederSyncService.php`

القواعد:

```text
Stable Section Record
+ Stable section_id
+ Stable seed_key
+ Stable option value
+ preserveAnswers = true
```

- لا يغير `seed_key` عند إعادة صياغة نفس القرار.
- لا تتغير `option.value` لمجرد تعديل Label.
- المزامنة تستخدم بدل الحذف وإعادة الإنشاء.
- تغيير غير متوافق مع إجابة محفوظة يجب أن يفشل بوضوح بدل حذف البيانات بصمت.
- كل Question Seeder جديد في المرحلة الحالية يستخدم `preserveAnswers = true`.
- لا يستخدم `migrate:refresh --seed` بصورة روتينية على قاعدة بيانات تحتوي إجابات نريد الحفاظ عليها.

---

## 12. قواعد إنشاء الأسئلة الجديدة

قبل أي Question Seeder جديد:

1. قراءة هذا الملف أولًا.
2. مراجعة الجزء المقابل من `تصور_مشروع_الارانب.md`.
3. مراجعة Architecture Review وحدود الـSubsection.
4. مراجعة الأسئلة والأقسام السابقة لمنع التكرار.
5. مراجعة Enums / Models / Engine قبل افتراض Type أو Operator جديد.
6. تحويل النقاط غير المحسومة فقط إلى أسئلة تنتج Decisions فعلية.
7. استخدام Stable `seed_key` وStable Option Values.
8. استخدام Dependencies عند وجود سبب وظيفي واضح.
9. تحديد `report_category` و`target_entity` حسب conventions الحالية.
10. عدم تحويل مثال أو اقتراح في المرجع إلى Requirement نهائي بدون سؤال/قرار.
11. عدم تضخيم الأسئلة أو إعادة سؤال قرار معماري معتمد.
12. الحفاظ على `preserveAnswers = true`.

عند إعداد Questionnaire Guide لاحقًا لا تعاد الدراسة من الصفر؛ يعتمد على:

```text
Project Context
+ Seeder النهائي
+ الإجابات المسجلة
+ مراجعة مرجعية سريعة للجزء المقابل من تصور_مشروع_الارانب.md
```

---

## 13. Question Orchestrators الحالية

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
```

شجرة الأسئلة الجديدة الحالية:

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
    └── HousingMovementQuestionsSeeder.php
```

Reports / Settings Orchestrators ما زالت بدون Question Seeders فعلية حتى يبدأ تصميمها.

---

## 14. حالة التنفيذ الحالية

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
```

لتحديث التطوير المحلي دون فقد الإجابات:

```bash
git pull origin master
php artisan db:seed --class=QuestionnaireAnimalHerdQuestionsSeeder
php artisan db:seed --class=QuestionnaireWorkflowQuestionsSeeder
```

إذا كان قسم AnimalHerd الحالي قد تم Seed له بالفعل، يشغل فقط Workflow Seeder عند الحاجة.

**الخطوة التالية بعد التحقق المحلي أو عند طلب الاستمرار:**

`4.3 الوزن والقياسات التشغيلية`

---

## 15. قاعدة GitHub الإلزامية

المستودع افتراضيًا:

**READ ONLY**

يسمح بالقراءة والبحث والتحليل والمراجعة والاقتراح فقط.

لا يتم تعديل أو إنشاء أو حذف ملف أو Commit أو Push أو Branch أو Pull Request أو Seeder إلا بطلب صريح ومباشر من المستخدم.

وجود صلاحية تقنية لا يعني وجود إذن بالتعديل.

`تصور_مشروع_الارانب.md` Reference Only ولا يعدل إلا بطلب صريح.

---

## 16. المبدأ الأساسي

هذا المشروع هو:

**أداة تحليل متطلبات قبل التطوير**

وليس:

**نظام إدارة المزرعة النهائي.**

كل قرار يجب أن يقلل الافتراضات قبل بناء النظام الحقيقي، مع الحفاظ على اتساق الوثائق والكود وعدم الاعتماد على الذاكرة أو المحادثة وحدها.
