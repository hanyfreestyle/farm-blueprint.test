<?php

namespace Database\Seeders\Questions\FarmStructure;

use App\Enums\Questionnaire\QuestionType;
use App\Models\QuestionnaireQuestion;
use App\Models\QuestionnaireSection;
use App\Services\Questionnaire\QuestionSeederSyncService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BatteryDataQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $this->adoptLegacySeedKeys();

            $questions = [
                [
                    'seed_key' => 'battery.fields',
                    'title' => 'ما البيانات التي يجب أن يحتوي عليها ملف البطارية؟',
                    'help_text' => 'حدد البيانات والعلاقات الأساسية التي يجب أن يدعمها ملف البطارية. التكوين الهيكلي هو المصدر الذي يعتمد عليه النظام في إنشاء الأقفاص التابعة للبطارية.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 1,
                    'report_category' => 'field',
                    'target_entity' => 'battery',
                    'options' => [
                        ['label' => 'اسم البطارية', 'value' => 'name'],
                        ['label' => 'كود البطارية', 'value' => 'code'],
                        ['label' => 'العنبر التابعة له', 'value' => 'barn'],
                        ['label' => 'النوع الفيزيائي للبطارية', 'value' => 'physical_type'],
                        ['label' => 'النشاط التشغيلي', 'value' => 'activity'],
                        ['label' => 'التكوين الهيكلي للبطارية', 'value' => 'structural_configuration'],
                        ['label' => 'الحالة التشغيلية', 'value' => 'status'],
                        ['label' => 'تاريخ بدء التشغيل', 'value' => 'started_at'],
                        ['label' => 'الشركة المصنعة', 'value' => 'manufacturer'],
                        ['label' => 'الموديل / الطراز', 'value' => 'model'],
                        ['label' => 'ملاحظات', 'value' => 'notes'],
                    ],
                ],
                [
                    'seed_key' => 'battery.required_fields',
                    'title' => 'أي من بيانات البطارية يجب أن تكون إلزامية عند إنشائها؟',
                    'help_text' => 'حدد الحد الأدنى من البيانات والعلاقات التي لا يسمح النظام بإنشاء سجل بطارية بدونها. متطلبات التفعيل والتشغيل يمكن أن تكون أكثر تشددًا من متطلبات إنشاء السجل.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 2,
                    'report_category' => 'validation_rule',
                    'target_entity' => 'battery',
                    'options' => [
                        ['label' => 'اسم البطارية', 'value' => 'name'],
                        ['label' => 'كود البطارية', 'value' => 'code'],
                        ['label' => 'العنبر التابعة له', 'value' => 'barn'],
                        ['label' => 'النوع الفيزيائي للبطارية', 'value' => 'physical_type'],
                        ['label' => 'النشاط التشغيلي', 'value' => 'activity'],
                        ['label' => 'التكوين الهيكلي للبطارية', 'value' => 'structural_configuration'],
                        ['label' => 'الحالة التشغيلية', 'value' => 'status'],
                        ['label' => 'تاريخ بدء التشغيل', 'value' => 'started_at'],
                        ['label' => 'الشركة المصنعة', 'value' => 'manufacturer'],
                        ['label' => 'الموديل / الطراز', 'value' => 'model'],
                        ['label' => 'ملاحظات', 'value' => 'notes'],
                    ],
                ],
                [
                    'seed_key' => 'battery.barn_relation',
                    'title' => 'هل يجب أن تتبع كل بطارية عنبرًا واحدًا فقط؟',
                    'help_text' => 'التسلسل الهيكلي المعتمد هو المزرعة ← العنبر ← البطارية ← القفص / العين، لذلك يجب حسم ما إذا كانت البطارية تنتمي إلى Parent Barn واحد فقط.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 3,
                    'report_category' => 'relationship_rule',
                    'target_entity' => 'battery',
                    'options' => [],
                ],
                [
                    'seed_key' => 'battery.identity_strategy',
                    'title' => 'كيف تريد تعريف البطارية داخل النظام؟',
                    'help_text' => 'حدد الهوية التشغيلية التي سيستخدمها العامل والإدارة للتمييز بين البطاريات.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 4,
                    'report_category' => 'field_rule',
                    'target_entity' => 'battery',
                    'options' => [
                        ['label' => 'اسم فقط', 'value' => 'name_only'],
                        ['label' => 'كود فقط', 'value' => 'code_only'],
                        ['label' => 'اسم وكود', 'value' => 'name_and_code'],
                    ],
                ],
                [
                    'seed_key' => 'battery.code_strategy',
                    'title' => 'كيف تريد التعامل مع كود البطارية؟',
                    'help_text' => 'حدد ما إذا كان الكود غير مطلوب أو يتم إدخاله يدويًا أو يولده النظام تلقائيًا.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 5,
                    'report_category' => 'field_rule',
                    'target_entity' => 'battery',
                    'options' => [
                        ['label' => 'لا نحتاج كودًا مستقلًا', 'value' => 'not_required'],
                        ['label' => 'يتم إدخال الكود يدويًا', 'value' => 'manual'],
                        ['label' => 'يقوم النظام بتوليد الكود تلقائيًا', 'value' => 'automatic'],
                    ],
                ],
                [
                    'seed_key' => 'battery.code_unique_scope',
                    'title' => 'ما نطاق عدم تكرار كود البطارية؟',
                    'help_text' => 'حدد نطاق التحقق من عدم تكرار كود البطارية إذا كان الكود مستخدمًا في النظام.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 6,
                    'report_category' => 'validation_rule',
                    'target_entity' => 'battery',
                    'options' => [
                        ['label' => 'فريد داخل العنبر فقط', 'value' => 'unique_within_barn'],
                        ['label' => 'فريد على مستوى النظام بالكامل', 'value' => 'globally_unique'],
                    ],
                ],
                [
                    'seed_key' => 'battery.physical_type_separation',
                    'title' => 'هل يجب فصل النوع الفيزيائي للبطارية عن نشاطها التشغيلي وتكوينها الهيكلي؟',
                    'help_text' => 'النوع الفيزيائي يصف تصميم البطارية، والنشاط يصف الغرض التشغيلي، بينما التكوين الهيكلي يحدد الجوانب والمستويات وعدد ومواقع الأقفاص التي سيولدها النظام.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 7,
                    'report_category' => 'architecture_rule',
                    'target_entity' => 'battery',
                    'options' => [],
                ],
                [
                    'seed_key' => 'battery.physical_type_management',
                    'title' => 'كيف يجب إدارة الأنواع الفيزيائية للبطاريات؟',
                    'help_text' => 'حدد هل الأنواع الفيزيائية قيم ثابتة أم Master Data قابلة للإدارة أم وصف حر داخل كل بطارية.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 8,
                    'report_category' => 'value_management',
                    'target_entity' => 'battery_type',
                    'options' => [
                        ['label' => 'قيم ثابتة داخل النظام', 'value' => 'fixed'],
                        ['label' => 'Master Data قابلة للإضافة والتعديل من لوحة التحكم', 'value' => 'managed'],
                        ['label' => 'وصف نصي حر داخل كل بطارية', 'value' => 'free_text'],
                    ],
                ],
                [
                    'seed_key' => 'battery.manufacturer_tracking',
                    'title' => 'هل نحتاج إلى تسجيل الشركة المصنعة أو الموديل / الطراز للبطارية؟',
                    'help_text' => 'اختر نعم إذا كانت بيانات الشركة المصنعة أو الموديل لها قيمة تشغيلية أو تحليلية أو صيانـية فعلية.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 9,
                    'report_category' => 'field_rule',
                    'target_entity' => 'battery',
                    'options' => [],
                ],
                [
                    'seed_key' => 'battery.activity_assignment_rule',
                    'title' => 'كيف يجب إسناد النشاط التشغيلي للبطارية؟',
                    'help_text' => 'نشاط البطارية يجب أن يعتمد على نفس OperationalActivity المستخدم في العنبر، ويحدد هذا السؤال عدد الأنشطة المسموح بها ومصدرها.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 10,
                    'report_category' => 'relationship_rule',
                    'target_entity' => 'battery',
                    'options' => [
                        ['label' => 'نشاط واحد فقط من الأنشطة المتاحة للعنبر', 'value' => 'single_from_barn'],
                        ['label' => 'أكثر من نشاط من الأنشطة المتاحة للعنبر', 'value' => 'multiple_from_barn'],
                        ['label' => 'نشاط مستقل غير مقيد بأنشطة العنبر', 'value' => 'independent'],
                    ],
                ],
                [
                    'seed_key' => 'battery.activity_change_policy',
                    'title' => 'متى يسمح بتغيير النشاط التشغيلي للبطارية بعد بدء استخدامها؟',
                    'help_text' => 'يجب ألا يسمح تغيير النشاط بخلق تعارض مع الحيوانات أو الدورات التشغيلية أو متطلبات التطهير والجاهزية، ويجب الحفاظ على تاريخ التغييرات.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 11,
                    'report_category' => 'workflow_rule',
                    'target_entity' => 'battery',
                    'options' => [
                        ['label' => 'لا يسمح بتغيير النشاط بعد بدء التشغيل', 'value' => 'never_after_start'],
                        ['label' => 'يسمح عند خلو البطارية من الحيوانات فقط', 'value' => 'when_empty_only'],
                        ['label' => 'يسمح فقط عندما تكون البطارية فارغة وجاهزة فعليًا لاستقبال حيوانات جديدة وفق إعدادات التشغيل، مع حفظ تاريخ التغيير', 'value' => 'when_empty_and_ready_with_history'],
                    ],
                ],
                [
                    'seed_key' => 'battery.structure_model',
                    'title' => 'ما مستوى المرونة المطلوب لوصف التكوين الهيكلي للبطارية؟',
                    'help_text' => 'التكوين الهيكلي هو المصدر الذي سيولد منه النظام الأقفاص، لذلك يجب أن يمثل الواقع الفيزيائي بما يكفي دون افتراض أن كل البطاريات منتظمة بنفس الشكل.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 12,
                    'report_category' => 'architecture_rule',
                    'target_entity' => 'battery_structure',
                    'options' => [
                        ['label' => 'نموذج بسيط: عدد مستويات / صفوف × عدد أقفاص ثابت', 'value' => 'simple_uniform'],
                        ['label' => 'نموذج هيكلي مرن فقط للتصميمات المختلفة', 'value' => 'flexible_only'],
                        ['label' => 'دعم النموذج البسيط والنموذج المرن معًا', 'value' => 'simple_and_flexible'],
                    ],
                ],
                [
                    'seed_key' => 'battery.structure_components',
                    'title' => 'ما العناصر التي يجب أن يستطيع التكوين الهيكلي للبطارية تمثيلها عند الحاجة؟',
                    'help_text' => 'اختر العناصر اللازمة لوصف موقع كل قفص فعليًا داخل البطارية وتوليد الأكواد والترتيب بصورة صحيحة.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 13,
                    'report_category' => 'structure_rule',
                    'target_entity' => 'battery_structure',
                    'options' => [
                        ['label' => 'الأوجه / الجوانب', 'value' => 'sides'],
                        ['label' => 'المستويات / الأدوار', 'value' => 'levels'],
                        ['label' => 'الأقسام / القطاعات داخل البطارية', 'value' => 'segments'],
                        ['label' => 'عدد الأقفاص داخل كل وحدة هيكلية', 'value' => 'cages_per_unit'],
                        ['label' => 'ترتيب الوحدات الهيكلية', 'value' => 'unit_order'],
                        ['label' => 'اتجاه الترقيم', 'value' => 'numbering_direction'],
                        ['label' => 'تسمية / رمز الوحدة الهيكلية', 'value' => 'unit_code'],
                    ],
                ],
                [
                    'seed_key' => 'battery.non_uniform_structure',
                    'title' => 'هل يجب دعم بطاريات يختلف فيها عدد الأقفاص بين جانب وآخر أو مستوى وآخر؟',
                    'help_text' => 'اختر نعم إذا كان النظام يجب ألا يفترض عددًا ثابتًا من الأقفاص في جميع أجزاء البطارية، بحيث يمكن تمثيل تصميمات غير منتظمة عند الحاجة.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 14,
                    'report_category' => 'architecture_rule',
                    'target_entity' => 'battery_structure',
                    'options' => [],
                ],
                [
                    'seed_key' => 'battery.total_cages_strategy',
                    'title' => 'كيف تريد تحديد إجمالي عدد العيون / الأقفاص في البطارية؟',
                    'help_text' => 'إجمالي عدد الأقفاص الفعلي يفضل أن يكون مشتقًا من التكوين الهيكلي بدل إدخاله كقيمة مستقلة يمكن أن تتعارض مع الواقع.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 15,
                    'report_category' => 'calculation_rule',
                    'target_entity' => 'battery',
                    'options' => [
                        ['label' => 'يحسب تلقائيًا من التكوين الهيكلي للبطارية', 'value' => 'calculated_from_structure'],
                        ['label' => 'يتم إدخاله يدويًا', 'value' => 'manual'],
                        ['label' => 'يحسب تلقائيًا مع السماح بقيمة تخطيطية منفصلة عند الحاجة', 'value' => 'calculated_with_planned_value'],
                    ],
                ],
                [
                    'seed_key' => 'battery.auto_generate_cages',
                    'title' => 'هل يجب أن ينشئ النظام العيون / الأقفاص تلقائيًا عند إنشاء تكوين البطارية؟',
                    'help_text' => 'عند اعتماد تكوين البطارية ينشئ النظام الأقفاص الناتجة منه بدل إدخال كل قفص يدويًا، مع الحفاظ على علاقة كل قفص بموقعه داخل الهيكل.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 16,
                    'report_category' => 'workflow_rule',
                    'target_entity' => 'battery',
                    'options' => [],
                ],
                [
                    'seed_key' => 'battery.manual_cage_creation_policy',
                    'title' => 'هل يجب منع إنشاء القفص / العين يدويًا بصورة مستقلة عن البطارية؟',
                    'help_text' => 'إذا كانت الإجابة نعم، فلا يوجد Create مستقل للقفص؛ جميع الأقفاص تنشأ من تكوين البطارية للحفاظ على عددها وهويتها وموقعها الهيكلي.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 17,
                    'report_category' => 'architecture_rule',
                    'target_entity' => 'cage',
                    'options' => [],
                ],
                [
                    'seed_key' => 'battery.cage_generation_timing',
                    'title' => 'متى يجب أن يقوم النظام بتوليد الأقفاص الناتجة من تكوين البطارية؟',
                    'help_text' => 'حدد هل يتم التوليد فور الحفظ أم بعد استكمال التكوين وتنفيذ أمر صريح يتيح للمستخدم مراجعة الشكل المتوقع قبل إنشاء الأقفاص.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 18,
                    'report_category' => 'workflow_rule',
                    'target_entity' => 'battery',
                    'options' => [
                        ['label' => 'فور حفظ البطارية أو تعديل تكوينها', 'value' => 'immediate_on_save'],
                        ['label' => 'بعد استكمال التكوين ثم تنفيذ أمر صريح «إنشاء الأقفاص»', 'value' => 'explicit_generate_action'],
                        ['label' => 'عند اعتماد البطارية للتشغيل فقط', 'value' => 'on_commissioning'],
                    ],
                ],
                [
                    'seed_key' => 'battery.cage_generation_preview',
                    'title' => 'هل يجب عرض معاينة للأقفاص قبل تأكيد عملية التوليد؟',
                    'help_text' => 'المعاينة تعرض العدد المتوقع والأكواد والمواقع الهيكلية حتى يكتشف المستخدم أخطاء التكوين قبل إنشاء الأقفاص فعليًا.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 19,
                    'report_category' => 'ui_requirement',
                    'target_entity' => 'battery',
                    'options' => [],
                ],
                [
                    'seed_key' => 'battery.cage_numbering_strategy',
                    'title' => 'ما نمط ترقيم العيون / الأقفاص الأنسب داخل البطارية؟',
                    'help_text' => 'حدد الطريقة التي تطابق الترقيم الميداني وتساعد العامل على الوصول إلى الموقع الصحيح داخل البطارية.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 20,
                    'report_category' => 'numbering_rule',
                    'target_entity' => 'cage',
                    'options' => [
                        ['label' => 'ترقيم تسلسلي داخل البطارية مثل B01-01، B01-02', 'value' => 'sequential'],
                        ['label' => 'ترقيم يعكس الموقع الهيكلي مثل الجانب / المستوى / الترتيب', 'value' => 'structure_based'],
                        ['label' => 'قالب ترقيم مخصص يحدد حسب التشغيل الفعلي', 'value' => 'custom'],
                    ],
                ],
                [
                    'seed_key' => 'battery.cage_numbering_direction',
                    'title' => 'هل يجب أن يسمح تكوين البطارية بتحديد اتجاه وترتيب توليد أرقام الأقفاص؟',
                    'help_text' => 'مثل الترقيم من اليمين إلى اليسار أو العكس، ومن أعلى إلى أسفل أو العكس، بما يطابق ترتيب الأقفاص الفعلي في الموقع.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 21,
                    'report_category' => 'numbering_rule',
                    'target_entity' => 'cage',
                    'options' => [],
                ],
                [
                    'seed_key' => 'battery.generated_cage_initial_state',
                    'title' => 'بعد توليد الأقفاص من البطارية، هل تصبح قابلة للتشغيل فورًا أم تمر بمرحلة تفعيل مستقلة؟',
                    'help_text' => 'مرحلة التوليد تعني وجود القفص هيكليًا، بينما التفعيل يعني السماح باستخدامه تشغيليًا. لا يجب خلط مرحلة التأسيس هذه بالحالة التشغيلية أو الإشغال.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 22,
                    'report_category' => 'workflow_rule',
                    'target_entity' => 'cage',
                    'options' => [
                        ['label' => 'تصبح قابلة للتشغيل فورًا بعد التوليد', 'value' => 'active_immediately'],
                        ['label' => 'تنشأ أولًا في مرحلة انتظار المراجعة / التفعيل', 'value' => 'pending_activation'],
                        ['label' => 'تحدد طريقة التفعيل من إعدادات التشغيل', 'value' => 'from_operational_settings'],
                    ],
                ],
                [
                    'seed_key' => 'battery.cage_activation_scope',
                    'title' => 'كيف يجب تفعيل الأقفاص المولدة من البطارية؟',
                    'help_text' => 'حدد هل التفعيل يتم للبطارية كلها دفعة واحدة أم يمكن استبعاد أقفاص غير جاهزة وتفعيل أقفاص محددة فقط.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 23,
                    'report_category' => 'workflow_rule',
                    'target_entity' => 'cage',
                    'options' => [
                        ['label' => 'تفعيل جميع أقفاص البطارية معًا فقط', 'value' => 'all_at_once'],
                        ['label' => 'تفعيل أقفاص مختارة فقط', 'value' => 'selected_only'],
                        ['label' => 'دعم التفعيل الجماعي والتفعيل الانتقائي معًا', 'value' => 'both'],
                    ],
                ],
                [
                    'seed_key' => 'battery.cage_activation_prerequisites',
                    'title' => 'ما الشروط التي يجب تحققها قبل السماح بتفعيل قفص مولد؟',
                    'help_text' => 'حدد شروط الجاهزية الأولية قبل أن يصبح القفص مؤهلًا للدخول في التشغيل والتسكين.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 24,
                    'report_category' => 'validation_rule',
                    'target_entity' => 'cage',
                    'options' => [
                        ['label' => 'اكتمال واعتماد التكوين الهيكلي للبطارية', 'value' => 'structure_approved'],
                        ['label' => 'وجود كود صالح وفريد للقفص', 'value' => 'valid_unique_code'],
                        ['label' => 'أن تكون البطارية في حالة تسمح بالتشغيل', 'value' => 'battery_operational'],
                        ['label' => 'أن يكون العنبر متاحًا تشغيليًا', 'value' => 'barn_operational'],
                        ['label' => 'اكتمال البيانات المطلوبة للقفص', 'value' => 'required_cage_data_complete'],
                        ['label' => 'عدم وجود مانع صيانة أو تجهيز يمنع التشغيل', 'value' => 'no_operational_block'],
                    ],
                ],
                [
                    'seed_key' => 'battery.regeneration_before_history',
                    'title' => 'هل يسمح بتصحيح تكوين البطارية وإعادة توليد الأقفاص إذا لم يوجد أي سجل تشغيلي على الأقفاص الناتجة؟',
                    'help_text' => 'إذا اكتشف المستخدم خطأ قبل دخول أي قفص في التشغيل، يمكن حذف الأقفاص المولدة غير المستخدمة وتصحيح التكوين ثم إعادة توليدها دون الإضرار بتاريخ فعلي.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 25,
                    'report_category' => 'workflow_rule',
                    'target_entity' => 'battery_structure',
                    'options' => [],
                ],
                [
                    'seed_key' => 'battery.structural_lock_trigger',
                    'title' => 'ما الذي يجب أن يقفل هوية الأقفاص ويمنع إعادة التوليد الهدام للبطارية؟',
                    'help_text' => 'بمجرد دخول القفص في التاريخ التشغيلي يجب تحديد متى تصبح هويته وموقعه جزءًا ثابتًا من السجل الذي لا يجوز حذفه أو إعادة بنائه.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 26,
                    'report_category' => 'lifecycle_rule',
                    'target_entity' => 'battery_structure',
                    'options' => [
                        ['label' => 'أول حركة حيوان فقط مثل التسكين أو النقل', 'value' => 'first_animal_movement'],
                        ['label' => 'أي سجل تشغيلي على أي قفص مثل التسكين أو النقل أو التطهير أو الصيانة', 'value' => 'any_operational_record'],
                    ],
                ],
                [
                    'seed_key' => 'battery.structural_lock_effects',
                    'title' => 'بعد وجود سجل تشغيلي على أي قفص، ما التغييرات الهيكلية التي يجب منعها على البطارية؟',
                    'help_text' => 'الهدف هو الحفاظ على هوية الأقفاص ومواقعها التاريخية وعدم كسر الحركات أو سجلات التطهير والصيانة المرتبطة بها.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 27,
                    'report_category' => 'lifecycle_rule',
                    'target_entity' => 'battery_structure',
                    'options' => [
                        ['label' => 'تغيير عدد الأقفاص', 'value' => 'change_cage_count'],
                        ['label' => 'إعادة توليد الأقفاص', 'value' => 'regenerate_cages'],
                        ['label' => 'حذف قفص له تاريخ', 'value' => 'delete_historical_cage'],
                        ['label' => 'إعادة ترقيم الأقفاص المستخدمة', 'value' => 'renumber_historical_cages'],
                        ['label' => 'تغيير الموقع الهيكلي التاريخي للقفص', 'value' => 'change_historical_position'],
                        ['label' => 'إعادة استخدام هوية أو كود قفص قديم لقفص آخر', 'value' => 'reuse_historical_identity'],
                    ],
                ],
                [
                    'seed_key' => 'battery.physical_reconfiguration_after_history',
                    'title' => 'كيف يجب التعامل مع تعديل مادي حقيقي يغيّر عدد أو توزيع الأقفاص بعد وجود تاريخ تشغيلي للبطارية؟',
                    'help_text' => 'هذا القرار يحمي التاريخ عندما تتغير البطارية فعليًا بصورة تجعل تكوينها الجديد مختلفًا جوهريًا عن التكوين الذي نشأت عليه السجلات السابقة.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 28,
                    'report_category' => 'lifecycle_rule',
                    'target_entity' => 'battery',
                    'options' => [
                        ['label' => 'تعديل البطارية الحالية مع الاحتفاظ بنفس الهوية', 'value' => 'modify_current_identity'],
                        ['label' => 'إخراج البطارية القديمة من الخدمة مع الحفاظ على أقفاصها وتاريخها، ثم إنشاء بطارية جديدة بهوية وكود وتكوين جديد', 'value' => 'retire_old_create_new'],
                        ['label' => 'يحتاج الأمر إلى مراجعة حالة بحالة', 'value' => 'case_by_case_review'],
                    ],
                ],
                [
                    'seed_key' => 'battery.cage_identity_after_history',
                    'title' => 'بعد وجود تاريخ تشغيلي للقفص، هل يجب الحفاظ على هويته وكوده وموقعه التاريخي ومنع استخدامها لقفص آخر؟',
                    'help_text' => 'القفص قد يخرج مؤقتًا للصيانة أو التطهير ثم يعود للخدمة بنفس الهوية؛ التوقف أو الصيانة لا ينشئان قفصًا جديدًا ولا يمحوان السجل القديم.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 29,
                    'report_category' => 'lifecycle_rule',
                    'target_entity' => 'cage',
                    'options' => [],
                ],
                [
                    'seed_key' => 'battery.statuses',
                    'title' => 'ما الحالات التشغيلية التي يمكن أن تكون عليها البطارية؟',
                    'help_text' => 'حالة البطارية التشغيلية مستقلة عن مرحلة إنشاء الأقفاص وتفعيلها، ومستقلة عن إشغال الأقفاص التابعة لها.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 30,
                    'report_category' => 'lookup_values',
                    'target_entity' => 'battery_status',
                    'options' => [
                        ['label' => 'نشطة', 'value' => 'active'],
                        ['label' => 'متوقفة', 'value' => 'stopped'],
                        ['label' => 'تحت الصيانة', 'value' => 'maintenance'],
                        ['label' => 'أخرى', 'value' => 'other'],
                    ],
                ],
                [
                    'seed_key' => 'battery.status_management',
                    'title' => 'هل حالات البطارية ثابتة أم قابلة للإدارة؟',
                    'help_text' => 'حدد هل يتم تمثيل حالات البطارية كقيم ثابتة داخل النظام أم كقائمة قابلة للإضافة والتعديل.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 31,
                    'report_category' => 'value_management',
                    'target_entity' => 'battery_status',
                    'options' => [
                        ['label' => 'قيم ثابتة داخل النظام', 'value' => 'fixed'],
                        ['label' => 'قابلة للإضافة والتعديل من لوحة التحكم', 'value' => 'managed'],
                    ],
                ],
                [
                    'seed_key' => 'battery.status_change_requires_empty',
                    'title' => 'هل يجب منع تغيير حالة البطارية إلى «متوقفة» أو «تحت الصيانة» طالما توجد حيوانات في أي قفص تابع لها؟',
                    'help_text' => 'إذا كانت الإجابة نعم، يجب أولًا إخلاء الحيوانات فعليًا من جميع الأقفاص التابعة للبطارية قبل السماح بالانتقال إلى حالة تمنع التشغيل.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 32,
                    'report_category' => 'workflow_rule',
                    'target_entity' => 'battery',
                    'options' => [],
                ],
                [
                    'seed_key' => 'battery.status_change_requires_inactive_cages',
                    'title' => 'هل يجب منع تغيير حالة البطارية إلى «متوقفة» أو «تحت الصيانة» طالما توجد أقفاص تابعة لها ما زالت نشطة تشغيليًا؟',
                    'help_text' => 'هذا الشرط مستقل عن شرط خلو البطارية من الحيوانات، ويطبق التسلسل من الأسفل إلى الأعلى: القفص يصبح في حالة غير تشغيلية مسموح بها أولًا ثم يمكن تغيير حالة البطارية.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 33,
                    'report_category' => 'workflow_rule',
                    'target_entity' => 'battery',
                    'options' => [],
                ],
                [
                    'seed_key' => 'battery.parent_status_effect',
                    'title' => 'إذا أصبح العنبر غير متاح تشغيليًا، هل تصبح البطارية وأقفاصها غير متاحة فعليًا دون تغيير حالاتهم المحلية المخزنة تلقائيًا؟',
                    'help_text' => 'يميز هذا القرار بين الحالة المحلية للبطارية والقفص وبين الإتاحة التشغيلية الفعلية الناتجة من حالة العنبر الأب، دون تطبيق Cascade لتغيير الحالات المخزنة.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 34,
                    'report_category' => 'business_rule',
                    'target_entity' => 'battery',
                    'options' => [],
                ],
                [
                    'seed_key' => 'battery.non_operational_child_effect',
                    'title' => 'عند إيقاف البطارية أو وضعها تحت الصيانة، هل يجب منع التسكين والتشغيل الجديد في جميع الأقفاص التابعة لها مع الاحتفاظ بحالاتها المحلية وتاريخها؟',
                    'help_text' => 'إيقاف البطارية يجعل الأقفاص غير متاحة فعليًا بسبب حالة الأب، لكنه لا يغير حالات الأقفاص المحلية تلقائيًا ولا يحذف سجلاتها السابقة.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 35,
                    'report_category' => 'business_rule',
                    'target_entity' => 'battery',
                    'options' => [],
                ],
                [
                    'seed_key' => 'battery.sanitation_supported_scopes',
                    'title' => 'على أي نطاق يجب أن يستطيع النظام تنفيذ عملية التطهير؟',
                    'help_text' => 'عملية التطهير Workflow تشغيلي مسجل ويمكن تنفيذها على قفص واحد أو مجموعة أقفاص أو بطارية كاملة حسب الحاجة.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 36,
                    'report_category' => 'workflow_rule',
                    'target_entity' => 'sanitation_operation',
                    'options' => [
                        ['label' => 'قفص واحد', 'value' => 'single_cage'],
                        ['label' => 'مجموعة أقفاص', 'value' => 'cage_group'],
                        ['label' => 'بطارية كاملة', 'value' => 'whole_battery'],
                    ],
                ],
                [
                    'seed_key' => 'battery.sanitation_requires_empty_cages',
                    'title' => 'هل يجب منع بدء تطهير البطارية بالكامل ما لم تكن جميع الأقفاص التابعة لها خالية من الحيوانات؟',
                    'help_text' => 'عملية التطهير على مستوى البطارية لا تبدأ بينما توجد حيوانات داخل أي قفص تابع لها؛ يجب إتمام الإخلاء أولًا.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 37,
                    'report_category' => 'workflow_rule',
                    'target_entity' => 'sanitation_operation',
                    'options' => [],
                ],
                [
                    'seed_key' => 'battery.sanitation_generates_cage_records',
                    'title' => 'عند تنفيذ تطهير على البطارية أو مجموعة أقفاص، هل يجب إنشاء سجل تطهير تلقائي لكل قفص مشمول مع ربطه بالعملية الأصلية؟',
                    'help_text' => 'يسجل العامل العملية الجماعية مرة واحدة، ويقوم النظام بإنشاء السجلات الفردية تلقائيًا حتى يظهر تاريخ التطهير بصورة صحيحة داخل Timeline كل قفص.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 38,
                    'report_category' => 'audit_rule',
                    'target_entity' => 'sanitation_record',
                    'options' => [],
                ],
                [
                    'seed_key' => 'battery.sanitation_policy_from_settings',
                    'title' => 'أي قواعد تخص التطهير وإعادة الاستخدام يجب أن تأتي من ملف إعدادات التشغيل المرتبط بالعنبر؟',
                    'help_text' => 'هذه القواعد لا تثبت داخل البطارية أو القفص نفسه؛ يتم تطبيق السياسة السارية في Operational Settings Profile الخاص بالعنبر.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 39,
                    'report_category' => 'settings_integration_rule',
                    'target_entity' => 'operational_settings_profile',
                    'options' => [
                        ['label' => 'هل التطهير إلزامي بعد انتهاء دورة الإشغال', 'value' => 'required_after_occupancy_cycle'],
                        ['label' => 'هل بدء / استمرار التطهير يمنع التسكين', 'value' => 'blocks_housing_during_sanitation'],
                        ['label' => 'هل توجد فترة انتظار بعد اكتمال التطهير', 'value' => 'post_sanitation_waiting_period'],
                        ['label' => 'هل انتهاء الصيانة يستلزم تطهيرًا قبل إعادة الاستخدام', 'value' => 'required_after_maintenance'],
                    ],
                ],
                [
                    'seed_key' => 'battery.sanitation_record_fields',
                    'title' => 'ما البيانات التي يجب أن يحتفظ بها سجل تنفيذ التطهير؟',
                    'help_text' => 'اختر البيانات اللازمة لإثبات تنفيذ العملية وتتبعها تاريخيًا دون تحويل السجل إلى بروتوكول طبي تفصيلي غير مطلوب حاليًا.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 40,
                    'report_category' => 'audit_rule',
                    'target_entity' => 'sanitation_record',
                    'options' => [
                        ['label' => 'وقت بدء العملية', 'value' => 'started_at'],
                        ['label' => 'وقت اكتمال العملية', 'value' => 'completed_at'],
                        ['label' => 'المستخدم المنفذ', 'value' => 'executed_by'],
                        ['label' => 'حالة العملية: جاري / مكتمل / ملغي', 'value' => 'status'],
                        ['label' => 'سبب / Trigger العملية', 'value' => 'trigger'],
                        ['label' => 'نطاق العملية: قفص / مجموعة / بطارية', 'value' => 'scope'],
                        ['label' => 'ملاحظات', 'value' => 'notes'],
                        ['label' => 'مرجع العملية الجماعية الأصلية للسجل المولد تلقائيًا', 'value' => 'parent_operation_reference'],
                        ['label' => 'مرجع إعدادات التشغيل المطبقة وقت العملية', 'value' => 'settings_reference'],
                    ],
                ],
                [
                    'seed_key' => 'battery.sanitation_completion_rule',
                    'title' => 'كيف يتم اعتبار عملية التطهير مكتملة من ناحية الاعتماد؟',
                    'help_text' => 'حدد هل يكفي أن يسجل المستخدم المنفذ إتمام العملية أم يلزم اعتماد مستخدم مسؤول آخر أو تحدد القاعدة من إعدادات التشغيل.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 41,
                    'report_category' => 'workflow_rule',
                    'target_entity' => 'sanitation_operation',
                    'options' => [
                        ['label' => 'المستخدم المنفذ يسجل إتمامها ولا تحتاج اعتمادًا ثانيًا', 'value' => 'executor_completes'],
                        ['label' => 'تحتاج اعتماد مستخدم مسؤول آخر', 'value' => 'second_approval_required'],
                        ['label' => 'تحدد طريقة الاعتماد من إعدادات التشغيل', 'value' => 'from_operational_settings'],
                    ],
                ],
                [
                    'seed_key' => 'battery.sanitation_settings_reference',
                    'title' => 'هل يجب أن يحتفظ سجل التطهير بمرجع إلى إعدادات التشغيل التي كانت مطبقة وقت التنفيذ؟',
                    'help_text' => 'الاحتفاظ بمرجع الإعدادات أو نسختها يسمح بتفسير السجل تاريخيًا إذا تغيرت لاحقًا قواعد مثل إلزام التطهير أو فترة الانتظار قبل إعادة الاستخدام.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 42,
                    'report_category' => 'history_rule',
                    'target_entity' => 'sanitation_record',
                    'options' => [],
                ],
                [
                    'seed_key' => 'battery.derived_metrics',
                    'title' => 'ما البيانات التي يجب أن يحسبها النظام تلقائيًا من الأقفاص التابعة للبطارية بدل إدخالها يدويًا؟',
                    'help_text' => 'هذه المؤشرات مشتقة من الأقفاص والحركات والحالات وقواعد الجاهزية، ويجب ألا تخزن كقيم يدوية قابلة للتعارض مع الواقع التشغيلي.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 43,
                    'report_category' => 'calculation_rule',
                    'target_entity' => 'battery',
                    'options' => [
                        ['label' => 'إجمالي عدد الأقفاص الفعلي', 'value' => 'total_cages'],
                        ['label' => 'عدد الأقفاص النشطة محليًا', 'value' => 'active_cages'],
                        ['label' => 'عدد الأقفاص المشغولة', 'value' => 'occupied_cages'],
                        ['label' => 'عدد الأقفاص المؤهلة فعليًا للتسكين', 'value' => 'housing_eligible_cages'],
                        ['label' => 'عدد الحيوانات الحالي', 'value' => 'current_animals'],
                        ['label' => 'السعة الفعلية', 'value' => 'effective_capacity'],
                        ['label' => 'الأماكن المتاحة فعليًا', 'value' => 'available_capacity'],
                    ],
                ],
                [
                    'seed_key' => 'battery.delete_policy',
                    'title' => 'كيف يجب التعامل مع حذف أو إخراج البطارية من الخدمة؟',
                    'help_text' => 'حدد السياسة التي تحافظ على الأقفاص والحركات وسجلات الصيانة والتطهير التاريخية، مع السماح بتصحيح أخطاء التكوين قبل وجود أي تاريخ تشغيلي.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 44,
                    'report_category' => 'lifecycle_rule',
                    'target_entity' => 'battery',
                    'options' => [
                        ['label' => 'يسمح بالحذف إذا لم يتم توليد أقفاص لها، وإلا يتم إيقافها فقط', 'value' => 'delete_before_generation_only'],
                        ['label' => 'يسمح بالحذف أو إعادة البناء إذا لم يوجد أي سجل تشغيلي، وبعد وجود تاريخ يتم إخراجها من الخدمة مع الحفاظ على السجل', 'value' => 'delete_or_rebuild_if_no_history_otherwise_retire'],
                        ['label' => 'لا يسمح بالحذف نهائيًا بعد إنشاء سجل البطارية ويتم إخراجها من الخدمة فقط', 'value' => 'retire_only'],
                    ],
                ],
                [
                    'seed_key' => 'battery.additional_requirements',
                    'title' => 'هل توجد بيانات أو خصائص أو متطلبات أخرى تخص البطارية ولم نتطرق إليها؟',
                    'help_text' => 'اكتب أي ملاحظة أو متطلب إضافي يخص البطارية أو تكوينها أو علاقتها بالأقفاص ويحتاج إلى مراجعة قبل اعتماد التصميم الفني.',
                    'type' => QuestionType::TEXTAREA,
                    'is_required' => false,
                    'sort_order' => 45,
                    'report_category' => 'manual_review',
                    'target_entity' => 'battery',
                    'options' => [],
                ],
            ];

            app(QuestionSeederSyncService::class)->sync(
                mainSectionName: 'إدارة البيانات الأساسية',
                sectionName: 'بيانات البطارية',
                questions: $questions,
                prune: true,
                preserveAnswers: true,
            );
        });
    }

    /**
     * Adopt stable seed keys only for legacy questions whose semantic meaning and
     * answer type remain compatible with the current definition. Any answered
     * legacy question that no longer maps safely will make preserveAnswers stop the
     * sync instead of deleting or silently changing that answer.
     */
    private function adoptLegacySeedKeys(): void
    {
        $mainSection = QuestionnaireSection::query()
            ->whereNull('parent_id')
            ->where('name', 'إدارة البيانات الأساسية')
            ->first();

        if (! $mainSection) {
            return;
        }

        $section = QuestionnaireSection::query()
            ->where('parent_id', $mainSection->id)
            ->where('name', 'بيانات البطارية')
            ->first();

        if (! $section) {
            return;
        }

        $legacyMap = [
            'ما البيانات التي يجب أن يحتوي عليها ملف البطارية؟' => 'battery.fields',
            'كيف تريد تعريف البطارية داخل النظام؟' => 'battery.identity_strategy',
            'كيف تريد التعامل مع كود البطارية؟' => 'battery.code_strategy',
            'هل نحتاج إلى تسجيل الشركة المصنعة أو النوع / الموديل للبطارية؟' => 'battery.manufacturer_tracking',
            'ما الحالات التشغيلية التي يمكن أن تكون عليها البطارية؟' => 'battery.statuses',
            'هل حالات البطارية ثابتة أم قابلة للإدارة؟' => 'battery.status_management',
            'عند إيقاف البطارية أو وضعها تحت الصيانة، هل يجب منع التسكين الجديد في جميع العيون التابعة لها مع الاحتفاظ بالتاريخ السابق؟' => 'battery.non_operational_child_effect',
            'هل توجد بيانات أو خصائص أو متطلبات أخرى تخص البطارية ولم نتطرق إليها؟' => 'battery.additional_requirements',
        ];

        foreach ($legacyMap as $legacyTitle => $seedKey) {
            $alreadyAdopted = QuestionnaireQuestion::query()
                ->where('section_id', $section->id)
                ->where('seed_key', $seedKey)
                ->exists();

            if ($alreadyAdopted) {
                continue;
            }

            $legacyQuestion = QuestionnaireQuestion::query()
                ->where('section_id', $section->id)
                ->whereNull('seed_key')
                ->where('title', $legacyTitle)
                ->first();

            if (! $legacyQuestion) {
                continue;
            }

            $legacyQuestion->seed_key = $seedKey;
            $legacyQuestion->save();
        }
    }
}
