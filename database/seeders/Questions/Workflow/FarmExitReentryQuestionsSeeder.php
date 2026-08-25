<?php

namespace Database\Seeders\Questions\Workflow;

use App\Enums\Questionnaire\QuestionType;
use App\Services\Questionnaire\QuestionSeederSyncService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FarmExitReentryQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $questions = [
                [
                    'seed_key' => 'farm_exit.canonical_event_routing_model',
                    'title' => 'كيف يجب التعامل مع أسباب خروج لها حدث Canonical متخصص في Workflow آخر مثل النفوق أو الفقد؟',
                    'help_text' => 'قائمة أسباب الخروج الحالية تحتوي مثلًا على النفوق والفقد، لكن النفوق أصبح Event مستقلًا في 4.13 والفقد/العثور على الحيوان في 4.14. المطلوب منع إنشاء واقعتين متنافستين لنفس الحدث مع الحفاظ على إمكانية تصنيف حالة الوجود والتقارير.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 1,
                    'report_category' => 'integration_rule',
                    'target_entity' => 'farm_exit_event',
                    'options' => [
                        ['label' => 'يستخدم الحدث المتخصص فقط وتشتق منه حالة الوجود/الخروج دون إنشاء Farm Exit Event إضافي', 'value' => 'canonical_domain_event_only_derive_presence'],
                        ['label' => 'يستخدم الحدث المتخصص مع إنشاء Farm Exit Event مرتبط به لتوحيد سجل الخروج', 'value' => 'canonical_domain_event_with_linked_exit_event'],
                        ['label' => 'ينشأ Farm Exit Event مستقل دائمًا ثم يرتبط بالحدث المتخصص عند وجوده', 'value' => 'always_create_exit_event_with_optional_domain_link'],
                    ],
                ],
                [
                    'seed_key' => 'farm_exit.record_fields',
                    'title' => 'ما البيانات التي يجب أن يحتفظ بها سجل الخروج الفعلي من المزرعة؟',
                    'help_text' => 'المصدر يذكر تاريخ الخروج والعمر والوزن/الموقع والمرحلة والسبب والمنفذ والملاحظات، مع إمكانية وجود جهة مستقبلة أو مشتري. القيم Canonical مثل الوزن والموقع يفضل ربطها بمصادرها الأصلية بدل نسخها كسجلات متنافسة.',
                    'type' => QuestionType::MULTI_CHOICE,
                    'is_required' => true,
                    'sort_order' => 2,
                    'report_category' => 'field',
                    'target_entity' => 'farm_exit_event',
                    'options' => [
                        ['label' => 'الحيوان', 'value' => 'animal'],
                        ['label' => 'تاريخ / وقت الخروج الفعلي', 'value' => 'exited_at'],
                        ['label' => 'العمر وقت الخروج عند إمكان تحديده', 'value' => 'age_at_exit'],
                        ['label' => 'مرجع آخر وزن أو وزن الخروج عند وجوده', 'value' => 'weight_reference'],
                        ['label' => 'مرجع موقع الإيواء الذي خرج منه الحيوان', 'value' => 'source_housing_reference'],
                        ['label' => 'المرحلة / المسار التشغيلي وقت الخروج', 'value' => 'operational_stage'],
                        ['label' => 'مرجع سبب الخروج من Master Data', 'value' => 'exit_reason_reference'],
                        ['label' => 'الجهة / الوجهة المستقبلة عند انطباقها', 'value' => 'destination_or_recipient'],
                        ['label' => 'مرجع القرار أو العملية التي أدت إلى الخروج عند وجودها', 'value' => 'source_decision_or_operation_reference'],
                        ['label' => 'المستخدم / منفذ عملية الخروج', 'value' => 'performed_by'],
                        ['label' => 'ملاحظات', 'value' => 'notes'],
                    ],
                ],
                [
                    'seed_key' => 'farm_exit.reason_reference_policy',
                    'title' => 'كيف يجب استخدام سبب الخروج المرجعي الموجود في Master Data عند تسجيل خروج فعلي؟',
                    'help_text' => 'لا تعاد قيم أسباب الخروج داخل هذا القسم. المطلوب حسم مدى إلزام مرجع السبب، والتعامل مع «سبب آخر»، مع مراعاة أن بعض الأسباب قد تكون ممثلة أصلًا بحدث متخصص حسب قرار السؤال السابق.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 3,
                    'report_category' => 'validation_rule',
                    'target_entity' => 'farm_exit_event',
                    'options' => [
                        ['label' => 'مرجع سبب الخروج إلزامي لكل Farm Exit Event، ويطلب وصف إضافي عند «سبب آخر»', 'value' => 'reason_required_other_detail_required'],
                        ['label' => 'مرجع سبب الخروج إلزامي لكل Farm Exit Event، و«سبب آخر» لا يفرض وصفًا إضافيًا', 'value' => 'reason_required_reference_only'],
                        ['label' => 'مرجع السبب إلزامي للخروج العام، أما الحدث Canonical المتخصص فيمكن أن يحدد السبب من نوع الحدث نفسه', 'value' => 'reason_required_except_canonical_domain_events'],
                    ],
                ],
                [
                    'seed_key' => 'farm_exit.active_context_handling_model',
                    'title' => 'إذا كان للحيوان مسار نشط وقت الخروج مثل حمل أو رضاعة أو تسمين أو عزل، كيف يجب التعامل معه عند تنفيذ الخروج؟',
                    'help_text' => 'الخروج قد يحدث من مراحل مختلفة. المطلوب حسم Boundary التنفيذ دون اختراع قواعد السماح والمنع هنا؛ شروط منع الخروج أو طلب Override تنتمي إلى Settings، بينما إعادة بناء المسارات المتأثرة يمكن أن تتكامل مع 4.14.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 4,
                    'report_category' => 'integration_rule',
                    'target_entity' => 'farm_exit_event',
                    'options' => [
                        ['label' => 'يكتشف النظام السياقات النشطة ويجب تسويتها أو إعادة بنائها قبل إكمال الخروج', 'value' => 'resolve_or_reconstruct_active_context_before_exit'],
                        ['label' => 'يسمح بتسجيل الخروج ثم ينفذ Reconstruction مترابطًا فورًا للمسارات التي أصبحت غير صالحة', 'value' => 'exit_then_immediate_linked_reconstruction'],
                        ['label' => 'يسجل الخروج وينشئ إجراءات معلقة لمعالجة المسارات النشطة لاحقًا', 'value' => 'exit_with_pending_context_resolution'],
                    ],
                ],
                [
                    'seed_key' => 'farm_exit.post_event_transition_model',
                    'title' => 'بعد تسجيل الخروج الفعلي، كيف يجب تحديث حالة الوجود والإشغال والمسارات والمهام دون حذف التاريخ؟',
                    'help_text' => 'المصدر ينص على أن الحيوان يصبح خارج المزرعة وغير نشط تشغيليًا مع الاحتفاظ بسجله، وإغلاق التسكين وتحرير السعة وإيقاف المهام غير الصالحة. حركة الإشغال Canonical في 4.2 والمهام في 4.17، لذلك المطلوب حسم طريقة التكامل.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 5,
                    'report_category' => 'integration_rule',
                    'target_entity' => 'animal_presence',
                    'options' => [
                        ['label' => 'يطبق الخروج تحديثات مترابطة تلقائيًا على حالة الوجود والإشغال والمسارات والمهام المناسبة', 'value' => 'automatic_linked_exit_transitions'],
                        ['label' => 'يسجل الخروج ثم ينشئ Actions / Tasks معلقة لإغلاق الإشغال والمسارات ومعالجة المهام', 'value' => 'create_pending_post_exit_actions'],
                        ['label' => 'يسجل الخروج فقط وتنفذ جميع الآثار المرتبطة يدويًا كأحداث مستقلة', 'value' => 'exit_record_only_manual_followup'],
                    ],
                ],
                [
                    'seed_key' => 'farm_exit.batch_operation_model',
                    'title' => 'كيف يجب دعم الخروج الجماعي مثل بيع مجموعة من أرانب التسمين في عملية واحدة؟',
                    'help_text' => 'المصدر يفضل إدخال البيانات المشتركة مرة واحدة مع الاحتفاظ بحركة خروج مستقلة لكل حيوان حتى لا نفقد التتبع الفردي. السؤال يحسم نموذج العملية دون افتراض أن كل المزارع تستخدم البيع الجماعي.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 6,
                    'report_category' => 'workflow_model',
                    'target_entity' => 'farm_exit_batch',
                    'options' => [
                        ['label' => 'دعم عملية خروج جماعية مع إنشاء Farm Exit Event مستقل لكل حيوان وربطه بالعملية المشتركة', 'value' => 'batch_operation_with_individual_exit_events'],
                        ['label' => 'دعم سجل خروج جماعي واحد فقط للمجموعة دون أحداث فردية', 'value' => 'group_exit_record_only'],
                        ['label' => 'لا يحتاج النظام خروجًا جماعيًا؛ يسجل كل حيوان منفردًا', 'value' => 'individual_exit_only'],
                    ],
                ],
                [
                    'seed_key' => 'sale_exit.commercial_data_scope',
                    'title' => 'ما نطاق البيانات التجارية المطلوب داخل حدث البيع نفسه في هذه النسخة من النظام؟',
                    'help_text' => 'المصدر يذكر المشتري والسعر كبيانات ممكنة إذا دخل الجانب التجاري ضمن نطاق النظام، لكنه يؤكد أن التفاصيل المالية الكاملة خارج دورة الإنتاج الحالية ويمكن تطويرها لاحقًا. المطلوب حسم الحد الأدنى فقط.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 7,
                    'report_category' => 'scope_rule',
                    'target_entity' => 'sale_exit_event',
                    'options' => [
                        ['label' => 'تشغيلي فقط: إثبات البيع والخروج دون مشتري أو أسعار', 'value' => 'operational_sale_only'],
                        ['label' => 'بيانات تجارية أساسية اختيارية مثل المشتري والسعر/القيمة دون إنشاء وحدة حسابات كاملة', 'value' => 'basic_optional_commercial_fields'],
                        ['label' => 'يحتفظ حدث الخروج بمرجع لعملية تجارية/مالية خارجية مستقلة دون تكرار تفاصيلها', 'value' => 'external_commercial_transaction_reference'],
                    ],
                ],
                [
                    'seed_key' => 'interfarm_transfer.boundary_model',
                    'title' => 'إذا انتقل الحيوان بين مزرعتين داخل نفس النظام، كيف يجب تمثيل العملية؟',
                    'help_text' => 'المرجع يؤكد أن النقل بين مزرعتين داخل نفس النظام ليس بالضرورة خروجًا نهائيًا، وأن الحيوان يحتفظ بنفس الكود والنسب وتاريخ الوزن والإنتاج. المطلوب حسم Boundary بين حركة الموقع والخروج/الدخول.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 8,
                    'report_category' => 'workflow_boundary',
                    'target_entity' => 'interfarm_transfer',
                    'options' => [
                        ['label' => 'يعامل كحركة نقل بين مواقع في 4.2 على نفس Animal Record دون Farm Exit / Re-entry', 'value' => 'cross_farm_housing_movement_same_record'],
                        ['label' => 'حدث Inter-farm Transfer مستقل يربط خروج المزرعة المصدر بدخول المزرعة المستقبلة مع بقاء نفس Animal Record', 'value' => 'dedicated_interfarm_transfer_same_record'],
                        ['label' => 'يسجل خروجًا من المزرعة المصدر ثم إعادة إدخال في المزرعة المستقبلة مع ربط الحدثين على نفس Animal Record', 'value' => 'linked_exit_and_reentry_same_record'],
                    ],
                ],
                [
                    'seed_key' => 'animal_reentry.previous_exit_link_model',
                    'title' => 'عند عودة حيوان ثبت أنه نفس الحيوان المسجل سابقًا، كيف يجب ربط إعادة الدخول بتاريخ خروجه السابق؟',
                    'help_text' => '4.1 يحسم الخطوات التي يمر بها الحيوان بعد العودة، أما هذا السؤال فيحسم علاقة Re-entry بواقعة الخروج التاريخية مع الحفاظ على نفس Animal Record وعدم إنشاء هوية جديدة.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 9,
                    'report_category' => 'relationship_rule',
                    'target_entity' => 'animal_reentry_event',
                    'options' => [
                        ['label' => 'يرتبط Re-entry Event صراحة بواقعة الخروج السابقة التي عاد منها الحيوان', 'value' => 'explicit_previous_exit_reference'],
                        ['label' => 'يستنتج الربط بأحدث خروج سابق لنفس الحيوان دون حفظ مرجع صريح', 'value' => 'derive_latest_prior_exit'],
                        ['label' => 'يكفي وجود الحدثين على Timeline نفس الحيوان دون علاقة مباشرة بينهما', 'value' => 'same_timeline_without_direct_link'],
                    ],
                ],
                [
                    'seed_key' => 'animal_reentry.presence_transition_model',
                    'title' => 'متى يجب اعتبار الحيوان «موجودًا بالمزرعة» مرة أخرى أثناء إعادة الدخول؟',
                    'help_text' => 'الحيوان قد يصل فعليًا إلى المزرعة قبل اكتمال التقييم أو الحجر أو الاعتماد التشغيلي. المطلوب فصل Physical Presence عن Production/Operational Readiness؛ خطوات الاستقبال التفصيلية نفسها تبقى في 4.1.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 10,
                    'report_category' => 'lifecycle_rule',
                    'target_entity' => 'animal_presence',
                    'options' => [
                        ['label' => 'يصبح موجودًا بالمزرعة عند تسجيل الوصول/إعادة الدخول الفعلي، حتى لو ظل تحت الاستقبال أو الحجر وغير متاح للتشغيل', 'value' => 'present_on_physical_reentry_arrival'],
                        ['label' => 'لا يعود لحالة موجود بالمزرعة إلا بعد اعتماد مسار الاستقبال في 4.1', 'value' => 'present_after_intake_finalization'],
                        ['label' => 'تشتق حالة الوجود من وجود تسكين نشط داخل المزرعة بعد العودة', 'value' => 'derive_presence_from_active_housing'],
                    ],
                ],
                [
                    'seed_key' => 'animal_reentry.episode_history_model',
                    'title' => 'كيف يجب الحفاظ على تاريخ الحيوان إذا خرج وعاد إلى المزرعة أكثر من مرة خلال حياته؟',
                    'help_text' => 'المعمارية المعتمدة تشترط الحفاظ على الهوية. المطلوب منع إعادة فتح أو مسح واقعة خروج قديمة بطريقة تفقد التاريخ، مع دعم أكثر من فترة وجود داخل المزرعة إذا حدث ذلك فعليًا.',
                    'type' => QuestionType::SINGLE_CHOICE,
                    'is_required' => true,
                    'sort_order' => 11,
                    'report_category' => 'history_rule',
                    'target_entity' => 'animal_presence_episode',
                    'options' => [
                        ['label' => 'كل خروج وإعادة دخول ينشئان Episode تاريخية جديدة مع بقاء جميع الفترات السابقة كما هي', 'value' => 'append_only_presence_episodes'],
                        ['label' => 'يوجد Presence Record مستمر يعاد فتحه عند العودة مع Audit كامل لفترات الخروج', 'value' => 'reopen_single_presence_record_with_full_audit'],
                        ['label' => 'تخزن حالة الوجود الحالية فقط ويستنتج التاريخ من أحداث الخروج والدخول دون Presence Episodes مستقلة', 'value' => 'current_presence_derived_from_exit_reentry_events'],
                    ],
                ],
            ];

            app(QuestionSeederSyncService::class)->sync(
                mainSectionName: 'الحركات ودورة التشغيل الفعلية',
                sectionName: 'الخروج من المزرعة وإعادة الدخول',
                questions: $questions,
                prune: true,
                preserveAnswers: true,
            );
        });
    }
}
