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

يجب تحديثه مع كل قرار معماري أو تنظيمي معتمد وكل انتقال مهم في حالة التنفيذ.

### سجل القرار المعماري

`docs/QUESTIONNAIRE_SECTION_ARCHITECTURE_REVIEW.md`

يوثق لماذا تم تقسيم الأقسام بهذه الطريقة، وما تم نقله أو دمجه أو حذفه كقسم مستقل، وحدود Data / Workflow / Reports / Settings.

**الحالة:** Architecture Review مكتملة ومعتمدة.

### خطة تنفيذ إعادة الهيكلة

`docs/QUESTIONNAIRE_ARCHITECTURE_IMPLEMENTATION_PLAN.md`

هي سجل التنفيذ الرسمي لإعادة هيكلة الأقسام.

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

الـorchestrator المسؤول:

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

Seeder:

`database/seeders/Sections/QuestionnaireMasterDataSectionSeeder.php`

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

### علاقة Master Data بـSettings

لا تنتقل كل Business Rule إلى Settings.

```text
تعريف القائمة / Lifecycle / Uniqueness / Initial Values / Retirement
→ تبقى Master Data غالبًا

قاعدة تشغيل يجب أن تختلف حسب نطاق التطبيق
→ مرشح Operational Setting ويجب حسمه بالأسئلة
```

`ملفات إعدادات التشغيل` ليست Subsection داخل Master Data.

---

## 7. القسم الثاني — هيكل المزرعة

Seeder:

`database/seeders/Sections/QuestionnaireFarmStructureSectionSeeder.php`

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

وجود الكود، Unique Scope، Entity Statuses، العلاقات الهيكلية وسياسة حذف/تقاعد الكيان تبقى مع Entity نفسه.

### اتجاهات مهمة حالية

#### Cage

```text
Battery Structure
→ Generate Cages
→ fixed Cage identity
→ Review / Activation
→ Operational Cage
→ later changes = Actions + History
```

- لا Create مستقل للقفص.
- لا Delete مستقل للقفص.
- Cage Code فريد على مستوى النظام.
- QR Code يولد من الهوية/الكود.
- بعد التفعيل تكون الهوية والموقع الهيكلي Immutable.
- تغييرات الحالة تتم عبر Actions وليس Edit عشوائي.
- كل Action يسجل History / Audit.
- الإشغال الحالي ينتج من حركات التسكين والنقل.

#### Battery

- تتبع Barn واحدًا.
- Battery Code فريد على مستوى النظام.
- بنيتها تحدد الأقفاص التابعة لها.
- الأقفاص تولد بعد اكتمال البنية ومراجعتها.
- أي تاريخ تشغيلي على Cage يقفل الهوية الهيكلية التاريخية.
- لا يعاد استخدام هوية/كود Cage تاريخي.
- إعادة الهيكلة بعد وجود تاريخ تشغيلي تنهي الهيكل القديم وتستخدم هيكلًا جديدًا عند الحاجة.
- توقف/صيانة Battery يؤثر على إتاحة الأقفاص دون تغيير حالاتهم المحلية تلقائيًا.

### سؤال مؤجل

> كيف يجب إدارة الأنواع الفيزيائية للبطاريات؟

يعود عند مراجعة/استكمال أسئلة Farm Structure، ولا يمنع العمل الحالي على القسم الثالث.

---

## 8. القسم الثالث — بيانات الحيوان وتكوين القطيع

Seeder:

`database/seeders/Sections/QuestionnaireAnimalHerdSectionSeeder.php`

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

### 8.1 — بيانات وهوية الحيوان — IMPLEMENTED ON GITHUB / WAITING LOCAL SEED

تم إنشاء:

`database/seeders/Questions/AnimalHerd/AnimalIdentityQuestionsSeeder.php`

وتم ربطه بـ:

`database/seeders/QuestionnaireAnimalHerdQuestionsSeeder.php`

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

unknown sex question
→ animal.identity_fields CONTAINS sex

breed requirement
→ animal.identity_fields CONTAINS breed

birth information methods
→ animal.identity_fields CONTAINS birth_information
```

تم استخدام:

```text
prune = true
preserveAnswers = true
```

حتى تصبح المزامنة آمنة عند بدء الإجابة على هذه المجموعة.

### حدود 3.1

يبقى خارج 3.1:

```text
مصدر الحيوان وتاريخ الدخول → 3.2
الأب / الأم / البطن / شجرة العائلة → 3.3
الوزن الفعلي → Workflow / Weight History
الموقع الحالي → مشتق من حركات التسكين والنقل
الحالة الإنتاجية والصحية الحالية → مشتقة من Workflow
الجاهزية → Settings + Reports
شكل الكود ومكوناته القابلة للتهيئة → Settings 6.2
```

**الخطوة التالية:** Pull ثم تشغيل Seeder الخاص بالقسم الثالث ومراجعة ظهور الأسئلة والـDependencies قبل الانتقال إلى 3.2.

---

## 9. القسم الرابع — الحركات ودورة التشغيل الفعلية

Seeder:

`database/seeders/Sections/QuestionnaireWorkflowSectionSeeder.php`

Workflow مسؤول عن **كل حدث أو إجراء فعلي يحدث بمرور الوقت ويغير سجل الحيوان أو البطن أو دورة الإنتاج أو موقع الإيواء أو المهمة التشغيلية**.

الشجرة: 17 Subsection.

```text
الحركات ودورة التشغيل الفعلية
├── استقبال الحيوان من الخارج وإعادة الإدخال
├── التسكين والنقل والإخلاء وإدارة الإشغال
├── الوزن والقياسات التشغيلية
├── التلقيح وإدارة المحاولات
├── فحص الحمل ومتابعة الحمل وتجهيز الولادة
├── تسجيل الولادة وإنشاء البطن
├── الرضاعة ومتابعة البطن وتداخل دورات الأم
├── الفطام والتحول إلى التتبع الفردي
├── النمو والفرز وإعادة التقييم
├── تحديد المصير والاستبعاد من المسار
├── الإحلال والاعتماد داخل القطيع الإنتاجي
├── التسمين والجاهزية للبيع
├── الصحة والعزل والتعافي والنفوق
├── الحالات الاستثنائية وإعادة بناء المسار
├── الخروج من المزرعة وإعادة الدخول
├── تشغيل وصيانة وتجهيز مواقع الإيواء
└── تنفيذ وإدارة المهام التشغيلية
```

قواعد فصل:

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

Seeder:

`database/seeders/Sections/QuestionnaireReportsSectionSeeder.php`

الشجرة: 15 Subsection.

```text
التقارير والتحليلات والتنبيهات ومؤشرات الأداء
├── لوحة التحكم ومتابعة التشغيل اليومي
├── تقارير القطيع والجاهزية
├── تقارير الخصوبة والتلقيح والحمل
├── تقارير الولادة والرضاعة والفطام
├── تقارير النمو والأوزان والتسمين
├── تقارير الصحة والنفوق والعزل
├── تحليل أداء الحيوانات الإنتاجية
├── تقارير النسب والإحلال
├── تقارير الإشغال والسعة والمواقع
├── الاتجاهات والمقارنات والتحليل عبر الزمن
├── الصفحات والسجلات التحليلية
├── التنبيهات والإنذار المبكر واكتشاف الحالات غير الطبيعية
├── جودة البيانات والاستثناءات الإدارية
├── المؤشرات الرئيسية للمزرعة KPIs
└── خصائص التقارير والتصفية والتصدير
```

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

---

## 11. القسم السادس — الإعدادات وقواعد التشغيل

Seeder:

`database/seeders/Sections/QuestionnaireSettingsSectionSeeder.php`

الشجرة: 13 Subsection.

```text
الإعدادات وقواعد التشغيل
├── نموذج الإعدادات ونطاق التطبيق
├── السياسات العامة والتحكم والتجاوز والتدقيق
├── قواعد تشغيل هيكل المزرعة ومواقع الإيواء
├── قواعد التسكين وتنظيم القطيع والجاهزية
├── قواعد التلقيح والخصوبة والجاهزية التناسلية
├── قواعد فحص الحمل والحمل وتجهيز الولادة
├── قواعد الولادة والرضاعة وإعادة التلقيح
├── قواعد الفطام والانتقال للتتبع الفردي
├── قواعد النمو والوزن والفرز والإحلال
├── قواعد التسمين والجاهزية للبيع
├── قواعد الصحة والعزل والنفوق والحالات الاستثنائية
├── قواعد المهام والتنبيهات والمواعيد والأولويات
└── إعدادات التقارير وKPIs والأهداف
```

المبدأ الأساسي:

```text
Structural / System Rule ≠ Operational Setting
```

أي Rule من الأقسام 1–5 لا ينتقل إلى Settings إلا إذا كان مطلوبًا أن يكون قابلًا للضبط أو الاختلاف حسب نطاق التشغيل.

Open Requirements المهمة داخل Settings تشمل:

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
- لا تتحول كل نقطة في Architecture Review إلى سؤال مستقل.

---

## 13. Question Seeders وOrchestrators

الـorchestrator العام:

`database/seeders/QuestionnaireQuestionsSeeder.php`

يستدعي بالترتيب:

```text
QuestionnaireMasterDataQuestionsSeeder
QuestionnaireFarmStructureQuestionsSeeder
QuestionnaireAnimalHerdQuestionsSeeder
QuestionnaireWorkflowQuestionsSeeder
QuestionnaireReportsQuestionsSeeder
QuestionnaireSettingsQuestionsSeeder
```

### الحالة الحالية

```text
Questions/
├── Concerns/
├── MasterData/
├── FarmStructure/
└── AnimalHerd/
    └── AnimalIdentityQuestionsSeeder.php
```

`QuestionnaireAnimalHerdQuestionsSeeder` يستدعي حاليًا:

```text
AnimalIdentityQuestionsSeeder
```

أما Orchestrators الخاصة بـWorkflow / Reports / Settings فما زالت بدون Question Seeders فعلية حتى تبدأ مرحلة تصميم أسئلة كل قسم.

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
- لا يتغير `seed_key` بسبب إعادة صياغة السؤال إذا ظل المعنى نفسه.
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

إذا احتجنا نقل Subsection مع إجابات محفوظة، يعاد استخدام نفس Section Record وتغيير `parent_id` بدل حذفه وإعادة إنشائه.

---

## 16. التشغيل المحلي الحالي

`DatabaseSeeder.php` يشغّل:

```text
QuestionnaireSectionSeeder
↓
QuestionnaireQuestionsSeeder
```

تم استخدام `migrate:refresh --seed` فقط خلال مرحلة إعادة البناء لأن الإجابات القديمة تم الاستغناء عنها.

**من الآن:** بمجرد بدء تسجيل إجابات جديدة نريد الحفاظ عليها، لا يستخدم `migrate:refresh --seed` كطريقة تشغيل عادية على قاعدة البيانات التي تحتوي هذه الإجابات.

لإضافة Question Seeder جديد إلى قاعدة التطوير الحالية يفضل تشغيل Seeder المستهدف بدل إعادة بناء القاعدة كلها متى كان ذلك كافيًا.

---

## 17. قواعد إنشاء الأسئلة الجديدة

قبل إنشاء أي Question Seeder جديد:

1. قراءة `FARM_BLUEPRINT_PROJECT_CONTEXT.md` أولًا.
2. مراجعة الجزء المقابل من `تصور_مشروع_الارانب.md`.
3. مراجعة `QUESTIONNAIRE_SECTION_ARCHITECTURE_REVIEW.md` لفهم سبب Subsection وحدوده.
4. مراجعة الأقسام والأسئلة والإجابات والـGuides الموجودة حاليًا.
5. تجنب تكرار سؤال أو Decision سبق تغطيته.
6. تحويل النقاط غير المحسومة إلى أسئلة تؤدي إلى Decisions واضحة.
7. مراجعة Enums / Models / Engine قبل افتراض Type أو Operator جديد.
8. استخدام `seed_key` ثابت وOption values مستقرة.
9. استخدام Dependencies فقط عند وجود سبب وظيفي واضح.
10. تحديد `report_category` و`target_entity` بما يتوافق مع البنية الحالية.
11. عدم تحويل مثال أو Open Requirement إلى Requirement نهائي دون سؤال أو قرار صريح.
12. عدم تضخيم الأسئلة بتحويل كل نقطة معمارية إلى سؤال منفصل.
13. استخدام `preserveAnswers = true` بمجرد بدء مرحلة الأسئلة المستقرة.

---

## 18. Open Requirements الرئيسية

تظل أسئلة مستقبلية وليست افتراضات:

- صلاحيات ونطاق الاطلاع على التقارير.
- Scope النهائي للإعدادات وعلاقة Farm / Barn / Profile.
- Reusable Profiles.
- Inheritance / Overrides.
- Versioning / Effective Date.
- أثر تعديل Settings على العمليات الجارية والمهام القائمة.
- Historical Settings Reference / Snapshot.
- صلاحيات واعتماد تغييرات Settings.
- تفاصيل Override Policy.
- طريقة تصحيح السجلات الحساسة.
- Minimum Audit حسب نوع العملية.
- الأعمار والأوزان والمدد والفواصل وThresholds وTargets.
- أي تفصيل لم يحسمه المرجع أو القرارات السابقة.

---

## 19. دورة البيانات النهائية

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

## 20. حالة التنفيذ الحالية والخطوة التالية

```text
Architecture → IMPLEMENTED & VERIFIED
Question Creation → IN PROGRESS
3.1 Animal Identity → IMPLEMENTED ON GITHUB / WAITING LOCAL SEED
```

الخطوة المحلية التالية:

```bash
git pull origin master
php artisan db:seed --class=QuestionnaireAnimalHerdQuestionsSeeder
```

بعد نجاح الـSeeder ومراجعة ظهور الأسئلة والـDependencies، تصبح 3.1 جاهزة للإجابة، ثم يبدأ تصميم:

`3.2 مصدر الحيوان وبداية السجل`

---

## 21. قاعدة GitHub الإلزامية

المستودع افتراضيًا:

**READ ONLY**

يسمح بالقراءة والبحث والتحليل والمراجعة والاقتراح فقط.

لا يتم تعديل أو إنشاء أو حذف ملف أو Commit أو Push أو Branch أو Pull Request أو Seeder إلا بطلب صريح ومباشر من المستخدم.

وجود صلاحية تقنية لا يعني وجود إذن بالتعديل.

`تصور_مشروع_الارانب.md` Reference Only ولا يتم تعديله إلا بطلب صريح.

---

## 22. المبدأ الأساسي

هذا المشروع هو:

**أداة تحليل متطلبات قبل التطوير**

وليس:

**نظام إدارة المزرعة النهائي.**

كل قرار يجب أن يقلل الافتراضات قبل بناء النظام الحقيقي، مع الحفاظ على اتساق الوثائق والكود وعدم الاعتماد على الذاكرة أو المحادثة وحدها.
