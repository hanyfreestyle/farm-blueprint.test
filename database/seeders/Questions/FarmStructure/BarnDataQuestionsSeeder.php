<?php

namespace Database\Seeders\Questions\FarmStructure;

use App\Enums\Questionnaire\QuestionType;
use App\Models\QuestionnaireQuestion;
use App\Models\QuestionnaireSection;
use App\Services\Questionnaire\QuestionSeederSyncService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarnDataQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $this->adoptLegacySeedKeys();

            $questions = [
                [
                    'seed_key' => 'barn.fields',
                    'title' => 'ما البيانات التي يجب أن يحتوي عليها ملف العنبر؟',
                    'help_text' => 'حدد البيانات والارتباطات الأساسية التي يجب أن يدعمها ملف العنبر. الأنشطة وأنظمة التهوية والتبريد والتدفئة معرفة كـMaster Data مستقلة ويتم ربط العنبر بها بدل إدخالها كنص حر.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 1,
                    'report_category' => 'field',
                    'target_entity' => 'barn',
                    'options' => [
                        ['label' => 'اسم العنبر', 'value' => 'name'],
                        ['label' => 'كود العنبر', 'value' => 'code'],
                        ['label' => 'المزرعة التابع لها', 'value' => 'farm'],
                        ['label' => 'النشاط التشغيلي', 'value' => 'usage'],
                        ['label' => 'ملف إعدادات التشغيل', 'value' => 'settings_profile'],
                        ['label' => 'نظام التهوية', 'value' => 'ventilation_system'],
                        ['label' => 'نظام التبريد', 'value' => 'cooling_system'],
                        ['label' => 'نظام التدفئة', 'value' => 'heating_system'],
                        ['label' => 'المساحة', 'value' => 'area'],
                        ['label' => 'السعة التصميمية / التقريبية', 'value' => 'capacity'],
                        ['label' => 'الحالة التشغيلية', 'value' => 'status'],
                        ['label' => 'تاريخ بدء التشغيل', 'value' => 'started_at'],
                        ['label' => 'وصف الموقع داخل المزرعة', 'value' => 'location_description'],
                        ['label' => 'ملاحظات', 'value' => 'notes'],
                    ],
                ],
                [
                    'seed_key' => 'barn.required_fields',
                    'title' => 'أي من بيانات العنبر يجب أن تكون إلزامية عند إنشائه؟',
                    'help_text' => 'حدد الحد الأدنى من البيانات والعلاقات التي لا يسمح النظام بإنشاء عنبر بدونها.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 2,
                    'report_category' => 'rule',
                    'target_entity' => 'barn',
                    'options' => [
                        ['label' => 'اسم العنبر', 'value' => 'name'],
                        ['label' => 'كود العنبر', 'value' => 'code'],
                        ['label' => 'المزرعة التابع لها', 'value' => 'farm'],
                        ['label' => 'النشاط التشغيلي', 'value' => 'usage'],
                        ['label' => 'ملف إعدادات التشغيل', 'value' => 'settings_profile'],
                        ['label' => 'نظام التهوية', 'value' => 'ventilation_system'],
                        ['label' => 'نظام التبريد', 'value' => 'cooling_system'],
                        ['label' => 'نظام التدفئة', 'value' => 'heating_system'],
                        ['label' => 'المساحة', 'value' => 'area'],
                        ['label' => 'السعة التصميمية / التقريبية', 'value' => 'capacity'],
                        ['label' => 'الحالة التشغيلية', 'value' => 'status'],
                        ['label' => 'تاريخ بدء التشغيل', 'value' => 'started_at'],
                        ['label' => 'وصف الموقع داخل المزرعة', 'value' => 'location_description'],
                        ['label' => 'ملاحظات', 'value' => 'notes'],
                    ],
                ],
                [
                    'seed_key' => 'barn.code_strategy',
                    'title' => 'كيف يجب التعامل مع كود العنبر؟',
                    'help_text' => 'حدد هل كود العنبر غير مطلوب أو يتم إدخاله يدويًا أو يولده النظام تلقائيًا.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 3,
                    'report_category' => 'field_rule',
                    'target_entity' => 'barn',
                    'options' => [
                        ['label' => 'لا نحتاج كودًا للعنبر', 'value' => 'not_required'],
                        ['label' => 'يتم إدخال الكود يدويًا', 'value' => 'manual'],
                        ['label' => 'يقوم النظام بتوليد الكود تلقائيًا', 'value' => 'automatic'],
                    ],
                ],
                [
                    'seed_key' => 'barn.code_unique_scope',
                    'title' => 'ما نطاق عدم تكرار كود العنبر؟',
                    'help_text' => 'حدد نطاق التحقق من تكرار كود العنبر إذا تم استخدام الأكواد في النظام.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 4,
                    'report_category' => 'rule',
                    'target_entity' => 'barn',
                    'options' => [
                        ['label' => 'يجب أن يكون الكود فريدًا داخل نفس المزرعة فقط', 'value' => 'unique_within_farm'],
                        ['label' => 'يجب أن يكون الكود فريدًا على مستوى النظام بالكامل', 'value' => 'global_unique'],
                        ['label' => 'يسمح بتكرار الكود', 'value' => 'duplicates_allowed'],
                    ],
                ],
                [
                    'seed_key' => 'barn.unique_name_within_farm',
                    'title' => 'هل يجب منع تكرار اسم العنبر داخل نفس المزرعة؟',
                    'help_text' => 'حدد هل يمكن أن تحتوي المزرعة الواحدة على أكثر من عنبر يحمل نفس الاسم.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 5,
                    'report_category' => 'rule',
                    'target_entity' => 'barn',
                    'options' => [],
                ],
                [
                    'seed_key' => 'barn.farm_relation',
                    'title' => 'هل يجب أن يتبع كل عنبر مزرعة واحدة؟',
                    'help_text' => 'يثبت هذا السؤال العلاقة التنظيمية الأساسية Farm → Barn بحيث يكون لكل عنبر مرجع لمزرعة واحدة.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 6,
                    'report_category' => 'relationship_rule',
                    'target_entity' => 'barn',
                    'options' => [],
                ],
                [
                    'seed_key' => 'barn.activity_requirement',
                    'title' => 'متى يجب تحديد النشاط التشغيلي للعنبر؟',
                    'help_text' => 'الأنشطة التشغيلية معرفة مسبقًا كـMaster Data مستقلة؛ المطلوب هنا تحديد إلزامية ربط العنبر بها وتوقيت ذلك.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 7,
                    'report_category' => 'relationship_rule',
                    'target_entity' => 'barn_activity',
                    'options' => [
                        ['label' => 'إلزامي عند إنشاء العنبر', 'value' => 'required_on_create'],
                        ['label' => 'يمكن إنشاء العنبر بدونه ولكن يجب تحديده قبل بدء التشغيل', 'value' => 'required_before_operation'],
                        ['label' => 'اختياري دائمًا', 'value' => 'optional'],
                    ],
                ],
                [
                    'seed_key' => 'barn.multiple_activities',
                    'title' => 'هل يمكن ربط العنبر بأكثر من نشاط تشغيلي في نفس الوقت؟',
                    'help_text' => 'يحدد هذا القرار هل العلاقة النهائية Barn → Activity أم Barn ↔ Activities.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 8,
                    'report_category' => 'relationship_rule',
                    'target_entity' => 'barn_activity',
                    'options' => [],
                ],
                [
                    'seed_key' => 'barn.settings_profile_requirement',
                    'title' => 'متى يجب ربط العنبر بملف إعدادات تشغيل؟',
                    'help_text' => 'ملفات إعدادات التشغيل مستقلة وقابلة لإعادة الاستخدام؛ المطلوب هنا تحديد إلزامية اختيار ملف إعدادات للعنبر وتوقيت ذلك.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 9,
                    'report_category' => 'relationship_rule',
                    'target_entity' => 'barn_settings_profile',
                    'options' => [
                        ['label' => 'إلزامي عند إنشاء العنبر', 'value' => 'required_on_create'],
                        ['label' => 'يمكن إنشاء العنبر بدونه ولكن يجب ربطه قبل بدء التشغيل', 'value' => 'required_before_operation'],
                        ['label' => 'اختياري', 'value' => 'optional'],
                    ],
                ],
                [
                    'seed_key' => 'barn.multiple_active_settings_profiles',
                    'title' => 'هل يمكن أن يكون للعنبر أكثر من ملف إعدادات تشغيل فعال في نفس الوقت؟',
                    'help_text' => 'يحدد هذا السؤال Cardinality العلاقة الفعالة بين العنبر وملفات إعدادات التشغيل، دون الدخول في محتوى الإعدادات نفسها.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 10,
                    'report_category' => 'relationship_rule',
                    'target_entity' => 'barn_settings_profile',
                    'options' => [],
                ],
                [
                    'seed_key' => 'barn.ventilation_relation',
                    'title' => 'كيف يتم ربط العنبر بنظام التهوية؟',
                    'help_text' => 'أنظمة التهوية معرفة كـMaster Data مستقلة؛ حدد هل العلاقة مطلوبة وهل يسمح بأكثر من نظام تهوية للعنبر.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 11,
                    'report_category' => 'relationship_rule',
                    'target_entity' => 'barn_ventilation',
                    'options' => [
                        ['label' => 'لا نحتاج تسجيل نظام تهوية للعنبر', 'value' => 'not_recorded'],
                        ['label' => 'اختياري، ونظام واحد كحد أقصى', 'value' => 'optional_single'],
                        ['label' => 'إلزامي، ويجب اختيار نظام واحد', 'value' => 'required_single'],
                        ['label' => 'اختياري، ويمكن ربط أكثر من نظام', 'value' => 'optional_multiple'],
                        ['label' => 'إلزامي، ويجب اختيار نظام واحد على الأقل ويمكن ربط أكثر من نظام', 'value' => 'required_multiple'],
                    ],
                ],
                [
                    'seed_key' => 'barn.cooling_relation',
                    'title' => 'كيف يتم ربط العنبر بنظام التبريد؟',
                    'help_text' => 'أنظمة التبريد معرفة كـMaster Data مستقلة؛ حدد هل العلاقة مطلوبة وهل يسمح بأكثر من نظام تبريد للعنبر.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 12,
                    'report_category' => 'relationship_rule',
                    'target_entity' => 'barn_cooling',
                    'options' => [
                        ['label' => 'لا نحتاج تسجيل نظام تبريد للعنبر', 'value' => 'not_recorded'],
                        ['label' => 'اختياري، ونظام واحد كحد أقصى', 'value' => 'optional_single'],
                        ['label' => 'إلزامي، ويجب اختيار نظام واحد', 'value' => 'required_single'],
                        ['label' => 'اختياري، ويمكن ربط أكثر من نظام', 'value' => 'optional_multiple'],
                        ['label' => 'إلزامي، ويجب اختيار نظام واحد على الأقل ويمكن ربط أكثر من نظام', 'value' => 'required_multiple'],
                    ],
                ],
                [
                    'seed_key' => 'barn.heating_relation',
                    'title' => 'كيف يتم ربط العنبر بنظام التدفئة؟',
                    'help_text' => 'أنظمة التدفئة معرفة كـMaster Data مستقلة؛ حدد هل العلاقة مطلوبة وهل يسمح بأكثر من نظام تدفئة للعنبر.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 13,
                    'report_category' => 'relationship_rule',
                    'target_entity' => 'barn_heating',
                    'options' => [
                        ['label' => 'لا نحتاج تسجيل نظام تدفئة للعنبر', 'value' => 'not_recorded'],
                        ['label' => 'اختياري، ونظام واحد كحد أقصى', 'value' => 'optional_single'],
                        ['label' => 'إلزامي، ويجب اختيار نظام واحد', 'value' => 'required_single'],
                        ['label' => 'اختياري، ويمكن ربط أكثر من نظام', 'value' => 'optional_multiple'],
                        ['label' => 'إلزامي، ويجب اختيار نظام واحد على الأقل ويمكن ربط أكثر من نظام', 'value' => 'required_multiple'],
                    ],
                ],
                [
                    'seed_key' => 'barn.statuses',
                    'title' => 'ما الحالات التشغيلية التي يمكن أن يكون عليها العنبر؟',
                    'help_text' => 'حدد الحالات التي يحتاج النظام إلى تمثيلها للعنبر.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 14,
                    'report_category' => 'lookup_values',
                    'target_entity' => 'barn_status',
                    'options' => [
                        ['label' => 'نشط', 'value' => 'active'],
                        ['label' => 'متوقف', 'value' => 'stopped'],
                        ['label' => 'تحت الصيانة', 'value' => 'maintenance'],
                        ['label' => 'أخرى', 'value' => 'other'],
                    ],
                ],
                [
                    'seed_key' => 'barn.status_management',
                    'title' => 'هل حالات العنبر ثابتة أم قابلة للإدارة؟',
                    'help_text' => 'حدد هل حالات العنبر قيم ثابتة في النظام أم قائمة يمكن إدارتها من لوحة التحكم.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 15,
                    'report_category' => 'value_management',
                    'target_entity' => 'barn_status',
                    'options' => [
                        ['label' => 'قيم ثابتة داخل النظام', 'value' => 'fixed'],
                        ['label' => 'قابلة للإضافة والتعديل من لوحة التحكم', 'value' => 'managed'],
                    ],
                ],
                [
                    'seed_key' => 'barn.capacity_strategy',
                    'title' => 'كيف يجب تحديد السعة الاستيعابية للعنبر؟',
                    'help_text' => 'حدد الفرق المطلوب بين السعة التصميمية أو التخطيطية وبين السعة الفعلية التي يمكن للنظام اشتقاقها من البطاريات والأقفاص.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 16,
                    'report_category' => 'calculation_rule',
                    'target_entity' => 'barn',
                    'options' => [
                        ['label' => 'لا نحتاج تسجيل السعة الاستيعابية', 'value' => 'not_required'],
                        ['label' => 'يتم إدخال السعة يدويًا', 'value' => 'manual'],
                        ['label' => 'يتم حسابها تلقائيًا من البطاريات والأقفاص', 'value' => 'calculated'],
                        ['label' => 'نحتفظ بسعة تصميمية منفصلة ونحسب السعة الفعلية تلقائيًا', 'value' => 'planned_and_calculated'],
                    ],
                ],
                [
                    'seed_key' => 'barn.battery_count_strategy',
                    'title' => 'كيف يتم تحديد عدد البطاريات الموجودة داخل العنبر؟',
                    'help_text' => 'حدد هل عدد البطاريات قيمة مستقلة أم يتم اشتقاقه من البطاريات المرتبطة فعليًا بالعنبر.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 17,
                    'report_category' => 'calculation_rule',
                    'target_entity' => 'barn',
                    'options' => [
                        ['label' => 'يُحسب تلقائيًا من البطاريات المرتبطة بالعنبر', 'value' => 'calculated'],
                        ['label' => 'يُدخل يدويًا', 'value' => 'manual'],
                        ['label' => 'عدد فعلي محسوب مع قيمة تخطيطية منفصلة', 'value' => 'planned_and_calculated'],
                    ],
                ],
                [
                    'seed_key' => 'barn.occupancy_calculation',
                    'title' => 'هل يجب أن يحسب النظام مؤشرات الإشغال الحالية للعنبر تلقائيًا؟',
                    'help_text' => 'مثل عدد الأقفاص، الأقفاص المشغولة، الأقفاص المتاحة، وعدد الأرانب الموجودة حاليًا اعتمادًا على البيانات التابعة للعنبر.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 18,
                    'report_category' => 'calculation_rule',
                    'target_entity' => 'barn',
                    'options' => [],
                ],
                [
                    'seed_key' => 'barn.status_change_requires_empty',
                    'title' => 'هل يجب منع تغيير حالة العنبر من «نشط» إلى «متوقف» أو «تحت الصيانة» طالما توجد حيوانات داخله؟',
                    'help_text' => 'إذا كانت الإجابة نعم، فلا يسمح النظام بتغيير حالة العنبر إلى متوقف أو تحت الصيانة إلا بعد نقل جميع الحيوانات منه والتأكد من أن الإشغال الحالي للعنبر يساوي صفرًا، ويعتمد التحقق على الإشغال الفعلي المشتق من الأقفاص التابعة للعنبر عبر البطاريات.',
                    'type' => QuestionType::YES_NO,
                    'is_required' => true,
                    'sort_order' => 19,
                    'report_category' => 'workflow_rule',
                    'target_entity' => 'barn',
                    'options' => [],
                ],
                [
                    'seed_key' => 'barn.delete_policy',
                    'title' => 'كيف يجب التعامل مع حذف عنبر سبق استخدامه؟',
                    'help_text' => 'حدد سياسة حذف العنبر مع الحفاظ على البطاريات والأقفاص والحركات والسجلات التاريخية المرتبطة به.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 20,
                    'report_category' => 'lifecycle_rule',
                    'target_entity' => 'barn',
                    'options' => [
                        ['label' => 'لا يسمح بحذفه نهائيًا ويتم إيقافه فقط', 'value' => 'disable_only'],
                        ['label' => 'يسمح بحذفه إذا لم يستخدم سابقًا، وإلا يتم إيقافه فقط', 'value' => 'delete_if_unused_otherwise_disable'],
                        ['label' => 'يسمح بالحذف المنطقي Soft Delete مع الاحتفاظ بالسجل التاريخي', 'value' => 'soft_delete'],
                    ],
                ],
                [
                    'seed_key' => 'barn.additional_requirements',
                    'title' => 'هل توجد بيانات أو قواعد أخرى تخص العنبر؟',
                    'help_text' => 'اكتب أي بيانات أو علاقة أو قاعدة تشغيلية إضافية تخص العنبر ولم تغطها الأسئلة السابقة.',
                    'type' => QuestionType::TEXTAREA,
                    'is_required' => false,
                    'sort_order' => 21,
                    'report_category' => 'manual_review',
                    'target_entity' => 'barn',
                    'options' => [],
                ],
            ];

            app(QuestionSeederSyncService::class)->sync(
                mainSectionName: 'إدارة البيانات الأساسية',
                sectionName: 'بيانات العنبر',
                questions: $questions,
                prune: true,
                preserveAnswers: true,
            );
        });
    }

    /**
     * Adopt stable seed keys for legacy questions that have the same semantic meaning.
     *
     * Questions whose meaning was moved to another Master Data subsection are not
     * adopted. If any such legacy question already has an answer, preserveAnswers
     * will stop the sync safely instead of deleting that answer silently.
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
            ->where('name', 'بيانات العنبر')
            ->first();

        if (! $section) {
            return;
        }

        $legacyMap = [
            'ما البيانات التي يجب أن يحتوي عليها ملف العنبر؟' => 'barn.fields',
            'كيف تريد التعامل مع كود العنبر؟' => 'barn.code_strategy',
            'هل يمكن للعنبر الواحد أن يخدم أكثر من استخدام في نفس الوقت؟' => 'barn.multiple_activities',
            'ما الحالات التشغيلية التي يمكن أن يكون عليها العنبر؟' => 'barn.statuses',
            'هل حالات العنبر ثابتة أم قابلة للإدارة؟' => 'barn.status_management',
            'كيف تريد تحديد السعة الاستيعابية للعنبر؟' => 'barn.capacity_strategy',
            'كيف يتم تحديد عدد البطاريات الموجودة داخل العنبر؟' => 'barn.battery_count_strategy',
            'هل تريد أن يحسب النظام تلقائيًا مؤشرات الإشغال الحالية للعنبر؟' => 'barn.occupancy_calculation',
            'هل توجد بيانات أو خصائص أو متطلبات أخرى تخص العنبر ولم نتطرق إليها؟' => 'barn.additional_requirements',
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
