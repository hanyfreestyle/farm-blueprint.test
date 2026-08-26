# 6.13 إعدادات التقارير وKPIs والأهداف

> **ملف مولد آليًا.** يعكس حالة الأسئلة والإجابات في قاعدة البيانات وقت التصدير، ولا يحتوي على تفسير تقني مستنتج.

## معلومات القسم

- القسم الرئيسي: الإعدادات وقواعد التشغيل
- القسم الفرعي: إعدادات التقارير وKPIs والأهداف
- وقت التصدير: 2026-08-26 10:47:15
- إجمالي الأسئلة: 24
- الأسئلة القابلة حاليًا: 14
- المجاب عنها: 0
- غير المجاب عنها: 14
- المفتوحة للمراجعة: 0

## وصف القسم

يحدد القيم القابلة للضبط التي تستخدم للحكم على التقارير والمؤشرات مثل Targets وThresholds وBenchmarks والفترات القابلة للتخصيص عند الحاجة. تعريف التقرير أو KPI نفسه يظل في قسم التقارير والتحليلات.

---

## س1. ما أنواع القيم المرجعية التي يجب أن تدعمها إعدادات التقارير وKPIs للحكم على النتائج؟

- **Question Key:** `report_kpi_settings.evaluation_reference_types`
- **النوع:** `multi_choice`
- **مطلوب:** نعم
- **الحالة الحالية:** ظاهر وقابل للإجابة
- **التصنيف التقريري:** `kpi_reference_rule`
- **الكيان المستهدف:** `kpi_evaluation_reference`

### التوضيح

التقارير تحدد ما الذي نقيسه، بينما هذا القسم يحدد القيم التي تساعد على تفسير النتيجة. لا يعني اختيار أي نوع هنا إنشاء KPI جديد أو تغيير معادلته.

### الاختيارات

- `target_value` — قيمة مستهدفة Target
- `acceptable_range` — نطاق طبيعي / مقبول Minimum–Maximum
- `benchmark` — Benchmark / خط أساس للمقارنة
- `classification_thresholds` — حدود تصنيف النتيجة مثل ضمن المتوقع / يحتاج متابعة / خارج المقبول
- `default_period` — فترة افتراضية لحساب المؤشر عند كونه زمنيًا

### الإجابة

**الإجابة الحالية:** لم تتم الإجابة

### المراجعة

- **الحالة:** لا توجد مراجعة

---

## س2. إذا كان للمؤشر هدف أو حد تشغيلي معرف بالفعل في Settings أخرى، من أين يجب أن يحصل التقرير على القيمة المرجعية؟

- **Question Key:** `report_kpi_settings.reference_source_model`
- **النوع:** `single_choice`
- **مطلوب:** نعم
- **الحالة الحالية:** ظاهر وقابل للإجابة
- **التصنيف التقريري:** `kpi_reference_source_rule`
- **الكيان المستهدف:** `kpi_evaluation_reference`

### التوضيح

مثال: وزن مستهدف أو مدة أو حد نمو تم حسمه في قسم تشغيلي سابق. المطلوب منع وجود قيمة تشغيلية وقيمة تقريرية متعارضتين لنفس المفهوم.

### الاختيارات

- `reuse_operational_setting_when_same_concept` — إعادة استخدام القيمة التشغيلية نفسها متى كان المفهوم واحدًا، ولا تنشأ نسخة تقريرية مستقلة
- `report_specific_references_only` — كل قيم الحكم على التقارير تعرف مستقلة داخل 6.13 حتى لو وُجد هدف تشغيلي مشابه
- `hybrid_reuse_and_report_specific` — نموذج هجين: يعاد استخدام القيم التشغيلية عند التطابق، وتضاف قيم تقريرية مستقلة فقط للمؤشرات التي لا يوجد لها مرجع سابق

### الإجابة

**الإجابة الحالية:** لم تتم الإجابة

### المراجعة

- **الحالة:** لا توجد مراجعة

---

## س3. لأي مجموعات من KPIs يجب أن تسمح المزرعة بتعريف Targets أو Ranges أو Benchmarks أو فترات افتراضية؟

- **Question Key:** `report_kpi_settings.configured_reference_categories`
- **النوع:** `multi_choice`
- **مطلوب:** نعم
- **الحالة الحالية:** ظاهر وقابل للإجابة
- **التصنيف التقريري:** `kpi_reference_scope`
- **الكيان المستهدف:** `farm_kpi_reference_settings`

### التوضيح

المجموعات نفسها معرفة في 5.15. هذا السؤال يحدد فقط أين نحتاج قيمًا مرجعية قابلة للضبط.

### الاختيارات

- `production` — الإنتاج
- `growth` — النمو
- `herd` — القطيع
- `health` — الصحة
- `operations` — التشغيل

### الإجابة

**الإجابة الحالية:** لم تتم الإجابة

### المراجعة

- **الحالة:** لا توجد مراجعة

---

## س4. ما القيم المرجعية المطلوبة لمؤشرات الإنتاج؟

- **Question Key:** `report_kpi_settings.production_reference_values`
- **النوع:** `textarea`
- **مطلوب:** نعم
- **الحالة الحالية:** مخفي حاليًا بسبب الشرط
- **التصنيف التقريري:** `production_kpi_reference_values`
- **الكيان المستهدف:** `production_kpi_reference_settings`

### التوضيح

اذكر فقط القيم التي تستخدمها المزرعة فعليًا لكل مؤشر مناسب، مثل Target أو Range أو حدود التصنيف أو الفترة الافتراضية. مؤشرات الإنتاج نفسها معرفة في 5.15، ولا تعاد معادلاتها هنا.

### Dependency

- **Parent Question Key:** `report_kpi_settings.configured_reference_categories`
- **Parent Question:** لأي مجموعات من KPIs يجب أن تسمح المزرعة بتعريف Targets أو Ranges أو Benchmarks أو فترات افتراضية؟
- **Operator:** `contains`
- **Dependency Value:** `production`

### الإجابة

**الإجابة الحالية:** غير مطبق حاليًا

### المراجعة

- **الحالة:** لا توجد مراجعة

---

## س5. ما القيم المرجعية المطلوبة لمؤشرات النمو؟

- **Question Key:** `report_kpi_settings.growth_reference_values`
- **النوع:** `textarea`
- **مطلوب:** نعم
- **الحالة الحالية:** مخفي حاليًا بسبب الشرط
- **التصنيف التقريري:** `growth_kpi_reference_values`
- **الكيان المستهدف:** `growth_kpi_reference_settings`

### التوضيح

يمكن أن تشمل أهدافًا أو نطاقات أو فترات للمؤشرات المعتمدة مثل وزن الفطام ومعدل النمو والوصول للوزن المستهدف، مع إعادة استخدام أهداف 6.9 و6.10 عندما تكون هي نفس القيمة التشغيلية.

### Dependency

- **Parent Question Key:** `report_kpi_settings.configured_reference_categories`
- **Parent Question:** لأي مجموعات من KPIs يجب أن تسمح المزرعة بتعريف Targets أو Ranges أو Benchmarks أو فترات افتراضية؟
- **Operator:** `contains`
- **Dependency Value:** `growth`

### الإجابة

**الإجابة الحالية:** غير مطبق حاليًا

### المراجعة

- **الحالة:** لا توجد مراجعة

---

## س6. ما القيم المرجعية المطلوبة لمؤشرات القطيع؟

- **Question Key:** `report_kpi_settings.herd_reference_values`
- **النوع:** `textarea`
- **مطلوب:** نعم
- **الحالة الحالية:** مخفي حاليًا بسبب الشرط
- **التصنيف التقريري:** `herd_kpi_reference_values`
- **الكيان المستهدف:** `herd_kpi_reference_settings`

### التوضيح

اذكر Targets أو Ranges أو الفترات التي تساعد على تفسير مؤشرات القطيع المعتمدة في 5.15 مثل الإحلال أو الحيوانات غير المنتجة، دون تحويل أعداد القطيع الحالية إلى قيم تدخل يدويًا.

### Dependency

- **Parent Question Key:** `report_kpi_settings.configured_reference_categories`
- **Parent Question:** لأي مجموعات من KPIs يجب أن تسمح المزرعة بتعريف Targets أو Ranges أو Benchmarks أو فترات افتراضية؟
- **Operator:** `contains`
- **Dependency Value:** `herd`

### الإجابة

**الإجابة الحالية:** غير مطبق حاليًا

### المراجعة

- **الحالة:** لا توجد مراجعة

---

## س7. ما القيم المرجعية المطلوبة لمؤشرات الصحة والنفوق والعزل؟

- **Question Key:** `report_kpi_settings.health_reference_values`
- **النوع:** `textarea`
- **مطلوب:** نعم
- **الحالة الحالية:** مخفي حاليًا بسبب الشرط
- **التصنيف التقريري:** `health_kpi_reference_values`
- **الكيان المستهدف:** `health_kpi_reference_settings`

### التوضيح

المصدر يضع الحدود الطبيعية ونسب النفوق والفترة الزمنية المستخدمة في الحكم ضمن النقاط التي تحتاج مراجعة. حدود اكتشاف Alert فعلي تظل تحت قواعد 6.12 إذا كانت مختلفة عن حدود عرض KPI.

### Dependency

- **Parent Question Key:** `report_kpi_settings.configured_reference_categories`
- **Parent Question:** لأي مجموعات من KPIs يجب أن تسمح المزرعة بتعريف Targets أو Ranges أو Benchmarks أو فترات افتراضية؟
- **Operator:** `contains`
- **Dependency Value:** `health`

### الإجابة

**الإجابة الحالية:** غير مطبق حاليًا

### المراجعة

- **الحالة:** لا توجد مراجعة

---

## س8. ما القيم المرجعية المطلوبة لمؤشرات التشغيل؟

- **Question Key:** `report_kpi_settings.operations_reference_values`
- **النوع:** `textarea`
- **مطلوب:** نعم
- **الحالة الحالية:** مخفي حاليًا بسبب الشرط
- **التصنيف التقريري:** `operations_kpi_reference_values`
- **الكيان المستهدف:** `operations_kpi_reference_settings`

### التوضيح

يمكن أن تشمل أهداف تنفيذ المهام في موعدها، حدود التأخير أو الإشغال أو السعة عند الحاجة. القيم الفعلية تظل مشتقة من Workflow وReports، وهنا نسجل مراجع الحكم فقط.

### Dependency

- **Parent Question Key:** `report_kpi_settings.configured_reference_categories`
- **Parent Question:** لأي مجموعات من KPIs يجب أن تسمح المزرعة بتعريف Targets أو Ranges أو Benchmarks أو فترات افتراضية؟
- **Operator:** `contains`
- **Dependency Value:** `operations`

### الإجابة

**الإجابة الحالية:** غير مطبق حاليًا

### المراجعة

- **الحالة:** لا توجد مراجعة

---

## س9. كيف يجب أن يعرف النظام اتجاه الحكم لكل KPI عند مقارنة القيمة بالهدف أو الحدود؟

- **Question Key:** `report_kpi_settings.metric_direction_model`
- **النوع:** `single_choice`
- **مطلوب:** نعم
- **الحالة الحالية:** ظاهر وقابل للإجابة
- **التصنيف التقريري:** `kpi_evaluation_rule`
- **الكيان المستهدف:** `kpi_evaluation_definition`

### التوضيح

ليس كل مؤشر يعمل بنفس الاتجاه؛ ارتفاع نسبة الحمل قد يكون مرغوبًا، بينما ارتفاع النفوق غير مرغوب، وبعض المؤشرات لها نطاق مقبول. هذا Metadata للحكم وليس معادلة KPI جديدة.

### الاختيارات

- `explicit_direction_per_kpi` — يحدد لكل KPI صراحة: الأعلى أفضل / الأقل أفضل / الأفضل داخل نطاق / معلوماتي دون حكم
- `derive_direction_from_reference_configuration` — يستنتج الاتجاه تلقائيًا من نوع المرجع والقيم المدخلة فقط
- `no_directional_evaluation` — لا يستخدم اتجاه حكم؛ تعرض القيمة والمرجع فقط دون تصنيف أفضل أو أسوأ

### الإجابة

**الإجابة الحالية:** لم تتم الإجابة

### المراجعة

- **الحالة:** لا توجد مراجعة

---

## س10. ما مستوى تصنيف KPI المطلوب عند توفر Targets أو Ranges معتمدة؟

- **Question Key:** `report_kpi_settings.status_classification_model`
- **النوع:** `single_choice`
- **مطلوب:** نعم
- **الحالة الحالية:** ظاهر وقابل للإجابة
- **التصنيف التقريري:** `kpi_classification_rule`
- **الكيان المستهدف:** `kpi_evaluation_status`

### التوضيح

هذا التصنيف يخص تفسير قيمة التقرير. لا يعني تلقائيًا إنشاء Alert؛ علاقة التصنيف بالتنبيه تحسم في سؤال مستقل داخل هذا القسم مع 6.12.

### الاختيارات

- `reference_only_no_status` — بدون تصنيف لوني/حكمي؛ تعرض القيمة مع المرجع فقط
- `two_state_acceptable_or_outside` — تصنيف ثنائي: ضمن المقبول / خارج المقبول
- `three_state_expected_watch_action` — تصنيف ثلاثي: ضمن المتوقع / يحتاج متابعة / خارج الحد المقبول
- `classification_model_by_kpi` — يختلف نموذج التصنيف حسب KPI

### الإجابة

**الإجابة الحالية:** لم تتم الإجابة

### المراجعة

- **الحالة:** لا توجد مراجعة

---

## س11. كيف يجب تعريف حدود التصنيف عندما لا تكون مجرد Target مباشر؟

- **Question Key:** `report_kpi_settings.threshold_value_model`
- **النوع:** `single_choice`
- **مطلوب:** نعم
- **الحالة الحالية:** ظاهر وقابل للإجابة
- **التصنيف التقريري:** `kpi_threshold_rule`
- **الكيان المستهدف:** `kpi_evaluation_threshold`

### التوضيح

المصدر يطلب مراجعة الحدود الطبيعية وحدود التنبيه لكل مؤشر. هذا السؤال يحسم طريقة التعبير عن حدود الحكم التقريرّي فقط.

### الاختيارات

- `absolute_threshold_values` — قيم مطلقة Min / Max أو حدود مستقلة حسب KPI
- `percentage_deviation_from_reference` — نسبة انحراف مسموحة عن Target أو Benchmark
- `threshold_model_by_kpi` — يختار كل KPI الطريقة المناسبة له

### الإجابة

**الإجابة الحالية:** لم تتم الإجابة

### المراجعة

- **الحالة:** لا توجد مراجعة

---

## س12. ما مصادر الـBenchmark التي يجب أن يدعمها النظام عند استخدام Benchmark للمقارنة؟

- **Question Key:** `report_kpi_settings.benchmark_sources`
- **النوع:** `multi_choice`
- **مطلوب:** نعم
- **الحالة الحالية:** مخفي حاليًا بسبب الشرط
- **التصنيف التقريري:** `kpi_benchmark_rule`
- **الكيان المستهدف:** `kpi_benchmark`

### التوضيح

لا يفترض هذا السؤال اتصالًا بمصدر خارجي. القيم المرجعية العلمية أو المهنية لا تعتمد إلا إذا تم اعتمادها صراحة وإدخالها كمراجع موثقة، كما يمكن استخدام تاريخ المزرعة نفسها كخط أساس.

### الاختيارات

- `farm_historical_baseline` — تاريخ المزرعة نفسها / Baseline داخلي
- `approved_manual_reference` — قيمة مرجعية معتمدة يدويًا من مصدر مهني أو علمي موثق

### Dependency

- **Parent Question Key:** `report_kpi_settings.evaluation_reference_types`
- **Parent Question:** ما أنواع القيم المرجعية التي يجب أن تدعمها إعدادات التقارير وKPIs للحكم على النتائج؟
- **Operator:** `contains`
- **Dependency Value:** `benchmark`

### الإجابة

**الإجابة الحالية:** غير مطبق حاليًا

### المراجعة

- **الحالة:** لا توجد مراجعة

---

## س13. عند استخدام تاريخ المزرعة كـBenchmark، كيف يجب تحديد الفترة المرجعية للمقارنة؟

- **Question Key:** `report_kpi_settings.historical_benchmark_period_model`
- **النوع:** `single_choice`
- **مطلوب:** نعم
- **الحالة الحالية:** مخفي حاليًا بسبب الشرط
- **التصنيف التقريري:** `kpi_benchmark_period_rule`
- **الكيان المستهدف:** `kpi_benchmark`

### التوضيح

المطلوب تحديد خط الأساس فقط. تحليل الاتجاه والمقارنات نفسه يبقى في 5.11.

### الاختيارات

- `previous_comparable_period` — الفترة السابقة المماثلة مباشرة
- `configured_fixed_baseline_period` — فترة Baseline ثابتة يحددها المستخدم لكل KPI أو مجموعة
- `support_previous_and_fixed_baselines` — يدعم الطريقتين ويحدد كل KPI أساس المقارنة المستخدم

### Dependency

- **Parent Question Key:** `report_kpi_settings.benchmark_sources`
- **Parent Question:** ما مصادر الـBenchmark التي يجب أن يدعمها النظام عند استخدام Benchmark للمقارنة؟
- **Operator:** `contains`
- **Dependency Value:** `farm_historical_baseline`

### الإجابة

**الإجابة الحالية:** غير مطبق حاليًا

### المراجعة

- **الحالة:** لا توجد مراجعة

---

## س14. كيف يجب التعامل مع القيم المرجعية العلمية أو المهنية التي لم يعتمد مصدرها أو نطاقها بعد؟

- **Question Key:** `report_kpi_settings.approved_reference_governance`
- **النوع:** `single_choice`
- **مطلوب:** نعم
- **الحالة الحالية:** مخفي حاليًا بسبب الشرط
- **التصنيف التقريري:** `kpi_reference_governance`
- **الكيان المستهدف:** `kpi_benchmark`

### التوضيح

التصور يذكر أن المعادلات والحدود الطبيعية الدقيقة تحتاج مراجعة. لا يجب إدخال رقم افتراضي وكأنه Benchmark معتمد.

### الاختيارات

- `inactive_until_explicitly_approved` — لا تستخدم القيمة في الحكم حتى يعتمد مصدرها وقيمتها صراحة
- `store_as_proposed_reference_until_approved` — يمكن حفظها كمرجع مقترح لكن لا تؤثر على التصنيف أو التنبيهات حتى اعتمادها
- `documented_provisional_reference` — يسمح باستخدام قيمة مؤقتة موثقة بوضوح مع تمييزها بأنها غير نهائية

### Dependency

- **Parent Question Key:** `report_kpi_settings.benchmark_sources`
- **Parent Question:** ما مصادر الـBenchmark التي يجب أن يدعمها النظام عند استخدام Benchmark للمقارنة؟
- **Operator:** `contains`
- **Dependency Value:** `approved_manual_reference`

### الإجابة

**الإجابة الحالية:** غير مطبق حاليًا

### المراجعة

- **الحالة:** لا توجد مراجعة

---

## س15. كيف يجب تعريف الفترة الافتراضية للمؤشرات التي تحتاج فترة زمنية للحساب؟

- **Question Key:** `report_kpi_settings.default_period_definition_model`
- **النوع:** `single_choice`
- **مطلوب:** نعم
- **الحالة الحالية:** ظاهر وقابل للإجابة
- **التصنيف التقريري:** `kpi_period_setting_rule`
- **الكيان المستهدف:** `kpi_default_period`

### التوضيح

5.15 يحدد كيف يتعامل عرض الـKPI مع الفترة، وهنا نحدد أين تخزن القيمة الافتراضية نفسها. مؤشرات Current State لا تحتاج فترة حساب مصطنعة.

### الاختيارات

- `default_period_per_kpi` — فترة افتراضية مستقلة لكل KPI زمني
- `default_period_by_kpi_category` — فترة افتراضية لكل مجموعة KPIs: إنتاج / نمو / قطيع / صحة / تشغيل
- `global_default_period_with_exceptions` — فترة افتراضية عامة مشتركة مع استثناءات محددة عند الحاجة

### الإجابة

**الإجابة الحالية:** لم تتم الإجابة

### المراجعة

- **الحالة:** لا توجد مراجعة

---

## س16. ما الفترات القياسية التي يجب أن تكون متاحة كمرجع افتراضي أو نطاق مراجعة للتقارير وKPIs؟

- **Question Key:** `report_kpi_settings.supported_period_presets`
- **النوع:** `multi_choice`
- **مطلوب:** نعم
- **الحالة الحالية:** ظاهر وقابل للإجابة
- **التصنيف التقريري:** `report_period_setting`
- **الكيان المستهدف:** `report_period`

### التوضيح

التصور يذكر المتابعة اليومية والأسبوعية والشهرية، بينما 5.16 يدعم اختيار فترة للتصفية. Current State يستخدم للمؤشرات اللحظية التي لا تعتمد على فترة.

### الاختيارات

- `current_state` — الحالة الحالية Current State
- `daily` — يومي
- `weekly` — أسبوعي
- `monthly` — شهري
- `custom_date_range` — نطاق تاريخ مخصص عند دعم التقرير لذلك

### الإجابة

**الإجابة الحالية:** لم تتم الإجابة

### المراجعة

- **الحالة:** لا توجد مراجعة

---

## س17. ما الفترة الافتراضية المناسبة لكل KPI زمني أو مجموعة KPIs؟

- **Question Key:** `report_kpi_settings.default_period_mapping`
- **النوع:** `textarea`
- **مطلوب:** نعم
- **الحالة الحالية:** ظاهر وقابل للإجابة
- **التصنيف التقريري:** `kpi_period_values`
- **الكيان المستهدف:** `kpi_default_period`

### التوضيح

اذكر الـKPI أو المجموعة والفترة المناسبة لها. لا تضف فترة إلى مؤشر يمثل Current State فقط. اختيار المستخدم لفترة أخرى عند العرض يظل وفق النموذج المعتمد في 5.15 و5.16.

### الإجابة

**الإجابة الحالية:** لم تتم الإجابة

### المراجعة

- **الحالة:** لا توجد مراجعة

---

## س18. عند عرض KPI لفترة تاريخية بعد أن تغير Target أو Range لاحقًا، أي مرجع يجب استخدامه للحكم على الفترة القديمة؟

- **Question Key:** `report_kpi_settings.historical_reference_interpretation_model`
- **النوع:** `single_choice`
- **مطلوب:** نعم
- **الحالة الحالية:** ظاهر وقابل للإجابة
- **التصنيف التقريري:** `historical_kpi_reference_rule`
- **الكيان المستهدف:** `kpi_evaluation_reference`

### التوضيح

6.1 يحسم Versioning وEffective Date وحماية التاريخ. هنا نحدد فقط أي نسخة من المرجع تستخدم عند تفسير تقرير تاريخي.

### الاختيارات

- `use_reference_effective_during_report_period` — استخدام Target / Range الذي كان فعالًا خلال الفترة التاريخية
- `reevaluate_history_against_current_reference` — إعادة تقييم التاريخ دائمًا مقابل المرجع الحالي
- `historical_reference_with_optional_current_comparison` — إظهار الحكم التاريخي بمرجعه وقتها مع إمكانية مقارنة إضافية بالمرجع الحالي

### الإجابة

**الإجابة الحالية:** لم تتم الإجابة

### المراجعة

- **الحالة:** لا توجد مراجعة

---

## س19. هل يجب أن تسمح بعض Targets أو Ranges بالاختلاف حسب خصائص الحيوان أو السياق التشغيلي، بخلاف نطاق Settings العام في 6.1؟

- **Question Key:** `report_kpi_settings.segmentation_enabled`
- **النوع:** `yes_no`
- **مطلوب:** نعم
- **الحالة الحالية:** ظاهر وقابل للإجابة
- **التصنيف التقريري:** `kpi_reference_segmentation_rule`
- **الكيان المستهدف:** `kpi_evaluation_reference`

### التوضيح

مثل اختلاف هدف أو نطاق حسب السلالة أو الجنس أو المرحلة. Farm/Barn/Profile Scope نفسه لا يعاد تعريفه هنا لأنه محسوم معماريًا في 6.1.

### الاختيارات

- `1` — نعم
- `0` — لا

### الإجابة

**الإجابة الحالية:** لم تتم الإجابة

### المراجعة

- **الحالة:** لا توجد مراجعة

---

## س20. ما الأبعاد التي يجب أن تستطيع القيم المرجعية الاختلاف حسبها عند الحاجة؟

- **Question Key:** `report_kpi_settings.segmentation_dimensions`
- **النوع:** `multi_choice`
- **مطلوب:** نعم
- **الحالة الحالية:** مخفي حاليًا بسبب الشرط
- **التصنيف التقريري:** `kpi_reference_segmentation_rule`
- **الكيان المستهدف:** `kpi_evaluation_reference`

### التوضيح

المصدر يطلب مؤشرات للذكور والإناث ويهتم بالمرحلة والسلالة وأغراض التشغيل. اختر فقط الأبعاد التي تحتاج اختلافًا حقيقيًا في Targets أو Ranges.

### الاختيارات

- `breed` — السلالة
- `sex` — الجنس
- `operational_stage` — المرحلة التشغيلية / الإنتاجية
- `production_purpose_or_role` — الغرض / الدور الإنتاجي

### Dependency

- **Parent Question Key:** `report_kpi_settings.segmentation_enabled`
- **Parent Question:** هل يجب أن تسمح بعض Targets أو Ranges بالاختلاف حسب خصائص الحيوان أو السياق التشغيلي، بخلاف نطاق Settings العام في 6.1؟
- **Operator:** `equals`
- **Dependency Value:** `1`

### الإجابة

**الإجابة الحالية:** غير مطبق حاليًا

### المراجعة

- **الحالة:** لا توجد مراجعة

---

## س21. إذا انطبق أكثر من Target أو Range مخصص على نفس الحالة، كيف يجب اختيار المرجع الفعال؟

- **Question Key:** `report_kpi_settings.segmented_reference_resolution_model`
- **النوع:** `single_choice`
- **مطلوب:** نعم
- **الحالة الحالية:** مخفي حاليًا بسبب الشرط
- **التصنيف التقريري:** `kpi_reference_resolution_rule`
- **الكيان المستهدف:** `kpi_evaluation_reference`

### التوضيح

هذا السؤال يخص تعارض أبعاد التخصيص مثل سلالة + جنس + مرحلة، وليس تعارض Farm/Barn/Profile الذي تحكمه Architecture 6.1.

### الاختيارات

- `most_specific_matching_reference_wins` — المرجع الأكثر تخصيصًا الذي يطابق أكبر عدد من الأبعاد هو المستخدم
- `explicit_dimension_precedence_per_kpi` — يحدد لكل KPI ترتيب أولوية واضح لأبعاد التخصيص
- `disallow_overlapping_segmented_references` — لا يسمح بتعريف مراجع متداخلة قد تنطبق في نفس الوقت

### Dependency

- **Parent Question Key:** `report_kpi_settings.segmentation_enabled`
- **Parent Question:** هل يجب أن تسمح بعض Targets أو Ranges بالاختلاف حسب خصائص الحيوان أو السياق التشغيلي، بخلاف نطاق Settings العام في 6.1؟
- **Operator:** `equals`
- **Dependency Value:** `1`

### الإجابة

**الإجابة الحالية:** غير مطبق حاليًا

### المراجعة

- **الحالة:** لا توجد مراجعة

---

## س22. ما العلاقة بين حدود تصنيف KPI في التقارير وحدود إنشاء Alert في 6.12؟

- **Question Key:** `report_kpi_settings.alert_threshold_relationship_model`
- **النوع:** `single_choice`
- **مطلوب:** نعم
- **الحالة الحالية:** ظاهر وقابل للإجابة
- **التصنيف التقريري:** `kpi_alert_integration_rule`
- **الكيان المستهدف:** `kpi_alert_threshold`

### التوضيح

قد يكون مجرد خروج KPI عن Target كافيًا للتنبيه، وقد تحتاج المزرعة Alert Threshold أشد أو مختلفًا. المطلوب منع الخلط بين لون/تصنيف التقرير وبين شرط إنشاء التنبيه.

### الاختيارات

- `reuse_kpi_thresholds_for_alerts` — تستخدم نفس حدود KPI كشرط Alert عندما يكون التنبيه مفعلًا لهذا المؤشر
- `separate_alert_thresholds` — حدود Alert مستقلة دائمًا عن Targets / Ranges الخاصة بعرض KPI
- `relationship_configurable_per_kpi` — يحدد السلوك لكل KPI: إعادة استخدام الحدود أو Alert Threshold مستقل

### الإجابة

**الإجابة الحالية:** لم تتم الإجابة

### المراجعة

- **الحالة:** لا توجد مراجعة

---

## س23. كيف يجب عرض KPI إذا كانت معادلته معتمدة لكن لم يتم بعد تحديد Target أو Range أو Benchmark له؟

- **Question Key:** `report_kpi_settings.missing_reference_behavior`
- **النوع:** `single_choice`
- **مطلوب:** نعم
- **الحالة الحالية:** ظاهر وقابل للإجابة
- **التصنيف التقريري:** `kpi_reference_fallback_rule`
- **الكيان المستهدف:** `farm_kpi`

### التوضيح

عدم وجود مرجع للحكم لا يعني أن القيمة الفعلية غير قابلة للحساب. يجب الفصل بين «لا توجد بيانات» وبين «لا يوجد Target معتمد».

### الاختيارات

- `show_value_without_classification_mark_reference_unconfigured` — عرض قيمة KPI دون تصنيف مع توضيح أن المرجع غير مضبوط
- `show_value_and_trend_without_reference_judgment` — عرض القيمة والاتجاه التاريخي فقط عند توفره، دون حكم جيد/سيئ
- `hide_from_primary_view_until_reference_configured` — إخفاء KPI من العرض الرئيسي حتى يتم تحديد مرجع الحكم

### الإجابة

**الإجابة الحالية:** لم تتم الإجابة

### المراجعة

- **الحالة:** لا توجد مراجعة

---

## س24. كيف يجب التعامل مع KPI له Target أو Range لكن بعض مكونات الحكم المطلوبة ما زالت غير محددة؟

- **Question Key:** `report_kpi_settings.reference_completeness_review_model`
- **النوع:** `single_choice`
- **مطلوب:** نعم
- **الحالة الحالية:** ظاهر وقابل للإجابة
- **التصنيف التقريري:** `kpi_reference_validation_rule`
- **الكيان المستهدف:** `kpi_evaluation_reference`

### التوضيح

مثال: يوجد Target لكن لا يوجد حد «يحتاج متابعة»، أو توجد Range بلا فترة حساب معتمدة. المطلوب ألا يكمل النظام القيم الناقصة بافتراضات غير موثقة.

### الاختيارات

- `use_approved_parts_disable_incomplete_classification` — يستخدم فقط الأجزاء المعتمدة ويعطل أي تصنيف يحتاج قيمة مفقودة
- `reference_inactive_until_complete` — يعتبر إعداد المرجع غير مكتمل ولا يستخدم أي جزء منه حتى تكتمل القيم المطلوبة
- `draft_until_complete_and_approved` — يسمح بحفظ إعداد غير مكتمل كمسودة، ولا يصبح فعالًا إلا بعد اعتماده كاملًا

### الإجابة

**الإجابة الحالية:** لم تتم الإجابة

### المراجعة

- **الحالة:** لا توجد مراجعة

