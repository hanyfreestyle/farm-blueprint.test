# 3.3 النسب وشجرة العائلة — دليل تفسير الإجابات

> **الحالة:** مسودة أولى مبنية على الإجابات الحالية للمراجعة  
> **ملف الإجابات المقابل:** `questionnaire-export/answers/03-animal-herd/03-animals.md`  
> **Question Keys المغطاة:** 10 / 10 — منها 9 مطبقة حاليًا و1 غير مطبق بسبب الـDependency

---

## 1. الغرض من هذا الملف

هذا الملف يفسر **المعنى الوظيفي والتقني للإجابات المعتمدة فعليًا** في قسم `النسب وشجرة العائلة` ضمن `بيانات الحيوان وتكوين القطيع`.

هو ليس مصدرًا بديلًا للإجابات، ولا يغير أي قرار موجود في ملف الإجابات.

```text
answers/03-animal-herd/03-animals.md
= ماذا قررنا؟

                    ↓ تفسير

guides/03-animal-herd/03-animals.md
= ماذا تعني هذه القرارات تقنيًا؟ وما حدودها؟
```

القاعدة المعمارية الأساسية لهذا القسم:

```text
Breed Master Data
≠
Animal Pedigree
≠
Genetic Line
```

السلالة تعريف مرجعي للحيوان، بينما النسب يمثل **العلاقات الفعلية بين الحيوان وأبيه وأمه والبطن التي خرج منها**، وشجرة العائلة مشتقة من هذه العلاقات عبر الأجيال.

---

## 2. الهدف الوظيفي من القسم

الغرض هو الحفاظ على الأصل البيولوجي للحيوان بصورة تسمح لاحقًا بـ:

- عرض الأب والأم والبطن الأصلية.
- بناء شجرة العائلة تلقائيًا بقدر البيانات المتاحة.
- التمييز بين نسب كامل وجزئي وغير معروف.
- معرفة مصدر/درجة توثيق معلومة النسب.
- الحفاظ على الأم البيولوجية منفصلة عن الأم الحاضنة / المرضعة.
- الاحتفاظ بمفهوم مستقل للخط الوراثي بجانب السلالة والنسب المباشر.

هذا القسم يصف **بيانات النسب وعلاقاتها**، ولا يسجل أحداث التلقيح أو الولادة أو نقل المواليد بين الأمهات؛ هذه أحداث Workflow مستقلة تكون مصدرًا لبعض بيانات النسب.

---

## 3. ملخص القرارات المعتمدة

```text
Animal Pedigree
├── Direct Relationships
│   ├── Biological Father
│   ├── Biological Mother
│   └── Birth Litter
│
├── Internal Animal Pedigree
│   └── derive automatically when available
│       + allow manual completion of missing data
│
├── Completeness
│   ├── complete
│   ├── partial
│   └── unknown
│
├── External Ancestors
│   └── unstructured text only
│
├── Evidence / Provenance
│   ├── system record
│   ├── pedigree certificate
│   ├── breeder record
│   ├── unverified statement
│   └── unknown
│
├── Family Tree
│   └── automatically derived from recorded parent relationships
│
├── Foster Care
│   └── Biological Mother remains unchanged
│       Foster Mother recorded separately
│
├── Genetic Line
│   └── required as a distinct concept
│
└── Offspring Breed
    └── manually selected from Breed Master Data
        no automatic derivation from parents
```

---

## 4. علاقات النسب الأساسية

### `animal.pedigree_relationships`

تم اعتماد العلاقات التالية:

```text
biological_father
biological_mother
birth_litter
```

المعنى أن سجل الحيوان يجب أن يستطيع الوصول إلى:

```text
Animal
├── Biological Father → Animal when known in system
├── Biological Mother → Animal when known in system
└── Birth Litter → Litter / Birth record
```

وجود `birth_litter` مهم لأنه يحافظ على المسار التاريخي:

```text
Animal
→ Birth Litter
→ Birth Event
→ Reproductive Cycle / Mating context
→ Biological Parents
```

لكن لا يجوز تكرار بيانات دورة الإنتاج داخل سجل الحيوان نفسه؛ المرجع إلى البطن والعلاقات هو الذي يربط التاريخ.

---

## 5. إنشاء نسب الحيوانات الناتجة داخل النظام

### `animal.internal_pedigree_derivation`

القرار الحالي:

```text
automatic_when_available_manual_completion
```

أي أن النظام يجب أن يستخدم الحقيقة الموجودة بالفعل في سجلات الولادة ودورة الإنتاج بدل مطالبة المستخدم بإعادة إدخال الأب والأم والبطن.

المسار الطبيعي:

```text
Mating / Reproduction Records
        ↓
Birth Event + Litter
        ↓
Individual Animal Record
        ↓
Pedigree relationships derived automatically
```

إذا كانت بعض المعلومات غير موجودة في المصدر، يسمح القرار الحالي **باستكمال الجزء الناقص يدويًا**.

هذا لا يعني السماح للمستخدم بتجاوز أو تغيير علاقة موثقة آليًا بلا ضوابط؛ قواعد تصحيح/تعديل نسب موثق لم تحسم في هذا القسم ويجب ألا تُخترع.

---

## 6. اكتمال النسب وجودة البيانات

### `animal.pedigree_completeness_states`

يجب دعم ثلاث حالات:

```text
Complete
Partial
Unknown
```

المعنى:

- `complete` → العلاقات المطلوبة متاحة وفق تعريف الاكتمال المعتمد لاحقًا.
- `partial` → بعض معلومات النسب معروفة وبعضها غير معروف.
- `unknown` → لا توجد معلومات نسب قابلة للاعتماد.

هذه الحالات **وصف لجودة/اكتمال البيانات** وليست حالة تشغيلية للحيوان.

ولا يجوز اختراع أب أو أم أو سلف لاستكمال شجرة ناقصة.

---

## 7. السلف الخارجي غير الموجود كحيوان داخل النظام

### `animal.external_ancestor_strategy`

القرار الحالي:

```text
unstructured_text_only
```

أي أن الأب/الأم/السلف المعروف من مصدر خارجي، لكنه غير موجود كـ`Animal Record` داخل النظام، لا يتم إنشاء سجل حيوان تشغيلي له ولا `ExternalAncestor` منظم حاليًا؛ تحفظ المعلومة كنص داخل بيانات النسب.

### `animal.external_ancestor_fields`

هذا السؤال **غير مطبق حاليًا** لأن شرطه يتطلب:

```text
animal.external_ancestor_strategy = external_ancestor_reference
```

وهذا ليس القرار الحالي.

لذلك لا يجوز استنتاج Schema لكيان `ExternalAncestor` أو حقوله من الاختيارات غير المطبقة.

### أثر هذا القرار على شجرة العائلة

هذا يضع حدًا مهمًا:

```text
Structured parent Animal relationships
→ يمكن تتبعها تلقائيًا عبر أجيال متعددة

External ancestor stored as text only
→ يمكن عرضه كمعلومة معروفة
→ لكنه ليس Node مرجعيًا يمكن متابعة آبائه وأجداده تلقائيًا
```

إذن الشجرة التلقائية تكون كاملة بقدر **العلاقات المنظمة الموجودة فعليًا**، وقد تتوقف عند سلف خارجي مسجل كنص.

هذا ليس تعارضًا مانعًا، لكنه قيد مباشر ناتج عن اختيار `unstructured_text_only`.

---

## 8. مصدر ودرجة توثيق النسب

### `animal.pedigree_evidence_types`

تم اعتماد جميع مصادر التوثيق التالية:

```text
system_record
pedigree_certificate
breeder_record
unverified_statement
unknown
```

الهدف هو عدم مساواة كل معلومات النسب في درجة المصدر.

مثال مفاهيمي:

```text
Parent relationship
+ Evidence / Provenance
```

قد تكون العلاقة:

- ناتجة تلقائيًا من سجلات النظام.
- مأخوذة من شهادة نسب.
- من سجل مربي.
- من إفادة غير موثقة بمستند.
- أو مصدرها غير معروف.

هذا السؤال يثبت ضرورة تمييز المصدر، لكنه لا يحدد Score رقميًا للثقة ولا ترتيبًا آليًا للأفضلية بين المصادر.

---

## 9. بناء شجرة العائلة

### `animal.family_tree_build_strategy`

القرار الحالي:

```text
automatic_from_parent_relationships
```

إذن لا يتم تخزين شجرة منفصلة وصيانتها يدويًا كنسخة أخرى من الحقيقة.

المصدر الأساسي:

```text
Animal A
├── Father → Animal B
│   ├── Father → ...
│   └── Mother → ...
└── Mother → Animal C
    ├── Father → ...
    └── Mother → ...
```

والنظام يبني العرض الشجري تلقائيًا من العلاقات المتاحة.

**لا يجوز** تخزين Parent Relationships ثم إنشاء Tree مستقلة قابلة للتعديل يدويًا بطريقة تسمح بتعارض المصدرين.

وبسبب قرار السلف الخارجي النصي، الشجرة لا تستطيع التوسع من ذلك السلف إلا إذا أصبح لاحقًا هناك نموذج مرجعي منظم بقرار جديد.

---

## 10. الأم البيولوجية مقابل الأم الحاضنة / المرضعة

### `animal.biological_vs_foster_mother`

الإجابة `نعم` تثبت قاعدة سلامة بيانات مهمة:

```text
Biological Mother
≠
Foster / Nursing Mother
```

إذا نُقل مولود إلى أنثى أخرى للرضاعة:

- لا تتغير الأم البيولوجية في النسب.
- الأم الحاضنة / المرضعة تسجل بصورة منفصلة في حدث الرعاية/الرضاعة المناسب.
- شجرة النسب تستمر في الاعتماد على الأصل البيولوجي.

إذن التحضين أو الرضاعة البديلة **حدث رعاية** ولا يعيد كتابة النسب الوراثي.

---

## 11. الخط الوراثي

### `animal.genetic_line_usage`

الإجابة الحالية:

```text
true
```

أي أن المشروع يحتاج إلى مفهوم مستقل باسم `Genetic Line` بجانب:

```text
Breed
Pedigree
```

لكن هذا السؤال يحسم **وجود المفهوم فقط**.

لا يجوز استنتاج من هذه الإجابة وحدها:

- حقول `GeneticLine`.
- هل هو Master Data أم Entity آخر.
- هل الحيوان يرتبط بخط واحد أو أكثر.
- هل الخط مرتبط بسلالة واحدة أو عدة سلالات.
- قواعد إنشاء الخط أو إيقافه.
- طريقة توريثه أو اشتقاقه للنسل.

هذه تفاصيل تحتاج Requirements / Questions مستقلة إذا لم تكن محسومة في قسم آخر.

---

## 12. سلالة نسل أبوين من سلالات مختلفة

### `animal.offspring_breed_derivation`

القرار الحالي:

```text
manual_from_master_data
```

أي أن النظام **لا يستنتج تلقائيًا سلالة النسل من سلالة الأب والأم**.

الحقيقة تحفظ في مستويين منفصلين:

```text
Pedigree
→ Father Breed + Mother Breed are knowable through parents

Animal Breed
→ selected explicitly from approved Breed Master Data
```

هذا يمنع افتراض قاعدة وراثية أو تصنيف تلقائي غير معتمد.

ولا يجوز للنظام أن ينشئ Breed جديدة أو يصنف النسل كهجين تلقائيًا لمجرد اختلاف سلالتي الأبوين؛ الاختيار يتم من تعريفات السلالات المعتمدة.

---

## 13. ما الذي لا يجوز استنتاجه من هذا القسم

لا يستنتج القسم وحده:

```text
Pedigree edit/correction approval workflow
Confidence numeric score
Evidence ranking algorithm
Kinship coefficient calculation
Inbreeding coefficient calculation
Automatic mating prohibition based on kinship
Maximum family-tree depth
ExternalAncestor structured entity
GeneticLine fields / lifecycle / cardinality
Automatic genetic-line inheritance
Automatic offspring breed classification
Foster mother as biological parent
Manual duplicate family-tree storage
```

كما لا يجوز:

- اعتبار Breed بديلًا عن Pedigree.
- اعتبار Genetic Line بديلًا عن Breed أو Parentage.
- إنشاء تاريخ أبوة/أمومة غير مدعوم بمصدر.
- تغيير الأم البيولوجية بسبب الرضاعة البديلة.

---

## 14. فحص الاتساق

**الحالة:** لا يوجد تعارض مانع داخل الإجابات الحالية.

النقاط المتسقة بوضوح:

1. النسب الداخلي يستفيد من سجلات الولادة والتكاثر بدل إعادة الإدخال.
2. يسمح ببيانات نسب ناقصة دون اختراع معلومات.
3. الشجرة مشتقة من علاقات الأب والأم، وليست نسخة يدوية مستقلة.
4. الأم البيولوجية منفصلة عن الأم الحاضنة.
5. سلالة النسل لا تستنتج آليًا من الوالدين.

### نقطة حدود وليست تعارضًا

`animal.external_ancestor_strategy = unstructured_text_only` يحد من قدرة الشجرة على التوسع آليًا عبر سلف خارجي غير منظم. يمكن عرضه كمعلومة، لكن لا يمكن تتبع سلسلة آبائه تلقائيًا.

### نقطة تحتاج استكمال تصميم لاحق

`animal.genetic_line_usage = نعم` يثبت الاحتياج لمفهوم `Genetic Line`، لكنه لا يحدد نموذجه أو قواعده. لا يجب اختراعها داخل Final Requirements دون مصدر معتمد إضافي.

---

## 15. المخرجات المطلوبة للـRequirements

يجب أن تغطي الـRequirements النهائية على الأقل:

1. **Direct Pedigree Relationships** — الأب البيولوجي، الأم البيولوجية، والبطن الأصلية.
2. **Automatic Internal Derivation** — استخدام سجلات الولادة والتكاثر كمصدر للنسب المتاح.
3. **Manual Completion Boundary** — السماح باستكمال الناقص دون إعادة كتابة الحقيقة الموثقة آليًا بلا قاعدة.
4. **Pedigree Completeness** — كامل / جزئي / غير معروف.
5. **External Ancestor Representation** — معلومات نصية فقط وفق القرار الحالي.
6. **Pedigree Evidence / Provenance** — دعم مصادر التوثيق الخمسة المعتمدة.
7. **Derived Family Tree** — الشجرة تبنى تلقائيًا من العلاقات المنظمة.
8. **Biological vs Foster Mother Integrity** — الحفاظ على الأصل البيولوجي وفصل الرعاية البديلة.
9. **Genetic Line Concept** — إثبات وجود مفهوم مستقل، مع إبقاء تفاصيله غير المحسومة خارج التنفيذ حتى تعتمد.
10. **Offspring Breed Selection** — تحديد السلالة يدويًا من Breed Master Data وعدم اشتقاقها تلقائيًا من الوالدين.
11. **No Invented Pedigree** — البيانات غير المعروفة تبقى غير معروفة.
12. **No Duplicate Tree Source of Truth** — عدم إنشاء شجرة مستقلة قابلة للتعديل بالتوازي مع Parent Relationships.

---

## 16. الخلاصة التنفيذية

القرار الحالي يبني النسب على مبدأ واضح:

```text
Recorded reproduction / birth truth
        ↓
Biological Parent Relationships
        ↓
Animal Pedigree
        ↓
Automatically Derived Family Tree
```

مع السماح بالنسب الجزئي أو غير المعروف، والاحتفاظ بمصدر التوثيق، وعدم خلط الأم المرضعة بالأم البيولوجية.

السلف الخارجي يبقى حاليًا **معلومة نصية** وليس كيانًا قابلًا للتتبع عبر أجيال، والخط الوراثي مطلوب كمفهوم مستقل لكنه يحتاج تفاصيل تصميم إضافية قبل تحويله إلى نموذج نهائي.
