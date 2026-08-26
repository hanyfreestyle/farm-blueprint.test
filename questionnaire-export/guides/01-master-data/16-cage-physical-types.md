# 1.16 أنواع الأقفاص الفيزيائية — دليل تفسير الإجابات

> **الحالة:** مسودة أولى للمراجعة — يوجد تعارض مفتوح داخل الإجابات  
> **ملف الإجابات المقابل:** `questionnaire-export/answers/01-master-data/16-cage-physical-types.md`  
> **Question Keys المغطاة:** 5 / 5

---

## 1. الهدف الوظيفي

`CagePhysicalType` يمثل **تعريفًا مرجعيًا للنوع الفيزيائي للقفص**، ويجب أن يظل منفصلًا عن:

```text
CagePhysicalType
= التصميم / النوع الفيزيائي

CageUsage
= الاستخدام التشغيلي الحالي أو المقصود

Cage Status / Occupancy
= الحالة والإشغال الفعلي
```

هذا الفصل يمنع خلط شكل القفص بما يحدث داخله تشغيليًا.

---

## 2. القرارات المسجلة حاليًا

`cage_physical_type.fields` اختارت:

```text
name
description
dimensions
physical_features
default_capacity
is_active
sort_order
notes
```

`cage_physical_type.required_fields` اختارت:

```text
name
dimensions
default_capacity
is_active
sort_order
```

`cage_physical_type.unique_name = نعم`.

`cage_physical_type.retirement_policy = disable_only`.

لكن `cage_physical_type.attribute_model` اختارت:

```text
name_description_only
```

أي أن النوع الفيزيائي يحتفظ بالاسم والوصف فقط، بينما المواصفات التفصيلية تبقى في تكوين البطارية أو ملاحظات القفص.

---

## 3. التعارض المفتوح

هناك تعارض مباشر بين ثلاثة قرارات:

```text
cage_physical_type.fields
→ dimensions + physical_features + default_capacity مدعومة داخل النوع

cage_physical_type.required_fields
→ dimensions + default_capacity إلزاميان داخل النوع

cage_physical_type.attribute_model = name_description_only
→ المواصفات التفصيلية لا تكون ضمن النوع الفيزيائي نفسه
```

لا يمكن تحويل هذه الإجابات إلى Schema/Requirements نهائية واحدة دون قرار إضافي.

### الجزء المتأثر

لا يمكن حسم:

- هل `dimensions` خاصية في `CagePhysicalType` أم في تكوين البطارية/القفص.
- هل `default_capacity` خاصية في النوع أم في القفص/التكوين.
- هل `physical_features` تخزن كحقل داخل النوع أم خارج الكيان.
- ما الحقول الإلزامية الحقيقية لتعريف النوع.

### القرار المطلوب لاحقًا

يجب حسم أحد الاتجاهين بصورة صريحة:

```text
A) النوع الفيزيائي يحمل مواصفاته الأساسية
   → تبقى dimensions/default_capacity وربما physical_features داخل CagePhysicalType

أو

B) النوع الفيزيائي مجرد اسم/وصف مرجعي
   → تنقل المواصفات التفصيلية إلى Battery/Cage configuration ولا تكون Required في CagePhysicalType
```

هذا الدليل لا يختار أحد الاتجاهين من تلقاء نفسه.

---

## 4. القرارات التي يمكن اعتمادها رغم التعارض

### فريدة الاسم

يجب منع تكرار اسم النوع الفيزيائي للقفص.

### Lifecycle

`disable_only` يعني أن النوع المستخدم تاريخيًا لا يحذف، بل يعطل لمنع استخدامه مستقبلًا مع بقاء الأقفاص السابقة مرتبطة به.

### حدود الكيان

حتى بعد حل تعارض الحقول، يجب ألا يتحول `CagePhysicalType` إلى بديل عن:

- استخدام القفص.
- حالة القفص.
- الإشغال الفعلي.
- حركة الحيوان.

---

## 5. ما لا يجوز استنتاجه

لا يستنتج هذا القسم:

- أن نوعًا فيزيائيًا معينًا يساوي استخدامًا تشغيليًا بعينه.
- أن السعة الافتراضية هي السعة الفعلية الحالية للقفص.
- أن كل الأقفاص من النوع نفسه يجب أن تكون في الحالة أو الاستخدام نفسه.
- قواعد تغيير استخدام القفص.
- قواعد توليد الأقفاص من البطارية.

---

## 6. فحص الاتساق

**الحالة:** يوجد تعارض حقيقي مفتوح بين `fields` و`required_fields` و`attribute_model`.

بقية القرارات الخاصة بفريدة الاسم وسياسة التقاعد متوافقة ولا تتأثر بالتعارض.

---

## 7. المخرجات المطلوبة للـRequirements

يمكن اعتماد حاليًا:

1. وجود كيان مرجعي مستقل `CagePhysicalType`.
2. الفصل بين النوع الفيزيائي والاستخدام التشغيلي والحالة.
3. منع تكرار الاسم.
4. التعطيل بدل الحذف.

ولا يجوز إغلاق Requirements الحقول والمواصفات والسعة قبل حسم التعارض الموضح أعلاه.
