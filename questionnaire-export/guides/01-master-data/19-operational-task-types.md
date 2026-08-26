# 1.19 أنواع المهام التشغيلية — دليل تفسير الإجابات

> **الحالة:** مسودة أولى للمراجعة  
> **ملف الإجابات المقابل:** `questionnaire-export/answers/01-master-data/19-operational-task-types.md`  
> **Question Keys المغطاة:** 6 / 6

---

## 1. الهدف الوظيفي

`OperationalTaskType` يمثل **تعريفًا مرجعيًا لنوع المهمة التشغيلية** التي يمكن أن يستخدمها محرك المهام.

يجب الفصل بين ثلاث طبقات:

```text
OperationalTaskType
= ما نوع المهمة؟

Settings / Task Rules
= متى ولماذا تُولد المهمة؟ وما الأولوية والموعد والتنبيه؟

Workflow Task Execution
= المهمة الفعلية التي أُنشئت ونُفذت أو تأخرت أو ألغيت
```

هذا القسم لا ينشئ مهام فعلية ولا يحدد توقيتها.

---

## 2. ملخص القرارات المعتمدة

```text
OperationalTaskType
├── Fields
│   ├── name
│   ├── description
│   ├── category
│   ├── is_active
│   ├── sort_order
│   └── notes
├── Required
│   ├── name
│   └── is_active
├── Categories: 9
├── Initial Task Types: 35
├── Unique Name: yes
└── Retirement: disable only
```

---

## 3. التصنيفات المعتمدة

تم اعتماد التصنيفات التالية لتنظيم أنواع المهام:

```text
herd
mating
pregnancy
lactation
weaning
growth_sorting
fattening
housing_sites
health
```

وجود `category` ضمن الحقول لا يعني أن التصنيف إلزامي عند إنشاء نوع المهمة، لأنه غير موجود ضمن `operational_task_type.required_fields`.

كما أن الإجابات لا تحدد Mapping تفصيليًا يربط كل نوع مهمة بتصنيف معين؛ لا يجوز اختراع هذا الربط من أسماء المهام فقط.

---

## 4. أنواع المهام المبدئية المعتمدة

تم اعتماد 35 نوع مهمة كبداية:

```text
periodic_weight
health_status_review
not_ready_for_production_review
replacement_candidate_review
female_due_for_mating
remating
second_mating_if_enabled
pregnancy_check
pregnancy_recheck
pregnancy_followup
nest_box_preparation
expected_birth_followup
litter_followup
offspring_weight
lactating_mother_remating
special_lactation_followup
weaning_preparation
weaning_weight
sex_determination
animal_identity_creation
weaned_animals_transfer
growth_periodic_weight
first_sorting
second_sorting
growth_stage_evaluation
deferred_case_reevaluation
fattening_growth_rate_review
sale_readiness_review
animal_transfer
cage_vacating
cage_cleaning_sanitation
cage_maintenance_review
animal_under_observation_followup
isolation_review
pre_return_to_production_reevaluation
```

هذه القائمة تعرف **أنواع المهام التي يجب أن يكون النظام قادرًا على الإشارة إليها**، ولا تثبت أن كل مهمة ستتولد دائمًا.

بشكل خاص، أسماء مثل:

```text
second_mating_if_enabled
lactating_mother_remating
offspring_weight
```

تحمل في اسمها أو وصفها معنى شرطيًا. وجود النوع في Master Data لا يفعّل النظام التشغيلي المقابل. قواعد التفعيل والتوقيت تأتي من Settings والأسئلة المتخصصة.

---

## 5. الحقول والإلزام

`operational_task_type.fields` تعتمد الاسم والوصف والتصنيف والحالة والترتيب والملاحظات.

`operational_task_type.required_fields` تجعل الاسم والحالة فقط إلزاميين.

بالتالي الوصف والتصنيف والترتيب والملاحظات مدعومة ولكنها اختيارية عند الإنشاء وفق الإجابات الحالية.

---

## 6. فريدة الاسم والتقاعد

`operational_task_type.unique_name = نعم`.

يجب منع تعريف نوعي مهمة بالاسم نفسه، دون أن يمنع ذلك إنشاء عدد غير محدود من المهام الفعلية من النوع نفسه.

`retirement_policy = disable_only`.

نوع المهمة يعطل ولا يحذف، حتى تظل المهام التاريخية السابقة مرتبطة بتعريف مفهوم.

---

## 7. نقطة غير محسومة

لا يوجد في مجموعة الأسئلة الحالية `Question Key` مستقل يقرر هل قاموس `OperationalTaskType` ثابت أم قابل للإضافة والتعديل من لوحة التحكم.

لذلك لا يجوز استنتاج صلاحية CRUD كاملة أو اعتبار القائمة Enum مغلقة من هذه الإجابات وحدها.

كذلك لا يوجد قرار يربط كل Task Type بتصنيف واحد أو أكثر؛ وجود حقل `category` وحده لا يكفي لاختراع Cardinality أو Mapping.

---

## 8. حدود التفسير

لا يحسم هذا القسم:

- توقيت توليد أي مهمة.
- الأولوية.
- المسؤول عن التنفيذ.
- التكرار وإعادة الجدولة.
- التنبيهات.
- شروط تفعيل المهام الشرطية.
- حالات المهمة الفعلية.
- ماذا يحدث عند عدم تنفيذ المهمة.

هذه القرارات تنتمي إلى Settings وWorkflow 4.17.

---

## 9. فحص الاتساق

لا يوجد تعارض مباشر داخل الإجابات.

توجد نقطتان غير محسومتين فقط:

1. Fixed vs Managed لقاموس أنواع المهام.
2. الربط التفصيلي بين الأنواع والتصنيفات.

---

## 10. المخرجات المطلوبة للـRequirements

1. وجود تعريف مرجعي `OperationalTaskType`.
2. دعم الحقول الستة مع إلزام الاسم والحالة.
3. دعم التصنيفات التسعة.
4. توفير أنواع المهام الخمسة والثلاثين كبداية.
5. منع تكرار اسم النوع.
6. التعطيل بدل الحذف.
7. عدم اعتبار وجود النوع تفعيلًا تلقائيًا للـWorkflow المقابل.
8. إبقاء قواعد التوقيت والتوليد والأولوية والتنبيهات في Settings.
9. إبقاء التنفيذ الفعلي للمهام في Workflow.
10. عدم اختراع Management Mode أو Category Mapping غير محسوم.
