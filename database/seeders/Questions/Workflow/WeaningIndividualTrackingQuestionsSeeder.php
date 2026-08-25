<?php

namespace Database\Seeders\Questions\Workflow;

use App\Enums\Questionnaire\QuestionType;
use App\Services\Questionnaire\QuestionSeederSyncService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WeaningIndividualTrackingQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $questions = [
                [
                    'seed_key' => 'weaning.operation_structure',
                    'title' => 'كيف يجب تنظيم عملية الفطام التي تحول البطن إلى سجلات حيوانات فردية؟',
                    'help_text' => 'المرجع يعتبر الفطام عملية رئيسية وليست مجرد تغيير Status؛ فهي تشمل مطابقة المواليد الموجودة، إنشاء السجلات الفردية، تسجيل البيانات المرتبطة، التسكين، ثم بدء المرحلة التالية. المطلوب حسم هل تنفذ كعملية موحدة أم جلسة مرحلية لها اعتماد نهائي.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 1,
                    'report_category' => 'workflow_model',
                    'target_entity' => 'weaning_operation',
                    'options' => [
                        ['label' => 'عملية موجهة واحدة تجمع الخطوات المطلوبة ثم تعتمد الفطام مرة واحدة', 'value' => 'guided_single_operation'],
                        ['label' => 'جلسة فطام مرحلية تحفظ كمسودة ويمكن استكمالها ثم اعتمادها نهائيًا', 'value' => 'staged_session_with_finalization'],
                        ['label' => 'تنفذ الخطوات كإجراءات مستقلة دون نقطة اعتماد موحدة للفطام', 'value' => 'independent_actions_without_unified_finalization'],
                    ],
                ],
                [
                    'seed_key' => 'weaning.event_record_fields',
                    'title' => 'ما البيانات التي يجب أن يحتفظ بها حدث الفطام الفعلي؟',
                    'help_text' => 'السجل يمثل ما حدث فعليًا عند الفطام. العمر يمكن حسابه من تاريخ الميلاد، بينما شروط العمر والوزن المطلوبة للسماح بالفطام تبقى في Settings.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 2,
                    'report_category' => 'field',
                    'target_entity' => 'weaning_event',
                    'options' => [
                        ['label' => 'البطن', 'value' => 'litter'],
                        ['label' => 'تاريخ / وقت الفطام الفعلي', 'value' => 'weaned_at'],
                        ['label' => 'العدد المتوقع وجوده قبل الفطام', 'value' => 'expected_alive_count'],
                        ['label' => 'عدد المواليد المفطومين فعليًا', 'value' => 'actual_weaned_count'],
                        ['label' => 'عمر الفطام وقت التنفيذ', 'value' => 'age_at_weaning'],
                        ['label' => 'ملاحظة الحالة العامة وقت الفطام', 'value' => 'general_condition'],
                        ['label' => 'سبب فرق العدد عند وجوده', 'value' => 'count_discrepancy_reason'],
                        ['label' => 'المستخدم / منفذ العملية', 'value' => 'performed_by'],
                        ['label' => 'ملاحظات', 'value' => 'notes'],
                    ],
                ],
                [
                    'seed_key' => 'weaning.count_reconciliation_model',
                    'title' => 'كيف يجب التعامل مع اختلاف عدد المواليد المتوقع في البطن عن العدد الموجود فعليًا عند الفطام؟',
                    'help_text' => 'العدد المتوقع ينتج من الأحياء عند الولادة ثم أحداث النفوق والنقل والاستقبال. المرجع يؤكد أن النظام لا يتجاهل الفرق عند الفطام، ويجب أن يظل له تفسير قبل إغلاق البطن.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 3,
                    'report_category' => 'validation_rule',
                    'target_entity' => 'weaning_event',
                    'options' => [
                        ['label' => 'لا يعتمد الفطام حتى تتطابق الأعداد أو يتم توثيق الأحداث التي تفسر الفرق', 'value' => 'require_reconciliation_before_finalize'],
                        ['label' => 'يسمح بالاعتماد مع اختلاف العدد بشرط تسجيل سبب إلزامي للفرق', 'value' => 'allow_mismatch_with_required_reason'],
                        ['label' => 'يسمح بالاعتماد بعد تحذير فقط دون إلزام بتفسير الفرق', 'value' => 'allow_mismatch_with_warning_only'],
                    ],
                ],
                [
                    'seed_key' => 'weaning.partial_weaning_model',
                    'title' => 'هل يجب أن يدعم النظام الفطام الجزئي لبعض أفراد البطن مع بقاء الباقي تحت الرضاعة؟',
                    'help_text' => 'المرجع يضع الفطام الجزئي كنقطة تحتاج مراجعة فعلية. إذا اعتمد، تنشأ سجلات فردية للمفطومين فقط بينما تظل البطن مفتوحة بالباقي حتى حدث فطام لاحق.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 4,
                    'report_category' => 'workflow_model',
                    'target_entity' => 'litter',
                    'options' => [
                        ['label' => 'لا؛ الفطام ينفذ للبطن كلها كعملية واحدة فقط', 'value' => 'full_litter_only'],
                        ['label' => 'نعم؛ ينشأ أفراد للمفطومين وتظل البطن مفتوحة بالباقي حتى فطامهم', 'value' => 'partial_weaning_keep_litter_open'],
                    ],
                ],
                [
                    'seed_key' => 'weaning.individual_creation_model',
                    'title' => 'كيف يجب إنشاء السجلات الفردية للأرانب الناتجة من الفطام داخل العملية؟',
                    'help_text' => 'بعد الفطام يصبح كل مولود مفطوم Animal Record مستقلًا بهوية دائمة وفق قواعد 3.1. هذا السؤال يحسم طريقة تجهيز السجلات داخل عملية الفطام دون إعادة تعريف نظام أكواد الحيوانات.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 5,
                    'report_category' => 'creation_rule',
                    'target_entity' => 'animal',
                    'options' => [
                        ['label' => 'يولد النظام تلقائيًا صفًا/سجلًا لكل مفطوم حسب العدد الفعلي ثم يستكمل المستخدم بيانات الأفراد في شاشة جماعية', 'value' => 'auto_generate_individual_rows_from_weaned_count'],
                        ['label' => 'يضيف المستخدم الأفراد في شاشة جماعية ويمنع الاعتماد حتى يطابق عددهم عدد المفطومين', 'value' => 'batch_add_individuals_with_count_validation'],
                        ['label' => 'ينشأ كل سجل حيوان واحدًا تلو الآخر داخل عملية الفطام مع التحقق من العدد النهائي', 'value' => 'create_individuals_one_by_one_with_final_validation'],
                    ],
                ],
                [
                    'seed_key' => 'weaning.inherited_animal_fields',
                    'title' => 'ما البيانات المعروفة التي يجب أن تنتقل تلقائيًا إلى سجل كل حيوان عند إنشائه من البطن؟',
                    'help_text' => 'الهدف منع إعادة إدخال بيانات موثوقة مثل تاريخ الميلاد والأصل والنسب. كود الحيوان نفسه يخضع لقواعد الهوية المعتمدة في 3.1 ولا يعاد تصميمه هنا.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 6,
                    'report_category' => 'inheritance_rule',
                    'target_entity' => 'animal',
                    'options' => [
                        ['label' => 'تاريخ الميلاد', 'value' => 'birth_date'],
                        ['label' => 'الأم البيولوجية', 'value' => 'biological_mother'],
                        ['label' => 'مرجع الأبوة / الأب عند توافره', 'value' => 'paternity_reference'],
                        ['label' => 'البطن الأصلية', 'value' => 'original_litter'],
                        ['label' => 'مرجع حدث الولادة', 'value' => 'birth_event'],
                        ['label' => 'السلالة / الأصل حسب القواعد المعتمدة', 'value' => 'breed_origin'],
                        ['label' => 'المزرعة', 'value' => 'farm'],
                        ['label' => 'تاريخ / حدث الفطام', 'value' => 'weaning_reference'],
                        ['label' => 'الأم الحاضنة عند وجودها', 'value' => 'foster_mother_if_applicable'],
                        ['label' => 'بطن / مجموعة الرضاعة الحالية عند اختلافها عن البطن الأصلية', 'value' => 'current_lactation_group_if_applicable'],
                        ['label' => 'مرجع تتبع النقل قبل الفطام عند وجوده', 'value' => 'preweaning_transfer_reference'],
                    ],
                ],
                [
                    'seed_key' => 'weaning.preweaning_origin_resolution_model',
                    'title' => 'عند وجود مواليد نُقلت بين الأمهات قبل الفطام، كيف يجب تثبيت أصل كل فرد عند إنشاء سجله النهائي؟',
                    'help_text' => '4.7 يسمح بتتبع المواليد المنقولين قبل وجود الكود الفردي النهائي. عند الفطام يجب ربط الفرد بأصله البيولوجي والبطن الأصلية مع الحفاظ على علاقة الأم الحاضنة، بدل فقد الأصل بسبب مجموعة الرضاعة الحالية.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 7,
                    'report_category' => 'integrity_rule',
                    'target_entity' => 'animal_origin',
                    'options' => [
                        ['label' => 'لا يعتمد سجل الفرد حتى يحسم الأصل البيولوجي والبطن الأصلية وعلاقة الحضانة من بيانات التتبع المتاحة', 'value' => 'require_origin_resolution_before_finalize'],
                        ['label' => 'يسمح بإنشاء الفرد مع علامة أن الأصل يحتاج استكمالًا لاحقًا مع الاحتفاظ بكل مراجع النقل المتاحة', 'value' => 'allow_unresolved_origin_with_reconciliation_flag'],
                        ['label' => 'يعتمد بطن الرضاعة الحالية كأصل للفرد دون الرجوع للبطن البيولوجية', 'value' => 'use_current_lactation_group_as_origin'],
                    ],
                ],
                [
                    'seed_key' => 'weaning.sex_capture_model',
                    'title' => 'كيف يجب التعامل مع تحديد جنس كل فرد أثناء الفطام؟',
                    'help_text' => 'المرجع يسمح بأن يكون الجنس غير محدد إذا تعذر التأكد، ثم يتم تحديده لاحقًا. توقيت الفصل بين الجنسين وقواعد إلزامه تبقى في Settings / Housing Rules.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 8,
                    'report_category' => 'field_rule',
                    'target_entity' => 'animal',
                    'options' => [
                        ['label' => 'يدعم ذكر / أنثى / غير محدد، ويمكن استكمال التحديد لاحقًا', 'value' => 'male_female_unknown_allow_later_identification'],
                        ['label' => 'يجب تحديد الجنس المؤكد لكل فرد قبل اعتماد الفطام', 'value' => 'require_confirmed_sex_before_finalize'],
                        ['label' => 'لا يسجل الجنس ضمن عملية الفطام ويترك لمرحلة لاحقة', 'value' => 'do_not_capture_sex_during_weaning'],
                    ],
                ],
                [
                    'seed_key' => 'weaning.weight_integration_model',
                    'title' => 'عند تسجيل وزن الفطام لكل فرد، كيف يجب ربطه بسجل الأوزان التشغيلي؟',
                    'help_text' => '4.3 قرر أن وزن الفطام Context داخل Weight History وأن قيمة القياس يجب ألا تتكرر في أكثر من مصدر. هذا السؤال يحسم التكامل بين شاشة الفطام والسجل القياسي؛ أما هل الوزن إلزامي للفطام فقاعدة في Settings.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 9,
                    'report_category' => 'integration_rule',
                    'target_entity' => 'weight_measurement',
                    'options' => [
                        ['label' => 'تنشئ/تربط عملية الفطام سجل وزن قياسيًا واحدًا لكل فرد بسياق Weaning دون تكرار القيمة', 'value' => 'create_or_link_canonical_weaning_measurement'],
                        ['label' => 'يخزن وزن الفطام داخل سجل الفطام نفسه كسجل مستقل عن Weight History', 'value' => 'store_weight_inside_weaning_record'],
                        ['label' => 'لا تسجل الأوزان من عملية الفطام وتنفذ كعملية وزن مستقلة بالكامل في 4.3', 'value' => 'weaning_does_not_capture_weight'],
                    ],
                ],
                [
                    'seed_key' => 'weaning.housing_integration_model',
                    'title' => 'كيف يجب دمج تسكين الأفراد بعد الفطام مع عملية الفطام نفسها؟',
                    'help_text' => 'المصدر يضع التسكين ضمن عملية الفطام ويسمح بتوزيع البطن على أكثر من قفص عند الحاجة. سجل الموقع الفعلي يجب أن يبقى عبر Housing Movements في 4.2، وليس كحقل موقع يدوي داخل الحيوان.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 10,
                    'report_category' => 'integration_rule',
                    'target_entity' => 'animal_housing',
                    'options' => [
                        ['label' => 'يختار المستخدم قفصًا أو أكثر داخل جلسة الفطام، وينشئ النظام حركة تسكين فردية لكل حيوان قبل الاعتماد', 'value' => 'housing_within_weaning_before_finalize'],
                        ['label' => 'تنشأ الأفراد أولًا ثم تنفذ خطوة تسكين منفصلة، ولا تكتمل عملية الفطام قبل اكتمال التسكين', 'value' => 'staged_housing_required_before_weaning_finalize'],
                        ['label' => 'يمكن اعتماد الفطام وإنشاء الأفراد قبل التسكين، ثم يسجل التسكين لاحقًا بصورة مستقلة', 'value' => 'finalize_weaning_before_housing'],
                    ],
                ],
                [
                    'seed_key' => 'weaning.summary_persistence_model',
                    'title' => 'كيف يجب حفظ ملخص نتيجة الفطام مثل العدد والعمر وتوزيع الجنس بعد إنشاء الأفراد؟',
                    'help_text' => 'المرجع يذكر تاريخ وعمر الفطام وعدد المفطومين وأعداد الذكور والإناث وغير محددي الجنس. بعض هذه القيم يمكن اشتقاقه من حدث الفطام والسجلات الفردية، لذلك نحتاج حسم هل نحفظ Snapshot تاريخية أم نعتمد على الاشتقاق.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 11,
                    'report_category' => 'history_rule',
                    'target_entity' => 'weaning_event',
                    'options' => [
                        ['label' => 'تشتق القيم من حدث الفطام والسجلات الفردية المرتبطة ولا تحفظ كملخص مستقل قابل للتعديل', 'value' => 'derive_summary_from_event_and_individuals'],
                        ['label' => 'تحفظ Snapshot تاريخية عند الاعتماد وتتحقق من تطابقها مع السجلات الفردية', 'value' => 'store_snapshot_and_validate_against_individuals'],
                        ['label' => 'تحفظ كملخص قابل للتعديل بصورة مستقلة عن السجلات الفردية', 'value' => 'editable_summary_independent_from_individuals'],
                    ],
                ],
                [
                    'seed_key' => 'weaning.post_completion_transition_model',
                    'title' => 'كيف يجب أن يبدأ مسار النمو والفرز للأفراد بعد اكتمال الفطام؟',
                    'help_text' => 'المرجع يعتبر الفطام Boundary ينهي إدارة المواليد كبطن ويبدأ التتبع الفردي ثم مرحلة النمو والفرز. مواعيد الأوزان والفرز وشروطهما تبقى في Settings و4.9.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 12,
                    'report_category' => 'transition_rule',
                    'target_entity' => 'animal_lifecycle',
                    'options' => [
                        ['label' => 'بعد اعتماد الفطام ينتقل الأفراد تلقائيًا إلى سياق النمو والفرز مع بقاء تفاصيل المتابعة في 4.9', 'value' => 'auto_start_growth_after_successful_weaning'],
                        ['label' => 'يكتمل الفطام وتصبح الأفراد مستقلة، ثم يبدأ مسار النمو بإجراء انتقال مستقل مسجل', 'value' => 'explicit_growth_start_after_weaning'],
                        ['label' => 'لا يوجد Transition واضح ويعدل المستخدم المرحلة الحالية يدويًا', 'value' => 'manual_stage_change_without_transition'],
                    ],
                ],
            ];

            app(QuestionSeederSyncService::class)->sync(
                mainSectionName: 'الحركات ودورة التشغيل الفعلية',
                sectionName: 'الفطام والتحول إلى التتبع الفردي',
                questions: $questions,
                prune: true,
                preserveAnswers: true,
            );
        });
    }
}
