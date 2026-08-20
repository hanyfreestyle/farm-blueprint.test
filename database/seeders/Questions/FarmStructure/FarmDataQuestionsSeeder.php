<?php

namespace Database\Seeders\Questions\FarmStructure;

use App\Enums\Questionnaire\QuestionType;
use App\Services\Questionnaire\QuestionSeederSyncService;
use Illuminate\Database\Seeder;

class FarmDataQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        $questions = [
            [
                'seed_key' => 'farm.fields',
                'title' => 'ما البيانات التي يجب أن يحتوي عليها ملف المزرعة؟',
                'help_text' => 'حدد البيانات التي يجب أن يدعمها ملف المزرعة. بعض الاختيارات قد تتحول لاحقًا إلى علاقات مستقلة وليست أعمدة مباشرة داخل جدول المزارع.',
                'type' => QuestionType::MULTI_CHOICE,
                'is_required' => true,
                'sort_order' => 1,
                'report_category' => 'field',
                'target_entity' => 'farm',
                'options' => [
                    ['label' => 'اسم المزرعة', 'value' => 'name'],
                    ['label' => 'كود المزرعة', 'value' => 'code'],
                    ['label' => 'أنشطة المزرعة', 'value' => 'activities'],
                    ['label' => 'أرقام الهاتف', 'value' => 'phones'],
                    ['label' => 'أرقام واتساب', 'value' => 'whatsapps'],
                    ['label' => 'المحافظة', 'value' => 'governorate'],
                    ['label' => 'المدينة', 'value' => 'city'],
                    ['label' => 'العنوان', 'value' => 'address'],
                    ['label' => 'الموقع على الخريطة', 'value' => 'map_location'],
                    ['label' => 'تاريخ بدء التشغيل', 'value' => 'started_at'],
                    ['label' => 'الحالة التشغيلية', 'value' => 'status'],
                    ['label' => 'ملاحظات عامة', 'value' => 'notes'],
                ],
            ],
            [
                'seed_key' => 'farm.required_fields',
                'title' => 'أي من بيانات ملف المزرعة يجب أن تكون إلزامية عند إنشاء مزرعة جديدة؟',
                'help_text' => 'حدد الحد الأدنى من البيانات التي لا يسمح النظام بإنشاء المزرعة بدونها.',
                'type' => QuestionType::MULTI_CHOICE,
                'is_required' => true,
                'sort_order' => 2,
                'report_category' => 'rule',
                'target_entity' => 'farm',
                'options' => [
                    ['label' => 'اسم المزرعة', 'value' => 'name'],
                    ['label' => 'كود المزرعة', 'value' => 'code'],
                    ['label' => 'نشاط واحد على الأقل', 'value' => 'activities'],
                    ['label' => 'رقم هاتف واحد على الأقل', 'value' => 'phones'],
                    ['label' => 'رقم واتساب واحد على الأقل', 'value' => 'whatsapps'],
                    ['label' => 'المحافظة', 'value' => 'governorate'],
                    ['label' => 'المدينة', 'value' => 'city'],
                    ['label' => 'العنوان', 'value' => 'address'],
                    ['label' => 'الموقع على الخريطة', 'value' => 'map_location'],
                    ['label' => 'تاريخ بدء التشغيل', 'value' => 'started_at'],
                    ['label' => 'الحالة التشغيلية', 'value' => 'status'],
                ],
            ],
            [
                'seed_key' => 'farm.code_strategy',
                'title' => 'كيف يجب التعامل مع كود المزرعة؟',
                'help_text' => 'حدد طريقة إنشاء الكود واستخدامه كمعرف تشغيلي للمزرعة بجانب الـID الداخلي.',
                'type' => QuestionType::SINGLE_CHOICE,
                'is_required' => true,
                'sort_order' => 3,
                'report_category' => 'rule',
                'target_entity' => 'farm',
                'options' => [
                    ['label' => 'المستخدم يدخله يدويًا', 'value' => 'manual'],
                    ['label' => 'النظام يولده تلقائيًا', 'value' => 'automatic'],
                    ['label' => 'النظام يولده تلقائيًا مع إمكانية تعديله', 'value' => 'automatic_editable'],
                    ['label' => 'لا نحتاج كودًا للمزرعة', 'value' => 'not_required'],
                ],
            ],
            [
                'seed_key' => 'farm.multiple_activities',
                'title' => 'هل يمكن ربط المزرعة بأكثر من نشاط في نفس الوقت؟',
                'help_text' => 'أنشطة المزرعة أصبحت بيانات مرجعية مستقلة، وهذا السؤال يحدد هل علاقة المزرعة بالأنشطة تسمح بنشاط واحد أم أكثر.',
                'type' => QuestionType::YES_NO,
                'is_required' => true,
                'sort_order' => 4,
                'report_category' => 'relationship',
                'target_entity' => 'farm',
                'options' => [],
            ],
            [
                'seed_key' => 'farm.unique_rule',
                'title' => 'ما قاعدة عدم التكرار المطلوبة لبيانات المزرعة؟',
                'help_text' => 'حدد البيانات التي يجب اعتبارها فريدة بين المزارع حتى نحدد قيود Unique المناسبة في قاعدة البيانات.',
                'type' => QuestionType::SINGLE_CHOICE,
                'is_required' => true,
                'sort_order' => 5,
                'report_category' => 'rule',
                'target_entity' => 'farm',
                'options' => [
                    ['label' => 'كود المزرعة فقط يجب ألا يتكرر', 'value' => 'code_only'],
                    ['label' => 'اسم المزرعة فقط يجب ألا يتكرر', 'value' => 'name_only'],
                    ['label' => 'اسم المزرعة وكودها يجب ألا يتكررا', 'value' => 'name_and_code'],
                    ['label' => 'لا نحتاج شرط Unique إضافيًا بخلاف الـID', 'value' => 'none'],
                ],
            ],
            [
                'seed_key' => 'farm.statuses',
                'title' => 'ما الحالات التشغيلية التي نحتاجها للمزرعة؟',
                'help_text' => 'التصور المرجعي يتضمن حالتي نشطة ومتوقفة. حدد الحالات التي يجب أن يمثلها النظام.',
                'type' => QuestionType::MULTI_CHOICE,
                'is_required' => true,
                'sort_order' => 6,
                'report_category' => 'field',
                'target_entity' => 'farm',
                'options' => [
                    ['label' => 'نشطة', 'value' => 'active'],
                    ['label' => 'متوقفة', 'value' => 'stopped'],
                    ['label' => 'أخرى', 'value' => 'other'],
                ],
            ],
            [
                'seed_key' => 'farm.stopped_behavior',
                'title' => 'عندما تكون المزرعة متوقفة، ما التأثير المطلوب على التشغيل؟',
                'help_text' => 'حدد المعنى التشغيلي لحالة المزرعة المتوقفة حتى لا تظل الحالة مجرد وصف بدون Business Rule واضح.',
                'type' => QuestionType::SINGLE_CHOICE,
                'is_required' => true,
                'sort_order' => 7,
                'report_category' => 'rule',
                'target_entity' => 'farm',
                'options' => [
                    ['label' => 'الحالة للمعلومة فقط ولا تمنع العمليات', 'value' => 'informational_only'],
                    ['label' => 'منع إنشاء عمليات وحركات تشغيلية جديدة مع استمرار العرض والتقارير', 'value' => 'block_new_operations'],
                    ['label' => 'منع التعديلات التشغيلية بالكامل باستثناء إعادة التفعيل والإجراءات الإدارية', 'value' => 'freeze_operations'],
                ],
            ],
            [
                'seed_key' => 'farm.children_on_stop',
                'title' => 'عند توقف المزرعة، كيف يتم التعامل مع العنابر والبطاريات والأقفاص التابعة لها؟',
                'help_text' => 'حدد هل توقف المزرعة يغير حالات العناصر التابعة فعليًا أم يؤثر فقط على إتاحتها التشغيلية.',
                'type' => QuestionType::SINGLE_CHOICE,
                'is_required' => true,
                'sort_order' => 8,
                'report_category' => 'rule',
                'target_entity' => 'farm',
                'options' => [
                    ['label' => 'تظل حالاتها الأصلية كما هي ولكن تعتبر غير متاحة تشغيليًا بسبب توقف المزرعة', 'value' => 'preserve_status_block_usage'],
                    ['label' => 'يتم تحويل العناصر التابعة تلقائيًا إلى حالة متوقفة', 'value' => 'cascade_stopped_status'],
                    ['label' => 'لا يوجد تأثير تلقائي ويتم التعامل مع كل عنصر بصورة مستقلة', 'value' => 'independent_status'],
                ],
            ],
            [
                'seed_key' => 'farm.multi_farm_timing',
                'title' => 'متى يجب أن يكون دعم تشغيل أكثر من مزرعة متاحًا؟',
                'help_text' => 'التصور يسمح بتعدد المزارع. المطلوب هنا تحديد نطاق الإصدار الأول مع الحفاظ على قابلية التوسع.',
                'type' => QuestionType::SINGLE_CHOICE,
                'is_required' => true,
                'sort_order' => 9,
                'report_category' => 'relationship',
                'target_entity' => 'farm',
                'options' => [
                    ['label' => 'من الإصدار الأول', 'value' => 'from_first_release'],
                    ['label' => 'مزرعة واحدة حاليًا مع بناء قاعدة البيانات والبنية من البداية لدعم أكثر من مزرعة مستقبلًا', 'value' => 'single_now_multi_ready'],
                ],
            ],
            [
                'seed_key' => 'farm.delete_policy',
                'title' => 'كيف يجب التعامل مع حذف مزرعة سبق أن ارتبطت ببيانات أو حركات تشغيلية؟',
                'help_text' => 'حدد سياسة الحذف المطلوبة بما يحافظ على سلامة العلاقات والسجل التاريخي.',
                'type' => QuestionType::SINGLE_CHOICE,
                'is_required' => true,
                'sort_order' => 10,
                'report_category' => 'rule',
                'target_entity' => 'farm',
                'options' => [
                    ['label' => 'لا يسمح بحذفها نهائيًا ويتم إيقافها فقط', 'value' => 'disable_only'],
                    ['label' => 'يسمح بالحذف المنطقي Soft Delete مع الاحتفاظ بالبيانات', 'value' => 'soft_delete'],
                    ['label' => 'يسمح بالحذف فقط إذا لم توجد بيانات تشغيلية مرتبطة بها', 'value' => 'delete_if_unused'],
                ],
            ],
            [
                'seed_key' => 'farm.additional_requirements',
                'title' => 'هل توجد بيانات أو قواعد أخرى خاصة بملف المزرعة ترى ضرورة إضافتها؟',
                'help_text' => 'اكتب أي نقطة مهمة لم تغطها الأسئلة السابقة حتى تتم مراجعتها قبل اعتماد Blueprint بيانات المزرعة.',
                'type' => QuestionType::TEXTAREA,
                'is_required' => false,
                'sort_order' => 11,
                'report_category' => 'general',
                'target_entity' => 'farm',
                'options' => [],
            ],
        ];

        app(QuestionSeederSyncService::class)->sync(
            mainSectionName: 'إدارة البيانات الأساسية',
            sectionName: 'بيانات المزرعة',
            questions: $questions,
            prune: true,
            preserveAnswers: false,
        );
    }
}
