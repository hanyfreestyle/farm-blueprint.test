# Farm Blueprint — Project Context & Working Rules

## 1. تعريف المشروع

المستودع الأساسي:

`hanyfreestyle/farm-blueprint.test`

هذا المشروع **ليس نظام إدارة مزرعة الأرانب النهائي**.

وظيفته بناء **Blueprint / دراسة تحليلية تفصيلية** قبل تطوير النظام الفعلي، وتحويل التصور ودورة العمل إلى:

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

الهدف من الأسئلة هو الوصول إلى قرارات تصميمية وتشغيلية موثقة، وليس جمع معلومات عامة فقط.

---

## 2. قاعدة تحديث هذا الملف

هذا الملف هو **المرجع الأساسي للحالة الحالية للمشروع**.

يجب تحديثه مع كل قرار معماري أو تنظيمي معتمد يؤثر على طريقة العمل، ولا يجوز تركه يعكس وضعًا أقدم من الكود أو القرار الحالي.

إذا تعارضت معلومة تاريخية في ملف آخر مع هذا الملف، تكون الأولوية:

```text
أحدث قرار معتمد من المستخدم
→ FARM_BLUEPRINT_PROJECT_CONTEXT.md
→ الكود الحالي
→ السجل التاريخي القديم
```

أما سجل التنفيذ والتاريخ التقني فيوجد في:

`docs/QUESTIONNAIRE_IMPLEMENTATION_PLAN.md`

---

## 3. التقنية المستخدمة

- Laravel 12
- PHP 8.2
- Filament 4
- MySQL
- Questionnaire Engine مخصص
- `spatie/laravel-translatable` للبيانات متعددة اللغات عند الحاجة

---

## 4. الهيكل الرئيسي الحالي للدراسة

الأقسام الرئيسية المعتمدة حاليًا بالترتيب:

1. إدارة البيانات الأساسية — Master / Reference Data.
2. هيكل المزرعة — Farm Structure.
3. إعدادات التشغيل ودورة الإنتاج — Operation Settings.
4. تكوين وإدخال القطيع — Herd Setup.
5. الحركات ودورة التشغيل الفعلية — Workflow.
6. التقارير والإشعارات ومؤشرات الأداء — Reports.

التمييز الأساسي:

```text
Master / Reference Data
= قوائم وتعريفات مرجعية يعاد استخدامها داخل النظام

Farm Structure
= كيانات فعلية تمثل المزرعة ومواقع التشغيل داخلها
```

---

## 5. إدارة البيانات الأساسية — Master / Reference Data

الشجرة الحالية:

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

Seeder الأقسام المسؤول:

`database/seeders/Sections/QuestionnaireMasterDataSectionSeeder.php`

### قاعدة مهمة — ملفات إعدادات التشغيل

`ملفات إعدادات التشغيل` **لم تعد Subsection داخل إدارة البيانات الأساسية**.

تم حذفها من Master Data، وسيعاد تصميمها لاحقًا داخل القسم الرئيسي المستقل:

`إعدادات التشغيل ودورة الإنتاج`

لا يتم إنشاء Seeder أسئلة لها داخل Master Data.

---

## 6. هيكل المزرعة — Farm Structure

الشجرة الحالية:

```text
هيكل المزرعة
├── بيانات المزرعة
├── بيانات العنبر
├── بيانات البطارية
└── بيانات القفص / العين
```

Seeder الأقسام المسؤول:

`database/seeders/Sections/QuestionnaireFarmStructureSectionSeeder.php`

العلاقة الهيكلية الأساسية:

```text
Farm
  ↓
Barn
  ↓
Battery
  ↓
Cage / Cell
```

Farm / Barn / Battery / Cage كيانات هيكلية فعلية وليست Lookup Lists.

---

## 7. شجرة Question Seeders — قاعدة إلزامية

يجب أن تعكس شجرة ملفات Question Seeders شجرة الأقسام نفسها، لمنع الأسماء التاريخية المضللة.

الشكل الحالي:

```text
database/seeders/Questions/
├── Concerns/
│
├── MasterData/
│   ├── OperationalActivitiesQuestionsSeeder.php
│   ├── ProductionPurposesQuestionsSeeder.php
│   ├── BreedMetricsQuestionsSeeder.php
│   ├── BreedDataQuestionsSeeder.php
│   ├── TransferReasonsQuestionsSeeder.php
│   ├── MortalityReasonsQuestionsSeeder.php
│   ├── ExclusionReasonsQuestionsSeeder.php
│   ├── ExitReasonsQuestionsSeeder.php
│   ├── MaleChangeReasonsQuestionsSeeder.php
│   ├── GovernoratesQuestionsSeeder.php
│   ├── CitiesQuestionsSeeder.php
│   ├── VentilationSystemsQuestionsSeeder.php
│   ├── CoolingSystemsQuestionsSeeder.php
│   └── HeatingSystemsQuestionsSeeder.php
│
├── FarmStructure/
│   ├── FarmDataQuestionsSeeder.php
│   ├── BarnDataQuestionsSeeder.php
│   ├── BatteryDataQuestionsSeeder.php
│   └── CageDataQuestionsSeeder.php
│
├── OperationSettings/   ← ينشأ عند بدء هذا القسم
├── HerdSetup/           ← ينشأ عند بدء هذا القسم
├── Workflow/            ← ينشأ عند بدء هذا القسم
└── Reports/             ← ينشأ عند بدء هذا القسم
```

لا يجوز وضع Master Data Seeder داخل `FarmStructure` أو العكس.

---

## 8. Orchestrators الحالية للأسئلة

الـorchestrator العام:

`database/seeders/QuestionnaireQuestionsSeeder.php`

يستدعي حاليًا:

```text
QuestionnaireMasterDataQuestionsSeeder
QuestionnaireFarmStructureQuestionsSeeder
```

### Master Data

`database/seeders/QuestionnaireMasterDataQuestionsSeeder.php`

يستدعي فقط Seeders التابعة لـ:

`database/seeders/Questions/MasterData/`

### Farm Structure

`database/seeders/QuestionnaireFarmStructureQuestionsSeeder.php`

يستدعي:

- `FarmDataQuestionsSeeder`
- `BarnDataQuestionsSeeder`
- `BatteryDataQuestionsSeeder`
- `CageDataQuestionsSeeder`

### الاسم القديم

`QuestionnaireFarmQuestionsSeeder` لم يعد جزءًا من التصميم الحالي وتم حذفه لتجنب الالتباس.

### DatabaseSeeder

يجب أن يبني الحالة الكاملة الحالية من الصفر عبر:

1. Seeders الأساسية للنظام.
2. `QuestionnaireSectionSeeder`.
3. `QuestionnaireQuestionsSeeder`.

---

## 9. طريقة التشغيل الحالية أثناء إعادة البناء

المستخدم يعتمد حاليًا محليًا على:

```bash
php artisan migrate:refresh --seed
```

هذا الأمر **تدميري** ويعيد بناء قاعدة البيانات بالكامل.

تم اعتماده مؤقتًا لأن الإجابات القديمة تم الاستغناء عنها أثناء تنظيف الأقسام والـSeeders قبل بدء دورة إجابات جديدة.

يجب أن يؤدي كل تشغيل حالي إلى:

- إنشاء جميع Main Sections بالترتيب الصحيح.
- إنشاء كل Subsection تحت Parent الصحيح.
- زرع كل الأسئلة المعتمدة حاليًا.
- عدم اعتماد Seeder على بيانات باقية من تشغيل سابق.
- عدم اختفاء مجموعة أسئلة بسبب نسيان استدعائها من orchestrator.

---

## 10. الانتقال لاحقًا إلى الحفاظ على الإجابات

عندما نبدأ دورة إجابات نريد الاحتفاظ بها، لا يستخدم `migrate:refresh --seed` كطريقة تشغيل عادية على قاعدة البيانات التي تحتوي تلك الإجابات.

المبدأ المستهدف:

```text
Stable Section Record
+ Stable section_id
+ Stable seed_key
+ Stable option value
+ preserveAnswers = true
```

هوية السؤال:

```text
section_id + seed_key
```

هوية Option:

```text
question_id + value
```

لا يتغير `seed_key` لمجرد تعديل صياغة السؤال إذا ظل القرار الوظيفي نفسه.

لا تتغير `option.value` لمجرد تعديل Label إذا ظل المعنى نفسه.

أي تغيير تدميري يجعل إجابة موجودة غير صالحة يجب أن يوقف المزامنة برسالة واضحة بدل حذف الإجابة بصمت.

---

## 11. نقل الأقسام مع وجود إجابات

إذا احتجنا لاحقًا إلى إعادة تصنيف Subsection مع وجود إجابات:

المسموح:

```text
نفس Section Record
→ تعديل parent_id
→ الحفاظ على Section ID
```

غير المسموح:

```text
Delete subsection
→ Create subsection جديد
→ Recreate questions
```

لأن ذلك يعرض Question IDs والإجابات والعلاقات للخطر.

---

## 12. المرجع الوظيفي الأساسي للأسئلة

الملف:

`تصور_مشروع_الارانب.md`

هو Source of Reference الوظيفي عند إنشاء أو مراجعة الأسئلة.

قبل إنشاء أسئلة جديدة يجب دائمًا:

1. مراجعة الجزء المقابل من الملف.
2. مراجعة الأقسام والأسئلة الموجودة حاليًا في المشروع.
3. تجنب تكرار سؤال أو قرار تم تغطيته سابقًا.
4. تحويل النقاط غير المحسومة إلى أسئلة تؤدي إلى قرارات واضحة.
5. الحفاظ على المصطلحات والمنطق الموجودين في المرجع.
6. عدم اختراع متطلبات غير مدعومة بالمحتوى وكأنها حقائق.

إذا اعتمد المستخدم لاحقًا قرارًا وظيفيًا أحدث، تكون الأولوية للقرار الأحدث داخل الـBlueprint دون تعديل المرجع نفسه إلا بطلب صريح.

---

## 13. قاعدة Final Requirements

عند الوصول إلى مرحلة كتابة المتطلبات النهائية، لا يرجع Requirements Agent إلى `تصور_مشروع_الارانب.md` ليغيّر قرارًا حُسم بالأسئلة والإجابات.

الأولوية:

```text
Latest agreed decision
→ Approved Answer
→ Questionnaire Guide
→ Final Requirements Input
```

المراجع المسموح بها لوكيل المتطلبات النهائية:

1. `docs/FARM_BLUEPRINT_PROJECT_CONTEXT.md`
2. ملف Guide الخاص بالقسم.
3. Final Requirements Input.
4. `docs/questionnaire-guide/REQUIREMENTS_CONFLICTS.md`

إذا وجد تعارضًا، لا يحسمه بنفسه؛ يسجله ويطلب قرار المستخدم.

---

## 14. فلسفة إنشاء الأسئلة

كل سؤال يجب أن ينتج قرارًا قابلًا للتحويل إلى Requirement أو Rule مثل:

- Field.
- Required / Nullable Rule.
- Relationship.
- Workflow Rule.
- Validation.
- State / Status.
- Task / Alert.
- Audit / History Rule.
- Configuration Rule.

أي Requirement أساسي يجب أن يكون ممثلًا بسؤال صريح، حتى إذا كان اتجاه القرار معروفًا مسبقًا.

يمكن أن يكون السؤال:

- Discovery Question.
- Confirmation Question.

لا يحذف السؤال لمجرد أن إجابته تبدو بديهية؛ يحذف إذا كان لا ينتج قرارًا مفيدًا أو موضوعًا في القسم الخطأ أو مكررًا.

---

## 15. Question Types الحالية

راجع دائمًا:

`App\Enums\Questionnaire\QuestionType`

الأنواع الحالية:

- `text`
- `textarea`
- `number`
- `date`
- `yes_no`
- `single_choice`
- `multi_choice`
- `select`

لا يتم افتراض نوع جديد قبل مراجعة الـEnum والكود الحالي.

---

## 16. Dependencies

راجع دائمًا:

`App\Enums\Questionnaire\QuestionDependencyOperator`

الـEngine يدعم حاليًا:

- `EQUALS`
- `CONTAINS`

لا يتم اختراع Operator جديد قبل مراجعة الكود والحاجة الوظيفية واعتماده.

Dependencies تستخدم فقط عند وجود سبب وظيفي واضح لعدم انطباق سؤال بناءً على إجابة سابقة.

---

## 17. Question Seeder Sync

الخدمة الموحدة:

`app/Services/Questionnaire/QuestionSeederSyncService.php`

القاعدة:

- لكل سؤال `seed_key` ثابت.
- تتم المزامنة بدل الحذف وإعادة الإنشاء العمياء.
- يجب ضبط `prune` و`preserveAnswers` وفق مرحلة العمل.
- عند الحفاظ على الإجابات، أي تغيير غير متوافق يجب أن يفشل بوضوح بدل فقد البيانات.

---

## 18. بيانات المزرعة — قرارات تأسيسية

`بيانات المزرعة` جزء من `هيكل المزرعة`.

الموقع الوظيفي يدعم:

- المحافظة.
- المدينة.
- العنوان.
- الموقع على الخريطة.

بيانات الاتصال يجب أن تدعم التعدد عند الحاجة، ولا يتم افتراض `phone_1`, `phone_2` كتصميم نهائي.

نشاط المزرعة لا يخزن كاختيار مستقل؛ الأنشطة الفعلية تستنتج من أنشطة العنابر التابعة لها.

---

## 19. الأنشطة التشغيلية

الأنشطة التشغيلية Master Data مستقلة.

الاتجاه الحالي:

> النشاط التشغيلي يرتبط بالعنبر وليس بالمزرعة.

أمثلة المرجع الوظيفي:

- إنتاج.
- فطام.
- تسمين.
- حجر / عزل.
- متعدد الاستخدام.

العلاقات في النظام النهائي تعتمد على IDs / Foreign Keys، وليس الأكواد النصية.

---

## 20. المستخدمون وفريق التشغيل

المبادئ الحالية:

- عضو فريق التشغيل User لديه Login.
- له Roles / Permissions.
- يمكن ربطه بالمزرعة أو المزارع التي يعمل عليها.
- يجب دعم الورديات عند الوصول إلى القسم المختص.
- يجب الحفاظ على السجل التاريخي عند تغير نطاق العمل أو الوردية.

التفاصيل النهائية تحسم بأسئلة صريحة.

---

## 21. المحافظات والمدن

Master Data مستقلة:

```text
Governorate
    ↓ 1:N
City
```

كل مدينة ترتبط بمحافظة عن طريق ID / Foreign Key.

---

## 22. قوائم الأسباب التشغيلية

القوائم التالية Master Data مستقلة:

- أسباب النقل.
- أسباب النفوق.
- أسباب الاستبعاد.
- أسباب الخروج.
- أسباب تغيير الذكر.

القيم التاريخية لا تحذف بطريقة تكسر السجلات السابقة؛ يتم تحديد سياسة التعطيل/الحذف من خلال الأسئلة المعتمدة.

---

## 23. تعدد اللغات

النظام النهائي عربي / إنجليزي في المحتوى الإداري القابل للعرض.

أي اسم أو وصف يحتاج ترجمة يستخدم Translatable JSON عند اعتماد ذلك، بدل إنشاء حقول منفصلة مثل:

```text
name_ar
name_en
```

الأرقام والتواريخ والـIDs والقيم التقنية لا تحتاج ترجمة.

---

## 24. Questionnaire Guides

ملفات `docs/questionnaire-guide/` تشرح كيفية تفسير كل سؤال وإجابته لاحقًا.

وظيفتها:

- ربط السؤال بـ`seed_key`.
- شرح ما الذي ينتج من الإجابة.
- توضيح ما لا يجوز استنتاجه.
- منع إعادة تفسير القرار بصورة مختلفة في مرحلة Requirements.

لا يجب أن تصبح نسخة ثانية من الإجابات الفعلية.

بعد فصل Farm Structure، يتم لاحقًا إعادة تنظيم ملفات Guide التاريخية التي ما زالت موجودة تحت `questionnaire-guide/master-data/` بخطوة توثيقية مستقلة، ولا تنقل بصورة عمياء.

---

## 25. القفص / العين — الاتجاه الحالي

القفص جزء من:

`هيكل المزرعة`

المبادئ الحالية المتفق عليها:

```text
Battery Structure
→ Generate Cages
→ Cage gets fixed identity
→ Review / Activation
→ Operational Cage
→ every later change = Action + History Record
```

قواعد أساسية:

- لا Create مستقل للقفص.
- لا Delete مستقل للقفص.
- Cage Code فريد على مستوى النظام.
- QR Code يولد من هوية/كود القفص.
- بعد التفعيل تكون الهوية والموقع الهيكلي Immutable.
- تغييرات الحالة تتم عبر Actions وليس Edit Form.
- كل Action يسجل History / Audit.
- الإشغال الحالي ينتج من حركات التسكين والنقل، لا من تعديل يدوي لقيمة occupancy.
- Cage Master Identity منفصلة عن Cage Operational State.

Seeder الحالي:

`database/seeders/Questions/FarmStructure/CageDataQuestionsSeeder.php`

---

## 26. بيانات البطارية — قواعد مهمة

Battery جزء من Farm Structure ويتبع Barn واحدًا.

أهم الاتجاهات الحالية:

- Battery Code فريد على مستوى النظام.
- بنية البطارية تحدد الأقفاص التابعة لها.
- الأقفاص تولد بعد اكتمال البنية ومراجعتها.
- أي تاريخ تشغيلي على Cage يقفل الهوية الهيكلية التاريخية.
- لا يعاد استخدام هوية/كود Cage تاريخي.
- إعادة هيكلة Battery بعد وجود تاريخ تشغيلي تتطلب إنهاء الهيكل القديم وإنشاء هيكل جديد عند الحاجة.
- توقف/صيانة Battery يؤثر على الإتاحة التشغيلية للأقفاص دون تغيير حالاتهم المحلية تلقائيًا.

---

## 27. دورة البيانات النهائية

```text
تصور_مشروع_الارانب.md
      ↓
Sections / Questions
      ↓
Answers / Review
      ↓
Questionnaire Guides
      +
Final Requirements Input
      +
Project Context
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

## 28. منهج العمل عند بناء قسم جديد

```text
1. قراءة FARM_BLUEPRINT_PROJECT_CONTEXT.md
↓
2. مراجعة الجزء المقابل من تصور_مشروع_الارانب.md
↓
3. مراجعة Section/Subsection الحالي
↓
4. مراجعة الأسئلة والقرارات السابقة
↓
5. استخراج القرارات غير المحسومة
↓
6. تصميم أسئلة مستقلة وغير مكررة
↓
7. تحديد seed_key ثابت
↓
8. مراجعة Question Type / Enums
↓
9. تحديد Options بقيم مستقرة
↓
10. تحديد Dependencies إن لزم
↓
11. تحديد report_category و target_entity
↓
12. مراجعة التغطية والتعارضات
↓
13. استخدام QuestionSeederSyncService
↓
14. تحديث هذا الملف إذا تغيرت الحالة المعمارية أو طريقة العمل
```

---

## 29. قاعدة GitHub الإلزامية

المستودع افتراضيًا:

**READ ONLY**

يسمح بالقراءة والبحث والتحليل والمراجعة والاقتراح فقط.

لا يتم تعديل أو إنشاء أو حذف ملف أو Commit أو Push أو Branch أو Pull Request أو Seeder إلا بطلب صريح ومباشر من المستخدم.

وجود صلاحية تقنية لا يعني وجود إذن بالتعديل.

`تصور_مشروع_الارانب.md` Reference Only ولا يتم تعديله إلا بطلب صريح.

بعد تعديل GitHub بطلب صريح، المسار المحلي المعتاد:

```bash
git pull origin master
```

وفي مرحلة إعادة البناء الحالية فقط:

```bash
php artisan migrate:refresh --seed
```

---

## 30. المبدأ الأساسي

هذا المشروع هو:

**أداة تحليل متطلبات قبل التطوير**

وليس:

**نظام إدارة المزرعة النهائي.**

كل قرار في الأسئلة يجب أن يساعد على تقليل الافتراضات قبل بناء النظام الحقيقي، مع الحفاظ على تاريخ القرارات والإجابات بمجرد الانتقال إلى مرحلة البيانات المستقرة.
