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

يجب تحديثه مع القرارات المعمارية والتنظيمية المعتمدة ومع انتقالات التنفيذ المهمة.

### سجل القرار المعماري

`docs/QUESTIONNAIRE_SECTION_ARCHITECTURE_REVIEW.md`

يوثق لماذا تم تقسيم الأقسام بهذه الطريقة وحدود Data / Workflow / Reports / Settings وما تم نقله أو دمجه أو إلغاؤه كقسم مستقل.

**الحالة:** Architecture Review مكتملة ومعتمدة.

### خطة تنفيذ إعادة الهيكلة

`docs/QUESTIONNAIRE_ARCHITECTURE_IMPLEMENTATION_PLAN.md`

**الحالة:**

```text
ARCHITECTURE_IMPLEMENTED_AND_VERIFIED
P0 → P10 = VERIFIED
```

نفذ المستخدم محليًا بنجاح:

```bash
git pull origin master
php artisan migrate:refresh --seed
```

وأكد نجاح التشغيل بدون Error في 2026-08-25.

### السجل التقني التاريخي

`docs/QUESTIONNAIRE_IMPLEMENTATION_PLAN.md`

يبقى سجلًا لتاريخ بناء أداة الاستبيان والمراحل التقنية السابقة، ولا يستبدل بخطة إعادة الهيكلة.

### المرجع الوظيفي الأساسي

`تصور_مشروع_الارانب.md`

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

## 3. التقنية المستخدمة

- Laravel 12
- PHP 8.2
- Filament 4
- MySQL
- Questionnaire Engine مخصص
- `spatie/laravel-translatable` عند الحاجة للمحتوى متعدد اللغات

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

الـSection Orchestrator:

`database/seeders/QuestionnaireSectionSeeder.php`

ويستدعي:

```text
QuestionnaireMasterDataSectionSeeder
QuestionnaireFarmStructureSectionSeeder
QuestionnaireAnimalHerdSectionSeeder
QuestionnaireWorkflowSectionSeeder
QuestionnaireReportsSectionSeeder
QuestionnaireSettingsSectionSeeder
```

Legacy Main Sections التالية لم تعد جزءًا من Fresh Seed:

```text
إعدادات التشغيل ودورة الإنتاج
تكوين وإدخال القطيع
التقارير والإشعارات ومؤشرات الأداء
```

---

## 5. قاعدة الفصل المعماري

```text
What IS it?
→ Master Data / Farm Structure / Animal Data

What HAPPENED?
→ Workflow

What do we KNOW from what happened?
→ Reports / Analytics

What RULES control what should happen?
→ Settings
```

بصياغة عملية:

```text
تعريف الكيان → Data
الحدث الفعلي → Workflow
التحليل أو العرض → Reports
القاعدة القابلة للضبط → Settings
```

هذه القاعدة مرجع إلزامي أثناء إنشاء الأسئلة لمنع التكرار بين الأقسام.

---

## 6. القسم الأول — إدارة البيانات الأساسية

```text
إدارة البيانات الأساسية
├── الأنشطة التشغيلية
├── الأغراض الإنتاجية
├── مؤشرات السلالات
├── بيانات السلالات
├── المستخدمون وفريق التشغيل
├── أسباب النقل
├── أسباب النفوق
├── أسباب الاستبعاد
├── أسباب الخروج
├── أسباب تغيير الذكر
├── المحافظات
├── المدن
├── أنظمة التهوية
├── أنظمة التبريد
└── أنظمة التدفئة
```

Seeder:

`database/seeders/Sections/QuestionnaireMasterDataSectionSeeder.php`

### قواعد مهمة

- Master Data لا تتحول تلقائيًا إلى Settings لمجرد أن بها Rules.
- تعريف القائمة / Lifecycle / Uniqueness / Initial Values / Retirement تبقى غالبًا مع Master Data.
- Operational Setting فقط هو المرشح للنقل إلى Settings إذا كان مطلوبًا أن يختلف حسب التشغيل أو النطاق.
- قوائم الأسباب مستقلة ويحافظ على قيمها التاريخية.
- Breed Master Data منفصلة عن Animal Pedigree.

---

## 7. القسم الثاني — هيكل المزرعة

```text
هيكل المزرعة
├── بيانات المزرعة
├── بيانات العنبر
├── بيانات البطارية
└── بيانات القفص / العين
```

العلاقة:

```text
Farm
↓
Barn
↓
Battery
↓
Cage / Cell
```

الفصل:

```text
تعريف Farm/Barn/Battery/Cage → Farm Structure
التسكين/النقل/الصيانة/التطهير → Workflow
قواعد السعة والإتاحة والتطهير → Settings
عرض الإشغال والسعة والحالة الحالية → Reports
```

### اتجاهات معتمدة مهمة

#### Cage

- لا Create مستقل للقفص.
- لا Delete مستقل للقفص.
- Cage Code فريد على مستوى النظام.
- QR Code يولد من الهوية/الكود.
- بعد التفعيل تكون الهوية والموقع الهيكلي Immutable.
- تغييرات الحالة تتم عبر Actions + History.
- الإشغال الحالي ينتج من حركات التسكين والنقل.
- Cage Master Identity منفصلة عن Cage Operational State.

#### Battery

- تتبع Barn واحدًا.
- Battery Code فريد على مستوى النظام.
- بنيتها تحدد الأقفاص التابعة لها.
- الأقفاص تولد بعد اكتمال البنية ومراجعتها.
- وجود تاريخ تشغيلي يقفل الهوية الهيكلية التاريخية.
- لا يعاد استخدام هوية/كود Cage تاريخي.
- توقف/صيانة Battery يؤثر على إتاحة الأقفاص دون تغيير حالاتهم المحلية تلقائيًا.

### سؤال مؤجل

> كيف يجب إدارة الأنواع الفيزيائية للبطاريات؟

يعود عند مراجعة/استكمال Farm Structure، ولا يمنع العمل الحالي على القسم الثالث.

---

## 8. القسم الثالث — بيانات الحيوان وتكوين القطيع

```text
بيانات الحيوان وتكوين القطيع
├── 3.1 بيانات وهوية الحيوان
├── 3.2 مصدر الحيوان وبداية السجل
├── 3.3 النسب وشجرة العائلة
├── 3.4 القطيع الافتتاحي وتهيئة نقطة البداية
└── 3.5 تكوين القطيع الإنتاجي وتنظيم المجموعات
```

قواعد مهمة:

- Animal Record يستمر مع نفس الحيوان طوال حياته.
- الموقع الحالي والوزن الحالي والجاهزية والحالة الإنتاجية المشتقة لا تعامل كحقول ثابتة تعدل يدويًا.
- Breed Master Data منفصلة عن Animal Pedigree.
- القطيع الافتتاحي يبدأ من أول معلومة موثوقة؛ لا يتم اختراع تاريخ غير معروف.

قرارات النقل:

```text
الدخول الأول والتقييم الفعلي → Workflow
التسكين وإدارة الإشغال → Workflow
الإحلال الداخلي كحدث فعلي → Workflow
قواعد الجاهزية → Settings
عرض الجاهزية وأسباب عدمها → Reports
```

### 8.1 — بيانات وهوية الحيوان — IMPLEMENTED & LOCAL SEED VERIFIED

Seeder:

`database/seeders/Questions/AnimalHerd/AnimalIdentityQuestionsSeeder.php`

عدد الأسئلة: **9**.

المفاتيح المستقرة:

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

الـDependencies:

```text
internal code questions
→ animal.identity_fields CONTAINS internal_code

external identifier questions
→ animal.identity_fields CONTAINS external_identifiers

unknown sex
→ animal.identity_fields CONTAINS sex

breed requirement
→ animal.identity_fields CONTAINS breed

birth information methods
→ animal.identity_fields CONTAINS birth_information
```

يستخدم:

```text
prune = true
preserveAnswers = true
```

### حدود 3.1

```text
مصدر الحيوان → 3.2
الأب / الأم / شجرة العائلة → 3.3
الوزن الفعلي → Workflow / Weight History
الموقع الحالي → مشتق من التسكين والنقل
الحالة الإنتاجية والصحية الحالية → مشتقة من Workflow
الجاهزية → Settings + Reports
شكل الكود القابل للتهيئة → Settings 6.2
```

### 8.2 — مصدر الحيوان وبداية السجل — IMPLEMENTED & LOCAL SEED VERIFIED

Seeder:

`database/seeders/Questions/AnimalHerd/AnimalSourceQuestionsSeeder.php`

عدد الأسئلة: **7**.

المفاتيح المستقرة:

```text
animal.source_origin_categories
animal.outside_source_types
animal.internal_source_derivation
animal.outside_source_fields
animal.interfarm_source_reference
animal.pre_entry_history_policy
animal.other_source_description_required
```

القرارات التي تغطيها المجموعة:

- دعم المصدر الداخلي مقابل القادم من خارج المزرعة الحالية.
- تمييز الشراء الخارجي / النقل من مزرعة أخرى / مصدر آخر.
- هل مصدر الحيوان المولود داخليًا يستنتج من سجل الولادة أم يدخل يدويًا.
- بيانات الجهة المصدر وكود الحيوان لدى المصدر وملاحظات المصدر.
- العلاقة بالمزرعة الأصلية عند النقل بين مزارع يديرها نفس النظام.
- سياسة المعلومات السابقة للدخول: لا اختلاق للتاريخ، مع حسم هل يسمح بمعلومات موثوقة سابقة للدخول.
- إلزام وصف «مصدر آخر» عند اختياره.

الـDependencies:

```text
animal.outside_source_types
animal.outside_source_fields
animal.pre_entry_history_policy
→ animal.source_origin_categories CONTAINS outside_current_farm

animal.internal_source_derivation
→ animal.source_origin_categories CONTAINS born_in_farm

animal.interfarm_source_reference
→ animal.outside_source_types CONTAINS inter_farm_transfer

animal.other_source_description_required
→ animal.outside_source_types CONTAINS other
```

يستخدم:

```text
prune = true
preserveAnswers = true
```

### حدود 3.2

لا يعاد فيه تسجيل أحداث الدخول الفعلية:

```text
تاريخ الوصول / الاستقبال
وزن الدخول
التقييم الأولي
الحجر أو العزل
التسكين
إعادة الدخول بعد خروج سابق
→ Workflow
```

والقطيع الموجود أصلًا عند بدء استخدام النظام وتاريخه الناقص يعالج في:

`3.4 القطيع الافتتاحي وتهيئة نقطة البداية`

### 8.3 — النسب وشجرة العائلة — IMPLEMENTED & LOCAL SEED VERIFIED

Seeder:

`database/seeders/Questions/AnimalHerd/AnimalPedigreeQuestionsSeeder.php`

عدد الأسئلة: **10**.

المفاتيح المستقرة:

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

القرارات التي تغطيها المجموعة:

- علاقات النسب المباشرة: الأب البيولوجي / الأم البيولوجية / البطن.
- هل نسب الناتج الداخلي يستنتج من سجلات الولادة ودورة الإنتاج أم يعاد إدخاله يدويًا.
- دعم النسب الكامل والجزئي وغير المعروف دون اختلاق بيانات.
- كيفية تمثيل والد أو سلف معروف لكنه غير موجود كحيوان داخل النظام.
- البيانات الممكن حفظها في سجل السلف الخارجي عند اعتماد هذا النموذج.
- مصادر توثيق النسب ودرجة موثوقية المعلومة.
- هل شجرة العائلة تبنى تلقائيًا من العلاقات أم تصان يدويًا.
- الفصل بين الأم البيولوجية والأم الحاضنة / المرضعة عند نقل المواليد.
- هل يحتاج المشروع مفهوم Genetic Line مستقلًا بجانب Breed وPedigree.
- كيفية التعامل مع تصنيف سلالة نسل أبوين من سلالات مختلفة دون فقد حقيقة سلالة الأب والأم.

Dependency الحالية:

```text
animal.external_ancestor_fields
→ animal.external_ancestor_strategy EQUALS external_ancestor_reference
```

يستخدم:

```text
prune = true
preserveAnswers = true
```

### حدود 3.3

لا تحسم داخله موضوعات مكانها الأقسام الأخرى:

```text
عدد الأجيال المعروضة في شجرة العائلة → Reports
تقارير الإخوة / الأبناء / انتشار الخطوط → Reports
درجات القرابة والتحليل قبل التلقيح → Settings / Reports حسب الوظيفة
Warning / Block / Override للقرابة → Settings 6.2 و6.5
طريقة تصحيح الأب أو الأم كسجل حساس → Settings 6.2
تقييم الأب والأم من نتائج الأبناء → Reports 5.7 و5.8
نقل المواليد بين الأمهات كحدث فعلي → Workflow
```

### 8.4 — القطيع الافتتاحي وتهيئة نقطة البداية — IMPLEMENTED & LOCAL SEED VERIFIED

Seeder:

`database/seeders/Questions/AnimalHerd/InitialHerdSetupQuestionsSeeder.php`

عدد الأسئلة: **11**.

المفاتيح المستقرة:

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

القرارات التي تغطيها المجموعة:

- ما المعلومات التشغيلية الحالية التي تثبت كنقطة بداية بدل تحويلها إلى حقول ثابتة في Animal Record.
- سياسة البيانات الناقصة عند إدخال حيوان موجود قبل النظام.
- الأوضاع التي يمكن أن يبدأ منها الحيوان: قطيع إنتاجي، دورة تناسلية قائمة، إحلال، نمو، تسمين، ملاحظة/عزل.
- نقاط البداية للأنثى التي تدخل النظام في منتصف دورة إنتاجية: انتظار جس، حمل، قرب ولادة، رضاعة، رضاعة مع إعادة تلقيح.
- هل يحتفظ النظام بلقطة البداية فقط أم يسمح بإضافة الحقائق السابقة الموثوقة مع تمييزها كبيانات قبل بدء النظام.
- أنواع الحقائق السابقة الممكن الاحتفاظ بها: تواريخ/أحداث تناسلية معروفة، عدد الولادات السابقة، الأبناء السابقون، الأوزان التاريخية.
- كيفية إظهار أن التاريخ قبل بدء النظام غير مكتمل حتى لا يفسر «غير معروف» على أنه «لم يحدث».
- مراجعات ما قبل الاعتماد: التسكين، عدم ازدواج الموقع، السعة، إتاحة الموقع، توافق الاستخدام، العزل، التنظيم، ونقص البيانات الحرج.
- هل التهيئة تمر بمسودة → مراجعة → اعتماد أم تدخل التشغيل مباشرة.
- هل يحفظ الرصيد الافتتاحي كخط أساس تاريخي محسوب من السجلات.
- هل اعتماد التهيئة يشغل تقييم المهام من الوضع الحالي، مع ترك قواعد المواعيد والأولوية لقسم Settings.

Dependencies:

```text
animal.opening_reproductive_contexts
→ animal.opening_starting_contexts CONTAINS female_active_reproductive_cycle

animal.opening_trusted_prior_fact_types
→ animal.opening_trusted_prior_history_strategy EQUALS snapshot_plus_trusted_prior_facts
```

يستخدم:

```text
prune = true
preserveAnswers = true
```

### حدود 3.4

```text
الهوية والسلالة والنسب → تستخدم نتائج 3.1 وMaster Data و3.3 ولا يعاد تعريفها هنا
التسكين والنقل كأحداث بعد بدء التشغيل → Workflow
حدود السعة وتوافق التسكين والجاهزية → Settings
تنفيذ المهمة وتأجيلها وإغلاقها → Workflow
مواعيد وأولويات توليد المهام → Settings
عرض الرصيد والجاهزية والتحليلات بعد التشغيل → Reports
```

3.4 يحدد **Initialization / Go-live Point** للمزرعة القائمة، ولا ينشئ تاريخًا غير معروف ولا يحول اللقطة الافتتاحية إلى Status يدوي دائم.

### 8.5 — تكوين القطيع الإنتاجي وتنظيم المجموعات — IMPLEMENTED ON GITHUB / WAITING LOCAL SEED

Seeder:

`database/seeders/Questions/AnimalHerd/ProductionHerdGroupsQuestionsSeeder.php`

عدد الأسئلة: **10**.

المفاتيح المستقرة:

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

القرارات التي تغطيها المجموعة:

- هل القطيع يدعم الإدارة الفردية فقط أم مفهوم المجموعات الإنتاجية أيضًا؛ ويمكن اختيار الطريقتين معًا.
- بيانات وعلاقات المجموعة: الاسم، الكود، الذكر الأساسي، الإناث، تاريخ التكوين، الحالة، الملاحظات.
- عند استخدام كود للمجموعة: طريقة إنشائه ونطاق فريدته.
- هل عضوية الأنثى في المجموعة اختيارية أو إلزامية، وهل يسمح بأكثر من مجموعة نشطة في الوقت نفسه.
- هل المجموعة تتطلب ذكرًا أساسيًا ثابتًا أم تسمح أن تكون بدونه أو لا تستخدم ذكرًا ثابتًا أصلًا.
- هل يمكن للذكر الأساسي الارتباط بأكثر من مجموعة نشطة.
- هل يوجد ذكر بديل ثابت اختياري أم يتم اختياره وقت الحاجة.
- حالات دورة حياة المجموعة: نشطة / متوقفة / تم حلها.
- الفصل بين تخصيص الذكر والإناث تنظيميًا وبين التلقيح الفعلي وإثبات الأبوة.
- هل يحتفظ النظام بالتكوين الحالي فقط أم بتاريخ كامل لتغير الذكر والإناث والحالة عبر الزمن.

Dependencies:

```text
أسئلة production_group العامة
→ production_herd.organization_methods CONTAINS production_groups

production_group.code_policy
→ production_group.record_fields CONTAINS group_code
```

يستخدم:

```text
prune = true
preserveAnswers = true
```

### حدود 3.5

```text
العدد المستهدف للإناث لكل ذكر والحد الأقصى والتنبيهات → Settings
جاهزية الذكر والأنثى للاستخدام الإنتاجي → Settings + Reports
فحص القرابة وقاعدة Warning / Block / Override → Settings / Reports
تغيير الذكر أو نقل أنثى بين المجموعات كحدث فعلي → Workflow
أسباب تغيير الذكر → Master Data موجودة بالفعل
التلقيح الفعلي والذكر المستخدم → Workflow
إثبات الأبوة → من سجل التلقيح الفعلي / Pedigree وليس من مجرد المجموعة
تقييم أداء المجموعة والأفراد → Reports
```

3.5 يحدد **نموذج تنظيم القطيع والكيان والعلاقات الأساسية فقط**، ولا يحول المجموعة إلى سجل تلقيح أو Settings Profile.

---

## 9. القسم الرابع — الحركات ودورة التشغيل الفعلية

Workflow مسؤول عن كل حدث أو إجراء فعلي يحدث بمرور الوقت ويغير سجل الحيوان أو البطن أو دورة الإنتاج أو موقع الإيواء أو المهمة التشغيلية.

الشجرة المعتمدة: 17 Subsection وتشمل الاستقبال، التسكين، الوزن، التلقيح، الحمل، الولادة، الرضاعة، الفطام، النمو، المصير، الإحلال، التسمين، الصحة، الحالات الاستثنائية، الخروج/إعادة الدخول، مواقع الإيواء، والمهام التشغيلية.

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

---

## 10. القسم الخامس — التقارير والتحليلات والتنبيهات ومؤشرات الأداء

الشجرة المعتمدة: 15 Subsection تشمل Dashboard، القطيع والجاهزية، الخصوبة والحمل، الولادة والرضاعة والفطام، النمو والتسمين، الصحة والنفوق، أداء الحيوانات، النسب والإحلال، الإشغال، الاتجاهات، الصفحات التحليلية، التنبيهات، جودة البيانات، KPIs، وخصائص التقارير.

الفصل:

```text
اكتشاف الحالة + عرض وتاريخ التنبيه → Reports
Threshold / Severity / Priority Rules → Settings
الإجراء المنفذ نتيجة التنبيه → Workflow
```

```text
ما KPI المطلوب وما الذي يقيسه → Reports
Target / Threshold / configurable period → Settings عند الحاجة
```

Open Requirement مهم: صلاحيات ونطاق الاطلاع على التقارير لم تحسم بعد.

---

## 11. القسم السادس — الإعدادات وقواعد التشغيل

الشجرة المعتمدة: 13 Subsection.

المبدأ الأساسي:

```text
Structural / System Rule ≠ Operational Setting
```

أي Rule من الأقسام 1–5 لا ينتقل إلى Settings إلا إذا كان مطلوبًا أن يكون قابلًا للضبط أو الاختلاف حسب نطاق التشغيل.

Open Requirements الرئيسية داخل Settings:

- Farm/Barn/Profile Scope النهائي.
- Reusable Profiles.
- Defaults / Inheritance.
- Overrides.
- Effective Date / Versioning.
- أثر تغيير Settings على العمليات الجارية.
- Historical Reference / Snapshot.
- صلاحيات واعتماد تغييرات Settings.
- Information / Warning / Block.
- Hard Constraints.
- Override Policy.
- Sensitive Record Correction.
- Minimum Audit Trail.
- الأعمار والأوزان والمدد والفواصل وThresholds وTargets.

ولا يفترض المشروع من تلقاء نفسه Environmental Control Module أو Veterinary Treatment Module أو Sales/Financial Module كاملًا.

---

## 12. قاعدة عدم تضخيم الأسئلة

Architecture Review أوسع من عدد الأسئلة النهائي.

عند إنشاء الأسئلة:

- كل سؤال يجب أن ينتج Decision فعلي.
- يجمع القرار الواحد في سؤال مركزي مناسب عند الإمكان.
- تستخدم Dependencies لإخفاء غير المنطبق.
- لا يعاد سؤال قرار معماري معتمد.
- لا يسأل ما يمكن استنتاجه من إجابة سابقة.
- لا تتحول كل نقطة معمارية إلى سؤال مستقل.

---

## 13. Question Seeders وOrchestrators

الـorchestrator العام:

`database/seeders/QuestionnaireQuestionsSeeder.php`

يستدعي:

```text
QuestionnaireMasterDataQuestionsSeeder
QuestionnaireFarmStructureQuestionsSeeder
QuestionnaireAnimalHerdQuestionsSeeder
QuestionnaireWorkflowQuestionsSeeder
QuestionnaireReportsQuestionsSeeder
QuestionnaireSettingsQuestionsSeeder
```

`QuestionnaireAnimalHerdQuestionsSeeder` يستدعي حاليًا:

```text
AnimalIdentityQuestionsSeeder
AnimalSourceQuestionsSeeder
AnimalPedigreeQuestionsSeeder
InitialHerdSetupQuestionsSeeder
ProductionHerdGroupsQuestionsSeeder
```

شجرة AnimalHerd الحالية:

```text
database/seeders/Questions/AnimalHerd/
├── AnimalIdentityQuestionsSeeder.php
├── AnimalSourceQuestionsSeeder.php
├── AnimalPedigreeQuestionsSeeder.php
├── InitialHerdSetupQuestionsSeeder.php
└── ProductionHerdGroupsQuestionsSeeder.php
```

Workflow / Reports / Settings Orchestrators ما زالت بدون Question Seeders فعلية حتى يبدأ تصميم كل قسم.

---

## 14. Question Types وDependencies الحالية

`App\Enums\Questionnaire\QuestionType` يدعم:

- `text`
- `textarea`
- `number`
- `date`
- `yes_no`
- `single_choice`
- `multi_choice`
- `select`

`App\Enums\Questionnaire\QuestionDependencyOperator` يدعم:

- `EQUALS`
- `CONTAINS`

لا يتم اختراع Type أو Dependency Operator جديد قبل مراجعة الكود والحاجة الوظيفية واعتماده.

---

## 15. Question Seeder Sync والحفاظ على الإجابات

الخدمة الموحدة:

`app/Services/Questionnaire/QuestionSeederSyncService.php`

المبادئ:

- لكل سؤال `seed_key` ثابت.
- هوية السؤال: `section_id + seed_key`.
- هوية Option: `question_id + value`.
- لا يتغير `seed_key` بسبب إعادة صياغة السؤال إذا ظل القرار نفسه.
- لا تتغير `option.value` بسبب تغيير Label فقط.
- تستخدم المزامنة بدل الحذف وإعادة الإنشاء العمياء.
- أي تغيير غير متوافق مع إجابة محفوظة يجب أن يفشل بوضوح بدل حذف البيانات بصمت.

المبدأ:

```text
Stable Section Record
+ Stable section_id
+ Stable seed_key
+ Stable option value
+ preserveAnswers = true
```

بعد بدء الإجابات المستقرة لا يستخدم `migrate:refresh --seed` بصورة روتينية على قاعدة البيانات المحتوية على الإجابات.

لإضافة Question Seeder جديد إلى قاعدة التطوير الحالية يفضل تشغيل Seeder المستهدف أو Orchestrator القسم بدل إعادة بناء القاعدة كلها متى كان ذلك كافيًا.

---

## 16. قواعد إنشاء الأسئلة الجديدة

قبل إنشاء أي Question Seeder جديد:

1. قراءة هذا الملف أولًا.
2. مراجعة الجزء المقابل من `تصور_مشروع_الارانب.md`.
3. مراجعة `QUESTIONNAIRE_SECTION_ARCHITECTURE_REVIEW.md` لفهم سبب Subsection وحدوده.
4. مراجعة الأقسام والأسئلة والإجابات والـGuides الحالية.
5. تجنب تكرار سؤال أو Decision سبق تغطيته.
6. تحويل النقاط غير المحسومة إلى Decisions واضحة عبر الأسئلة.
7. مراجعة Enums / Models / Engine قبل افتراض Type أو Operator جديد.
8. استخدام `seed_key` ثابت وOption values مستقرة.
9. استخدام Dependencies فقط عند سبب وظيفي واضح.
10. تحديد `report_category` و`target_entity` بما يتوافق مع البنية الحالية.
11. عدم تحويل مثال أو Open Requirement إلى Requirement نهائي دون سؤال أو قرار صريح.
12. عدم تضخيم الأسئلة.
13. استخدام `preserveAnswers = true` في مرحلة الأسئلة المستقرة.

---

## 17. دورة البيانات النهائية

```text
تصور_مشروع_الارانب.md
↓
Architecture Review
↓
Sections / Questions
↓
Answers / Review
↓
Questionnaire Guides
+ Final Requirements Input
+ Project Context
↓
Requirements Agent
↓
Software Requirements / Business Rules
↓
Blueprint
↓
النظام النهائي لاحقًا
```

---

## 18. حالة التنفيذ الحالية والخطوة التالية

```text
Architecture → IMPLEMENTED & VERIFIED
Question Creation → IN PROGRESS
3.1 Animal Identity → IMPLEMENTED & LOCAL SEED VERIFIED
3.2 Animal Source / Record Start → IMPLEMENTED & LOCAL SEED VERIFIED
3.3 Animal Pedigree / Family Tree → IMPLEMENTED & LOCAL SEED VERIFIED
3.4 Initial Herd Setup → IMPLEMENTED & LOCAL SEED VERIFIED
3.5 Production Herd / Groups → IMPLEMENTED ON GITHUB / WAITING LOCAL SEED
```

الخطوة المحلية التالية:

```bash
git pull origin master
php artisan db:seed --class=QuestionnaireAnimalHerdQuestionsSeeder
```

بعد نجاح الـSeeder يصبح **القسم الثالث كاملًا من ناحية Question Seeders**، وتبدأ المرحلة التالية من:

`4.1 استقبال الحيوان من الخارج وإعادة الإدخال`

---

## 19. قاعدة GitHub الإلزامية

المستودع افتراضيًا:

**READ ONLY**

يسمح بالقراءة والبحث والتحليل والمراجعة والاقتراح فقط.

لا يتم تعديل أو إنشاء أو حذف ملف أو Commit أو Push أو Branch أو Pull Request أو Seeder إلا بطلب صريح ومباشر من المستخدم.

وجود صلاحية تقنية لا يعني وجود إذن بالتعديل.

`تصور_مشروع_الارانب.md` Reference Only ولا يتم تعديله إلا بطلب صريح.

---

## 20. المبدأ الأساسي

هذا المشروع هو:

**أداة تحليل متطلبات قبل التطوير**

وليس:

**نظام إدارة المزرعة النهائي.**

كل قرار يجب أن يقلل الافتراضات قبل بناء النظام الحقيقي، مع الحفاظ على اتساق الوثائق والكود وعدم الاعتماد على الذاكرة أو المحادثة وحدها.
